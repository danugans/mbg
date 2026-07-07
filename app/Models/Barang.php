<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Barang extends Model
{
    protected $table = 'barang';
    protected $fillable = ['nama_barang', 'jenis', 'kategori', 'satuan', 'interval_service_bulan'];

    public function mutasiStok(): HasMany
    {
        return $this->hasMany(MutasiStok::class, 'barang_id')->orderBy('tanggal', 'asc');
    }

    public function riwayatService(): HasMany
    {
        return $this->hasMany(RiwayatService::class, 'barang_id')->orderBy('tanggal', 'desc');
    }
}