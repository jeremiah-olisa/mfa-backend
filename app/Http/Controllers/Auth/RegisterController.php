<?php

namespace App\Http\Controllers\Auth;

use App\Constants\SetupConstant;
use App\Http\Controllers\Controller;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
            'app' => ['required', 'string', 'in:' . implode(',', SetupConstant::$apps)],
            'role' => ['required', 'string', 'in:' . implode(',', SetupConstant::$roles)],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        return $this->userRepository->create($data);
    }

    public function register(Request $request)
    {
        $requestData = $this->validator($request->all())->validate();

        // Check if the user already exists
        $user = $this->userRepository->findByEmail($requestData['email']);
        
        if ($user) {
            // User exists, check if they are registered for the app
            $app = $requestData['app'] ?? null; // Ensure the app is in the request data
            
            if ($app && !$user->userApps()->where('app', $app)->exists()) {
                // Attach the app to the user
                $user->userApps()->create(['app' => $app]);
            }
            
            // Log in the existing user
            $this->guard()->login($user);
            
            return $request->wantsJson()
            ? new JsonResponse(['message' => 'User registered for the app and logged in.'], 200)
            : redirect($this->redirectPath());
        }
        
        // If user does not exist, proceed with the normal registration flow
        event(new Registered($user = $this->create($requestData)));
    
        // Attach the app to the newly registered user
        $app = $requestData['app'] ?? null;
        if ($app) {
            $user->userApps()->create(['app' => $app]);
        }

        $this->guard()->login($user);

        if ($response = $this->registered($request, $user)) {
            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 201)
            : redirect($this->redirectPath());
    }
}
