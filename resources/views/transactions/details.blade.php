@extends('layouts.app')

@section('title', 'Transaction Details')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Transaction Details</h1>

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Transaction ID: {{ $transaction['transaction']['merchant']['transactionId'] }}</h2>
            <p><strong>Status:</strong> {{ $transaction['transaction']['merchant']['status'] }}</p>
            <p><strong>Reference No:</strong> {{ $transaction['transaction']['merchant']['referenceNo'] }}</p>
            <p><strong>Type:</strong> {{ $transaction['transaction']['merchant']['type'] }}</p>
            <p><strong>Operation:</strong> {{ $transaction['transaction']['merchant']['operation'] }}</p>
            <p><strong>Channel:</strong> {{ $transaction['transaction']['merchant']['channel'] }}</p>
            <p><strong>Message:</strong> {{ $transaction['transaction']['merchant']['message'] }}</p>
            <p><strong>Created At:</strong> {{ $transaction['transaction']['merchant']['created_at'] }}</p>
            <p><strong>Updated At:</strong> {{ $transaction['transaction']['merchant']['updated_at'] }}</p>
        </div>

        <div class="bg-gray-100 p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Merchant Information</h2>
            <p><strong>Name:</strong> {{ $transaction['merchant']['name'] }}</p>
            <p><strong>Original Amount:</strong> {{ $transaction['fx']['merchant']['originalAmount'] }}</p>
            <p><strong>Original Currency:</strong> {{ $transaction['fx']['merchant']['originalCurrency'] }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <h2 class="text-xl font-bold mb-4">Customer Information</h2>
            <p><strong>First Name:</strong> {{ $transaction['customerInfo']['billingFirstName'] }}</p>
            <p><strong>Last Name:</strong> {{ $transaction['customerInfo']['billingLastName'] }}</p>
            <p><strong>Email:</strong> {{ $transaction['customerInfo']['email'] }}</p>
            <p><strong>Company:</strong> {{ $transaction['customerInfo']['billingCompany'] }}</p>
            <p><strong>City:</strong> {{ $transaction['customerInfo']['billingCity'] }}</p>
            <p><strong>Address:</strong> {{ $transaction['customerInfo']['billingAddress1'] ?? 'N/A' }}</p>
        </div>

        <div class="bg-gray-100 p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">Agent Information</h2>
            <p><strong>Agent ID:</strong> {{ $transaction['transaction']['merchant']['agent']['id'] }}</p>
            <p><strong>Customer IP:</strong> {{ $transaction['transaction']['merchant']['agent']['customerIp'] }}</p>
            <p><strong>Customer User Agent:</strong> {{ $transaction['transaction']['merchant']['agent']['customerUserAgent'] }}</p>
            <p><strong>Merchant IP:</strong> {{ $transaction['transaction']['merchant']['agent']['merchantIp'] }}</p>
            <p><strong>Merchant User Agent:</strong> {{ $transaction['transaction']['merchant']['agent']['merchantUserAgent'] }}</p>
            <p><strong>Created At:</strong> {{ $transaction['transaction']['merchant']['agent']['created_at'] }}</p>
            <p><strong>Updated At:</strong> {{ $transaction['transaction']['merchant']['agent']['updated_at'] }}</p>
        </div>

        <div class="mt-6">
            <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Back to Transactions</a>
        </div>
    </div>
@endsection
