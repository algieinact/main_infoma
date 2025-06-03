<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Residence;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ResidenceController extends Controller
{
    public function index(Request $request)
    {
        $query = Residence::with(['provider', 'category'])
            ->where('is_active', 1)
            ->where('available_rooms', '>', 0);

        // Search by title or city
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
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

        // Filter by gender type
        if ($request->filled('gender_type')) {
            $query->where('gender_type', $request->gender_type);
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
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
                $query->orderBy('is_featured', 'desc')->orderBy('rating', 'desc');
                break;
            default:
                $query->orderBy('created_at', 'desc');
        }

        $residences = $query->paginate(12);

        // Get filter options
        $categories = Category::where('type', 'residence')->where('is_active', 1)->get();
        $cities = Residence::where('is_active', 1)->distinct()->pluck('city')->sort();

        return view('residences.index', compact('residences', 'categories', 'cities'));
    }

    public function show($slug)
    {
        $residence = Residence::with(['provider', 'category', 'reviews.user'])
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        // Log user activity
        if (Auth::check()) {
            UserActivity::create([
                'user_id' => Auth::id(),
                'activityable_id' => $residence->id,
                'activityable_type' => Residence::class,
                'action' => 'view',
                'metadata' => [
                    'title' => $residence->title,
                    'viewed_at' => now()
                ]
            ]);
        }

        // Get similar residences
        $similarResidences = Residence::with(['provider', 'category'])
            ->where('id', '!=', $residence->id)
            ->where('category_id', $residence->category_id)
            ->where('city', $residence->city)
            ->where('is_active', 1)
            ->where('available_rooms', '>', 0)
            ->orderBy('rating', 'desc')
            ->limit(4)
            ->get();

        // Calculate average rating
        $averageRating = $residence->reviews()->avg('rating') ?? 0;

        return view('residences.show', compact('residence', 'similarResidences', 'averageRating'));
    }

    public function getCities()
    {
        $cities = Residence::where('is_active', 1)
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

        $residences = Residence::where('is_active', 1)
            ->where('available_rooms', '>', 0)
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('city', 'like', "%{$query}%")
                  ->orWhere('address', 'like', "%{$query}%");
            })
            ->select('id', 'title', 'slug', 'city', 'price', 'price_period')
            ->limit(10)
            ->get();

        return response()->json($residences);
    }

    public function store(Request $request)
    {
        $input = $request->all();
        // Konversi input string menjadi array jika perlu
        if (isset($input['facilities']) && is_string($input['facilities'])) {
            $input['facilities'] = array_filter(array_map('trim', explode(',', $input['facilities'])));
        }
        if (isset($input['rules']) && is_string($input['rules'])) {
            $input['rules'] = array_filter(array_map('trim', explode(',', $input['rules'])));
        }
        $validated = \Validator::make($input, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'price_period' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'facilities' => 'required|array',
            'rules' => 'required|array',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'gender_type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ])->validate();
        $validated['provider_id'] = Auth::id();
        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = true;
        $validated['is_featured'] = false;
        $validated['rating'] = 0;
        $validated['total_reviews'] = 0;
        // Handle image uploads
        if ($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('residences', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }
        $residence = Residence::create($validated);
        return redirect()->route('provider.dashboard')
            ->with('success', 'Residence created successfully.');
    }

    public function update(Request $request, $id)
    {
        $residence = Residence::findOrFail($id);
        
        // Check if user is authorized to update this residence
        if ($residence->provider_id !== Auth::id()) {
            abort(403);
        }
        $input = $request->all();
        // Konversi input string menjadi array jika perlu
        if (isset($input['facilities']) && is_string($input['facilities'])) {
            $input['facilities'] = array_filter(array_map('trim', explode(',', $input['facilities'])));
        }
        if (isset($input['rules']) && is_string($input['rules'])) {
            $input['rules'] = array_filter(array_map('trim', explode(',', $input['rules'])));
        }
        $validated = \Validator::make($input, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string',
            'price' => 'required|numeric|min:0',
            'price_period' => 'required|string',
            'address' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'facilities' => 'required|array',
            'rules' => 'required|array',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'gender_type' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ])->validate();
        $validated['slug'] = Str::slug($validated['title']);

        // Handle image uploads
        if ($request->hasFile('images')) {
            // Delete old images
            if ($residence->images) {
                foreach ($residence->images as $oldImage) {
                    Storage::disk('public')->delete($oldImage);
                }
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('residences', 'public');
                $images[] = $path;
            }
            $validated['images'] = $images;
        }

        $residence->update($validated);

        return redirect()->route('provider.dashboard')
            ->with('success', 'Residence updated successfully.');
    }

    public function create()
    {
        $categories = Category::where('type', 'residence')->where('is_active', 1)->get();
        return view('residences.create', compact('categories'));
    }

    public function edit($id)
    {
        $residence = Residence::findOrFail($id);
        if ($residence->provider_id !== Auth::id()) {
            abort(403);
        }
        $categories = Category::where('type', 'residence')->where('is_active', 1)->get();
        return view('residences.edit', compact('residence', 'categories'));
    }

    public function destroy($id)
    {
        $residence = Residence::findOrFail($id);
        if ($residence->provider_id !== Auth::id()) {
            abort(403);
        }
        // Hapus gambar jika ada
        if ($residence->images) {
            foreach ($residence->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $residence->delete();
        return redirect()->route('provider.dashboard')->with('success', 'Residence berhasil dihapus.');
    }

    public function providerIndex()
    {
        $residences = Residence::with(['category', 'bookings'])
            ->where('provider_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $categories = Category::where('type', 'residence')->where('is_active', 1)->get();
        $cities = Residence::where('is_active', 1)->distinct()->pluck('city')->sort();

        return view('provider.manage.residence', [
            'residences' => $residences,
            'isProvider' => true,
            'categories' => $categories,
            'cities' => $cities
        ]);
    }
}