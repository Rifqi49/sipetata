<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Kategori;
use App\Models\Desa;
use Illuminate\Http\Request;

class WisataController extends Controller
{
    /**
     * Daftar semua wisata (dengan pagination).
     */
    public function index()
    {
        $wisata = Wisata::with(['kategori', 'desa'])->latest()->paginate(10);
        return view('admin.wisata.index', compact('wisata'));
    }

    /**
     * Form tambah wisata.
     */
    public function create()
    {
        $kategori = Kategori::all();
        $desa     = Desa::all();
        return view('admin.wisata.create', compact('kategori', 'desa'));
    }

    /**
     * Simpan wisata baru.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_wisata' => 'required|string|max:150|unique:wisata,nama_wisata',
            'kategori_id' => 'required|exists:kategori,id',
            'desa_id'     => 'nullable|exists:desa,id',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'deskripsi'   => 'nullable|string',
            'alamat'      => 'nullable|string|max:255',
            'jam_buka'    => 'nullable|string|max:50',
            'harga_tiket' => 'nullable|numeric|min:0',
            'fasilitas'   => 'nullable|string',
        ]);

        Wisata::create($data);

        return redirect()->route('admin.wisata.index')
                         ->with('success', 'Data wisata berhasil ditambahkan.');
    }

    /**
     * Detail wisata (tidak dipakai di panel admin, tapi wajib ada
     * karena Route::resource mendaftarkan method show).
     */
    public function show(Wisata $wisata)
    {
        return redirect()->route('admin.wisata.edit', $wisata);
    }

    /**
     * Form edit wisata.
     */
    public function edit(Wisata $wisata)
    {
        $kategori = Kategori::all();
        $desa     = Desa::all();
        return view('admin.wisata.edit', compact('wisata', 'kategori', 'desa'));
    }

    /**
     * Update wisata.
     */
    public function update(Request $request, Wisata $wisata)
    {
        $data = $request->validate([
            'nama_wisata' => 'required|string|max:150|unique:wisata,nama_wisata,' . $wisata->id,
            'kategori_id' => 'required|exists:kategori,id',
            'desa_id'     => 'nullable|exists:desa,id',
            'latitude'    => 'required|numeric|between:-90,90',
            'longitude'   => 'required|numeric|between:-180,180',
            'deskripsi'   => 'nullable|string',
            'alamat'      => 'nullable|string|max:255',
            'jam_buka'    => 'nullable|string|max:50',
            'harga_tiket' => 'nullable|numeric|min:0',
            'fasilitas'   => 'nullable|string',
        ]);

        $wisata->update($data);

        return redirect()->route('admin.wisata.index')
                         ->with('success', 'Data wisata berhasil diperbarui.');
    }

    /**
     * Hapus wisata.
     */
    public function destroy(Wisata $wisata)
    {
        $wisata->delete();

        return redirect()->route('admin.wisata.index')
                         ->with('success', 'Data wisata berhasil dihapus.');
    }
}