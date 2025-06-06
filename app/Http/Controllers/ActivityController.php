<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Category;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $query = Activity::with(['provider', 'category'])
            ->where('is_active', 1)
            ->where('registration_deadline', '>', now())
            ->where('current_participants', '<', \DB::raw('max_participants'));

        // Search by title or location
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by format
        if ($request->filled('format')) {
            $query->where('format', $request->format);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by price
        if ($request->filled('is_free')) {
            if ($request->is_free == '1') {
                $query->where('is_free', 1);
            } else {
                $query->where('is_free', 0);
            }
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('end_date', '<=', $request->end_date);
        }

        // Sort
        $sort = $request->get('sort', 'date_asc');
        switch ($sort) {
            case 'date_desc':
                $query->orderBy('start_date', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'featured':
                $query->orderBy('is_featured', 'desc')->orderBy('start_date', 'asc');
                break;
            default:
                $query->orderBy('start_date', 'asc');
        }

        $activities = $query->paginate(12);

        // Get filter options
        $categories = Category::where('type', 'activity')->where('is_active', 1)->get();
        $cities = Activity::where('is_active', 1)->distinct()->pluck('city')->sort();

        return view('activities.index', compact('activities', 'categories', 'cities'));
    }

    public function show($slug)
    {
        $activity = Activity::with(['provider', 'category', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        // Check if registration is still open
        $registrationOpen = $activity->registration_deadline > now() && 
                           $activity->current_participants < $activity->max_participants;

        // Log user activity
        if (Auth::check()) {
            UserActivity::create([
                'user_id' => Auth::id(),
                'activityable_id' => $activity->id,
                'activityable_type' => Activity::class,
                'action' => 'view',
                'metadata' => [
                    'title' => $activity->title,
                    'viewed_at' => now()
                ]
            ]);
        }

        // Get similar activities
        $similarActivities = Activity::with(['provider', 'category'])
            ->where('id', '!=', $activity->id)
            ->where('category_id', $activity->category_id)
            ->where('is_active', 1)
            ->where('registration_deadline', '>', now())
            ->where('current_participants', '<', \DB::raw('max_participants'))
            ->orderBy('start_date', 'asc')
            ->limit(4)
            ->get();

        // Calculate average rating
        $averageRating = $activity->reviews()->avg('rating') ?? 0;

        return view('activities.show', compact('activity', 'similarActivities', 'averageRating', 'registrationOpen'));
    }

    public function getCities()
    {
        $cities = Activity::where('is_active', 1)
            ->distinct()
            ->orderBy('city')
            ->pluck('city');
        
        return response()->json($cities);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (!$query) {
            return response()->json([]);
        }

        $activities = Activity::where('is_active', 1)
            ->where('registration_deadline', '>', now())
            ->where('current_participants', '<', \DB::raw('max_participants'))
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('city', 'like', "%{$query}%")
                  ->orWhere('location', 'like', "%{$query}%");
            })
            ->select('id', 'title', 'slug', 'city', 'price', 'is_free', 'start_date')
            ->limit(10)
            ->get();

        return response()->json($activities);
    }

    public function store(Request $request)
    {
        // Konversi requirements dan benefits ke array jika berupa string
        $request->merge([
            'requirements' => is_array($request->requirements) ? $request->requirements : array_filter(array_map('trim', explode(',', $request->requirements))),
            'benefits' => is_array($request->benefits) ? $request->benefits : array_filter(array_map('trim', explode(',', $request->benefits))),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_free' => 'required|boolean',
            'location' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'format' => 'required|string',
            'meeting_link' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:start_date',
            'requirements' => 'required|array',
            'benefits' => 'required|array',
            'max_participants' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['provider_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['current_participants'] = 0;
        $validated['is_active'] = true;
        $validated['is_featured'] = false;
        $validated['rating'] = 0;
        $validated['total_reviews'] = 0;

        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('activities', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $activity = Activity::create($validated);

        return redirect()->route('provider.activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function update(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        
        // Check if user is authorized to update this activity
        if ($activity->provider_id !== auth()->id()) {
            abort(403);
        }

        // Konversi requirements dan benefits ke array jika berupa string
        $request->merge([
            'requirements' => is_array($request->requirements) ? $request->requirements : array_filter(array_map('trim', explode(',', $request->requirements))),
            'benefits' => is_array($request->benefits) ? $request->benefits : array_filter(array_map('trim', explode(',', $request->benefits))),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'is_free' => 'required|boolean',
            'location' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'format' => 'required|string',
            'meeting_link' => 'nullable|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:start_date',
            'requirements' => 'required|array',
            'benefits' => 'required|array',
            'max_participants' => 'required|integer|min:1',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validated['slug'] = Str::slug($validated['title']);

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($activity->images) {
                foreach ($activity->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('activities', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $activity->update($validated);

        return redirect()->route('provider.activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    public function create()
    {
        $categories = Category::where('type', 'activity')->where('is_active', 1)->get();
        return view('activities.create', compact('categories'));
    }

    public function edit($id)
    {
        $activity = Activity::findOrFail($id);
        if ($activity->provider_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::where('type', 'activity')->where('is_active', 1)->get();
        return view('activities.edit', compact('activity', 'categories'));
    }

    public function destroy($id)
    {
        $activity = Activity::findOrFail($id);
        if ($activity->provider_id !== Auth::id()) {
            abort(403);
        }
        // Hapus gambar jika ada
        if ($activity->images) {
            foreach ($activity->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $activity->delete();
        return redirect()->route('provider.dashboard')->with('success', 'Activity berhasil dihapus.');
    }

    public function providerIndex()
    {
        $activities = Activity::with(['category', 'bookings'])
            ->where('provider_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::where('type', 'activity')->where('is_active', 1)->get();
        $cities = Activity::where('is_active', 1)->distinct()->pluck('city')->sort();

        return view('provider.manage.activity', [
            'activities' => $activities,
            'isProvider' => true,
            'categories' => $categories,
            'cities' => $cities
        ]);
    }
}