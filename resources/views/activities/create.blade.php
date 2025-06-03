@extends('layouts.appAdmin')
@section('title', 'Tambah Kegiatan')
@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Tambah Kegiatan</h1>
    <form action="{{ route('provider.activities.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @include('activities.form', ['mode' => 'create'])
        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
            <a href="{{ route('provider.dashboard') }}" class="ml-4 text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
