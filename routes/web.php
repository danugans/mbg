<?php

use App\Http\Controllers\DapurController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DapurController::class, 'ringkasan'])->name('ringkasan');

Route::get('/master-barang', [DapurController::class, 'masterBarang'])->name('master.indeks');
Route::post('/master-barang/simpan', [DapurController::class, 'simpanBarang'])->name('master.simpan');
Route::delete('/master-barang/hapus/{id}', [DapurController::class, 'hapusBarang'])->name('master.hapus');

Route::get('/barang-olahan', [DapurController::class, 'barangOlahan'])->name('olahan.indeks');
Route::post('/barang-olahan/mutasi', [DapurController::class, 'simpanMutasiStok'])->name('olahan.mutasi');

Route::get('/barang-inventaris', [DapurController::class, 'barangInventaris'])->name('inventaris.indeks');
Route::post('/barang-inventaris/service', [DapurController::class, 'simpanService'])->name('inventaris.service');

Route::get('/pengeluaran-operasional', [DapurController::class, 'pengeluaranOperasional'])->name('operasional.indeks');
Route::post('/pengeluaran-operasional/simpan', [DapurController::class, 'simpanPengeluaran'])->name('operasional.simpan');

Route::get('/laporan-bulanan', [DapurController::class, 'laporanBulanan'])->name('laporan.indeks');