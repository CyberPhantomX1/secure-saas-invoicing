<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Client Portal - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'] } } }
        }
    </script>
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

    @if(session('portal_customer_id'))
    <header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-6 sticky top-0 z-20">
        <a href="{{ route('portal.dashboard') }}" class="flex items-center space-x-2">
            <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <span class="text-lg font-bold text-slate-900">Client Portal</span>
        </a>
        <div class="flex items-center space-x-4">
            <span class="text-sm text-slate-600 hidden sm:block">{{ session('portal_customer_name') }}</span>
            <form method="POST" action="{{ route('portal.logout') }}">
                @csrf
                <button type="submit" class="text-sm text-slate-500 hover:text-red-600 transition-colors">Sign out</button>
            </form>
        </div>
    </header>
    @endif

    @if(session('success'))
        <div class="max-w-5xl mx-auto px-4 mt-6">
            <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-md">
                <p class="text-sm text-emerald-800">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="max-w-5xl mx-auto px-4 mt-6">
            <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-md">
                <p class="text-sm text-red-800">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <main class="flex-1 p-4 sm:p-6 lg:p-8">
        @yield('content')
    </main>

</body>
</html>