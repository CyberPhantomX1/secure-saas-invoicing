@component('mail::message')
# Payment Received - Thank You!

Hello {{ $invoice->customer->name }},

We have successfully received your payment.

**Payment Details:**
- Invoice Number: {{ $invoice->invoice_number }}
- Amount Paid: ${{ number_format($invoice->total_amount, 2) }}
- Payment Date: {{ $invoice->paid_at->format('F d, Y') }}

Your invoice has been marked as **Paid**.

@component('mail::button', ['url' => url('/invoices/' . $invoice->id)])
View Receipt
@endcomponent

Thank you for your business!<br>
{{ config('app.name') }}
@endcomponent