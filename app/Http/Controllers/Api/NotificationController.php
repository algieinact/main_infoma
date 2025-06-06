<?php

namespace App\Http\Controllers\Api;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationController extends BaseController
{
    /**
     * Display a listing of notifications.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = $request->user()->notifications();

        // Apply filters
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('is_read')) {
            $query->where('is_read', $request->is_read);
        }

        $notifications = $query->latest()
            ->paginate($request->get('per_page', 10));

        return $this->sendResponse($notifications, 'Notifications retrieved successfully');
    }

    /**
     * Mark all notifications as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead(Request $request)
    {
        $request->user()->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return $this->sendResponse(null, 'All notifications marked as read');
    }

    /**
     * Mark a notification as read.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(Request $request, Notification $notification)
    {
        // Check if user is authorized to mark this notification
        if ($notification->user_id !== auth()->id()) {
            return $this->sendForbiddenError('You are not authorized to mark this notification');
        }

        $notification->update([
            'is_read' => true,
            'read_at' => now(),
        ]);

        return $this->sendResponse($notification, 'Notification marked as read');
    }

    /**
     * Remove the specified notification.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, Notification $notification)
    {
        // Check if user is authorized to delete this notification
        if ($notification->user_id !== auth()->id()) {
            return $this->sendForbiddenError('You are not authorized to delete this notification');
        }

        $notification->delete();

        return $this->sendResponse(null, 'Notification deleted successfully');
    }
} 