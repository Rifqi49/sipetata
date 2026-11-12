@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
        <input type="text" name="nama_kategori" maxlength="50"
               value="{{ old('nama_kategori', $kategori->nama_kategori ?? '') }}"
               class="mt-1 w-full border-gray-300 rounded shadow-sm" required>
        @error('nama_kategori') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Warna Marker</label>
        <div class="mt-1 flex items-center gap-3">
            <input type="color" name="warna_marker"
                   value="{{ old('warna_marker', $kategori->warna_marker ?? '#27ae60') }}"
                   class="h-10 w-16 border border-gray-300 rounded cursor-pointer">
            <span class="text-xs text-gray-500">Dipakai untuk warna titik di peta dan badge kategori.</span>
        </div>
        @error('warna_marker') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Icon Marker (opsional)</label>
        <input type="text" name="icon_marker" maxlength="100"
               value="{{ old('icon_marker', $kategori->icon_marker ?? '') }}"
               placeholder="Contoh: tree, mosque, building"
               class="mt-1 w-full border-gray-300 rounded shadow-sm">
        @error('icon_marker') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="3"
                  class="mt-1 w-full border-gray-300 rounded shadow-sm"
                  placeholder="Penjelasan singkat tentang kategori ini">{{ old('deskripsi', $kategori->deskripsi ?? '') }}</textarea>
        @error('deskripsi') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.kategori.index') }}"
       class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
    <button class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">
        Simpan
    </button>
</div>
