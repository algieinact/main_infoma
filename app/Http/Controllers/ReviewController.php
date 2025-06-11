<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Residence;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'reviewable_type' => 'required|string',
            'reviewable_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
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

        $review = new Review();
        $review->reviewable_type = $reviewableType;
        $review->reviewable_id = $reviewableId;
        $review->user_id = Auth::id();
        $review->rating = $request->rating;
        $review->comment = $request->comment;
        $review->booking_id = null; // Fix: set booking_id to null
        $review->save();

        // Update rating & total_reviews pada parent
        $reviewable = $review->reviewable;
        $reviewable->rating = $reviewable->reviews()->avg('rating');
        $reviewable->total_reviews = $reviewable->reviews()->count();
        $reviewable->save();

        return back()->with('success', 'Review submitted successfully.');
    }
}
