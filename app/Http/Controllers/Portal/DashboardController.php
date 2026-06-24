<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $customerId = session('portal_customer_id');

        $invoices = Invoice::withoutGlobalScopes()
            ->where('customer_id', $customerId)
            ->with('items')
            ->latest('invoice_date')
            ->get();

        $totalInvoices = $invoices->count();
        $totalPaid = $invoices->where('status', 'paid')->sum('total_amount');
        $totalUnpaid = $invoices->where('status', '!=', 'paid')->sum('total_amount');

        return view('portal.dashboard', compact('invoices', 'totalInvoices', 'totalPaid', 'totalUnpaid'));
    }
}