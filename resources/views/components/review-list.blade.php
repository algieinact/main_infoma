@props(['reviews', 'showForm' => false, 'reviewableType' => null, 'reviewableId' => null, 'existingReview' => null])

<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold mb-4">Reviews ({{ $reviews->count() }})</h2>
    
    @if($reviews->count() > 0)
        <div class="space-y-6 mb-6">
            @foreach($reviews as $review)
                <div class="border-b border-gray-200 pb-6 last:border-b-0">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gray-200 rounded-full flex items-center justify-center">
                                <span class="text-gray-600 font-semibold text-sm">
                                    {{ $review->display_name[0] }}
                                </span>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">{{ $review->display_name }}</h4>
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
                    
                    <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                    
                    @if($review->images && count($review->images) > 0)
                        <div class="mt-3 flex space-x-2">
                            @foreach($review->images as $image)
                                <img src="{{ asset('storage/' . $image) }}" 
                                     alt="Review image" 
                                     class="w-16 h-16 object-cover rounded">
                            @endforeach
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No reviews yet</h3>
            <p class="text-gray-500">Be the first to share your experience!</p>
        </div>
    @endif
    
    @if($showForm && $reviewableType && $reviewableId)
        @if(auth()->check())
            @php
                $userReview = $reviews->where('user_id', auth()->id())->first();
            @endphp
            
            @if($userReview)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold mb-4">Your Review</h3>
                    <x-review-form 
                        :reviewableType="$reviewableType" 
                        :reviewableId="$reviewableId" 
                        :existingReview="$userReview" />
                </div>
            @else
                <div class="border-t border-gray-200 pt-6">
                    <x-review-form 
                        :reviewableType="$reviewableType" 
                        :reviewableId="$reviewableId" />
                </div>
            @endif
        @else
            <div class="border-t border-gray-200 pt-6 text-center">
                <p class="text-gray-600 mb-4">Please <a href="{{ route('login') }}" class="text-blue-600 hover:underline">login</a> to write a review.</p>
            </div>
        @endif
    @endif
</div>
