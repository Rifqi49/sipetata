@extends('layouts.admin')

@section('title', 'Edit Kategori')

@section('content')
<form method="POST" action="{{ route('admin.kategori.update', $kategori) }}"
      class="bg-white p-6 rounded-lg shadow max-w-3xl">
    @method('PUT')
    @include('admin.kategori._form')
</form>
@endsection
