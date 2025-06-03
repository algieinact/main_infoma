<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Booking;

class DashboardController extends Controller
{
    public function index()
    {
        // Get all bookings for the logged-in user with related data
        $bookings = Booking::with(['bookable', 'transactions'])
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get booking counts by status
        $bookingCounts = [
            'pending' => $bookings->where('status', Booking::STATUS_PENDING)->count(),
            'confirmed' => $bookings->where('status', Booking::STATUS_CONFIRMED)->count(),
            'completed' => $bookings->where('status', Booking::STATUS_COMPLETED)->count(),
            'cancelled' => $bookings->where('status', Booking::STATUS_CANCELLED)->count(),
        ];

        return view('dashboard', [
            'user' => Auth::user(),
            'bookings' => $bookings,
            'bookingCounts' => $bookingCounts,
        ]);
    }
}