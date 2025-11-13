<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kendaraan;
use App\Models\Kategori;
use App\Models\Harga;
use App\Models\User;
use App\Models\Rental;
use App\Models\Pembayaran;
use App\Models\Denda;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Exception;

class AdminController extends Controller
{
    // ============
    // DASHBOARD
    // ============
    public function dashboard()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        return view('admin.dashboard');
    }

    // =====================
    // PENGELOLAAN KENDARAAN
    // =====================
    public function kendaraan()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        // Ambil data kendaraan dengan relasi yang sesuai
        $kendaraans = Kendaraan::with(['kategori', 'harga'])->get();

        // Get kategori options (unik berdasarkan nama)
        $kategoriOptions = Kategori::select('nama')->distinct()->pluck('nama', 'nama')->toArray();

        // Get jenis options grouped by kategori
        $jenisOptions = [];
        $kategoriList = Kategori::all()->groupBy('nama');
        foreach ($kategoriList as $kategoriNama => $items) {
            $jenisOptions[$kategoriNama] = $items->pluck('jenis', 'id')->toArray();
        }

        return view('admin.kendaraan', compact('kendaraans', 'kategoriOptions', 'jenisOptions'));
    }

    // ====================
    // TAMBAH KENDARAAN - DIPERBAIKI
    // ====================
    public function storeKendaraan(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        // Debug: lihat data yang dikirim
        Log::info('Data yang diterima:', $request->all());

        // VALIDASI YANG DIPERBAIKI: Gambar lebih fleksibel
        $request->validate([
            'gambar' => 'required|image|max:5120', // Menerima semua jenis image, max 5MB
            'merk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:20|unique:kendaraan,plat_nomor',
            'warna' => 'required|string|max:50',
            'tahun' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'stok' => 'required|integer|min:1',
        ]);

        try {
            // Debug file info
            if ($request->hasFile('gambar')) {
                Log::info('File info:', [
                    'original_name' => $request->file('gambar')->getClientOriginalName(),
                    'extension' => $request->file('gambar')->getClientOriginalExtension(),
                    'mime_type' => $request->file('gambar')->getMimeType(),
                    'size' => $request->file('gambar')->getSize(),
                ]);
            }

            // Upload gambar
            $gambarPath = $request->file('gambar')->store('kendaraan', 'public');

            // Create kendaraan
            $kendaraan = Kendaraan::create([
                'kategori_id' => $request->kategori_id,
                'nama' => $request->nama,
                'merk' => $request->merk,
                'plat_nomor' => $request->plat_nomor,
                'warna' => $request->warna,
                'tahun' => $request->tahun,
                'stok' => $request->stok,
                'gambar' => $gambarPath,
            ]);

            Log::info('Kendaraan berhasil dibuat:', $kendaraan->toArray());

            return redirect()->route('admin.kendaraan')->with('success', 'Kendaraan berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error saat menambah kendaraan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan kendaraan: ' . $e->getMessage());
        }
    }

    // ====================
    // DETAIL KENDARAAN 
    // ====================
    public function detailKendaraan($id)
    {
        $kendaraan = Kendaraan::with(['kategori', 'harga'])->find($id);

        if (!$kendaraan) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $kendaraan->id,
                'nama' => $kendaraan->nama,
                'merk' => $kendaraan->merk,
                'kategori' => $kendaraan->kategori
                    ? $kendaraan->kategori->nama . ' - ' . $kendaraan->kategori->jenis
                    : '-',
                'plat_nomor' => $kendaraan->plat_nomor,
                'warna' => $kendaraan->warna,
                'tahun' => $kendaraan->tahun,
                'stok' => $kendaraan->stok,
                'gambar' => $kendaraan->gambar
                    ? asset('storage/' . $kendaraan->gambar)
                    : asset('placeholder-car.jpg'),
                'harga_per_hari' => $kendaraan->harga
                    ? 'Rp ' . number_format($kendaraan->harga->harga_per_hari, 0, ',', '.')
                    : '-',
                'harga_per_minggu' => $kendaraan->harga
                    ? 'Rp ' . number_format($kendaraan->harga->harga_per_minggu, 0, ',', '.')
                    : '-',
                'harga_per_bulan' => $kendaraan->harga
                    ? 'Rp ' . number_format($kendaraan->harga->harga_per_bulan, 0, ',', '.')
                    : '-',
                'denda_per_jam' => $kendaraan->harga
                    ? 'Rp ' . number_format($kendaraan->harga->denda_per_jam, 0, ',', '.')
                    : '-',
                'deposit' => $kendaraan->harga
                    ? 'Rp ' . number_format($kendaraan->harga->deposit, 0, ',', '.')
                    : '-',
            ]
        ]);
    }

    // ====================
    // EDIT KENDARAAN - DIPERBAIKI
    // ====================
    public function editKendaraan($id)
    {
        try {
            $kendaraan = Kendaraan::with(['kategori', 'harga'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $kendaraan->id,
                    'merk' => $kendaraan->merk,
                    'nama' => $kendaraan->nama,
                    'plat_nomor' => $kendaraan->plat_nomor,
                    'warna' => $kendaraan->warna,
                    'tahun' => $kendaraan->tahun,
                    'stok' => $kendaraan->stok,
                    'gambar' => $kendaraan->gambar ? asset('storage/' . $kendaraan->gambar) : asset('placeholder-car.jpg'),
                    'kategori' => $kendaraan->kategori ? $kendaraan->kategori->nama : '',
                    'kategori_id' => $kendaraan->kategori_id,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan'
            ], 404);
        }
    }

    // ====================
    // UPDATE KENDARAAN - DIPERBAIKI
    // ====================
    public function updateKendaraan(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $kendaraan = Kendaraan::findOrFail($id);

        // VALIDASI YANG DIPERBAIKI: Gambar lebih fleksibel
        $request->validate([
            'gambar' => 'nullable|image|max:5120', // Menerima semua jenis image, max 5MB
            'merk' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'nama' => 'required|string|max:255',
            'plat_nomor' => 'required|string|max:20|unique:kendaraan,plat_nomor,' . $id,
            'warna' => 'required|string|max:50',
            'tahun' => 'required|integer|min:1990|max:' . (date('Y') + 1),
            'stok' => 'required|integer|min:1',
        ]);

        try {
            $data = [
                'kategori_id' => $request->kategori_id,
                'nama' => $request->nama,
                'merk' => $request->merk,
                'plat_nomor' => $request->plat_nomor,
                'warna' => $request->warna,
                'tahun' => $request->tahun,
                'stok' => $request->stok,
            ];

            // Update gambar jika ada
            if ($request->hasFile('gambar')) {
                // Hapus gambar lama
                if ($kendaraan->gambar) {
                    Storage::disk('public')->delete($kendaraan->gambar);
                }
                $data['gambar'] = $request->file('gambar')->store('kendaraan', 'public');
            }

            $kendaraan->update($data);

            return redirect()->route('admin.kendaraan')->with('success', 'Kendaraan berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error saat update kendaraan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui kendaraan: ' . $e->getMessage());
        }
    }

    // ====================
    // HAPUS KENDARAAN
    // ====================
    public function destroyKendaraan($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        try {
            $kendaraan = Kendaraan::findOrFail($id);

            // Hapus gambar
            if ($kendaraan->gambar) {
                Storage::disk('public')->delete($kendaraan->gambar);
            }

            $kendaraan->delete();

            return redirect()->route('admin.kendaraan')->with('success', 'Kendaraan berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error saat hapus kendaraan: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus kendaraan: ' . $e->getMessage());
        }
    }

    // ============
    // PENGELOLAAN HARGA
    // ============
    public function harga()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        // Ambil harga dengan relasi kendaraan dan kategori
        $hargas = Harga::with(['kendaraan.kategori'])->get();

        // Ambil kendaraan yang belum memiliki harga
        $kendaraansTanpaHarga = Kendaraan::whereDoesntHave('harga')
            ->with('kategori')
            ->get();

        return view('admin.harga', compact('hargas', 'kendaraansTanpaHarga'));
    }

    // ====================
    // TAMBAH HARGA
    // ====================
    public function storeHarga(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $request->validate([
            'kendaraan_id' => 'required|exists:kendaraan,id|unique:harga,kendaraan_id',
            'harga_per_hari' => 'required|numeric|min:0',
            'harga_per_minggu' => 'nullable|numeric|min:0',
            'harga_per_bulan' => 'nullable|numeric|min:0',
            'denda_per_jam' => 'required|numeric|min:0',
        ]);

        try {
            // Auto calculate jika kosong
            $hargaPerMinggu = $request->harga_per_minggu ?? ($request->harga_per_hari * 7 * 0.9);
            $hargaPerBulan = $request->harga_per_bulan ?? ($request->harga_per_hari * 30 * 0.8);

            Harga::create([
                'kendaraan_id' => $request->kendaraan_id,
                'harga_per_hari' => $request->harga_per_hari,
                'harga_per_minggu' => $hargaPerMinggu,
                'harga_per_bulan' => $hargaPerBulan,
                'denda_per_jam' => $request->denda_per_jam,
                'is_active' => true,
            ]);

            return redirect()->route('admin.harga')->with('success', 'Harga berhasil ditambahkan!');
        } catch (\Exception $e) {
            Log::error('Error saat tambah harga: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menambahkan harga: ' . $e->getMessage());
        }
    }

    // ====================
    // EDIT HARGA
    // ====================
    public function editHarga($id)
    {
        $harga = Harga::with(['kendaraan.kategori'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => [
                'harga_per_hari' => $harga->harga_per_hari,
                'harga_per_minggu' => $harga->harga_per_minggu,
                'harga_per_bulan' => $harga->harga_per_bulan,
                'denda_per_jam' => $harga->denda_per_jam,
                'kendaraan' => [
                    'merk' => $harga->kendaraan->merk,
                    'nama' => $harga->kendaraan->nama,
                    'plat_nomor' => $harga->kendaraan->plat_nomor,
                    'kategori' => $harga->kendaraan->kategori ? $harga->kendaraan->kategori->nama . ' - ' . $harga->kendaraan->kategori->jenis : '-',
                ]
            ]
        ]);
    }

    // ====================
    // UPDATE HARGA
    // ====================
    public function updateHarga(Request $request, $id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $harga = Harga::findOrFail($id);

        $request->validate([
            'harga_per_hari' => 'required|numeric|min:0',
            'harga_per_minggu' => 'nullable|numeric|min:0',
            'harga_per_bulan' => 'nullable|numeric|min:0',
            'denda_per_jam' => 'required|numeric|min:0',
        ]);

        try {
            $harga->update([
                'harga_per_hari' => $request->harga_per_hari,
                'harga_per_minggu' => $request->harga_per_minggu,
                'harga_per_bulan' => $request->harga_per_bulan,
                'denda_per_jam' => $request->denda_per_jam,
            ]);

            return redirect()->route('admin.harga')->with('success', 'Harga berhasil diperbarui!');
        } catch (\Exception $e) {
            Log::error('Error saat update harga: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memperbarui harga: ' . $e->getMessage());
        }
    }

    // ====================
    // HAPUS HARGA
    // ====================
    public function destroyHarga($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        try {
            $harga = Harga::findOrFail($id);
            $harga->delete();

            return redirect()->route('admin.harga')->with('success', 'Harga berhasil dihapus!');
        } catch (\Exception $e) {
            Log::error('Error saat hapus harga: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menghapus harga: ' . $e->getMessage());
        }
    }


    // =========
    // PENGELOLAAN KATEGORI
    // =========
    public function kategori()
    {
        // Ambil semua kategori beserta kendaraan-nya (meskipun kosong)
        $kategoris = \App\Models\Kategori::with('kendaraan')->get();

        $groupedData = [];

        foreach ($kategoris as $kategori) {
            $jenis = $kategori->jenis;

            // Group kendaraan berdasarkan merek (kalau ada)
            $merekGroups = $kategori->kendaraan->groupBy('merk');

            // Simpan data kategori
            $groupedData[$kategori->nama][$jenis] = [
                'jumlah' => $kategori->kendaraan->count(),
                'merekGroups' => $merekGroups,
                'kategori_id' => $kategori->id
            ];
        }

        $totalKategori = $kategoris->count();

        return view('admin.kategori', compact('groupedData', 'totalKategori'));
    }

    // ====================
    // TAMBAH DATA KATEGORI
    // ====================
    public function storeKategori(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
        ]);

        // Cek duplikat
        $existingKategori = \App\Models\Kategori::where('nama', $request->kategori)
            ->where('jenis', $request->jenis)
            ->first();

        if ($existingKategori) {
            return redirect()->back()->with('error', 'Kategori "' . $request->kategori . '" dengan jenis "' . $request->jenis . '" sudah ada!');
        }

        \App\Models\Kategori::create([
            'nama' => $request->kategori,
            'jenis' => $request->jenis,
        ]);

        return redirect()->route('admin.kategori')->with('success', 'Data berhasil ditambahkan!');
    }

    // ====================
    // UPDATE DATA KATEGORI
    // ====================
    public function updateKategori(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:kategori,id',
            'kategori' => 'required|string|max:255',
            'jenis' => 'required|string|max:255',
        ]);

        $kategori = \App\Models\Kategori::findOrFail($request->id);

        $duplicate = \App\Models\Kategori::where('nama', $request->kategori)
            ->where('jenis', $request->jenis)
            ->where('id', '!=', $request->id)
            ->first();

        if ($duplicate) {
            return redirect()->back()->with('error', 'Kategori "' . $request->kategori . '" dengan jenis "' . $request->jenis . '" sudah ada!');
        }

        $kategori->update([
            'nama' => $request->kategori,
            'jenis' => $request->jenis,
        ]);

        return redirect()->route('admin.kategori')->with('success', 'Data berhasil diperbarui');
    }

    // ====================
    // HAPUS DATA KATEGORI
    // ====================
    public function destroyKategori($id)
    {
        $kategori = \App\Models\Kategori::findOrFail($id);

        // Ambil semua kendaraan terkait kategori ini
        $kendaraans = \App\Models\Kendaraan::where('kategori_id', $id)->get();

        if ($kendaraans->isNotEmpty()) {
            // Cek apakah masih ada kendaraan dengan stok > 0
            $adaStok = $kendaraans->contains(function ($kendaraan) {
                return $kendaraan->stok > 0;
            });

            if ($adaStok) {
                return redirect()->route('admin.kategori')
                    ->with('error', 'Kategori tidak dapat dihapus karena masih ada kendaraan dengan stok tersisa!');
            }
        }

        // Jika sampai sini berarti kategori aman dihapus
        $kategori->delete();

        return redirect()->route('admin.kategori')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    // ============
    // CUSTOMER
    // ============
    public function customer()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        $customers = User::where('role', 'customer')->with(['rental'])->get();
        return view('admin.customer', compact('customers'));
    }

    // ============
    // PEMBAYARAN
    // ============
    public function pembayaran()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        try {
            // Ambil data pembayaran dengan relasi dan error handling
            $pembayarans = Pembayaran::with([
                'user',
                'rental.kendaraan' => function ($query) {
                    $query->withTrashed(); // Jika pakai soft delete
                }
            ])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            // Hitung summary dengan error handling
            $totalPendapatan = Pembayaran::where('status', 'paid')->sum('jumlah') ?? 0;
            $pendingPayments = Pembayaran::where('status', 'pending')->count() ?? 0;
            $successPayments = Pembayaran::where('status', 'paid')->count() ?? 0;
            $failedPayments = Pembayaran::where('status', 'failed')->count() ?? 0;

            return view('admin.pembayaran', compact(
                'pembayarans',
                'totalPendapatan',
                'pendingPayments',
                'successPayments',
                'failedPayments'
            ));
        } catch (\Exception $e) {
            Log::error('Error in pembayaran method: ' . $e->getMessage());
            // Return view dengan data kosong jika ada error
            return view('admin.pembayaran', [
                'pembayarans' => collect(),
                'totalPendapatan' => 0,
                'pendingPayments' => 0,
                'successPayments' => 0,
                'failedPayments' => 0
            ])->with('error', 'Terjadi kesalahan saat memuat data pembayaran.');
        }
    }

    // Get payment detail
    public function getPaymentDetail($id)
    {
        $pembayaran = Pembayaran::with(['user', 'rental.kendaraan'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $pembayaran
        ]);
    }

    // Get payment proof
    public function getPaymentProof($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        if (!$pembayaran->bukti_pembayaran) {
            return response()->json([
                'success' => false,
                'message' => 'Bukti pembayaran tidak tersedia'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $pembayaran
        ]);
    }

    // Verify payment
    public function verifyPayment($id)
    {
        $pembayaran = Pembayaran::findOrFail($id);

        $pembayaran->update([
            'status' => 'paid',
            'tanggal_bayar' => now(),
        ]);

        return redirect()->route('admin.pembayaran')->with('success', 'Pembayaran berhasil diverifikasi!');
    }

    // ============
    // DENDA
    // ============
    public function denda()
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        // Ambil data denda dengan relasi
        $dendas = Denda::with(['user', 'rental.kendaraan'])
            ->orderBy('tanggal_dibuat', 'desc')
            ->paginate(10);

        // Hitung summary
        $totalDenda = Denda::sum('jumlah');
        $dendaBelumBayar = Denda::where('status', 'unpaid')->sum('jumlah');
        $dendaSudahBayar = Denda::where('status', 'paid')->sum('jumlah');
        $totalKasus = Denda::count();

        return view('admin.denda', compact(
            'dendas',
            'totalDenda',
            'dendaBelumBayar',
            'dendaSudahBayar',
            'totalKasus'
        ));
    }

    // ============
    // VERIFIKASI PEMBAYARAN
    // ============
    public function verifikasiPembayaran($id)
    {
        if (Auth::user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk admin.');
        }

        // Implement verifikasi pembayaran logic here
        return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi.');
    }

    // ===========
    // KONFIRMASI PEMBAYARAN DENDA
    // ===========
    public function confirmPayment($id)
    {
        $denda = Denda::findOrFail($id);

        $denda->update([
            'status' => 'paid',
            'tanggal_dibayar' => now(),
        ]);

        return redirect()->route('admin.denda')->with('success', 'Pembayaran denda berhasil dikonfirmasi!');
    }

    // ===========
    // HAPUS DENDA
    // ===========
    public function destroyDenda($id)
    {
        $denda = Denda::findOrFail($id);
        $denda->delete();

        return redirect()->route('admin.denda')->with('success', 'Data denda berhasil dihapus!');
    }

    // ===========
    // RENTAl
    // ===========
    public function rental()
    {
        $rentals = Rental::with(['user.kendaraan'])->get();

        return view('admin.rental', compact(
            'rentals'
        ));

    }
}
