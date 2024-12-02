@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-full max-w-md p-8 space-y-6 bg-white rounded shadow">
            <h2 class="text-2xl font-bold text-center">Login</h2>

            @if($errors->any())
                <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block mb-2 text-sm font-medium text-gray-700">E-mail</label>
                    <input type="email" name="email" id="email" required
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                           placeholder="E-mail">
                </div>
                <div class="mb-6">
                    <label for="password" class="block mb-2 text-sm font-medium text-gray-700">Password</label>
                    <input type="password" name="password" id="password" required
                           class="w-full px-3 py-2 border rounded focus:outline-none focus:ring focus:border-blue-300"
                           placeholder="Password">
                </div>
                <div>
                    <button type="submit"
                            class="w-full px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
