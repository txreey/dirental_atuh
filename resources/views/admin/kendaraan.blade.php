@extends('admin.layouts.app')

@section('title', 'Kelola Kendaraan - Dirental Atuh')

@section('content')
    <div class="container px-4 py-6 mx-auto">
        <!-- Header -->
        <div class="flex flex-col items-start justify-between gap-4 mb-6 md:flex-row md:items-center">
            <h2 class="text-2xl font-bold text-gray-800">Kelola Kendaraan</h2>
            <button id="createModalButton"
                class="flex items-center px-4 py-2 space-x-2 text-white transition duration-200 bg-red-500 rounded-lg hover:bg-red-600">
                <i class="fas fa-plus"></i>
                <span>Tambah Kendaraan</span>
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

        <!-- Display Validation Errors -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle text-red-600 mr-2"></i>
                    <h3 class="text-red-800 font-semibold">Terjadi Kesalahan!</h3>
                </div>
                <ul class="text-red-700 text-sm list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Table -->
        <div class="overflow-hidden bg-white rounded-lg shadow">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Gambar</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Merk
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Nama
                            </th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Kategori</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Harga/Hari</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">
                                Status</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($kendaraans as $kendaraan)
                            @php
                                // PERBAIKAN: Display kategori yang sesuai dengan struktur baru
                                $kategoriDisplay = $kendaraan->kategori
                                    ? $kendaraan->kategori->nama . ' - ' . $kendaraan->kategori->jenis
                                    : '-';

                                $hargaDisplay = $kendaraan->harga
                                    ? 'Rp ' . number_format($kendaraan->harga->harga_per_hari, 0, ',', '.')
                                    : '<span class="text-gray-400">Belum diatur</span>';

                                $tersewaCount = $kendaraan
                                    ->rental()
                                    ->whereIn('status', ['dipinjam', 'pending'])
                                    ->count();
                                $tersisa = $kendaraan->stok - $tersewaCount;
                                $statusDisplay = $tersisa > 0 ? "Tersisa {$tersisa}" : 'Tidak Tersedia';
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <img src="{{ $kendaraan->gambar ? asset('storage/' . $kendaraan->gambar) : '/placeholder-car.jpg' }}"
                                        alt="{{ $kendaraan->nama }}" class="object-cover w-16 h-12 rounded">
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $kendaraan->merk }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $kendaraan->nama }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    {{ $kategoriDisplay }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap">
                                    {!! $hargaDisplay !!}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $tersisa > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $statusDisplay }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium whitespace-nowrap">
                                    <div class="flex space-x-2">
                                        <button onclick="showDetail({{ $kendaraan->id }})"
                                            class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-lg transition duration-200">
                                            <i class="fas fa-eye mr-1"></i>Detail
                                        </button>
                                        <button onclick="editKendaraan({{ $kendaraan->id }})"
                                            class="text-yellow-600 hover:text-yellow-900 bg-yellow-50 hover:bg-yellow-100 px-3 py-1 rounded-lg transition duration-200">
                                            <i class="fas fa-edit mr-1"></i>Edit
                                        </button>
                                        <button onclick="deleteKendaraan({{ $kendaraan->id }})"
                                            class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-lg transition duration-200">
                                            <i class="fas fa-trash mr-1"></i>Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-car text-4xl mb-3 text-gray-300"></i>
                                        <p class="text-lg font-medium mb-1">Belum ada data kendaraan</p>
                                        <p class="text-sm">Klik tombol "Tambah Kendaraan" untuk menambahkan kendaraan
                                            pertama.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Kendaraan -->
    <div id="createModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-gray-600 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 flex items-center justify-between px-6 py-4 bg-white border-b">
                <h3 class="text-xl font-semibold text-gray-800">Tambah Kendaraan Baru</h3>
                <button type="button" onclick="closeCreateModal()" class="text-lg text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form action="{{ route('admin.kendaraan.store') }}" method="POST" enctype="multipart/form-data"
                id="createKendaraanForm" class="p-6 space-y-4">
                @csrf

                <!-- Gambar - DIPERBAIKI: Tambah pesan informasi format -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Gambar Kendaraan</label>
                    <input type="file" name="gambar" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100"
                        required>
                    <p class="mt-1 text-xs text-gray-500">Format yang didukung: JPG, JPEG, PNG, GIF, BMP, WEBP, SVG.
                        Maksimal 5MB.</p>
                    @error('gambar')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Merk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Merk Kendaraan</label>
                    <input type="text" name="merk" value="{{ old('merk') }}"
                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Contoh: Toyota, Honda, Yamaha" required>
                    @error('merk')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori dan Jenis -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                        <select id="kategoriSelect" name="kategori"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                            required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoriOptions as $kategoriNama => $kategoriDisplay)
                                <option value="{{ $kategoriNama }}"
                                    {{ old('kategori') == $kategoriNama ? 'selected' : '' }}>
                                    {{ $kategoriDisplay }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategori')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Jenis</label>
                        <select id="jenisSelect" name="kategori_id"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                            required>
                            <option value="">Pilih Jenis</option>
                            <!-- Options akan diisi oleh JavaScript -->
                        </select>
                        @error('kategori_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Informasi Lainnya -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama Kendaraan</label>
                        <input type="text" name="nama" value="{{ old('nama') }}"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                            placeholder="Contoh: Avanza, Beat, Ninja" required>
                        @error('nama')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Plat Nomor</label>
                        <input type="text" name="plat_nomor" value="{{ old('plat_nomor') }}"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                            placeholder="Contoh: B 1234 ABC" required>
                        @error('plat_nomor')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Warna</label>
                        <input type="text" name="warna" value="{{ old('warna') }}"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                            placeholder="Contoh: Hitam, Putih, Merah" required>
                        @error('warna')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tahun</label>
                        <input type="number" name="tahun" value="{{ old('tahun') }}" min="1990"
                            max="{{ date('Y') + 1 }}"
                            class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                            placeholder="Contoh: 2023" required>
                        @error('tahun')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" name="stok" value="{{ old('stok') }}" min="1"
                        class="block w-full px-3 py-2 mt-1 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                        placeholder="Jumlah unit yang tersedia" required>
                    @error('stok')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end pt-4 space-x-3 border-t">
                    <button type="button" onclick="closeCreateModal()"
                        class="px-4 py-2 text-gray-700 transition duration-200 border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                    <button type="submit" id="submitButton"
                        class="px-4 py-2 text-white transition duration-200 bg-red-500 rounded-md hover:bg-red-600 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Simpan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Edit Kendaraan -->
    <div id="editModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-gray-600 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 flex items-center justify-between px-6 py-4 bg-white border-b">
                <h3 class="text-xl font-semibold text-gray-800">Edit Kendaraan</h3>
                <button type="button" onclick="closeEditModal()" class="text-lg text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="editKendaraanForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                @method('PUT')

                <!-- Hidden ID -->
                <input type="hidden" name="id" id="edit_id">

                <!-- Gambar -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Gambar Kendaraan</label>
                    <input type="file" name="gambar" id="edit_gambar" accept="image/*"
                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-yellow-50 file:text-yellow-700 hover:file:bg-yellow-100">
                    <img id="edit_preview" class="w-32 h-24 mt-2 rounded-md object-cover border" src=""
                        alt="Preview">
                    <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengganti gambar.</p>
                </div>

                <!-- Merk -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Merk</label>
                    <input type="text" id="edit_merk" name="merk"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                        required>
                </div>

                <!-- Kategori & Jenis -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kategori</label>
                        <select id="edit_kategori" name="kategori"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                            required>
                            <option value="">Pilih Kategori</option>
                            @foreach ($kategoriOptions as $kategoriNama => $kategoriDisplay)
                                <option value="{{ $kategoriNama }}">{{ $kategoriDisplay }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Jenis</label>
                        <select id="edit_jenis" name="kategori_id"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                            required>
                            <option value="">Pilih Jenis</option>
                        </select>
                    </div>
                </div>

                <!-- Nama, Plat -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" id="edit_nama" name="nama"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Plat Nomor</label>
                        <input type="text" id="edit_plat_nomor" name="plat_nomor"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                            required>
                    </div>
                </div>

                <!-- Warna, Tahun -->
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Warna</label>
                        <input type="text" id="edit_warna" name="warna"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                            required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tahun</label>
                        <input type="number" id="edit_tahun" name="tahun"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                            min="1990" max="{{ date('Y') + 1 }}" required>
                    </div>
                </div>

                <!-- Stok -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Stok</label>
                    <input type="number" id="edit_stok" name="stok" min="1"
                        class="block w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-yellow-500 focus:border-yellow-500"
                        required>
                </div>

                <div class="flex justify-end pt-4 space-x-3 border-t">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 text-gray-700 border border-gray-300 rounded-md hover:bg-gray-50">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 text-white bg-yellow-500 rounded-md hover:bg-yellow-600 flex items-center">
                        <i class="fas fa-save mr-2"></i>
                        <span>Update</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Detail Kendaraan -->
    <div id="detailModal" class="fixed inset-0 z-50 items-center justify-center hidden bg-gray-600 bg-opacity-50">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="sticky top-0 flex items-center justify-between px-6 py-4 bg-white border-b">
                <h3 class="text-xl font-semibold text-gray-800">Detail Kendaraan</h3>
                <button onclick="closeDetailModal()" class="text-lg text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6 space-y-4" id="detailContent">
                <!-- Content akan diisi oleh JavaScript -->
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Data jenis berdasarkan kategori
        const jenisData = @json($jenisOptions);

        // Debug: cek data jenis
        console.log('Jenis Data:', jenisData);

        // Modal functions
        function openCreateModal() {
            console.log('openCreateModal called');
            const modal = document.getElementById('createModal');
            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                // Reset form ketika modal dibuka
                document.getElementById('createKendaraanForm').reset();
                document.getElementById('jenisSelect').innerHTML = '<option value="">Pilih Jenis</option>';
                document.getElementById('jenisSelect').disabled = true;
            }
        }

        function closeCreateModal() {
            const modal = document.getElementById('createModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';

                // Reset button state
                const submitButton = document.getElementById('submitButton');
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-save mr-2"></i><span>Simpan</span>';
            }
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        function showDetail(kendaraanId) {
            // Show loading
            document.getElementById('detailContent').innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <div class="w-12 h-12 border-b-2 border-red-500 rounded-full animate-spin"></div>
                </div>
            `;

            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Fetch data detail
            fetch(`/admin/kendaraan/detail/${kendaraanId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const kendaraan = data.data;
                        document.getElementById('detailContent').innerHTML = `
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <img src="${kendaraan.gambar}" alt="${kendaraan.nama}" class="object-cover w-full h-48 rounded-lg">
                                </div>
                                <div class="space-y-3">
                                    <div>
                                        <h4 class="text-lg font-semibold text-gray-800">${kendaraan.nama}</h4>
                                        <p class="text-gray-600">${kendaraan.merk}</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div><strong>Kategori:</strong> ${kendaraan.kategori}</div>
                                        <div><strong>Plat:</strong> ${kendaraan.plat_nomor}</div>
                                        <div><strong>Warna:</strong> ${kendaraan.warna}</div>
                                        <div><strong>Tahun:</strong> ${kendaraan.tahun}</div>
                                        <div><strong>Stok:</strong> ${kendaraan.stok}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t">
                                <h5 class="mb-3 text-lg font-semibold text-gray-800">Informasi Harga</h5>
                                <div class="grid grid-cols-2 gap-4 text-sm md:grid-cols-3">
                                    <div class="p-3 rounded-lg bg-gray-50">
                                        <div class="font-medium text-gray-600">Harga per Hari</div>
                                        <div class="text-lg font-bold text-red-600">${kendaraan.harga_per_hari}</div>
                                    </div>
                                    <div class="p-3 rounded-lg bg-gray-50">
                                        <div class="font-medium text-gray-600">Harga per Minggu</div>
                                        <div class="text-lg font-bold text-red-600">${kendaraan.harga_per_minggu}</div>
                                    </div>
                                    <div class="p-3 rounded-lg bg-gray-50">
                                        <div class="font-medium text-gray-600">Harga per Bulan</div>
                                        <div class="text-lg font-bold text-red-600">${kendaraan.harga_per_bulan}</div>
                                    </div>
                                    <div class="p-3 rounded-lg bg-gray-50">
                                        <div class="font-medium text-gray-600">Denda per Jam</div>
                                        <div class="text-lg font-bold text-red-600">${kendaraan.denda_per_jam}</div>
                                    </div>
                                </div>
                            </div>
                        `;
                    } else {
                        document.getElementById('detailContent').innerHTML = `
                            <div class="py-8 text-center">
                                <p class="text-red-500">Gagal memuat data detail kendaraan.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('detailContent').innerHTML = `
                        <div class="py-8 text-center">
                            <p class="text-red-500">Terjadi kesalahan saat memuat data.</p>
                        </div>
                    `;
                });
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        // === EDIT MODAL - VERSI DIPERBAIKI ===
        function editKendaraan(kendaraanId) {
            console.log('Edit kendaraan ID:', kendaraanId);

            const modal = document.getElementById('editModal');
            const form = document.getElementById('editKendaraanForm');

            // Tampilkan modal
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';

            // Reset form dulu
            form.reset();

            // Show loading state di form
            document.getElementById('edit_merk').value = 'Loading...';
            document.getElementById('edit_nama').value = 'Loading...';
            document.getElementById('edit_plat_nomor').value = 'Loading...';
            document.getElementById('edit_warna').value = 'Loading...';
            document.getElementById('edit_tahun').value = 'Loading...';
            document.getElementById('edit_stok').value = 'Loading...';

            // Ambil data dari server
            fetch(`/admin/kendaraan/${kendaraanId}/edit`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.status);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Data received:', data);

                    if (data.success && data.data) {
                        const k = data.data;

                        // Isi form dengan data yang sebenarnya
                        document.getElementById('edit_id').value = k.id;
                        document.getElementById('edit_merk').value = k.merk || '';
                        document.getElementById('edit_nama').value = k.nama || '';
                        document.getElementById('edit_plat_nomor').value = k.plat_nomor || '';
                        document.getElementById('edit_warna').value = k.warna || '';
                        document.getElementById('edit_tahun').value = k.tahun || '';
                        document.getElementById('edit_stok').value = k.stok || '';

                        // Set gambar preview
                        const previewImg = document.getElementById('edit_preview');
                        if (k.gambar) {
                            previewImg.src = k.gambar;
                            previewImg.classList.remove('hidden');
                        } else {
                            previewImg.src = '/placeholder-car.jpg';
                        }

                        // Handle kategori dan jenis
                        const kategoriSelect = document.getElementById('edit_kategori');
                        const jenisSelect = document.getElementById('edit_jenis');

                        // Reset jenis select
                        jenisSelect.innerHTML = '<option value="">Pilih Jenis</option>';
                        jenisSelect.disabled = true;

                        // Jika ada kategori_id, cari kategorinya
                        if (k.kategori_id) {
                            // Cari kategori berdasarkan kategori_id
                            let foundKategori = null;
                            for (const [kategoriNama, jenisList] of Object.entries(jenisData)) {
                                if (jenisList[k.kategori_id]) {
                                    foundKategori = kategoriNama;
                                    break;
                                }
                            }

                            if (foundKategori) {
                                kategoriSelect.value = foundKategori;

                                // Populate jenis options
                                Object.entries(jenisData[foundKategori]).forEach(([id, nama]) => {
                                    const option = document.createElement('option');
                                    option.value = id;
                                    option.textContent = nama;
                                    if (id == k.kategori_id) {
                                        option.selected = true;
                                    }
                                    jenisSelect.appendChild(option);
                                });
                                jenisSelect.disabled = false;
                            }
                        }

                        // Set form action untuk update
                        form.action = `/admin/kendaraan/update/${kendaraanId}`;

                        console.log('Form berhasil diisi dengan data');

                    } else {
                        throw new Error(data.message || 'Format data tidak valid');
                    }
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Gagal memuat data kendaraan: ' + error.message
                    });
                    closeEditModal();
                });
        }

        // Hapus kendaraan
        function deleteKendaraan(kendaraanId) {
            Swal.fire({
                title: 'Hapus Kendaraan',
                text: "Apakah Anda yakin ingin menghapus kendaraan ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/admin/kendaraan/destroy/${kendaraanId}`;

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

        // DOMContentLoaded event listener
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM loaded - modal script initialized');

            // EVENT LISTENER UNTUK TOMBOL BUAT MODAL
            const createModalButton = document.getElementById('createModalButton');
            if (createModalButton) {
                createModalButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    console.log('Create modal button clicked');
                    openCreateModal();
                });
            }

            // Handler untuk kategori select (CREATE)
            const kategoriSelect = document.getElementById('kategoriSelect');
            const jenisSelect = document.getElementById('jenisSelect');

            if (kategoriSelect && jenisSelect) {
                kategoriSelect.addEventListener('change', function() {
                    const kategoriNama = this.value;
                    console.log('Kategori dipilih:', kategoriNama);

                    // Reset jenis select
                    jenisSelect.innerHTML = '<option value="">Pilih Jenis</option>';
                    jenisSelect.disabled = true;

                    if (kategoriNama && jenisData[kategoriNama]) {
                        console.log('Jenis tersedia:', jenisData[kategoriNama]);
                        jenisSelect.disabled = false;

                        // Populate jenis options
                        Object.entries(jenisData[kategoriNama]).forEach(([id, jenisNama]) => {
                            const option = document.createElement('option');
                            option.value = id;
                            option.textContent = jenisNama;
                            jenisSelect.appendChild(option);
                        });
                    } else {
                        console.log('Tidak ada jenis untuk kategori:', kategoriNama);
                    }
                });
            }

            // Handler untuk kategori select (EDIT)
            const editKategoriSelect = document.getElementById('edit_kategori');
            const editJenisSelect = document.getElementById('edit_jenis');

            if (editKategoriSelect && editJenisSelect) {
                editKategoriSelect.addEventListener('change', function() {
                    const kategoriNama = this.value;
                    console.log('Edit Kategori dipilih:', kategoriNama);

                    // Reset jenis select
                    editJenisSelect.innerHTML = '<option value="">Pilih Jenis</option>';
                    editJenisSelect.disabled = true;

                    if (kategoriNama && jenisData[kategoriNama]) {
                        console.log('Edit Jenis tersedia:', jenisData[kategoriNama]);
                        editJenisSelect.disabled = false;

                        // Populate jenis options
                        Object.entries(jenisData[kategoriNama]).forEach(([id, jenisNama]) => {
                            const option = document.createElement('option');
                            option.value = id;
                            option.textContent = jenisNama;
                            editJenisSelect.appendChild(option);
                        });
                    }
                });
            }

            // Preview gambar saat upload di edit modal
            const editGambarInput = document.getElementById('edit_gambar');
            if (editGambarInput) {
                editGambarInput.addEventListener('change', function(e) {
                    const file = e.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            document.getElementById('edit_preview').src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Form submission handler - CREATE
            const form = document.getElementById('createKendaraanForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('Form submission started...');

                    // Simple validation - hanya cek required fields
                    const requiredFields = this.querySelectorAll('[required]');
                    let isValid = true;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            isValid = false;
                            field.classList.add('border-red-500');
                            console.log('Field required kosong:', field.name);
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });

                    // Cek khusus untuk jenis (kategori_id)
                    const kategoriId = document.getElementById('jenisSelect').value;
                    if (!kategoriId) {
                        isValid = false;
                        document.getElementById('jenisSelect').classList.add('border-red-500');
                        console.log('Jenis belum dipilih');
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Harap pilih jenis kendaraan!',
                            confirmButtonColor: '#f59e0b',
                        });
                    }

                    if (!isValid) {
                        e.preventDefault();
                        console.log('Form validation failed');
                        return;
                    }

                    console.log('Form validation passed, submitting...');

                    // Show loading state
                    const submitButton = document.getElementById('submitButton');
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Menyimpan...';

                    // Form akan di-submit secara normal
                    console.log('Form submitted successfully');
                });
            }

            // Form submission handler - EDIT
            const editForm = document.getElementById('editKendaraanForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    console.log('Edit form submission started...');

                    // Show loading state
                    const submitButton = editForm.querySelector('button[type="submit"]');
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';

                    // Form akan di-submit secara normal
                });
            }

            // Event listeners untuk modal
            const createModal = document.getElementById('createModal');
            const editModal = document.getElementById('editModal');
            const detailModal = document.getElementById('detailModal');

            if (createModal) {
                createModal.addEventListener('click', function(e) {
                    if (e.target === createModal) {
                        closeCreateModal();
                    }
                });
            }

            if (editModal) {
                editModal.addEventListener('click', function(e) {
                    if (e.target === editModal) {
                        closeEditModal();
                    }
                });
            }

            if (detailModal) {
                detailModal.addEventListener('click', function(e) {
                    if (e.target === detailModal) {
                        closeDetailModal();
                    }
                });
            }

            // Close modal dengan ESC
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeCreateModal();
                    closeEditModal();
                    closeDetailModal();
                }
            });

            console.log('All event listeners initialized successfully');
        });

        // Global function untuk debug
        window.debugForm = function() {
            const formData = new FormData(document.getElementById('createKendaraanForm'));
            console.log('Form Data:');
            for (let [key, value] of formData.entries()) {
                console.log(key + ': ' + value);
            }
        }
    </script>

    <style>
        /* Custom scrollbar untuk modal */
        #createModal>div,
        #editModal>div,
        #detailModal>div {
            scrollbar-width: thin;
            scrollbar-color: #cbd5e0 #f7fafc;
        }

        #createModal>div::-webkit-scrollbar,
        #editModal>div::-webkit-scrollbar,
        #detailModal>div::-webkit-scrollbar {
            width: 6px;
        }

        #createModal>div::-webkit-scrollbar-track,
        #editModal>div::-webkit-scrollbar-track,
        #detailModal>div::-webkit-scrollbar-track {
            background: #f7fafc;
        }

        #createModal>div::-webkit-scrollbar-thumb,
        #editModal>div::-webkit-scrollbar-thumb,
        #detailModal>div::-webkit-scrollbar-thumb {
            background-color: #cbd5e0;
            border-radius: 3px;
        }

        /* Loading animation */
        .fa-spinner {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
@endpush
