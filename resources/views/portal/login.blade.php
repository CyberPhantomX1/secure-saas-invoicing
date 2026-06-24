@extends('layouts.portal')

@section('content')
<div class="min-h-screen flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full">
        <div class="text-center mb-8">
            <svg class="mx-auto w-12 h-12 text-indigo-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            <h2 class="text-2xl font-bold text-slate-900">Client Portal</h2>
            <p class="mt-2 text-sm text-slate-500">Sign in to view your invoices</p>
        </div>
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-8">
            <form method="POST" action="{{ route('portal.login') }}">
                @csrf
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                    <input id="password" type="password" name="password" required
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror">
                </div>
                <button type="submit" class="w-full py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
                    Sign in to Portal
                </button>
            </form>
        </div>
    </div>
</div>
@endsection