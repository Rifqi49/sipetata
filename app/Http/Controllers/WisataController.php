<?php

namespace App\Http\Controllers;

use App\Http\Resources\WisataResource;
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
        $kategori      = Kategori::withCount('wisata')->orderBy('nama_kategori')->get();

        return view('welcome', compact('totalWisata', 'totalKategori', 'kategori'));
    }

    /**
     * Halaman utama peta wisata.
     */
    public function peta()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        return view('peta', compact('kategori'));
    }

    /**
     * API JSON untuk Leaflet.
     */
    public function apiIndex()
    {
        $wisata = Wisata::with(['kategori', 'desa'])->get();

        // toArray() menjaga bentuk respons tetap array polos seperti sebelumnya.
        return WisataResource::collection($wisata)->toArray(request());
    }
}