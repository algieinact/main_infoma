<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Residence;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Routing\Controller as BaseController;

class HomeController extends BaseController
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        // Get featured residences
        $featuredResidences = Residence::with(['provider', 'category'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->where('available_rooms', '>', 0)
            ->orderBy('rating', 'desc')
            ->limit(6)
            ->get();

        // Get featured activities
        $featuredActivities = Activity::with(['provider', 'category'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->where('registration_deadline', '>', now())
            ->where('current_participants', '<', \DB::raw('max_participants'))
            ->orderBy('start_date', 'asc')
            ->limit(6)
            ->get();

        // Get recent residences
        $recentResidences = Residence::with(['provider', 'category'])
            ->where('is_active', 1)
            ->where('available_rooms', '>', 0)
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Get recent activities
        $recentActivities = Activity::with(['provider', 'category'])
            ->where('is_active', 1)
            ->where('registration_deadline', '>', now())
            ->where('current_participants', '<', \DB::raw('max_participants'))
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Get user's recent activity history
        $recentHistory = UserActivity::with(['activityable'])
            ->where('user_id', Auth::id())
            ->where('action', 'view')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('home', compact(
            'featuredResidences',
            'featuredActivities',
            'recentResidences',
            'recentActivities',
            'recentHistory'
        ));
    }

    public function landing()
    {
        // Get some sample data for landing page
        $totalResidences = Residence::where('is_active', 1)->count();
        $totalActivities = Activity::where('is_active', 1)->count();
        
        $sampleResidences = Residence::with(['provider', 'category'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->orderBy('rating', 'desc')
            ->limit(3)
            ->get();

        $sampleActivities = Activity::with(['provider', 'category'])
            ->where('is_active', 1)
            ->where('is_featured', 1)
            ->where('registration_deadline', '>', now())
            ->orderBy('start_date', 'asc')
            ->limit(3)
            ->get();

        return view('landing', compact(
            'totalResidences',
            'totalActivities',
            'sampleResidences',
            'sampleActivities'
        ));
    }
}