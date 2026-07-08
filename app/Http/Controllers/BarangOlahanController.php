<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\MutasiStok;
use App\Helpers\KalkulatorDapur;
use Illuminate\Http\Request;

class BarangOlahanController extends Controller
{
    public function indeks(Request $request)
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

    public function simpanMutasi(Request $request)
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
}