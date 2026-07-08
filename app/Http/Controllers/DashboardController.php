<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use App\Models\RiwayatService;
use App\Models\PengeluaranOperasional;
use App\Helpers\KalkulatorDapur;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function indeks()
    {
        $bulanIni = Carbon::now()->format('Y-m');
        $semuaBarang = Barang::all();
        $semuaService = RiwayatService::all();

        $pembelianBulanIni = MutasiStok::where('tipe', 'masuk')->where('tanggal', 'like', "$bulanIni%")->get()->sum(function($m){ 
            return $m->jumlah * $m->harga_satuan; 
        });
        $serviceBulanIni = RiwayatService::where('tanggal', 'like', "$bulanIni%")->sum('biaya');
        $operasionalBulanIni = PengeluaranOperasional::where('tanggal', 'like', "$bulanIni%")->sum('nominal');
        $totalKasKeluar = $pembelianBulanIni + $serviceBulanIni + $operasionalBulanIni;

        $totalNilaiStokOlahan = 0;
        $totalPemakaianBulanIni = 0;
        $barangOlahan = $semuaBarang->where('jenis', 'olahan');
        
        foreach ($barangOlahan as $b) {
            $bukuStok = KalkulatorDapur::hitungBukuStok($b->mutasiStok);
            $totalNilaiStokOlahan += $bukuStok['total_nilai_aset'];
            
            foreach ($bukuStok['rows'] as $baris) {
                if ($baris['tipe'] === 'keluar' && str_starts_with($baris['tanggal'], $bulanIni)) {
                    $totalPemakaianBulanIni += $baris['nilai_transaksi'];
                }
            }
        }

        $jadwalMendatang = [];
        foreach ($semuaBarang->where('jenis', 'inventaris') as $b) {
            $jatuhTempo = KalkulatorDapur::hitungJadwalServiceBerikutnya($b, $semuaService);
            if ($jatuhTempo) {
                $jadwalMendatang[] = ['barang' => $b, 'jatuh_tempo' => $jatuhTempo];
            }
        }
        usort($jadwalMendatang, function($a, $b) { return strcmp($a['jatuh_tempo'], $b['jatuh_tempo']); });

        // Arahkan ke file view dashboard
        return view('halaman.dashboard', compact(
            'totalKasKeluar', 'totalNilaiStokOlahan', 'totalPemakaianBulanIni', 
            'serviceBulanIni', 'pembelianBulanIni', 'operasionalBulanIni', 'jadwalMendatang'
        ));
    }
}