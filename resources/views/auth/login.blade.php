@extends('layouts.app')

@section('page-title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-slate-900">Welcome back</h2>
            <p class="mt-2 text-sm text-slate-500">Sign in to your account</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <span class="ml-2 text-sm text-slate-600">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Sign in
                </button>

                <p class="mt-4 text-center text-sm text-slate-500">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sign up</a>
                </p>
            </form>
        </div>
    </div>
</div>
@endsection