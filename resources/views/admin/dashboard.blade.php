@extends('admin.layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-gradient-to-r from-red-300 to-red-400 rounded-2xl p-8 text-white shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Selamat datang, {{ Auth::user()->name }}! 👋</h1>
                    <p class="text-blue-100">Ini adalah panel administrasi Dirental Atuh</p>
                </div>
                <div class="text-5xl">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Vehicles -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-blue-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Kendaraan</p>
                        <h3 class="text-2xl font-bold text-gray-800">25</h3>
                    </div>
                    <div class="text-blue-500 text-2xl">
                        <i class="fas fa-car-side"></i>
                    </div>
                </div>
            </div>

            <!-- Active Rentals -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Rental Aktif</p>
                        <h3 class="text-2xl font-bold text-gray-800">12</h3>
                    </div>
                    <div class="text-green-500 text-2xl">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Customer</p>
                        <h3 class="text-2xl font-bold text-gray-800">150</h3>
                    </div>
                    <div class="text-purple-500 text-2xl">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Denda Pembayaran Telat -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-purple-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Denda Pembayaran</p>
                        <h3 class="text-2xl font-bold text-gray-800">6</h3>
                    </div>
                    <div class="text-purple-500 text-2xl">
                        <i class="fas fa-money-bill"></i>
                    </div>
                </div>
            </div>

            <!-- Total Rentals -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-green-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Total Rental</p>
                        <h3 class="text-2xl font-bold text-gray-800">8</h3>
                    </div>
                    <div class="text-green-500 text-2xl">
                        <i class="fas fa-history"></i>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pembayaran Tertunda</p>
                        <h3 class="text-2xl font-bold text-gray-800">1</h3>
                    </div>
                    <div class="text-yellow-500 text-2xl">
                        <i class="fas fa-credit-card"></i>
                    </div>
                </div>
            </div>

            <!-- Revenue -->
            <div class="bg-white rounded-2xl p-6 shadow-lg border-l-4 border-yellow-500">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-500 text-sm">Pendapatan</p>
                        <h3 class="text-2xl font-bold text-gray-800">Rp 15.2Jt</h3>
                    </div>
                    <div class="text-yellow-500 text-2xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-6 shadow-lg">
                <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.kendaraan') }}"
                        class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-blue-500 hover:bg-blue-50 transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-plus-circle text-blue-500 text-xl"></i>
                            <span class="font-medium text-gray-700">Tambah Kendaraan Baru</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>

                    <a href="{{ route('admin.rental') }}"
                        class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:border-green-500 hover:bg-green-50 transition-all duration-200">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-eye text-green-500 text-xl"></i>
                            <span class="font-medium text-gray-700">Lihat Semua Rental</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </a>
                </div>
            </div>
        </div>
    @endsection
