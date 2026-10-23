@extends('layouts.admin')
@section('title', 'Edit Wisata')
@section('content')
<form method="POST" action="{{ route('admin.wisata.update', $wisata) }}"
      class="bg-white p-6 rounded-lg shadow max-w-4xl">
    @method('PUT')
    @include('admin.wisata._form')
</form>
@endsection