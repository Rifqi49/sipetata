<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id', 'nama_wisata', 'deskripsi', 'latitude', 'longitude',
    'alamat', 'kategori_id', 'desa_id', 'foto_utama',
    'jam_buka', 'harga_tiket', 'fasilitas',
])]
class Wisata extends Model
{
    protected $table = 'wisata';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }
}