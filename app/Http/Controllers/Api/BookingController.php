<?php

namespace App\Http\Controllers\Api;

use App\Models\Booking;
use App\Models\Residence;
use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BookingController extends BaseController
{
    /**
     * Display a listing of bookings.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->user()->bookings();

        // Apply filters
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        if ($request->has('type')) {
            $query->where('bookable_type', $request->type === 'residence' ? Residence::class : Activity::class);
        }

        $bookings = $query->with(['bookable', 'user'])
            ->latest()
            ->paginate($request->get('per_page', 10));

        return $this->sendResponse($bookings, 'Bookings retrieved successfully');
    }

    /**
     * Display the specified booking.
     *
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Booking $booking)
    {
        // Check if user is authorized to view this booking
        if ($booking->user_id !== auth()->id() && !auth()->user()->isProvider()) {
            return $this->sendForbiddenError('You are not authorized to view this booking');
        }

        $booking->load(['bookable', 'user', 'transactions', 'reviews']);

        return $this->sendResponse($booking, 'Booking retrieved successfully');
    }

    /**
     * Store a newly created booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'bookable_type' => 'required|string|in:residence,activity',
            'bookable_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'booking_data' => 'required|array',
            'files' => 'sometimes|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Get the bookable model
        $bookableType = $request->bookable_type === 'residence' ? Residence::class : Activity::class;
        $bookable = $bookableType::findOrFail($request->bookable_id);

        // Check if bookable is available
        if (!$bookable->isAvailable()) {
            return $this->sendError('The selected item is not available');
        }

        // Handle file uploads
        $files = [];
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('bookings', 'public');
                $files[] = $path;
            }
        }

        // Calculate total amount
        $totalAmount = $this->calculateTotalAmount($bookable, $request->start_date, $request->end_date);

        $booking = Booking::create([
            'booking_code' => 'BK' . Str::random(8),
            'user_id' => $request->user()->id,
            'bookable_type' => $bookableType,
            'bookable_id' => $bookable->id,
            'booking_data' => $request->booking_data,
            'files' => $files,
            'status' => Booking::STATUS_PENDING,
            'booking_date' => now(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_amount' => $totalAmount,
            'discount_amount' => 0,
            'final_amount' => $totalAmount,
            'notes' => $request->notes,
        ]);

        // Update availability
        if ($bookable instanceof Residence) {
            $bookable->decreaseAvailability();
        } else {
            $bookable->increaseParticipants();
        }

        return $this->sendResponse($booking, 'Booking created successfully');
    }

    /**
     * Update the specified booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Booking $booking)
    {
        // Check if user is authorized to update this booking
        if ($booking->user_id !== auth()->id() && !auth()->user()->isProvider()) {
            return $this->sendForbiddenError('You are not authorized to update this booking');
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'booking_data' => 'sometimes|required|array',
            'files' => 'sometimes|array',
            'files.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
            'notes' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Handle file uploads if provided
        if ($request->hasFile('files')) {
            // Delete old files
            foreach ($booking->files as $file) {
                Storage::delete($file);
            }

            $files = [];
            foreach ($request->file('files') as $file) {
                $path = $file->store('bookings', 'public');
                $files[] = $path;
            }
            $booking->files = $files;
        }

        // Update other fields
        $booking->fill($request->only([
            'start_date',
            'end_date',
            'booking_data',
            'notes',
        ]));

        // Recalculate total amount if dates changed
        if ($request->has(['start_date', 'end_date'])) {
            $totalAmount = $this->calculateTotalAmount($booking->bookable, $request->start_date, $request->end_date);
            $booking->total_amount = $totalAmount;
            $booking->final_amount = $totalAmount - $booking->discount_amount;
        }

        $booking->save();

        return $this->sendResponse($booking, 'Booking updated successfully');
    }

    /**
     * Remove the specified booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Booking $booking)
    {
        // Check if user is authorized to delete this booking
        if ($booking->user_id !== auth()->id() && !auth()->user()->isProvider()) {
            return $this->sendForbiddenError('You are not authorized to delete this booking');
        }

        // Delete files
        foreach ($booking->files as $file) {
            Storage::delete($file);
        }

        // Update availability
        if ($booking->bookable instanceof Residence) {
            $booking->bookable->increaseAvailability();
        } else {
            $booking->bookable->decreaseParticipants();
        }

        $booking->delete();

        return $this->sendResponse(null, 'Booking deleted successfully');
    }

    /**
     * Cancel the specified booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancel(Request $request, Booking $booking)
    {
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Check if booking can be cancelled
        if (!$booking->isPending() && !$booking->isConfirmed()) {
            return $this->sendError('This booking cannot be cancelled');
        }

        $booking->cancel($request->cancellation_reason);

        // Update availability
        if ($booking->bookable instanceof Residence) {
            $booking->bookable->increaseAvailability();
        } else {
            $booking->bookable->decreaseParticipants();
        }

        return $this->sendResponse($booking, 'Booking cancelled successfully');
    }

    /**
     * Confirm the specified booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function confirm(Request $request, Booking $booking)
    {
        // Check if user is the provider
        if ($request->user()->id !== $booking->bookable->provider_id) {
            return $this->sendForbiddenError('You are not authorized to confirm this booking');
        }

        // Check if booking can be confirmed
        if (!$booking->isPending()) {
            return $this->sendError('This booking cannot be confirmed');
        }

        $booking->confirm();

        return $this->sendResponse($booking, 'Booking confirmed successfully');
    }

    /**
     * Reject the specified booking.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Booking  $booking
     * @return \Illuminate\Http\JsonResponse
     */
    public function reject(Request $request, Booking $booking)
    {
        $validator = Validator::make($request->all(), [
            'rejection_reason' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors()->toArray());
        }

        // Check if user is the provider
        if ($request->user()->id !== $booking->bookable->provider_id) {
            return $this->sendForbiddenError('You are not authorized to reject this booking');
        }

        // Check if booking can be rejected
        if (!$booking->isPending()) {
            return $this->sendError('This booking cannot be rejected');
        }

        $booking->reject();

        // Update availability
        if ($booking->bookable instanceof Residence) {
            $booking->bookable->increaseAvailability();
        } else {
            $booking->bookable->decreaseParticipants();
        }

        return $this->sendResponse($booking, 'Booking rejected successfully');
    }

    /**
     * Calculate total amount for a booking.
     *
     * @param  mixed  $bookable
     * @param  string  $startDate
     * @param  string  $endDate
     * @return float
     */
    private function calculateTotalAmount($bookable, $startDate, $endDate)
    {
        $start = new \DateTime($startDate);
        $end = new \DateTime($endDate);
        $interval = $start->diff($end);

        if ($bookable instanceof Residence) {
            $days = $interval->days;
            switch ($bookable->price_period) {
                case 'day':
                    return $bookable->price * $days;
                case 'week':
                    return $bookable->price * ceil($days / 7);
                case 'month':
                    return $bookable->price * ceil($days / 30);
                case 'year':
                    return $bookable->price * ceil($days / 365);
                default:
                    return $bookable->price;
            }
        } else {
            return $bookable->price;
        }
    }
} 