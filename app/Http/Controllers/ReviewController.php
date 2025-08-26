<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Residence;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        $reviews = Review::with(['user', 'reviewable'])
            ->latest()
            ->paginate(20);

        return view('reviews.index', compact('reviews'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|string|in:App\Models\Residence,App\Models\Activity',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'is_anonymous' => 'boolean',
        ]);

        $reviewableType = $request->reviewable_type;
        $reviewableId = $request->reviewable_id;

        // Cek apakah user sudah pernah review
        $existing = Review::where('reviewable_type', $reviewableType)
            ->where('reviewable_id', $reviewableId)
            ->where('user_id', Auth::id())
            ->first();
            
        if ($existing) {
            return back()->with('error', 'You have already reviewed this item.');
        }

        try {
            DB::beginTransaction();

            $review = Review::create([
                'user_id' => Auth::id(),
                'reviewable_type' => $reviewableType,
                'reviewable_id' => $reviewableId,
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_anonymous' => $request->boolean('is_anonymous', false),
            ]);

            // Update rating & total_reviews pada parent
            $reviewable = $review->reviewable;
            if ($reviewable) {
                $avgRating = $reviewable->reviews()->avg('rating');
                $totalReviews = $reviewable->reviews()->count();
                
                $reviewable->update([
                    'rating' => round($avgRating, 2),
                    'total_reviews' => $totalReviews
                ]);
            }

            DB::commit();
            return back()->with('success', 'Review submitted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to submit review. Please try again.');
        }
    }

    public function update(Request $request, Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to edit this review.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
            'is_anonymous' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $oldRating = $review->rating;
            
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'is_anonymous' => $request->boolean('is_anonymous', false),
            ]);

            // Update rating pada parent jika rating berubah
            $reviewable = $review->reviewable;
            if ($reviewable && $oldRating != $request->rating) {
                $avgRating = $reviewable->reviews()->avg('rating');
                $reviewable->update([
                    'rating' => round($avgRating, 2)
                ]);
            }

            DB::commit();
            return back()->with('success', 'Review updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update review. Please try again.');
        }
    }

    public function destroy(Review $review)
    {
        // Check if user owns this review
        if ($review->user_id !== Auth::id()) {
            return back()->with('error', 'You are not authorized to delete this review.');
        }

        try {
            DB::beginTransaction();

            $reviewable = $review->reviewable;
            
            $review->delete();

            // Update rating & total_reviews pada parent
            if ($reviewable) {
                $avgRating = $reviewable->reviews()->avg('rating') ?? 0;
                $totalReviews = $reviewable->reviews()->count();
                
                $reviewable->update([
                    'rating' => round($avgRating, 2),
                    'total_reviews' => $totalReviews
                ]);
            }

            DB::commit();
            return back()->with('success', 'Review deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete review. Please try again.');
        }
    }
}
