@extends('skema.aplikasi')
@section('konten')
<div>
    <header class="flex justify-between items-center mb-6">
        <h1 class="font-display text-xl font-bold text-green-deep">Laporan Penutupan Kas Bulanan</h1>
        <form action="{{ route('laporan.indeks') }}" method="GET">
            <select name="bulan" onchange="this.form.submit()" class="font-mono border text-xs p-1.5 rounded bg-card">
                @foreach($opsiBulan as $ob)
                <option value="{{ $ob }}" {{ $ob === $bulan ? 'selected' : '' }}>{{ $ob }}</option>
                @endforeach
            </select>
        </form>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-card border border-paperline rounded-lg p-4">
            <h3 class="font-display font-bold text-xs uppercase text-muted mb-3">Grafik Penyerapan Anggaran Dana</h3>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between text-xs mb-0.5"><span>Belanja Bahan Olahan</span><b>Rp{{ number_format($totalPembelian, 0, ',', '.') }}</b></div>
                    <div class="bg-paper h-2 w-full rounded"><div class="bg-mustard h-full rounded" style="width: {{ ($totalPembelian/$nilaiMaksimumBar)*100 }}%"></div></div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-0.5"><span>Pemeliharaan Service</span><b>Rp{{ number_format($totalService, 0, ',', '.') }}</b></div>
                    <div class="bg-paper h-2 w-full rounded"><div class="bg-rust h-full rounded" style="width: {{ ($totalService/$nilaiMaksimumBar)*100 }}%"></div></div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-0.5"><span>Beban Operasional</span><b>Rp{{ number_format($totalOperasional, 0, ',', '.') }}</b></div>
                    <div class="bg-paper h-2 w-full rounded"><div class="bg-green h-full rounded" style="width: {{ ($totalOperasional/$nilaiMaksimumBar)*100 }}%"></div></div>
                </div>
            </div>
            <div class="mt-4 pt-3 border-t font-mono text-sm font-bold flex justify-between">
                <span>TOTAL KAS KELUAR BULANAN:</span><span>Rp{{ number_format($grandTotalKas, 0, ',', '.') }}</span>
            </div>
        </div>

        <div class="bg-card border border-paperline rounded-lg p-4 text-xs">
            <h3 class="font-display font-bold text-xs text-green-deep mb-2">Belanja Inventori</h3>
            <div class="divide-y divide-dashed">
                @foreach($barangOlahan as $bo)
                    @if(isset($pembelianPerBarang[$bo->id]))
                    <div class="flex justify-between py-1.5"><span>{{ $bo->nama_barang }}</span><b>Rp{{ number_format($pembelianPerBarang[$bo->id], 0, ',', '.') }}</b></div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection