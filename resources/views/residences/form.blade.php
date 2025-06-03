@extends('layouts.appAdmin')

@section('content')
<div class="max-w-2xl mx-auto py-10">
    <form action="{{ $mode === 'create' ? route('provider.residences.store') : route('provider.residences.update', $residence) }}" 
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
                   value="{{ old('title', $mode === 'edit' ? $residence->title : '') }}" required>
            @error('title')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Deskripsi</label>
            <textarea name="description" class="w-full border rounded px-3 py-2" rows="4" required>{{ old('description', $mode === 'edit' ? $residence->description : '') }}</textarea>
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
                        {{ old('category_id', $mode === 'edit' ? $residence->category_id : '') == $category->id ? 'selected' : '' }}>
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
                <option value="kost" {{ old('type', $mode === 'edit' ? $residence->type : '') == 'kost' ? 'selected' : '' }}>Kost</option>
                <option value="kontrakan" {{ old('type', $mode === 'edit' ? $residence->type : '') == 'kontrakan' ? 'selected' : '' }}>Kontrakan</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Harga</label>
            <input type="number" name="price" class="w-full border rounded px-3 py-2" 
                   value="{{ old('price', $mode === 'edit' ? $residence->price : '') }}" required>
            @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Periode Harga</label>
            <select name="price_period" class="w-full border rounded px-3 py-2" required>
                <option value="">Pilih Periode</option>
                <option value="daily" {{ old('price_period', $mode === 'edit' ? $residence->price_period : '') == 'daily' ? 'selected' : '' }}>Harian</option>
                <option value="weekly" {{ old('price_period', $mode === 'edit' ? $residence->price_period : '') == 'weekly' ? 'selected' : '' }}>Mingguan</option>
                <option value="monthly" {{ old('price_period', $mode === 'edit' ? $residence->price_period : '') == 'monthly' ? 'selected' : '' }}>Bulanan</option>
                <option value="yearly" {{ old('price_period', $mode === 'edit' ? $residence->price_period : '') == 'yearly' ? 'selected' : '' }}>Tahunan</option>
            </select>
            @error('price_period')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Alamat</label>
            <input type="text" name="address" class="w-full border rounded px-3 py-2" 
                   value="{{ old('address', $mode === 'edit' ? $residence->address : '') }}" required>
            @error('address')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Kota</label>
            <input type="text" name="city" class="w-full border rounded px-3 py-2" 
                   value="{{ old('city', $mode === 'edit' ? $residence->city : '') }}" required>
            @error('city')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Provinsi</label>
            <input type="text" name="province" class="w-full border rounded px-3 py-2" 
                   value="{{ old('province', $mode === 'edit' ? $residence->province : '') }}" required>
            @error('province')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Fasilitas (pisahkan dengan koma)</label>
            <input type="text" name="facilities" class="w-full border rounded px-3 py-2" 
                   value="{{ old('facilities', $mode === 'edit' ? implode(',', $residence->facilities ?? []) : '') }}" required>
            @error('facilities')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Peraturan (pisahkan dengan koma)</label>
            <input type="text" name="rules" class="w-full border rounded px-3 py-2" 
                   value="{{ old('rules', $mode === 'edit' ? implode(',', $residence->rules ?? []) : '') }}" required>
            @error('rules')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Jumlah Kamar</label>
            <input type="number" name="total_rooms" class="w-full border rounded px-3 py-2" 
                   value="{{ old('total_rooms', $mode === 'edit' ? $residence->total_rooms : '') }}" required>
            @error('total_rooms')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Kamar Tersedia</label>
            <input type="number" name="available_rooms" class="w-full border rounded px-3 py-2" 
                   value="{{ old('available_rooms', $mode === 'edit' ? $residence->available_rooms : '') }}" required>
            @error('available_rooms')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Tipe Gender</label>
            <select name="gender_type" class="w-full border rounded px-3 py-2" required>
                <option value="">Pilih Tipe Gender</option>
                <option value="male" {{ old('gender_type', $mode === 'edit' ? $residence->gender_type : '') == 'male' ? 'selected' : '' }}>Laki-laki</option>
                <option value="female" {{ old('gender_type', $mode === 'edit' ? $residence->gender_type : '') == 'female' ? 'selected' : '' }}>Perempuan</option>
                <option value="mixed" {{ old('gender_type', $mode === 'edit' ? $residence->gender_type : '') == 'mixed' ? 'selected' : '' }}>Campuran</option>
            </select>
            @error('gender_type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block font-medium">Gambar</label>
            <input type="file" name="images[]" class="w-full border rounded px-3 py-2" multiple accept="image/*">
            @error('images')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
            @if($mode === 'edit' && $residence->images)
                <div class="mt-2 grid grid-cols-4 gap-2">
                    @foreach($residence->images as $image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $image) }}" alt="Residence Image" class="w-full h-24 object-cover rounded">
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
