<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    /**
     * Display a listing of invoices.
     */
    public function index(): View
    {
        $invoices = Invoice::with('customer')
            ->latest('invoice_date')
            ->get();

        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new invoice.
     */
    public function create(): View
    {
        // TenantScope automatically filters to current user's customers
        $customers = Customer::orderBy('name')->get();

        return view('invoices.create', compact('customers'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(StoreInvoiceRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Generate unique invoice number
        $invoiceNumber = $this->generateInvoiceNumber($validated['invoice_date']);

        // Backend calculation - NEVER trust frontend totals
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

        // Apply discount securely
        $discount = (float) ($validated['discount'] ?? 0.00);
        if ($discount > $subtotal) {
            $discount = $subtotal;
        }

        $finalTotal = round($subtotal - $discount, 2);

        // Database transaction for atomicity
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

        return redirect()
            ->route('invoices.index')
            ->with('success', "Invoice {$invoiceNumber} created successfully.");
    }

    /**
     * Display the specified invoice.
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'items']);

        return view('invoices.show', compact('invoice'));
    }

    /**
     * Generate sequential invoice number.
     */
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