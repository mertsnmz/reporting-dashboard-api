<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\AuthController;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\AuthService;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;
use Mockery;

class AuthControllerTest extends TestCase
{
    protected AuthController $controller;
    protected AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = Mockery::mock(AuthService::class);
        $this->controller = new AuthController($this->authService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_show_login_form_returns_correct_view()
    {
        $response = $this->controller->showLoginForm();

        $this->assertEquals('auth.login', $response->name());
    }

    public function test_login_redirects_to_dashboard_when_successful()
    {
        $email = 'test@example.com';
        $password = 'password123';
        $token = 'test-token-123';

        $request = Mockery::mock(LoginRequest::class);
        $request->shouldReceive('input')
            ->with('email')
            ->andReturn($email);
        $request->shouldReceive('input')
            ->with('password')
            ->andReturn($password);

        $this->authService
            ->shouldReceive('login')
            ->with($email, $password)
            ->once()
            ->andReturn($token);

        $response = $this->controller->login($request);

        $this->assertEquals(route('dashboard'), $response->getTargetUrl());
        $this->assertEquals($token, session('api_token'));
    }

    public function test_login_returns_to_login_page_with_error_when_credentials_invalid()
    {
        $email = 'test@example.com';
        $password = 'wrong-password';

        $request = Mockery::mock(LoginRequest::class);
        $request->shouldReceive('input')
            ->with('email')
            ->andReturn($email);
        $request->shouldReceive('input')
            ->with('password')
            ->andReturn($password);

        $this->authService
            ->shouldReceive('login')
            ->with($email, $password)
            ->once()
            ->andReturn(null);

        $response = $this->controller->login($request);

        $this->assertTrue($response->isRedirect());
        $this->assertArrayHasKey('error', session('errors')->getBag('default')->toArray());
    }

    public function test_login_returns_to_login_page_with_error_when_exception_occurs()
    {
        $email = 'test@example.com';
        $password = 'password123';

        $request = Mockery::mock(LoginRequest::class);
        $request->shouldReceive('input')
            ->with('email')
            ->andReturn($email);
        $request->shouldReceive('input')
            ->with('password')
            ->andReturn($password);

        $this->authService
            ->shouldReceive('login')
            ->with($email, $password)
            ->once()
            ->andThrow(new \Exception('Server error'));

        $response = $this->controller->login($request);

        $this->assertTrue($response->isRedirect());
        $this->assertArrayHasKey('error', session('errors')->getBag('default')->toArray());
    }

    public function test_logout_redirects_to_login_page()
    {
        $this->authService
            ->shouldReceive('logout')
            ->once();

        $response = $this->controller->logout();

        $this->assertEquals(route('login'), $response->getTargetUrl());
    }
}
