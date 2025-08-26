@extends('layouts.app')

@section('title', 'All Reviews')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">All Reviews</h1>
        <p class="mt-2 text-gray-600">See what people are saying about our residences and activities</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <form action="{{ route('reviews.index') }}" method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Type Filter -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select name="type" id="type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Types</option>
                        <option value="residence" {{ request('type') == 'residence' ? 'selected' : '' }}>Residences</option>
                        <option value="activity" {{ request('type') == 'activity' ? 'selected' : '' }}>Activities</option>
                    </select>
                </div>

                <!-- Rating Filter -->
                <div>
                    <label for="rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select name="rating" id="rating" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Ratings</option>
                        <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                        <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                        <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                        <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                        <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
                    </select>
                </div>

                <!-- Sort -->
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-1">Sort By</label>
                    <select name="sort" id="sort" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                        <option value="rating_high" {{ request('sort') == 'rating_high' ? 'selected' : '' }}>Highest Rating</option>
                        <option value="rating_low" {{ request('sort') == 'rating_low' ? 'selected' : '' }}>Lowest Rating</option>
                    </select>
                </div>

                <!-- Search -->
                <div>
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Search</label>
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
                           placeholder="Search comments...">
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Reviews List -->
    <div class="space-y-6">
        @forelse($reviews as $review)
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <span class="text-gray-600 font-semibold text-lg">
                                {{ $review->display_name[0] }}
                            </span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $review->display_name }}</h3>
                            <div class="flex items-center space-x-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                                <span class="text-sm text-gray-600 ml-2">{{ $review->rating }}/5</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-sm text-gray-500">
                        {{ $review->created_at->diffForHumans() }}
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- Review Type Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                            {{ $review->reviewable_type === 'App\\Models\\Residence' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                            {{ $review->reviewable_type === 'App\\Models\\Residence' ? 'Residence' : 'Activity' }}
                        </span>

                        <!-- Item Name -->
                        <a href="{{ $review->reviewable_type === 'App\\Models\\Residence' ? route('residences.show', $review->reviewable->slug) : route('activities.show', $review->reviewable->slug) }}" 
                           class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $review->reviewable->title }}
                        </a>
                    </div>

                    @auth
                        @if($review->user_id === auth()->id())
                            <div class="flex items-center space-x-2">
                                <a href="#" onclick="editReview({{ $review->id }})" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                                <form action="{{ route('reviews.destroy', $review) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" onclick="return confirm('Are you sure you want to delete this review?')" 
                                            class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>

                @if($review->images && count($review->images) > 0)
                    <div class="mt-4 flex space-x-2">
                        @foreach($review->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" 
                                 alt="Review image" 
                                 class="w-16 h-16 object-cover rounded">
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No reviews found</h3>
                <p class="text-gray-500">Try adjusting your filters or check back later.</p>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $reviews->links() }}
    </div>
</div>
@endsection
