@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-gray-500 text-sm">Total Wisata</p>
        <p class="text-3xl font-bold text-emerald-700">{{ $totalWisata }}</p>
    </div>
    <div class="bg-white p-6 rounded-lg shadow">
        <p class="text-gray-500 text-sm">Total Kategori</p>
        <p class="text-3xl font-bold text-emerald-700">{{ $totalKategori }}</p>
    </div>
</div>
@endsection