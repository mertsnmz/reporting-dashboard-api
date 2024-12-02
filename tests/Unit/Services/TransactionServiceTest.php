<?php

namespace Tests\Unit\Services;

use App\Services\TransactionService;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    protected TransactionService $transactionService;
    protected string $baseUrl = 'http://api.test';
    protected string $token = 'test-token';

    protected function setUp(): void
    {
        parent::setUp();

        Config::set('api.base_url', $this->baseUrl);
        $this->transactionService = new TransactionService();
    }

    public function test_index_returns_transaction_list_when_successful()
    {
        $expectedResponse = [
            'data' => [
                ['id' => 1, 'amount' => 100],
                ['id' => 2, 'amount' => 200],
            ],
            'current_page' => 1,
            'per_page' => 50,
        ];

        Http::fake([
            "{$this->baseUrl}/transaction/list" => Http::response($expectedResponse, 200)
        ]);

        $params = ['page' => 1];
        $result = $this->transactionService->index($params, $this->token);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_index_returns_null_when_api_call_fails()
    {
        Http::fake([
            "{$this->baseUrl}/transaction/list" => Http::response(null, 500)
        ]);

        $params = ['page' => 1];
        $result = $this->transactionService->index($params, $this->token);

        $this->assertNull($result);
    }

    public function test_get_transaction_returns_transaction_details_when_successful()
    {
        $expectedResponse = [
            'id' => 1,
            'amount' => 100,
            'status' => 'completed'
        ];

        Http::fake([
            "{$this->baseUrl}/transaction" => Http::response($expectedResponse, 200)
        ]);

        $result = $this->transactionService->getTransaction('1', $this->token);

        $this->assertEquals($expectedResponse, $result);
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function test_get_transaction_returns_null_when_request_fails()
    {
        Http::fake([
            "{$this->baseUrl}/transaction" => Http::response(null, 500)
        ]);

        $result = $this->transactionService->getTransaction('1', $this->token);
        $this->assertNull($result);
    }

    public function test_get_merchant_returns_merchant_details_when_successful()
    {
        $apiResponse = [
            'customerInfo' => [
                'id' => 1,
                'name' => 'Test Merchant'
            ]
        ];

        Http::fake([
            "{$this->baseUrl}/client" => Http::response($apiResponse, 200)
        ]);

        $result = $this->transactionService->getMerchant('1', $this->token);

        $this->assertEquals($apiResponse['customerInfo'], $result);
    }

    public function test_get_merchant_returns_null_when_api_call_fails()
    {
        Http::fake([
            "{$this->baseUrl}/client" => Http::response(null, 500)
        ]);

        $result = $this->transactionService->getMerchant('1', $this->token);

        $this->assertNull($result);
    }

    public function test_get_report_returns_report_data_when_successful()
    {
        $expectedResponse = [
            'total' => 1000,
            'count' => 10
        ];

        Http::fake([
            "{$this->baseUrl}/transactions/report" => Http::response($expectedResponse, 200)
        ]);

        $params = [
            'fromDate' => '2024-01-01',
            'toDate' => '2024-12-31'
        ];

        $result = $this->transactionService->getReport($params, $this->token);

        $this->assertEquals($expectedResponse, $result);
    }

    public function test_get_report_throws_exception_when_api_call_fails()
    {
        Http::fake([
            "{$this->baseUrl}/transactions/report" => Http::response(null, 500)
        ]);

        $this->expectException(\Exception::class);

        $params = [
            'fromDate' => '2024-01-01',
            'toDate' => '2024-12-31'
        ];

        $this->transactionService->getReport($params, $this->token);
    }
}
