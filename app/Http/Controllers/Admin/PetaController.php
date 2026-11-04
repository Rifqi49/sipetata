<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\WisataResource;
use App\Models\Kategori;
use App\Models\Wisata;
use Illuminate\Support\Facades\Auth;

class PetaController extends Controller
{
    /**
     * Halaman peta khusus admin/petugas (di dalam layout admin).
     */
    public function index()
    {
        $kategori = Kategori::all();

        return view('admin.peta', compact('kategori'));
    }

    /**
     * API JSON peta admin.
     * - superadmin & admin: semua wisata
     * - petugas: hanya wisata miliknya
     */
    public function apiIndex()
    {
        $query = Wisata::with(['kategori', 'desa', 'user']);

        if (Auth::user()->role === 'petugas') {
            $query->where('user_id', Auth::id());
        }

        // toArray() dipakai agar payload tetap berupa array polos
        // (bukan {"data": [...]}), sama seperti API peta publik.
        return WisataResource::collection($query->latest()->get())->toArray(request());
    }
}
