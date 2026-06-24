<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\RedirectResponse;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function createCheckoutSession(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice->id)
                ->with('error', 'This invoice has already been paid.');
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $amountInCents = (int) round($invoice->total_amount * 100);

        try {
            $session = Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'product_data' => [
                                'name' => "Invoice Payment #{$invoice->invoice_number}",
                                'description' => "Payment for invoice dated {$invoice->invoice_date->format('M d, Y')}",
                            ],
                            'unit_amount' => $amountInCents,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('invoices.show', $invoice->id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('invoices.show', $invoice->id) . '?payment=cancelled',
                'metadata' => [
                    'invoice_id' => $invoice->id,
                ],
            ]);

            $invoice->update(['stripe_session_id' => $session->id]);

            return redirect($session->url);

        } catch (\Exception $e) {
            return redirect()->route('invoices.show', $invoice->id)
                ->with('error', 'Payment initialization failed: ' . $e->getMessage());
        }
    }
}