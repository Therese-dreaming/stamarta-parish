<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Verify Email | Santa Marta | San Roque</title>
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://site-assets.fontawesome.com/releases/v6.4.2/css/all.css">
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 font-['Poppins'] min-h-full flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg relative z-50">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ asset('images/church-logo.png') }}" alt="Logo" class="h-12 w-12">
                    <span class="ml-3 text-xl font-semibold text-[#0d5c2f]">SANTA MARTA | SAN ROQUE</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-[#0d5c2f] transition-colors">Home</a>
                    <a href="{{ route('contact') }}" class="text-gray-600 hover:text-[#0d5c2f] transition-colors">Contact</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-12">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <i class="fas fa-envelope text-green-600 text-xl"></i>
                </div>
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Check Your Email
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    We've sent a verification link to your email address.
                </p>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-4">What's next?</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-600">
                    <li>Check your email inbox (and spam folder)</li>
                    <li>Click the verification link in the email</li>
                    <li>Return here to sign in to your account</li>
                </ol>
            </div>

            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Didn't receive the email? Check your spam folder or 
                            <button onclick="openResendModal()" class="font-medium text-blue-800 hover:text-blue-900 underline">
                                request a new verification email
                            </button>.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('login') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#0d5c2f] hover:bg-[#0d5c2f]/90 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Back to Sign In
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#0d5c2f] text-white mt-auto">
        <div class="container mx-auto px-6 py-8">
            <div class="text-center text-white/60">
                <p>&copy; {{ date('Y') }} Santa Marta Parish. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Resend Verification Modal -->
    <div id="resendModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-xl max-w-md w-full">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Resend Verification Email</h3>
                </div>
                <form id="resendForm" class="p-6">
                    @csrf
                    <div class="mb-4">
                        <label for="resend_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="resend_email" name="email" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f]"
                               placeholder="Enter your email address">
                    </div>
                    
                    <div id="resendMessage" class="mb-4 hidden">
                        <div id="successMessage" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg hidden">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span id="successText"></span>
                        </div>
                        <div id="errorMessage" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg hidden">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span id="errorText"></span>
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeResendModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                            Send Email
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openResendModal() {
            document.getElementById('resendModal').classList.remove('hidden');
            document.getElementById('resend_email').focus();
        }

        function closeResendModal() {
            document.getElementById('resendModal').classList.add('hidden');
            document.getElementById('resendMessage').classList.add('hidden');
            document.getElementById('successMessage').classList.add('hidden');
            document.getElementById('errorMessage').classList.add('hidden');
            document.getElementById('resendForm').reset();
        }

        document.getElementById('resendForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('resend_email').value;
            const messageDiv = document.getElementById('resendMessage');
            const successDiv = document.getElementById('successMessage');
            const errorDiv = document.getElementById('errorMessage');
            const successText = document.getElementById('successText');
            const errorText = document.getElementById('errorText');
            
            // Hide previous messages
            messageDiv.classList.add('hidden');
            successDiv.classList.add('hidden');
            errorDiv.classList.add('hidden');
            
            fetch('{{ route("verification.send") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ email: email })
            })
            .then(response => response.json())
            .then(data => {
                messageDiv.classList.remove('hidden');
                if (data.success) {
                    successText.textContent = data.message;
                    successDiv.classList.remove('hidden');
                    // Close modal after 2 seconds
                    setTimeout(() => {
                        closeResendModal();
                    }, 2000);
                } else {
                    errorText.textContent = data.message;
                    errorDiv.classList.remove('hidden');
                }
            })
            .catch(error => {
                messageDiv.classList.remove('hidden');
                errorText.textContent = 'Error sending verification email. Please try again.';
                errorDiv.classList.remove('hidden');
            });
        });

        // Close modal when clicking outside
        document.getElementById('resendModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeResendModal();
            }
        });
    </script>
</body>
</html> 