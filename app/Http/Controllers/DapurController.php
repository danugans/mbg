<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use App\Models\RiwayatService;
use App\Models\PengeluaranOperasional;
use App\Helpers\KalkulatorDapur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DapurController extends Controller
{
    public function ringkasan()
    {
        $bulanIni = Carbon::now()->format('Y-m');
        $semuaBarang = Barang::all();
        $semuaService = RiwayatService::all();

        // 1. Hitung total kas keluar bulan ini
        $pembelianBulanIni = MutasiStok::where('tipe', 'masuk')->where('tanggal', 'like', "$bulanIni%")->get()->sum(function($m){ 
            return $m->jumlah * $m->harga_satuan; 
        });
        $serviceBulanIni = RiwayatService::where('tanggal', 'like', "$bulanIni%")->sum('biaya');
        $operasionalBulanIni = PengeluaranOperasional::where('tanggal', 'like', "$bulanIni%")->sum('nominal');
        $totalKasKeluar = $pembelianBulanIni + $serviceBulanIni + $operasionalBulanIni;

        // 2. Hitung nilai stok & pemakaian internal
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

        // 3. Prediksi jadwal service mendatang
        $jadwalMendatang = [];
        foreach ($semuaBarang->where('jenis', 'inventaris') as $b) {
            $jatuhTempo = KalkulatorDapur::hitungJadwalServiceBerikutnya($b, $semuaService);
            if ($jatuhTempo) {
                $jadwalMendatang[] = ['barang' => $b, 'jatuh_tempo' => $jatuhTempo];
            }
        }
        usort($jadwalMendatang, function($a, $b) { return strcmp($a['jatuh_tempo'], $b['jatuh_tempo']); });

        return view('halaman.ringkasan', compact(
            'totalKasKeluar', 'totalNilaiStokOlahan', 'totalPemakaianBulanIni', 
            'serviceBulanIni', 'pembelianBulanIni', 'operasionalBulanIni', 'jadwalMendatang'
        ));
    }

    public function masterBarang(Request $request)
    {
        $saring = $request->query('saring', 'semua');
        $query = Barang::query();
        if ($saring !== 'semua') {
            $query->where('jenis', $saring);
        }
        $daftarBarang = $query->orderBy('created_at', 'desc')->get();
        return view('halaman.master_barang', compact('daftarBarang', 'saring'));
    }

    public function simpanBarang(Request $request)
    {
        $request->validate([
            'nama_barang' => 'required|string',
            'jenis' => 'required|in:olahan,inventaris',
            'kategori' => 'required|string',
        ]);

        Barang::create([
            'nama_barang' => $request->nama_barang,
            'jenis' => $request->jenis,
            'kategori' => $request->kategori,
            'satuan' => $request->jenis === 'olahan' ? $request->satuan : null,
            'interval_service_bulan' => $request->jenis === 'inventaris' ? $request->interval_service_bulan : null,
        ]);

        return redirect()->back()->with('success', 'Barang baru berhasil didaftarkan!');
    }

    public function hapusBarang($id)
    {
        Barang::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus dari sistem!');
    }

    public function barangOlahan(Request $request)
    {
        $barangOlahan = Barang::where('jenis', 'olahan')->get();
        $idTerpilih = $request->query('terpilih', $barangOlahan->first()?->id);
        
        $semuaBukuStok = [];
        foreach ($barangOlahan as $b) {
            $semuaBukuStok[$b->id] = KalkulatorDapur::hitungBukuStok($b->mutasiStok);
        }

        $barangTerpilih = $barangOlahan->firstWhere('id', $idTerpilih);
        $bukuStokTerpilih = $barangTerpilih ? $semuaBukuStok[$barangTerpilih->id] : null;

        return view('halaman.barang_olahan', compact('barangOlahan', 'barangTerpilih', 'bukuStokTerpilih', 'semuaBukuStok'));
    }

    public function simpanMutasiStok(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal' => 'required|date',
            'tipe' => 'required|in:masuk,keluar',
            'jumlah' => 'required|numeric|min:0.01',
            'harga_satuan' => 'required_if:tipe,masuk|nullable|numeric',
        ]);

        MutasiStok::create($request->all());
        return redirect()->route('olahan.indeks', ['terpilih' => $request->barang_id])->with('success', 'Mutasi stok berhasil disimpan!');
    }

    public function barangInventaris(Request $request)
    {
        $barangInventaris = Barang::where('jenis', 'inventaris')->get();
        $idTerpilih = $request->query('terpilih', $barangInventaris->first()?->id);
        $barangTerpilih = $barangInventaris->firstWhere('id', $idTerpilih);
        
        $semuaService = RiwayatService::all();
        $riwayatTerpilih = [];
        $jadwalBerikutnya = null;
        $totalBiayaService = 0;

        if ($barangTerpilih) {
            $riwayatTerpilih = RiwayatService::where('barang_id', $barangTerpilih->id)->orderBy('tanggal', 'desc')->get();
            $jadwalBerikutnya = KalkulatorDapur::hitungJadwalServiceBerikutnya($barangTerpilih, $semuaService);
            $totalBiayaService = $riwayatTerpilih->sum('biaya');
        }

        return view('halaman.barang_inventaris', compact('barangInventaris', 'barangTerpilih', 'riwayatTerpilih', 'jadwalBerikutnya', 'totalBiayaService'));
    }

    public function simpanService(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,id',
            'tanggal' => 'required|date',
            'biaya' => 'required|numeric|min:0',
            'vendor' => 'required|string',
        ]);

        RiwayatService::create($request->all());
        return redirect()->route('inventaris.indeks', ['terpilih' => $request->barang_id])->with('success', 'Catatan pemeliharaan service telah ditambahkan!');
    }

    public function pengeluaranOperasional()
    {
        $daftarPengeluaran = PengeluaranOperasional::orderBy('tanggal', 'desc')->get();
        $totalPengeluaran = $daftarPengeluaran->sum('nominal');
        return view('halaman.pengeluaran_operasional', compact('daftarPengeluaran', 'totalPengeluaran'));
    }

    public function simpanPengeluaran(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'nominal' => 'required|numeric|min:1',
        ]);

        PengeluaranOperasional::create($request->all());
        return redirect()->back()->with('success', 'Pengeluaran operasional berhasil dicatat!');
    }

    public function laporanBulanan(Request $request)
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