@extends('customer.layouts.app')

@section('title', 'Dashboard Customer - Dirental Atuh')

@section('content')
    <div class="space-y-8">
        <!-- Hero Welcome Section -->
        <div
            class="relative bg-gradient-to-br from-pink-500 via-pink-600 to-pink-700 rounded-3xl p-8 text-white shadow-2xl overflow-hidden">
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row items-center justify-between">
                    <div class="flex-1 mb-6 lg:mb-0">
                        <h1 class="text-4xl lg:text-5xl font-bold mb-4 leading-tight">
                            Selamat Datang, <span class="text-yellow-300">{{ Auth::user()->name }}! 👋</span>
                        </h1>
                        <p class="text-pink-100 text-lg mb-6 max-w-2xl">
                            Temukan kebebasan berkendara dengan armada terbaik kami.
                            Perjalanan Anda dimulai dari sini!
                        </p>
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('customer.kendaraan') }}"
                                class="bg-white text-pink-600 px-6 py-3 rounded-xl font-bold hover:bg-pink-50 transform hover:-translate-y-1 transition-all duration-300 shadow-lg">
                                🚗 Rental Sekarang
                            </a>
                            <button
                                class="border-2 border-white text-white px-6 py-3 rounded-xl font-bold hover:bg-white hover:text-pink-600 transition-all duration-300">
                                ℹ️ Pelajari Lebih Lanjut
                            </button>
                        </div>
                    </div>
                    <div class="flex-1 flex justify-center">
                        <div class="relative">
                            <div
                                class="w-64 h-64 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                <i class="fas fa-car text-8xl text-white"></i>
                            </div>
                            <div
                                class="absolute -top-4 -right-4 bg-yellow-400 text-pink-800 px-4 py-2 rounded-full font-bold animate-pulse">
                                🎉 PROMO!
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Background Pattern -->
            <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full -translate-y-36 translate-x-36"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white/5 rounded-full translate-y-48 -translate-x-48"></div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div
                class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-pink-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Rental Aktif</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">2</h3>
                        <p class="text-pink-500 text-sm mt-1">🟢 Sedang berjalan</p>
                    </div>
                    <div class="text-pink-500 text-3xl bg-pink-50 p-3 rounded-xl">
                        <i class="fas fa-car-side"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-pink-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Total Penyewaan</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">8</h3>
                        <p class="text-gray-500 text-sm mt-1">Sejak bergabung</p>
                    </div>
                    <div class="text-pink-500 text-3xl bg-pink-50 p-3 rounded-xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-pink-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Poin Reward</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">1,250</h3>
                        <p class="text-pink-500 text-sm mt-1">⭐ Member Gold</p>
                    </div>
                    <div class="text-pink-500 text-3xl bg-pink-50 p-3 rounded-xl">
                        <i class="fas fa-trophy"></i>
                    </div>
                </div>
            </div>

            <div
                class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-pink-500 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm font-medium">Diskon Aktif</p>
                        <h3 class="text-3xl font-bold text-gray-800 mt-2">15%</h3>
                        <p class="text-pink-500 text-sm mt-1">Untuk rental berikutnya</p>
                    </div>
                    <div class="text-pink-500 text-3xl bg-pink-50 p-3 rounded-xl">
                        <i class="fas fa-tag"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- What is Dirental Atuh? -->
        <div class="bg-gradient-to-r from-pink-50 to-pink-100 rounded-3xl p-8 shadow-lg">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-4">Apa Itu <span class="text-pink-600">Dirental Atuh</span>?
                </h2>
                <p class="text-gray-600 text-lg max-w-3xl mx-auto">
                    Platform rental kendaraan terpercaya yang memberikan pengalaman berkendara terbaik
                    dengan armada berkualitas, harga terjangkau, dan pelayanan 24/7.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-2xl text-pink-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Terpercaya</h3>
                    <p class="text-gray-600">Semua kendaraan terawat dengan asuransi lengkap untuk keamanan perjalanan Anda
                    </p>
                </div>

                <div class="text-center p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bolt text-2xl text-pink-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Cepat & Mudah</h3>
                    <p class="text-gray-600">Proses rental yang simpel, hanya 3 langkah untuk mendapatkan kendaraan impian
                    </p>
                </div>

                <div class="text-center p-6 bg-white rounded-2xl shadow-md hover:shadow-lg transition-all duration-300">
                    <div class="w-16 h-16 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-2xl text-pink-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Support 24/7</h3>
                    <p class="text-gray-600">Tim support siap membantu kapan saja selama masa rental berlangsung</p>
                </div>
            </div>
        </div>

        <!-- How It Works -->
        <div class="bg-white rounded-3xl p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Cara Rental <span class="text-pink-600">3
                    Langkah Mudah</span></h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center relative">
                    <div
                        class="w-20 h-20 bg-pink-500 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">
                        1
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Pilih Kendaraan</h3>
                    <p class="text-gray-600">Browse koleksi kendaraan kami dan pilih yang sesuai kebutuhan</p>
                    <div class="absolute top-10 right-0 w-8 h-0.5 bg-pink-200 hidden md:block"></div>
                </div>

                <div class="text-center relative">
                    <div
                        class="w-20 h-20 bg-pink-500 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">
                        2
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Isi Data & Bayar</h3>
                    <p class="text-gray-600">Lengkapi data rental dan lakukan pembayaran dengan metode pilihan</p>
                    <div class="absolute top-10 right-0 w-8 h-0.5 bg-pink-200 hidden md:block"></div>
                </div>

                <div class="text-center">
                    <div
                        class="w-20 h-20 bg-pink-500 text-white rounded-2xl flex items-center justify-center text-2xl font-bold mx-auto mb-4 shadow-lg">
                        3
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Ambil & Nikmati</h3>
                    <p class="text-gray-600">Ambil kendaraan di lokasi yang ditentukan dan nikmati perjalanan</p>
                </div>
            </div>
        </div>

        <!-- Quick Actions & Promo -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Quick Actions -->
            <div class="bg-white rounded-3xl p-8 shadow-lg">
                <h2 class="text-2xl font-bold text-gray-800 mb-6">Aksi Cepat 🚀</h2>
                <div class="space-y-4">
                    <a href="{{ route('customer.kendaraan') }}"
                        class="flex items-center justify-between p-5 bg-gradient-to-r from-pink-50 to-pink-50 rounded-2xl hover:from-pink-100 hover:to-pink-100 transition-all duration-300 group border border-pink-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center text-white">
                                <i class="fas fa-search"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Cari Kendaraan</h3>
                                <p class="text-sm text-gray-600">Temukan kendaraan perfect</p>
                            </div>
                        </div>
                        <i
                            class="fas fa-chevron-right text-gray-400 group-hover:text-pink-500 transform group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <a href="{{ route('customer.rental') }}"
                        class="flex items-center justify-between p-5 bg-gradient-to-r from-pink-50 to-pink-50 rounded-2xl hover:from-pink-100 hover:to-pink-100 transition-all duration-300 group border border-pink-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center text-white">
                                <i class="fas fa-history"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Riwayat Rental</h3>
                                <p class="text-sm text-gray-600">Lihat semua penyewaan Anda</p>
                            </div>
                        </div>
                        <i
                            class="fas fa-chevron-right text-gray-400 group-hover:text-pink-500 transform group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <a href="{{ route('customer.pembayaran') }}"
                        class="flex items-center justify-between p-5 bg-gradient-to-r from-pink-50 to-pink-50 rounded-2xl hover:from-pink-100 hover:to-pink-100 transition-all duration-300 group border border-pink-100">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-pink-500 rounded-xl flex items-center justify-center text-white">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-800">Pembayaran</h3>
                                <p class="text-sm text-gray-600">Kelola tagihan & pembayaran</p>
                            </div>
                        </div>
                        <i
                            class="fas fa-chevron-right text-gray-400 group-hover:text-pink-500 transform group-hover:translate-x-1 transition-all"></i>
                    </a>
                </div>
            </div>

            <!-- Promo & Offers -->
            <div class="bg-gradient-to-br from-pink-500 to-pink-600 rounded-3xl p-8 text-white shadow-2xl">
                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">🎉 Promo Spesial!</h2>
                        <p class="text-pink-100">Manfaatkan penawaran terbaik kami</p>
                    </div>
                    <div class="bg-yellow-400 text-pink-800 px-3 py-1 rounded-full text-sm font-bold animate-pulse">
                        HOT
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 border border-white/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold">Diskon 20%</h3>
                                <p class="text-pink-100 text-sm">Rental mingguan pertama</p>
                            </div>
                            <div class="bg-white text-pink-600 px-3 py-1 rounded-full text-sm font-bold">
                                Klik
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 border border-white/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold">Gratis Supir</h3>
                                <p class="text-pink-100 text-sm">Min. rental 3 hari</p>
                            </div>
                            <div class="bg-white text-pink-600 px-3 py-1 rounded-full text-sm font-bold">
                                Klik
                            </div>
                        </div>
                    </div>

                    <div class="bg-white/20 backdrop-blur-sm rounded-2xl p-4 border border-white/30">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-bold">Cashback 10%</h3>
                                <p class="text-pink-100 text-sm">Member loyalitas</p>
                            </div>
                            <div class="bg-white text-pink-600 px-3 py-1 rounded-full text-sm font-bold">
                                Klik
                            </div>
                        </div>
                    </div>
                </div>

                <button
                    class="w-full bg-white text-pink-600 py-3 rounded-xl font-bold mt-6 hover:bg-pink-50 transform hover:-translate-y-1 transition-all duration-300 shadow-lg">
                    Lihat Semua Promo
                </button>
            </div>
        </div>

        <!-- Vehicle Categories Preview -->
        <div class="bg-white rounded-3xl p-8 shadow-lg">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-8">Kategori <span
                    class="text-pink-600">Kendaraan</span></h2>

            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center group cursor-pointer">
                    <div
                        class="w-20 h-20 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-pink-200 transition-colors">
                        <i class="fas fa-car text-3xl text-pink-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">City Car</h3>
                    <p class="text-gray-600 text-sm">Rp 200K/hari</p>
                </div>

                <div class="text-center group cursor-pointer">
                    <div
                        class="w-20 h-20 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-pink-200 transition-colors">
                        <i class="fas fa-car text-3xl text-pink-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">SUV</h3>
                    <p class="text-gray-600 text-sm">Rp 450K/hari</p>
                </div>

                <div class="text-center group cursor-pointer">
                    <div
                        class="w-20 h-20 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-pink-200 transition-colors">
                        <i class="fas fa-shuttle-van text-3xl text-pink-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">MPV</h3>
                    <p class="text-gray-600 text-sm">Rp 350K/hari</p>
                </div>

                <div class="text-center group cursor-pointer">
                    <div
                        class="w-20 h-20 bg-pink-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-pink-200 transition-colors">
                        <i class="fas fa-motorcycle text-3xl text-pink-600"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Motor</h3>
                    <p class="text-gray-600 text-sm">Rp 75K/hari</p>
                </div>
            </div>
        </div>
    </div>
@endsection
