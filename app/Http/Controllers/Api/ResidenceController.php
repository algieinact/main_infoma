<?php

namespace App\Http\Controllers\Api;

use App\Models\Residence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ResidenceController extends BaseController
{
    /**
     * Display a listing of residences.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Residence::query();

        // Apply filters
        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('city')) {
            $query->byCity($request->city);
        }

        if ($request->has(['min_price', 'max_price'])) {
            $query->byPriceRange($request->min_price, $request->max_price);
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        // Apply sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $allowedSortFields = ['created_at', 'price', 'rating', 'available_rooms'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Always show active residences
        $query->active();

        $residences = $query->with(['provider', 'category'])
            ->paginate($request->get('per_page', 10));

        return $this->sendResponse($residences, 'Residences retrieved successfully');
    }

    /**
     * Display the specified residence.
     *
     * @param  \App\Models\Residence  $residence
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Residence $residence)
    {
        $residence->load(['provider', 'category', 'reviews.user']);

        return $this->sendResponse($residence, 'Residence retrieved successfully');
    }

    /**
     * Store a newly created residence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:apartment,dormitory,house',
            'price' => 'required|numeric|min:0',
            'price_period' => 'required|string|in:day,week,month,year',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'facilities' => 'required|array',
            'rules' => 'required|array',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'total_rooms' => 'required|integer|min:1',
            'available_rooms' => 'required|integer|min:0',
            'gender_type' => 'required|string|in:male,female,mixed',
            'available_from' => 'required|date',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Handle image uploads
        $images = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('residences', 'public');
            $images[] = $path;
        }

        $residence = Residence::create([
            'provider_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'type' => $request->type,
            'price' => $request->price,
            'price_period' => $request->price_period,
            'address' => $request->address,
            'city' => $request->city,
            'province' => $request->province,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'facilities' => $request->facilities,
            'rules' => $request->rules,
            'images' => $images,
            'total_rooms' => $request->total_rooms,
            'available_rooms' => $request->available_rooms,
            'gender_type' => $request->gender_type,
            'available_from' => $request->available_from,
            'is_active' => true,
        ]);

        return $this->sendResponse($residence, 'Residence created successfully');
    }

    /**
     * Update the specified residence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Residence  $residence
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Residence $residence)
    {
        // Check if user is the provider
        if ($request->user()->id !== $residence->provider_id) {
            return $this->sendForbiddenError('You are not authorized to update this residence');
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|string|in:apartment,dormitory,house',
            'price' => 'sometimes|required|numeric|min:0',
            'price_period' => 'sometimes|required|string|in:day,week,month,year',
            'address' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'province' => 'sometimes|required|string|max:255',
            'latitude' => 'sometimes|required|numeric',
            'longitude' => 'sometimes|required|numeric',
            'facilities' => 'sometimes|required|array',
            'rules' => 'sometimes|required|array',
            'images' => 'sometimes|required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'total_rooms' => 'sometimes|required|integer|min:1',
            'available_rooms' => 'sometimes|required|integer|min:0',
            'gender_type' => 'sometimes|required|string|in:male,female,mixed',
            'available_from' => 'sometimes|required|date',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Handle image uploads if provided
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($residence->images as $image) {
                Storage::delete($image);
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('residences', 'public');
                $images[] = $path;
            }
            $residence->images = $images;
        }

        // Update other fields
        $residence->fill($request->only([
            'category_id',
            'title',
            'description',
            'type',
            'price',
            'price_period',
            'address',
            'city',
            'province',
            'latitude',
            'longitude',
            'facilities',
            'rules',
            'total_rooms',
            'available_rooms',
            'gender_type',
            'available_from',
            'is_active',
        ]));

        // Update slug if title changed
        if ($request->has('title')) {
            $residence->slug = Str::slug($request->title);
        }

        $residence->save();

        return $this->sendResponse($residence, 'Residence updated successfully');
    }

    /**
     * Remove the specified residence.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Residence  $residence
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Residence $residence)
    {
        // Check if user is the provider
        if ($request->user()->id !== $residence->provider_id) {
            return $this->sendForbiddenError('You are not authorized to delete this residence');
        }

        // Delete images
        foreach ($residence->images as $image) {
            Storage::delete($image);
        }

        $residence->delete();

        return $this->sendResponse(null, 'Residence deleted successfully');
    }
} 