<?php

namespace App\Http\Controllers\Auth;

use App\Constants\SetupConstant;
use App\Http\Controllers\Controller;
use App\Repositories\ReferralRepository;
use App\Repositories\UserRepository;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';
    protected UserRepository $userRepository;
    protected ReferralRepository $referralRepository;

    private $API_ERRORS = [
        '400' => 'Bad request. Please try again.',
        '401' => 'Unauthorized. Please log in.',
        '404' => 'Not found. Please check the URL.',
        '500' => 'Internal server error. Please try again later.',
        'NO_INTERNET' => 'No response from server. Please check your internet connection.',
        '_' => 'An error occurred. Please try again.',
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, ReferralRepository $referralRepository)
    {
        $this->userRepository = $userRepository;
        $this->referralRepository = $referralRepository;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'role' => ['required', 'string', 'in:' . implode(',', SetupConstant::$roles)],
            'phone' => ['required', 'string', 'regex:/^(070|080|081|090|091)\d{8}$/'], // Match specific prefixes and 11 digits
            'device_id' => ['required', 'string', 'min:5'],
            'parent_phone' => ['nullable', 'string', 'regex:/^(070|080|081|090|091)\d{8}$/'], // Optional field with the same pattern
            'app' => ['nullable', 'string', 'in:' . implode(',', SetupConstant::$apps)],
            'referral_code' => ['nullable', 'string', 'min:5'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $referral_code = $data["referral_code"] ?? null;
        unset($data["referral_code"]);

        $user = null;
        DB::transaction(function () use ($data, $referral_code, &$user) {

            $user = $this->userRepository->create($data);

            if (!empty($referral_code)) {
                $referral = $this->userRepository->getUserByReferralCode($referral_code);
                $this->referralRepository->createReferral($referral->id, $user->id, $referral_code);
            }

            $user->profile()->updateOrCreate([], [
                'phone' => $data['phone'] ?? null,
                'parent_phone' => $data['parent_phone'] ?? null,
            ]);

            // Attach the app to the newly registered user
            $app = $data['app'] ?? null;
            if ($app) {
                $user->addAppToUser($app);
            }
        });

        $data["guardian"] = $data["parent_phone"] ?? null;
        $data["guardian"] = $data["parent_phone"] ?? null;
        unset($data["parent_phone"]);

        $this->registerOnOauthServer($data);

        return $user;
    }

    public function register(Request $request)
    {
        $requestData = $this->validator($request->all())->validate();

        // Check if the user already exists
        $user = $this->userRepository->findByEmail($requestData['email']);
        DB::beginTransaction();

        try {
            if ($user) {
                // User exists, check if they are registered for the app
                $app = $requestData['app'] ?? null; // Ensure the app is in the request data

                // Attach the app to the user
                $user->addAppToUser($app);

                // Update UserProfile if phone or parent_phone is provided
                if (!empty($requestData['phone']) || !empty($requestData['parent_phone'])) {
                    $user->profile()->updateOrCreate([], [
                        'phone' => $requestData['phone'] ?? null,
                        'parent_phone' => $requestData['parent_phone'] ?? null,
                    ]);
                }

                // Log in the existing user
                $this->guard()->login($user);

                return $request->wantsJson()
                    ? new JsonResponse(['message' => 'User registered for the app and logged in.'], 200)
                    : redirect($this->redirectPath());
            }

            // If user does not exist, proceed with the normal registration flow
            event(new Registered($user = $this->create($requestData)));

            DB::commit(); // Commit the transaction
        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on failure
            throw $e; // Re-throw the exception for further handling/logging
        }
        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }


        return $request->wantsJson()
            ? $this->api_response('Registration successful', null, 201) :
            redirect($this->redirectPath());
    }

    public function registerOnOauthServer(array $data)
    {
        $deviceId = 123456; // Logic to get device ID;
        $formData = array_merge($data, ['deviceID' => $deviceId]);
        $BASE_URL = SetupConstant::$oAuthBaseUrls[$data['app']];
        try {
            $response = Http::asMultipart()
                ->withHeaders([
                    'Accept' => '*/*',
                    'Content-Type' => 'multipart/form-data',
                ])
                ->post($BASE_URL . '/register/validationMobile', $formData);

            $responseData = $response->json();
            return $this->handleValidationResponse($responseData);
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }
    }

    private function handleValidationResponse($responseData)
    {
        if ($responseData['message'] === 'Success' || $responseData['message'] == '0') {
            return $responseData;
        } else {
            // Handle specific validation errors
            throw new BadRequestHttpException($responseData['message']);
        }
    }

    private function handleException(\Exception|Throwable|HttpException $exception)
    {
        $errorCode = $exception->getCode();
        $message = isset($this->API_ERRORS[$errorCode]) ? $this->API_ERRORS[$errorCode] : $this->API_ERRORS['_'];
        $statusCode = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;

        return response()->json(['error' => $message], $statusCode);
    }
}
