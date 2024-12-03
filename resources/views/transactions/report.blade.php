@extends('layouts.app')

@section('title', 'Transaction Report')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Transaction Report</h1>

        <form method="GET" action="{{ route('transactions.report') }}" class="mb-6 bg-gray-100 p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="fromDate" class="block text-gray-700">From Date</label>
                    <input type="date" id="fromDate" name="fromDate"
                           value="{{ $params['fromDate'] ?? now()->subYears(30)->format('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <label for="toDate" class="block text-gray-700">To Date</label>
                    <input type="date" id="toDate" name="toDate"
                           value="{{ $params['toDate'] ?? now()->format('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded p-2">
                </div>
                <div>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded mt-6">Generate Report</button>
                </div>
            </div>
        </form>

        @if(isset($report['response']))
            <div class="bg-white p-6 rounded-lg shadow">
                <h2 class="text-xl font-bold mb-4">Transaction Report Summary</h2>
                <table class="min-w-full table-auto">
                    <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2">Currency</th>
                        <th class="px-4 py-2">Transaction Count</th>
                        <th class="px-4 py-2">Total Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($report['response'] as $item)
                        <tr>
                            <td class="border px-4 py-2">{{ $item['currency'] }}</td>
                            <td class="border px-4 py-2">{{ $item['count'] }}</td>
                            <td class="border px-4 py-2">{{ number_format($item['total'], 2) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500">No data available for the selected date range.</p>
        @endif
    </div>
@endsection
