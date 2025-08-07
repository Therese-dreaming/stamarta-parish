@if(session('success') || session('error'))
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-3 w-auto max-w-sm">
    @if(session('success'))
        <div class="toast-message bg-white border-l-4 border-green-500 shadow-xl rounded-lg p-4 w-80 transform transition-all duration-300 ease-out opacity-0 translate-x-full" 
             style="animation: slideInRight 0.4s ease-out forwards;">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-semibold text-gray-900">Success!</p>
                    <p class="text-sm text-gray-600 mt-1">{{ session('success') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="closeToast(this)" 
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="toast-message bg-white border-l-4 border-red-500 shadow-xl rounded-lg p-4 w-80 transform transition-all duration-300 ease-out opacity-0 translate-x-full" 
             style="animation: slideInRight 0.4s ease-out forwards;">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-red-600"></i>
                    </div>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-semibold text-gray-900">Error!</p>
                    <p class="text-sm text-gray-600 mt-1">{{ session('error') }}</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                    <button onclick="closeToast(this)" 
                            class="text-gray-400 hover:text-gray-600 transition-colors duration-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
@keyframes slideInRight {
    0% {
        transform: translateX(100%);
        opacity: 0;
    }
    100% {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    0% {
        transform: translateX(0);
        opacity: 1;
    }
    100% {
        transform: translateX(100%);
        opacity: 0;
    }
}

.toast-message {
    backdrop-filter: blur(10px);
    background: rgba(255, 255, 255, 0.95);
    border: 1px solid rgba(209, 213, 219, 0.3);
    width: 320px;
    max-width: 90vw;
}

.toast-message:hover {
    transform: translateY(-1px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

#toast-container {
    width: auto;
    max-width: 320px;
}
</style>

<script>
function closeToast(button) {
    const toast = button.closest('.toast-message');
    if (toast) {
        toast.style.animation = 'slideOutRight 0.3s ease-in forwards';
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

// Auto-hide toasts after 5 seconds with smooth animation
setTimeout(() => {
    const toasts = document.querySelectorAll('.toast-message');
    toasts.forEach((toast, index) => {
        setTimeout(() => {
            toast.style.animation = 'slideOutRight 0.3s ease-in forwards';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, index * 100); // Stagger the animations
    });
}, 5000);
</script>
@endif 