@extends('layouts.ministry')

@section('title', 'Dashboard - ' . ($ministry->name ?? 'Ministry'))

@section('content')
@include('components.toast')

<div class="space-y-6">
    <!-- Enhanced Header -->
    <div class="bg-[#0d5c2f] rounded-2xl shadow-xl overflow-hidden">
        <div class="px-8 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/10 rounded-bl-full"></div>
            <div class="absolute left-0 bottom-0 w-24 h-24 bg-white/5 rounded-tr-full"></div>
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center relative z-10">
                <div class="flex items-center mb-4 md:mb-0">
                    <div class="mr-6 hidden md:block">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center border-2 border-white/30 shadow-lg">
                            <i class="fas fa-tachometer-alt text-white text-2xl"></i>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white flex items-center mb-2">
                            <i class="fas fa-tachometer-alt mr-3"></i>
                            Ministry Dashboard
                        </h1>
                        <p class="text-white/90 text-base">Welcome back, {{ Auth::user()->name }}</p>
                        @if($ministry)
                            <div class="flex items-center mt-3 text-white/80 text-sm">
                                <i class="fas fa-church mr-2"></i>
                                <span>{{ $ministry->name }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="text-right text-white">
                    <div class="text-4xl font-bold mb-1">{{ date('M d, Y') }}</div>
                    <div class="text-sm opacity-90">{{ date('l') }}</div>
                    <div class="flex space-x-3 mt-4">
                        <a href="{{ route('ministry.activities.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-plus mr-2"></i>
                            New Activity
                        </a>
                        <a href="{{ route('ministry.members.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 text-white font-medium rounded-lg transition-all duration-200 border border-white/30 hover:border-white/50">
                            <i class="fas fa-user-plus mr-2"></i>
                            Add Member
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($ministry)
        <!-- Key Metrics Cards -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Members</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministry->members_count ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-[#0d5c2f] rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        <span>Active members</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Activities</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministry->activities_count ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-calendar-alt text-white text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="fas fa-arrow-up text-blue-500 mr-1"></i>
                        <span>This year</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Budget Requests</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministry->budget_requests_count ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-coins text-white text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                        <span>Pending approval</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-gray-100 p-6 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Cash Inflows</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $ministry->cash_inflows_count ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-arrow-up text-white text-lg"></i>
                    </div>
                </div>
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500">
                        <i class="fas fa-arrow-up text-purple-500 mr-1"></i>
                        <span>This month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Activities Chart -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Activities Overview</h3>
                        <p class="text-sm text-gray-500">Monthly activity distribution</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-[#0d5c2f] rounded-full"></div>
                        <span class="text-xs text-gray-500">Activities</span>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="activitiesChart"></canvas>
                    <div id="activitiesEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-line text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Activities Yet</p>
                            <p class="text-gray-400 text-xs mt-1">Start by creating your first ministry activity</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Requests Monthly Chart -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Budget Requests</h3>
                        <p class="text-sm text-gray-500">Monthly budget request trends</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-xs text-gray-500">Amount (₱)</span>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="budgetRequestsChart"></canvas>
                    <div id="budgetRequestsEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-coins text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Budget Requests</p>
                            <p class="text-gray-400 text-xs mt-1">Budget requests will appear here once created</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Member Growth Chart -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Member Growth</h3>
                        <p class="text-sm text-gray-500">New members per month</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-xs text-gray-500">Members</span>
                    </div>
                </div>
                <div class="h-48 relative">
                    <canvas id="memberGrowthChart"></canvas>
                    <div id="memberGrowthEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user-plus text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No New Members</p>
                            <p class="text-gray-400 text-xs mt-1">Member growth will be tracked here</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cash Inflow Trends -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cash Inflow Trends</h3>
                        <p class="text-sm text-gray-500">Monthly cash inflow amounts</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        <span class="text-xs text-gray-500">Amount (₱)</span>
                    </div>
                </div>
                <div class="h-48 relative">
                    <canvas id="cashInflowChart"></canvas>
                    <div id="cashInflowEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-arrow-up text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Cash Inflows</p>
                            <p class="text-gray-400 text-xs mt-1">Cash inflow trends will appear here</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cash Inflow by Source -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Cash Inflow Sources</h3>
                        <p class="text-sm text-gray-500">Distribution by source type</p>
                    </div>
                </div>
                <div class="h-48 relative">
                    <canvas id="cashInflowSourceChart"></canvas>
                    <div id="cashInflowSourceEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-chart-pie text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Cash Inflows</p>
                            <p class="text-gray-400 text-xs mt-1">Source distribution will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Analysis Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Activity Completion Chart -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Activity Completion</h3>
                        <p class="text-sm text-gray-500">Completed vs upcoming activities</p>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="activityCompletionChart"></canvas>
                    <div id="activityCompletionEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-tasks text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Activities</p>
                            <p class="text-gray-400 text-xs mt-1">Activity completion will be tracked here</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Approval Rate Chart -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Budget Approval Rate</h3>
                        <p class="text-sm text-gray-500">Approval trends over time</p>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="budgetApprovalRateChart"></canvas>
                    <div id="budgetApprovalRateEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-bar text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Budget Requests</p>
                            <p class="text-gray-400 text-xs mt-1">Approval trends will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Analysis Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Member Role Distribution -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Member Roles</h3>
                        <p class="text-sm text-gray-500">Distribution by member role</p>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="memberRoleChart"></canvas>
                    <div id="memberRoleEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-users text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Members Yet</p>
                            <p class="text-gray-400 text-xs mt-1">Member roles will appear here</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Budget Status Overview -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Budget Status</h3>
                        <p class="text-sm text-gray-500">Request status distribution</p>
                    </div>
                </div>
                <div class="h-64 relative">
                    <canvas id="budgetStatusChart"></canvas>
                    <div id="budgetStatusEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-chart-pie text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Budget Requests</p>
                            <p class="text-gray-400 text-xs mt-1">Status distribution will appear here</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Member Status and Recent Activities -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Member Status Chart -->
            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Member Status</h3>
                        <p class="text-sm text-gray-500">Active vs inactive members</p>
                    </div>
                </div>
                <div class="h-48 relative">
                    <canvas id="memberStatusChart"></canvas>
                    <div id="memberStatusEmptyState" class="hidden absolute inset-0 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user-check text-gray-400 text-lg"></i>
                            </div>
                            <p class="text-gray-500 text-sm font-medium">No Members</p>
                            <p class="text-gray-400 text-xs mt-1">Member status will appear here</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-lg border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Recent Activities</h3>
                        <p class="text-sm text-gray-500">Latest ministry activities</p>
                    </div>
                    <a href="{{ route('ministry.activities.index') }}" class="text-[#0d5c2f] hover:text-[#0a4a26] text-sm font-medium">
                        View All
                    </a>
                </div>
                <div class="space-y-4">
                    @if(isset($recentActivities) && $recentActivities->count() > 0)
                        @foreach($recentActivities as $activity)
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 bg-[#0d5c2f] rounded-lg flex items-center justify-center">
                                <i class="{{ $activity['icon'] }} text-white text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900">{{ $activity['title'] }}</h4>
                                <p class="text-sm text-gray-500">{{ $activity['date'] }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium 
                                    @if($activity['status'] === 'upcoming') bg-green-100 text-green-800
                                    @elseif($activity['status'] === 'completed') bg-blue-100 text-blue-800
                                    @else bg-yellow-100 text-yellow-800 @endif">
                                    {{ $activity['status_text'] }}
                                </span>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-calendar text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">No recent activities found</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('ministry.activities.create') }}" class="group p-4 rounded-lg border-2 border-gray-200 hover:border-[#0d5c2f] transition-all duration-200 hover:shadow-md">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-[#0d5c2f] rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-plus text-white text-lg"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 group-hover:text-[#0d5c2f]">New Activity</h4>
                        <p class="text-xs text-gray-500 mt-1">Create a new ministry activity</p>
                    </div>
                </a>
                
                <a href="{{ route('ministry.members.create') }}" class="group p-4 rounded-lg border-2 border-gray-200 hover:border-[#0d5c2f] transition-all duration-200 hover:shadow-md">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-user-plus text-white text-lg"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 group-hover:text-[#0d5c2f]">Add Member</h4>
                        <p class="text-xs text-gray-500 mt-1">Add a new ministry member</p>
                    </div>
                </a>
                
                <a href="{{ route('ministry.manual-cash-inflows.create') }}" class="group p-4 rounded-lg border-2 border-gray-200 hover:border-[#0d5c2f] transition-all duration-200 hover:shadow-md">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-coins text-white text-lg"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 group-hover:text-[#0d5c2f]">Cash Inflow</h4>
                        <p class="text-xs text-gray-500 mt-1">Record cash inflow</p>
                    </div>
                </a>
                
                <a href="{{ route('ministry.activities.index') }}" class="group p-4 rounded-lg border-2 border-gray-200 hover:border-[#0d5c2f] transition-all duration-200 hover:shadow-md">
                    <div class="text-center">
                        <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-chart-bar text-white text-lg"></i>
                        </div>
                        <h4 class="font-medium text-gray-900 group-hover:text-[#0d5c2f]">View Reports</h4>
                        <p class="text-xs text-gray-500 mt-1">View ministry reports</p>
                    </div>
                </a>
            </div>
        </div>
    @else
        <!-- No Ministry Assigned -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-8 text-center">
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-r from-yellow-100 to-yellow-200 flex items-center justify-center mx-auto mb-6 shadow-lg">
                <i class="fas fa-exclamation-triangle text-yellow-600 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Ministry Assigned</h3>
            <p class="text-gray-500 mb-8 max-w-md mx-auto">No ministry is assigned to your account yet. Please contact an administrator to get access to ministry features.</p>
            <a href="mailto:admin@stamarta-parish.com" 
               class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] hover:bg-[#0a4a26] text-white font-medium rounded-lg transition-all duration-200">
                <i class="fas fa-envelope mr-2"></i>
                Contact Administrator
            </a>
        </div>
    @endif
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Dashboard Charts JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Real data from backend
    const chartData = {
        activities: @json($activitiesData ?? ['labels' => [], 'data' => []]),
        budgetRequests: @json($budgetRequestsData ?? ['labels' => [], 'data' => []]),
        memberGrowth: @json($memberGrowthData ?? ['labels' => [], 'data' => []]),
        cashInflow: @json($cashInflowData ?? ['labels' => [], 'data' => []]),
        cashInflowSource: @json($cashInflowSourceData ?? ['labels' => [], 'data' => [], 'colors' => []]),
        activityCompletion: @json($activityCompletionData ?? ['labels' => [], 'datasets' => []]),
        budgetApprovalRate: @json($budgetApprovalRateData ?? ['labels' => [], 'datasets' => []]),
        memberRole: @json($memberRoleData ?? ['labels' => [], 'data' => [], 'colors' => []]),
        budgetStatus: @json($budgetStatusData ?? ['labels' => [], 'data' => [], 'colors' => []]),
        memberStatus: @json($memberStatusData ?? ['labels' => [], 'data' => [], 'colors' => []])
    };

    // Activities Chart
    const activitiesCtx = document.getElementById('activitiesChart');
    const activitiesEmptyState = document.getElementById('activitiesEmptyState');
    if (activitiesCtx) {
        if (chartData.activities.isEmpty) {
            activitiesCtx.style.display = 'none';
            activitiesEmptyState.classList.remove('hidden');
        } else {
            new Chart(activitiesCtx, {
                type: 'line',
                data: {
                    labels: chartData.activities.labels,
                    datasets: [{
                        label: 'Activities',
                        data: chartData.activities.data,
                        borderColor: '#0d5c2f',
                        backgroundColor: 'rgba(13, 92, 47, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#0d5c2f',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
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
                                color: 'rgba(0, 0, 0, 0.05)'
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

    // Budget Requests Chart
    const budgetRequestsCtx = document.getElementById('budgetRequestsChart');
    const budgetRequestsEmptyState = document.getElementById('budgetRequestsEmptyState');
    if (budgetRequestsCtx) {
        if (chartData.budgetRequests.isEmpty) {
            budgetRequestsCtx.style.display = 'none';
            budgetRequestsEmptyState.classList.remove('hidden');
        } else {
            new Chart(budgetRequestsCtx, {
                type: 'bar',
                data: {
                    labels: chartData.budgetRequests.labels,
                    datasets: [{
                        label: 'Budget Requests (₱)',
                        data: chartData.budgetRequests.data,
                        backgroundColor: 'rgba(16, 185, 129, 0.8)',
                        borderColor: '#10b981',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
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
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
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

    // Member Growth Chart
    const memberGrowthCtx = document.getElementById('memberGrowthChart');
    const memberGrowthEmptyState = document.getElementById('memberGrowthEmptyState');
    if (memberGrowthCtx) {
        if (chartData.memberGrowth.isEmpty) {
            memberGrowthCtx.style.display = 'none';
            memberGrowthEmptyState.classList.remove('hidden');
        } else {
            new Chart(memberGrowthCtx, {
                type: 'bar',
                data: {
                    labels: chartData.memberGrowth.labels,
                    datasets: [{
                        label: 'New Members',
                        data: chartData.memberGrowth.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.8)',
                        borderColor: '#3b82f6',
                        borderWidth: 2,
                        borderRadius: 6,
                        borderSkipped: false,
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
                                color: 'rgba(0, 0, 0, 0.05)'
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

    // Cash Inflow Chart
    const cashInflowCtx = document.getElementById('cashInflowChart');
    const cashInflowEmptyState = document.getElementById('cashInflowEmptyState');
    if (cashInflowCtx) {
        if (chartData.cashInflow.isEmpty) {
            cashInflowCtx.style.display = 'none';
            cashInflowEmptyState.classList.remove('hidden');
        } else {
            new Chart(cashInflowCtx, {
                type: 'line',
                data: {
                    labels: chartData.cashInflow.labels,
                    datasets: [{
                        label: 'Cash Inflow (₱)',
                        data: chartData.cashInflow.data,
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#8b5cf6',
                        pointBorderColor: '#ffffff',
                        pointBorderWidth: 2,
                        pointRadius: 6
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
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return '₱' + value.toLocaleString();
                                }
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

    // Cash Inflow Source Chart
    const cashInflowSourceCtx = document.getElementById('cashInflowSourceChart');
    const cashInflowSourceEmptyState = document.getElementById('cashInflowSourceEmptyState');
    if (cashInflowSourceCtx) {
        if (chartData.cashInflowSource.isEmpty) {
            cashInflowSourceCtx.style.display = 'none';
            cashInflowSourceEmptyState.classList.remove('hidden');
        } else {
            new Chart(cashInflowSourceCtx, {
                type: 'doughnut',
                data: {
                    labels: chartData.cashInflowSource.labels,
                    datasets: [{
                        data: chartData.cashInflowSource.data,
                        backgroundColor: chartData.cashInflowSource.colors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 10
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Activity Completion Chart
    const activityCompletionCtx = document.getElementById('activityCompletionChart');
    const activityCompletionEmptyState = document.getElementById('activityCompletionEmptyState');
    if (activityCompletionCtx) {
        if (chartData.activityCompletion.isEmpty) {
            activityCompletionCtx.style.display = 'none';
            activityCompletionEmptyState.classList.remove('hidden');
        } else {
            new Chart(activityCompletionCtx, {
                type: 'bar',
                data: {
                    labels: chartData.activityCompletion.labels,
                    datasets: chartData.activityCompletion.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
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

    // Budget Approval Rate Chart
    const budgetApprovalRateCtx = document.getElementById('budgetApprovalRateChart');
    const budgetApprovalRateEmptyState = document.getElementById('budgetApprovalRateEmptyState');
    if (budgetApprovalRateCtx) {
        if (chartData.budgetApprovalRate.isEmpty) {
            budgetApprovalRateCtx.style.display = 'none';
            budgetApprovalRateEmptyState.classList.remove('hidden');
        } else {
            new Chart(budgetApprovalRateCtx, {
                type: 'bar',
                data: {
                    labels: chartData.budgetApprovalRate.labels,
                    datasets: chartData.budgetApprovalRate.datasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
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

    // Member Role Chart
    const memberRoleCtx = document.getElementById('memberRoleChart');
    const memberRoleEmptyState = document.getElementById('memberRoleEmptyState');
    if (memberRoleCtx) {
        if (chartData.memberRole.isEmpty) {
            memberRoleCtx.style.display = 'none';
            memberRoleEmptyState.classList.remove('hidden');
        } else {
            new Chart(memberRoleCtx, {
                type: 'doughnut',
                data: {
                    labels: chartData.memberRole.labels,
                    datasets: [{
                        data: chartData.memberRole.data,
                        backgroundColor: chartData.memberRole.colors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    }

    // Budget Status Chart
    const budgetStatusCtx = document.getElementById('budgetStatusChart');
    const budgetStatusEmptyState = document.getElementById('budgetStatusEmptyState');
    if (budgetStatusCtx) {
        if (chartData.budgetStatus.isEmpty) {
            budgetStatusCtx.style.display = 'none';
            budgetStatusEmptyState.classList.remove('hidden');
        } else {
            new Chart(budgetStatusCtx, {
                type: 'pie',
                data: {
                    labels: chartData.budgetStatus.labels,
                    datasets: [{
                        data: chartData.budgetStatus.data,
                        backgroundColor: chartData.budgetStatus.colors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    }

    // Member Status Chart
    const memberStatusCtx = document.getElementById('memberStatusChart');
    const memberStatusEmptyState = document.getElementById('memberStatusEmptyState');
    if (memberStatusCtx) {
        if (chartData.memberStatus.isEmpty) {
            memberStatusCtx.style.display = 'none';
            memberStatusEmptyState.classList.remove('hidden');
        } else {
            new Chart(memberStatusCtx, {
                type: 'pie',
                data: {
                    labels: chartData.memberStatus.labels,
                    datasets: [{
                        data: chartData.memberStatus.data,
                        backgroundColor: chartData.memberStatus.colors,
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true
                            }
                        }
                    }
                }
            });
        }
    }
});
</script>

<!-- Custom CSS for enhanced styling -->
<style>
/* Smooth animations */
* {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Chart container styling */
canvas {
    border-radius: 8px;
}

/* Hover effects for quick actions */
.group:hover .group-hover\:scale-110 {
    transform: scale(1.1);
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 6px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

@endsection


