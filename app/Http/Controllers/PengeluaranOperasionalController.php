<?php

namespace App\Http\Controllers;

use App\Models\PengeluaranOperasional;
use Illuminate\Http\Request;

class PengeluaranOperasionalController extends Controller
{
    public function indeks()
    {
        $daftarPengeluaran = PengeluaranOperasional::orderBy('tanggal', 'desc')->get();
        $totalPengeluaran = $daftarPengeluaran->sum('nominal');
        return view('halaman.pengeluaran_operasional', compact('daftarPengeluaran', 'totalPengeluaran'));
    }

    public function simpan(Request $request)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'kategori' => 'required|string',
            'nominal' => 'required|numeric|min:1',
        ]);

        PengeluaranOperasional::create($request->all());
        return redirect()->back()->with('success', 'Pengeluaran operasional berhasil dicatat!');
    }
}