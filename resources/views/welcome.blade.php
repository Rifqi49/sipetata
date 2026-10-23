<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIPETATA — Sistem Pemetaan Wisata Tanah Bumbu</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-800 antialiased">

    {{-- ============ NAVBAR ============ --}}
    <nav class="bg-white shadow-sm">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <div>
                <h1 class="text-xl font-bold text-emerald-700 leading-tight">SIPETATA</h1>
                <p class="text-xs text-gray-500">Sistem Pemetaan Wisata Tanah Bumbu</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('login') }}"
                   class="text-sm text-gray-600 hover:text-emerald-700">
                    Login Admin
                </a>
                <a href="{{ route('peta') }}"
                   class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm px-4 py-2 rounded-lg shadow">
                    Lihat Peta
                </a>
            </div>
        </div>
    </nav>

    {{-- ============ HERO ============ --}}
    <section class="bg-gradient-to-br from-emerald-600 to-emerald-800 text-white">
        <div class="max-w-6xl mx-auto px-4 py-20 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-4">
                Jelajahi Wisata<br class="md:hidden"> Kabupaten Tanah Bumbu
            </h2>
            <p class="text-emerald-100 text-lg max-w-2xl mx-auto mb-8">
                Temukan destinasi wisata alam, bahari, budaya, dan buatan
                di Kabupaten Tanah Bumbu melalui peta interaktif.
            </p>
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="{{ route('peta') }}"
                   class="bg-white text-emerald-700 hover:bg-emerald-50 font-semibold px-6 py-3 rounded-lg shadow-lg">
                    🗺️ Buka Peta Wisata
                </a>
                <a href="#kategori"
                   class="bg-emerald-700 hover:bg-emerald-900 text-white font-semibold px-6 py-3 rounded-lg border border-emerald-500">
                    Lihat Kategori
                </a>
            </div>
        </div>
    </section>

    {{-- ============ STATISTIK ============ --}}
    <section class="max-w-6xl mx-auto px-4 -mt-10">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <p class="text-4xl font-bold text-emerald-700">{{ $totalWisata }}</p>
                <p class="text-sm text-gray-500 mt-1">Destinasi Wisata</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <p class="text-4xl font-bold text-emerald-700">{{ $totalKategori }}</p>
                <p class="text-sm text-gray-500 mt-1">Kategori Wisata</p>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <p class="text-4xl font-bold text-emerald-700">1</p>
                <p class="text-sm text-gray-500 mt-1">Kabupaten</p>
            </div>
        </div>
    </section>

    {{-- ============ KATEGORI ============ --}}
    <section id="kategori" class="max-w-6xl mx-auto px-4 py-16">
        <div class="text-center mb-10">
            <h3 class="text-2xl font-bold text-gray-800">Kategori Wisata</h3>
            <p class="text-gray-500 mt-1">Wisata Tanah Bumbu terbagi dalam beberapa kategori</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($kategori as $k)
                <a href="{{ route('peta') }}"
                   class="bg-white rounded-xl shadow hover:shadow-lg transition p-6 border-t-4"
                   style="border-top-color: {{ $k->warna_marker }}">
                    <div class="w-10 h-10 rounded-full mb-3 flex items-center justify-center text-white text-lg"
                         style="background: {{ $k->warna_marker }}">
                        📍
                    </div>
                    <h4 class="font-semibold text-gray-800">{{ $k->nama_kategori }}</h4>
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $k->wisata_count }} destinasi
                    </p>
                </a>
            @endforeach
        </div>
    </section>

    {{-- ============ TENTANG ============ --}}
    <section class="bg-emerald-50 border-t border-emerald-100">
        <div class="max-w-4xl mx-auto px-4 py-16 text-center">
            <h3 class="text-2xl font-bold text-gray-800 mb-3">Tentang SIPETATA</h3>
            <p class="text-gray-600 max-w-2xl mx-auto">
                SIPETATA adalah Sistem Pemetaan Wisata Kabupaten Tanah Bumbu berbasis web
                yang menampilkan lokasi destinasi wisata secara interaktif menggunakan
                peta digital. Dikembangkan untuk mendukung promosi pariwisata daerah
                dan memudahkan wisatawan menemukan destinasi.
            </p>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="bg-emerald-900 text-emerald-200">
        <div class="max-w-6xl mx-auto px-4 py-6 text-center text-sm">
            <p class="font-semibold text-white mb-1">SIPETATA</p>
            <p>Sistem Pemetaan Wisata Tanah Bumbu</p>
            <p class="mt-3 text-xs text-emerald-300">
                &copy; {{ date('Y') }} — Dikembangkan untuk Diskominfosp Kabupaten Tanah Bumbu
            </p>
        </div>
    </footer>

</body>
</html>