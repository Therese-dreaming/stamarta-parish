@extends('layouts.user')

@section('title', 'Book Service - Step 1')

@section('content')
<!-- Modern Progress Indicator -->
<div class="bg-white shadow-sm border-b border-gray-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-4 sm:mb-0">
            <h1 class="text-2xl font-bold text-gray-900 mb-2 sm:mb-0">Book {{ $service->name }}</h1>
            <div class="text-sm text-gray-500">Step 1 of 3</div>
        </div>
        
        <div class="relative mt-4">
            <!-- Progress Bar Background -->
            <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                <div class="h-full bg-[#0d5c2f] rounded-full" style="width: 33.33%"></div>
            </div>
            
            <!-- Step Indicators -->
            <div class="flex justify-between items-center mt-2">
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-[#0d5c2f] text-white flex items-center justify-center text-sm font-medium shadow-md">
                        <i class="fas fa-user"></i>
                    </div>
                    <span class="text-xs font-medium text-[#0d5c2f] mt-2">Personal Info</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500 mt-2">Schedule</span>
                </div>
                
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full bg-gray-200 text-gray-500 flex items-center justify-center text-sm font-medium">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <span class="text-xs font-medium text-gray-500 mt-2">Requirements</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Service Information Card -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 sticky top-6">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-bold text-gray-900">Service Details</h2>
                            <div class="w-10 h-10 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center">
                                <i class="fas fa-concierge-bell text-[#0d5c2f]"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $service->name }}</h3>
                        
                        @if($service->description)
                            <p class="text-gray-600 mb-6 text-sm">{{ $service->description }}</p>
                        @endif
                        
                        <div class="space-y-4">
                            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                        <i class="fas fa-clock text-[#0d5c2f] text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Duration</span>
                                </div>
                                <span class="text-sm text-gray-600">{{ $service->formatted_duration }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                        <i class="fas fa-users text-[#0d5c2f] text-sm"></i>
                                    </div>
                                    <span class="text-sm font-medium text-gray-700">Capacity</span>
                                </div>
                                <span class="text-sm text-gray-600">Max {{ $service->max_slots }} slot(s)</span>
                            </div>
                            
                            <div class="flex items-center justify-between py-3 border-t border-gray-100">
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                        <i class="fas fa-money-bill-wave text-[#0d5c2f] text-sm"></i>
                                    </div>
                                </div>
                                <span class="text-sm font-semibold text-[#0d5c2f]">{{ $service->formatted_fees }}</span>
                            </div>
                            
                            @if($service->schedules)
                                <div class="pt-3 border-t border-gray-100">
                                    <div class="flex items-center mb-3">
                                        <div class="w-8 h-8 rounded-full bg-[#0d5c2f]/10 flex items-center justify-center mr-3">
                                            <i class="fas fa-calendar-alt text-[#0d5c2f] text-sm"></i>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700">Available Times</span>
                                    </div>
                                    
                                    <div class="text-xs text-gray-600 space-y-2 ml-11">
                                        @foreach($service->schedules as $day => $times)
                                            <div>
                                                <span class="font-medium text-gray-700">{{ ucfirst($day) }}:</span> 
                                                {{ implode(', ', $times) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Personal Information Form -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                    <div class="p-6 border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-900">Personal Information</h2>
                        <p class="text-gray-500 text-sm mt-1">Please provide your contact details and service-specific information</p>
                    </div>
                    
                    <form action="{{ route('booking.step1.store', $service) }}" method="POST" class="p-6">
                        @csrf
                        
                        <div class="space-y-6">
                            <!-- Contact Information Section -->
                            <div>
                                <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-address-card text-[#0d5c2f] mr-2"></i>Contact Information
                                </h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Contact Phone -->
                                    <div>
                                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-1">
                                            Contact Phone <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-phone text-gray-400"></i>
                                            </div>
                                            <input type="tel" id="contact_phone" name="contact_phone" 
                                                value="{{ old('contact_phone', Auth::user()->phone ?? '') }}" required
                                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                placeholder="+63 912 345 6789">
                                        </div>
                                        @error('contact_phone')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Contact Address -->
                                    <div class="md:col-span-2">
                                        <label for="contact_address" class="block text-sm font-medium text-gray-700 mb-1">
                                            Contact Address <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <i class="fas fa-map-marker-alt text-gray-400"></i>
                                            </div>
                                            <textarea id="contact_address" name="contact_address" rows="2" required
                                                    class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                    placeholder="Enter your complete address">{{ old('contact_address') }}</textarea>
                                        </div>
                                        @error('contact_address')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Dynamic Custom Fields -->
                            @php
                                $customFields = \App\Services\ServiceConfigService::getCustomFields($service->service_type);
                            @endphp
                            
                            @if($customFields)
                                <div>
                                    <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                        <i class="fas fa-clipboard-list text-[#0d5c2f] mr-2"></i>Service-Specific Information
                                    </h3>

                                    @if(in_array($service->service_type, ['solo_baptism','group_baptism']))
                                        <div class="space-y-6">
                                            <!-- Child's Information Card -->
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-baby text-[#0d5c2f] mr-2"></i>Child's Information
                                                </h4>
                                                
                                                <!-- Child's Name Split -->
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Child's Name <span class="text-red-500">*</span></label>
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                        <input 
                                                            type="text"
                                                            id="custom_fields_child_last_name"
                                                            name="custom_fields[child_last_name]"
                                                            required
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                            placeholder="Last name"
                                                            value="{{ old('custom_fields.child_last_name') }}"
                                                        >
                                                        <input 
                                                            type="text"
                                                            id="custom_fields_child_first_name"
                                                            name="custom_fields[child_first_name]"
                                                            required
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                            placeholder="First name"
                                                            value="{{ old('custom_fields.child_first_name') }}"
                                                        >
                                                        <input 
                                                            type="text"
                                                            id="custom_fields_child_middle_initial"
                                                            name="custom_fields[child_middle_initial]"
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                            placeholder="M.I."
                                                            value="{{ old('custom_fields.child_middle_initial') }}"
                                                        >
                                                    </div>
                                                    @error('custom_fields.child_last_name')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                    @error('custom_fields.child_first_name')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Child Birth Date -->
                                                <div>
                                                    <label for="custom_fields_child_birth_date" class="block text-sm font-medium text-gray-700 mb-1">Child's Birth Date <span class="text-red-500">*</span></label>
                                                    <div class="relative">
                                                        <i class="fas fa-calendar-alt text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                                                        <input 
                                                            type="date"
                                                            id="custom_fields_child_birth_date"
                                                            name="custom_fields[child_birth_date]"
                                                            required
                                                            class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                            value="{{ old('custom_fields.child_birth_date') }}"
                                                        >
                                                    </div>
                                                    @error('custom_fields.child_birth_date')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Place of Birth and Nationality -->
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
                                                    <div>
                                                        <label for="custom_fields_place_of_birth" class="block text-sm font-medium text-gray-700 mb-1">Place of Birth <span class="text-red-500">*</span></label>
                                                        <input type="text" id="custom_fields_place_of_birth" name="custom_fields[place_of_birth]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="City/Municipality, Province" value="{{ old('custom_fields.place_of_birth') }}">
                                                        @error('custom_fields.place_of_birth')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label for="custom_fields_nationality" class="block text-sm font-medium text-gray-700 mb-1">Nationality <span class="text-red-500">*</span></label>
                                                        <input type="text" id="custom_fields_nationality" name="custom_fields[nationality]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="e.g., Filipino" value="{{ old('custom_fields.nationality') }}">
                                                        @error('custom_fields.nationality')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Parents Information Card -->
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-users text-[#0d5c2f] mr-2"></i>Parents' Information
                                                </h4>
                                                
                                                <!-- Father's Name -->
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Father's Name <span class="text-red-500">*</span></label>
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                        <input type="text" id="custom_fields_father_last_name" name="custom_fields[father_last_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Last name" value="{{ old('custom_fields.father_last_name') }}">
                                                        <input type="text" id="custom_fields_father_first_name" name="custom_fields[father_first_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="First name" value="{{ old('custom_fields.father_first_name') }}">
                                                        <input type="text" id="custom_fields_father_middle_initial" name="custom_fields[father_middle_initial]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="M.I." value="{{ old('custom_fields.father_middle_initial') }}">
                                                    </div>
                                                    @error('custom_fields.father_last_name')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                    @error('custom_fields.father_first_name')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Mother's Name -->
                                                <div>
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Mother's Name <span class="text-red-500">*</span></label>
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                        <input type="text" id="custom_fields_mother_last_name" name="custom_fields[mother_last_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Last name" value="{{ old('custom_fields.mother_last_name') }}">
                                                        <input type="text" id="custom_fields_mother_first_name" name="custom_fields[mother_first_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="First name" value="{{ old('custom_fields.mother_first_name') }}">
                                                        <input type="text" id="custom_fields_mother_middle_initial" name="custom_fields[mother_middle_initial]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="M.I." value="{{ old('custom_fields.mother_middle_initial') }}">
                                                    </div>
                                                    @error('custom_fields.mother_last_name')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                    @error('custom_fields.mother_first_name')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>

                                            <!-- Godparents Card -->
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-user-friends text-[#0d5c2f] mr-2"></i>Godparents <span class="text-red-500">*</span>
                                                </h4>
                                                
                                                <div id="godparents-list" class="space-y-2">
                                                    @php
                                                        $oldGodparents = old('custom_fields.godparents');
                                                        $godparents = is_array($oldGodparents) && count($oldGodparents) > 0 ? $oldGodparents : [''];
                                                    @endphp
                                                    @foreach($godparents as $idx => $gp)
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" name="custom_fields[godparents][{{ $idx }}]" value="{{ $gp }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Enter godparent's full name" required>
                                                            <button type="button" class="remove-godparent w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full" aria-label="Remove"><i class="fas fa-times"></i></button>
                                                        </div>
                                                        @error("custom_fields.godparents.$idx")
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    @endforeach
                                                </div>
                                                <button type="button" id="add-godparent" class="mt-3 px-4 py-2 text-sm text-[#0d5c2f] border border-[#0d5c2f] rounded-lg hover:bg-[#0d5c2f]/5 flex items-center">
                                                    <i class="fas fa-plus mr-2"></i> Add Godparent
                                                </button>
                                                @error('custom_fields.godparents')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    const list = document.getElementById('godparents-list');
                                                    const addBtn = document.getElementById('add-godparent');

                                                    function bindRemoveButtons() {
                                                        list.querySelectorAll('.remove-godparent').forEach(btn => {
                                                            btn.onclick = function () {
                                                                const item = this.closest('div.flex');
                                                                if (item) {
                                                                    item.remove();
                                                                    renumberFields();
                                                                }
                                                            };
                                                        });
                                                    }

                                                    function renumberFields() {
                                                        const rows = Array.from(list.querySelectorAll('div.flex'));
                                                        rows.forEach((row, i) => {
                                                            const input = row.querySelector('input[type="text"]');
                                                            if (input) {
                                                                input.name = `custom_fields[godparents][${i}]`;
                                                            }
                                                        });
                                                    }

                                                    addBtn?.addEventListener('click', function () {
                                                        const idx = list.querySelectorAll('div.flex').length;
                                                        const wrapper = document.createElement('div');
                                                        wrapper.className = 'flex items-center gap-2';
                                                        wrapper.innerHTML = `
                                                            <input type="text" name="custom_fields[godparents][${idx}]" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Enter godparent's full name" required>
                                                            <button type="button" class="remove-godparent w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full" aria-label="Remove"><i class="fas fa-times"></i></button>
                                                        `;
                                                        list.appendChild(wrapper);
                                                        bindRemoveButtons();
                                                    });

                                                    bindRemoveButtons();
                                                });
                                            </script>
                                        </div>
                                    @elseif($service->service_type === 'wedding')
                                        <div class="space-y-6">
                                            <!-- Groom Information Card -->
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-male text-[#0d5c2f] mr-2"></i>Groom's Information
                                                </h4>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div class="md:col-span-2">
                                                        <label for="custom_fields_groom_name" class="block text-sm font-medium text-gray-700 mb-1">Groom's Name <span class="text-red-500">*</span></label>
                                                        <input type="text" id="custom_fields_groom_name" name="custom_fields[groom_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Enter the groom's full name" value="{{ old('custom_fields.groom_name') }}">
                                                        @error('custom_fields.groom_name')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label for="custom_fields_groom_birth_date" class="block text-sm font-medium text-gray-700 mb-1">Groom's Birth Date <span class="text-red-500">*</span></label>
                                                        <div class="relative">
                                                            <i class="fas fa-calendar-alt text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                                                            <input type="date" id="custom_fields_groom_birth_date" name="custom_fields[groom_birth_date]" required class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" value="{{ old('custom_fields.groom_birth_date') }}">
                                                        </div>
                                                        @error('custom_fields.groom_birth_date')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label for="custom_fields_groom_religion" class="block text-sm font-medium text-gray-700 mb-1">Groom's Religion <span class="text-red-500">*</span></label>
                                                        <input type="text" id="custom_fields_groom_religion" name="custom_fields[groom_religion]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="e.g., Roman Catholic" value="{{ old('custom_fields.groom_religion') }}">
                                                        @error('custom_fields.groom_religion')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Bride Information Card -->
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-female text-[#0d5c2f] mr-2"></i>Bride's Information
                                                </h4>

                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                    <div class="md:col-span-2">
                                                        <label for="custom_fields_bride_name" class="block text-sm font-medium text-gray-700 mb-1">Bride's Name <span class="text-red-500">*</span></label>
                                                        <input type="text" id="custom_fields_bride_name" name="custom_fields[bride_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Enter the bride's full name" value="{{ old('custom_fields.bride_name') }}">
                                                        @error('custom_fields.bride_name')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label for="custom_fields_bride_birth_date" class="block text-sm font-medium text-gray-700 mb-1">Bride's Birth Date <span class="text-red-500">*</span></label>
                                                        <div class="relative">
                                                            <i class="fas fa-calendar-alt text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                                                            <input type="date" id="custom_fields_bride_birth_date" name="custom_fields[bride_birth_date]" required class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" value="{{ old('custom_fields.bride_birth_date') }}">
                                                        </div>
                                                        @error('custom_fields.bride_birth_date')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                    <div>
                                                        <label for="custom_fields_bride_religion" class="block text-sm font-medium text-gray-700 mb-1">Bride's Religion <span class="text-red-500">*</span></label>
                                                        <input type="text" id="custom_fields_bride_religion" name="custom_fields[bride_religion]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="e.g., Roman Catholic" value="{{ old('custom_fields.bride_religion') }}">
                                                        @error('custom_fields.bride_religion')
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Witnesses Card -->
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-user-friends text-[#0d5c2f] mr-2"></i>Witnesses <span class="text-red-500">*</span>
                                                </h4>

                                                @php
                                                    $oldWitnesses = old('custom_fields.witnesses');
                                                    $witnesses = is_array($oldWitnesses) && count($oldWitnesses) > 0 ? $oldWitnesses : [''];
                                                @endphp
                                                <div id="witnesses-list" class="space-y-2">
                                                    @foreach($witnesses as $idx => $wt)
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" name="custom_fields[witnesses][{{ $idx }}]" value="{{ $wt }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Enter witness' full name" required>
                                                            <button type="button" class="remove-witness w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full" aria-label="Remove"><i class="fas fa-times"></i></button>
                                                        </div>
                                                        @error("custom_fields.witnesses.$idx")
                                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                        @enderror
                                                    @endforeach
                                                </div>
                                                <button type="button" id="add-witness" class="mt-3 px-4 py-2 text-sm text-[#0d5c2f] border border-[#0d5c2f] rounded-lg hover:bg-[#0d5c2f]/5 flex items-center">
                                                    <i class="fas fa-plus mr-2"></i> Add Witness
                                                </button>
                                                @error('custom_fields.witnesses')
                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                @enderror
                                            </div>

                                            <script>
                                                document.addEventListener('DOMContentLoaded', function () {
                                                    const wList = document.getElementById('witnesses-list');
                                                    const addWBtn = document.getElementById('add-witness');

                                                    function bindRemoveWitness() {
                                                        wList.querySelectorAll('.remove-witness').forEach(btn => {
                                                            btn.onclick = function () {
                                                                const row = this.closest('div.flex');
                                                                if (row) row.remove();
                                                                renumberWitnesses();
                                                            };
                                                        });
                                                    }

                                                    function renumberWitnesses() {
                                                        const rows = Array.from(wList.querySelectorAll('div.flex'));
                                                        rows.forEach((row, i) => {
                                                            const input = row.querySelector('input[type="text"]');
                                                            if (input) {
                                                                input.name = `custom_fields[witnesses][${i}]`;
                                                            }
                                                        });
                                                    }

                                                    addWBtn?.addEventListener('click', function () {
                                                        const idx = wList.querySelectorAll('div.flex').length;
                                                        const wrapper = document.createElement('div');
                                                        wrapper.className = 'flex items-center gap-2';
                                                        wrapper.innerHTML = `
                                                            <input type="text" name="custom_fields[witnesses][${idx}]" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Enter witness' full name" required>
                                                            <button type="button" class="remove-witness w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full" aria-label="Remove"><i class="fas fa-times"></i></button>
                                                        `;
                                                        wList.appendChild(wrapper);
                                                        bindRemoveWitness();
                                                    });

                                                    bindRemoveWitness();
                                                });
                                            </script>
                                        </div>
                                    @elseif($service->service_type === 'blessing')
                                        <div class="space-y-6">
                                            <!-- Person Information Card -->
                                            <div class="border border-gray-200 rounded-lg p-4 bg-gray-50">
                                                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                                                    <i class="fas fa-user text-[#0d5c2f] mr-2"></i>Person Information
                                                </h4>
                                                <div class="mb-4">
                                                    <label class="block text-sm font-medium text-gray-700 mb-1">Person's Name <span class="text-red-500">*</span></label>
                                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                                                        <input type="text" id="custom_fields_person_last_name" name="custom_fields[person_last_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Last name" value="{{ old('custom_fields.person_last_name') }}">
                                                        <input type="text" id="custom_fields_person_first_name" name="custom_fields[person_first_name]" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="First name" value="{{ old('custom_fields.person_first_name') }}">
                                                        <input type="text" id="custom_fields_person_middle_initial" name="custom_fields[person_middle_initial]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="M.I." value="{{ old('custom_fields.person_middle_initial') }}">
                                                    </div>
                                                    @error('custom_fields.person_last_name')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                    @error('custom_fields.person_first_name')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Type of Blessing -->
                                                <div>
                                                    <label for="custom_fields_blessing_type" class="block text-sm font-medium text-gray-700 mb-1">Type of Blessing <span class="text-red-500">*</span></label>
                                                    <div class="relative">
                                                        <i class="fas fa-praying-hands text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                                                        <select id="custom_fields_blessing_type" name="custom_fields[blessing_type]" required class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]">
                                                            <option value="">Select Type</option>
                                                            <option value="house" {{ old('custom_fields.blessing_type')=='house' ? 'selected' : '' }}>House Blessing</option>
                                                            <option value="vehicle" {{ old('custom_fields.blessing_type')=='vehicle' ? 'selected' : '' }}>Vehicle Blessing</option>
                                                            <option value="business" {{ old('custom_fields.blessing_type')=='business' ? 'selected' : '' }}>Business Blessing</option>
                                                            <option value="other" {{ old('custom_fields.blessing_type')=='other' ? 'selected' : '' }}>Other</option>
                                                        </select>
                                                    </div>
                                                    @error('custom_fields.blessing_type')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>

                                                <!-- Details -->
                                                <div class="mt-4">
                                                    <label for="custom_fields_blessing_details" class="block text-sm font-medium text-gray-700 mb-1">Details (Optional)</label>
                                                    <textarea id="custom_fields_blessing_details" name="custom_fields[blessing_details]" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Additional details about the blessing">{{ old('custom_fields.blessing_details') }}</textarea>
                                                    @error('custom_fields.blessing_details')
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            @foreach($customFields as $fieldKey => $fieldConfig)
                                                <div class="{{ $fieldConfig['type'] === 'textarea' ? 'md:col-span-2' : '' }}">
                                                    <label for="custom_fields_{{ $fieldKey }}" class="block text-sm font-medium text-gray-700 mb-1">
                                                        {{ $fieldConfig['label'] }}
                                                        @if($fieldConfig['required'])
                                                            <span class="text-red-500">*</span>
                                                        @endif
                                                    </label>
                                                    @if($fieldConfig['type'] === 'textarea')
                                                        <textarea 
                                                            id="custom_fields_{{ $fieldKey }}" 
                                                            name="custom_fields[{{ $fieldKey }}]"
                                                            rows="3"
                                                            @if($fieldConfig['required']) required @endif
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                            placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                                        >{{ old("custom_fields.{$fieldKey}") }}</textarea>
                                                    @elseif($fieldConfig['type'] === 'select')
                                                        <select 
                                                            id="custom_fields_{{ $fieldKey }}" 
                                                            name="custom_fields[{{ $fieldKey }}]"
                                                            @if($fieldConfig['required']) required @endif
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                        >
                                                            <option value="">Select {{ $fieldConfig['label'] }}</option>
                                                            @foreach($fieldConfig['options'] as $value => $label)
                                                                <option value="{{ $value }}" {{ old("custom_fields.{$fieldKey}") == $value ? 'selected' : '' }}>
                                                                    {{ $label }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @elseif($fieldConfig['type'] === 'number')
                                                        <input 
                                                            type="number" 
                                                            id="custom_fields_{{ $fieldKey }}" 
                                                            name="custom_fields[{{ $fieldKey }}]"
                                                            @if($fieldConfig['required']) required @endif
                                                            @if(isset($fieldConfig['min'])) min="{{ $fieldConfig['min'] }}" @endif
                                                            @if(isset($fieldConfig['max'])) max="{{ $fieldConfig['max'] }}" @endif
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                            placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                                            value="{{ old("custom_fields.{$fieldKey}") }}"
                                                        >
                                                    @elseif($fieldConfig['type'] === 'date')
                                                        <div class="relative">
                                                            <i class="fas fa-calendar-alt text-gray-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                                                            <input 
                                                                type="date" 
                                                                id="custom_fields_{{ $fieldKey }}" 
                                                                name="custom_fields[{{ $fieldKey }}]"
                                                                @if($fieldConfig['required']) required @endif
                                                                class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                                value="{{ old("custom_fields.{$fieldKey}") }}"
                                                            >
                                                        </div>
                                                    @elseif($fieldConfig['type'] === 'array')
                                                        @php
                                                            $oldArray = old("custom_fields.$fieldKey");
                                                            $items = is_array($oldArray) && count($oldArray) > 0 ? $oldArray : [''];
                                                        @endphp
                                                        <div id="array-{{ $fieldKey }}-list" class="space-y-2">
                                                            @foreach($items as $idx => $val)
                                                                <div class="flex items-center gap-2">
                                                                    <input type="text" name="custom_fields[{{ $fieldKey }}][{{ $idx }}]" value="{{ $val }}" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="{{ $fieldConfig['placeholder'] ?? 'Enter value' }}" required>
                                                                    <button type="button" class="remove-array-item w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full" aria-label="Remove"><i class="fas fa-times"></i></button>
                                                                </div>
                                                                @error("custom_fields.$fieldKey.$idx")
                                                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                                @enderror
                                                            @endforeach
                                                        </div>
                                                        <button type="button" data-array-key="{{ $fieldKey }}" class="add-array-item mt-2 px-3 py-2 text-sm text-[#0d5c2f] border border-[#0d5c2f] rounded-lg hover:bg-[#0d5c2f]/5 flex items-center">
                                                            <i class="fas fa-plus mr-2"></i> Add
                                                        </button>
                                                    @else
                                                        <input 
                                                            type="{{ $fieldConfig['type'] }}" 
                                                            id="custom_fields_{{ $fieldKey }}" 
                                                            name="custom_fields[{{ $fieldKey }}]"
                                                            @if($fieldConfig['required']) required @endif
                                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                                            placeholder="{{ $fieldConfig['placeholder'] ?? '' }}"
                                                            value="{{ old("custom_fields.{$fieldKey}") }}"
                                                        >
                                                    @endif
                                                    @error("custom_fields.{$fieldKey}")
                                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Additional Notes -->
                            <div>
                                <h3 class="text-md font-semibold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-sticky-note text-[#0d5c2f] mr-2"></i>Additional Information
                                </h3>
                                
                                <div>
                                    <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-1">
                                        Additional Notes (Optional)
                                    </label>
                                    <textarea id="additional_notes" name="additional_notes" rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                                            placeholder="Any special requests or additional information">{{ old('additional_notes') }}</textarea>
                                    @error('additional_notes')
                                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Navigation Buttons -->
                            <div class="flex justify-between items-center mt-8 pt-6 border-t border-gray-200">
                                <a href="{{ route('services.index') }}" 
                                   class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-arrow-left mr-2"></i>Back to Services
                                </a>
                                <button type="submit" 
                                        class="px-6 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                                    Continue to Schedule Selection
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                            </div>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                // Generic array-type dynamic add/remove (e.g., witnesses)
                                document.querySelectorAll('.add-array-item').forEach(addBtn => {
                                    addBtn.addEventListener('click', function () {
                                        const key = this.getAttribute('data-array-key');
                                        const list = document.getElementById(`array-${key}-list`);
                                        const idx = list.querySelectorAll('div.flex').length;
                                        const wrapper = document.createElement('div');
                                        wrapper.className = 'flex items-center gap-2';
                                        wrapper.innerHTML = `
                                            <input type="text" name="custom_fields[${key}][${idx}]" class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]" placeholder="Enter value" required>
                                            <button type="button" class="remove-array-item w-8 h-8 flex items-center justify-center text-red-500 hover:bg-red-50 rounded-full" aria-label="Remove"><i class="fas fa-times"></i></button>
                                        `;
                                        list.appendChild(wrapper);
                                        bindGenericArrayRemove(list);
                                    });
                                });

                                function bindGenericArrayRemove(scope) {
                                    (scope || document).querySelectorAll('.remove-array-item').forEach(btn => {
                                        btn.onclick = function () {
                                            const row = this.closest('div.flex');
                                            if (row) row.remove();
                                        };
                                    });
                                }

                                bindGenericArrayRemove();
                            });
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection