<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\TransactionController;
use App\Http\Requests\Transaction\IndexRequest;
use App\Services\TransactionService;
use Tests\TestCase;
use Mockery;

class TransactionControllerTest extends TestCase
{
    protected TransactionController $controller;
    protected TransactionService $transactionService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->transactionService = Mockery::mock(TransactionService::class);
        $this->controller = new TransactionController($this->transactionService);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_index_returns_view_with_transactions_when_successful()
    {
        $params = ['page' => 1];
        $token = 'test-token';

        session(['api_token' => $token]);

        $apiResponse = [
            'data' => [
                ['id' => 1, 'amount' => 100],
            ],
            'current_page' => 1,
            'per_page' => 50,
            'next_page_url' => null,
            'prev_page_url' => null,
            'from' => 1,
            'to' => 50,
        ];

        $this->transactionService
            ->shouldReceive('index')
            ->with($params, $token)
            ->once()
            ->andReturn($apiResponse);

        $request = Mockery::mock(IndexRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($params);

        $response = $this->controller->index($request);

        $this->assertEquals('transactions.index', $response->name());
        $this->assertArrayHasKey('transactions', $response->getData());
        $this->assertArrayHasKey('pagination', $response->getData());
    }

    public function test_index_returns_error_view_when_api_fails()
    {
        $params = ['page' => 1];
        $token = 'test-token';

        session(['api_token' => $token]);

        $this->transactionService
            ->shouldReceive('index')
            ->with($params, $token)
            ->once()
            ->andReturn(null);

        $request = Mockery::mock(IndexRequest::class);
        $request->shouldReceive('validated')
            ->once()
            ->andReturn($params);

        $response = $this->controller->index($request);

        $this->assertEquals('transactions.index', $response->name());
        $this->assertEmpty($response->getData()['transactions']);
        $this->assertNull($response->getData()['pagination']);
    }

    public function test_get_transaction_returns_transaction_details_when_successful()
    {
        $token = 'test-token';
        $transactionId = '1';
        $transaction = ['id' => 1, 'amount' => 100];

        session(['api_token' => $token]);

        $this->transactionService
            ->shouldReceive('getTransaction')
            ->with($transactionId, $token)
            ->once()
            ->andReturn($transaction);

        $response = $this->controller->getTransaction($transactionId);

        $this->assertEquals('transactions.details', $response->name());
        $this->assertEquals($transaction, $response->getData()['transaction']);
    }

    public function test_get_merchant_returns_merchant_details_when_successful()
    {
        $token = 'test-token';
        $transactionId = '1';
        $merchant = ['id' => 1, 'name' => 'Test Merchant'];

        session(['api_token' => $token]);

        $this->transactionService
            ->shouldReceive('getMerchant')
            ->with($transactionId, $token)
            ->once()
            ->andReturn($merchant);

        $response = $this->controller->getMerchant($transactionId);

        $this->assertEquals('transactions.merchant', $response->name());
        $this->assertEquals($merchant, $response->getData()['merchant']);
    }

    public function test_report_returns_report_view_when_successful()
    {
        $token = 'test-token';
        $params = [
            'fromDate' => '2024-01-01',
            'toDate' => '2024-12-31',
            'merchant' => '1',
            'acquirer' => '2'
        ];

        session(['api_token' => $token]);

        $report = ['total' => 1000, 'count' => 10];

        $this->transactionService
            ->shouldReceive('getReport')
            ->with($params, $token)
            ->once()
            ->andReturn($report);

        $request = request()->merge($params);

        $response = $this->controller->report($request);

        $this->assertEquals('transactions.report', $response->name());
        $this->assertEquals($report, $response->getData()['report']);
        $this->assertEquals($params, $response->getData()['params']);
    }
}
