<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(): View
    {
        $invoices = Invoice::with('customer:id,name,email')
            ->select('id', 'customer_id', 'invoice_number', 'invoice_date', 'due_date', 'total_amount', 'status')
            ->latest('invoice_date')
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();

        return view('invoices.create', compact('customers'));
    }

    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $invoiceNumber = $this->generateInvoiceNumber($validated['invoice_date']);

        $subtotal = 0.00;
        $itemsToInsert = [];

        foreach ($validated['items'] as $item) {
            $quantity      = (float) $item['quantity'];
            $price         = (float) $item['price'];
            $taxPercentage = (float) $item['tax_percentage'];

            $lineTotal   = $quantity * $price;
            $taxAmount   = round($lineTotal * ($taxPercentage / 100), 2);
            $itemTotal   = $lineTotal + $taxAmount;

            $subtotal += $itemTotal;

            $itemsToInsert[] = [
                'product_name'   => $item['product_name'],
                'quantity'       => $quantity,
                'price'          => $price,
                'tax_amount'     => $taxAmount,
                'total'          => $itemTotal,
                'created_at'     => now(),
                'updated_at'     => now(),
            ];
        }

        $discount = (float) ($validated['discount'] ?? 0.00);
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $finalTotal = round($subtotal - $discount, 2);

        DB::transaction(function () use ($validated, $invoiceNumber, $itemsToInsert, $finalTotal, $discount) {
        $invoice = Invoice::create([
            'customer_id'    => $validated['customer_id'],
            'invoice_number' => $invoiceNumber,
            'invoice_date'   => $validated['invoice_date'],
            'due_date'       => $validated['due_date'],
            'discount'       => $discount,
            'total_amount'   => $finalTotal,
            'status'         => 'draft',
        ]);

        $invoice->items()->createMany($itemsToInsert);
    });

    // Send email to customer
    $invoice = Invoice::where('invoice_number', $invoiceNumber)->first();
    if ($invoice && $invoice->customer->email) {
        Mail::to($invoice->customer->email)->send(new InvoiceCreatedMail($invoice));
    }

    return redirect()->route('invoices.index')
        ->with('success', "Invoice {$invoiceNumber} created successfully. Email sent to customer.");
}

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'items']);

        return view('invoices.show', compact('invoice'));
    }

    public function downloadPDF(Invoice $invoice)
    {
        $invoice->load(['customer', 'items', 'user']);

        $pdf = Pdf::loadView('invoices.pdf', compact('invoice'));
        $pdf->setPaper('a4', 'portrait');

        return $pdf->download('invoice-' . $invoice->invoice_number . '.pdf');
    }

    public function toggleStatus(Invoice $invoice): RedirectResponse
    {
        $invoice->status = ($invoice->status === 'paid') ? 'unpaid' : 'paid';
        $invoice->save();

        $statusText = ucfirst($invoice->status);

        return redirect()
            ->route('invoices.show', $invoice->id)
            ->with('success', "Invoice status successfully updated to '{$statusText}'.");
    }

    private function generateInvoiceNumber(string $invoiceDate): string
    {
        $year = date('Y', strtotime($invoiceDate));

        $count = Invoice::where('user_id', auth()->id())
            ->whereYear('invoice_date', $year)
            ->count();

        $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT);

        return "INV-{$year}-{$sequence}";
    }
}