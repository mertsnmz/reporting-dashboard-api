@extends('layouts.app')

@section('title', 'Transactions')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Transactions</h1>

        <form method="GET" action="{{ route('transactions.index') }}" class="mb-6 bg-gray-100 p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @php
                    $fromDate = request()->input('fromDate', $params['fromDate'] ?? now()->subYears(30)->format('Y-m-d'));
                    $toDate = request()->input('toDate', $params['toDate'] ?? now()->format('Y-m-d'));
                @endphp
                <div>
                    <label for="fromDate" class="block text-gray-700">From Date</label>
                    <input type="date" id="fromDate" name="fromDate"
                           value="{{ $fromDate }}"
                           class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="toDate" class="block text-gray-700">To Date</label>
                    <input type="date" id="toDate" name="toDate"
                           value="{{ $toDate }}"
                           class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="status" class="block text-gray-700">Status</label>
                    <select id="status" name="status" class="w-full border border-gray-300 rounded p-2">
                        <option value="">All</option>
                        <option value="APPROVED" {{ request('status') == 'APPROVED' ? 'selected' : '' }}>Approved</option>
                        <option value="WAITING" {{ request('status') == 'WAITING' ? 'selected' : '' }}>Waiting</option>
                        <option value="DECLINED" {{ request('status') == 'DECLINED' ? 'selected' : '' }}>Declined</option>
                        <option value="ERROR" {{ request('status') == 'ERROR' ? 'selected' : '' }}>Error</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded mt-6">Apply Filters</button>
                </div>
            </div>
        </form>

        <div class="overflow-x-auto bg-white p-6 rounded-lg shadow">
            <table class="min-w-full table-auto">
                <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2">Transaction ID</th>
                    <th class="px-4 py-2">Merchant</th>
                    <th class="px-4 py-2">Amount</th>
                    <th class="px-4 py-2">Currency</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Created At</th>
                </tr>
                </thead>
                <tbody>
                @forelse($transactions as $transaction)
                    <tr>
                        <td class="border px-4 py-2">
                            <a href="{{ route('transactions.getTransaction', ['transactionId' => $transaction['transaction']['merchant']['transactionId']]) }}"
                               class="text-blue-500 hover:underline">
                                {{ $transaction['transaction']['merchant']['transactionId'] }}
                            </a>
                        </td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('transactions.getMerchant', ['transactionId' => $transaction['transaction']['merchant']['transactionId']]) }}"
                               class="text-blue-500 hover:underline">
                                {{ $transaction['merchant']['name'] }}
                            </a>
                        </td>
                        <td class="border px-4 py-2">{{ $transaction['fx']['merchant']['originalAmount'] }}</td>
                        <td class="border px-4 py-2">{{ $transaction['fx']['merchant']['originalCurrency'] }}</td>
                        <td class="border px-4 py-2">{{ $transaction['transaction']['merchant']['status'] }}</td>
                        <td class="border px-4 py-2">{{ $transaction['transaction']['merchant']['created_at'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-gray-500">No transactions found for the selected filters.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if($pagination)
            <div class="mt-6 flex justify-between items-center">
                @if($pagination['prev_page_url'])
                    <a href="{{ route('transactions.index', array_merge(request()->all(), ['page' => $pagination['current_page'] - 1])) }}"
                       class="px-4 py-2 bg-gray-300 rounded">Previous</a>
                @else
                    <span class="px-4 py-2 text-gray-400">Previous</span>
                @endif

                <span>Page {{ $pagination['current_page'] }}</span>

                @if($pagination['next_page_url'])
                    <a href="{{ route('transactions.index', array_merge(request()->all(), ['page' => $pagination['current_page'] + 1])) }}"
                       class="px-4 py-2 bg-gray-300 rounded">Next</a>
                @else
                    <span class="px-4 py-2 text-gray-400">Next</span>
                @endif
            </div>
        @endif
    </div>
@endsection
