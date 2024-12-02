<?php

namespace App\Http\Middleware;

use App\Services\AuthService;
use Closure;
use Illuminate\Http\Request;

class ApiAuth
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function handle(Request $request, Closure $next)
    {
        if (!$request->session()->has('api_token') || !$request->session()->has('email') || !$request->session()->has('password')) {
            return redirect()->route('login');
        }

        if ($request->session()->has('token_expiry') && now()->greaterThan($request->session()->get('token_expiry'))) {
            $this->authService->refreshToken(
                $request->session()->get('email'),
                $request->session()->get('password')
            );
        }

        return $next($request);
    }
}
