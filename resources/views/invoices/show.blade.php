@extends('layouts.app')

@section('page-title', 'Invoice Details')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('invoices.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-indigo-600 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Invoices
        </a>
    </div>

    @if(request('session_id'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-md flex items-start">
            <svg class="w-5 h-5 text-emerald-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <p class="text-sm text-emerald-800 font-medium">Payment successful! Your invoice has been marked as paid.</p>
        </div>
    @endif

    @if(request('payment') === 'cancelled')
        <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-md flex items-start">
            <svg class="w-5 h-5 text-amber-500 mr-3 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            <p class="text-sm text-amber-800 font-medium">Payment was cancelled. You can try again anytime.</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 sm:p-8">

            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $invoice->invoice_number }}</h1>
                    <p class="text-sm text-slate-500 mt-1">Invoice</p>
                </div>
                <div class="flex flex-col items-end gap-3">
                    @php
                        $statusClass = match($invoice->status) {
                            'paid' => 'bg-emerald-100 text-emerald-800',
                            'unpaid' => 'bg-amber-100 text-amber-800',
                            'draft' => 'bg-slate-100 text-slate-800',
                            default => 'bg-slate-100 text-slate-800'
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">
                        {{ ucfirst($invoice->status) }}
                    </span>

                    <div class="flex gap-2 flex-wrap">
                        <a href="{{ route('invoices.download', $invoice->id) }}" class="inline-flex items-center px-3 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PDF
                        </a>

                        @if($invoice->status !== 'paid')
                            <form action="{{ route('invoices.pay', $invoice->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors" onclick="return confirm('You will be redirected to Stripe to complete the payment.')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Pay ${{ number_format($invoice->total_amount, 2) }}
                                </button>
                            </form>
                        @else
                            <span class="inline-flex items-center px-4 py-2 text-sm font-medium text-emerald-700 bg-emerald-50 rounded-lg border border-emerald-200">
                                <svg class="w-4 h-4 mr-1.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Paid on {{ $invoice->paid_at ? $invoice->paid_at->format('M d, Y') : 'N/A' }}
                            </span>
                        @endif

                        @if($invoice->status === 'paid')
                            <form action="{{ route('invoices.toggleStatus', $invoice->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-amber-300 shadow-sm text-sm font-medium rounded-lg text-amber-700 bg-amber-50 hover:bg-amber-100 transition-colors" onclick="return confirm('Are you sure you want to mark this invoice as unpaid?')">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Mark Unpaid
                                </button>
                            </form>
                        @else
                            <form action="{{ route('invoices.toggleStatus', $invoice->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-3 py-2 border border-emerald-300 shadow-sm text-sm font-medium rounded-lg text-emerald-700 bg-emerald-50 hover:bg-emerald-100 transition-colors" onclick="return confirm('Mark this invoice as paid?')">
                                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Mark as Paid
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-slate-200">
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Bill To</h3>
                    <p class="text-base font-semibold text-slate-900">{{ $invoice->customer->name }}</p>
                    <p class="text-sm text-slate-600 mt-1">{{ $invoice->customer->email }}</p>
                    <p class="text-sm text-slate-600">{{ $invoice->customer->phone }}</p>
                    <p class="text-sm text-slate-600 mt-2 leading-relaxed">{{ $invoice->customer->address }}</p>
                </div>
                <div class="md:text-right">
                    <h3 class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-3">Invoice Details</h3>
                    <p class="text-sm text-slate-600"><span class="font-medium text-slate-900">Invoice Date:</span> {{ $invoice->invoice_date->format('F d, Y') }}</p>
                    <p class="text-sm text-slate-600 mt-2"><span class="font-medium text-slate-900">Due Date:</span> {{ $invoice->due_date->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Price</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Tax</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-slate-200">
                        @foreach($invoice->items as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-4 text-sm font-medium text-slate-900">{{ $item->product_name }}</td>
                                <td class="px-4 py-4 text-sm text-slate-600 text-center">{{ $item->quantity }}</td>
                                <td class="px-4 py-4 text-sm text-slate-600 text-right">${{ number_format($item->price, 2) }}</td>
                                <td class="px-4 py-4 text-sm text-slate-600 text-right">${{ number_format($item->tax_amount, 2) }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-slate-900 text-right">${{ number_format($item->total, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-slate-50">
                        <tr>
                            <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-slate-600">Subtotal:</td>
                            <td class="px-4 py-3 text-right text-sm font-semibold text-slate-900">${{ number_format($invoice->items->sum('total'), 2) }}</td>
                        </tr>
                        @if($invoice->discount > 0)
                            <tr>
                                <td colspan="4" class="px-4 py-3 text-right text-sm font-medium text-slate-600">Discount:</td>
                                <td class="px-4 py-3 text-right text-sm font-semibold text-red-600">-${{ number_format($invoice->discount, 2) }}</td>
                            </tr>
                        @endif
                        <tr class="bg-indigo-50">
                            <td colspan="4" class="px-4 py-4 text-right text-base font-bold text-slate-900 border-t-2 border-indigo-200">Total Due:</td>
                            <td class="px-4 py-4 text-right text-base font-bold text-indigo-600 border-t-2 border-indigo-200">${{ number_format($invoice->total_amount, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection