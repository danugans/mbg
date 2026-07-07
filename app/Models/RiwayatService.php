<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiwayatService extends Model
{
    protected $table = 'riwayat_service';
    protected $fillable = ['barang_id', 'tanggal', 'biaya', 'vendor', 'catatan'];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }
}