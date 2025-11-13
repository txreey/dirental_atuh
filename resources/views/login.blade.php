<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dirental Atuh</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-pink-700 to-red-300 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full mx-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-black-800 mb-2">Login ke Dirental Atuh</h2>
            <p class="text-gray-600">Masuk ke akun Anda</p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-6">
                <input id="email" name="email" type="email" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    placeholder="Email address" value="{{ old('email') }}">
                @error('email')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <input id="password" name="password" type="password" required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                    placeholder="Password">
                @error('password')
                    <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full bg-gradient-to-r from-pink-500 to-red-300 text-white py-3 rounded-lg font-semibold hover:from-pink-600 hover:to-red-300 transition-all duration-200 transform hover:-translate-y-1 shadow-lg hover:shadow-xl">
                Login
            </button>

            <div class="text-center mt-6">
                <a href="{{ route('register') }}"
                    class="text-blue-500 hover:text-blue-700 font-medium transition-colors duration-200">
                    Belum punya akun? Daftar di sini
                </a>
            </div>
        </form>
    </div>
</body>

</html>
