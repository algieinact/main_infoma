<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Auth::user()->bookmarks()
            ->with('bookmarkable')
            ->latest()
            ->paginate(12);

        return view('bookmarks.index', compact('bookmarks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bookmarkable_type' => 'required|in:App\Models\Residence,App\Models\Activity',
            'bookmarkable_id' => 'required|exists:' . $request->bookmarkable_type . ',id'
        ]);

        $bookmark = Auth::user()->bookmarks()->create([
            'bookmarkable_type' => $request->bookmarkable_type,
            'bookmarkable_id' => $request->bookmarkable_id
        ]);

        if ($request->ajax()) {
            return response()->json([
                'message' => 'Bookmarked successfully',
                'bookmark' => $bookmark
            ]);
        }

        return back()->with('success', 'Bookmarked successfully');
    }

    public function destroy(Bookmark $bookmark)
    {
        if ($bookmark->user_id !== Auth::id()) {
            abort(403);
        }

        $bookmark->delete();

        if (request()->ajax()) {
            return response()->json([
                'message' => 'Bookmark removed successfully'
            ]);
        }

        return back()->with('success', 'Bookmark removed successfully');
    }
} 