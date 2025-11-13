@extends('admin.layouts.app')

@section('content')
    <div class="w-full px-4 py-4 mx-auto max-w-7xl">
        <h2 class="pb-2 mb-4 text-xl font-bold text-gray-800 border-b border-gray-300">
            Data Kategori Kendaraan
        </h2>

        {{-- Tombol tambah & search --}}
        <div class="flex flex-col items-start justify-between gap-3 mb-4 md:flex-row md:items-center">
            <button
                class="flex items-center gap-2 px-3 py-2 text-sm text-white transition duration-200 bg-blue-600 rounded-lg hover:bg-blue-700"
                onclick="openCreateModal()">
                <i class="bi bi-plus-circle"></i> Tambah Data
            </button>

            <div class="flex w-full gap-2 md:w-auto">
                <input type="text" id="searchInput"
                    class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 md:w-64"
                    placeholder="Cari data...">
                <button class="px-3 py-2 text-sm bg-gray-200 rounded-lg hover:bg-gray-300" onclick="clearSearch()">
                    <i class="bi bi-x-circle"></i> Reset
                </button>
            </div>
        </div>

        {{-- SweetAlert Notif --}}
        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", () => {
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
                document.addEventListener("DOMContentLoaded", () => {
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

        {{-- Table --}}
        <div class="overflow-hidden bg-white border border-gray-300 rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-800">
                    <thead class="text-white bg-gray-700">
                        <tr>
                            <th class="p-3 text-center border border-gray-400 w-12">No</th>
                            <th class="p-3 border border-gray-400">Kategori</th>
                            <th class="p-3 border border-gray-400">Jenis</th>
                            <th class="p-3 text-center border border-gray-400 w-20">Jumlah</th>
                            <th class="p-3 border border-gray-400">Merek</th>
                            <th class="p-3 border border-gray-400">Nama Kendaraan</th>
                            <th class="p-3 text-center border border-gray-400 w-24">Stok</th>
                            <th class="p-3 text-center border border-gray-400 w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($groupedData as $kategoriNama => $jenisList)
                            @php
                                // hitung total baris (kalau ga ada kendaraan, tetep 1)
                                $rowspanKategori = max(
                                    collect($jenisList)->reduce(function ($carry, $jenis) {
                                        return $carry + max(1, collect($jenis['merekGroups'])->flatten(1)->count());
                                    }, 0),
                                    1,
                                );
                                $firstKategori = true;
                            @endphp

                            @foreach ($jenisList as $jenis => $data)
                                @php
                                    $kendaraanCount = collect($data['merekGroups'])->flatten(1)->count();
                                    $rowspanJenis = max($kendaraanCount, 1);
                                    $firstJenis = true;
                                @endphp

                                {{-- Kalau ADA kendaraan --}}
                                @if ($kendaraanCount > 0)
                                    @foreach ($data['merekGroups'] as $merek => $kendaraans)
                                        @php
                                            $rowspanMerek = count($kendaraans);
                                            $firstMerek = true;
                                        @endphp

                                        @foreach ($kendaraans as $kendaraan)
                                            <tr class="hover:bg-gray-50 border-b border-gray-300">
                                                {{-- Kategori --}}
                                                @if ($firstKategori)
                                                    <td rowspan="{{ $rowspanKategori }}"
                                                        class="p-3 text-center border border-gray-400">
                                                        {{ $no++ }}
                                                    </td>
                                                    <td rowspan="{{ $rowspanKategori }}"
                                                        class="p-3 font-medium border border-gray-400">
                                                        {{ $kategoriNama }}
                                                    </td>
                                                    @php $firstKategori = false; @endphp
                                                @endif

                                                {{-- Jenis --}}
                                                @if ($firstJenis)
                                                    <td rowspan="{{ $rowspanJenis }}" class="p-3 border border-gray-400">
                                                        {{ $jenis }}
                                                    </td>
                                                    <td rowspan="{{ $rowspanJenis }}"
                                                        class="p-3 text-center font-semibold border border-gray-400">
                                                        {{ $data['jumlah'] }}
                                                    </td>
                                                    @php $firstJenis = false; @endphp
                                                @endif

                                                {{-- Merek --}}
                                                @if ($firstMerek)
                                                    <td rowspan="{{ $rowspanMerek }}"
                                                        class="p-3 border border-gray-400 font-semibold text-gray-700">
                                                        {{ $merek }}
                                                    </td>
                                                    @php $firstMerek = false; @endphp
                                                @endif

                                                {{-- Nama kendaraan & stok --}}
                                                <td class="p-3 border border-gray-400">{{ $kendaraan->nama }}</td>
                                                <td class="p-3 text-center border border-gray-400">{{ $kendaraan->stok }}
                                                </td>

                                                {{-- Aksi --}}
                                                <td class="p-3 text-center border border-gray-400">
                                                    <div class="flex justify-center gap-1">
                                                        <button
                                                            class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-yellow-500 rounded hover:bg-yellow-600"
                                                            onclick="openEditModal({{ $kendaraan->id }}, '{{ addslashes($kategoriNama) }}', '{{ addslashes($jenis) }}')">
                                                            <i class="bi bi-pencil"></i> Edit
                                                        </button>
                                                        <form
                                                            action="{{ route('admin.kategori.destroy', $data['kategori_id']) }}"
                                                            method="POST" onsubmit="return confirmDelete(event)"
                                                            class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button
                                                                class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600 transition-colors">
                                                                <i class="bi bi-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                @else
                                    {{-- Kalau BELUM ADA kendaraan --}}
                                    <tr class="bg-gray-50 border-b border-gray-300">
                                        @if ($firstKategori)
                                            <td rowspan="{{ $rowspanKategori }}"
                                                class="p-3 text-center border border-gray-400">
                                                {{ $no++ }}
                                            </td>
                                            <td rowspan="{{ $rowspanKategori }}"
                                                class="p-3 font-medium border border-gray-400">
                                                {{ $kategoriNama }}
                                            </td>
                                            @php $firstKategori = false; @endphp
                                        @endif

                                        <td class="p-3 border border-gray-400">{{ $jenis }}</td>
                                        <td class="p-3 text-center border border-gray-400">0</td>
                                        <td class="p-3 border border-gray-400 text-gray-400 italic" colspan="3">
                                            Belum ada kendaraan terdaftar
                                        </td>
                                        <td class="p-3 text-center border border-gray-400">
                                            <div class="flex justify-center gap-1">

                                                <a href="{{ route('admin.kendaraan') }}">
                                                    <button
                                                        class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-blue-500 rounded hover:bg-blue-600">
                                                        <i class="bi bi-plus-circle"></i> Tambah
                                                    </button>
                                                </a>
                                                <form action="{{ route('admin.kategori.destroy', $data['kategori_id']) }}"
                                                    method="POST" onsubmit="return confirmDelete(event)" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button
                                                        class="flex items-center gap-1 px-2 py-1 text-xs text-white bg-red-500 rounded hover:bg-red-600 transition-colors">
                                                        <i class="bi bi-trash"></i> Hapus
                                                    </button>
                                                </form>

                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-3 text-sm text-gray-600">Menampilkan {{ $totalKategori }} jenis kendaraan</div>


        {{-- ========== MODAL TAMBAH ========== --}}
        <div id="createModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
            <div class="w-full max-w-md mx-4 bg-white rounded-lg shadow-xl">
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Data Kategori</h3>
                    <button type="button" onclick="closeCreateModal()"
                        class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
                </div>
                <form action="{{ route('admin.kategori.store') }}" method="POST" id="createForm">
                    @csrf
                    <div class="p-4 space-y-3">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Kategori</label>
                            <input type="text" name="kategori" id="create-kategori"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Contoh: Mobil, Motor" required>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Jenis</label>
                            <input type="text" name="jenis" id="create-jenis"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Contoh: SUV, Sport, Matic" required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-4 border-t border-gray-200">
                        <button type="button" onclick="closeCreateModal()"
                            class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-3 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- ========== MODAL EDIT ========== --}}
        <div id="editModal" class="fixed inset-0 z-50 flex items-center justify-center hidden bg-black bg-opacity-50">
            <div class="w-full max-w-md mx-4 bg-white rounded-lg shadow-xl">
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Edit Data Kategori</h3>
                    <button type="button" onclick="closeEditModal()"
                        class="text-gray-400 hover:text-gray-600 text-lg">✕</button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-4 space-y-3">
                        <input type="hidden" id="edit-id" name="id">
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Kategori</label>
                            <input type="text" id="edit-kategori" name="kategori"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                        </div>
                        <div>
                            <label class="block mb-1 text-sm font-medium text-gray-700">Jenis</label>
                            <input type="text" id="edit-jenis" name="jenis"
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 p-4 border-t border-gray-200">
                        <button type="button" onclick="closeEditModal()"
                            class="px-3 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">Batal</button>
                        <button type="submit"
                            class="px-3 py-2 text-sm text-white bg-yellow-600 rounded-lg hover:bg-yellow-700 transition-colors">Update</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            function openCreateModal() {
                document.getElementById('createModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
                document.getElementById('createForm').reset();
            }

            function closeCreateModal() {
                document.getElementById('createModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function openEditModal(id, kategori, jenis) {
                document.getElementById('edit-id').value = id;
                document.getElementById('edit-kategori').value = kategori;
                document.getElementById('edit-jenis').value = jenis;
                const route = "{{ route('admin.kategori.update', ':id') }}";
                document.getElementById('editForm').action = route.replace(':id', id);
                document.getElementById('editModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            function confirmDelete(event) {
                event.preventDefault();
                const form = event.target;
                Swal.fire({
                    title: 'Yakin ingin hapus?',
                    text: 'Data yang dihapus tidak bisa dikembalikan!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then(result => {
                    if (result.isConfirmed) form.submit();
                });
            }
        </script>
    @endpush
