<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPETATA — Sistem Pemetaan Wisata Tanah Bumbu</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        #map { height: 100vh; width: 100%; }
        .leaflet-popup-content { font-family: system-ui, sans-serif; }
    </style>
</head>
<body class="m-0">

    {{-- Header / Brand --}}
    <div class="absolute top-4 left-4 z-[1000] bg-white rounded-lg shadow-lg px-4 py-2">
        <a href="{{ route('home') }}" class="block hover:opacity-80">
            <h1 class="text-lg font-bold text-emerald-700 leading-tight">SIPETATA</h1>
            <p class="text-xs text-gray-500">← Kembali ke Beranda</p>
        </a>
    </div>

    {{-- Search & Filter --}}
    <div class="absolute top-4 left-1/2 -translate-x-1/2 z-[1000] w-11/12 max-w-md bg-white rounded-lg shadow-lg p-3 space-y-2">
        <input type="text" id="searchInput" placeholder="Cari wisata..."
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
        <select id="filterKategori"
                class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            <option value="">Semua Kategori</option>
            @foreach($kategori as $k)
                <option value="{{ $k->nama_kategori }}">{{ $k->nama_kategori }}</option>
            @endforeach
        </select>
    </div>

    {{-- Tombol admin --}}
    <a href="{{ route('login') }}"
       class="absolute top-4 right-4 z-[1000] bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow text-sm">
        Login Admin
    </a>

    <div id="map"></div>

    <script>
        var map = L.map('map').setView([-3.45, 115.7], 9);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var semuaMarker = [];

        fetch('{{ route("api.wisata") }}')
            .then(res => res.json())
            .then(data => {
                data.forEach(function(item) {
                    var marker = L.circleMarker([item.latitude, item.longitude], {
                        radius: 8,
                        fillColor: item.kategori.warna,
                        color: '#fff',
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.9
                    });

                    var popup = `
                        <div style="min-width:200px">
                            <h3 style="margin:0 0 4px;font-size:14px;font-weight:bold">${item.nama_wisata}</h3>
                            <span style="background:${item.kategori.warna};color:#fff;padding:2px 8px;border-radius:4px;font-size:11px">
                                ${item.kategori.nama}
                            </span>
                            ${item.desa ? `<p style="margin:6px 0 0;font-size:12px;color:#666">📍 ${item.desa}</p>` : ''}
                            <p style="margin:6px 0 0;font-size:12px">${item.deskripsi ?? 'Belum ada deskripsi.'}</p>
                            ${item.jam_buka ? `<p style="margin:4px 0 0;font-size:12px">🕐 ${item.jam_buka}</p>` : ''}
                            ${item.harga_tiket ? `<p style="margin:4px 0 0;font-size:12px">🎫 Rp ${parseInt(item.harga_tiket).toLocaleString('id-ID')}</p>` : ''}
                            ${item.fasilitas ? `<p style="margin:4px 0 0;font-size:12px">✨ ${item.fasilitas}</p>` : ''}
                        </div>
                    `;

                    marker.bindPopup(popup);
                    marker.addTo(map);

                    semuaMarker.push({
                        marker: marker,
                        nama: item.nama_wisata.toLowerCase(),
                        kategori: item.kategori.nama,
                        data: item
                    });
                });
            });

        function applyFilter() {
            var keyword = document.getElementById('searchInput').value.toLowerCase();
            var kategori = document.getElementById('filterKategori').value;

            semuaMarker.forEach(function(m) {
                var cocokNama = m.nama.includes(keyword);
                var cocokKategori = kategori === '' || m.kategori === kategori;

                if (cocokNama && cocokKategori) {
                    if (!map.hasLayer(m.marker)) map.addLayer(m.marker);
                } else {
                    if (map.hasLayer(m.marker)) map.removeLayer(m.marker);
                }
            });
        }

        document.getElementById('searchInput').addEventListener('input', applyFilter);
        document.getElementById('filterKategori').addEventListener('change', applyFilter);
    </script>
</body>
</html>