@extends('layouts.app')

@section('title', 'My Bookmarks')

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-900">My Bookmarks</li>
            </ol>
        </nav>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">My Bookmarks</h1>

            @if($bookmarks->isEmpty())
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No bookmarks</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by bookmarking some residences or activities.</p>
                    <div class="mt-6">
                        <a href="{{ route('residences.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Browse Residences
                        </a>
                        <a href="{{ route('activities.index') }}" class="ml-3 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-blue-600 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Browse Activities
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($bookmarks as $bookmark)
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition duration-300">
                            @if($bookmark->bookmarkable_type === 'App\Models\Residence')
                                <a href="{{ route('residences.show', $bookmark->bookmarkable->slug) }}" class="block">
                                    @if($bookmark->bookmarkable->images && count($bookmark->bookmarkable->images) > 0)
                                        <img src="{{ asset('storage/' . $bookmark->bookmarkable->images[0]) }}" 
                                             alt="{{ $bookmark->bookmarkable->title }}" 
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400">No image</span>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $bookmark->bookmarkable->title }}</h3>
                                        <p class="text-gray-600">{{ Str::limit($bookmark->bookmarkable->description, 100) }}</p>
                                        <div class="mt-4 flex justify-between items-center">
                                            <span class="text-blue-600 font-semibold">{{ $bookmark->bookmarkable->formatted_price }}</span>
                                            <span class="text-sm text-gray-500">{{ $bookmark->bookmarkable->city }}</span>
                                        </div>
                                    </div>
                                </a>
                            @else
                                <a href="{{ route('activities.show', $bookmark->bookmarkable->slug) }}" class="block">
                                    @if($bookmark->bookmarkable->images && count($bookmark->bookmarkable->images) > 0)
                                        <img src="{{ asset('storage/' . $bookmark->bookmarkable->images[0]) }}" 
                                             alt="{{ $bookmark->bookmarkable->title }}" 
                                             class="w-full h-48 object-cover">
                                    @else
                                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                            <span class="text-gray-400">No image</span>
                                        </div>
                                    @endif
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900">{{ $bookmark->bookmarkable->title }}</h3>
                                        <p class="text-gray-600">{{ Str::limit($bookmark->bookmarkable->description, 100) }}</p>
                                        <div class="mt-4 flex justify-between items-center">
                                            <span class="text-blue-600 font-semibold">{{ $bookmark->bookmarkable->formatted_price }}</span>
                                            <span class="text-sm text-gray-500">{{ $bookmark->bookmarkable->start_date->format('M j, Y') }}</span>
                                        </div>
                                    </div>
                                </a>
                            @endif
                            <div class="px-4 py-3 bg-gray-50 border-t border-gray-200">
                                <form action="{{ route('bookmarks.destroy', $bookmark) }}" method="POST" class="flex justify-end">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                        Remove Bookmark
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $bookmarks->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 