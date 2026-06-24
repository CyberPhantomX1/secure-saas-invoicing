<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 13px;
            color: #333333;
            line-height: 1.5;
            padding: 40px;
        }
        
        /* Header Section */
        .header {
            width: 100%;
            margin-bottom: 30px;
            border-bottom: 3px solid #4F46E5;
            padding-bottom: 20px;
        }
        
        .header::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .company-info {
            float: left;
            width: 50%;
        }
        
        .invoice-info {
            float: right;
            width: 50%;
            text-align: right;
        }
        
        .company-name {
            color: #4F46E5;
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .company-email {
            color: #6B7280;
            font-size: 12px;
        }
        
        .invoice-title {
            font-size: 28px;
            color: #111827;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .invoice-number {
            font-size: 14px;
            font-weight: bold;
            color: #374151;
        }
        
        /* Status Badge */
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 8px;
        }
        
        .badge-paid {
            background-color: #D1FAE5;
            color: #065F46;
        }
        
        .badge-unpaid {
            background-color: #FEE2E2;
            color: #991B1B;
        }
        
        .badge-draft {
            background-color: #E5E7EB;
            color: #374151;
        }
        
        /* Details Section */
        .details {
            width: 100%;
            margin-bottom: 40px;
        }
        
        .details::after {
            content: "";
            display: table;
            clear: both;
        }
        
        .bill-to {
            float: left;
            width: 50%;
        }
        
        .invoice-dates {
            float: right;
            width: 50%;
            text-align: right;
        }
        
        .section-title {
            color: #6B7280;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 8px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .customer-name {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 4px;
        }
        
        .customer-detail {
            margin: 2px 0;
            color: #4B5563;
            font-size: 12px;
        }
        
        .date-row {
            margin: 6px 0;
            font-size: 12px;
        }
        
        .date-label {
            font-weight: bold;
            color: #374151;
        }
        
        /* Items Table */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .items-table thead {
            background-color: #F3F4F6;
        }
        
        .items-table th {
            color: #374151;
            text-align: left;
            padding: 12px 10px;
            border-bottom: 2px solid #E5E7EB;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            letter-spacing: 0.5px;
        }
        
        .items-table td {
            padding: 12px 10px;
            border-bottom: 1px solid #E5E7EB;
            font-size: 12px;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        /* Totals Section */
        .totals-wrapper {
            width: 100%;
            margin-bottom: 40px;
        }
        
        .totals-table {
            width: 320px;
            float: right;
            border-collapse: collapse;
        }
        
        .totals-table td {
            padding: 8px 12px;
            font-size: 13px;
        }
        
        .totals-table .label {
            color: #6B7280;
            text-align: left;
        }
        
        .totals-table .value {
            text-align: right;
            font-weight: 500;
        }
        
        .total-row td {
            font-size: 18px;
            font-weight: bold;
            border-top: 2px solid #4F46E5;
            color: #4F46E5;
            padding-top: 12px;
            margin-top: 8px;
        }
        
        /* Footer */
        .footer {
            margin-top: 60px;
            text-align: center;
            color: #6B7280;
            font-size: 11px;
            border-top: 1px solid #E5E7EB;
            padding-top: 20px;
            clear: both;
        }
        
        .footer-title {
            font-weight: bold;
            margin-bottom: 5px;
            color: #374151;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">{{ $invoice->user->business_name ?? 'My Business' }}</div>
            <div class="company-email">{{ $invoice->user->email }}</div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
            @php
                $badgeClass = $invoice->status === 'paid' ? 'badge-paid' : ($invoice->status === 'draft' ? 'badge-draft' : 'badge-unpaid');
            @endphp
            <div class="badge {{ $badgeClass }}">{{ $invoice->status }}</div>
        </div>
    </div>

    <!-- Bill To & Dates Section -->
    <div class="details">
        <div class="bill-to">
            <div class="section-title">Bill To:</div>
            <div class="customer-name">{{ $invoice->customer->name }}</div>
            <div class="customer-detail">{{ $invoice->customer->email }}</div>
            <div class="customer-detail">{{ $invoice->customer->phone }}</div>
            <div class="customer-detail">{{ $invoice->customer->address }}</div>
        </div>
        <div class="invoice-dates">
            <div class="section-title">Invoice Details:</div>
            <div class="date-row">
                <span class="date-label">Invoice Date:</span>
                {{ $invoice->invoice_date->format('F d, Y') }}
            </div>
            <div class="date-row">
                <span class="date-label">Due Date:</span>
                {{ $invoice->due_date->format('F d, Y') }}
            </div>
        </div>
    </div>

    <!-- Line Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th align="left">Description</th>
                <th align="center" width="10%">Qty</th>
                <th align="right" width="15%">Price</th>
                <th align="right" width="15%">Tax</th>
                <th align="right" width="15%">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">${{ number_format($item->price, 2) }}</td>
                    <td class="text-right">${{ number_format($item->tax_amount, 2) }}</td>
                    <td class="text-right">${{ number_format($item->total, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals Section -->
    <div class="totals-wrapper">
        <table class="totals-table">
            <tr>
                <td class="label">Subtotal:</td>
                <td class="value">${{ number_format($invoice->items->sum('total'), 2) }}</td>
            </tr>
            @if($invoice->discount > 0)
                <tr>
                    <td class="label">Discount:</td>
                    <td class="value">-${{ number_format($invoice->discount, 2) }}</td>
                </tr>
            @endif
            <tr class="total-row">
                <td class="label">Total Due:</td>
                <td class="value">${{ number_format($invoice->total_amount, 2) }}</td>
            </tr>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-title">Thank you for your business!</div>
        <div>If you have any questions about this invoice, please contact us at {{ $invoice->user->email }}</div>
    </div>

</body>
</html>