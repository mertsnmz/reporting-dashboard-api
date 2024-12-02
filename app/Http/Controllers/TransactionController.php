<?php

namespace App\Http\Controllers;

use App\Http\Requests\Transaction\IndexRequest;
use App\Services\TransactionService;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(IndexRequest $request): Factory|View|Application
    {
        $token = session('api_token');
        $params = $request->validated();

        $response = $this->transactionService->index($params, $token);

        if ($response) {
            $transactions = $response['data'] ?? [];
            $pagination = [
                'current_page' => $response['current_page'] ?? 1,
                'per_page' => $response['per_page'] ?? 50,
                'next_page_url' => $response['next_page_url'] ?? null,
                'prev_page_url' => $response['prev_page_url'] ?? null,
                'from' => $response['from'] ?? 1,
                'to' => $response['to'] ?? 50,
            ];

            return view('transactions.index', compact('transactions', 'pagination', 'params'));
        }

        return view('transactions.index', [
            'transactions' => [],
            'pagination' => null,
            'params' => $params,
        ])->withErrors(['error' => 'Failed to fetch transactions.']);
    }

    public function getTransaction(string $transactionId): Application|Factory|View|RedirectResponse
    {
        $token = session('api_token');
        try {
            $transaction = $this->transactionService->getTransaction($transactionId, $token);

            if ($transaction) {
                return view('transactions.details', compact('transaction'));
            } else {
                return back()->withErrors(['error' => 'Failed to retrieve the transaction.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while retrieving the transaction.']);
        }
    }

    public function getMerchant(string $transactionId): Application|Factory|View|RedirectResponse
    {
        $token = session('api_token');

        try {
            $merchant = $this->transactionService->getMerchant($transactionId, $token);

            if ($merchant) {
                return view('transactions.merchant', compact('merchant'));
            } else {
                return back()->withErrors(['error' => 'Failed to retrieve merchant details.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while retrieving the merchant details.']);
        }
    }

    public function report(Request $request): Application|Factory|View|RedirectResponse
    {
        $token = session('api_token');
        $params = $request->only(['fromDate', 'toDate', 'merchant', 'acquirer']);

        try {
            $report = $this->transactionService->getReport($params, $token);
            return view('transactions.report', compact('report', 'params'));
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
