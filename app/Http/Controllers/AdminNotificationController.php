<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminNotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');
        
        $query = Notification::where('user_id', $user->id)
            ->where('type', 'admin')
            ->orderBy('created_at', 'desc');
        
        // Filter by type if specified
        if ($type === 'unread') {
            $query->where('is_read', false);
        } elseif ($type === 'read') {
            $query->where('is_read', true);
        } elseif ($type === 'user_actions') {
            $query->whereIn('action', [
                Notification::ACTION_USER_BOOKING_CREATED,
                Notification::ACTION_USER_PAYMENT_SUBMITTED,
                Notification::ACTION_USER_BOOKING_CANCELLED,
                Notification::ACTION_USER_CONTACT_MESSAGE,
            ]);
        } elseif ($type === 'staff_actions') {
            $query->whereIn('action', [
                Notification::ACTION_STAFF_BOOKING_ACKNOWLEDGED,
                Notification::ACTION_STAFF_BOOKING_APPROVED,
                Notification::ACTION_STAFF_BOOKING_REJECTED,
                Notification::ACTION_STAFF_PAGE_CREATED,
                Notification::ACTION_STAFF_ACTIVITY_CREATED,
            ]);
        } elseif ($type === 'priest_actions') {
            $query->whereIn('action', [
                Notification::ACTION_PRIEST_PROFILE_EDITED,
                Notification::ACTION_PRIEST_LEAVE_FILED,
            ]);
        }
        
        $notifications = $query->paginate(20);
        
        // Get counts
        $counts = [
            'all' => Notification::where('user_id', $user->id)->where('type', 'admin')->count(),
            'unread' => Notification::where('user_id', $user->id)->where('type', 'admin')->where('is_read', false)->count(),
            'read' => Notification::where('user_id', $user->id)->where('type', 'admin')->where('is_read', true)->count(),
            'user_actions' => Notification::where('user_id', $user->id)
                ->where('type', 'admin')
                ->whereIn('action', [
                    Notification::ACTION_USER_BOOKING_CREATED,
                    Notification::ACTION_USER_PAYMENT_SUBMITTED,
                    Notification::ACTION_USER_BOOKING_CANCELLED,
                    Notification::ACTION_USER_CONTACT_MESSAGE,
                ])->count(),
            'staff_actions' => Notification::where('user_id', $user->id)
                ->where('type', 'admin')
                ->whereIn('action', [
                    Notification::ACTION_STAFF_BOOKING_ACKNOWLEDGED,
                    Notification::ACTION_STAFF_BOOKING_APPROVED,
                    Notification::ACTION_STAFF_BOOKING_REJECTED,
                    Notification::ACTION_STAFF_PAGE_CREATED,
                    Notification::ACTION_STAFF_ACTIVITY_CREATED,
                ])->count(),
            'priest_actions' => Notification::where('user_id', $user->id)
                ->where('type', 'admin')
                ->whereIn('action', [
                    Notification::ACTION_PRIEST_PROFILE_EDITED,
                    Notification::ACTION_PRIEST_LEAVE_FILED,
                ])->count(),
        ];
        
        return view('admin.notifications.index', compact('notifications', 'type', 'counts', 'user'));
    }
    
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'exists:notifications,id'
        ]);
        
        $user = Auth::user();
        $notificationIds = $request->notification_ids;
        
        // Ensure user can only mark their own notifications as read
        $notificationIds = Notification::whereIn('id', $notificationIds)
            ->where('user_id', $user->id)
            ->where('type', 'admin')
            ->pluck('id')
            ->toArray();
        
        if (!empty($notificationIds)) {
            NotificationService::markAsRead($notificationIds, $user->id);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ]);
    }
    
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->where('type', 'admin')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
    
    public function getUnreadCount(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit');
        
        $count = Notification::where('user_id', $user->id)
            ->where('type', 'admin')
            ->where('is_read', false)
            ->count();
        
        $response = ['count' => $count];
        
        // If limit is provided, also return recent notifications
        if ($limit) {
            $notifications = Notification::where('user_id', $user->id)
                ->where('type', 'admin')
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($notification) {
                    $bookingData = null;
                    if ($notification->booking_id) {
                        try {
                            $booking = \App\Models\Booking::with(['service', 'user'])->find($notification->booking_id);
                            if ($booking) {
                                $bookingData = [
                                    'id' => $booking->id,
                                    'service_name' => $booking->service ? $booking->service->name : 'Unknown Service',
                                    'user_name' => $booking->user ? $booking->user->name : 'Unknown User',
                                ];
                            }
                        } catch (\Exception $e) {
                            // If there's an error loading booking data, just skip it
                        }
                    }
                    
                    return [
                        'id' => $notification->id,
                        'message' => $notification->message,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'action' => $notification->action,
                        'data' => $notification->data,
                        'booking' => $bookingData,
                    ];
                });
            
            $response['notifications'] = $notifications;
        }
        
        return response()->json($response);
    }
    
    public function delete(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'exists:notifications,id'
        ]);
        
        $user = Auth::user();
        $notificationIds = $request->notification_ids;
        
        // Ensure user can only delete their own notifications
        $notificationIds = Notification::whereIn('id', $notificationIds)
            ->where('user_id', $user->id)
            ->where('type', 'admin')
            ->pluck('id')
            ->toArray();
        
        if (!empty($notificationIds)) {
            Notification::whereIn('id', $notificationIds)->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications deleted successfully'
        ]);
    }
}
