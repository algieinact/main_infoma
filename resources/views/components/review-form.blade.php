@props(['reviewableType', 'reviewableId', 'existingReview' => null])

<div class="bg-white rounded-lg shadow-md p-6 mb-8">
    <h2 class="text-xl font-semibold mb-4">
        @if($existingReview)
            Edit Review
        @else
            Write a Review
        @endif
    </h2>
    
    @if($existingReview)
        <form action="{{ route('reviews.update', $existingReview) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
    @else
        <form action="{{ route('reviews.store') }}" method="POST" class="space-y-4">
            @csrf
    @endif
    
        <input type="hidden" name="reviewable_type" value="{{ $reviewableType }}">
        <input type="hidden" name="reviewable_id" value="{{ $reviewableId }}">
        
        <div>
            <label for="rating" class="block text-sm font-medium text-gray-700 mb-2">Rating</label>
            <div class="flex items-center space-x-2">
                @for($i = 1; $i <= 5; $i++)
                    <input type="radio" name="rating" id="rating_{{ $i }}" value="{{ $i }}" 
                           class="sr-only" 
                           {{ $existingReview && $existingReview->rating == $i ? 'checked' : '' }}
                           {{ !$existingReview && $i == 5 ? 'checked' : '' }}>
                    <label for="rating_{{ $i }}" class="cursor-pointer">
                        <svg class="w-8 h-8 {{ $existingReview && $existingReview->rating >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400 transition-colors" 
                             fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </label>
                @endfor
            </div>
            @error('rating')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="comment" class="block text-sm font-medium text-gray-700 mb-2">Comment</label>
            <textarea name="comment" id="comment" rows="4" 
                      class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                      placeholder="Share your experience...">{{ old('comment', $existingReview ? $existingReview->comment : '') }}</textarea>
            @error('comment')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center">
            <input type="checkbox" name="is_anonymous" id="is_anonymous" value="1" 
                   class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                   {{ $existingReview && $existingReview->is_anonymous ? 'checked' : '' }}>
            <label for="is_anonymous" class="ml-2 text-sm text-gray-700">Post anonymously</label>
        </div>

        <div class="flex justify-end space-x-3">
            @if($existingReview)
                <button type="button" onclick="deleteReview({{ $existingReview->id }})" 
                        class="px-4 py-2 text-red-600 border border-red-600 rounded-md hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-red-500">
                    Delete Review
                </button>
            @endif
            <button type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{ $existingReview ? 'Update Review' : 'Submit Review' }}
            </button>
        </div>
    </form>
</div>

@if($existingReview)
    <form id="delete-review-form" action="{{ route('reviews.destroy', $existingReview) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function deleteReview(reviewId) {
            if (confirm('Are you sure you want to delete this review?')) {
                document.getElementById('delete-review-form').submit();
            }
        }
    </script>
@endif
