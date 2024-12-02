<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login'])->name('login.post');

Route::middleware('auth.api')->group(function () {
    Route::prefix('transactions')->group(function () {
        Route::get('report', [TransactionController::class, 'report'])->name('transactions.report');
        Route::get('{transactionId}/merchant', [TransactionController::class, 'getMerchant'])->name('transactions.getMerchant');
        Route::get('{transactionId}', [TransactionController::class, 'getTransaction'])->name('transactions.getTransaction');
        Route::get('/', [TransactionController::class, 'index'])->name('transactions.index');
    });

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});
