@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="bg-gray-800 text-white p-6 rounded-lg shadow-lg">
            <h1 class="text-4xl font-bold">Dashboard</h1>
            <p class="mt-2 text-lg">Welcome!</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
            <a href="{{ route('transactions.index') }}" class="block bg-blue-600 text-white p-6 rounded-lg shadow text-center hover:bg-blue-700 transition">
                <h2 class="text-2xl font-bold">View Transactions <i class="fa-solid fa-arrow-up-right-from-square"></i></h2>
                <p class="mt-2 text-lg">Explore all transaction data</p>
            </a>
            <a href="{{ route('transactions.report') }}" class="block bg-green-600 text-white p-6 rounded-lg shadow text-center hover:bg-green-700 transition">
                <h2 class="text-2xl font-bold">View Reports <i class="fa-solid fa-arrow-up-right-from-square"></i></h2>
                <p class="mt-2 text-lg">Analyze transaction statistics</p>
            </a>
        </div>
    </div>
@endsection
