<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use App\Models\RiwayatService;
use App\Models\PengeluaranOperasional;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanBulananController extends Controller
{
    public function indeks(Request $request)
    {
        $bulan = $request->query('bulan', Carbon::now()->format('Y-m'));
        
        $kumpulanBulan = collect([Carbon::now()->format('Y-m')]);
        MutasiStok::select('tanggal')->get()->each(function($m) use ($kumpulanBulan){ $kumpulanBulan->push(substr($m->tanggal, 0, 7)); });
        RiwayatService::select('tanggal')->get()->each(function($s) use ($kumpulanBulan){ $kumpulanBulan->push(substr($s->tanggal, 0, 7)); });
        PengeluaranOperasional::select('tanggal')->get()->each(function($e) use ($kumpulanBulan){ $kumpulanBulan->push(substr($e->tanggal, 0, 7)); });
        $opsiBulan = $kumpulanBulan->unique()->sort()->values()->all();

        $mutasiBulanIni = MutasiStok::where('tanggal', 'like', "$bulan%")->get();
        $totalPembelian = $mutasiBulanIni->where('tipe', 'masuk')->sum(function($m){ return $m->jumlah * $m->harga_satuan; });
        
        $serviceBulanIni = RiwayatService::where('tanggal', 'like', "$bulan%")->get();
        $totalService = $serviceBulanIni->sum('biaya');
        
        $operasionalBulanIni = PengeluaranOperasional::where('tanggal', 'like', "$bulan%")->get();
        $totalOperasional = $operasionalBulanIni->sum('nominal');
        
        $grandTotalKas = $totalPembelian + $totalService + $totalOperasional;
        $nilaiMaksimumBar = max($totalPembelian, $totalService, $totalOperasional, 1);

        $pembelianPerBarang = [];
        foreach ($mutasiBulanIni->where('tipe', 'masuk') as $m) {
            $pembelianPerBarang[$m->barang_id] = ($pembelianPerBarang[$m->barang_id] ?? 0) + ($m->jumlah * $m->harga_satuan);
        }

        $barangOlahan = Barang::where('jenis', 'olahan')->get();

        return view('halaman.laporan_bulanan', compact('bulan', 'opsiBulan', 'totalPembelian', 'totalService', 'totalOperasional', 'grandTotalKas', 'nilaiMaksimumBar', 'pembelianPerBarang', 'barangOlahan', 'serviceBulanIni'));
    }
}