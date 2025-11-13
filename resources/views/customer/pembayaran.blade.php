@extends('customer.layouts.app')

@section('title', 'Kelola Pembayaran - Dirental Atuh')

@section('content')
    <div class="container mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Kelola Pembayaran</h2>
            <div class="flex space-x-4">
                <select id="statusFilter"
                    class="border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
                    <option value="">Semua Status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="failed">Failed</option>
                    <option value="expired">Expired</option>
                </select>
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari customer..."
                        class="border border-gray-300 rounded-md pl-10 pr-4 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500">
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
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Total Pendapatan</p>
                        <p class="text-2xl font-bold text-gray-800">Rp
                            {{-- {{ number_format($totalPendapatan, 0, ',', '.') ??}} --}}
                            20.000
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Menunggu Konfirmasi</p>
                        <p class="text-2xl font-bold text-gray-800">
                            20
                            {{-- {{ $pendingPayments }} --}}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-check-circle text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Berhasil</p>
                        <p class="text-2xl font-bold text-gray-800">
                            {{-- {{ $successPayments }} --}}
                            20
                        </p>
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
                        <p class="text-2xl font-bold text-gray-800">
                            {{-- {{ $failedPayments }} --}}
                            20
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Kendaraan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode
                                Bayar</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Metode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="paymentTable">
                        @forelse($pembayarans as $pembayaran)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center mr-3">
                                            <span class="text-red-600 font-bold text-sm">
                                                {{ strtoupper(substr($pembayaran->user->name ?? 'N', 0, 1)) }}
                                            </span>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $pembayaran->user->name ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $pembayaran->user->email ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($pembayaran->rental && $pembayaran->rental->kendaraan)
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $pembayaran->rental->kendaraan->nama }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $pembayaran->rental->kendaraan->merk }}
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-400">Data kendaraan tidak tersedia</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <code class="bg-gray-100 px-2 py-1 rounded">{{ $pembayaran->kode_pembayaran }}</code>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                                    Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if ($pembayaran->metode_pembayaran == 'transfer_bank')
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">Transfer
                                            Bank</span>
                                    @elseif($pembayaran->metode_pembayaran == 'virtual_account')
                                        <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">Virtual
                                            Account</span>
                                    @elseif($pembayaran->metode_pembayaran == 'e_wallet')
                                        <span
                                            class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">E-Wallet</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-full text-xs">Cash</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($pembayaran->status == 'pending')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($pembayaran->status == 'paid')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Paid
                                        </span>
                                    @elseif($pembayaran->status == 'failed')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                            Failed
                                        </span>
                                    @elseif($pembayaran->status == 'expired')
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                            Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div>
                                        <div>Buat: {{ $pembayaran->created_at->format('d M Y') }}</div>
                                        @if ($pembayaran->batas_pembayaran)
                                            <div class="text-xs text-gray-500">Batas:
                                                {{ \Carbon\Carbon::parse($pembayaran->batas_pembayaran)->format('d M Y H:i') }}
                                            </div>
                                        @endif
                                        @if ($pembayaran->tanggal_bayar)
                                            <div class="text-xs text-green-600">Bayar:
                                                {{ \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->format('d M Y H:i') }}
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="showDetail({{ $pembayaran->id }})"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition duration-200">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </button>
                                        @if ($pembayaran->status == 'pending' && $pembayaran->bukti_pembayaran)
                                            <button onclick="verifyPayment({{ $pembayaran->id }})"
                                                class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-lg transition duration-200">
                                                <i class="fas fa-check mr-1"></i>Verifikasi
                                            </button>
                                        @endif
                                        @if ($pembayaran->bukti_pembayaran)
                                            <button onclick="viewProof({{ $pembayaran->id }})"
                                                class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-3 py-1 rounded-lg transition duration-200">
                                                <i class="fas fa-receipt mr-1"></i>Bukti
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                    <i class="fas fa-receipt text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-lg">Tidak ada data pembayaran</p>
                                    <p class="text-sm">Belum ada pembayaran yang tercatat.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            {{-- @if ($pembayarans->hasPages())
                <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                    {{ $pembayarans->links() }}
                </div>
            @endif --}}
        </div>
    </div>

    <!-- Modal Detail -->
    <div id="detailModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Detail Pembayaran</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>
            <div class="p-6" id="detailContent">
                <!-- Content akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>

    <!-- Modal Bukti Pembayaran -->
    <div id="proofModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-3xl mx-4">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-800">Bukti Pembayaran</h3>
                <button onclick="closeProofModal()" class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
            </div>
            <div class="p-6" id="proofContent">
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
            window.location.href = `{{ route('admin.pembayaran') }}?status=${status}`;
        });

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#paymentTable tr');

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
        function showDetail(pembayaranId) {
            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailContent').innerHTML = `
        <div class="flex justify-center">
            <div class="w-12 h-12 border-b-2 border-red-500 rounded-full animate-spin"></div>
        </div>
    `;

            // Implement AJAX call untuk ambil detail pembayaran
            fetch(`/admin/pembayaran/${pembayaranId}/detail`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const payment = data.data;
                        document.getElementById('detailContent').innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Kode Pembayaran</label>
                                <p class="font-semibold">${payment.kode_pembayaran}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Jumlah</label>
                                <p class="font-semibold">Rp ${new Intl.NumberFormat('id-ID').format(payment.jumlah)}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="text-sm font-medium text-gray-500">Status</label>
                                <p><span class="px-2 py-1 rounded-full text-xs ${getStatusClass(payment.status)}">${getStatusText(payment.status)}</span></p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-gray-500">Metode</label>
                                <p>${getMethodText(payment.metode_pembayaran)}</p>
                            </div>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500">Keterangan</label>
                            <p>${payment.keterangan || '-'}</p>
                        </div>
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('detailContent').innerHTML =
                        '<p class="text-red-500">Gagal memuat detail pembayaran.</p>';
                });
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }

        // View bukti pembayaran
        function viewProof(pembayaranId) {
            document.getElementById('proofModal').classList.remove('hidden');
            document.getElementById('proofContent').innerHTML = `
        <div class="flex justify-center">
            <div class="w-12 h-12 border-b-2 border-red-500 rounded-full animate-spin"></div>
        </div>
    `;

            fetch(`/admin/pembayaran/${pembayaranId}/proof`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const payment = data.data;
                        document.getElementById('proofContent').innerHTML = `
                    <div class="text-center">
                        <img src="/storage/${payment.bukti_pembayaran}" alt="Bukti Pembayaran" class="max-w-full h-auto mx-auto rounded-lg shadow-md">
                        <p class="text-sm text-gray-500 mt-4">Bukti pembayaran untuk kode: ${payment.kode_pembayaran}</p>
                    </div>
                `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('proofContent').innerHTML =
                        '<p class="text-red-500">Gagal memuat bukti pembayaran.</p>';
                });
        }

        function closeProofModal() {
            document.getElementById('proofModal').classList.add('hidden');
        }

        // Verifikasi pembayaran
        function verifyPayment(pembayaranId) {
            Swal.fire({
                title: 'Verifikasi Pembayaran',
                text: "Apakah Anda yakin ingin memverifikasi pembayaran ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Verifikasi!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `{{ url('admin/pembayaran') }}/${pembayaranId}/verify`;

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

        // Helper functions
        function getStatusClass(status) {
            const classes = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'paid': 'bg-green-100 text-green-800',
                'failed': 'bg-red-100 text-red-800',
                'expired': 'bg-gray-100 text-gray-800'
            };
            return classes[status] || 'bg-gray-100 text-gray-800';
        }

        function getStatusText(status) {
            const texts = {
                'pending': 'Pending',
                'paid': 'Paid',
                'failed': 'Failed',
                'expired': 'Expired'
            };
            return texts[status] || status;
        }

        function getMethodText(method) {
            const texts = {
                'transfer_bank': 'Transfer Bank',
                'virtual_account': 'Virtual Account',
                'e_wallet': 'E-Wallet',
                'cash': 'Cash'
            };
            return texts[method] || method;
        }
    </script>
@endpush
