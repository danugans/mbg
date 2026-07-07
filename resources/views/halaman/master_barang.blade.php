@extends('skema.aplikasi')
@section('konten')
<div x-data="{ bukaModal: false }">
    <header class="flex justify-between items-center mb-6">
        <div>
            <h1 class="font-display text-2xl font-bold text-green-deep">Master Data Barang</h1>
        </div>
        <button @click="bukaModal = true" class="bg-green text-white text-xs font-semibold px-4 py-2 rounded shadow">+ Daftarkan Barang</button>
    </header>

    <div class="bg-card border border-paperline rounded-lg overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead>
                <tr class="bg-paper font-mono text-xs uppercase border-b border-paperline text-muted">
                    <th class="p-3">Nama Barang</th>
                    <th class="p-3">Jenis</th>
                    <th class="p-3">Kategori</th>
                    <th class="p-3">Spesifikasi Ukur/Service</th>
                    <th class="p-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($daftarBarang as $b)
                <tr class="border-b border-paperline">
                    <td class="p-3 font-semibold">{{ $b->nama_barang }}</td>
                    <td class="p-3"><span class="text-[11px] px-2 py-0.5 rounded {{ $b->jenis === 'olahan' ? 'bg-mustard/20 text-mustard' : 'bg-rust/20 text-rust' }}">{{ ucfirst($b->jenis) }}</span></td>
                    <td class="p-3">{{ $b->kategori }}</td>
                    <td class="p-3 font-mono text-xs">{{ $b->jenis === 'olahan' ? "$b->satuan" : "Setiap $b->interval_service_bulan Bulan" }}</td>
                    <td class="p-3">
                        <form action="{{ route('master.hapus', $b->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini?')">
                            @csrf @method('DELETE')
                            <button class="text-rust text-xs hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="bukaModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-card border border-paperline rounded-lg w-full max-w-sm p-5" @click.outside="bukaModal = false" x-data="{ tipePilihan: 'olahan' }">
            <h3 class="font-display font-bold text-base mb-3 text-green-deep">Daftarkan Barang Baru</h3>
            <form action="{{ route('master.simpan') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs text-muted uppercase mb-1">Jenis Barang</label>
                    <select name="jenis" x-model="tipePilihan" class="w-full border rounded p-2 text-xs bg-white">
                        <option value="olahan">Barang Olahan (Bahan Habis Pakai)</option>
                        <option value="inventaris">Barang Inventaris (Aset Tetap)</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-muted uppercase mb-1">Nama Barang</label>
                    <input type="text" name="nama_barang" required class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <div>
                    <label class="block text-xs text-muted uppercase mb-1">Kategori</label>
                    <input type="text" name="kategori" required placeholder="mis. Sayur, Elektronik" class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <div x-show="tipePilihan === 'olahan'">
                    <label class="block text-xs text-muted uppercase mb-1">Satuan</label>
                    <input type="text" name="satuan" placeholder="mis. kg, liter" class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <div x-show="tipePilihan === 'inventaris'">
                    <label class="block text-xs text-muted uppercase mb-1">Interval Rutin Service (Bulan)</label>
                    <input type="number" name="interval_service_bulan" class="w-full border rounded p-2 text-xs bg-white" value="3">
                </div>
                <button type="submit" class="w-full bg-green text-white py-2 rounded text-xs font-bold">Simpan</button>
            </form>
        </div>
    </div>
</div>
@endsection