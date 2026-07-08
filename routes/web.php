<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MasterBarangController;
use App\Http\Controllers\BarangOlahanController;
use App\Http\Controllers\BarangInventarisController;
use App\Http\Controllers\PengeluaranOperasionalController;
use App\Http\Controllers\LaporanBulananController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Root -> login view
Route::get('/', function () {
    return view('masuk.masuk');
})->name('login');

Route::get('/masuk', function () { return view('masuk.masuk'); });
Route::post('/masuk', function (Request $request) {
    $data = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'password' => ['required'],
    ]);

    $credentials = ['name' => $data['name'], 'password' => $data['password']];
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect()->route('ringkasan');
    }

    return back()->withErrors(['name' => 'Nama pengguna atau password salah.'])->withInput();
})->name('login.submit');

// Ringkasan / Dashboard
Route::get('/ringkasan', [DashboardController::class, 'indeks'])->name('ringkasan');

// Master Barang
Route::prefix('master-barang')->name('master.')->group(function () {
    Route::get('/', [MasterBarangController::class, 'indeks'])->name('indeks');
    Route::post('/simpan', [MasterBarangController::class, 'simpan'])->name('simpan');
    Route::delete('/hapus/{id}', [MasterBarangController::class, 'hapus'])->name('hapus');
});

// Barang Olahan (Bahan Baku)
Route::prefix('barang-olahan')->name('olahan.')->group(function () {
    Route::get('/', [BarangOlahanController::class, 'indeks'])->name('indeks');
    Route::post('/mutasi', [BarangOlahanController::class, 'simpanMutasi'])->name('mutasi');
});

// Barang Inventaris (Aset Tetap)
Route::prefix('barang-inventaris')->name('inventaris.')->group(function () {
    Route::get('/', [BarangInventarisController::class, 'indeks'])->name('indeks');
    Route::post('/service', [BarangInventarisController::class, 'simpanService'])->name('service');
});

// Pengeluaran Operasional
Route::prefix('pengeluaran-operasional')->name('operasional.')->group(function () {
    Route::get('/', [PengeluaranOperasionalController::class, 'indeks'])->name('indeks');
    Route::post('/simpan', [PengeluaranOperasionalController::class, 'simpan'])->name('simpan');
});

// Laporan Bulanan
Route::get('/laporan-bulanan', [LaporanBulananController::class, 'indeks'])->name('laporan.indeks');