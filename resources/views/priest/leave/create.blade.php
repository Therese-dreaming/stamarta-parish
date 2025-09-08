@extends('layouts.priest')

@section('title', 'File Leave')

@section('content')
<div class="font-[Poppins] min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-lg overflow-hidden mb-6">
            <div class="px-6 py-6 relative">
                <div class="absolute right-0 top-0 w-20 h-20 bg-white/10 rounded-bl-full"></div>
                <div class="absolute bottom-0 left-0 w-14 h-14 bg-white/5 rounded-tr-full"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white">File Leave Application</h1>
                        <p class="text-white/90">Submit your leave request for approval</p>
                    </div>
                    <a href="{{ route('priest.dashboard') }}" 
                       class="inline-flex items-center px-4 py-2 bg-white/10 text-white rounded-lg hover:bg-white/20 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>

        <!-- Leave Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-lg bg-[#0d5c2f]/10 text-[#0d5c2f] mr-3">
                        <i class="fas fa-file-alt"></i>
                    </span>
                    Leave Application Form
                </h2>
                <p class="text-sm text-gray-600 mt-1">Please fill out all required information</p>
            </div>

            <form action="{{ route('priest.leave.store') }}" method="POST" class="p-6">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Leave Type -->
                    <div class="md:col-span-2">
                        <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Leave Type <span class="text-red-500">*</span>
                        </label>
                        <select name="leave_type" id="leave_type" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                            <option value="">Select leave type</option>
                            <option value="pilgrimage" {{ old('leave_type') == 'pilgrimage' ? 'selected' : '' }}>Pilgrimage</option>
                            <option value="medical" {{ old('leave_type') == 'medical' ? 'selected' : '' }}>Medical</option>
                            <option value="personal" {{ old('leave_type') == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="other" {{ old('leave_type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('leave_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date" required
                               value="{{ old('start_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                        @error('start_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        <div id="overlap-warning" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                            <div class="flex items-start space-x-2">
                                <i class="fas fa-exclamation-triangle text-red-600 text-sm mt-0.5"></i>
                                <div>
                                    <p class="text-sm text-red-800 font-medium">Overlapping Leave Detected</p>
                                    <p class="text-xs text-red-700 mt-1" id="overlap-details"></p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- End Date -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-700 mb-2">
                            End Date <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date" required
                               value="{{ old('end_date') }}"
                               min="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors">
                        @error('end_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Reason -->
                    <div class="md:col-span-2">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for Leave <span class="text-red-500">*</span>
                        </label>
                        <textarea name="reason" id="reason" rows="4" required
                                  placeholder="Please provide a detailed reason for your leave request..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-none">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Contact Information -->
                    <div class="md:col-span-2">
                        <label for="contact_info" class="block text-sm font-medium text-gray-700 mb-2">
                            Contact Information During Leave <span class="text-red-500">*</span>
                        </label>
                        <textarea name="contact_info" id="contact_info" rows="3" required
                                  placeholder="Provide your contact details while on leave (phone, email, address)..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-none">{{ old('contact_info') }}</textarea>
                        @error('contact_info')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Emergency Contact -->
                    <div class="md:col-span-2">
                        <label for="emergency_contact" class="block text-sm font-medium text-gray-700 mb-2">
                            Emergency Contact <span class="text-red-500">*</span>
                        </label>
                        <textarea name="emergency_contact" id="emergency_contact" rows="3" required
                                  placeholder="Provide emergency contact information (name, relationship, phone, address)..."
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-[#0d5c2f] transition-colors resize-none">{{ old('emergency_contact') }}</textarea>
                        @error('emergency_contact')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Existing Leave Periods -->
                <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl mt-0.5"></i>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-blue-800 mb-3">Your Current Leave Applications</h3>
                            <div id="existing-leaves-list" class="space-y-2">
                                <p class="text-sm text-blue-700 italic">Loading your leave applications...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Important Notice -->
                <div class="mt-6 p-6 bg-yellow-50 border border-yellow-200 rounded-xl">
                    <div class="flex items-start space-x-3">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-xl mt-0.5"></i>
                        <div>
                            <h3 class="text-sm font-semibold text-yellow-800 mb-2">Important Notice</h3>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• Your leave application will be reviewed by the administration</li>
                                <li>• You will be marked as inactive during your leave period</li>
                                <li>• You will not be assigned to any new bookings while on leave</li>
                                <li>• Please ensure all your current bookings are properly handled</li>
                                <li>• <strong>You cannot file overlapping leave applications</strong></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Checkbox -->
                <div class="mt-6">
                    <label class="flex items-start space-x-3">
                        <input type="checkbox" name="confirmation" required
                               class="mt-1 h-4 w-4 text-[#0d5c2f] focus:ring-[#0d5c2f] border-gray-300 rounded">
                        <span class="text-sm text-gray-700">
                            I confirm that I have read and understood the terms above, and I agree to be marked as inactive during my leave period. <span class="text-red-500">*</span>
                        </span>
                    </label>
                    @error('confirmation')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="mt-8 flex items-center justify-end space-x-4">
                    <a href="{{ route('priest.dashboard') }}" 
                       class="px-6 py-3 text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-8 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors font-medium shadow-sm hover:shadow-md">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Submit Leave Application
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const overlapWarning = document.getElementById('overlap-warning');
    const overlapDetails = document.getElementById('overlap-details');
    const submitButton = document.querySelector('button[type="submit"]');
    
    let existingLeaves = [];
    
    // Fetch existing leaves on page load
    fetchExistingLeaves();
    
    function fetchExistingLeaves() {
        fetch('{{ route("priest.leave.existing") }}')
            .then(response => response.json())
            .then(data => {
                existingLeaves = data;
                populateExistingLeavesList();
                checkForOverlaps();
            })
            .catch(error => {
                console.error('Error fetching existing leaves:', error);
            });
    }
    
    function populateExistingLeavesList() {
        const existingLeavesList = document.getElementById('existing-leaves-list');
        
        if (existingLeaves.length === 0) {
            existingLeavesList.innerHTML = '<p class="text-sm text-blue-700 italic">No pending or approved leave applications found.</p>';
            return;
        }
        
        const leavesHtml = existingLeaves.map(leave => {
            const startDate = new Date(leave.start_date).toLocaleDateString();
            const endDate = new Date(leave.end_date).toLocaleDateString();
            const leaveType = leave.leave_type.charAt(0).toUpperCase() + leave.leave_type.slice(1);
            const status = leave.status.charAt(0).toUpperCase() + leave.status.slice(1);
            
            const statusColor = leave.status === 'approved' ? 'text-green-600' : 'text-yellow-600';
            const statusIcon = leave.status === 'approved' ? 'fa-check-circle' : 'fa-clock';
            
            return `
                <div class="flex items-center justify-between p-3 bg-white rounded-lg border border-blue-200">
                    <div class="flex items-center space-x-3">
                        <i class="fas ${statusIcon} ${statusColor}"></i>
                        <div>
                            <p class="text-sm font-medium text-blue-900">${leaveType} Leave</p>
                            <p class="text-xs text-blue-700">${startDate} - ${endDate}</p>
                        </div>
                    </div>
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${statusColor} bg-white border">
                        ${status}
                    </span>
                </div>
            `;
        }).join('');
        
        existingLeavesList.innerHTML = leavesHtml;
    }
    
    function checkForOverlaps() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        
        if (!startDate || !endDate) {
            hideOverlapWarning();
            return;
        }
        
        const overlappingLeave = findOverlappingLeave(startDate, endDate);
        
        if (overlappingLeave) {
            showOverlapWarning(overlappingLeave);
        } else {
            hideOverlapWarning();
        }
    }
    
    function findOverlappingLeave(startDate, endDate) {
        return existingLeaves.find(leave => {
            const leaveStart = new Date(leave.start_date);
            const leaveEnd = new Date(leave.end_date);
            const newStart = new Date(startDate);
            const newEnd = new Date(endDate);
            
            // Check for any overlap
            return (newStart <= leaveEnd && newEnd >= leaveStart);
        });
    }
    
    function showOverlapWarning(overlappingLeave) {
        const startDate = new Date(overlappingLeave.start_date).toLocaleDateString();
        const endDate = new Date(overlappingLeave.end_date).toLocaleDateString();
        const leaveType = overlappingLeave.leave_type.charAt(0).toUpperCase() + overlappingLeave.leave_type.slice(1);
        const status = overlappingLeave.status.charAt(0).toUpperCase() + overlappingLeave.status.slice(1);
        
        overlapDetails.textContent = `You have a ${status.toLowerCase()} ${leaveType.toLowerCase()} leave from ${startDate} to ${endDate}. Please choose different dates.`;
        overlapWarning.classList.remove('hidden');
        
        // Disable submit button
        submitButton.disabled = true;
        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
    }
    
    function hideOverlapWarning() {
        overlapWarning.classList.add('hidden');
        
        // Enable submit button
        submitButton.disabled = false;
        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
    }
    
    startDateInput.addEventListener('change', function() {
        if (this.value) {
            endDateInput.min = this.value;
            if (endDateInput.value && endDateInput.value < this.value) {
                endDateInput.value = this.value;
            }
        }
        checkForOverlaps();
    });
    
    endDateInput.addEventListener('change', function() {
        checkForOverlaps();
    });
});
</script>
@endsection
