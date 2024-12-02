<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class TransactionService
{
    protected string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = config('api.base_url');
    }

    public function index(array $params, string $token): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/transaction/list", $params);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('API Transaction List Request Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @throws RequestException
     * @throws ConnectionException
     */
    public function getTransaction(string $transactionId, string $token): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/transaction", [
                'transactionId' => $transactionId,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (RequestException $e) {
            Log::error('API Transaction Request Failed: ' . $e->getMessage());

            throw $e;
        }
    }

    public function getMerchant(string $transactionId, string $token): ?array
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
                'Content-Type' => 'application/json',
            ])->post("{$this->apiUrl}/client", [
                'transactionId' => $transactionId,
            ]);

            if ($response->successful()) {
                return $response->json()['customerInfo'] ?? null;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('API Merchant Request Failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @param array $params
     * @param string $token
     * @return array|null
     * @throws Exception
     */
    public function getReport(array $params, string $token): ?array
    {
        $params['fromDate'] = $params['fromDate'] ?? now()->subYears(30)->format('Y-m-d');
        $params['toDate'] = $params['toDate'] ?? now()->format('Y-m-d');

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post("{$this->apiUrl}/transactions/report", $params);

            if ($response->successful()) {
                return $response->json();
            }

            throw new \Exception('Failed to fetch the transaction report.');
        } catch (\Exception $e) {
            throw new \Exception('An error occurred while fetching the report: ' . $e->getMessage());
        }
    }
}
