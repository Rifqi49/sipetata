{{--
    Batas wilayah Kabupaten Tanah Bumbu.
    Sumber data: OpenStreetMap (Nominatim), lisensi ODbL 1.0.
    Dipakai bersama oleh peta publik dan peta admin.
--}}
<script>
    window.muatWilayahTanahBumbu = function (map) {
        var pane = 'wilayahTanahBumbu';

        if (! map.getPane(pane)) {
            map.createPane(pane);
            // Di bawah overlay marker (z-index 400) agar marker tetap terlihat.
            map.getPane(pane).style.zIndex = 390;
        }

        return fetch('{{ asset('geo/tanah-bumbu.geojson') }}')
            .then(function (res) {
                if (! res.ok) {
                    throw new Error('Batas wilayah gagal dimuat (' + res.status + ')');
                }
                return res.json();
            })
            .then(function (geojson) {
                var geometry = geojson.features[0].geometry;

                // Area di luar kabupaten dibuat lebih terang supaya wilayah tetap fokus.
                if (geometry.type === 'Polygon' && geometry.coordinates.length === 1) {
                    L.geoJSON({
                        type: 'Polygon',
                        coordinates: [
                            [[-180, -85], [180, -85], [180, 85], [-180, 85], [-180, -85]],
                            geometry.coordinates[0]
                        ]
                    }, {
                        pane: pane,
                        interactive: false,
                        style: {
                            stroke: false,
                            fillColor: '#ffffff',
                            fillOpacity: 0.55
                        }
                    }).addTo(map);
                }

                var batas = L.geoJSON(geojson, {
                    pane: pane,
                    interactive: false,
                    style: {
                        color: '#047857',
                        weight: 2,
                        opacity: 0.95,
                        dashArray: '6 4',
                        fillColor: '#10b981',
                        fillOpacity: 0.10
                    }
                }).addTo(map);

                map.fitBounds(batas.getBounds(), { padding: [24, 24] });

                var label = L.control({ position: 'bottomleft' });
                label.onAdd = function () {
                    var div = L.DomUtil.create('div');
                    div.innerHTML =
                        '<div style="background:rgba(255,255,255,.95);border-radius:8px;padding:6px 10px;' +
                        'box-shadow:0 1px 4px rgba(0,0,0,.25);font:12px/1.4 system-ui,sans-serif">' +
                        '<strong style="color:#047857">Kabupaten Tanah Bumbu</strong><br>' +
                        '<span style="color:#6b7280">Kalimantan Selatan</span><br>' +
                        '<span style="color:#9ca3af;font-size:10px">Batas: &copy; OpenStreetMap (ODbL)</span>' +
                        '</div>';
                    return div;
                };
                label.addTo(map);

                return batas;
            })
            .catch(function (err) {
                console.warn('Wilayah Tanah Bumbu:', err.message);
                return null;
            });
    };
</script>
