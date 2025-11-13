<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CustomerController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Route utama
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected routes - PAKAI AUTH BIASA
Route::middleware(['auth'])->group(function () {

    // Admin routes - CHECK ROLE MANUAL DI CONTROLLER
    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Kendaraan Routes
        Route::get('/kendaraan', [AdminController::class, 'kendaraan'])->name('admin.kendaraan');
        Route::post('/kendaraan/store', [AdminController::class, 'storeKendaraan'])->name('admin.kendaraan.store');
        Route::get('/kendaraan/detail/{id}', [AdminController::class, 'detailKendaraan'])->name('admin.kendaraan.detail');
        Route::get('/kendaraan/{id}/edit', [AdminController::class, 'editKendaraan']);
        Route::put('/kendaraan/update/{id}', [AdminController::class, 'updateKendaraan'])->name('admin.kendaraan.update');
        Route::delete('/kendaraan/destroy/{id}', [AdminController::class, 'destroyKendaraan'])->name('admin.kendaraan.destroy');

        // Kategori Routes
        Route::get('/kategori', [AdminController::class, 'kategori'])->name('admin.kategori');
        Route::post('/kategori/store', [AdminController::class, 'storeKategori'])->name('admin.kategori.store');
        Route::put('/kategori/update/{id}', [AdminController::class, 'updateKategori'])->name('admin.kategori.update');
        Route::delete('/kategori/destroy/{id}', [AdminController::class, 'destroyKategori'])->name('admin.kategori.destroy');

        // Harga Routes
        Route::get('/harga', [AdminController::class, 'harga'])->name('admin.harga');
        Route::post('/harga/store', [AdminController::class, 'storeHarga'])->name('admin.harga.store');
        Route::get('/harga/{id}/edit', [AdminController::class, 'editHarga'])->name('admin.harga.edit');
        Route::put('/harga/{id}', [AdminController::class, 'updateHarga'])->name('admin.harga.update');
        Route::delete('/harga/{id}', [AdminController::class, 'destroyHarga'])->name('admin.harga.destroy');


        // Customer Routes
        Route::get('/customer', [AdminController::class, 'customer'])->name('admin.customer');

        // Rental Routes
        Route::get('/rental', [AdminController::class, 'rental'])->name('admin.rental');
        Route::post('/rental/konfirmasi/{id}', [AdminController::class, 'konfirmasiRental'])->name('admin.rental.konfirmasi');
        Route::post('/rental/selesai/{id}', [AdminController::class, 'selesaiRental'])->name('admin.rental.selesai');
        Route::post('/rental/batalkan/{id}', [AdminController::class, 'batalkanRental'])->name('admin.rental.batalkan');

        // Pembayaran Routes
        Route::get('/pembayaran', [AdminController::class, 'pembayaran'])->name('admin.pembayaran');
        Route::get('/pembayaran/{id}/detail', [AdminController::class, 'getPaymentDetail']);
        Route::get('/pembayaran/{id}/proof', [AdminController::class, 'getPaymentProof']);
        Route::put('/pembayaran/{id}/verify', [AdminController::class, 'verifyPayment'])->name('admin.pembayaran.verify');

        // Denda Routes
        Route::get('/denda', [AdminController::class, 'denda'])->name('admin.denda');
        Route::put('/admin/denda/{id}/confirm', [AdminController::class, 'confirmPayment'])->name('admin.denda.confirm');
        Route::post('/pembayaran/verifikasi/{id}', [AdminController::class, 'verifikasiPembayaran'])->name('admin.pembayaran.verifikasi');
        Route::delete('/admin/denda/{id}', [AdminController::class, 'destroyDenda'])->name('admin.denda.destroy');

        // ini udda ada dendanya?? heyyy matiiii 
    });

    // Customer routes - CHECK ROLE MANUAL DI CONTROLLER
    Route::prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'dashboard'])->name('dashboard');
        Route::get('/kendaraan', [CustomerController::class, 'kendaraan'])->name('kendaraan');
        Route::get('/rental', [CustomerController::class, 'rental'])->name('rental');
        Route::get('/pembayaran', [CustomerController::class, 'pembayaran'])->name('pembayaran');
        Route::post('/pembayaran/upload', [CustomerController::class, 'uploadBuktiPembayaran'])->name('pembayaran.upload');
        Route::get('/denda', [CustomerController::class, 'denda'])->name('denda');
        Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');

        // Tambahan routes untuk customer
        Route::post('/rental/store', [CustomerController::class, 'storeRental'])->name('rental.store');
        Route::get('/rental/detail/{id}', [CustomerController::class, 'detailRental'])->name('rental.detail');
    });
});
