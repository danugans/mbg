<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class MasterBarangController extends Controller
{
    public function indeks(Request $request)
    {
        $saring = $request->query('saring', 'semua');
        $query = Barang::query();
        if ($saring !== 'semua') {
            $query->where('jenis', $saring);
        }
        $daftarBarang = $query->orderBy('created_at', 'desc')->get();
        return view('halaman.master_barang', compact('daftarBarang', 'saring'));
    }

    public function simpan(Request $request)
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

    public function hapus($id)
    {
        Barang::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus!');
    }
}