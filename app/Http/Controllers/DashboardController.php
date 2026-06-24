<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $userId = auth()->id();
        $cacheKey = "dashboard_{$userId}";
        $cacheDuration = 300; // 5 minutes

        $data = Cache::remember($cacheKey, $cacheDuration, function () use ($userId) {
            $currentYear = now()->year;

            // Optimized query with select only needed columns
            $invoices = Invoice::whereYear('invoice_date', $currentYear)
                ->select('invoice_date', 'total_amount', 'status')
                ->get();

            $months = [];
            $revenueData = [];

            for ($i = 1; $i <= 12; $i++) {
                $months[] = Carbon::createFromDate($currentYear, $i, 1)->format('M');
                $revenueData[$i] = 0.00;
            }

            foreach ($invoices as $invoice) {
                $monthNumber = (int) Carbon::parse($invoice->invoice_date)->format('n');
                $revenueData[$monthNumber] += (float) $invoice->total_amount;
            }

            $revenueData = array_values($revenueData);

            $statusData = $invoices->groupBy('status')->map->count()->toArray();
            $statusLabels = array_keys($statusData);
            $statusCounts = array_values($statusData);

            if (empty($statusLabels)) {
                $statusLabels = ['No Data'];
                $statusCounts = [1];
            }

            $totalInvoices = $invoices->count();
            $totalPaid = $invoices->where('status', 'paid')->sum('total_amount');
            $totalUnpaid = $invoices->whereNotIn('status', ['paid'])->sum('total_amount');
            $recentInvoices = Invoice::with('customer:id,name')
                ->select('id', 'invoice_number', 'customer_id', 'invoice_date', 'total_amount', 'status')
                ->latest('invoice_date')
                ->take(5)
                ->get();

            return compact(
                'months',
                'revenueData',
                'statusLabels',
                'statusCounts',
                'totalInvoices',
                'totalPaid',
                'totalUnpaid',
                'recentInvoices'
            );
        });

        return view('dashboard', $data);
    }
}