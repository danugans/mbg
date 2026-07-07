@extends('skema.aplikasi')
@section('konten')
<div x-data="{ bukaModal: false }">
    <header class="flex justify-between items-center mb-5">
        <h1 class="font-display text-2xl font-bold text-green-deep">Pengeluaran Operasional Umum</h1>
        <button @click="bukaModal = true" class="bg-green text-white text-xs font-semibold px-4 py-2 rounded">+ Tambah Pengeluaran</button>
    </header>

    <div class="bg-green-deep text-white p-3 rounded-lg inline-block mb-4 font-mono text-sm">
        Akumulasi Biaya: Rp{{ number_format($totalPengeluaran, 0, ',', '.') }}
    </div>

    <div class="bg-card border border-paperline rounded-lg overflow-hidden">
        <table class="w-full text-xs text-left">
            <thead>
                <tr class="bg-paper font-mono uppercase text-muted border-b">
                    <th class="p-2.5">Tanggal</th>
                    <th class="p-2.5">Kategori</th>
                    <th class="p-2.5">Deskripsi</th>
                    <th class="p-2.5">Nominal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($daftarPengeluaran as $dp)
                <tr class="border-b border-paperline">
                    <td class="p-2.5 font-mono">{{ $dp->tanggal }}</td>
                    <td class="p-2.5 font-bold text-green-deep">{{ $dp->kategori }}</td>
                    <td class="p-2.5">{{ $dp->deskripsi }}</td>
                    <td class="p-2.5 font-mono font-bold">Rp{{ number_format($dp->nominal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div x-show="bukaModal" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" x-cloak>
        <div class="bg-card border border-paperline rounded-lg w-full max-w-sm p-5" @click.outside="bukaModal = false">
            <h3 class="font-display font-bold text-sm mb-3">Catat Beban Operasional</h3>
            <form action="{{ route('operasional.simpan') }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-xs text-muted mb-1">Tanggal</label>
                    <input type="date" name="tanggal" required value="{{ date('Y-m-d') }}" class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <div>
                    <label class="block text-xs text-muted mb-1">Kategori</label>
                    <select name="kategori" class="w-full border rounded p-2 text-xs bg-white">
                        <option value="Listrik & Air">Listrik & Air</option>
                        <option value="Gas LPG">Gas LPG</option>
                        <option value="Logistik Dapur">Logistik Dapur</option>
                        <option value="Gaji Kru">Gaji Kru</option>
                        <option value="Lain-lain">Lain-lain</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-muted mb-1">Deskripsi</label>
                    <input type="text" name="deskripsi" class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <div>
                    <label class="block text-xs text-muted mb-1">Nominal Biaya (Rp)</label>
                    <input type="number" name="nominal" required class="w-full border rounded p-2 text-xs bg-white">
                </div>
                <button type="submit" class="w-full bg-green text-white py-2 rounded text-xs font-bold">Simpan Pengeluaran</button>
            </form>
        </div>
    </div>
</div>
@endsection