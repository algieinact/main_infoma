<?php

namespace App\Http\Controllers\Api;

use App\Models\Review;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewController extends BaseController
{
    /**
     * Display a listing of reviews.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Review::query();

        // Apply filters
        if ($request->has('type')) {
            $query->where('reviewable_type', $request->type === 'residence' ? 'App\\Models\\Residence' : 'App\\Models\\Activity');
        }

        if ($request->has('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->with(['user', 'booking', 'reviewable'])
            ->latest()
            ->paginate($request->get('per_page', 10));

        return $this->sendResponse($reviews, 'Reviews retrieved successfully');
    }

    /**
     * Store a newly created review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        $booking = Booking::findOrFail($request->booking_id);

        // Check if user is authorized to review this booking
        if ($booking->user_id !== auth()->id()) {
            return $this->sendForbiddenError('You are not authorized to review this booking');
        }

        // Check if booking is completed
        if (!$booking->isCompleted()) {
            return $this->sendError('You can only review completed bookings');
        }

        // Check if review already exists
        if ($booking->reviews()->exists()) {
            return $this->sendError('You have already reviewed this booking');
        }

        $review = Review::create([
            'user_id' => auth()->id(),
            'booking_id' => $booking->id,
            'reviewable_type' => $booking->bookable_type,
            'reviewable_id' => $booking->bookable_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'review_date' => now(),
        ]);

        // Update bookable's rating
        $bookable = $booking->bookable;
        $bookable->total_reviews++;
        $bookable->rating = ($bookable->rating * ($bookable->total_reviews - 1) + $request->rating) / $bookable->total_reviews;
        $bookable->save();

        return $this->sendResponse($review, 'Review created successfully');
    }

    /**
     * Update the specified review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Review $review)
    {
        // Check if user is authorized to update this review
        if ($review->user_id !== auth()->id()) {
            return $this->sendForbiddenError('You are not authorized to update this review');
        }

        $validator = Validator::make($request->all(), [
            'rating' => 'sometimes|required|numeric|min:1|max:5',
            'comment' => 'sometimes|required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Update bookable's rating if rating changed
        if ($request->has('rating')) {
            $bookable = $review->reviewable;
            $bookable->rating = ($bookable->rating * $bookable->total_reviews - $review->rating + $request->rating) / $bookable->total_reviews;
            $bookable->save();
        }

        $review->fill($request->only(['rating', 'comment']));
        $review->save();

        return $this->sendResponse($review, 'Review updated successfully');
    }

    /**
     * Remove the specified review.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Review  $review
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Review $review)
    {
        // Check if user is authorized to delete this review
        if ($review->user_id !== auth()->id()) {
            return $this->sendForbiddenError('You are not authorized to delete this review');
        }

        // Update bookable's rating
        $bookable = $review->reviewable;
        if ($bookable->total_reviews > 1) {
            $bookable->rating = ($bookable->rating * $bookable->total_reviews - $review->rating) / ($bookable->total_reviews - 1);
        } else {
            $bookable->rating = 0;
        }
        $bookable->total_reviews--;
        $bookable->save();

        $review->delete();

        return $this->sendResponse(null, 'Review deleted successfully');
    }
} 