<?php

namespace App\Http\Controllers;

use App\Services\TransactionService;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index(): Factory|View|Application|RedirectResponse
    {
        return view('dashboard.index');
    }
}
