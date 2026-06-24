<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;

class WebhookController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (\UnexpectedValueException $e) {
            return response('Invalid Payload', 400);
        } catch (SignatureVerificationException $e) {
            return response('Invalid Signature', 400);
        }

        if ($event->type === 'checkout.session.completed') {
        $session = $event->data->object;
        $invoiceId = $session->metadata->invoice_id ?? null;

        if ($invoiceId) {
            $invoice = Invoice::withoutGlobalScopes()->where('id', $invoiceId)->first();

            if ($invoice && $invoice->status !== 'paid') {
                $invoice->update([
                    'status' => 'paid',
                    'paid_at' => now(),
                ]);

                // Send payment confirmation email
                if ($invoice->customer->email) {
                    Mail::to($invoice->customer->email)->send(new PaymentReceivedMail($invoice));
                }
            }
        }
    }

    return response('Webhook Handled', 200);
}
}