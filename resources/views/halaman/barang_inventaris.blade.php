@extends('skema.aplikasi')
@section('konten')
<div x-data="{ bukaModalService: false }">
    <header class="mb-5">
        <h1 class="font-display text-2xl font-bold text-green-deep">Manajemen Pemeliharaan Barang Inventaris</h1>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-[250px_1fr] gap-4">
        <div class="space-y-2">
            @forelse($barangInventaris as $bi)
            <a href="{{ route('inventaris.indeks', ['terpilih' => $bi->id]) }}" class="block p-3 rounded-lg bg-card border text-left {{ $barangTerpilih && $barangTerpilih->id === $bi->id ? 'border-green ring-1 ring-green' : 'border-paperline' }}">
                <div class="font-bold text-sm">{{ $bi->nama_barang }}</div>
                <div class="text-[11px] text-muted font-mono mt-0.5">{{ $bi->kategori }}</div>
            </a>
            @empty
            <div class="text-xs text-muted p-2">Belum ada barang inventaris terdaftar.</div>
            @endforelse
        </div>

        @if($barangTerpilih)
        <div class="bg-card border border-paperline rounded-lg p-5">
            <div class="flex justify-between items-start border-b border-paperline pb-3 mb-4">
                <div>
                    <span class="font-mono text-[10px] text-muted uppercase">Kartu Kontrol Service Aset</span>
                    <h2 class="font-display text-lg font-bold text-green-deep">{{ $barangTerpilih->nama_barang }}</h2>
                    <p class="text-xs text-muted">Kategori: {{ $barangTerpilih->kategori }} · Siklus Service Wajib: {{ $barangTerpilih->interval_service_bulan }} Bulan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-4 text-xs">
                <div class="bg-paper p-3 rounded border border-paperline">
                    <span class="text-muted block text-[10px] uppercase">Estimasi Jadwal Service Berikutnya</span>
                    <b class="font-mono text-sm {{ $jadwalBerikutnya && $jadwalBerikutnya < date('Y-m-d') ? 'text-rust' : 'text-green-deep' }}">
                        {{ $jadwalBerikutnya ? date('d M Y', strtotime($jadwalBerikutnya)) : 'Belum Pernah Service' }}
                    </b>
                </div>
                <div class="bg-paper p-3 rounded border border-paperline">
                    <span class="text-muted block text-[10px] uppercase">Akumulasi Biaya Perawatan Seluruhnya</span>
                    <b class="font-mono text-sm">Rp{{ number_format($totalBiayaService, 0, ',', '.') }}</b>
                </div>
            </div>

            <button @click="bukaModalService = true" class="bg-rust text-white text-xs font-semibold px-4 py-2 rounded mb-4 shadow hover:bg-rust/90">
                + Catat Log Perbaikan / Service Baru
            </button>

            <h3 class="font-display font-bold text-xs uppercase text-muted mb-2 tracking-wider">Histori Service Pemeliharaan</h3>
            <div class="overflow-x-auto border border-paperline rounded">
                <table class="w-full text-xs text-left border-collapse">
                    <thead>
                        <tr class="bg-paper font-mono border-b border-paperline text-muted uppercase">
                            <th class="p-2.5">Tanggal</th>
                            <th class="p-2.5">Nama Vendor / Teknisi</th>
                            <th class="p-2.5">Biaya (Rp)</th>
                            <th class="p-2.5">Catatan Perbaikan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($riwayatTerpilih as $rs)
                        <tr class="border-b border-paperline bg-white/50">
                            <td class="p-2.5 font-mono">{{ date('d-m-Y', strtotime($rs->tanggal)) }}</td>
                            <td class="p-2.5 font-semibold text-green-deep">{{ $rs->vendor }}</td>
                            <td class="p-2.5 font-mono font-bold">Rp{{ number_format($rs->biaya, 0, ',', '.') }}</td>
                            <td class="p-2.5 text-muted">{{ $rs->catatan ?? '—' }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-5">Barang ini belum memiliki riwayat tindakan service pemeliharaan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @else
        <div class="bg-card border border-paperline rounded-lg p-5 text-center text-xs text-muted">
            Silakan pilih salah satu barang inventaris di bilah kiri untuk memunculkan log buku kontrol pemeliharaan.
        </div>
        @endif
    </div>

    <div x-show="bukaModalService" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-card border border-paperline rounded-lg w-full max-w-sm p-5" @click.outside="bukaModalService = false">
            <h3 class="font-display font-bold text-base text-green-deep mb-3 border-b pb-2">Catat Riwayat Service Baru</h3>
            <form action="{{ route('inventaris.service') }}" method="POST" class="space-y-3">
                @csrf
                <input type="hidden" name="barang_id" value="{{ $barangTerpilih?->id }}">
                
                <div>
                    <label class="block text-xs text-muted mb-1 uppercase tracking-tight">Tanggal Tindakan</label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full border rounded p-2 text-xs bg-white">
                </div>
                
                <div>
                    <label class="block text-xs text-muted mb-1 uppercase tracking-tight">Nama Vendor / Bengkel / Teknisi</label>
                    <input type="text" name="vendor" required placeholder="mis. CV Maju Jaya AC, Bengkel Toyota" class="w-full border rounded p-2 text-xs bg-white">
                </div>

                <div>
                    <label class="block text-xs text-muted mb-1 uppercase tracking-tight">Biaya Service Total (Rp)</label>
                    <input type="number" name="biaya" required placeholder="0" class="w-full border rounded p-2 text-xs bg-white">
                </div>

                <div>
                    <label class="block text-xs text-muted mb-1 uppercase tracking-tight">Catatan Detail Kerusakan / Penggantian Part</label>
                    <textarea name="catatan" rows="3" placeholder="mis. Ganti freon, Tambah oli, Service rutin berkala" class="w-full border rounded p-2 text-xs bg-white"></textarea>
                </div>

                <div class="flex gap-2 pt-2">
                    <button type="button" @click="bukaModalService = false" class="w-1/3 bg-paper border border-paperline py-2 rounded text-xs">Batal</button>
                    <button type="submit" class="w-2/3 bg-green text-white py-2 rounded text-xs font-bold shadow">Simpan Log Perawatan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection