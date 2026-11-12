@extends('layouts.admin')

@section('title', 'Kategori Wisata')

@section('actions')
    <a href="{{ route('admin.kategori.create') }}"
       class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow">
        + Tambah Kategori
    </a>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="min-w-full text-sm">
        <thead class="bg-gray-100 text-left">
            <tr>
                <th class="px-4 py-2">No</th>
                <th class="px-4 py-2">Nama Kategori</th>
                <th class="px-4 py-2">Warna Marker</th>
                <th class="px-4 py-2">Deskripsi</th>
                <th class="px-4 py-2">Jumlah Wisata</th>
                <th class="px-4 py-2 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kategori as $i => $k)
                <tr class="border-t hover:bg-gray-50">
                    <td class="px-4 py-2">{{ $kategori->firstItem() + $i }}</td>
                    <td class="px-4 py-2 font-medium">{{ $k->nama_kategori }}</td>
                    <td class="px-4 py-2">
                        <span class="inline-flex items-center gap-2">
                            <span class="inline-block w-4 h-4 rounded-full border border-gray-200"
                                  style="background: {{ $k->warna_marker }}"></span>
                            <code class="text-xs text-gray-600">{{ $k->warna_marker }}</code>
                        </span>
                    </td>
                    <td class="px-4 py-2 text-gray-600">
                        {{ \Illuminate\Support\Str::limit($k->deskripsi ?: '-', 70) }}
                    </td>
                    <td class="px-4 py-2">{{ $k->wisata_count }}</td>
                    <td class="px-4 py-2 text-right space-x-1">
                        <a href="{{ route('admin.kategori.edit', $k) }}"
                           class="text-blue-600 hover:underline">Edit</a>

                        @if($k->wisata_count > 0)
                            <span class="text-gray-400 text-xs cursor-not-allowed"
                                  title="Masih dipakai oleh {{ $k->wisata_count }} data wisata">
                                Hapus
                            </span>
                        @else
                            <form action="{{ route('admin.kategori.destroy', $k) }}"
                                  method="POST" class="inline"
                                  onsubmit="return confirm('Yakin hapus kategori ini?')">
                                @csrf @method('DELETE')
                                <button class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                        Belum ada kategori wisata.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="p-4">{{ $kategori->links() }}</div>
</div>
@endsection
