<?php

namespace Tests\Unit\Services;

use App\Services\AuthService;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    protected AuthService $authService;
    protected string $baseUrl = 'http://api.test';
    protected string $email = 'test@example.com';
    protected string $password = 'password123';

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('api.base_url', $this->baseUrl);
        $this->authService = new AuthService();
    }

    public function test_login_returns_token_when_successful()
    {
        $expectedToken = 'test-token-123';

        Http::fake([
            "{$this->baseUrl}/merchant/user/login" => Http::response([
                'token' => $expectedToken
            ], 200)
        ]);

        $token = $this->authService->login($this->email, $this->password);

        $this->assertEquals($expectedToken, $token);
        $this->assertEquals($expectedToken, session('api_token'));
        $this->assertEquals($this->email, session('email'));
        $this->assertEquals($this->password, session('password'));
        $this->assertNotNull(session('token_expiry'));
    }

    public function test_login_returns_null_when_credentials_invalid()
    {
        Http::fake([
            "{$this->baseUrl}/merchant/user/login" => Http::response([
                'message' => 'Invalid credentials'
            ], 401)
        ]);

        $token = $this->authService->login($this->email, $this->password);

        $this->assertNull($token);
        $this->assertNull(session('api_token'));
    }

    public function test_login_returns_null_on_server_error()
    {
        Http::fake([
            "{$this->baseUrl}/merchant/user/login" => Http::response([
                'message' => 'Server error'
            ], 500)
        ]);

        $result = $this->authService->login($this->email, $this->password);
        $this->assertNull($result);
    }

    public function test_refresh_token_returns_true_when_successful()
    {
        $newToken = 'new-test-token-123';

        Http::fake([
            "{$this->baseUrl}/merchant/user/login" => Http::response([
                'token' => $newToken
            ], 200)
        ]);

        $result = $this->authService->refreshToken($this->email, $this->password);

        $this->assertTrue($result);
        $this->assertEquals($newToken, session('api_token'));
        $this->assertNotNull(session('token_expiry'));
    }

    public function test_refresh_token_returns_false_when_failed()
    {
        Http::fake([
            "{$this->baseUrl}/merchant/user/login" => Http::response([
                'message' => 'Invalid credentials'
            ], 401)
        ]);

        $result = $this->authService->refreshToken($this->email, $this->password);

        $this->assertFalse($result);
    }

    public function test_logout_clears_session()
    {
        Session::put([
            'api_token' => 'test-token',
            'token_expiry' => now(),
            'email' => $this->email,
            'password' => $this->password
        ]);

        $this->authService->logout();

        $this->assertNull(session('api_token'));
        $this->assertNull(session('token_expiry'));
        $this->assertNull(session('email'));
        $this->assertNull(session('password'));
    }
}
