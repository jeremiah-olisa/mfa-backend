<?php

namespace App\Http\Controllers\Auth;

use App\Constants\SetupConstant;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';
    protected UserRepository $userRepository;
    // protected UserAppRepository $userAppRepository;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        // $this->middleware('guest')->except('logout');
        // $this->middleware('auth')->only('logout');
        $this->userRepository = $userRepository;
        // $this->userAppRepository = $userAppRepository;
    }

    protected function validateLogin(Request $request): array
    {
        return $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string|min:6',
            'app' => 'required|string|in:' . implode(',', SetupConstant::$apps),
        ]);
    }

    public function login(Request $request)
    {
        // Validate login request
        $validatedRes = $this->validateLogin($request);

        // Begin database transaction
        DB::beginTransaction();

        try {
            // Check if the user exists locally
            $user = $this->userRepository->findByEmail($request->input('email'), ['role'], ['userApps']);

            if (!$user) {
                // Fetch student details from the OAuth endpoint
                $studentDetails = $this->fetchStudentDetails($request->input('email'));

                // Register the user locally
                $validatedRes['name'] = trim(
                    ($studentDetails['firstname'] ?? '') . ' ' . ($studentDetails['lastname'] ?? '')
                ) ?: $validatedRes[$this->username()];

                $user = $this->userRepository->create($validatedRes);

                // Create a user profile
                $user->profile()->create([
                    'phone' => $studentDetails['phone'] ?? null,
                    'parent_email' => $studentDetails['parent_email'] ?? null,
                    'parent_phone' => $studentDetails['parent_phone'] ?? null,
                    'plan' => $studentDetails['plan'] ?? null,
                    'plan_duration' => isset($studentDetails['plan_duration'])
                        ? (int)filter_var($studentDetails['plan_duration'], FILTER_VALIDATE_INT)
                        : null,
                    'plan_started_at' => $studentDetails['plan_started_at'] ?? null,
                    'plan_expires_at' => $studentDetails['plan_expires_at'] ?? null,
                ]);
            }

            // Validate and associate the app with the user
            $app = $request->input('app');
            if (!$user->userApps()->where('app', $app)->exists()) {
                $user->userApps()->create(['app' => $app]);
            }

            DB::commit(); // Commit the transaction
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on failure
            throw $e; // Re-throw the exception for further handling/logging
        }


        // Handle rate-limiting for failed login attempts
        if (
            method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)
        ) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        // Validate user role and app association
        if ($user->role !== 'Admin') {
            $userApps = $user->userApps()->pluck('app')->toArray();

            if (!in_array($app, $userApps)) {
                throw ValidationException::withMessages([
                    $this->username() => ['Invalid App User Credentials'],
                ]);
            }
        }

        // Attempt login
        if ($this->attemptLogin($request)) {
            if ($request->hasSession() && !$request->wantsJson())
                $request->session()->put('auth.password_confirmed_at', time());


            return $this->sendLoginResponse($request);
        }

        // Increment login attempts for failed login
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }


    public function fetchStudentDetails(string $value, string $name = 'email')
    {
        $response = $response = Http::attach($name, $value)
            ->withHeaders([
                'Accept' => '*/*',
            ])
            ->post('https://myfirstattempt.com/login/fetch_studentMobile');

        $studentDetails = json_decode($response->body(), true);

        if (!$studentDetails) {
            throw ValidationException::withMessages([
                'oauth' => 'User not found',
            ]);
        }

        return $studentDetails;
    }

    protected function sendLoginResponse(Request $request)
    {
        if (!$request->wantsJson())
            $request->session()->regenerate();

        $this->clearLoginAttempts($request);

        $user = $this->guard($request->wantsJson() ? 'api' : 'web')->user();


        if (!$request->wantsJson())
            return redirect()->intended($this->redirectPath());


        // Split the name into first and last names
        [$firstname, $lastname] = $this->splitName($user->name);

        // Generate a token
        $token = $user->createToken($user->name . 'API Token')->plainTextToken;

        $responseData = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'phone' => $user->profile->phone ?? null,
            'plan' => $user->profile->plan ?? null,
            'plan_duration' => $user->profile->plan_duration ?? null,
            'student_status' => $user->student_status ?? '1', // Default to '1' if not set
            'last_login' => $user->last_login ?? now()->toDateTimeString(),
            'deviceID' => $user->device_id ?? null,
            'parent_email' => $user->profile->parent_email ?? null,
            'parent_phone' => $user->profile->parent_phone ?? null,
            'token' => $token,
        ];

        if ($response = $this->authenticated($request, $user)) {
            return $response;
        }

        return $this->api_response('Login Successful', ['data' => $responseData], 200);
    }


    protected function guard(string $guard)
    {
        return Auth::guard($guard);
    }

    protected function attemptLogin(Request $request)
    {
        $cred = $this->credentials($request);
        if (!$request->wantsJson())
            return $this->guard('web')->attempt(
                $cred,
                $request->boolean('remember')
            );

        $user = $this->userRepository->findByEmail($cred[$this->username()]);


        if ($user && Hash::check($cred['password'], $user->password))
            return Auth::loginUsingId($user->id, $request->boolean('remember'));


        $message = 'The provided credentials are incorrect.';
        throw new UnauthorizedHttpException("Bearer", $message);
    }

    /**
     * Split a full name into first and last name.
     *
     * @param string $name
     * @return array
     */
    private function splitName(string $name): array
    {
        $parts = explode(' ', $name, 2); // Split into two parts
        $firstname = $parts[0] ?? null;
        $lastname = $parts[1] ?? null; // Null if there's no last name
        return [$firstname, $lastname];
    }
}
