<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserNotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');
        
        $query = Notification::with(['booking.service'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc');
        
        // Filter by type if specified
        if ($type === 'unread') {
            $query->where('is_read', false);
        } elseif ($type === 'read') {
            $query->where('is_read', true);
        }
        
        $notifications = $query->paginate(20);
        
        // Get counts
        $counts = [
            'all' => Notification::where('user_id', $user->id)->count(),
            'unread' => Notification::where('user_id', $user->id)->where('is_read', false)->count(),
            'read' => Notification::where('user_id', $user->id)->where('is_read', true)->count(),
        ];
        
        return view('user.notifications.index', compact('notifications', 'type', 'counts', 'user'));
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
        
        NotificationService::markAllAsRead($user->id);
        
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
            ->where('is_read', false)
            ->count();
        
        $response = ['count' => $count];
        
        // If limit is provided, also return recent notifications
        if ($limit) {
            $notifications = Notification::with(['booking.service'])
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'message' => $notification->message,
                        'is_read' => $notification->is_read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'action' => $notification->action,
                        'booking' => $notification->booking ? [
                            'id' => $notification->booking->id,
                            'service_name' => $notification->booking->service->name,
                        ] : null,
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
