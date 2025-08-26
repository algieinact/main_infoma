@extends('layouts.app')

@section('title', 'Temukan Kegiatan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <!-- Search and Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('activities.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                        placeholder="Cari nama kegiatan...">
                </div>

                <!-- Category -->
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
                    <select name="category" id="category"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- City -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">Kota</label>
                    <select name="city" id="city"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Semua Kota</option>
                        @foreach($cities as $city)
                        <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Range -->
                <div>
                    <label for="date_range" class="block text-sm font-medium text-gray-700 mb-1">Rentang Tanggal</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Mulai">
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Selesai">
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- Price Range -->
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Rentang Harga</label>
                    <div class="flex items-center space-x-2">
                        <input type="number" name="min_price" value="{{ request('min_price') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Min">
                        <span class="text-gray-500">-</span>
                        <input type="number" name="max_price" value="{{ request('max_price') }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            placeholder="Max">
                    </div>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Urutkan</label>
                    <select name="sort" id="sort"
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="date_asc" {{ request('sort') == 'date_asc' ? 'selected' : '' }}>Tanggal: Terdekat</option>
                        <option value="date_desc" {{ request('sort') == 'date_desc' ? 'selected' : '' }}>Tanggal: Terjauh</option>
                        <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
                        <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
                        <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Rating Tertinggi</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($activities as $activity)
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <a href="{{ route('activities.show', $activity->slug) }}" class="block">
                <div class="relative h-48">
                    <img src="{{ $activity->first_image ?? asset('images/placeholder.jpg') }}"
                        alt="{{ $activity->title }}"
                        class="w-full h-full object-cover">
                    @if($activity->is_featured)
                    <span class="absolute top-2 right-2 bg-yellow-400 text-xs font-bold px-2 py-1 rounded">
                        Featured
                    </span>
                    @endif
                    <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-4">
                        <div class="text-white">
                            <div class="flex items-center text-sm mb-1">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                {{ $activity->start_date->format('d M Y') }}
                            </div>
                            <div class="flex items-center text-sm">
                                <i class="fas fa-users mr-2"></i>
                                {{ $activity->current_participants }}/{{ $activity->max_participants }} peserta
                            </div>
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $activity->title }}</h3>
                    <p class="text-gray-600 text-sm mb-2">
                        <i class="fas fa-map-marker-alt text-blue-500 mr-1"></i>
                        {{ $activity->city }}
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-blue-600 font-semibold">
                            {{ $activity->is_free ? 'Gratis' : 'Rp ' . number_format($activity->price) }}
                        </span>
                        <x-rating-summary :rating="$activity->rating" :totalReviews="$activity->total_reviews" size="sm" />
                    </div>
                </div>
            </a>
        </div>
        @empty
        <div class="col-span-full text-center py-12">
            <i class="fas fa-search text-gray-400 text-5xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600">Tidak ada hasil yang ditemukan</h3>
            <p class="text-gray-500 mt-2">Coba ubah filter pencarian Anda</p>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $activities->links() }}
    </div>
</div>
@endsection 