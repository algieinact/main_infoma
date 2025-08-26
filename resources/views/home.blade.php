@extends('layouts.app')

@section('title', 'Infoma - Platform Informasi Kebutuhan Mahasiswa')

@section('content')
<!-- Hero Section -->
<div class="relative bg-blue-900 text-white">
    <div class="absolute inset-0">
        <img src="https://images.unsplash.com/photo-1606761568499-6d2451b23c66?q=80&w=1974&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
            alt="Mahasiswa" class="w-full h-full object-cover object-center opacity-70">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-700 via-blue-800 to-blue-900 opacity-60"></div>
    </div>
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                Selamat Datang, {{ auth()->user()->name }}!
            </h1>
            <p class="text-xl md:text-2xl mb-8">Platform terpercaya untuk kebutuhan mahasiswa</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('residences.index') }}"
                    class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-blue-50 transition duration-300">
                    Cari Tempat Tinggal
                </a>
                <a href="{{ route('activities.index') }}"
                    class="bg-blue-500 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-400 transition duration-300">
                    Lihat Kegiatan
                </a>
            </div>
        </div>
    </div>
</div>


<!-- Recently Viewed Section -->
<!-- @if(count($recentHistory) > 0)
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-gray-900 mb-8">Baru Dilihat</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($recentHistory->take(3) as $item)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                <div class="p-6">
                    <h3 class="text-xl font-semibold mb-2">{{ $item->activityable->title ?? '-' }}</h3>
                    <p class="text-gray-600">{{ Str::limit($item->activityable->description ?? '-', 100) }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif -->

<!-- Available Residences Section -->
<div class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Tempat Tinggal Tersedia</h2>
                <p class="mt-2 text-gray-600">Daftar kost dan kontrakan yang masih tersedia untuk mahasiswa</p>
            </div>
            <a href="{{ route('residences.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Lihat Semua →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($recentResidences->take(9) as $residence)
            <a href="{{ route('residences.show', $residence) }}" class="block group">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    @if($residence->images && count($residence->images) > 0)
                    <img src="{{ asset('storage/' . $residence->images[0]) }}" alt="{{ $residence->title }}"
                        class="w-full h-48 object-cover group-hover:opacity-90">
                    @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No image</span>
                    </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $residence->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($residence->description, 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-semibold">Rp {{ number_format($residence->price) }} /
                                {{ $residence->price_period }}</span>
                            <span class="text-blue-600 hover:text-blue-800">Detail →</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Available Activities Section -->
<div class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Kegiatan Kampus Tersedia</h2>
                <p class="mt-2 text-gray-600">Daftar kegiatan kampus yang masih bisa diikuti</p>
            </div>
            <a href="{{ route('activities.index') }}" class="text-blue-600 hover:text-blue-800 font-semibold">
                Lihat Semua →
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($recentActivities->take(9) as $activity)
            <a href="{{ route('activities.show', $activity) }}" class="block group">
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition duration-300">
                    @if($activity->images && count($activity->images) > 0)
                    <img src="{{ asset('storage/' . $activity->images[0]) }}" alt="{{ $activity->title }}"
                        class="w-full h-48 object-cover group-hover:opacity-90">
                    @else
                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                        <span class="text-gray-400">No image</span>
                    </div>
                    @endif
                    <div class="p-6">
                        <h3 class="text-xl font-semibold mb-2">{{ $activity->title }}</h3>
                        <p class="text-gray-600 mb-4">{{ Str::limit($activity->description, 100) }}</p>
                        <div class="flex justify-between items-center">
                            <span class="text-blue-600 font-semibold">Rp {{ number_format($activity->price) }}</span>
                            <span class="text-blue-600 hover:text-blue-800">Detail →</span>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>

@endsection