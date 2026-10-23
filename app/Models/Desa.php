<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nama_desa', 'kecamatan', 'latitude', 'longitude', 'jarak_ke_ibukota'])]
class Desa extends Model
{
    protected $table = 'desa';

    public function wisata(): HasMany
    {
        return $this->hasMany(Wisata::class);
    }
}