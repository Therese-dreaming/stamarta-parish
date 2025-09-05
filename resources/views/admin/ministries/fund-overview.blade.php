@extends('layouts.admin')

@section('title', 'Fund Overview')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex space-x-3">
                <a href="{{ route('admin.ministries.index') }}" class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors shadow-lg hover:shadow-xl">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Ministries
                </a>
            </div>
        </div>
    </div>

    <!-- Enhanced Header -->
    <div class="bg-[#0d5c2f] rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-white/5 rounded-tr-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="mr-6 hidden md:block">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center border-2 border-white/30 shadow-lg">
                            <i class="fas fa-chart-pie text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-coins mr-3"></i>
                            {{ $ministry->name }} Fund Overview
                        </h1>
                        <p class="text-white/90 text-base">Financial summary and transaction history</p>
                        <div class="flex items-center mt-3 text-white/80 text-sm">
                            <i class="fas fa-info-circle mr-2"></i>
                            <span>Total: {{ $transactions->total() }} transactions</span>
                        </div>
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold mb-1">₱{{ number_format($balance, 2) }}</div>
                    <div class="text-sm opacity-90">Current Balance</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Enhanced Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Current Balance -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Current Balance</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($balance, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-wallet text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-blue-500 mr-1"></i>
                    <span>Available funds</span>
                </div>
            </div>
        </div>

        <!-- Total Credits -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Credits</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalCredits, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-arrow-up text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                    <span>{{ $transactionDistribution['credits']['count'] }} transactions</span>
                </div>
            </div>
        </div>

        <!-- Total Debits -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Total Debits</p>
                    <p class="text-2xl font-bold text-gray-900">₱{{ number_format($totalDebits, 2) }}</p>
                </div>
                <div class="w-12 h-12 bg-red-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-arrow-down text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                    <span>{{ $transactionDistribution['debits']['count'] }} transactions</span>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Last Month</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $recentActivity['last_month_transactions'] }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-calendar text-white text-lg"></i>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-gray-100">
                <div class="flex items-center text-xs text-gray-500">
                    <i class="fas fa-info-circle text-purple-500 mr-1"></i>
                    <span>Transactions</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Transaction Type Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-pie mr-2 text-[#0d5c2f]"></i>
                    Transaction Distribution
                </h3>
                <p class="text-sm text-gray-600 mt-1">Credits vs Debits breakdown</p>
            </div>
            <div class="p-6">
                @if($transactions->total() > 0 && ($totalCredits > 0 || $totalDebits > 0))
                    <div class="flex items-center justify-center h-48">
                        <canvas id="transactionChart" width="200" height="200"></canvas>
                    </div>
                    <div class="flex justify-center space-x-6 mt-4">
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-green-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Credits ({{ $transactionDistribution['credits']['count'] }})</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-4 h-4 bg-red-500 rounded-full mr-2"></div>
                            <span class="text-sm text-gray-600">Debits ({{ $transactionDistribution['debits']['count'] }})</span>
                        </div>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-48 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-chart-pie text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">No Transaction Data</h4>
                        <p class="text-xs text-gray-500 max-w-xs">Start creating transactions to see the distribution chart</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Monthly Trend Chart -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-[#0d5c2f]"></i>
                    Monthly Balance Trend
                </h3>
                <p class="text-sm text-gray-600 mt-1">Balance progression over time</p>
            </div>
            <div class="p-6">
                @if($transactions->total() > 0)
                    <div class="flex items-center justify-center h-48">
                        <canvas id="trendChart" width="400" height="200"></canvas>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center h-48 text-center">
                        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-chart-line text-gray-400 text-2xl"></i>
                        </div>
                        <h4 class="text-sm font-medium text-gray-900 mb-2">No Trend Data</h4>
                        <p class="text-xs text-gray-500 max-w-xs">Transaction history will appear here to show balance trends</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Transactions Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-list mr-2 text-[#0d5c2f]"></i>
                        All Transactions
                    </h3>
                    <p class="text-sm text-gray-600 mt-1">Complete financial activity history with pagination</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <span class="text-sm text-gray-500">
                        Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} transactions
                    </span>
                </div>
            </div>
        </div>

        <!-- View Toggle -->
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex border-b border-gray-200">
                <button id="table-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-[#0d5c2f] text-[#0d5c2f]">
                    <i class="fas fa-table mr-2"></i> Table View
                </button>
                <button id="card-view-btn" class="flex-1 px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 hover:bg-gray-50 transition-all duration-200 border-b-2 border-transparent">
                    <i class="fas fa-th-large mr-2"></i> Cards View
                </button>
            </div>
        </div>

        <!-- Table View -->
        <div id="table-view" class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date & Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entered By</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $tx)
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $tx->created_at->format('M d, Y') }}</div>
                                <div class="text-sm text-gray-500">{{ $tx->created_at->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $tx->type === 'credit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    @if($tx->type === 'credit')
                                        <i class="fas fa-arrow-up w-3 h-3 mr-1"></i>
                                    @else
                                        <i class="fas fa-arrow-down w-3 h-3 mr-1"></i>
                                    @endif
                                    {{ ucfirst($tx->type) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}₱{{ number_format($tx->amount, 2) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate" title="{{ $tx->description }}">
                                    {{ $tx->description }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ class_basename($tx->source_type) }} #{{ $tx->source_id }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 bg-[#0d5c2f] rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">
                                            {{ optional($tx->enteredBy)->name ? substr(optional($tx->enteredBy)->name, 0, 2) : 'N/A' }}
                                        </span>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ optional($tx->enteredBy)->name ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="text-gray-500">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="fas fa-receipt text-gray-400 text-xl"></i>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900 mb-1">No transactions yet</h3>
                                    <p class="text-sm text-gray-500">Get started by creating your first transaction.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Card View -->
        <div id="card-view" class="hidden p-6">
            @if($transactions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($transactions as $tx)
                        <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:border-[#0d5c2f] transition-all duration-200">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 {{ $tx->type === 'credit' ? 'bg-green-100' : 'bg-red-100' }} rounded-full flex items-center justify-center">
                                        <i class="fas {{ $tx->type === 'credit' ? 'fa-arrow-up text-green-600' : 'fa-arrow-down text-red-600' }} text-sm"></i>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ ucfirst($tx->type) }}</div>
                                        <div class="text-xs text-gray-500">{{ $tx->created_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <span class="text-lg font-bold {{ $tx->type === 'credit' ? 'text-green-600' : 'text-red-600' }}">
                                    {{ $tx->type === 'credit' ? '+' : '-' }}₱{{ number_format($tx->amount, 2) }}
                                </span>
                            </div>
                            
                            <div class="space-y-2">
                                <div class="text-sm text-gray-900 line-clamp-2" title="{{ $tx->description }}">
                                    {{ $tx->description }}
                                </div>
                                
                                <div class="flex items-center justify-between text-xs text-gray-500">
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded">
                                        {{ class_basename($tx->source_type) }} #{{ $tx->source_id }}
                                    </span>
                                    <span>{{ $tx->created_at->format('h:i A') }}</span>
                                </div>
                                
                                <div class="flex items-center pt-2 border-t border-gray-200">
                                    <div class="w-6 h-6 bg-[#0d5c2f] rounded-full flex items-center justify-center">
                                        <span class="text-xs font-medium text-white">
                                            {{ optional($tx->enteredBy)->name ? substr(optional($tx->enteredBy)->name, 0, 2) : 'N/A' }}
                                        </span>
                                    </div>
                                    <span class="ml-2 text-xs text-gray-600">{{ optional($tx->enteredBy)->name ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-sm font-medium text-gray-900 mb-1">No transactions yet</h3>
                    <p class="text-sm text-gray-500">Get started by creating your first transaction.</p>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of {{ $transactions->total() }} results
                    </div>
                    <div class="flex items-center space-x-2">
                        {{ $transactions->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle functionality
    const tableViewBtn = document.getElementById('table-view-btn');
    const cardViewBtn = document.getElementById('card-view-btn');
    const tableView = document.getElementById('table-view');
    const cardView = document.getElementById('card-view');

    function showTableView() {
        tableView.classList.remove('hidden');
        cardView.classList.add('hidden');
        tableViewBtn.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]');
        tableViewBtn.classList.remove('border-transparent');
        cardViewBtn.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]');
        cardViewBtn.classList.add('border-transparent');
    }

    function showCardView() {
        cardView.classList.remove('hidden');
        tableView.classList.add('hidden');
        cardViewBtn.classList.add('border-[#0d5c2f]', 'text-[#0d5c2f]');
        cardViewBtn.classList.remove('border-transparent');
        tableViewBtn.classList.remove('border-[#0d5c2f]', 'text-[#0d5c2f]');
        tableViewBtn.classList.add('border-transparent');
    }

    tableViewBtn.addEventListener('click', showTableView);
    cardViewBtn.addEventListener('click', showCardView);

    // Chart data from backend
    const transactionCount = {{ $transactions->total() }};
    const creditAmount = {{ $totalCredits }};
    const debitAmount = {{ $totalDebits }};
    const monthlyTrends = @json($monthlyTrends);
    
    // Only initialize charts if there's meaningful data
    if (transactionCount > 0 && (creditAmount > 0 || debitAmount > 0)) {
        // Transaction Type Chart (Doughnut)
        const transactionCtx = document.getElementById('transactionChart');
        if (transactionCtx) {
            const transactionChart = new Chart(transactionCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Credits', 'Debits'],
                    datasets: [{
                        data: [creditAmount, debitAmount],
                        backgroundColor: ['#10B981', '#EF4444'],
                        borderWidth: 0,
                        cutout: '70%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        // Monthly Trend Chart (Line) - Using real data from backend
        const trendCtx = document.getElementById('trendChart');
        if (trendCtx && monthlyTrends.length > 0) {
            const months = monthlyTrends.map(trend => trend.month);
            const balanceData = monthlyTrends.map(trend => trend.balance);
            
            const trendChart = new Chart(trendCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: months,
                    datasets: [{
                        label: 'Balance',
                        data: balanceData,
                        borderColor: '#0d5c2f',
                        backgroundColor: 'rgba(13, 92, 47, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0d5c2f',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>
@endsection


