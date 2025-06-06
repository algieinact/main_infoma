<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        // Mark as read if not already read
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }
        
        return view('notifications.show', compact('notification'));
    }

    public function markAllAsRead()
    {
        auth()->user()->notifications()
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return back()->with('success', 'Semua notifikasi telah ditandai sebagai telah dibaca');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        
        if (!$notification->read_at) {
            $notification->update(['read_at' => now()]);
        }

        return back()->with('success', 'Notifikasi telah ditandai sebagai telah dibaca');
    }
} 