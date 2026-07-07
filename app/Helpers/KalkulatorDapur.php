<?php

namespace App\Helpers;

use Carbon\Carbon;

class KalkulatorDapur
{
    public static function hitungBukuStok($daftarMutasi)
    {
        $stok_sekarang = 0;
        $harga_rata_rata = 0;
        $baris_tabel = [];

        foreach ($daftarMutasi as $m) {
            $nilai_transaksi = 0;
            if ($m->tipe === 'masuk') {
                $stok_baru = $stok_sekarang + $m->jumlah;
                $harga_rata_rata = $stok_baru > 0 
                    ? (($stok_sekarang * $harga_rata_rata) + ($m->jumlah * $m->harga_satuan)) / $stok_baru 
                    : $m->harga_satuan;
                $stok_sekarang = $stok_baru;
                $nilai_transaksi = $m->jumlah * $m->harga_satuan;
            } else {
                $nilai_transaksi = $m->jumlah * $harga_rata_rata;
                $stok_sekarang = max(0, $stok_sekarang - $m->jumlah);
            }

            $baris_tabel[] = [
                'id' => $m->id,
                'tanggal' => $m->tanggal,
                'tipe' => $m->tipe,
                'jumlah' => $m->jumlah,
                'harga_satuan' => $m->harga_satuan,
                'saldo_stok' => $stok_sekarang,
                'rata_rata_saat_ini' => $harga_rata_rata,
                'nilai_transaksi' => $nilai_transaksi,
                'catatan' => $m->catatan
            ];
        }

        return [
            'rows' => array_reverse($baris_tabel), // Terbaru muncul di atas
            'stok_akhir' => $stok_sekarang,
            'harga_rata' => $harga_rata_rata,
            'total_nilai_aset' => $stok_sekarang * $harga_rata_rata
        ];
    }

    public static function hitungJadwalServiceBerikutnya($barang, $semuaService)
    {
        $serviceBarang = $semuaService->where('barang_id', $barang->id)->sortByDesc('tanggal');
        if ($serviceBarang->isEmpty()) {
            return null;
        }
        $tanggalTerakhir = Carbon::parse($serviceBarang->first()->tanggal);
        return $tanggalTerakhir->addMonths($barang->interval_service_bulan ?? 3)->format('Y-m-d');
    }
}