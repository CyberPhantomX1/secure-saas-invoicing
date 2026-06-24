@extends('layouts.app')

@section('page-title', 'Customer Details')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('customers.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Customers
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
            <h3 class="text-base font-semibold text-slate-900">{{ $customer->name }}</h3>
            <div class="flex gap-2">
                <a href="{{ route('customers.edit', $customer->id) }}" class="inline-flex items-center px-3 py-1.5 border border-slate-300 shadow-sm text-xs font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors">Edit</a>
                <form action="{{ route('customers.destroy', $customer->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3 py-1.5 border border-red-300 shadow-sm text-xs font-medium rounded-lg text-red-700 bg-white hover:bg-red-50 transition-colors">Delete</button>
                </form>
            </div>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Email</h4>
                    <p class="text-sm text-slate-900">{{ $customer->email }}</p>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Phone</h4>
                    <p class="text-sm text-slate-900">{{ $customer->phone }}</p>
                </div>
                <div class="md:col-span-2">
                    <h4 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Address</h4>
                    <p class="text-sm text-slate-900">{{ $customer->address }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection