<?php

use App\Http\Controllers\LapanganController;
use App\Http\Controllers\ReservasiController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MembershipController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\LapanganController as AdminLapanganController;
use App\Http\Controllers\Admin\ReservasiController as AdminReservasiController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ============================================================
// HALAMAN UTAMA (PUBLIC)
// ============================================================
Route::get('/', [LapanganController::class, 'index'])->name('home');
Route::get('/lapangan/{id}', [LapanganController::class, 'show'])->name('lapangan.show');

// ============================================================
// AUTH ROUTES (BAWAAN BREEZE) - JANGAN DIHAPUS
// ============================================================
require __DIR__.'/auth.php';

// ============================================================
// ROUTE DASHBOARD (WAJIB LOGIN)
// ============================================================
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('customer.dashboard');
    })->name('dashboard');
});

// ============================================================
// CUSTOMER ROUTES (WAJIB LOGIN)
// ============================================================
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/reservasi', [ReservasiController::class, 'index'])->name('reservasi');
    Route::post('/reservasi', [ReservasiController::class, 'store'])->name('reservasi.store');

    Route::get('/riwayat', [ReservasiController::class, 'riwayat'])->name('riwayat');

    Route::get('/payment/{id}', [PaymentController::class, 'reservasi'])->name('payment');
    Route::post('/payment/{id}', [PaymentController::class, 'uploadBukti'])->name('payment.upload');

    Route::get('/membership', [MembershipController::class, 'index'])->name('membership');
    Route::get('/payment-membership/{id}', [PaymentController::class, 'membership'])->name('payment.membership');
    Route::post('/payment-membership/{id}', [PaymentController::class, 'uploadMembership'])->name('payment.membership.upload');
});

// ============================================================
// ADMIN ROUTES (WAJIB LOGIN + ADMIN)
// ============================================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Kelola Lapangan
    Route::get('/lapangan', [AdminLapanganController::class, 'index'])->name('lapangan');
    Route::get('/lapangan/create', [AdminLapanganController::class, 'create'])->name('lapangan.create');
    Route::post('/lapangan', [AdminLapanganController::class, 'store'])->name('lapangan.store');
    Route::get('/lapangan/{id}/edit', [AdminLapanganController::class, 'edit'])->name('lapangan.edit');
    Route::put('/lapangan/{id}', [AdminLapanganController::class, 'update'])->name('lapangan.update');
    Route::delete('/lapangan/{id}', [AdminLapanganController::class, 'destroy'])->name('lapangan.destroy');
    Route::post('/lapangan/{id}/toggle', [AdminLapanganController::class, 'toggleStatus'])->name('lapangan.toggle');

    // Kelola Reservasi
    Route::get('/reservasi', [AdminReservasiController::class, 'index'])->name('reservasi');
    Route::post('/reservasi/{id}/verifikasi', [AdminReservasiController::class, 'verifikasi'])->name('reservasi.verifikasi');
    Route::post('/reservasi/{id}/batalkan', [AdminReservasiController::class, 'batalkan'])->name('reservasi.batalkan');
    Route::post('/reservasi/{id}/selesai', [AdminReservasiController::class, 'selesai'])->name('reservasi.selesai');
    Route::delete('/reservasi/{id}', [AdminReservasiController::class, 'destroy'])->name('reservasi.destroy');

    // Kelola Membership & Tier
    Route::get('/member', [AdminMemberController::class, 'index'])->name('member');
    Route::post('/member/{id}/verifikasi', [AdminMemberController::class, 'verifikasi'])->name('member.verifikasi');
    Route::delete('/member/{id}', [AdminMemberController::class, 'destroy'])->name('member.destroy');

    Route::prefix('tier')->name('tier.')->group(function () {
        Route::get('/create', [AdminMemberController::class, 'createTier'])->name('create');
        Route::post('/', [AdminMemberController::class, 'storeTier'])->name('store');
        Route::get('/{id}/edit', [AdminMemberController::class, 'editTier'])->name('edit');
        Route::put('/{id}', [AdminMemberController::class, 'updateTier'])->name('update');
        Route::delete('/{id}', [AdminMemberController::class, 'destroyTier'])->name('destroy');
    });
});