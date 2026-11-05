<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — SIPETATA</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <link rel="preload" as="image" href="{{ asset('images/login/pantai-siring-pagatan.jpg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Figtree, system-ui, -apple-system, sans-serif;
            color: #1f2937;
            background: #f8fafc;
        }

        .login-page { display: flex; min-height: 100vh; }

        /* ===== Panel galeri ===== */
        .login-media {
            position: relative;
            flex: 1 1 58%;
            min-height: 100vh;
            overflow: hidden;
            background: #064e3b;
        }

        .login-slide {
            position: absolute;
            inset: 0;
            background-size: cover;
            background-position: center;
            opacity: 0;
            transform: scale(1.03);
            transition: opacity .9s ease, transform 7s ease;
        }

        .login-slide.is-active {
            opacity: 1;
            transform: scale(1);
        }

        .login-media::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg,
                rgba(6, 78, 59, .05) 0%,
                rgba(6, 78, 59, .22) 45%,
                rgba(6, 78, 59, .72) 78%,
                rgba(4, 55, 42, .92) 100%);
            pointer-events: none;
            z-index: 1;
        }

        .media-logo {
            position: absolute;
            top: 24px;
            left: 28px;
            z-index: 3;
            display: inline-flex;
            align-items: center;
            background: #fff;
            border-radius: 12px;
            padding: 8px 14px;
            box-shadow: 0 6px 18px rgba(0, 0, 0, .22);
        }

        .media-logo img { height: 36px; width: auto; display: block; }

        .media-counter {
            position: absolute;
            top: 28px;
            right: 28px;
            z-index: 3;
            font-size: 12px;
            color: #d1fae5;
            background: rgba(4, 55, 42, .45);
            border: 1px solid rgba(209, 250, 229, .35);
            padding: 5px 10px;
            border-radius: 999px;
            letter-spacing: .04em;
        }

        .media-content {
            position: absolute;
            left: 36px;
            right: 36px;
            bottom: 74px;
            z-index: 3;
            color: #fff;
            max-width: 600px;
        }

        .media-kicker {
            margin: 0 0 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #6ee7b7;
        }

        .media-title {
            margin: 0 0 8px;
            font-size: clamp(24px, 3.2vw, 38px);
            line-height: 1.15;
            font-weight: 700;
            text-shadow: 0 2px 18px rgba(0, 0, 0, .35);
        }

        .media-desc {
            margin: 0 0 14px;
            font-size: 14px;
            line-height: 1.55;
            color: #e5f7ef;
            max-width: 520px;
        }

        .media-credit {
            margin: 0;
            font-size: 11px;
            color: rgba(255, 255, 255, .68);
        }

        .media-credit a { color: rgba(255, 255, 255, .85); text-decoration: underline; }

        .media-arrow {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 4;
            width: 42px;
            height: 42px;
            border-radius: 50%;
            border: 1px solid rgba(255, 255, 255, .35);
            background: rgba(4, 55, 42, .35);
            color: #fff;
            font-size: 22px;
            line-height: 1;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .15s ease;
        }

        .media-arrow:hover { background: rgba(4, 55, 42, .7); }
        .media-arrow.prev { left: 18px; }
        .media-arrow.next { right: 18px; }

        .media-dots {
            position: absolute;
            left: 0;
            right: 0;
            bottom: 30px;
            z-index: 4;
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .media-dot {
            width: 9px;
            height: 9px;
            padding: 0;
            border-radius: 50%;
            border: 0;
            background: rgba(255, 255, 255, .45);
            cursor: pointer;
            transition: background .15s ease, width .2s ease;
        }

        .media-dot.is-active {
            background: #fff;
            width: 26px;
            border-radius: 999px;
        }

        /* ===== Panel form ===== */
        .login-panel {
            flex: 1 1 42%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 40px;
            background: #f8fafc;
        }

        .login-card { width: 100%; max-width: 420px; }

        .login-eyebrow {
            margin: 0 0 10px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: #059669;
        }

        .login-title {
            margin: 0 0 8px;
            font-size: 29px;
            line-height: 1.2;
            font-weight: 700;
            color: #111827;
        }

        .login-sub {
            margin: 0 0 26px;
            font-size: 14px;
            line-height: 1.6;
            color: #6b7280;
        }

        .login-status {
            margin-bottom: 18px;
            padding: 10px 12px;
            border-radius: 10px;
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            font-size: 13px;
        }

        .login-field { margin-bottom: 16px; }

        .login-label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        .login-input {
            width: 100%;
            padding: 11px 13px;
            font-size: 14px;
            color: #111827;
            background: #fff;
            border: 1px solid #d1d5db;
            border-radius: 10px;
            outline: none;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .login-input::placeholder { color: #9ca3af; }

        .login-input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, .16);
        }

        .login-pass-wrap { position: relative; }

        .login-pass-wrap .login-input { padding-right: 96px; }

        .login-toggle {
            position: absolute;
            top: 50%;
            right: 8px;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: #059669;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            padding: 4px 8px;
            border-radius: 6px;
        }

        .login-toggle:hover { background: #ecfdf5; }

        .login-error {
            margin: 6px 0 0;
            font-size: 12px;
            color: #dc2626;
        }

        .login-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin: 4px 0 20px;
        }

        .login-remember {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            color: #4b5563;
            cursor: pointer;
        }

        .login-remember input {
            width: 16px;
            height: 16px;
            accent-color: #059669;
            cursor: pointer;
        }

        .login-link {
            font-size: 13px;
            color: #059669;
            text-decoration: none;
            font-weight: 500;
        }

        .login-link:hover { text-decoration: underline; }

        .login-btn {
            width: 100%;
            padding: 12px 16px;
            border: 0;
            border-radius: 10px;
            background: #059669;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background .15s ease, transform .05s ease;
        }

        .login-btn:hover { background: #047857; }
        .login-btn:active { transform: translateY(1px); }

        .login-hint {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 6px;
            margin-top: 14px;
            font-size: 12px;
            color: #6b7280;
        }

        .login-hint .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 8px;
            border-radius: 999px;
            background: #ecfdf5;
            color: #047857;
            font-size: 11px;
            font-weight: 600;
        }

        .login-footer {
            margin-top: 26px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            font-size: 12px;
            color: #9ca3af;
        }

        .login-footer a { color: #6b7280; text-decoration: none; }
        .login-footer a:hover { color: #047857; }

        @media (max-width: 1023px) {
            .login-page { flex-direction: column; }

            .login-media {
                flex: none;
                min-height: 0;
                height: 40vh;
                min-height: 300px;
                max-height: 380px;
            }

            .media-logo { top: 16px; left: 16px; padding: 6px 10px; }
            .media-logo img { height: 28px; }

            .media-counter { top: 18px; right: 16px; font-size: 11px; }

            .media-content { left: 20px; right: 20px; bottom: 56px; }
            .media-title { font-size: 24px; }
            .media-desc { font-size: 13px; margin-bottom: 10px; }

            .media-arrow { display: none; }
            .media-dots { bottom: 18px; }

            .login-panel { padding: 28px 20px 40px; }
            .login-title { font-size: 25px; }
        }

        @media (max-width: 420px) {
            .media-content .media-desc { display: none; }
            .media-title { font-size: 21px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .login-slide { transition: opacity .2s ease; transform: none; }
        }
    </style>
</head>
<body>
    <div class="login-page">

        {{-- ===== Galeri wisata ===== --}}
        <section class="login-media" id="loginMedia" aria-label="Galeri wisata Kabupaten Tanah Bumbu">
            @php
                $slides = [
                    [
                        'file'    => 'pantai-siring-pagatan.jpg',
                        'title'   => 'Pantai Siring Pagatan',
                        'desc'    => 'Hamparan pasir pesisir Kusan Hilir dengan deretan pohon cemara yang meneduhkan.',
                        'credit'  => 'said masalboys',
                        'license' => 'CC BY 3.0',
                        'url'     => 'https://creativecommons.org/licenses/by/3.0/',
                    ],
                    [
                        'file'    => 'pantai-pagatan.jpg',
                        'title'   => 'Pantai Pagatan',
                        'desc'    => 'Ombak pesisir Pagatan, salah satu ikon wisata bahari Kabupaten Tanah Bumbu.',
                        'credit'  => 'Palar Banjarnahor',
                        'license' => 'CC BY 3.0',
                        'url'     => 'https://creativecommons.org/licenses/by/3.0/',
                    ],
                    [
                        'file'    => 'embung-salimuran.jpg',
                        'title'   => 'Embung Salimuran',
                        'desc'    => 'Embung Salimuran, panorama air tenang dengan latar perbukitan hijau Tanah Bumbu.',
                        'credit'  => 'Wadaihangit',
                        'license' => 'CC BY-SA 4.0',
                        'url'     => 'https://creativecommons.org/licenses/by-sa/4.0/',
                    ],
                    [
                        'file'    => 'menara-suar-tanjung-petang.jpg',
                        'title'   => 'Menara Suar Tanjung Petang',
                        'desc'    => 'Menara suar di pesisir Tanjung Petang, Pagatan — saksi sejarah pelayaran di Tanah Bumbu.',
                        'credit'  => 'Wadaihangit',
                        'license' => 'CC BY-SA 4.0',
                        'url'     => 'https://creativecommons.org/licenses/by-sa/4.0/',
                    ],
                    [
                        'file'    => 'masjid-agung-nurussalam.jpg',
                        'title'   => 'Masjid Agung Nurussalam',
                        'desc'    => 'Masjid Agung Nurussalam, landmark religi dan budaya masyarakat Tanah Bumbu.',
                        'credit'  => 'Wadaihangit',
                        'license' => 'CC BY-SA 4.0',
                        'url'     => 'https://creativecommons.org/licenses/by-sa/4.0/',
                    ],
                ];
            @endphp

            @foreach ($slides as $i => $slide)
                <div class="login-slide {{ $i === 0 ? 'is-active' : '' }}"
                     style="background-image: url('{{ asset('images/login/'.$slide['file']) }}')"
                     data-title="{{ $slide['title'] }}"
                     data-desc="{{ $slide['desc'] }}"
                     data-credit="{{ $slide['credit'] }}"
                     data-license="{{ $slide['license'] }}"
                     data-url="{{ $slide['url'] }}"></div>
            @endforeach

            <a href="{{ route('home') }}" class="media-logo" aria-label="Kembali ke beranda SIPETATA">
                <img src="{{ asset('images/logo-sipetata-trim.png') }}" alt="Logo SIPETATA">
            </a>

            <div class="media-counter" id="slideCounter">1 / {{ count($slides) }}</div>

            <button type="button" class="media-arrow prev" id="prevSlide" aria-label="Foto sebelumnya">&#8249;</button>
            <button type="button" class="media-arrow next" id="nextSlide" aria-label="Foto berikutnya">&#8250;</button>

            <div class="media-content">
                <p class="media-kicker">Galeri Wisata Tanah Bumbu</p>
                <h2 class="media-title" id="slideTitle">{{ $slides[0]['title'] }}</h2>
                <p class="media-desc" id="slideDesc">{{ $slides[0]['desc'] }}</p>
                <p class="media-credit">
                    Foto: <span id="slideCredit">{{ $slides[0]['credit'] }}</span> ·
                    <a id="slideLicense" href="{{ $slides[0]['url'] }}" target="_blank" rel="noopener">{{ $slides[0]['license'] }}</a> ·
                    <a href="https://commons.wikimedia.org/wiki/Category:Tanah_Bumbu_Regency" target="_blank" rel="noopener">Wikimedia Commons</a>
                </p>
            </div>

            <div class="media-dots" role="tablist" aria-label="Pilih foto galeri">
                @foreach ($slides as $i => $slide)
                    <button type="button"
                            class="media-dot {{ $i === 0 ? 'is-active' : '' }}"
                            data-index="{{ $i }}"
                            role="tab"
                            aria-selected="{{ $i === 0 ? 'true' : 'false' }}"
                            aria-label="Foto {{ $i + 1 }}: {{ $slide['title'] }}"></button>
                @endforeach
            </div>
        </section>

        {{-- ===== Form login ===== --}}
        <main class="login-panel">
            <div class="login-card">
                <p class="login-eyebrow">Panel Admin</p>
                <h1 class="login-title">Selamat datang kembali</h1>
                <p class="login-sub">Masuk untuk mengelola data dan peta destinasi wisata Kabupaten Tanah Bumbu.</p>

                @if (session('status'))
                    <div class="login-status">{{ session('status') }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="login-field">
                        <label class="login-label" for="email">Email</label>
                        <input id="email"
                               class="login-input"
                               type="email"
                               name="email"
                               value="{{ old('email') }}"
                               placeholder="nama@sipetata.com"
                               required
                               autofocus
                               autocomplete="username">
                        @error('email')
                            <p class="login-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="login-field">
                        <label class="login-label" for="password">Password</label>
                        <div class="login-pass-wrap">
                            <input id="password"
                                   class="login-input"
                                   type="password"
                                   name="password"
                                   placeholder="Masukkan password"
                                   required
                                   autocomplete="current-password">
                            <button type="button" class="login-toggle" id="togglePassword" aria-label="Tampilkan password">
                                Lihat
                            </button>
                        </div>
                        @error('password')
                            <p class="login-error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="login-row">
                        <label class="login-remember" for="remember_me">
                            <input id="remember_me" type="checkbox" name="remember">
                            <span>Ingat saya</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="login-link" href="{{ route('password.request') }}">Lupa password?</a>
                        @endif
                    </div>

                    <button type="submit" class="login-btn">Masuk</button>

                    <div class="login-hint">
                        <span class="badge">Admin</span>
                        <span class="badge">Petugas</span>
                        <span>Gunakan akun yang diberikan pengelola.</span>
                    </div>
                </form>

                <div class="login-footer">
                    <a href="{{ route('home') }}">← Kembali ke beranda</a>
                    <span>&copy; {{ date('Y') }} SIPETATA</span>
                </div>
            </div>
        </main>
    </div>

    <script>
        (function () {
            var media = document.getElementById('loginMedia');
            var slides = Array.prototype.slice.call(document.querySelectorAll('.login-slide'));
            var dots = Array.prototype.slice.call(document.querySelectorAll('.media-dot'));
            var titleEl = document.getElementById('slideTitle');
            var descEl = document.getElementById('slideDesc');
            var creditEl = document.getElementById('slideCredit');
            var licenseEl = document.getElementById('slideLicense');
            var counterEl = document.getElementById('slideCounter');

            var index = 0;
            var timer = null;
            var DELAY = 5500;

            function render(i) {
                index = (i + slides.length) % slides.length;

                slides.forEach(function (slide, n) {
                    slide.classList.toggle('is-active', n === index);
                });

                dots.forEach(function (dot, n) {
                    dot.classList.toggle('is-active', n === index);
                    dot.setAttribute('aria-selected', n === index ? 'true' : 'false');
                });

                var data = slides[index].dataset;
                titleEl.textContent = data.title;
                descEl.textContent = data.desc;
                creditEl.textContent = data.credit;
                licenseEl.textContent = data.license;
                licenseEl.href = data.url;
                counterEl.textContent = (index + 1) + ' / ' + slides.length;
            }

            function next() { render(index + 1); }
            function prev() { render(index - 1); }

            function stop() {
                if (timer) {
                    clearInterval(timer);
                    timer = null;
                }
            }

            function start() {
                stop();
                if (! window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                    timer = setInterval(next, DELAY);
                }
            }

            document.getElementById('nextSlide').addEventListener('click', function () {
                next();
                start();
            });

            document.getElementById('prevSlide').addEventListener('click', function () {
                prev();
                start();
            });

            dots.forEach(function (dot) {
                dot.addEventListener('click', function () {
                    render(parseInt(dot.dataset.index, 10));
                    start();
                });
            });

            media.addEventListener('mouseenter', stop);
            media.addEventListener('mouseleave', start);
            media.addEventListener('focusin', stop);
            media.addEventListener('focusout', start);

            document.addEventListener('keydown', function (event) {
                if (event.key === 'ArrowLeft') { prev(); start(); }
                if (event.key === 'ArrowRight') { next(); start(); }
            });

            var touchStartX = null;
            media.addEventListener('touchstart', function (event) {
                touchStartX = event.touches[0].clientX;
            }, { passive: true });
            media.addEventListener('touchend', function (event) {
                if (touchStartX === null) { return; }
                var dx = event.changedTouches[0].clientX - touchStartX;
                if (Math.abs(dx) > 40) {
                    dx < 0 ? next() : prev();
                    start();
                }
                touchStartX = null;
            }, { passive: true });

            var toggle = document.getElementById('togglePassword');
            var password = document.getElementById('password');
            toggle.addEventListener('click', function () {
                var show = password.type === 'password';
                password.type = show ? 'text' : 'password';
                toggle.textContent = show ? 'Sembunyikan' : 'Lihat';
                toggle.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
            });

            render(0);
            start();
        })();
    </script>
</body>
</html>
