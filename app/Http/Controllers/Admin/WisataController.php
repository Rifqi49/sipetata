<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Wisata;
use App\Models\Kategori;
use App\Models\Desa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WisataController extends Controller
{
    /**
     * Query dasar: filter berdasarkan role.
     * - superadmin & admin: lihat semua wisata
     * - petugas: hanya lihat wisata miliknya
     */
    private function queryWisata()
    {
        $query = Wisata::with(['kategori', 'desa', 'user']);

        if (Auth::user()->role === 'petugas') {
            $query->where('user_id', Auth::id());
        }

        return $query;
    }

    public function index()
    {
        $wisata = $this->queryWisata()->latest()->paginate(10);
        return view('admin.wisata.index', compact('wisata'));
    }

    public function create()
    {
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $desa     = Desa::all();
        return view('admin.wisata.create', compact('kategori', 'desa'));
    }

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
            'foto_utama'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data['user_id'] = Auth::id();   // ← catat siapa yang input

         if ($request->hasFile('foto_utama')) {
            $data['foto_utama'] = $request->file('foto_utama')->store('wisata', 'public');
        }

        Wisata::create($data);

        return redirect()->route('admin.wisata.index')
                         ->with('success', 'Data wisata berhasil ditambahkan.');
    }

    public function show(Wisata $wisata)
    {
        $this->authorizeAkses($wisata);
        return redirect()->route('admin.wisata.edit', $wisata);
    }

    public function edit(Wisata $wisata)
    {
        $this->authorizeAkses($wisata);
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $desa     = Desa::all();
        return view('admin.wisata.edit', compact('wisata', 'kategori', 'desa'));
    }

    public function update(Request $request, Wisata $wisata)
    {
        $this->authorizeAkses($wisata);

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
            'foto_utama'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Proses upload foto baru sebelum data disimpan
        if ($request->hasFile('foto_utama')) {
            // Hapus foto lama jika ada
            if ($wisata->foto_utama) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($wisata->foto_utama);
            }
            // Simpan foto baru
            $data['foto_utama'] = $request->file('foto_utama')->store('wisata', 'public');
        } else {
            // Jangan timpa foto lama jika tidak ada upload baru
            unset($data['foto_utama']);
        }

        $wisata->update($data);

        return redirect()->route('admin.wisata.index')
                         ->with('success', 'Data wisata berhasil diperbarui.');
    }

    public function destroy(Wisata $wisata)
    {
        $this->authorizeAkses($wisata);
        
        // Hapus file foto
        if ($wisata->foto_utama) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($wisata->foto_utama);
        }
        
        $wisata->delete();

        return redirect()->route('admin.wisata.index')
                         ->with('success', 'Data wisata berhasil dihapus.');
    }

    /**
     * Cek apakah user boleh mengelola wisata ini.
     * Petugas hanya boleh untuk wisata miliknya.
     */
    private function authorizeAkses(Wisata $wisata): void
    {
        if (Auth::user()->role === 'petugas' && $wisata->user_id !== Auth::id()) {
            abort(403, 'Anda tidak berhak mengelola data wisata ini.');
        }
    }
}