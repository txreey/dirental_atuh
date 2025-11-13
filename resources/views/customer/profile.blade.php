@extends('customer.layouts.app')

@section('title', 'Profil Saya - Dirental Atuh')

@section('content')
<div class="container mx-auto max-w-4xl">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Profil Saya</h2>
        <p class="text-gray-600">Kelola informasi profil dan akun Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <!-- Profile Photo -->
                <div class="text-center mb-6">
                    <div class="w-24 h-24 bg-gradient-to-r from-green-400 to-teal-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="text-white font-bold text-2xl">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</h3>
                    <p class="text-gray-600 text-sm">{{ Auth::user()->email }}</p>
                    <span class="inline-block mt-2 bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                        Customer
                    </span>
                </div>

                <!-- Stats -->
                <div class="space-y-4 border-t border-gray-200 pt-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Rental</span>
                        <span class="font-semibold text-gray-800">{{ $totalRental }}x</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Member Since</span>
                        <span class="font-semibold text-gray-800">{{ Auth::user()->created_at->format('M Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Status</span>
                        <span class="font-semibold text-green-600">Aktif</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Profile Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm">
                <!-- Tabs -->
                <div class="border-b border-gray-200">
                    <nav class="flex -mb-px">
                        <button class="py-4 px-6 text-center border-b-2 border-green-500 text-green-500 font-medium">
                            Informasi Pribadi
                        </button>
                        <button class="py-4 px-6 text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                            Keamanan
                        </button>
                    </nav>
                </div>

                <!-- Form -->
                <form class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" value="{{ Auth::user()->name }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" value="{{ Auth::user()->email }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                            <input type="tel" value="{{ Auth::user()->phone ?? '' }}" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" 
                                   placeholder="Masukkan nomor telepon">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir</label>
                            <input type="date" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
                        <textarea rows="3" 
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"
                                  placeholder="Masukkan alamat lengkap">{{ Auth::user()->address ?? '' }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kota</label>
                            <input type="text" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" 
                                   placeholder="Masukkan kota">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                            <input type="text" 
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500" 
                                   placeholder="Masukkan kode pos">
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                            Batal
                        </button>
                        <button type="submit" class="px-6 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition duration-200">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection