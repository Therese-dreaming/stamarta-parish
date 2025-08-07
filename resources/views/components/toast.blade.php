@if(session('success') || session('error') || session('warning') || session('info'))
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2">
    @if(session('success'))
        <div id="toast-success" class="bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between min-w-80 transform transition-all duration-300 translate-x-full">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-3"></i>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="closeToast('toast-success')" class="ml-4 text-white hover:text-green-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div id="toast-error" class="bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between min-w-80 transform transition-all duration-300 translate-x-full">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-3"></i>
                <span>{{ session('error') }}</span>
            </div>
            <button onclick="closeToast('toast-error')" class="ml-4 text-white hover:text-red-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('warning'))
        <div id="toast-warning" class="bg-yellow-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between min-w-80 transform transition-all duration-300 translate-x-full">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle mr-3"></i>
                <span>{{ session('warning') }}</span>
            </div>
            <button onclick="closeToast('toast-warning')" class="ml-4 text-white hover:text-yellow-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif

    @if(session('info'))
        <div id="toast-info" class="bg-blue-500 text-white px-6 py-4 rounded-lg shadow-lg flex items-center justify-between min-w-80 transform transition-all duration-300 translate-x-full">
            <div class="flex items-center">
                <i class="fas fa-info-circle mr-3"></i>
                <span>{{ session('info') }}</span>
            </div>
            <button onclick="closeToast('toast-info')" class="ml-4 text-white hover:text-blue-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show toasts with animation
    const toasts = document.querySelectorAll('#toast-container > div');
    toasts.forEach((toast, index) => {
        setTimeout(() => {
            toast.classList.remove('translate-x-full');
            toast.classList.add('translate-x-0');
        }, index * 100);
    });

    // Auto-hide toasts after 5 seconds
    toasts.forEach(toast => {
        setTimeout(() => {
            closeToast(toast.id);
        }, 5000);
    });
});

function closeToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.add('translate-x-full');
        toast.classList.remove('translate-x-0');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}
</script>
@endif 