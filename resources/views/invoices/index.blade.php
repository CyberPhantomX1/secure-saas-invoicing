@extends('layouts.app')

@section('page-title', 'Invoices')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Invoices</h2>
            <p class="mt-1 text-sm text-slate-500">Manage and track your invoices</p>
        </div>
        <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Create Invoice
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Invoice #</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Invoice Date</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Due Date</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($invoices as $invoice)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-900">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-slate-900">{{ $invoice->customer->name }}</div>
                                <div class="text-sm text-slate-500">{{ $invoice->customer->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $invoice->invoice_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                {{ $invoice->due_date->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-slate-900 text-right">
                                ${{ number_format($invoice->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $badgeClass = match($invoice->status) {
                                        'paid' => 'bg-emerald-100 text-emerald-700 ring-emerald-600/20',
                                        'unpaid' => 'bg-amber-100 text-amber-700 ring-amber-600/20',
                                        'draft' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                        default => 'bg-slate-100 text-slate-700 ring-slate-600/20'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold ring-1 ring-inset {{ $badgeClass }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('invoices.show', $invoice->id) }}" class="text-indigo-600 hover:text-indigo-900 font-semibold">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center">
                                <div class="text-slate-400 mb-2">
                                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-slate-500 mb-3">No invoices found</p>
                                <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700">
                                    Create your first invoice
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection