@component('mail::message')
# New Invoice Created

Hello {{ $invoice->customer->name }},

A new invoice has been created for you.

**Invoice Details:**
- Invoice Number: {{ $invoice->invoice_number }}
- Amount: ${{ number_format($invoice->total_amount, 2) }}
- Due Date: {{ $invoice->due_date->format('F d, Y') }}

@component('mail::button', ['url' => url('/invoices/' . $invoice->id)])
View Invoice
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent