@extends('customer.layouts.app')

@section('title', 'Pembayaran - Dirental Atuh')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Pembayaran</h2>
        <p class="text-gray-600">Kelola pembayaran rental kendaraan Anda</p>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-sm mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px">
                <button class="py-4 px-6 text-center border-b-2 border-green-500 text-green-500 font-medium">
                    Menunggu Pembayaran
                </button>
                <button class="py-4 px-6 text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                    Sudah Dibayar
                </button>
                <button class="py-4 px-6 text-center border-b-2 border-transparent text-gray-500 hover:text-gray-700 font-medium">
                    Semua
                </button>
            </nav>
        </div>
    </div>

    <!-- Payment List -->
    <div class="space-y-4">
        @foreach($pembayarans as $pembayaran)
        <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition duration-200">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="flex items-start space-x-4 mb-4 md:mb-0">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-car text-green-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">{{ $pembayaran->rental->kendaraan->nama }}</h3>
                        <p class="text-gray-600 text-sm">{{ $pembayaran->rental->kendaraan->merk }}</p>
                        <div class="flex items-center space-x-4 mt-2 text-sm text-gray-600">
                            <span><i class="fas fa-calendar-alt mr-1"></i> {{ $pembayaran->rental->tanggal_mulai->format('d M Y') }} - {{ $pembayaran->rental->tanggal_selesai->format('d M Y') }}</span>
                            <span><i class="fas fa-clock mr-1"></i> {{ $pembayaran->rental->total_hari }} hari</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col items-end">
                    <div class="text-right mb-2">
                        <div class="text-2xl font-bold text-gray-800">Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}</div>
                        <span class="text-sm text-gray-500">Total Pembayaran</span>
                    </div>
                    
                    <div class="flex space-x-2">
                        @if($pembayaran->status == 'pending')
                        <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                            Menunggu Pembayaran
                        </span>
                        <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-1 rounded-lg text-sm transition duration-200">
                            Bayar Sekarang
                        </button>
                        @elseif($pembayaran->status == 'paid')
                        <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">
                            Sudah Dibayar
                        </span>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Payment Details -->
            <div class="mt-4 pt-4 border-t border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Metode Pembayaran:</span>
                        <span class="font-semibold">{{ $pembayaran->metode_pembayaran ?? 'Belum dipilih' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Batas Pembayaran:</span>
                        <span class="font-semibold text-red-600">{{ $pembayaran->batas_pembayaran->format('d M Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Kode Pembayaran:</span>
                        <span class="font-semibold">{{ $pembayaran->kode_pembayaran }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection