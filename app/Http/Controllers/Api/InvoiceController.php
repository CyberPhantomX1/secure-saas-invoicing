<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $invoices = Invoice::with('customer:id,name,email')
            ->select('id', 'customer_id', 'invoice_number', 'invoice_date', 'due_date', 'total_amount', 'status')
            ->latest('invoice_date')
            ->get();

        return response()->json(['data' => $invoices]);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        $invoice = Invoice::with(['customer', 'items'])
            ->findOrFail($id);

        return response()->json(['data' => $invoice]);
    }

    public function toggleStatus(Request $request, string $id): JsonResponse
    {
        $invoice = Invoice::findOrFail($id);

        $invoice->status = ($invoice->status === 'paid') ? 'unpaid' : 'paid';
        $invoice->save();

        return response()->json([
            'message' => 'Status updated.',
            'data'    => $invoice,
        ]);
    }
}