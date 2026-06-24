<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class InvoiceController extends Controller
{
    private function getCustomerInvoice(string $invoiceId): Invoice
    {
        $customerId = session('portal_customer_id');

        $invoice = Invoice::withoutGlobalScopes()
            ->where('id', $invoiceId)
            ->where('customer_id', $customerId)
            ->with(['customer', 'items', 'user'])
            ->firstOrFail();

        return $invoice;
    }

    public function show(string $id): View
    {
        $invoice = $this->getCustomerInvoice($id);
        return view('portal.invoices.show', compact('invoice'));
    }

    public function downloadPDF(string $id)
    {
        $invoice = $this->getCustomerInvoice($id);
        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $pdf->setPaper('a4', 'portrait');
        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function pay(string $id): RedirectResponse
    {
        $invoice = $this->getCustomerInvoice($id);

        if ($invoice->status === 'paid') {
            return redirect()->route('portal.invoices.show', $invoice->id)
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
                            ],
                            'unit_amount' => $amountInCents,
                        ],
                        'quantity' => 1,
                    ],
                ],
                'mode' => 'payment',
                'success_url' => route('portal.invoices.show', $invoice->id) . '?session_id={CHECKOUT_SESSION_ID}',
                'cancel_url' => route('portal.invoices.show', $invoice->id) . '?payment=cancelled',
                'metadata' => [
                    'invoice_id' => $invoice->id,
                ],
            ]);

            $invoice->update(['stripe_session_id' => $session->id]);
            return redirect($session->url);

        } catch (\Exception $e) {
            return redirect()->route('portal.invoices.show', $invoice->id)
                ->with('error', 'Payment failed: ' . $e->getMessage());
        }
    }
}