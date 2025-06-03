@extends('layouts.app')
@section('title', 'Edit Profil')
@section('content')
<div class="max-w-2xl mx-auto py-10">
    <h1 class="text-2xl font-bold mb-6">Edit Profil</h1>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')
        <div class="flex items-center space-x-4 mb-6">
            <img src="{{ Auth::user()->avatar_url }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border">
            <div>
                <label class="block font-medium mb-1">Ganti Foto Profil</label>
                <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-gray-500">
                @error('avatar')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div>
            <label class="block font-medium">Nama Lengkap</label>
            <input type="text" name="name" class="w-full border rounded px-3 py-2" value="{{ old('name', Auth::user()->name) }}" required>
            @error('name')
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block font-medium">Email</label>
            <input type="email" name="email" class="w-full border rounded px-3 py-2 bg-gray-100" value="{{ Auth::user()->email }}" readonly>
        </div>
        <div>
            <label class="block font-medium">Nomor Telepon</label>
            <input type="text" name="phone" class="w-full border rounded px-3 py-2" value="{{ old('phone', Auth::user()->phone) }}">
            @error('phone')
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label class="block font-medium">Alamat</label>
            <input type="text" name="address" class="w-full border rounded px-3 py-2" value="{{ old('address', Auth::user()->address) }}">
            @error('address')
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Tanggal Lahir</label>
                <input type="date" name="birth_date" class="w-full border rounded px-3 py-2" value="{{ old('birth_date', Auth::user()->birth_date ? Auth::user()->birth_date->format('Y-m-d') : '') }}">
                @error('birth_date')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block font-medium">Jenis Kelamin</label>
                <select name="gender" class="w-full border rounded px-3 py-2">
                    <option value="">Pilih</option>
                    <option value="male" @if(old('gender', Auth::user()->gender)==='male') selected @endif>Laki-laki</option>
                    <option value="female" @if(old('gender', Auth::user()->gender)==='female') selected @endif>Perempuan</option>
                </select>
                @error('gender')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-medium">Universitas</label>
                <input type="text" name="university" class="w-full border rounded px-3 py-2" value="{{ old('university', Auth::user()->university) }}">
                @error('university')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label class="block font-medium">Jurusan</label>
                <input type="text" name="major" class="w-full border rounded px-3 py-2" value="{{ old('major', Auth::user()->major) }}">
                @error('major')
                <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div>
            <label class="block font-medium">Tahun Lulus</label>
            <input type="number" name="graduation_year" class="w-full border rounded px-3 py-2" value="{{ old('graduation_year', Auth::user()->graduation_year) }}">
            @error('graduation_year')
            <div class="text-red-500 text-xs mt-1">{{ $message }}</div>
            @enderror
        </div>
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
