@extends('skema.aplikasi')
@section('konten')
<div x-data="{ bukaModal: false, tipeMutasi: 'masuk' }">
    <header class="mb-5">
        <h1 class="font-display text-2xl font-bold text-green-deep">Kartu Stok & Perputaran Bahan Olahan</h1>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-[250px_1fr] gap-4">
        <div class="space-y-2">
            @foreach($barangOlahan as $bo)
            <a href="{{ route('olahan.indeks', ['terpilih' => $bo->id]) }}" class="block p-3 rounded-lg bg-card border text-left {{ $barangTerpilih && $barangTerpilih->id === $bo->id ? 'border-green ring-1 ring-green' : 'border-paperline' }}">
                <div class="font-bold text-sm">{{ $bo->nama_barang }}</div>
                <div class="text-xs text-muted font-mono mt-1">Stok: {{ $semuaBukuStok[$bo->id]['stok_akhir'] }} {{ $bo->satuan }}</div>
            </a>
            @endforeach
        </div>

        @if($barangTerpilih)
        <div class="bg-card border border-paperline rounded-lg p-4">
            <h2 class="font-display text-lg font-bold text-green-deep mb-3">{{ $barangTerpilih->nama_barang }}</h2>
            
            <div class="grid grid-cols-3 gap-2 mb-4 text-xs">
                <div class="bg-paper p-2 rounded"><span>Sisa Volume:</span><br><b class="font-mono text-sm">{{ $bukuStokTerpilih['stok_akhir'] }} {{ $barangTerpilih->satuan }}</b></div>
                <div class="bg-paper p-2 rounded"><span>Harga Rata:</span><br><b class="font-mono text-sm">Rp{{ number_format($bukuStokTerpilih['harga_rata'], 0, ',', '.') }}</b></div>
                <div class="bg-paper p-2 rounded"><span>Total Aset:</span><br><b class="font-mono text-sm">Rp{{ number_format($bukuStokTerpilih['total_nilai_aset'], 0, ',', '.') }}</b></div>
            </div>

            <div class="flex gap-2 mb-4">
                <button @click="bukaModal = true; tipeMutasi = 'masuk'" class="bg-mustard text-ink text-xs font-semibold px-3 py-1.5 rounded">+ Stok Masuk (Beli)</button>
                <button @click="bukaModal = true; tipeMutasi = 'keluar'" class="bg-rust text-white text-xs font-semibold px-3 py-1.5 rounded">- Stok Keluar (Guna)</button>
            </div>

            <table class="w-full text-xs text-left border">
                <thead>
                    <tr class="bg-paper font-mono text-muted uppercase">
                        <th class="p-2">Tanggal</th>
                        <th class="p-2">Tipe</th>
                        <th class="p-2">Jumlah</th>
                        <th class="p-2">Harga Satuan</th>
                        <th class="p-2">Total Transaksi</th>
                        <th class="p-2">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bukuStokTerpilih['rows'] as $r)
                    <tr class="border-b">
                        <td class="p-2 font-mono">{{ $r['tanggal'] }}</td>
                        <td class="p-2"><span class="px-1.5 py-0.5 rounded text-[10px] {{ $r['tipe'] === 'masuk' ? 'bg-green/10 text-green' : 'bg-rust/10 text-rust' }}">{{ strtoupper($r['tipe']) }}</span></td>
                        <td class="p-2 font-mono">{{ $r['jumlah'] }}</td>
                        <td class="p-2 font-mono">Rp{{ number_format($r['harga_satuan'] ?? 0, 0, ',', '.') }}</td>
                        <td class="p-2 font-mono">Rp{{ number_format($r['nilai_transaksi'], 0, ',', '.') }}</td>
                        <td class="p-2 font-mono font-bold">{{ $r['saldo_stok'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>

    <div x-show="bukaModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-card border border-paperline rounded-lg w-full max-w-sm p-5" @click.outside="bukaModal = false">
            <h3 class="font-display font-bold text-sm mb-3">Pencatatan Mutasi <span x-text="tipeMutasi.toUpperCase()"></span></h3>
            <form action="{{ route('olahan.mutasi') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="barang_id" value="{{ $barangTerpilih?->id }}">
                <input type="hidden" name="tipe" :value="tipeMutasi">
                <div>
                    <label class="block text-xs text-muted mb-1">Tanggal</label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <div>
                    <label class="block text-xs text-muted mb-1">Jumlah Volume ({{ $barangTerpilih?->satuan }})</label>
                    <input type="number" step="0.01" name="jumlah" required class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <div x-show="tipeMutasi === 'masuk'">
                    <label class="block text-xs text-muted mb-1">Harga Beli per Satuan (Rp)</label>
                    <input type="number" name="harga_satuan" ::required="tipeMutasi === 'masuk'" class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <div>
                    <label class="block text-xs text-muted mb-1">Catatan</label>
                    <input type="text" name="catatan" placeholder="Keterangan..." class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <button type="submit" class="w-full bg-green text-white py-2 rounded text-xs font-bold">Simpan Mutasi</button>
            </form>
        </div>
    </div>
</div>
@endsection