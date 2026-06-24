<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#4F46E5">
    <title>{{ config('app.name', 'SaaS Invoicing') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        /* Mobile optimizations */
        @media (max-width: 768px) {
            .mobile-table {
                font-size: 0.875rem;
            }
            .mobile-table th,
            .mobile-table td {
                padding: 0.5rem;
            }
            .touch-target {
                min-height: 44px;
                min-width: 44px;
            }
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Prevent text selection on buttons */
        button, a {
            -webkit-tap-highlight-color: transparent;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    @auth
    <!-- Desktop Sidebar -->
    <aside class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-slate-300 hidden md:flex flex-col z-30">
        <div class="flex items-center justify-center h-16 border-b border-slate-800">
            <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-xl font-bold text-white">Invoicr</span>
            </a>
        </div>
        <nav class="flex-1 px-4 py-6 space-y-1">
            <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white transition-colors' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                Dashboard
            </a>
            <a href="{{ route('customers.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('customers.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white transition-colors' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Customers
            </a>
            <a href="{{ route('invoices.index') }}" class="flex items-center px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('invoices.*') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-800 hover:text-white transition-colors' }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Invoices
            </a>
        </nav>
    </aside>

    <!-- Mobile Header with Hamburger -->
    <header class="md:hidden fixed top-0 left-0 right-0 bg-slate-900 text-white h-16 flex items-center justify-between px-4 z-40">
        <button id="mobile-menu-btn" class="touch-target p-2 rounded-lg hover:bg-slate-800 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
        </button>
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="text-lg font-bold">Invoicr</span>
        </div>
        <div class="w-10"></div>
    </header>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="md:hidden fixed inset-0 bg-black bg-opacity-50 z-50 hidden">
        <div class="bg-slate-900 w-64 h-full transform transition-transform duration-300 -translate-x-full" id="mobile-menu-panel">
            <div class="flex items-center justify-between p-4 border-b border-slate-800">
                <span class="text-lg font-bold text-white">Menu</span>
                <button id="mobile-menu-close" class="touch-target p-2 rounded-lg hover:bg-slate-800 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <nav class="px-4 py-6 space-y-2">
                <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors touch-target">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('customers.index') }}" class="flex items-center px-3 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors touch-target">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Customers
                </a>
                <a href="{{ route('invoices.index') }}" class="flex items-center px-3 py-3 rounded-lg text-sm font-medium text-slate-300 hover:bg-slate-800 hover:text-white transition-colors touch-target">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Invoices
                </a>
                <div class="border-t border-slate-800 pt-4 mt-4">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center px-3 py-3 rounded-lg text-sm font-medium text-red-400 hover:bg-slate-800 transition-colors touch-target">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Sign Out
                        </button>
                    </form>
                </div>
            </nav>
        </div>
    </div>
    @endauth

    <div class="@auth md:ml-64 @endauth flex flex-col min-h-screen @auth md:pt-0 pt-16 @endauth">

        @auth
        <!-- Desktop Header -->
        <header class="hidden md:flex bg-white border-b border-slate-200 h-16 items-center justify-between px-6 sticky top-0 z-20">
            <div class="flex items-center">
                <h1 class="text-lg font-semibold text-slate-800">@yield('page-title', 'Dashboard')</h1>
            </div>
            
            <div class="relative" id="profile-dropdown-container">
                <button id="profile-dropdown-btn" class="flex items-center space-x-3 focus:outline-none hover:bg-slate-50 rounded-lg px-2 py-1.5 transition-colors touch-target">
                    <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-slate-700 hidden sm:block">{{ Auth::user()->name }}</span>
                    <svg class="w-4 h-4 text-slate-400 transition-transform" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                
                <div id="profile-dropdown-menu" class="hidden absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-slate-200 py-1 z-50">
                    <div class="px-4 py-3 border-b border-slate-100">
                        <p class="text-xs text-slate-500 mb-1">Signed in as</p>
                        <p class="text-sm font-medium text-slate-900 truncate">{{ Auth::user()->email }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors flex items-center touch-target">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </header>
        @endauth

        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 w-full">
                <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-md flex items-start">
                    <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <p class="text-sm text-emerald-800">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6 w-full">
                <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md flex items-start">
                    <svg class="w-5 h-5 text-red-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    <p class="text-sm text-red-800">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <main class="flex-1 p-4 sm:p-6 lg:p-8 pb-24 md:pb-8">
            @yield('content')
        </main>
    </div>

    <!-- Mobile Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const mobileMenuBtn = document.getElementById('mobile-menu-btn');
            const mobileMenu = document.getElementById('mobile-menu');
            const mobileMenuPanel = document.getElementById('mobile-menu-panel');
            const mobileMenuClose = document.getElementById('mobile-menu-close');

            if (mobileMenuBtn && mobileMenu) {
                mobileMenuBtn.addEventListener('click', function() {
                    mobileMenu.classList.remove('hidden');
                    setTimeout(() => {
                        mobileMenuPanel.classList.remove('-translate-x-full');
                    }, 10);
                });

                mobileMenuClose.addEventListener('click', function() {
                    mobileMenuPanel.classList.add('-translate-x-full');
                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                    }, 300);
                });

                mobileMenu.addEventListener('click', function(e) {
                    if (e.target === mobileMenu) {
                        mobileMenuPanel.classList.add('-translate-x-full');
                        setTimeout(() => {
                            mobileMenu.classList.add('hidden');
                        }, 300);
                    }
                });
            }

            // Profile dropdown
            const dropdownBtn = document.getElementById('profile-dropdown-btn');
            const dropdownMenu = document.getElementById('profile-dropdown-menu');
            const dropdownArrow = document.getElementById('dropdown-arrow');
            const dropdownContainer = document.getElementById('profile-dropdown-container');

            if (dropdownBtn && dropdownMenu) {
                dropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdownMenu.classList.toggle('hidden');
                    dropdownArrow.style.transform = dropdownMenu.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
                });

                document.addEventListener('click', function(e) {
                    if (!dropdownContainer.contains(e.target)) {
                        dropdownMenu.classList.add('hidden');
                        dropdownArrow.style.transform = 'rotate(0deg)';
                    }
                });

                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !dropdownMenu.classList.contains('hidden')) {
                        dropdownMenu.classList.add('hidden');
                        dropdownArrow.style.transform = 'rotate(0deg)';
                    }
                });
            }
        });
    </script>

</body>
</html>