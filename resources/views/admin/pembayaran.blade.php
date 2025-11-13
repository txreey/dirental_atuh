@extends('admin.layouts.app')

@section('title', 'Kelola Pembayaran - Dirental Atuh')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Pembayaran</h2>
        <div class="flex space-x-4">
            <select id="statusFilter" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                <option value="">Semua Status</option>
                <option value="pending">Menunggu</option>
                <option value="paid">Dibayar</option>
                <option value="failed">Gagal</option>
                <option value="expired">Kadaluarsa</option>
            </select>
            <div class="relative">
                <input type="text" placeholder="Cari kode pembayaran..." class="border border-gray-300 rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-money-bill-wave text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Transaksi</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($totalTransaksi ?? '0') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Berhasil</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($pembayaranBerhasil ?? '0') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Menunggu</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($pembayaranPending ?? '0') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Gagal</p>
                    <p class="text-2xl font-bold text-gray-800">{{ number_format($pembayaranGagal ?? '0') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Pembayaran Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Batas Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pembayarans as $pembayaran)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $pembayaran->kode_pembayaran }}</div>
                            <div class="text-sm text-gray-500">{{ $pembayaran->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-red-600 font-bold text-sm">{{ strtoupper(substr($pembayaran->user->name, 0, 1)) }}</span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $pembayaran->user->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $pembayaran->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#{{ $pembayaran->rental_id }}</div>
                            <div class="text-sm text-gray-500">{{ $pembayaran->rental->kendaraan->nama }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @php
                                $metodeLabels = [
                                    'transfer_bank' => 'Transfer Bank',
                                    'virtual_account' => 'Virtual Account',
                                    'e_wallet' => 'E-Wallet',
                                    'cash' => 'Cash'
                                ];
                            @endphp
                            {{ $metodeLabels[$pembayaran->metode_pembayaran] ?? $pembayaran->metode_pembayaran }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paid' => 'bg-green-100 text-green-800',
                                    'failed' => 'bg-red-100 text-red-800',
                                    'expired' => 'bg-gray-100 text-gray-800'
                                ];
                            @endphp
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$pembayaran->status] }}">
                                @if($pembayaran->status == 'pending')
                                Menunggu
                                @elseif($pembayaran->status == 'paid')
                                Dibayar
                                @elseif($pembayaran->status == 'failed')
                                Gagal
                                @else
                                Kadaluarsa
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $pembayaran->batas_pembayaran->format('d M Y H:i') }}
                            @if($pembayaran->status == 'pending' && $pembayaran->batas_pembayaran->isPast())
                            <br><span class="text-red-600 text-xs">Telah kadaluarsa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition duration-200">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                                @if($pembayaran->status == 'pending' && $pembayaran->bukti_pembayaran)
                                <button class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-lg transition duration-200">
                                    <i class="fas fa-check mr-1"></i>Verifikasi
                                </button>
                                @endif
                                @if($pembayaran->bukti_pembayaran)
                                <button class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-3 py-1 rounded-lg transition duration-200">
                                    <i class="fas fa-image mr-1"></i>Bukti
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $pembayarans->links() }}
        </div>
    </div>
</div>

<script>
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    // Implement filter logic here
    window.location.href = '{{ route("admin.pembayaran") }}?status=' + status;
});
</script>
@endsection