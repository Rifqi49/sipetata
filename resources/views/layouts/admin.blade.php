<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SIPETATA</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full">
    <div class="min-h-full flex">

        {{-- Sidebar --}}
        <aside class="w-64 bg-emerald-800 text-white flex flex-col">
            <div class="p-4 border-b border-emerald-700">
                <h1 class="text-xl font-bold leading-tight">SIPETATA</h1>
                <p class="text-xs text-emerald-200">Sistem Pemetaan Wisata Tanah Bumbu</p>
            </div>
            <nav class="flex-1 p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}"
                   class="block px-3 py-2 rounded hover:bg-emerald-700
                          {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-700' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('admin.wisata.index') }}"
                   class="block px-3 py-2 rounded hover:bg-emerald-700
                          {{ request()->routeIs('admin.wisata.*') ? 'bg-emerald-700' : '' }}">
                    Data Wisata
                </a>
                <a href="{{ route('peta') }}" target="_blank"
                   class="block px-3 py-2 rounded hover:bg-emerald-700">
                    Lihat Peta ↗
                </a>
            </nav>
            <form method="POST" action="{{ route('logout') }}" class="p-4 border-t border-emerald-700">
                @csrf
                <button class="w-full text-left px-3 py-2 rounded hover:bg-emerald-700">
                    Logout
                </button>
            </form>
        </aside>

        {{-- Main --}}
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800">@yield('title')</h1>
                @yield('actions')
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>