@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Booking Details #' . $booking->id)

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Hero Header with Pattern Background -->
    <div class="relative bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/80 rounded-2xl shadow-lg overflow-hidden">
        <div class="absolute inset-0 bg-pattern opacity-10"></div>
        <div class="relative px-6 py-10 sm:px-10">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <div class="flex items-center">
                        <span class="inline-flex items-center justify-center w-12 h-12 rounded-lg bg-white/20 mr-3">
                            <i class="fas fa-calendar-check text-white text-lg"></i>
                        </span>
                        <h1 class="text-3xl font-bold text-white">Booking #{{ $booking->id }}</h1>
                    </div>
                    <p class="text-white/80 mt-2 ml-15">{{ $booking->service->name }}</p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        {{ $booking->status_badge }} animate-pulse-slow">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.index') : route('admin.bookings.index') }}" 
                       class="w-12 h-12 rounded-full bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-colors" title="Back to Bookings">
                        <i class="fas fa-arrow-left text-lg"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
 
    <div x-data="{ tab: 'info' }" class="space-y-4">
        <!-- Tabs Nav -->
        <div class="bg-white border border-gray-200 rounded-xl p-2 sticky top-0 z-10 shadow-sm">
            <div class="flex flex-wrap gap-2">
                <button @click="tab='info'" :class="tab==='info' ? 'bg-[#0d5c2f] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-info-circle mr-1.5"></i>Booking Info
                </button>
                <button @click="tab='docs'" :class="tab==='docs' ? 'bg-[#0d5c2f] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-file-alt mr-1.5"></i>Documents
                </button>
                <button @click="tab='payment'" :class="tab==='payment' ? 'bg-[#0d5c2f] text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200'" class="px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                    <i class="fas fa-money-bill-wave mr-1.5"></i>Payment
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-8 space-y-6">
                <!-- Booking Information Card -->
                <div x-show="tab==='info'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-[#0d5c2f]"></i>
                            Booking Information
                        </h2>
                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">Created: {{ $booking->created_at->format('M d, Y g:i A') }}</span>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-concierge-bell text-[#0d5c2f] mr-2"></i>
                                    Service Details
                                </h3>
                                <div class="space-y-3">
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Service:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->service->name }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Date & Time:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->formatted_date }} at {{ $booking->formatted_time }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Duration:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->service->formatted_duration }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Fees:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->service->formatted_fees }}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-user text-[#0d5c2f] mr-2"></i>
                                    Contact Information
                                </h3>
                                <div class="space-y-3">
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Name:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->user->name }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Phone:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->contact_phone }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Address:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->contact_address }}</p>
                                    </div>
                                    <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 hover:border-[#0d5c2f]/30 transition-all group">
                                        <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Email:</span>
                                        <p class="text-gray-900 text-sm font-medium">{{ $booking->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($booking->additional_notes)
                            <div class="mt-5 pt-4 border-t border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-sticky-note text-[#0d5c2f] mr-2"></i>
                                    Additional Notes
                                </h3>
                                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                    <p class="text-gray-700 text-sm">{{ $booking->additional_notes }}</p>
                                </div>
                            </div>
                        @endif

                        @if($booking->custom_data && count($booking->custom_data) > 0)
                            <div class="mt-5 pt-4 border-t border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                    <i class="fas fa-clipboard-list text-[#0d5c2f] mr-2"></i>
                                    Service-Specific Information
                                </h3>
                                <div class="space-y-4">
                                    @php
                                        $serviceType = $booking->service->service_type ?? 'general';
                                    @endphp
                                    
                                    @if(in_array($serviceType, ['solo_baptism', 'group_baptism']))
                                        <!-- Baptism Information -->
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-baby text-[#0d5c2f] mr-2"></i>Child's Information
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @if(isset($booking->custom_data['child_last_name']) || isset($booking->custom_data['child_first_name']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Child's Name:</span>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $booking->custom_data['child_first_name'] ?? '' }} 
                                                            {{ $booking->custom_data['child_middle_initial'] ?? '' }} 
                                                            {{ $booking->custom_data['child_last_name'] ?? '' }}
                                                        </p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['child_birth_date']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Birth Date:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->custom_data['child_birth_date'])->format('F d, Y') }}</p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['place_of_birth']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Place of Birth:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ $booking->custom_data['place_of_birth'] }}</p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['nationality']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Nationality:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ $booking->custom_data['nationality'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Parents Information -->
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-users text-[#0d5c2f] mr-2"></i>Parents' Information
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @if(isset($booking->custom_data['father_last_name']) || isset($booking->custom_data['father_first_name']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Father's Name:</span>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $booking->custom_data['father_first_name'] ?? '' }} 
                                                            {{ $booking->custom_data['father_middle_initial'] ?? '' }} 
                                                            {{ $booking->custom_data['father_last_name'] ?? '' }}
                                                        </p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['mother_last_name']) || isset($booking->custom_data['mother_first_name']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Mother's Name:</span>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $booking->custom_data['mother_first_name'] ?? '' }} 
                                                            {{ $booking->custom_data['mother_middle_initial'] ?? '' }} 
                                                            {{ $booking->custom_data['mother_last_name'] ?? '' }}
                                                        </p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Godparents -->
                                        @if(isset($booking->custom_data['godparents']) && is_array($booking->custom_data['godparents']))
                                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-user-friends text-[#0d5c2f] mr-2"></i>Godparents
                                                </h4>
                                                <div class="space-y-2">
                                                    @foreach($booking->custom_data['godparents'] as $index => $godparent)
                                                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                            <span class="text-xs font-medium text-gray-500">Godparent {{ $index + 1 }}:</span>
                                                            <p class="text-sm font-medium text-gray-900">{{ $godparent }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                    @elseif($serviceType === 'wedding')
                                        <!-- Wedding Information -->
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-male text-[#0d5c2f] mr-2"></i>Groom's Information
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @if(isset($booking->custom_data['groom_name']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Name:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ $booking->custom_data['groom_name'] }}</p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['groom_birth_date']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Birth Date:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->custom_data['groom_birth_date'])->format('F d, Y') }}</p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['groom_religion']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Religion:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ $booking->custom_data['groom_religion'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-female text-[#0d5c2f] mr-2"></i>Bride's Information
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @if(isset($booking->custom_data['bride_name']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Name:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ $booking->custom_data['bride_name'] }}</p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['bride_birth_date']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Birth Date:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($booking->custom_data['bride_birth_date'])->format('F d, Y') }}</p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['bride_religion']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Religion:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ $booking->custom_data['bride_religion'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Witnesses -->
                                        @if(isset($booking->custom_data['witnesses']) && is_array($booking->custom_data['witnesses']))
                                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-user-friends text-[#0d5c2f] mr-2"></i>Witnesses
                                                </h4>
                                                <div class="space-y-2">
                                                    @foreach($booking->custom_data['witnesses'] as $index => $witness)
                                                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                            <span class="text-xs font-medium text-gray-500">Witness {{ $index + 1 }}:</span>
                                                            <p class="text-sm font-medium text-gray-900">{{ $witness }}</p>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                    @elseif($serviceType === 'blessing')
                                        <!-- Blessing Information -->
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                <i class="fas fa-user text-[#0d5c2f] mr-2"></i>Person Information
                                            </h4>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @if(isset($booking->custom_data['person_last_name']) || isset($booking->custom_data['person_first_name']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Person's Name:</span>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $booking->custom_data['person_first_name'] ?? '' }} 
                                                            {{ $booking->custom_data['person_middle_initial'] ?? '' }} 
                                                            {{ $booking->custom_data['person_last_name'] ?? '' }}
                                                        </p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['blessing_type']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                        <span class="text-xs font-medium text-gray-500">Type of Blessing:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ ucfirst(str_replace('_', ' ', $booking->custom_data['blessing_type'])) }}</p>
                                                    </div>
                                                @endif
                                                @if(isset($booking->custom_data['blessing_details']))
                                                    <div class="bg-white rounded-lg p-3 border border-gray-200 md:col-span-2">
                                                        <span class="text-xs font-medium text-gray-500">Details:</span>
                                                        <p class="text-sm font-medium text-gray-900">{{ $booking->custom_data['blessing_details'] }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                    @else
                                        <!-- Generic Custom Fields -->
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @foreach($booking->custom_data as $fieldKey => $fieldValue)
                                                    @if(is_string($fieldValue) || is_numeric($fieldValue))
                                                        <div class="bg-white rounded-lg p-3 border border-gray-200">
                                                            <span class="text-xs font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $fieldKey)) }}:</span>
                                                            <p class="text-sm font-medium text-gray-900">{{ $fieldValue }}</p>
                                                        </div>
                                                    @elseif(is_array($fieldValue))
                                                        <div class="bg-white rounded-lg p-3 border border-gray-200 md:col-span-2">
                                                            <span class="text-xs font-medium text-gray-500">{{ ucwords(str_replace('_', ' ', $fieldKey)) }}:</span>
                                                            <div class="mt-2 space-y-1">
                                                                @foreach($fieldValue as $index => $item)
                                                                    <p class="text-sm font-medium text-gray-900">{{ $index + 1 }}. {{ $item }}</p>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
 
                <!-- Documents -->
                <div x-show="tab==='docs'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-file-alt mr-2 text-[#0d5c2f]"></i>
                            Submitted Documents
                        </h2>
                        @if($booking->requirements_submitted && count($booking->requirements_submitted) > 0)
                            <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                {{ count($booking->requirements_submitted) - (isset($booking->requirements_submitted['conditional_answers']) ? 1 : 0) }} documents
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        @if($booking->requirements_submitted && count($booking->requirements_submitted) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($booking->requirements_submitted as $field => $path)
                                    @if($field !== 'conditional_answers')
                                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-[#0d5c2f]/30 transition-all duration-300">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center">
                                                    <span class="w-10 h-10 rounded-lg bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                                        <i class="fas fa-file-pdf text-[#0d5c2f] text-sm"></i>
                                                    </span>
                                                    <div>
                                                        <h4 class="text-sm font-medium text-gray-900 capitalize">
                                                            {{ str_replace('_', ' ', $field) }}
                                                        </h4>
                                                        <p class="text-xs text-gray-500 mt-0.5">Document uploaded</p>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <a href="{{ Storage::url($path) }}" target="_blank" title="View" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                                        <i class="fas fa-eye text-xs"></i>
                                                    </a>
                                                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.download-document', [$booking, $field]) : route('admin.bookings.download-document', [$booking, $field]) }}" 
                                                    title="Download" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-[#0d5c2f] text-white hover:bg-[#0d5c2f]/90 transition-colors">
                                                        <i class="fas fa-download text-xs"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="py-10">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-file-alt text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-base font-medium text-gray-900 mb-2">No Documents Submitted</h3>
                                    <p class="text-sm text-gray-500 max-w-md">
                                        No documents have been submitted for this booking yet.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
 
                <!-- Payment Information -->
                <div x-show="tab==='payment'" class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-money-bill-wave mr-2 text-[#0d5c2f]"></i>
                            Payment Information
                        </h2>
                        @if($booking->payment && $booking->payment->payment_status && is_string($booking->payment->payment_status))
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-medium {{ $booking->payment->payment_status_badge }}">
                                {{ ucfirst($booking->payment->payment_status) }}
                            </span>
                        @endif
                    </div>
                    <div class="p-4">
                        @if($booking->payment)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-receipt text-[#0d5c2f] mr-2"></i>
                                        Payment Details
                                    </h3>
                                    <div class="space-y-3">
                                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group hover:border-[#0d5c2f]/30 transition-all">
                                            <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Total Fee:</span>
                                            <p class="text-gray-900 text-sm font-semibold">{{ $booking->payment->formatted_total_fee }}</p>
                                        </div>
                                        <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group hover:border-[#0d5c2f]/30 transition-all">
                                            <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Payment Method:</span>
                                            <p class="text-gray-900 text-sm">{{ $booking->payment->payment_method_label }}</p>
                                        </div>
                                        @if($booking->payment->payment_reference)
                                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group hover:border-[#0d5c2f]/30 transition-all">
                                                <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Reference:</span>
                                                <p class="text-gray-900 text-sm">{{ $booking->payment->payment_reference }}</p>
                                            </div>
                                        @endif
                                        @if($booking->payment->payment_notes)
                                            <div class="bg-gray-50 rounded-xl p-3 border border-gray-100 group hover:border-[#0d5c2f]/30 transition-all">
                                                <span class="text-xs font-medium text-gray-500 group-hover:text-[#0d5c2f] transition-colors">Notes:</span>
                                                <p class="text-gray-900 text-sm">{{ $booking->payment->payment_notes }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900 mb-3 flex items-center">
                                        <i class="fas fa-file-invoice text-[#0d5c2f] mr-2"></i>
                                        Payment Proof
                                    </h3>
                                    @if($booking->payment->payment_proof)
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center justify-center">
                                            <div class="w-14 h-14 bg-[#0d5c2f]/10 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-file-image text-[#0d5c2f] text-xl"></i>
                                            </div>
                                            <p class="text-gray-700 mb-3 text-center text-sm">Payment proof has been uploaded</p>
                                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.download-payment-proof', $booking) : route('admin.bookings.download-payment-proof', $booking) }}" 
                                            class="inline-flex items-center px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors text-sm">
                                                <i class="fas fa-download mr-2"></i>Download Proof
                                            </a>
                                        </div>
                                        <div class="mt-3 space-y-2">
                                            @if($booking->payment->payment_submitted_at)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-500">Submitted:</span>
                                                    <span class="text-gray-700">{{ $booking->payment->payment_submitted_at->format('M d, Y g:i A') }}</span>
                                                </div>
                                            @endif
                                            @if($booking->payment->payment_verified_at)
                                                <div class="flex items-center justify-between text-xs">
                                                    <span class="text-gray-500">Verified:</span>
                                                    <span class="text-gray-700">{{ $booking->payment->payment_verified_at->format('M d, Y g:i A') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 flex flex-col items-center justify-center">
                                            <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                <i class="fas fa-file-upload text-gray-400 text-xl"></i>
                                            </div>
                                            <p class="text-gray-500 text-center text-sm">No payment proof has been uploaded yet</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <!-- Empty Payment State -->
                            <div class="py-10">
                                <div class="flex flex-col items-center justify-center text-center">
                                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                        <i class="fas fa-file-invoice-dollar text-gray-400 text-2xl"></i>
                                    </div>
                                    <h3 class="text-base font-medium text-gray-900 mb-2">No Payment Information</h3>
                                    <p class="text-sm text-gray-500 max-w-md mb-6">
                                        This booking doesn't have any payment details yet. Payment information will appear here once the booking is acknowledged.
                                    </p>
                                    
                                    @if($booking->status === 'pending')
                                        <button onclick="openAcknowledgeModal()" 
                                                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center justify-center text-sm">
                                            <i class="fas fa-check mr-2"></i>Acknowledge Booking
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
 
            <!-- Sidebar -->
            <div class="lg:col-span-4 space-y-6">
                <!-- Booking Summary -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-chart-simple mr-2 text-[#0d5c2f]"></i>
                            Booking Summary
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div class="bg-[#0d5c2f]/5 rounded-xl p-4 text-center">
                                <span class="text-2xl font-bold text-[#0d5c2f]">{{ $booking->id }}</span>
                                <p class="text-xs text-gray-600 mt-1">Booking ID</p>
                            </div>
                            <div class="bg-[#0d5c2f]/5 rounded-xl p-4 text-center">
                                <span class="text-2xl font-bold text-[#0d5c2f]">
                                    @if($booking->payment)
                                        {{ substr($booking->payment->formatted_total_fee, 1) }}
                                    @else
                                        0
                                    @endif
                                </span>
                                <p class="text-xs text-gray-600 mt-1">Total Fee (₱)</p>
                            </div>
                        </div>
                         
                        <div class="space-y-3">
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-plus text-[#0d5c2f] mr-2"></i>
                                    <span class="text-sm text-gray-700">Created</span>
                                </div>
                                <span class="text-sm font-medium">{{ $booking->created_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-day text-[#0d5c2f] mr-2"></i>
                                    <span class="text-sm text-gray-700">Service Date</span>
                                </div>
                                <span class="text-sm font-medium">{{ $booking->formatted_date }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2 border-b border-gray-100">
                                <div class="flex items-center">
                                    <i class="fas fa-clock text-[#0d5c2f] mr-2"></i>
                                    <span class="text-sm text-gray-700">Service Time</span>
                                </div>
                                <span class="text-sm font-medium">{{ $booking->formatted_time }}</span>
                            </div>
                            <div class="flex items-center justify-between py-2">
                                <div class="flex items-center">
                                    <i class="fas fa-user text-[#0d5c2f] mr-2"></i>
                                    <span class="text-sm text-gray-700">Booked By</span>
                                </div>
                                <span class="text-sm font-medium">{{ $booking->user->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Booking Timeline (always visible) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-history mr-2 text-[#0d5c2f]"></i>
                            Booking Timeline
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <div class="timeline-item flex">
                                <div class="timeline-left">
                                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-plus text-green-600 text-xs"></i>
                                    </div>
                                    <div class="timeline-line"></div>
                                </div>
                                <div class="ml-3 pb-6">
                                    <p class="text-xs font-medium text-gray-900">Booking Created</p>
                                    <p class="text-[10px] text-gray-500">{{ $booking->created_at->format('M d, Y g:i A') }}</p>
                                </div>
                            </div>
                            @foreach($booking->actions as $index => $action)
                                <div class="timeline-item flex">
                                    <div class="timeline-left">
                                        <div class="w-6 h-6 bg-{{ $action->action_color }}-100 rounded-full flex items-center justify-center">
                                            <i class="{{ $action->action_icon }} text-{{ $action->action_color }}-600 text-xs"></i>
                                        </div>
                                        @if(!$loop->last || $booking->payment && $booking->payment->payment_submitted_at)
                                            <div class="timeline-line"></div>
                                        @endif
                                    </div>
                                    <div class="ml-3 pb-6">
                                        <p class="text-xs font-medium text-gray-900">{{ $action->action_type_label }}</p>
                                        <p class="text-[10px] text-gray-500">{{ $action->created_at->format('M d, Y g:i A') }}</p>
                                        @if($action->notes)
                                            <p class="text-[10px] text-gray-600 mt-1 bg-gray-50 p-2 rounded border border-gray-100">{{ $action->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                            @if($booking->payment && $booking->payment->payment_submitted_at)
                                <div class="timeline-item flex">
                                    <div class="timeline-left">
                                        <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-upload text-blue-600 text-xs"></i>
                                        </div>
                                        @if($booking->payment && $booking->payment->payment_verified_at)
                                            <div class="timeline-line"></div>
                                        @endif
                                    </div>
                                    <div class="ml-3 pb-6">
                                        <p class="text-xs font-medium text-gray-900">Payment Proof Submitted</p>
                                        <p class="text-[10px] text-gray-500">{{ $booking->payment->payment_submitted_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                            @if($booking->payment && $booking->payment->payment_verified_at)
                                <div class="timeline-item flex">
                                    <div class="timeline-left">
                                        <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-check-double text-green-600 text-xs"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs font-medium text-gray-900">Payment Verified</p>
                                        <p class="text-[10px] text-gray-500">{{ $booking->payment->payment_verified_at->format('M d, Y g:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- User Information (always visible) -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-[#0d5c2f]"></i>
                            User Information
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center mr-3 overflow-hidden">
                                @if($booking->user->profile_photo_path)
                                    <img src="{{ Storage::url($booking->user->profile_photo_path) }}" alt="{{ $booking->user->name }}" class="w-full h-full object-cover">
                                @else
                                    <i class="fas fa-user text-gray-400"></i>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</h4>
                                <p class="text-xs text-gray-500">Member since {{ $booking->user->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.users.show', $booking->user) : route('admin.users.show', $booking->user) }}" 
                               class="w-full px-3 py-2 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg transition-colors flex items-center justify-center text-sm">
                                <i class="fas fa-user mr-2"></i>
                                <span>View User Profile</span>
                            </a>
                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.index', ['user_id' => $booking->user->id]) : route('admin.bookings.index', ['user_id' => $booking->user->id]) }}" 
                               class="w-full px-3 py-2 bg-green-50 hover:bg-green-100 text-green-700 rounded-lg transition-colors flex items-center justify-center text-sm">
                                <i class="fas fa-calendar-check mr-2"></i>
                                <span>View User's Bookings</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-base font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-bolt mr-2 text-[#0d5c2f]"></i>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-3">
                            <a href="#" 
                                onclick="window.print(); return false;"
                                class="w-full px-4 py-2.5 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-lg transition-colors flex items-center justify-center">
                                 <i class="fas fa-print mr-2"></i>
                                 <span>Print Booking Details</span>
                             </a>
                            
                            @if($booking->status === 'approved' || $booking->status === 'completed')
                                <a href="{{ isset($isStaff) && $isStaff ? route('staff.bookings.certificate', $booking) : route('admin.bookings.certificate', $booking) }}" 
                                   target="_blank"
                                   class="w-full px-4 py-2.5 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg transition-colors flex items-center justify-center">
                                    <i class="fas fa-certificate mr-2"></i>
                                    <span>Generate Certificate</span>
                                </a>
                            @endif
                            
                            @if(in_array($booking->status, ['pending', 'acknowledged', 'payment_hold']))
                                <button onclick="openCancelModal()" 
                                        class="w-full px-4 py-2.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg transition-colors flex items-center justify-center">
                                    <i class="fas fa-ban mr-2"></i>
                                    <span>Cancel Booking</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Acknowledge Modal -->
<div id="acknowledgeModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 animate-fade-in">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-lg w-full animate-slide-up">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Acknowledge Booking</h3>
                        <p class="text-sm text-gray-600">Set payment details and acknowledge the booking</p>
                    </div>
                </div>
                
                <form action="{{ isset($isStaff) && $isStaff ? route('staff.bookings.acknowledge', $booking) : route('admin.bookings.acknowledge', $booking) }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="total_fee" class="block text-sm font-medium text-gray-700 mb-2">
                                Total Fee (₱) *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₱</span>
                                <input type="number" 
                                       id="total_fee" 
                                       name="total_fee" 
                                       step="0.01" 
                                       min="0"
                                       required
                                       class="w-full pl-8 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                       placeholder="0.00"
                                       value="{{ $booking->service ? ($booking->service->getFeeForDate($booking->service_date)['amount'] ?? '') : '' }}">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter the total amount the user needs to pay</p>
                        </div>
                        
                        <div>
                            <label for="acknowledge_notes" class="block text-sm font-medium text-gray-700 mb-2">
                                Notes (Optional)
                            </label>
                            <textarea id="acknowledge_notes" 
                                      name="notes" 
                                      rows="3"
                                      class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                      placeholder="Add any notes about the acknowledgment or payment instructions"></textarea>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3 mt-6">
                        <button type="button" 
                                onclick="closeModal('acknowledgeModal')"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                            <i class="fas fa-check mr-2"></i>Acknowledge Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 animate-fade-in">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full animate-slide-up">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-times text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Reject Booking</h3>
                        <p class="text-sm text-gray-600">Please provide a reason for rejection</p>
                    </div>
                </div>
                
                <form action="{{ isset($isStaff) && $isStaff ? route('staff.bookings.reject', $booking) : route('admin.bookings.reject', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Rejection *
                        </label>
                        <textarea id="reject_notes" 
                                  name="notes" 
                                  rows="3"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                  placeholder="Please provide a reason for rejecting this booking"></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" 
                                onclick="closeModal('rejectModal')"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            <i class="fas fa-times mr-2"></i>Reject Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Payment Verification Modal -->
<div id="paymentVerificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 animate-fade-in">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-lg w-full animate-slide-up">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-double text-green-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Verify Payment</h3>
                        <p class="text-sm text-gray-600">Confirm payment and approve booking</p>
                    </div>
                </div>
                
                <div class="mb-6">
                    <div class="bg-gray-50 rounded-xl p-4 border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-gray-700">Payment Amount:</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $booking->payment ? $booking->payment->formatted_total_fee : 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-sm font-medium text-gray-700">Payment Method:</span>
                            <span class="text-sm text-gray-900">{{ $booking->payment ? $booking->payment->payment_method_label : 'N/A' }}</span>
                        </div>
                        @if($booking->payment && $booking->payment->payment_reference)
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-700">Reference:</span>
                                <span class="text-sm text-gray-900">{{ $booking->payment->payment_reference }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <form action="{{ isset($isStaff) && $isStaff ? route('staff.bookings.verify-payment', $booking) : route('admin.bookings.verify-payment', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="verification_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Notes (Optional)
                        </label>
                        <textarea id="verification_notes" 
                                  name="notes" 
                                  rows="2"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                  placeholder="Add any notes about the payment verification"></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" 
                                onclick="closeModal('paymentVerificationModal')"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                            <i class="fas fa-check-double mr-2"></i>Verify & Approve
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Cancel Booking Modal -->
<div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50 animate-fade-in">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full animate-slide-up">
            <div class="p-6">
                <div class="flex items-center mb-6">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-ban text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-semibold text-gray-900">Cancel Booking</h3>
                        <p class="text-sm text-gray-600">Are you sure you want to cancel this booking?</p>
                    </div>
                </div>
                
                <div class="bg-yellow-50 rounded-lg p-4 mb-6 border border-yellow-200">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                        <p class="text-sm text-yellow-700">
                            This action will cancel the booking and notify the user. This cannot be undone.
                        </p>
                    </div>
                </div>
                
                <form action="{{ isset($isStaff) && $isStaff ? route('staff.bookings.reject', $booking) : route('admin.bookings.reject', $booking) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="cancel_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Cancellation *
                        </label>
                        <textarea id="cancel_notes" 
                                  name="notes" 
                                  rows="3"
                                  required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                  placeholder="Please provide a reason for cancelling this booking"></textarea>
                    </div>
                    
                    <div class="flex items-center justify-end space-x-3">
                        <button type="button" 
                                onclick="closeModal('cancelModal')"
                                class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                            Go Back
                        </button>
                        <button type="submit" 
                                class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                            <i class="fas fa-ban mr-2"></i>Cancel Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .timeline-left {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .timeline-line {
        width: 2px;
        height: 100%;
        background-color: #e5e7eb;
        margin-top: 8px;
    }
    
    .animate-pulse-slow {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.5s ease forwards;
    }
    
    .animate-fade-in {
        animation: fadeIn 0.3s ease forwards;
    }
    
    .animate-slide-up {
        animation: slideUp 0.3s ease forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .bg-pattern {
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    }
</style>

<script>
function openAcknowledgeModal() {
    const el = document.getElementById('acknowledgeModal');
    if (el) el.classList.remove('hidden');
}

function openRejectModal() {
    const el = document.getElementById('rejectModal');
    if (el) el.classList.remove('hidden');
}

function openPaymentVerificationModal() {
    const el = document.getElementById('paymentVerificationModal');
    if (el) el.classList.remove('hidden');
}

function openCancelModal() {
    const el = document.getElementById('cancelModal');
    if (el) el.classList.remove('hidden');
}

function closeModal(modalId) {
    const el = document.getElementById(modalId);
    if (el) el.classList.add('hidden');
}

// Close modals when clicking outside their inner panel
['acknowledgeModal','rejectModal','paymentVerificationModal','cancelModal'].forEach(id => {
    const modal = document.getElementById(id);
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    }
});

// Reveal timeline items on load (for older entries without the new animation class)
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.timeline-item').forEach((item, index) => {
        setTimeout(() => {
            item.style.opacity = '1';
        }, 100 * (index + 1));
    });
});
</script>
@endsection
    