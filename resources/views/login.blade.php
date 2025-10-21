<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Toko Buku</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .book-pattern {
            background: #f8fafc;
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
        }

        .btn-login {
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }

        .card-shadow {
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="book-pattern min-h-screen flex items-center justify-center p-4">

    {{-- Login Container --}}
    <div class="w-full max-w-md">

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl card-shadow overflow-hidden">

            {{-- Header Section --}}
            <div class="px-8 pt-10 pb-8 text-center border-b">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mb-4 shadow-lg">
                    <i class="fas fa-book text-white text-3xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-2">Toko Buku</h1>
                <p class="text-gray-500 text-sm">Masuk ke akun Anda untuk melanjutkan</p>
            </div>

            {{-- Form Section --}}
            <div class="px-8 py-8">

                {{-- Alert Messages (untuk Laravel) --}}
                {{-- Error Alert --}}
                <div id="errorAlert"
                    class="hidden bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg mb-5 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span id="errorMessage"></span>
                    </div>
                </div>

                {{-- Success Alert --}}
                <div id="successAlert"
                    class="hidden bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded-lg mb-5 text-sm">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span id="successMessage"></span>
                    </div>
                </div>

                {{-- Error Messages --}}
                @if ($errors->any())
                    <div id="errorAlert"
                        class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg mb-5 text-sm flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <div>
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Session Error --}}
                @if (session('error'))
                    <div id="errorAlert"
                        class="bg-red-50 border-l-4 border-red-500 text-red-700 p-3 rounded-lg mb-5 text-sm flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                {{-- Session Success --}}
                @if (session('success'))
                    <div id="successAlert"
                        class="bg-green-50 border-l-4 border-green-500 text-green-700 p-3 rounded-lg mb-5 text-sm flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif


                {{-- Login Form --}}
                <form id="loginForm" method="POST" action="{{ route('login.post') }}" class="space-y-5">
                    @csrf

                    {{-- Email Input --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-envelope"></i>
                            </span>
                            <input type="email" id="email" name="email" required placeholder="xxx@email.com"
                                class="input-focus w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                        </div>
                    </div>

                    {{-- Password Input --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
                                <i class="fas fa-lock"></i>
                            </span>
                            <input type="password" id="password" name="password" required
                                placeholder="Masukkan password"
                                class="input-focus w-full pl-10 pr-12 py-3 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                            <button type="button" id="togglePassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <button type="submit"
                        class="btn-login w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg text-sm font-semibold">
                        <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                    </button>

                </form>

                {{-- Footer --}}
                <div class="text-center mt-6 text-sm text-blue-500">
                    <p>&copy; 2025 Toko Buku. All rights reserved.</p>
                </div>
            </div>

        </div>


    </div>

    <script>
        // Toggle Password Visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eyeIcon');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;

            if (type === 'password') {
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            } else {
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            }
        });

        // Form Submission (untuk demo, hapus ini kalau pakai Laravel)
        const loginForm = document.getElementById('loginForm');

        loginForm.addEventListener('submit', function(e) {
            // Uncomment baris di bawah untuk demo mode
            // e.preventDefault();

            // Demo validation (hapus kalau pakai Laravel)
            // const email = document.getElementById('email').value;
            // const password = document.getElementById('password').value;

            // if (email && password) {
            //     document.getElementById('successAlert').classList.remove('hidden');
            //     document.getElementById('successMessage').textContent = 'Login berhasil! Mengalihkan...';
            // }
        });
    </script>

</body>

</html>
