@extends('layouts.appAdmin')
@section('title', 'Edit Kegiatan')
@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Edit Kegiatan</h1>
    <form action="{{ route('provider.activities.update', $activity) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        @include('activities.form', ['mode' => 'edit'])
        <div>
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Update</button>
            <a href="{{ route('provider.dashboard') }}" class="ml-4 text-gray-600 hover:underline">Batal</a>
        </div>
    </form>
</div>
@endsection
