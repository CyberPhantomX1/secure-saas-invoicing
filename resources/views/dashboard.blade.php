@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">

    <div>
        <h2 class="text-2xl font-bold text-slate-900">Welcome back, {{ Auth::user()->name }}!</h2>
        <p class="mt-1 text-sm text-slate-500">Here's what's happening with your business today.</p>
    </div>

    <!-- Metric Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 bg-indigo-50 rounded-lg p-3">
                <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Invoices</p>
                <p class="text-2xl font-bold text-slate-900">{{ $totalInvoices ?? 0 }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 bg-emerald-50 rounded-lg p-3">
                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Paid</p>
                <p class="text-2xl font-bold text-emerald-600">${{ number_format($totalPaid ?? 0, 2) }}</p>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 flex items-center space-x-4 hover:shadow-md transition-shadow">
            <div class="flex-shrink-0 bg-amber-50 rounded-lg p-3">
                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Unpaid</p>
                <p class="text-2xl font-bold text-amber-600">${{ number_format($totalUnpaid ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="flex flex-wrap gap-4">
        <a href="{{ route('customers.create') }}" class="inline-flex items-center px-4 py-2 border border-slate-300 shadow-sm text-sm font-medium rounded-lg text-slate-700 bg-white hover:bg-slate-50 transition-colors">
            <svg class="w-4 h-4 mr-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Add Customer
        </a>
        <a href="{{ route('invoices.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
            Create Invoice
        </a>
    </div>

    <!-- Analytics Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="mb-4">
                <h3 class="text-base font-semibold text-slate-900">Monthly Revenue ({{ now()->year }})</h3>
                <p class="text-sm text-slate-500">Total income generated per month</p>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <div class="mb-4">
                <h3 class="text-base font-semibold text-slate-900">Invoice Status Distribution</h3>
                <p class="text-sm text-slate-500">Breakdown of current invoice states</p>
            </div>
            <div class="relative h-72 w-full flex justify-center">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Invoices Table -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
            <h3 class="text-base font-semibold text-slate-900">Recent Invoices</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Invoice #</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-slate-500 uppercase tracking-wider">Amount</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-slate-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-slate-200">
                    @forelse($recentInvoices ?? [] as $invoice)
                        <tr class="hover:bg-slate-50 transition-colors duration-150">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">
                                <a href="{{ route('invoices.show', $invoice->id) }}">{{ $invoice->invoice_number }}</a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-900">{{ $invoice->customer->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500">{{ $invoice->invoice_date->format('M d, Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900 text-right">${{ number_format($invoice->total_amount, 2) }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusClass = match($invoice->status) {
                                        'paid' => 'bg-emerald-100 text-emerald-800',
                                        'unpaid' => 'bg-amber-100 text-amber-800',
                                        'draft' => 'bg-slate-100 text-slate-800',
                                        default => 'bg-slate-100 text-slate-800'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-sm text-slate-500">No recent invoices found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const months = @json($months);
        const revenueData = @json($revenueData);
        const statusLabels = @json($statusLabels);
        const statusCounts = @json($statusCounts);

        Chart.defaults.font.family = "'Inter', sans-serif";
        Chart.defaults.color = '#64748b';

        // Monthly Revenue Line Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const gradient = revenueCtx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(99, 102, 241, 0.2)');
        gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Revenue ($)',
                    data: revenueData,
                    borderColor: '#6366f1',
                    backgroundColor: gradient,
                    borderWidth: 2,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#6366f1',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleColor: '#f8fafc',
                        bodyColor: '#f8fafc',
                        padding: 12,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '$' + context.parsed.y.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: {
                            callback: function(value) { return '$' + value; }
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });

        // Invoice Status Doughnut Chart
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        const statusColors = statusLabels.map(label => {
            switch(label.toLowerCase()) {
                case 'paid': return '#10b981';
                case 'unpaid': return '#f59e0b';
                case 'overdue': return '#ef4444';
                case 'draft': return '#64748b';
                default: return '#cbd5e1';
            }
        });

        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: statusLabels.map(label => label.charAt(0).toUpperCase() + label.slice(1)),
                datasets: [{
                    data: statusCounts,
                    backgroundColor: statusColors,
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            pointStyle: 'circle',
                            padding: 20,
                            font: { size: 12, weight: '500' }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = total > 0 ? Math.round((context.parsed / total) * 100) : 0;
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection