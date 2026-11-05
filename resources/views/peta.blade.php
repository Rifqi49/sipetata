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

    {{-- Tombol pintar: Dashboard bila sudah login, Login bila belum --}}
    @auth
        <a href="{{ route('admin.dashboard') }}"
           class="absolute top-4 right-4 z-[1000] bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow text-sm">
            Dashboard
        </a>
    @else
        <a href="{{ route('login') }}"
           class="absolute top-4 right-4 z-[1000] bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded shadow text-sm">
            Login
        </a>
    @endauth

    {{-- Lokasi saya + daftar destinasi terdekat --}}
    <div style="position:absolute;right:16px;bottom:96px;z-index:1000;display:flex;flex-direction:column;align-items:flex-end;gap:8px;width:288px;max-width:calc(100% - 32px)">
        <div id="panelTerdekat" style="display:none;width:100%;background:#fff;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.18);overflow:hidden">
            <div style="padding:8px 12px;background:#ecfdf5;border-bottom:1px solid #d1fae5">
                <p style="margin:0;font-size:13px;font-weight:600;color:#065f46">Destinasi Terdekat</p>
                <p id="infoLokasi" style="margin:2px 0 0;font-size:11px;color:#6b7280">Berdasarkan lokasi Anda</p>
            </div>
            <ul id="daftarTerdekat" style="list-style:none;margin:0;padding:0;max-height:264px;overflow-y:auto"></ul>
        </div>

        <button type="button" id="btnLokasi"
                style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;background:#fff;color:#047857;font-size:13px;font-weight:600;padding:10px 16px;border-radius:10px;border:1px solid #d1fae5;box-shadow:0 4px 16px rgba(0,0,0,.18);cursor:pointer">
            <span aria-hidden="true">📍</span>
            <span id="labelLokasi">Lokasi Saya</span>
        </button>
    </div>

    {{-- Info rute perjalanan --}}
    <div id="panelRute"
         style="display:none;position:absolute;left:50%;bottom:24px;transform:translateX(-50%);z-index:1000;background:#fff;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.2);padding:8px 14px;align-items:center;gap:12px;max-width:calc(100% - 32px)">
        <div style="min-width:0">
            <p id="infoRuteJarak" style="margin:0;font-size:13px;font-weight:600;color:#047857">Menghitung rute…</p>
            <p id="infoRuteWaktu" style="margin:0;font-size:11px;color:#6b7280"></p>
        </div>
        <button type="button" id="btnTutupRute"
                style="border:0;background:transparent;color:#dc2626;font-size:11px;cursor:pointer;white-space:nowrap">
            Tutup
        </button>
    </div>

    <div id="map"></div>

    @include('partials.wilayah-tanah-bumbu')

    <script>
        var map = L.map('map', { zoomControl: false }).setView([-3.45, 115.7], 9);

        // Zoom di kanan bawah agar tidak menutupi kartu SIPETATA di kiri atas.
        L.control.zoom({ position: 'bottomright' }).addTo(map);

        // Pane rute: di atas batas wilayah (390) tetapi di bawah marker (400).
        map.createPane('rutePerjalanan');
        map.getPane('rutePerjalanan').style.zIndex = 398;

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        // Tampilkan batas Kabupaten Tanah Bumbu (sekaligus fit ke wilayahnya).
        muatWilayahTanahBumbu(map);

        var semuaMarker = [];

        // ====== Lokasi pengguna ======
        var lokasiSaya = null;
        var markerLokasi = null;
        var lingkaranAkurasi = null;
        var layerRute = null;

        var btnLokasi = document.getElementById('btnLokasi');
        var labelLokasi = document.getElementById('labelLokasi');
        var panelTerdekat = document.getElementById('panelTerdekat');
        var infoLokasi = document.getElementById('infoLokasi');
        var daftarTerdekat = document.getElementById('daftarTerdekat');
        var panelRute = document.getElementById('panelRute');
        var infoRuteJarak = document.getElementById('infoRuteJarak');
        var infoRuteWaktu = document.getElementById('infoRuteWaktu');

        function jarakKm(lat1, lon1, lat2, lon2) {
            var R = 6371;
            var dLat = (lat2 - lat1) * Math.PI / 180;
            var dLon = (lon2 - lon1) * Math.PI / 180;
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2)
                + Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180)
                * Math.sin(dLon / 2) * Math.sin(dLon / 2);

            return 2 * R * Math.asin(Math.sqrt(a));
        }

        function formatJarak(km) {
            if (km < 1) {
                return Math.round(km * 1000) + ' m';
            }
            return km.toFixed(1).replace('.', ',') + ' km';
        }

        btnLokasi.addEventListener('click', function () {
            if (!navigator.geolocation) {
                infoLokasi.textContent = 'Browser tidak mendukung geolokasi.';
                panelTerdekat.style.display = 'block';
                return;
            }

            btnLokasi.disabled = true;
            labelLokasi.textContent = 'Mencari lokasi…';

            navigator.geolocation.getCurrentPosition(
                function (pos) {
                    btnLokasi.disabled = false;
                    labelLokasi.textContent = 'Perbarui Lokasi';
                    tampilkanLokasi(pos.coords.latitude, pos.coords.longitude, pos.coords.accuracy || 0);
                    susunDestinasiTerdekat();
                },
                function (err) {
                    btnLokasi.disabled = false;
                    labelLokasi.textContent = 'Lokasi Saya';
                    panelTerdekat.style.display = 'block';
                    infoLokasi.textContent = err.code === 1
                        ? 'Izin lokasi ditolak. Aktifkan izin lokasi di browser.'
                        : 'Lokasi tidak tersedia. Coba lagi.';
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 60000 }
            );
        });

        function tampilkanLokasi(lat, lng, akurasi) {
            lokasiSaya = { lat: lat, lng: lng };

            if (markerLokasi) {
                markerLokasi.setLatLng([lat, lng]);
                lingkaranAkurasi.setLatLng([lat, lng]).setRadius(akurasi);
            } else {
                lingkaranAkurasi = L.circle([lat, lng], {
                    radius: akurasi,
                    color: '#2563eb',
                    weight: 1,
                    opacity: 0.35,
                    fillColor: '#3b82f6',
                    fillOpacity: 0.10,
                    interactive: false
                }).addTo(map);

                markerLokasi = L.circleMarker([lat, lng], {
                    radius: 7,
                    color: '#fff',
                    weight: 2,
                    opacity: 1,
                    fillColor: '#2563eb',
                    fillOpacity: 1
                }).addTo(map).bindPopup('Lokasi Anda');
            }

            infoLokasi.textContent = 'Lokasi terdeteksi'
                + (akurasi ? ' (±' + Math.round(akurasi) + ' m)' : '');
            panelTerdekat.style.display = 'block';

            map.flyTo([lat, lng], 12);
        }

        function susunDestinasiTerdekat() {
            if (!lokasiSaya) {
                return;
            }

            var daftar = semuaMarker.map(function (m) {
                return {
                    m: m,
                    jarak: jarakKm(lokasiSaya.lat, lokasiSaya.lng, m.data.latitude, m.data.longitude)
                };
            }).sort(function (a, b) {
                return a.jarak - b.jarak;
            });

            daftarTerdekat.innerHTML = '';

            daftar.forEach(function (d) {
                var li = document.createElement('li');
                li.style.cssText = 'display:flex;align-items:center;justify-content:space-between;gap:8px;'
                    + 'padding:8px 12px;border-top:1px solid #f3f4f6;cursor:pointer;font-size:12px';

                li.innerHTML = '<span style="display:flex;align-items:center;gap:8px;min-width:0">'
                    + '<span style="width:8px;height:8px;border-radius:50%;flex:none;background:' + d.m.data.kategori.warna + '"></span>'
                    + '<span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;color:#374151">' + d.m.data.nama_wisata + '</span>'
                    + '</span>'
                    + '<span style="color:#6b7280;white-space:nowrap;font-size:11px">' + formatJarak(d.jarak) + '</span>';

                li.addEventListener('mouseenter', function () { li.style.background = '#ecfdf5'; });
                li.addEventListener('mouseleave', function () { li.style.background = 'transparent'; });
                li.addEventListener('click', function () {
                    map.flyTo([d.m.data.latitude, d.m.data.longitude], 15);
                    d.m.marker.openPopup();
                });

                daftarTerdekat.appendChild(li);
            });
        }

        // ====== Popup destinasi (jarak + tombol rute) ======
        function buatPopup(item) {
            var jarakTeks = '';
            var tombolRute = '';

            if (lokasiSaya) {
                var km = jarakKm(lokasiSaya.lat, lokasiSaya.lng, item.latitude, item.longitude);
                jarakTeks = '<p style="margin:6px 0 0;font-size:12px;color:#047857">📏 '
                    + formatJarak(km) + ' (garis lurus) dari lokasi Anda</p>';
                tombolRute = '<button type="button" class="btn-rute" '
                    + 'data-lat="' + item.latitude + '" data-lng="' + item.longitude + '" '
                    + 'style="margin-top:8px;width:100%;background:#059669;color:#fff;border:0;'
                    + 'padding:6px 10px;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer">'
                    + '🛣️ Rute dari lokasi saya</button>';
            }

            return `
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
                    ${jarakTeks}
                    ${tombolRute}
                </div>
            `;
        }

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

                    marker.bindPopup(function () {
                        return buatPopup(item);
                    });

                    marker.addTo(map);

                    semuaMarker.push({
                        marker: marker,
                        nama: item.nama_wisata.toLowerCase(),
                        kategori: item.kategori.nama,
                        data: item
                    });
                });
            });

        map.on('popupopen', function (e) {
            var tombol = e.popup.getElement().querySelector('.btn-rute');
            if (!tombol) {
                return;
            }

            tombol.addEventListener('click', function () {
                hitungRute(parseFloat(tombol.dataset.lat), parseFloat(tombol.dataset.lng));
            });
        });

        // ====== Rute perjalanan (OSRM) ======
        function bersihkanRute() {
            if (layerRute) {
                map.removeLayer(layerRute);
                layerRute = null;
            }
            panelRute.style.display = 'none';
        }

        function tampilkanInfoRute(judul, detail) {
            panelRute.style.display = 'flex';
            infoRuteJarak.textContent = judul;
            infoRuteWaktu.textContent = detail || '';
        }

        function hitungRute(lat, lng) {
            if (!lokasiSaya) {
                return;
            }

            bersihkanRute();
            tampilkanInfoRute('Menghitung rute…', '');

            var url = 'https://router.project-osrm.org/route/v1/driving/'
                + lokasiSaya.lng + ',' + lokasiSaya.lat + ';' + lng + ',' + lat
                + '?overview=full&geometries=geojson';

            fetch(url)
                .then(function (res) {
                    if (!res.ok) {
                        throw new Error('HTTP ' + res.status);
                    }
                    return res.json();
                })
                .then(function (data) {
                    if (!data.routes || !data.routes.length) {
                        throw new Error('Rute tidak ditemukan');
                    }

                    var rute = data.routes[0];

                    layerRute = L.geoJSON(rute.geometry, {
                        pane: 'rutePerjalanan',
                        style: {
                            color: '#2563eb',
                            weight: 5,
                            opacity: 0.85,
                            lineCap: 'round'
                        }
                    }).addTo(map);

                    map.fitBounds(layerRute.getBounds(), { padding: [40, 40] });

                    var km = rute.distance / 1000;
                    var menit = Math.max(1, Math.round(rute.duration / 60));

                    tampilkanInfoRute('Rute berkendara: ' + formatJarak(km), '± ' + menit + ' menit');
                })
                .catch(function () {
                    tampilkanInfoRute('Rute tidak tersedia', 'Periksa koneksi internet lalu coba lagi.');
                });
        }

        document.getElementById('btnTutupRute').addEventListener('click', bersihkanRute);

        // ====== Pencarian & filter ======
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
