@extends('layouts.portal')

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('portal.dashboard') }}" class="text-sm text-slate-500 hover:text-indigo-600 transition-colors inline-flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Dashboard
        </a>
    </div>

    @if(request('session_id'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r-md">
            <p class="text-sm text-emerald-800 font-medium">Payment successful! Thank you.</p>
        </div>
    @endif

    @if(request('payment') === 'cancelled')
        <div class="mb-6 bg-amber-50 border-l-4 border-amber-500 p-4 rounded-r-md">
            <p class="text-sm text-amber-800 font-medium">Payment was cancelled.</p>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="p-6 sm:p-8">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-slate-900">{{ $invoice->invoice_number }}</h1>
                    <p class="text-sm text-slate-500 mt-1">From: {{ $invoice->user->business_name ?? 'Our Company' }}</p>
                </div>
                <div class="flex flex-col items-end gap-3">
                    @php
                        $statusClass = match($invoice->status) {
                            'paid' => 'bg-emerald-100 text-emerald-800',
                            'unpaid' => 'bg-amber-100 text-amber-800',
                            default => 'bg-slate-100 text-slate-800'
                        };
                    @endphp
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $statusClass }}">{{ ucfirst($invoice->status) }}</span>
                    <div class="flex gap-2">
                        <a href="{{ route('portal.invoices.download', $invoice->id) }}" class="inline-flex items-center px-3 py-2 border border-slate-300 text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors">
                            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Download PDF
                        </a>
                        @if($invoice->status !== 'paid')
                            <form action="{{ route('portal.invoices.pay', $invoice->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 transition-colors" onclick="return confirm('Redirect to secure payment page?')">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    Pay ${{ number_format($invoice->total_amount, 2) }}
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8 pb-8 border-b border-slate-200">
                <div>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase mb-2">Invoice Date</h3>
                    <p class="text-sm text-slate-900">{{ $invoice->invoice_date->format('F d, Y') }}</p>
                    <h3 class="text-xs font-semibold text-slate-500 uppercase mb-2 mt-4">Due Date</h3>
                    <p class="text-sm text-slate-900">{{ $invoice->due_date->format('F d, Y') }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-slate-500 uppercase">Description</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-slate-500 uppercase">Qty</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Price</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Tax</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-slate-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach($invoice->items as $item)
                            <tr>
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