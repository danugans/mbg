@extends('skema.aplikasi')
@section('konten')
<div>
    <header class="mb-6">
        <div class="font-mono text-[11px] text-muted uppercase">Dashboard Utama</div>
        <h1 class="font-display text-2xl text-green-deep font-bold">Kondisi Kas & Logistik Dapur</h1>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-green-deep text-white rounded-lg p-4">
            <div class="text-xs opacity-75 mb-1">Kas Keluar Bulan Ini</div>
            <div class="font-mono text-lg font-bold">Rp{{ number_format($totalKasKeluar, 0, ',', '.') }}</div>
        </div>
        <div class="bg-card border border-paperline rounded-lg p-4">
            <div class="text-xs text-muted mb-1">Nilai Aset Bahan Olahan</div>
            <div class="font-mono text-lg font-bold">Rp{{ number_format($totalNilaiStokOlahan, 0, ',', '.') }}</div>
        </div>
        <div class="bg-card border border-paperline rounded-lg p-4">
            <div class="text-xs text-muted mb-1">Pemakaian Bahan Dapur</div>
            <div class="font-mono text-lg font-bold">Rp{{ number_format($totalPemakaianBulanIni, 0, ',', '.') }}</div>
        </div>
        <div class="bg-card border border-paperline rounded-lg p-4">
            <div class="text-xs text-muted mb-1">Total Biaya Pemeliharaan</div>
            <div class="font-mono text-lg font-bold">Rp{{ number_format($serviceBulanIni, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-card border border-paperline rounded-lg p-4">
            <h3 class="font-display font-bold text-green mb-3">Arus Penggunaan Kas Bulan Ini</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between py-1 border-b border-dashed border-paperline"><span>Belanja Stok Masuk</span><b>Rp{{ number_format($pembelianBulanIni, 0, ',', '.') }}</b></div>
                <div class="flex justify-between py-1 border-b border-dashed border-paperline"><span>Biaya Service Inventaris</span><b>Rp{{ number_format($serviceBulanIni, 0, ',', '.') }}</b></div>
                <div class="flex justify-between py-1 border-b border-dashed border-paperline"><span>Biaya Operasional Umum</span><b>Rp{{ number_format($operasionalBulanIni, 0, ',', '.') }}</b></div>
            </div>
        </div>
        <div class="bg-card border border-paperline rounded-lg p-4">
            <h3 class="font-display font-bold text-green mb-3">Jadwal Service Terdekat</h3>
            <div class="space-y-2">
                @forelse($jadwalMendatang as $jm)
                <div class="flex justify-between items-center text-xs p-2 bg-paper rounded border">
                    <div><b>{{ $jm['barang']->nama_barang }}</b><div class="text-[10px] text-muted">{{ $jm['barang']->kategori }}</div></div>
                    <span class="font-mono font-bold text-rust">{{ date('d-m-Y', strtotime($jm['jatuh_tempo'])) }}</span>
                </div>
                @empty
                <div class="text-center text-xs text-muted py-4">Belum ada agenda service instan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection