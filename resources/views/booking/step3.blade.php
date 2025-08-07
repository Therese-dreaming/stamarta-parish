@extends('layouts.user')

@section('title', 'Book Service - Step 3')

@section('content')
<!-- Progress Bar -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <div class="flex items-center justify-center">
            <div class="flex items-center space-x-4">
                <div class="flex items-center">
                    <div class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-green-600">Service & Personal Info</span>
                </div>
                <div class="w-16 h-0.5 bg-green-500"></div>
                <div class="flex items-center">
                    <div class="bg-green-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-green-600">Schedule Selection</span>
                </div>
                <div class="w-16 h-0.5 bg-[#0d5c2f]"></div>
                <div class="flex items-center">
                    <div class="bg-[#0d5c2f] text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-semibold">3</div>
                    <span class="ml-2 text-sm font-medium text-[#0d5c2f]">Document Upload</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-8 bg-gray-50 min-h-screen">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Final Booking Summary -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Final Booking Summary</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Service Details</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Service:</span>
                                <span class="font-medium">{{ $service->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Duration:</span>
                                <span class="font-medium">{{ $service->formatted_duration }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Fees:</span>
                                <span class="font-medium text-[#0d5c2f]">{{ $service->formatted_fees }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Schedule</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Date:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($step2Data['selected_date'])->format('F d, Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Time:</span>
                                <span class="font-medium">{{ $step2Data['selected_time'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Day:</span>
                                <span class="font-medium">{{ \Carbon\Carbon::parse($step2Data['selected_date'])->format('l') }}</span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Contact Info</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Phone:</span>
                                <span class="font-medium">{{ $step1Data['contact_phone'] }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Address:</span>
                                <span class="font-medium text-sm">{{ Str::limit($step1Data['contact_address'], 30) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Upload Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-2xl font-bold text-gray-900">Document Upload</h2>
                <p class="text-gray-600 mt-1">Please upload the required documents for your service</p>
            </div>
            <form action="{{ route('booking.step3.store', $service) }}" method="POST" enctype="multipart/form-data" class="p-6">
                @csrf
                
                @php
                    $serviceType = $service->service_type ?? 'general';
                    $requirements = [];
                    $conditionalQuestions = [];
                    
                    switch($serviceType) {
                        case 'baptism':
                            $requirements = [
                                'birth_certificate' => 'Birth Certificate (Certified True Copy)'
                            ];
                            $conditionalQuestions = [
                                'parents_married' => [
                                    'question' => 'Are the parents married?',
                                    'field' => 'parents_marriage_contract',
                                    'label' => "Parents' Marriage Contract"
                                ],
                                'from_other_parish' => [
                                    'question' => 'Are you coming from another parish?',
                                    'field' => 'baptismal_permit',
                                    'label' => 'Baptismal Permit'
                                ]
                            ];
                            break;
                        case 'wedding':
                            $requirements = [
                                'baptismal_certificate' => 'Baptismal Certificate',
                                'confirmation_certificate' => 'Confirmation Certificate',
                                'cenomar' => 'CENOMAR',
                                'marriage_license' => 'Marriage License',
                                'id_pictures' => 'ID Pictures (2x2)'
                            ];
                            $conditionalQuestions = [
                                'civilly_married' => [
                                    'question' => 'Are you already civilly married?',
                                    'field' => 'civil_marriage_contract',
                                    'label' => 'Civil Marriage Contract'
                                ],
                                'cohabiting' => [
                                    'question' => 'Are you currently cohabiting?',
                                    'field' => 'affidavit_cohabitation',
                                    'label' => 'Affidavit of Cohabitation'
                                ]
                            ];
                            break;
                        case 'blessing':
                            $requirements = [
                                'valid_id' => 'Valid ID'
                            ];
                            $conditionalQuestions = [
                                'has_ownership' => [
                                    'question' => 'Do you need to prove ownership?',
                                    'field' => 'proof_ownership',
                                    'label' => 'Proof of Ownership'
                                ]
                            ];
                            break;
                        default:
                            $requirements = [
                                'valid_id' => 'Valid ID'
                            ];
                            $conditionalQuestions = [
                                'additional_docs' => [
                                    'question' => 'Do you have additional documents to submit?',
                                    'field' => 'additional_documents',
                                    'label' => 'Additional Documents'
                                ]
                            ];
                    }
                @endphp

                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Required Documents</h3>
                    <div class="space-y-4">
                        @foreach($requirements as $field => $label)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <label for="{{ $field }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $label }}
                                    <span class="text-red-500">*</span>
                                </label>
                                <div class="flex items-center space-x-4">
                                    <input type="file" 
                                           id="{{ $field }}" 
                                           name="documents[{{ $field }}]" 
                                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                           class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0d5c2f] file:text-white hover:file:bg-[#0d5c2f]/90"
                                           required>
                                    <div class="text-xs text-gray-500">
                                        <p>Accepted: PDF, JPG, PNG, DOC</p>
                                        <p>Max: 5MB per file</p>
                                    </div>
                                </div>
                                @error("documents.{$field}")
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Conditional Questions -->
                @if(!empty($conditionalQuestions))
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Additional Questions</h3>
                        <div class="space-y-4">
                            @foreach($conditionalQuestions as $questionKey => $questionData)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $questionData['question'] }}
                                        </label>
                                        <div class="flex space-x-4">
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       name="conditional_answers[{{ $questionKey }}]" 
                                                       value="yes" 
                                                       class="mr-2 text-[#0d5c2f] focus:ring-[#0d5c2f]"
                                                       data-field="{{ $questionData['field'] }}"
                                                       onchange="toggleDocumentUpload('{{ $questionData['field'] }}', this.value)">
                                                <span class="text-sm text-gray-700">Yes</span>
                                            </label>
                                            <label class="flex items-center">
                                                <input type="radio" 
                                                       name="conditional_answers[{{ $questionKey }}]" 
                                                       value="no" 
                                                       class="mr-2 text-[#0d5c2f] focus:ring-[#0d5c2f]"
                                                       data-field="{{ $questionData['field'] }}"
                                                       onchange="toggleDocumentUpload('{{ $questionData['field'] }}', this.value)">
                                                <span class="text-sm text-gray-700">No</span>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div id="upload-{{ $questionData['field'] }}" class="hidden">
                                        <label for="{{ $questionData['field'] }}" class="block text-sm font-medium text-gray-700 mb-2">
                                            {{ $questionData['label'] }}
                                        </label>
                                        <div class="flex items-center space-x-4">
                                            <input type="file" 
                                                   id="{{ $questionData['field'] }}" 
                                                   name="documents[{{ $questionData['field'] }}]" 
                                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#0d5c2f] file:text-white hover:file:bg-[#0d5c2f]/90">
                                            <div class="text-xs text-gray-500">
                                                <p>Accepted: PDF, JPG, PNG, DOC</p>
                                                <p>Max: 5MB per file</p>
                                            </div>
                                        </div>
                                        @error("documents.{$questionData['field']}")
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Additional Notes -->
                <div class="mb-6">
                    <label for="additional_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Additional Notes (Optional)
                    </label>
                    <textarea id="additional_notes" name="additional_notes" rows="4"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                              placeholder="Any additional information, special requests, or notes for the parish office"></textarea>
                    @error('additional_notes')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Important Information -->
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-0.5 mr-2"></i>
                        <div>
                            <h4 class="text-sm font-medium text-yellow-800 mb-1">Important Reminders</h4>
                            <ul class="text-sm text-yellow-700 space-y-1">
                                <li>• Please ensure all documents are clear and legible</li>
                                <li>• Bring original documents on the day of your service</li>
                                <li>• Arrive 30 minutes before your scheduled time</li>
                                <li>• Dress appropriately for the service</li>
                                <li>• Payment should be made at the parish office</li>
                                <li>• Contact the office if you need to reschedule or cancel</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Terms and Conditions -->
                <div class="mb-6">
                    <label class="flex items-start">
                        <input type="checkbox" name="terms_accepted" value="1"
                               class="mt-1 mr-3 w-4 h-4 text-[#0d5c2f] border-gray-300 rounded focus:ring-[#0d5c2f] focus:ring-2" required>
                        <span class="text-sm text-gray-700">
                            I understand and agree to the booking terms and conditions. I confirm that all information provided is accurate and I will bring all required documents on the scheduled date. I also confirm that the uploaded documents are authentic and belong to me or the person I am representing.
                        </span>
                    </label>
                    @error('terms_accepted')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between items-center pt-6 border-t border-gray-200">
                    <a href="{{ route('booking.step2', $service) }}" 
                       class="px-6 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Schedule
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                        <i class="fas fa-check mr-2"></i>Submit Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function toggleDocumentUpload(fieldName, value) {
    const uploadDiv = document.getElementById(`upload-${fieldName}`);
    const fileInput = document.getElementById(fieldName);
    
    if (value === 'yes') {
        uploadDiv.classList.remove('hidden');
        fileInput.required = true;
    } else {
        uploadDiv.classList.add('hidden');
        fileInput.required = false;
        fileInput.value = ''; // Clear the file input
    }
}

// Initialize on page load to handle any pre-selected values
document.addEventListener('DOMContentLoaded', function() {
    // Check for any pre-selected radio buttons and show/hide upload fields accordingly
    const radioButtons = document.querySelectorAll('input[type="radio"]:checked');
    radioButtons.forEach(radio => {
        const fieldName = radio.dataset.field;
        if (fieldName) {
            toggleDocumentUpload(fieldName, radio.value);
        }
    });
});
</script>
@endsection 