@extends('layouts.admin')
@section('title', 'Tambah Wisata')
@section('content')
<form method="POST" action="{{ route('admin.wisata.store') }}"
      class="bg-white p-6 rounded-lg shadow max-w-4xl">
    @include('admin.wisata._form')
</form>
@endsection