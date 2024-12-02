<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function showLoginForm(): Application|Factory|View
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        try {
            $token = $this->authService->login($request->input('email'), $request->input('password'));

            if ($token) {
                session(['api_token' => $token]);
                return redirect()->route('dashboard');
            } else {
                return back()->withErrors(['error' => 'Login failed. Please check your information.']);
            }
        } catch (\Exception $e) {
            Log::error('Login Failed: ' . $e->getMessage());

            return back()->withErrors(['error' => 'An error occurred while logging in. Please try again later.']);
        }
    }

    public function logout(): RedirectResponse
    {
        $this->authService->logout();
        return redirect()->route('login');
    }
}
