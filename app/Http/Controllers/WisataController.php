<?php

namespace App\Http\Controllers;

use App\Models\Wisata;
use App\Models\Kategori;

class WisataController extends Controller
{
    /**
     * Halaman depan / landing page SIPETATA.
     */
    public function home()
    {
        $totalWisata   = Wisata::count();
        $totalKategori = Kategori::count();
        $kategori      = Kategori::withCount('wisata')->get();

        return view('welcome', compact('totalWisata', 'totalKategori', 'kategori'));
    }

    /**
     * Halaman utama peta wisata.
     */
    public function peta()
    {
        $kategori = Kategori::all();
        return view('peta', compact('kategori'));
    }

    /**
     * API JSON untuk Leaflet.
     */
    public function apiIndex()
    {
        $wisata = Wisata::with(['kategori', 'desa'])->get();

        return response()->json($wisata->map(function ($w) {
            return [
                'id'          => $w->id,
                'nama_wisata' => $w->nama_wisata,
                'deskripsi'   => $w->deskripsi,
                'latitude'    => (float) $w->latitude,
                'longitude'   => (float) $w->longitude,
                'alamat'      => $w->alamat,
                'jam_buka'    => $w->jam_buka,
                'harga_tiket' => $w->harga_tiket,
                'fasilitas'   => $w->fasilitas,
                'foto_utama'  => $w->foto_utama,
                'kategori'    => [
                    'nama'  => $w->kategori->nama_kategori,
                    'warna' => $w->kategori->warna_marker,
                ],
                'desa' => $w->desa ? $w->desa->nama_desa : null,
            ];
        }));
    }
}