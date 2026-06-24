@extends('layouts.app')

@section('page-title', 'Create Customer')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Customers
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base font-semibold text-slate-900">New Customer</h3>
        </div>
        <div class="p-6">
            <form method="POST" action="{{ route('customers.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required autofocus
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-slate-700 mb-1">Phone Number</label>
                    <input type="text" id="phone" name="phone" value="{{ old('phone') }}" required
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('phone') border-red-500 @enderror">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-slate-700 mb-1">Address</label>
                    <textarea id="address" name="address" rows="3" required
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('address') border-red-500 @enderror">{{ old('address') }}</textarea>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NEW: Portal Password Field -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Portal Password <span class="text-slate-400">(Optional)</span></label>
                    <input type="password" id="password" name="password"
                        class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('password') border-red-500 @enderror"
                        placeholder="Leave blank to skip portal access">
                    <p class="mt-1 text-xs text-slate-400">Set a password to allow this customer to access the client portal.</p>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('customers.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors">Cancel</a>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">Save Customer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection