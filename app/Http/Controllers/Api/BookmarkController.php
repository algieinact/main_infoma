<?php

namespace App\Http\Controllers\Api;

use App\Models\Bookmark;
use App\Models\Residence;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends BaseController
{
    /**
     * Display a listing of bookmarks.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->user()->bookmarks();

        // Apply filters
        if ($request->has('type')) {
            $query->where('bookmarkable_type', $request->type === 'residence' ? Residence::class : Activity::class);
        }

        $bookmarks = $query->with(['bookmarkable'])
            ->latest()
            ->paginate($request->get('per_page', 10));

        return $this->sendResponse($bookmarks, 'Bookmarks retrieved successfully');
    }

    /**
     * Store a newly created bookmark.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bookmarkable_type' => 'required|string|in:residence,activity',
            'bookmarkable_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Get the bookmarkable model
        $bookmarkableType = $request->bookmarkable_type === 'residence' ? Residence::class : Activity::class;
        $bookmarkable = $bookmarkableType::findOrFail($request->bookmarkable_id);

        // Check if already bookmarked
        if ($bookmarkable->isBookmarkedBy($request->user())) {
            return $this->sendError('This item is already bookmarked');
        }

        $bookmark = Bookmark::create([
            'user_id' => $request->user()->id,
            'bookmarkable_type' => $bookmarkableType,
            'bookmarkable_id' => $bookmarkable->id,
        ]);

        return $this->sendResponse($bookmark, 'Bookmark created successfully');
    }

    /**
     * Remove the specified bookmark.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bookmark  $bookmark
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Bookmark $bookmark)
    {
        // Check if user is authorized to delete this bookmark
        if ($bookmark->user_id !== auth()->id()) {
            return $this->sendForbiddenError('You are not authorized to delete this bookmark');
        }

        $bookmark->delete();

        return $this->sendResponse(null, 'Bookmark deleted successfully');
    }
} 