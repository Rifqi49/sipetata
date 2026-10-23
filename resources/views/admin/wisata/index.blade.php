@extends('layouts.admin')

@section('title', 'Data Wisata')
@section('actions')
    <a href="{{ route('admin.wisata.create') }}"
       class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow">
        + Tambah Wisata
    </a>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama</th>
                <th class="px-4 py-2">Kategori</th>
                <th class="px-4 py-2">Desa</th>
                <th class="px-4 py-2">Koordinat</th>
                <th class="px-4 py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wisata as $i => $w)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $wisata->firstItem() + $i }}</td>
                    <td class="px-4 py-2 font-medium">{{ $w->nama_wisata }}</td>
                    <td class="px-4 py-2">
                        <span class="px-2 py-1 text-xs rounded text-white"
                              style="background: {{ $w->kategori->warna_marker }}">
                            {{ $w->kategori->nama_kategori }}
                        </span>
                    </td>
                    <td class="px-4 py-2">{{ $w->desa->nama_desa ?? '-' }}</td>
                    <td class="px-4 py-2 text-gray-500">
                        {{ $w->latitude }}, {{ $w->longitude }}
                    </td>
                    <td class="px-4 py-2 text-right space-x-1">
                        <a href="{{ route('admin.wisata.edit', $w) }}"
                           class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('admin.wisata.destroy', $w) }}"
                              method="POST" class="inline"
                              onsubmit="return confirm('Yakin hapus data ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        Belum ada data wisata.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $wisata->links() }}</div>
</div>
@endsection