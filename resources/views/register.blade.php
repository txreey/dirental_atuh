<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Dirental Car Rental</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
            z-index: 10;
        }

        .input-field {
            padding: 1rem 1rem 1rem 3rem;
            width: 100%;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: white;
        }

        .input-field:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            transform: translateY(-2px);
        }

        .input-textarea {
            padding: 1rem 1rem 1rem 3rem;
            min-height: 100px;
            resize: vertical;
        }

        .floating-label {
            position: absolute;
            top: -10px;
            left: 12px;
            font-size: 0.75rem;
            background: white;
            padding: 0 8px;
            color: #6b7280;
            z-index: 10;
            transition: all 0.3s ease;
        }

        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            padding: 1rem 2rem;
            font-size: 1rem;
            font-weight: 600;
        }

        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
        }

        .error-message {
            margin-top: 0.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center py-8 px-4">
    <!-- Background Decorations -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-32 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-32 w-80 h-80 bg-white/10 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-lg">
        <!-- Main Card -->
        <div class="glass-card rounded-3xl shadow-2xl p-8">
            <!-- Header -->
            <div class="text-center mb-8">
                <div
                    class="w-20 h-20 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg">
                    <i class="fas fa-car text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Bergabung dengan Kami</h1>
                <p class="text-gray-600">Daftar akun baru untuk mulai menyewa kendaraan</p>
            </div>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                <div class="space-y-4">
                    <!-- Name Field -->
                    <div class="input-group">
                        <label class="floating-label">Nama Lengkap</label>
                        <i class="input-icon fas fa-user"></i>
                        <input id="name" name="name" type="text" required class="input-field"
                            value="{{ old('name') }}">
                        @error('name')
                            <p class="error-message text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Email Field -->
                    <div class="input-group">
                        <label class="floating-label">Alamat Email</label>
                        <i class="input-icon fas fa-envelope"></i>
                        <input id="email" name="email" type="email" required class="input-field"
                            value="{{ old('email') }}">
                        @error('email')
                            <p class="error-message text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Phone Field -->
                    <div class="input-group">
                        <label class="floating-label">Nomor Telepon</label>
                        <i class="input-icon fas fa-phone"></i>
                        <input id="phone" name="phone" type="text" required class="input-field"
                            value="{{ old('phone') }}">
                        @error('phone')
                            <p class="error-message text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Address Field -->
                    <div class="input-group">
                        <label class="floating-label">Alamat Lengkap</label>
                        <i class="input-icon fas fa-map-marker-alt"></i>
                        <textarea id="address" name="address" required class="input-field input-textarea">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="error-message text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="input-group">
                        <label class="floating-label">Password</label>
                        <i class="input-icon fas fa-lock"></i>
                        <input id="password" name="password" type="password" required class="input-field">
                        @error('password')
                            <p class="error-message text-red-500 flex items-center">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="input-group">
                        <label class="floating-label">Konfirmasi Password</label>
                        <i class="input-icon fas fa-lock"></i>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="input-field">
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                    class="btn-gradient w-full py-4 px-4 border border-transparent text-sm font-semibold rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 mt-6">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Sekarang
                </button>

                <!-- Login Link -->
                <div class="text-center pt-6">
                    <p class="text-gray-600">
                        Sudah punya akun?
                        <a href="{{ route('login') }}"
                            class="text-blue-600 hover:text-blue-500 font-semibold transition-colors duration-300">
                            <i class="fas fa-sign-in-alt mr-1"></i>
                            Login di sini
                        </a>
                    </p>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center mt-6">
            <p class="text-white/80 text-sm">
                &copy; 2024 Dirental Car Rental. All rights reserved.
            </p>
        </div>
    </div>

    <script>
        // Add floating label animation
        document.querySelectorAll('.input-field').forEach(input => {
            // Check if field has value on load
            if (input.value) {
                input.parentElement.querySelector('.floating-label').style.color = '#3b82f6';
            }

            input.addEventListener('focus', function() {
                this.parentElement.querySelector('.floating-label').style.color = '#3b82f6';
            });

            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.querySelector('.floating-label').style.color = '#6b7280';
                }
            });
        });
    </script>
</body>

</html>
