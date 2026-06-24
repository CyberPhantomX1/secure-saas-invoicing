@component('mail::message')
# Payment Reminder

Hello {{ $invoice->customer->name }},

This is a friendly reminder that your invoice is due soon.

**Invoice Details:**
- Invoice Number: **{{ $invoice->invoice_number }}**
- Amount Due: **${{ number_format($invoice->total_amount, 2) }}**
- Due Date: **{{ $invoice->due_date->format('F d, Y') }}**
- Status: **{{ ucfirst($invoice->status) }}**

Please make the payment before the due date to avoid any late fees.

@component('mail::button', ['url' => url('/portal/login')])
Go to Client Portal
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent