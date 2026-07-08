@extends('skema.aplikasi')
@section('konten')
<div>
    <header class="mb-6">
        <div class="font-mono text-[11px] text-muted uppercase">Akses Pengguna</div>
        <h1 class="font-display text-2xl text-green-deep font-bold">Hak dan Pengaturan Akses Pengguna</h1>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="bg-card border border-paperline rounded-lg p-6 shadow-sm">
            <h2 class="font-display text-lg font-bold text-green-deep mb-3">Instruksi Akses</h2>
            <p class="text-sm text-muted leading-relaxed">Di halaman ini, Anda dapat meninjau peran pengguna dan hak aksesnya. Gunakan menu di samping untuk membuka modul lain, atau tambahkan pengaturan akses berikut jika diperlukan.</p>
            <ul class="mt-4 space-y-3 text-sm">
                <li class="bg-paper p-3 rounded border border-paperline">Lihat daftar pengguna yang boleh mengakses sistem.</li>
                <li class="bg-paper p-3 rounded border border-paperline">Kelola level akses untuk dashboard, data barang, dan laporan.</li>
                <li class="bg-paper p-3 rounded border border-paperline">Pastikan hanya admin yang dapat mengubah master data dan pengeluaran operasional.</li>
            </ul>
        </div>
        <div class="bg-card border border-paperline rounded-lg p-6 shadow-sm">
            <h2 class="font-display text-lg font-bold text-green-deep mb-3">Status Akses Saat Ini</h2>
            <div class="space-y-3 text-sm text-muted">
                <div class="bg-white rounded-lg border border-paperline p-4">
                    <div class="font-semibold">Admin</div>
                    <div class="text-xs text-muted">Akses penuh ke seluruh fitur sistem.</div>
                </div>
                <div class="bg-white rounded-lg border border-paperline p-4">
                    <div class="font-semibold">Operator Dapur</div>
                    <div class="text-xs text-muted">Akses terbatas hanya untuk input bahan, layanan, dan pengeluaran.</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
