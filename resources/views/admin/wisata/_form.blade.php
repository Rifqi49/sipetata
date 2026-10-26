@csrf

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Nama Wisata</label>
        <input type="text" name="nama_wisata"
               value="{{ old('nama_wisata', $wisata->nama_wisata ?? '') }}"
               class="mt-1 w-full border-gray-300 rounded shadow-sm" required>
        @error('nama_wisata') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Kategori</label>
        <select name="kategori_id" class="mt-1 w-full border-gray-300 rounded shadow-sm" required>
            <option value="">— Pilih —</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}"
                    @selected(old('kategori_id', $wisata->kategori_id ?? '') == $k->id)>
                    {{ $k->nama_kategori }}
                </option>
            @endforeach
        </select>
        @error('kategori_id') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Desa (opsional)</label>
        <select name="desa_id" class="mt-1 w-full border-gray-300 rounded shadow-sm">
            <option value="">— Pilih —</option>
            @foreach($desa as $d)
                <option value="{{ $d->id }}"
                    @selected(old('desa_id', $wisata->desa_id ?? '') == $d->id)>
                    {{ $d->nama_desa }} ({{ $d->kecamatan }})
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Latitude</label>
        <input type="text" name="latitude"
               value="{{ old('latitude', $wisata->latitude ?? '') }}"
               placeholder="-3.8000"
               class="mt-1 w-full border-gray-300 rounded shadow-sm" required>
        @error('latitude') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Longitude</label>
        <input type="text" name="longitude"
               value="{{ old('longitude', $wisata->longitude ?? '') }}"
               placeholder="115.5000"
               class="mt-1 w-full border-gray-300 rounded shadow-sm" required>
        @error('longitude') <p class="text-red-600 text-sm">{{ $message }}</p> @enderror
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
        <textarea name="deskripsi" rows="4"
                  class="mt-1 w-full border-gray-300 rounded shadow-sm">{{ old('deskripsi', $wisata->deskripsi ?? '') }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Alamat</label>
        <input type="text" name="alamat"
               value="{{ old('alamat', $wisata->alamat ?? '') }}"
               class="mt-1 w-full border-gray-300 rounded shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Jam Buka</label>
        <input type="text" name="jam_buka"
               value="{{ old('jam_buka', $wisata->jam_buka ?? '') }}"
               placeholder="08.00 - 17.00"
               class="mt-1 w-full border-gray-300 rounded shadow-sm">
    </div>

    <div>
        <label class="block text-sm font-medium text-gray-700">Harga Tiket</label>
        <input type="number" name="harga_tiket" step="0.01"
               value="{{ old('harga_tiket', $wisata->harga_tiket ?? '') }}"
               class="mt-1 w-full border-gray-300 rounded shadow-sm">
    </div>

    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
        <input type="text" name="fasilitas"
               value="{{ old('fasilitas', $wisata->fasilitas ?? '') }}"
               placeholder="Parkir, Toilet, Gazebo"
               class="mt-1 w-full border-gray-300 rounded shadow-sm">
    </div>
</div>

<div class="mt-6 flex justify-end gap-2">
    <a href="{{ route('admin.wisata.index') }}"
       class="px-4 py-2 bg-gray-200 rounded hover:bg-gray-300">Batal</a>
    <button class="px-4 py-2 bg-emerald-600 text-white rounded hover:bg-emerald-700">
        Simpan
    </button>
</div>