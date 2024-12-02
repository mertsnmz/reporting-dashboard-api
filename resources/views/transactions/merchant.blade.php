@extends('layouts.app')

@section('title', 'Merchant Details')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Merchant Details</h1>

        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-xl font-bold mb-4">
                {{ $merchant['billingFirstName'] ?? 'N/A' }} {{ $merchant['billingLastName'] ?? 'N/A' }}
            </h2>
            <p><strong>Company:</strong> {{ $merchant['billingCompany'] ?? 'N/A' }}</p>
            <p><strong>Email:</strong> {{ $merchant['email'] ?? 'N/A' }}</p>
            <p><strong>Phone:</strong> {{ $merchant['billingPhone'] ?? 'N/A' }}</p>
            <p><strong>Billing Address:</strong>
                {{ $merchant['billingAddress1'] ?? 'N/A' }},
                {{ $merchant['billingCity'] ?? 'N/A' }},
                {{ $merchant['billingCountry'] ?? 'N/A' }}
            </p>
            <p><strong>Shipping Address:</strong>
                {{ $merchant['shippingAddress1'] ?? 'N/A' }},
                {{ $merchant['shippingCity'] ?? 'N/A' }},
                {{ $merchant['shippingCountry'] ?? 'N/A' }}
            </p>
            <p><strong>Card Number:</strong> {{ $merchant['number'] ?? 'N/A' }}</p>
            <p><strong>Expiry Date:</strong>
                {{ $merchant['expiryMonth'] ?? 'N/A' }}/{{ $merchant['expiryYear'] ?? 'N/A' }}
            </p>
            <p><strong>Gender:</strong> {{ $merchant['gender'] ?? 'N/A' }}</p>
            <p><strong>Birthday:</strong> {{ $merchant['birthday'] ?? 'N/A' }}</p>
        </div>

        <div class="mt-6">
            <a href="{{ route('transactions.index') }}" class="px-4 py-2 bg-blue-500 text-white rounded">Back to Transactions</a>
        </div>
    </div>
@endsection
