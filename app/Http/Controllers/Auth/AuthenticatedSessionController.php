<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthenticatedSessionController extends LoginController
{
    /**
     * Display the login view.
     */
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        return $this->login($request);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {

        if (!$request->wantsJson())
            Auth::guard('web')->logout();
        else
            Auth::guard('api')->user()->currentAccessToken()->delete();

        if ($request->wantsJson()) {
            return $this->api_response('Logout Successful', [], 200);
        }

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
