@extends('layouts.appAdmin')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <form action="{{ $mode === 'create' ? route('provider.activities.store') : route('provider.activities.update', $activity) }}" 
          method="POST" 
          enctype="multipart/form-data" 
          class="space-y-6">
        @csrf
        @if($mode === 'edit')
            @method('PUT')
        @endif

        <div>
            <label class="block font-medium">Judul</label>
            <input type="text" name="title" class="w-full border rounded px-3 py-2" 
                   value="{{ old('title', $mode === 'edit' ? $activity->title : '') }}" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Deskripsi</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required>{{ old('description', $mode === 'edit' ? $activity->description : '') }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Kategori</label>
            <select name="category_id" class="w-full border rounded px-3 py-2" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" 
                        {{ old('category_id', $mode === 'edit' ? $activity->category_id : '') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Tipe</label>
            <select name="type" class="w-full border rounded px-3 py-2" required>
                <option value="">Pilih Tipe</option>
                <option value="seminar" {{ old('type', $mode === 'edit' ? $activity->type : '') == 'seminar' ? 'selected' : '' }}>Seminar</option>
                <option value="webinar" {{ old('type', $mode === 'edit' ? $activity->type : '') == 'webinar' ? 'selected' : '' }}>Webinar</option>
                <option value="mentoring" {{ old('type', $mode === 'edit' ? $activity->type : '') == 'mentoring' ? 'selected' : '' }}>Mentoring</option>
                <option value="lomba" {{ old('type', $mode === 'edit' ? $activity->type : '') == 'lomba' ? 'selected' : '' }}>Lomba</option>
                <option value="workshop" {{ old('type', $mode === 'edit' ? $activity->type : '') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                <option value="training" {{ old('type', $mode === 'edit' ? $activity->type : '') == 'training' ? 'selected' : '' }}>Training</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Format</label>
            <select name="format" class="w-full border rounded px-3 py-2" required>
                <option value="">Pilih Format</option>
                <option value="online" {{ old('format', $mode === 'edit' ? $activity->format : '') == 'online' ? 'selected' : '' }}>Online</option>
                <option value="offline" {{ old('format', $mode === 'edit' ? $activity->format : '') == 'offline' ? 'selected' : '' }}>Offline</option>
                <option value="hybrid" {{ old('format', $mode === 'edit' ? $activity->format : '') == 'hybrid' ? 'selected' : '' }}>Hybrid</option>
            </select>
            @error('format')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Gratis?</label>
            <select name="is_free" class="w-full border rounded px-3 py-2" required>
                <option value="1" {{ old('is_free', $mode === 'edit' ? $activity->is_free : '') == 1 ? 'selected' : '' }}>Ya</option>
                <option value="0" {{ old('is_free', $mode === 'edit' ? $activity->is_free : '') == 0 ? 'selected' : '' }}>Tidak</option>
            </select>
            @error('is_free')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Harga</label>
            <input type="number" name="price" class="w-full border rounded px-3 py-2" 
                   value="{{ old('price', $mode === 'edit' ? $activity->price : '') }}" required>
            @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Tanggal Mulai</label>
            <input type="datetime-local" name="start_date" class="w-full border rounded px-3 py-2" 
                   value="{{ old('start_date', $mode === 'edit' ? $activity->start_date->format('Y-m-d\TH:i') : '') }}" required>
            @error('start_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Tanggal Selesai</label>
            <input type="datetime-local" name="end_date" class="w-full border rounded px-3 py-2" 
                   value="{{ old('end_date', $mode === 'edit' ? $activity->end_date->format('Y-m-d\TH:i') : '') }}" required>
            @error('end_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Batas Pendaftaran</label>
            <input type="datetime-local" name="registration_deadline" class="w-full border rounded px-3 py-2" 
                   value="{{ old('registration_deadline', $mode === 'edit' ? $activity->registration_deadline->format('Y-m-d\TH:i') : '') }}" required>
            @error('registration_deadline')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Lokasi</label>
            <input type="text" name="location" class="w-full border rounded px-3 py-2" 
                   value="{{ old('location', $mode === 'edit' ? $activity->location : '') }}" required>
            @error('location')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Kota</label>
            <input type="text" name="city" class="w-full border rounded px-3 py-2" 
                   value="{{ old('city', $mode === 'edit' ? $activity->city : '') }}" required>
            @error('city')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Provinsi</label>
            <input type="text" name="province" class="w-full border rounded px-3 py-2" 
                   value="{{ old('province', $mode === 'edit' ? $activity->province : '') }}" required>
            @error('province')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Meeting Link (jika online)</label>
            <input type="text" name="meeting_link" class="w-full border rounded px-3 py-2" 
                   value="{{ old('meeting_link', $mode === 'edit' ? $activity->meeting_link : '') }}">
            @error('meeting_link')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Syarat (pisahkan dengan koma)</label>
            <input type="text" name="requirements" class="w-full border rounded px-3 py-2" 
                   value="{{ old('requirements', $mode === 'edit' ? implode(',', $activity->requirements ?? []) : '') }}" required>
            @error('requirements')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Benefit (pisahkan dengan koma)</label>
            <input type="text" name="benefits" class="w-full border rounded px-3 py-2" 
                   value="{{ old('benefits', $mode === 'edit' ? implode(',', $activity->benefits ?? []) : '') }}" required>
            @error('benefits')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Maksimal Peserta</label>
            <input type="number" name="max_participants" class="w-full border rounded px-3 py-2" 
                   value="{{ old('max_participants', $mode === 'edit' ? $activity->max_participants : '') }}" required>
            @error('max_participants')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Gambar</label>
            <input type="file" name="images[]" class="w-full border rounded px-3 py-2" multiple accept="image/*">
            @error('images')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            @if($mode === 'edit' && $activity->images)
                <div class="mt-2 grid grid-cols-4 gap-2">
                    @foreach($activity->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image) }}" alt="Activity Image" class="w-full h-24 object-cover rounded">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex justify-end space-x-4">
            <a href="{{ route('provider.dashboard') }}" class="px-4 py-2 text-gray-600 hover:text-gray-800">Batal</a>
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ $mode === 'create' ? 'Simpan' : 'Update' }}
            </button>
        </div>
    </form>
</div>
@endsection
