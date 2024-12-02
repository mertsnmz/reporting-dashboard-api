<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Reporting API Dashboard')</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>
<body class="flex flex-col min-h-screen">
<nav class="bg-gray-800 fixed w-full z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="{{ route('dashboard') }}" class="text-white text-xl font-bold">Reporting API Dashboard</a>
            </div>
            <div class="hidden md:flex space-x-4">
                @if(session()->has('api_token'))
                    <a href="{{ route('dashboard') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                    <div class="relative">
                        <button id="transactions-button" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium focus:outline-none">
                            Transactions
                            <svg class="inline w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.25 8.29a.75.75 0 01-.02-1.06z" clip-rule="evenodd" />
                            </svg>
                        </button>
                        <div id="transactions-dropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg hidden">
                            <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Transaction List</a>
                            <a href="{{ route('transactions.report') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Transaction Report</a>
                        </div>
                    </div>
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">
                        Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Login</a>
                @endif
            </div>
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-300 hover:text-white focus:outline-none">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    <div id="mobile-menu" class="hidden md:hidden">
        @if(session()->has('api_token'))
            <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">Dashboard</a>
            <a href="{{ route('transactions.index') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">Transaction List</a>
            <a href="{{ route('transactions.report') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">Transaction Report</a>
            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">
                Logout
            </a>
        @else
            <a href="{{ route('login') }}" class="block px-4 py-2 text-gray-300 hover:bg-gray-700 hover:text-white">Login</a>
        @endif
    </div>
</nav>

<div class="flex-grow pt-16">
    @yield('content')
</div>

<footer class="bg-gray-800 text-gray-400 py-4">
    <div class="container mx-auto px-4 flex flex-col md:flex-row justify-between items-center">
        <p class="text-sm">&copy; {{ date('Y') }} Reporting API Dashboard. All rights reserved.</p>
    </div>
</footer>

<script>
    const transactionsButton = document.getElementById('transactions-button');
    const transactionsDropdown = document.getElementById('transactions-dropdown');

    if (transactionsButton) {
        transactionsButton.addEventListener('click', () => {
            transactionsDropdown.classList.toggle('hidden');
        });
    }

    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    }
</script>
</body>
</html>
