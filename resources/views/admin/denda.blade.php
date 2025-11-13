@extends('admin.layouts.app')

@section('title', 'Kelola Denda - Dirental Atuh')

@section('content')
<div class="container mx-auto">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Denda</h2>
        <div class="flex space-x-4">
            <select id="statusFilter" class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                <option value="">Semua Status</option>
                <option value="unpaid">Belum Bayar</option>
                <option value="paid">Sudah Bayar</option>
            </select>
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Cari customer..." class="border border-gray-300 rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
    </div>

    <!-- SweetAlert Notif -->
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#2563eb',
                    timer: 3000
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#ef4444',
                    timer: 3000
                });
            });
        </script>
    @endif

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Denda</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($totalDenda, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Belum Dibayar</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($dendaBelumBayar, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Sudah Dibayar</p>
                    <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($dendaSudahBayar, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Total Kasus</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $totalKasus }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Denda Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rental ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Keterlambatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Denda</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="dendaTable">
                    @foreach($dendas as $denda)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                    <span class="text-red-600 font-bold text-sm">
                                        {{ strtoupper(substr($denda->user->name ?? 'N', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $denda->user->name ?? 'N/A' }}</div>
                                    <div class="text-sm text-gray-500">{{ $denda->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $denda->rental->kendaraan->nama ?? 'N/A' }}
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $denda->rental->kendaraan->merk ?? 'N/A' }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            #{{ $denda->rental_id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $denda->hari_keterlambatan }} hari
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            Rp {{ number_format($denda->jumlah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($denda->status == 'unpaid')
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Belum Bayar
                            </span>
                            @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Sudah Bayar
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($denda->tanggal_dibuat)->format('d M Y') }}
                            @if($denda->tanggal_dibayar)
                            <br><span class="text-gray-500">Bayar: {{ \Carbon\Carbon::parse($denda->tanggal_dibayar)->format('d M Y') }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <button onclick="showDetail({{ $denda->id }})" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition duration-200">
                                    <i class="fas fa-eye mr-1"></i>Detail
                                </button>
                                @if($denda->status == 'unpaid')
                                <button onclick="confirmPayment({{ $denda->id }})" class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-lg transition duration-200">
                                    <i class="fas fa-check mr-1"></i>Konfirmasi
                                </button>
                                @endif
                                <button onclick="deleteDenda({{ $denda->id }})" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition duration-200">
                                    <i class="fas fa-trash mr-1"></i>Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $dendas->links() }}
        </div>
    </div>
</div>

<!-- Modal Detail -->
<div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <div class="flex items-center justify-between p-6 border-b">
            <h3 class="text-xl font-semibold text-gray-800">Detail Denda</h3>
            <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
        </div>
        <div class="p-6" id="detailContent">
            <!-- Content akan diisi oleh JavaScript -->
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Filter by status
document.getElementById('statusFilter').addEventListener('change', function() {
    const status = this.value;
    window.location.href = `{{ route('admin.denda') }}?status=${status}`;
});

// Search functionality
document.getElementById('searchInput').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const rows = document.querySelectorAll('#dendaTable tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Show detail modal
function showDetail(dendaId) {
    // Fetch detail data via AJAX atau tampilkan data sederhana
    document.getElementById('detailModal').classList.remove('hidden');
    document.getElementById('detailContent').innerHTML = `
        <div class="flex justify-center">
            <div class="w-12 h-12 border-b-2 border-red-500 rounded-full animate-spin"></div>
        </div>
    `;
    
    // Implement AJAX call untuk ambil detail denda
    setTimeout(() => {
        document.getElementById('detailContent').innerHTML = `
            <p>Loading detail denda ID: ${dendaId}</p>
            <!-- Isi dengan data detail -->
        `;
    }, 1000);
}

function closeDetailModal() {
    document.getElementById('detailModal').classList.add('hidden');
}

// Konfirmasi pembayaran
function confirmPayment(dendaId) {
    Swal.fire({
        title: 'Konfirmasi Pembayaran',
        text: "Apakah Anda yakin ingin mengkonfirmasi pembayaran denda ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#10b981',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Konfirmasi!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form konfirmasi pembayaran
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('admin/denda') }}/${dendaId}/confirm`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'PUT';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
} 

// Hapus denda
function deleteDenda(dendaId) {
    Swal.fire({
        title: 'Hapus Denda',
        text: "Apakah Anda yakin ingin menghapus data denda ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            // Submit form hapus
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `{{ url('admin/denda') }}/${dendaId}`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodField = document.createElement('input');
            methodField.type = 'hidden';
            methodField.name = '_method';
            methodField.value = 'DELETE';
            
            form.appendChild(csrfToken);
            form.appendChild(methodField);
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush