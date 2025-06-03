@extends('layouts.app')

@section('title', $activity->title)

@section('content')
<div class="bg-gray-100 min-h-screen py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm">
                <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700">Home</a></li>
                <li class="text-gray-500">/</li>
                <li><a href="{{ route('activities.index') }}" class="text-gray-500 hover:text-gray-700">Activities</a></li>
                <li class="text-gray-500">/</li>
                <li class="text-gray-900">{{ $activity->title }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
                    <div class="relative">
                        @if($activity->is_featured)
                            <div class="absolute top-4 left-4 bg-yellow-400 text-yellow-900 px-3 py-1 rounded-full text-sm font-semibold">
                                Featured
                            </div>
                        @endif
                        <div class="aspect-w-16 aspect-h-9">
                            @if($activity->images && count($activity->images) > 0)
                                <img src="{{ asset('storage/' . $activity->images[0]) }}" 
                                     alt="{{ $activity->title }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-400">No image available</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    @if($activity->images && count($activity->images) > 1)
                        <div class="p-4 grid grid-cols-4 gap-2">
                            @foreach($activity->images as $image)
                                <div class="aspect-w-1 aspect-h-1">
                                    <img src="{{ asset('storage/' . $image) }}" 
                                         alt="{{ $activity->title }}" 
                                         class="w-full h-full object-cover rounded cursor-pointer hover:opacity-75">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Activity Details -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $activity->title }}</h1>
                    <div class="flex items-center text-gray-600 mb-4">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span>{{ $activity->location }}, {{ $activity->city }}</span>
                    </div>
                    <div class="flex items-center mb-6">
                        <div class="flex items-center text-yellow-400">
                            @for($i = 1; $i <= 5; $i++)
                                <svg class="w-5 h-5 {{ $i <= $averageRating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        <span class="ml-2 text-gray-600">{{ number_format($averageRating, 1) }} ({{ $activity->total_reviews }} reviews)</span>
                    </div>

                    <div class="prose max-w-none mb-8">
                        <h2 class="text-xl font-semibold mb-4">Description</h2>
                        <p class="text-gray-600">{{ $activity->description }}</p>
                    </div>

                    <!-- Schedule -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Schedule</h2>
                            <div class="space-y-4">
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium">Start Date</p>
                                        <p>{{ $activity->start_date->format('F j, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium">End Date</p>
                                        <p>{{ $activity->end_date->format('F j, Y') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center text-gray-600">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div>
                                        <p class="font-medium">Time</p>
                                        <p>{{ $activity->start_date->format('g:i A') }} - {{ $activity->end_date->format('g:i A') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold mb-4">Requirements</h2>
                            <ul class="space-y-2">
                                @foreach($activity->requirements as $requirement)
                                    <li class="flex items-center text-gray-600">
                                        <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        {{ $requirement }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div>
                        <h2 class="text-xl font-semibold mb-4">Reviews</h2>
                        @if($activity->reviews->count() > 0)
                            <div class="space-y-6">
                                @foreach($activity->reviews as $review)
                                    <div class="border-b border-gray-200 pb-6">
                                        <div class="flex items-center mb-4">
                                            <img src="{{ $review->user->avatar ?? asset('images/default-avatar.png') }}" 
                                                 alt="{{ $review->user->name }}" 
                                                 class="w-10 h-10 rounded-full mr-4">
                                            <div>
                                                <h3 class="font-semibold">{{ $review->user->name }}</h3>
                                                <div class="flex items-center text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-gray-600">{{ $review->comment }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-600">No reviews yet.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Registration Card -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <div class="text-2xl font-bold text-gray-900 mb-4">
                        {{ $activity->formatted_price }}
                    </div>
                    <div class="space-y-4 mb-6">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                            <span>{{ $activity->current_participants }} / {{ $activity->max_participants }} participants</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Registration until {{ $activity->registration_deadline->format('F j, Y') }}</span>
                        </div>
                    </div>
                    @auth
                        @if($activity->isBookmarkedBy(auth()->user()))
                            <form action="{{ route('bookmarks.destroy', $activity->bookmarks->where('user_id', auth()->id())->first()) }}" method="POST" class="mb-4">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z"/>
                                    </svg>
                                    Remove Bookmark
                                </button>
                            </form>
                        @else
                            <form action="{{ route('bookmarks.store') }}" method="POST" class="mb-4">
                                @csrf
                                <input type="hidden" name="bookmarkable_type" value="App\Models\Activity">
                                <input type="hidden" name="bookmarkable_id" value="{{ $activity->id }}">
                                <button type="submit" class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                    </svg>
                                    Add to Bookmarks
                                </button>
                            </form>
                        @endif
                    @endauth
                    @if($registrationOpen)
                        <a href="{{ route('bookings.create.activity', ['id' => $activity->id]) }}" 
                           class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-300">
                            Register Now
                        </a>
                    @else
                        <button disabled class="block w-full bg-gray-400 text-white text-center py-3 rounded-lg font-semibold cursor-not-allowed">
                            Registration Closed
                        </button>
                    @endif
                </div>

                <!-- Provider Info -->
                <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                    <h2 class="text-xl font-semibold mb-4">Provider Information</h2>
                    <div class="flex items-center mb-4">
                        <img src="{{ $activity->provider->avatar ?? asset('images/default-avatar.png') }}" 
                             alt="{{ $activity->provider->name }}" 
                             class="w-12 h-12 rounded-full mr-4">
                        <div>
                            <h3 class="font-semibold">{{ $activity->provider->name }}</h3>
                            <p class="text-gray-600">Member since {{ $activity->provider->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span>{{ $activity->provider->phone }}</span>
                        </div>
                        <div class="flex items-center text-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $activity->provider->email }}</span>
                        </div>
                    </div>
                </div>

                <!-- Similar Activities -->
                @if($similarActivities->count() > 0)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h2 class="text-xl font-semibold mb-4">Similar Activities</h2>
                        <div class="space-y-4">
                            @foreach($similarActivities as $similar)
                                <a href="{{ route('activities.show', $similar->slug) }}" class="block">
                                    <div class="flex items-center">
                                        @if($similar->images && count($similar->images) > 0)
                                            <img src="{{ asset('storage/' . $similar->images[0]) }}" 
                                                 alt="{{ $similar->title }}" 
                                                 class="w-20 h-20 object-cover rounded-lg mr-4">
                                        @else
                                            <div class="w-20 h-20 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                                <span class="text-gray-400 text-xs">No image</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h3 class="font-semibold text-gray-900">{{ $similar->title }}</h3>
                                            <p class="text-gray-600">{{ $similar->formatted_price }}</p>
                                            <p class="text-sm text-gray-500">{{ $similar->start_date->format('M j, Y') }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection 