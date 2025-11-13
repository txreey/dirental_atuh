@extends('admin.layouts.app')

@section('title', 'Kelola Customer - Dirental Atuh')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Customer</h2>
        <div class="flex space-x-4">
            <div class="relative">
                <input type="text" placeholder="Cari customer..." class="border border-gray-300 rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Customer Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($customers as $customer)
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition duration-200">
            <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 bg-gradient-to-r from-red-400 to-pink-500 rounded-full flex items-center justify-center">
                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($customer->name, 0, 1)) }}</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">{{ $customer->name }}</h3>
                    <p class="text-sm text-gray-600">{{ $customer->email }}</p>
                </div>
            </div>
            
            <div class="space-y-2 text-sm text-gray-600">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-phone w-4"></i>
                    <span>{{ $customer->phone ?? 'Belum ada nomor telepon' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-map-marker-alt w-4"></i>
                    <span class="truncate">{{ $customer->address ?? 'Belum ada alamat' }}</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-calendar-alt w-4"></i>
                    <span>Bergabung: {{ \Carbon\Carbon::parse($customer->created_at)->format('d M Y') }}</span>
                </div>
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">Total Rental:</span>
                    <span class="font-semibold">{{ $customer->rentals_count ?? 0 }}x</span>
                </div>
            </div>
            
            <div class="mt-4 flex space-x-2">
                <button class="flex-1 bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded text-sm transition duration-200">
                    <i class="fas fa-eye mr-1"></i> Detail
                </button>
                <button class="flex-1 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded text-sm transition duration-200">
                    <i class="fas fa-envelope mr-1"></i> Email
                </button>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection