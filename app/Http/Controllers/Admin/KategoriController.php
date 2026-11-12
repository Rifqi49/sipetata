<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        $this->pastikanBolehKelola();

        $kategori = Kategori::withCount('wisata')
            ->orderBy('nama_kategori')
            ->paginate(10);

        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        $this->pastikanBolehKelola();

        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $this->pastikanBolehKelola();

        Kategori::create($this->validasi($request));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori wisata berhasil ditambahkan.');
    }

    public function edit(Kategori $kategori)
    {
        $this->pastikanBolehKelola();

        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, Kategori $kategori)
    {
        $this->pastikanBolehKelola();

        $kategori->update($this->validasi($request, $kategori));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori wisata berhasil diperbarui.');
    }

    public function destroy(Kategori $kategori)
    {
        $this->pastikanBolehKelola();

        $jumlahWisata = $kategori->wisata()->count();

        if ($jumlahWisata > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', "Kategori \"{$kategori->nama_kategori}\" tidak bisa dihapus karena masih dipakai oleh {$jumlahWisata} data wisata.");
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori wisata berhasil dihapus.');
    }

    /**
     * Hanya superadmin dan admin yang boleh mengelola kategori.
     * Petugas tetap bisa memilih kategori saat menginput wisata.
     */
    private function pastikanBolehKelola(): void
    {
        if (! in_array(auth()->user()->role, ['superadmin', 'admin'], true)) {
            abort(403, 'Hanya admin yang dapat mengelola kategori wisata.');
        }
    }

    private function validasi(Request $request, ?Kategori $kategori = null): array
    {
        return $request->validate([
            'nama_kategori' => [
                'required', 'string', 'max:50',
                Rule::unique('kategori', 'nama_kategori')->ignore($kategori?->id),
            ],
            'deskripsi'   => ['nullable', 'string'],
            'warna_marker' => ['required', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'icon_marker' => ['nullable', 'string', 'max:100'],
        ]);
    }
}
