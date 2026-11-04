<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Bentuk data wisata untuk peta (publik & admin).
 *
 * Field "pemilik" hanya dikirim ke halaman admin, supaya
 * payload peta publik tetap sama seperti sebelumnya.
 */
class WisataResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'nama_wisata' => $this->nama_wisata,
            'deskripsi'   => $this->deskripsi,
            'latitude'    => (float) $this->latitude,
            'longitude'   => (float) $this->longitude,
            'alamat'      => $this->alamat,
            'jam_buka'    => $this->jam_buka,
            'harga_tiket' => $this->harga_tiket,
            'fasilitas'   => $this->fasilitas,
            'foto_utama'  => $this->foto_utama,
            'kategori'    => [
                'nama'  => $this->kategori->nama_kategori,
                'warna' => $this->kategori->warna_marker,
            ],
            'desa' => $this->desa ? $this->desa->nama_desa : null,
            'pemilik' => $this->when(
                $request->routeIs('admin.*'),
                fn () => $this->user?->name
            ),
        ];
    }
}
