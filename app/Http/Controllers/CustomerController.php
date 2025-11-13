<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Kendaraan;
use App\Models\Pembayaran;
use App\Models\Denda;
use App\Models\Rental;

class CustomerController extends Controller
{
    public function dashboard()
    {
        if (Auth::user()->role !== 'customer') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk customer.');
        }

        return view('customer.dashboard');
    }

    public function kendaraan()
    {
        if (Auth::user()->role !== 'customer') {
            return redirect('/')->with('error', 'Akses ditolak. Hanya untuk customer.');
        }

        return view('customer.kendaraan');
    }

    // CustomerController.php
    public function rental()
    {
        $kendaraans = Kendaraan::where('status', 'tersedia')->with('kategori')->get();
        return view('customer.rental', compact('kendaraans'));
    }

    // CustomerController.php
    public function pembayaran()
    {
        // Ambil pembayaran milik user yang login
        $pembayarans = Pembayaran::where('user_id', Auth::id())
            ->with(['rental.kendaraan'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.pembayaran', compact('pembayarans'));
    }

    // CustomerController.php
    public function uploadBuktiPembayaran(Request $request)
    {
        $request->validate([
            'pembayaran_id' => 'required|exists:pembayarans,id',
            'bukti_pembayaran' => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
            'metode_pembayaran' => 'required|in:transfer_bank,virtual_account,e_wallet'
        ]);

        $pembayaran = Pembayaran::where('id', $request->pembayaran_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($pembayaran->status != 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Pembayaran sudah diproses atau kadaluarsa.'
            ]);
        }

        // Upload file
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $filename = 'bukti_' . time() . '_' . $pembayaran->kode_pembayaran . '.' . $file->getClientOriginalExtension();
            $file->storeAs('bukti_pembayaran', $filename, 'public');

            $pembayaran->update([
                'bukti_pembayaran' => $filename,
                'metode_pembayaran' => $request->metode_pembayaran
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload. Menunggu verifikasi admin.'
        ]);
    }

    public function denda()
    {
        $dendas = Denda::where('user_id', Auth::id())
            ->with(['rental.kendaraan'])
            ->latest()
            ->get();

        $totalDenda = $dendas->sum('jumlah');
        $dendaBelumBayar = $dendas->where('status', 'unpaid')->sum('jumlah');
        $dendaSudahBayar = $dendas->where('status', 'paid')->sum('jumlah');

        return view('customer.denda', compact('dendas', 'totalDenda', 'dendaBelumBayar', 'dendaSudahBayar'));
    }

    public function profile()
    {
        $totalRental = Rental::where('user_id', Auth::id())->count();
        return view('customer.profile', compact('totalRental'));
    }
}
