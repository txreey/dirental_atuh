@extends('admin.layouts.app')

@section('title', 'Kelola Harga - Dirental Atuh')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-800">Kelola Harga</h2>
        <button onclick="openCreateModal()" 
            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition duration-200">
            <i class="fas fa-plus"></i>
            <span>Tambah Paket Harga</span>
        </button>
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

    <!-- Pricing Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kendaraan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga per Hari</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga per Minggu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga per Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Denda per Jam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($hargas as $harga)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $harga->kendaraan->merk }}</div>
                                <div class="text-sm text-gray-500">{{ $harga->kendaraan->nama }}</div>
                                <div class="text-xs text-gray-400">{{ $harga->kendaraan->plat_nomor }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($harga->kendaraan->kategori)
                                {{ $harga->kendaraan->kategori->nama }} - {{ $harga->kendaraan->kategori->jenis }}
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            Rp {{ number_format($harga->harga_per_hari, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($harga->harga_per_minggu, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($harga->harga_per_bulan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($harga->denda_per_jam, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="editHarga({{ $harga->id }})" 
                                class="text-blue-600 hover:text-blue-900 mr-3 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition duration-200">
                                <i class="fas fa-edit mr-1"></i>Edit
                            </button>
                            <button onclick="deleteHarga({{ $harga->id }})" 
                                class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition duration-200">
                                <i class="fas fa-trash mr-1"></i>Hapus
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-money-bill-wave text-4xl mb-3 text-gray-300"></i>
                            <p class="text-lg font-medium mb-1">Belum ada data harga</p>
                            <p class="text-sm">Tambahkan paket harga untuk kendaraan yang tersedia.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Harga -->
<div id="createModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center px-6 py-4 border-b sticky top-0 bg-white">
            <h3 class="text-xl font-semibold text-gray-800">Tambah Paket Harga</h3>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 text-lg">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form action="{{ route('admin.harga.store') }}" method="POST" class="p-6 space-y-4">
            @csrf

            <!-- Pilih Kendaraan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kendaraan</label>
                <select name="kendaraan_id" id="kendaraanSelect" 
                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-red-500 focus:border-red-500" required>
                    <option value="">Pilih Kendaraan</option>
                    @foreach($kendaraansTanpaHarga as $kendaraan)
                        <option value="{{ $kendaraan->id }}">{{ $kendaraan->merk }} - {{ $kendaraan->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Harga -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Hari</label>
                    <input type="number" name="harga_per_hari" id="hargaPerHari" oninput="calculateDiscount()"
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Contoh: 200000" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Minggu</label>
                    <input type="number" name="harga_per_minggu" id="hargaPerMinggu" readonly
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-red-500 focus:border-red-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Bulan</label>
                    <input type="number" name="harga_per_bulan" id="hargaPerBulan" readonly
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-red-500 focus:border-red-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Denda per Jam</label>
                    <input type="number" name="denda_per_jam"
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Contoh: 50000" required min="0">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeCreateModal()" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit Harga -->
<div id="editModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4">
        <div class="flex justify-between items-center px-6 py-4 border-b bg-white">
            <h3 class="text-xl font-semibold text-gray-800">Edit Harga</h3>
            <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600 text-lg"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            <div id="editKendaraanInfo" class="text-sm text-gray-600 font-medium mb-3"></div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Hari</label>
                    <input type="number" name="harga_per_hari" id="editHargaPerHari" oninput="editCalculateDiscount()"
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-red-500 focus:border-red-500" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Minggu</label>
                    <input type="number" name="harga_per_minggu" id="editHargaPerMinggu" 
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-red-500 focus:border-red-500" required min="0">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Harga per Bulan</label>
                    <input type="number" name="harga_per_bulan" id="editHargaPerBulan"
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-red-500 focus:border-red-500" required min="0">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Denda per Jam</label>
                    <input type="number" name="denda_per_jam" id="editDendaPerJam"
                        class="block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-red-500 focus:border-red-500" required min="0">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeEditModal()" class="px-4 py-2 border rounded-md text-gray-700 hover:bg-gray-50">Batal</button>
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Update</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function calculateDiscount() {
    const h = parseFloat(document.getElementById('hargaPerHari').value) || 0;
    document.getElementById('hargaPerMinggu').value = Math.floor(h * 7 * 0.9);
    document.getElementById('hargaPerBulan').value = Math.floor(h * 30 * 0.8);
}
function editCalculateDiscount() {
    const h = parseFloat(document.getElementById('editHargaPerHari').value) || 0;
    document.getElementById('editHargaPerMinggu').value = Math.floor(h * 7 * 0.9);
    document.getElementById('editHargaPerBulan').value = Math.floor(h * 30 * 0.8);
}

function openCreateModal() {
    document.getElementById('createModal').classList.remove('hidden');
    document.getElementById('createModal').classList.add('flex');
}
function closeCreateModal() {
    document.getElementById('createModal').classList.add('hidden');
    document.getElementById('createModal').classList.remove('flex');
}
function openEditModal() {
    document.getElementById('editModal').classList.remove('hidden');
    document.getElementById('editModal').classList.add('flex');
}
function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
    document.getElementById('editModal').classList.remove('flex');
}

// Edit Harga
function editHarga(id) {
    fetch(`/admin/harga/${id}/edit`)
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                const d = res.data;
                document.getElementById('editKendaraanInfo').innerText =
                    `${d.kendaraan.merk} ${d.kendaraan.nama} (${d.kendaraan.plat_nomor}) • ${d.kendaraan.kategori}`;
                document.getElementById('editHargaPerHari').value = d.harga_per_hari;
                document.getElementById('editHargaPerMinggu').value = d.harga_per_minggu;
                document.getElementById('editHargaPerBulan').value = d.harga_per_bulan;
                document.getElementById('editDendaPerJam').value = d.denda_per_jam;
                document.getElementById('editForm').action = `/admin/harga/${id}`;
                openEditModal();
            } else {
                Swal.fire('Gagal', 'Data tidak ditemukan', 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Gagal mengambil data harga', 'error'));
}

// Delete Harga
function deleteHarga(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data harga akan dihapus permanen.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, hapus!'
    }).then(result => {
        if (result.isConfirmed) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/harga/${id}`;
            form.innerHTML = `@csrf @method('DELETE')`;
            document.body.appendChild(form);
            form.submit();
        }
    });
}
</script>
@endpush
