@extends('layouts.admin')

@section('title', 'Peta Wisata')
@section('actions')
    <a href="{{ route('peta') }}" target="_blank"
       class="bg-white border border-emerald-600 text-emerald-700 hover:bg-emerald-50 px-4 py-2 rounded shadow-sm text-sm">
        Buka Peta Publik ↗
    </a>
@endsection

@section('content')
<div class="relative bg-white rounded-lg shadow overflow-hidden">
    {{-- Pencarian & filter kategori --}}
    <div class="absolute top-4 left-4 z-[1000] w-72 max-w-[calc(100%-9rem)] bg-white rounded-lg shadow-lg p-3 space-y-2">
        <input type="text" id="adminSearch" placeholder="Cari wisata..."
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <select id="adminFilterKategori"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
    </div>

    {{-- Info cakupan data sesuai role --}}
    <div class="absolute top-4 right-4 z-[1000] bg-white/95 backdrop-blur rounded-lg shadow px-3 py-2 text-xs text-gray-600">
        <p class="font-semibold text-gray-800">
            {{ auth()->user()->role === 'petugas' ? 'Wisata milik Anda' : 'Seluruh Wisata' }}
        </p>
        <p><span id="adminMarkerCount">0</span> destinasi ditampilkan</p>
    </div>

    <div id="adminMap" class="h-[calc(100vh-7rem)] min-h-[480px] w-full"></div>
</div>

@include('partials.wilayah-tanah-bumbu')
@endsection

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var adminMap = L.map('adminMap', { zoomControl: false }).setView([-3.45, 115.7], 9);

        L.control.zoom({ position: 'bottomright' }).addTo(adminMap);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(adminMap);

        // Batas wilayah Kabupaten Tanah Bumbu (sekaligus fit ke wilayahnya).
        var wilayah = muatWilayahTanahBumbu(adminMap);

        var adminMarkers = [];

        function updateAdminCount() {
            var tampil = adminMarkers.filter(function (m) { return adminMap.hasLayer(m.marker); }).length;
            document.getElementById('adminMarkerCount').textContent = tampil;
        }

        function adminApplyFilter() {
            var keyword = document.getElementById('adminSearch').value.toLowerCase();
            var kategori = document.getElementById('adminFilterKategori').value;

            adminMarkers.forEach(function (m) {
                var cocokNama = m.nama.includes(keyword);
                var cocokKategori = kategori === '' || m.kategoriId === kategori;

                if (cocokNama && cocokKategori) {
                    if (!adminMap.hasLayer(m.marker)) adminMap.addLayer(m.marker);
                } else {
                    if (adminMap.hasLayer(m.marker)) adminMap.removeLayer(m.marker);
                }
            });

            updateAdminCount();
        }

        fetch('{{ route("admin.api.wisata") }}')
            .then(function (res) { return res.json(); })
            .then(function (data) {
                var bounds = [];

                data.forEach(function (item) {
                    var marker = L.circleMarker([item.latitude, item.longitude], {
                        radius: 9,
                        fillColor: item.kategori.warna,
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.95
                    });

                    var popup = `
                        <div style="min-width:220px">
                            ${item.foto_utama ? `<img src="/storage/${item.foto_utama}" alt="${item.nama_wisata}" style="width:100%;height:130px;object-fit:cover;border-radius:6px;margin-bottom:8px">` : ''}
                            <h3 style="margin:0 0 4px;font-size:14px;font-weight:bold">${item.nama_wisata}</h3>
                            <span style="background:${item.kategori.warna};color:#fff;padding:2px 8px;border-radius:4px;font-size:11px">
                                ${item.kategori.nama}
                            </span>
                            ${item.desa ? `<p style="margin:6px 0 0;font-size:12px;color:#666">📍 ${item.desa}</p>` : ''}
                            <p style="margin:6px 0 0;font-size:12px">${item.deskripsi ?? 'Belum ada deskripsi.'}</p>
                            ${item.jam_buka ? `<p style="margin:4px 0 0;font-size:12px">🕐 ${item.jam_buka}</p>` : ''}
                            ${item.harga_tiket ? `<p style="margin:4px 0 0;font-size:12px">🎫 Rp ${parseInt(item.harga_tiket).toLocaleString('id-ID')}</p>` : ''}
                            ${item.fasilitas ? `<p style="margin:4px 0 0;font-size:12px">✨ ${item.fasilitas}</p>` : ''}
                            ${item.pemilik ? `<p style="margin:8px 0 0;font-size:12px;color:#047857;border-top:1px solid #e5e7eb;padding-top:6px">👤 Petugas: ${item.pemilik}</p>` : ''}
                        </div>
                    `;

                    marker.bindPopup(popup);
                    marker.addTo(adminMap);

                    bounds.push([item.latitude, item.longitude]);

                    adminMarkers.push({
                        marker: marker,
                        nama: item.nama_wisata.toLowerCase(),
                        kategori: item.kategori.nama,
                        kategoriId: String(item.kategori.id)
                    });
                });

                if (bounds.length) {
                    // Fallback bila batas wilayah gagal dimuat.
                    wilayah.then(function (layer) {
                        if (! layer) {
                            adminMap.fitBounds(bounds, { padding: [40, 40] });
                        }
                    });
                }

                updateAdminCount();
            });

        document.getElementById('adminSearch').addEventListener('input', adminApplyFilter);
        document.getElementById('adminFilterKategori').addEventListener('change', adminApplyFilter);
    </script>
@endpush
