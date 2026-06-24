@extends('layouts.app')

@section('page-title', 'Create New Invoice')

@section('content')
<div class="max-w-5xl mx-auto">
    <form method="POST" action="{{ route('invoices.store') }}" id="invoice-form">
        @csrf

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h3 class="text-base font-semibold text-slate-900">Invoice Details</h3>
            </div>
            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Customer Select -->
                <div class="md:col-span-1">
                    <label for="customer_id" class="block text-sm font-medium text-slate-700 mb-1">Customer</label>
                    <select id="customer_id" name="customer_id" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('customer_id') border-red-500 @enderror" required>
                        <option value="">Select a customer...</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Invoice Date -->
                <div>
                    <label for="invoice_date" class="block text-sm font-medium text-slate-700 mb-1">Invoice Date</label>
                    <input type="date" id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('invoice_date') border-red-500 @enderror" required>
                    @error('invoice_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Due Date -->
                <div>
                    <label for="due_date" class="block text-sm font-medium text-slate-700 mb-1">Due Date</label>
                    <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('due_date') border-red-500 @enderror" required>
                    @error('due_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Discount -->
                <div class="md:col-span-3">
                    <label for="discount" class="block text-sm font-medium text-slate-700 mb-1">Discount (Amount)</label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" step="0.01" id="discount" name="discount" value="{{ old('discount', 0) }}" class="block w-full pl-7 rounded-lg border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('discount') border-red-500 @enderror">
                    </div>
                    @error('discount')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Dynamic Items Section -->
        <div class="mt-8 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex justify-between items-center">
                <h3 class="text-base font-semibold text-slate-900">Line Items</h3>
                <button type="button" id="add-item-btn" class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Add Item
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-1/2">Description</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-24">Qty</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-32">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider w-24">Tax %</th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider w-20">Action</th>
                        </tr>
                    </thead>
                    <tbody id="items-body" class="bg-white divide-y divide-slate-200">
                        <!-- Rows injected by JS -->
                    </tbody>
                </table>
            </div>

            @error('items')
                <div class="px-6 py-3 bg-red-50 border-t border-red-100">
                    <p class="text-sm text-red-600">{{ $message }}</p>
                </div>
            @enderror
        </div>

        <!-- Form Actions -->
        <div class="mt-8 flex justify-end space-x-4">
            <a href="{{ route('invoices.index') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center px-6 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                Generate Invoice
            </button>
        </div>
    </form>
</div>

<!-- Vanilla JavaScript for Dynamic Rows (Updated with Tailwind Classes) -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        let itemIndex = 0;
        const tbody = document.getElementById('items-body');
        const addBtn = document.getElementById('add-item-btn');

        function addRow() {
            const tr = document.createElement('tr');
            tr.className = "hover:bg-slate-50 transition-colors";
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="text" name="items[${itemIndex}][product_name]" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="Item description" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" name="items[${itemIndex}][quantity]" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="1" step="1" value="1" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-slate-500 sm:text-sm">$</span>
                        </div>
                        <input type="number" name="items[${itemIndex}][price]" class="block w-full pl-7 rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="0.01" step="0.01" required>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <input type="number" name="items[${itemIndex}][tax_percentage]" class="block w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" min="0" max="100" step="0.01" value="0" required>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button type="button" class="text-red-600 hover:text-red-900 transition-colors remove-row" title="Remove item">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                    </button>
                </td>
            `;
            tbody.appendChild(tr);
            itemIndex++;
        }

        addBtn.addEventListener('click', addRow);

        tbody.addEventListener('click', function (e) {
            if (e.target.closest('.remove-row')) {
                e.target.closest('tr').remove();
            }
        });

        // Add one default row on page load
        addRow();
    });
</script>
@endsection