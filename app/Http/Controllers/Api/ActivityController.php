<?php

namespace App\Http\Controllers\Api;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ActivityController extends BaseController
{
    /**
     * Display a listing of activities.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Activity::query();

        // Apply filters
        if ($request->has('type')) {
            $query->byType($request->type);
        }

        if ($request->has('format')) {
            $query->byFormat($request->format);
        }

        if ($request->has('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        if ($request->has('free')) {
            $query->free();
        }

        if ($request->has('upcoming')) {
            $query->upcoming();
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        // Apply sorting
        $sortField = $request->get('sort_by', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');
        $allowedSortFields = ['created_at', 'price', 'rating', 'start_date', 'current_participants'];
        
        if (in_array($sortField, $allowedSortFields)) {
            $query->orderBy($sortField, $sortDirection);
        }

        // Always show active activities
        $query->active();

        $activities = $query->with(['provider', 'category'])
            ->paginate($request->get('per_page', 10));

        return $this->sendResponse($activities, 'Activities retrieved successfully');
    }

    /**
     * Display the specified activity.
     *
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Activity $activity)
    {
        $activity->load(['provider', 'category', 'reviews.user']);

        return $this->sendResponse($activity, 'Activity retrieved successfully');
    }

    /**
     * Store a newly created activity.
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
            'type' => 'required|string',
            'price' => 'required_if:is_free,false|numeric|min:0',
            'is_free' => 'required|boolean',
            'location' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'format' => 'required|string|in:online,offline,hybrid',
            'meeting_link' => 'required_if:format,online,hybrid|url',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'registration_deadline' => 'required|date|before:start_date',
            'requirements' => 'required|array',
            'benefits' => 'required|array',
            'images' => 'required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'max_participants' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Handle image uploads
        $images = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('activities', 'public');
            $images[] = $path;
        }

        $activity = Activity::create([
            'provider_id' => $request->user()->id,
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'description' => $request->description,
            'type' => $request->type,
            'price' => $request->is_free ? 0 : $request->price,
            'is_free' => $request->is_free,
            'location' => $request->location,
            'city' => $request->city,
            'province' => $request->province,
            'format' => $request->format,
            'meeting_link' => $request->meeting_link,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'registration_deadline' => $request->registration_deadline,
            'requirements' => $request->requirements,
            'benefits' => $request->benefits,
            'images' => $images,
            'max_participants' => $request->max_participants,
            'current_participants' => 0,
            'is_active' => true,
        ]);

        return $this->sendResponse($activity, 'Activity created successfully');
    }

    /**
     * Update the specified activity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Activity $activity)
    {
        // Check if user is the provider
        if ($request->user()->id !== $activity->provider_id) {
            return $this->sendForbiddenError('You are not authorized to update this activity');
        }

        $validator = Validator::make($request->all(), [
            'category_id' => 'sometimes|required|exists:categories,id',
            'title' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'type' => 'sometimes|required|string',
            'price' => 'required_if:is_free,false|numeric|min:0',
            'is_free' => 'sometimes|required|boolean',
            'location' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:255',
            'province' => 'sometimes|required|string|max:255',
            'format' => 'sometimes|required|string|in:online,offline,hybrid',
            'meeting_link' => 'required_if:format,online,hybrid|url',
            'start_date' => 'sometimes|required|date|after:now',
            'end_date' => 'sometimes|required|date|after:start_date',
            'registration_deadline' => 'sometimes|required|date|before:start_date',
            'requirements' => 'sometimes|required|array',
            'benefits' => 'sometimes|required|array',
            'images' => 'sometimes|required|array|min:1',
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'max_participants' => 'sometimes|required|integer|min:1',
            'is_active' => 'sometimes|required|boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Handle image uploads if provided
        if ($request->hasFile('images')) {
            // Delete old images
            foreach ($activity->images as $image) {
                Storage::delete($image);
            }

            $images = [];
            foreach ($request->file('images') as $image) {
                $path = $image->store('activities', 'public');
                $images[] = $path;
            }
            $activity->images = $images;
        }

        // Update other fields
        $activity->fill($request->only([
            'category_id',
            'title',
            'description',
            'type',
            'price',
            'is_free',
            'location',
            'city',
            'province',
            'format',
            'meeting_link',
            'start_date',
            'end_date',
            'registration_deadline',
            'requirements',
            'benefits',
            'max_participants',
            'is_active',
        ]));

        // Update price if is_free changed
        if ($request->has('is_free')) {
            $activity->price = $request->is_free ? 0 : $request->price;
        }

        // Update slug if title changed
        if ($request->has('title')) {
            $activity->slug = Str::slug($request->title);
        }

        $activity->save();

        return $this->sendResponse($activity, 'Activity updated successfully');
    }

    /**
     * Remove the specified activity.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Activity  $activity
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Activity $activity)
    {
        // Check if user is the provider
        if ($request->user()->id !== $activity->provider_id) {
            return $this->sendForbiddenError('You are not authorized to delete this activity');
        }

        // Delete images
        foreach ($activity->images as $image) {
            Storage::delete($image);
        }

        $activity->delete();

        return $this->sendResponse(null, 'Activity deleted successfully');
    }
} 