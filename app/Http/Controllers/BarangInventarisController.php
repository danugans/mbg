<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\RiwayatService;
use App\Helpers\KalkulatorDapur;
use Illuminate\Http\Request;

class BarangInventarisController extends Controller
{
    public function indeks(Request $request)
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
        return redirect()->route('inventaris.indeks', ['terpilih' => $request->barang_id])->with('success', 'Catatan service telah ditambahkan!');
    }
}