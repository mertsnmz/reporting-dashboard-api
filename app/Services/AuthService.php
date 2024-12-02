<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class AuthService
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    /**
     * @throws Exception
     */
    public function login(string $email, string $password): ?string
    {
        try {
            $response = $this->makeRequest('/merchant/user/login', [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                session([
                    'api_token' => $response->json('token'),
                    'token_expiry' => now()->addMinutes(10),
                    'email' => $email,
                    'password' => $password,
                ]);

                return $response->json('token');
            }

            return null;
        } catch (Exception $e) {
            Log::error('Login failed: ' . $e->getMessage());
            throw $e;
        }
    }

    public function refreshToken(string $email, string $password): bool
    {
        try {
            $response = $this->makeRequest('/merchant/user/login', [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                session([
                    'api_token' => $response->json('token'),
                    'token_expiry' => now()->addMinutes(10),
                ]);

                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Token refresh failed: ' . $e->getMessage());
            return false;
        }
    }

    protected function makeRequest(string $endpoint, array $data): Response
    {
        return Http::post("{$this->apiUrl}{$endpoint}", $data);
    }

    public function logout(): void
    {
        session()->forget(['api_token', 'token_expiry', 'email', 'password']);
    }
}
