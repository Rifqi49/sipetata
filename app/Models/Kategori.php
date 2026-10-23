<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_kategori', 'deskripsi', 'warna_marker', 'icon_marker'])]
class Kategori extends Model
{
    protected $table = 'kategori';

    public function wisata(): HasMany
    {
        return $this->hasMany(Wisata::class);
    }
}