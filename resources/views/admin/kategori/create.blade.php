@extends('layouts.admin')

@section('title', 'Tambah Kategori')

@section('content')
<form method="POST" action="{{ route('admin.kategori.store') }}"
      class="bg-white p-6 rounded-lg shadow max-w-3xl">
    @include('admin.kategori._form')
</form>
@endsection
