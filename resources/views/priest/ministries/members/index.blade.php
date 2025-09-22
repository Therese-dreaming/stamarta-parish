@extends("layouts.priest")

@section("title", $ministry->name . " - Members")
@section("content")
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route("priest.ministries.index") }}" class="text-white/80 hover:text-white transition-colors mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            {{ $ministry->name }} Members
                        </h1>
                    </div>
                    <p class="text-white/80 text-sm">View ministry members and their information (Read Only)</p>
                </div>
                <div class="flex items-center space-x-3">
                    @if($ministry->fund)
                    <a href="{{ route("admin.ministries.fund", $ministry) }}" 
                       class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow">
                        <i class="fas fa-chart-line mr-2 text-sm"></i>
                        <span>View Ledger</span>
                    </a>
                    @endif
                    <div class="px-4 py-2 rounded-lg bg-white/20 flex items-center text-white">
                        <i class="fas fa-eye mr-2 text-sm"></i>
                        <span>View Only</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ministry Head Section -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-orange-50 to-orange-100 px-6 py-4 border-b border-orange-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-orange-500 rounded-xl flex items-center justify-center shadow-sm mr-3">
                        <i class="fas fa-user-crown text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Ministry Head</h2>
                        <p class="text-sm text-gray-600">Leader and administrator of this ministry</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if($ministry->head)
                <div class="flex items-center space-x-4 p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg border border-orange-200">
                    <div class="h-12 w-12 rounded-full bg-gradient-to-r from-orange-500 to-orange-600 flex items-center justify-center shadow-sm">
                        <i class="fas fa-user text-white"></i>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $ministry->head->name }}</h3>
                        <p class="text-sm text-gray-600">{{ $ministry->head->email }}</p>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-500 text-white mt-1">
                            Ministry Head
                        </span>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Role</p>
                        <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $ministry->head->role)) }}</p>
                    </div>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-slash text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Head Assigned</h3>
                    <p class="text-gray-500">This ministry currently has no assigned head.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Regular Members Section -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 px-6 py-4 border-b border-blue-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-xl flex items-center justify-center shadow-sm mr-3">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">Ministry Members</h2>
                        <p class="text-sm text-gray-600">Regular members of this ministry</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-gray-900">{{ $ministry->members->count() }} Total Members</p>
                    <p class="text-xs text-gray-500">Including ministry head</p>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if($ministry->members->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($ministry->members as $member)
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-lg border border-blue-200 p-4 hover:shadow-md transition-shadow">
                        <div class="flex items-center space-x-3">
                            <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center justify-center shadow-sm">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-medium text-gray-900 truncate">{{ $member->user->name }}</h3>
                                <p class="text-sm text-gray-600 truncate">{{ $member->user->email }}</p>
                                <div class="flex items-center space-x-2 mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-500 text-white">
                                        Member
                                    </span>
                                    @if($member->user->id === $ministry->head_user_id)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-orange-500 text-white">
                                            Head
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-3 pt-3 border-t border-blue-200">
                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                                <div>
                                    <span class="font-medium">Joined:</span>
                                    <p>{{ $member->created_at->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <span class="font-medium">Role:</span>
                                    <p>{{ ucfirst(str_replace('_', ' ', $member->user->role)) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-users text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Members Yet</h3>
                    <p class="text-gray-500 max-w-sm mx-auto">This ministry doesn't have any members yet.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Summary Statistics -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <i class="fas fa-chart-bar mr-2 text-[#0d5c2f]"></i>
            Member Statistics
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg border border-blue-200">
                <div class="text-2xl font-bold text-blue-600">{{ $ministry->members->count() }}</div>
                <div class="text-sm text-gray-600">Total Members</div>
            </div>
            
            <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                <div class="text-2xl font-bold text-green-600">{{ $ministry->head ? 1 : 0 }}</div>
                <div class="text-sm text-gray-600">Ministry Head</div>
            </div>
            
            <div class="text-center p-4 bg-purple-50 rounded-lg border border-purple-200">
                <div class="text-2xl font-bold text-purple-600">
                    {{ $ministry->members->where('user.role', 'ministry_head')->count() }}
                </div>
                <div class="text-sm text-gray-600">Ministry Heads</div>
            </div>
            
            <div class="text-center p-4 bg-orange-50 rounded-lg border border-orange-200">
                <div class="text-2xl font-bold text-orange-600">
                    {{ $ministry->members->where('user.role', 'user')->count() }}
                </div>
                <div class="text-sm text-gray-600">Regular Users</div>
            </div>
        </div>
    </div>
</div>
@endsection
