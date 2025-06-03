<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller as BaseController;

class ProviderDashboardController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        $bookings = Booking::with(['user', 'bookable'])
            ->whereHas('bookable', function ($query) {
                $query->where('provider_id', Auth::id());
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('provider.dashboard', compact('bookings'));
    }

    public function showBooking(Booking $booking)
    {
        // Check if booking belongs to provider
        if ($booking->bookable->provider_id !== Auth::id()) {
            abort(403);
        }

        $booking->load(['user', 'bookable', 'transactions']);

        return view('provider.bookings.show', compact('booking'));
    }

    public function approveBooking(Request $request, Booking $booking)
    {
        // Check if booking belongs to provider
        if ($booking->bookable->provider_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking is in correct state
        if ($booking->status !== 'waiting_provider_approval') {
            return back()->withErrors(['error' => 'Booking tidak dapat disetujui dalam status ini.']);
        }

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'provider_approved',
                'notes' => $request->notes ?? null
            ]);

            // Create notification for user
            $booking->user->notifications()->create([
                'title' => 'Booking Disetujui',
                'message' => 'Booking ' . $booking->booking_code . ' telah disetujui oleh penyedia.',
                'type' => 'booking_approved',
                'data' => json_encode([
                    'booking_id' => $booking->id,
                ]),
                'action_url' => route('bookings.show', $booking),
            ]);

            DB::commit();
            return back()->with('success', 'Booking berhasil disetujui.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function rejectBooking(Request $request, Booking $booking)
    {
        // Check if booking belongs to provider
        if ($booking->bookable->provider_id !== Auth::id()) {
            abort(403);
        }

        // Check if booking is in correct state
        if ($booking->status !== 'waiting_provider_approval') {
            return back()->withErrors(['error' => 'Booking tidak dapat ditolak dalam status ini.']);
        }

        $validator = $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        DB::beginTransaction();
        try {
            $booking->update([
                'status' => 'provider_rejected',
                'notes' => $request->rejection_reason
            ]);

            // Create notification for user
            $booking->user->notifications()->create([
                'title' => 'Booking Ditolak',
                'message' => 'Booking ' . $booking->booking_code . ' telah ditolak oleh penyedia.',
                'type' => 'booking_rejected',
                'data' => json_encode([
                    'booking_id' => $booking->id,
                    'reason' => $request->rejection_reason
                ]),
                'action_url' => route('bookings.show', $booking),
            ]);

            DB::commit();
            return back()->with('success', 'Booking berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}