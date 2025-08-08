@props(['id', 'title', 'message', 'confirmText' => 'Confirm', 'cancelText' => 'Cancel', 'confirmClass' => 'bg-red-600 hover:bg-red-700'])

<div id="{{ $id }}" class="fixed inset-0 z-50 hidden">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black bg-opacity-50 transition-opacity"></div>
    
    <!-- Modal -->
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="relative bg-white rounded-xl shadow-xl max-w-md w-full mx-auto transform transition-all">
            <!-- Header -->
            <div class="flex items-center justify-between p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                <button type="button" onclick="closeModal('{{ $id }}')" 
                        class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <!-- Content -->
            <div class="p-6">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-gray-700">{{ $message }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex items-center justify-end space-x-3 p-6 border-t border-gray-200">
                <button type="button" onclick="closeModal('{{ $id }}')"
                        class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                    {{ $cancelText }}
                </button>
                <button type="button" onclick="confirmAction('{{ $id }}')"
                        class="px-4 py-2 text-white rounded-lg transition-colors {{ $confirmClass }}">
                    {{ $confirmText }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function confirmAction(modalId) {
    const modal = document.getElementById(modalId);
    const form = modal.querySelector('form');
    if (form) {
        form.submit();
    }
    closeModal(modalId);
}

// Close modal when clicking backdrop
document.addEventListener('DOMContentLoaded', function() {
    const modals = document.querySelectorAll('[id$="-modal"]');
    modals.forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal(this.id);
            }
        });
    });
});
</script> 