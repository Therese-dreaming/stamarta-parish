<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PriestNotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $type = $request->get('type', 'all');
        
        $query = Notification::where('user_id', $user->id)
            ->where('type', 'priest')
            ->orderBy('created_at', 'desc');
        
        if ($type === 'unread') {
            $query->where('is_read', false);
        } elseif ($type === 'read') {
            $query->where('is_read', true);
        } elseif ($type === 'leave_actions') {
            $query->whereIn('action', [
                Notification::ACTION_PRIEST_LEAVE_APPROVED,
                Notification::ACTION_PRIEST_LEAVE_REJECTED,
            ]);
        } elseif ($type === 'booking_assignments') {
            $query->where('action', Notification::ACTION_PRIEST_BOOKING_ASSIGNED);
        }
        
        $notifications = $query->paginate(20);
        
        // Get counts
        $counts = [
            'all' => Notification::where('user_id', $user->id)
                ->where('type', 'priest')
                ->count(),
            'unread' => Notification::where('user_id', $user->id)
                ->where('type', 'priest')
                ->where('is_read', false)
                ->count(),
            'read' => Notification::where('user_id', $user->id)
                ->where('type', 'priest')
                ->where('is_read', true)
                ->count(),
            'leave_actions' => Notification::where('user_id', $user->id)
                ->where('type', 'priest')
                ->whereIn('action', [
                    Notification::ACTION_PRIEST_LEAVE_APPROVED,
                    Notification::ACTION_PRIEST_LEAVE_REJECTED,
                ])->count(),
            'booking_assignments' => Notification::where('user_id', $user->id)
                ->where('type', 'priest')
                ->where('action', Notification::ACTION_PRIEST_BOOKING_ASSIGNED)
                ->count(),
        ];
        
        return view('priest.notifications.index', compact('notifications', 'type', 'counts', 'user'));
    }
    
    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer|exists:notifications,id'
        ]);
        
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->whereIn('id', $request->notification_ids)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return response()->json(['success' => true]);
    }
    
    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->where('type', 'priest')
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now()
            ]);
        
        return response()->json(['success' => true]);
    }
    
    public function getUnreadCount(Request $request)
    {
        $user = Auth::user();
        $limit = $request->get('limit', 5);
        
        $count = Notification::where('user_id', $user->id)
            ->where('type', 'priest')
            ->where('is_read', false)
            ->count();
        
        $notifications = [];
        if ($limit > 0) {
            $notifications = Notification::where('user_id', $user->id)
                ->where('type', 'priest')
                ->where('is_read', false)
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get()
                ->map(function ($notification) {
                    return [
                        'id' => $notification->id,
                        'message' => $notification->message,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'is_read' => $notification->is_read,
                        'booking' => $notification->booking ? [
                            'id' => $notification->booking->id,
                            'service' => $notification->booking->service ? $notification->booking->service->name : null,
                            'user' => $notification->booking->user ? $notification->booking->user->name : null,
                        ] : null,
                    ];
                });
        }
        
        return response()->json([
            'count' => $count,
            'notifications' => $notifications
        ]);
    }
    
    public function delete(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'integer|exists:notifications,id'
        ]);
        
        $user = Auth::user();
        
        Notification::where('user_id', $user->id)
            ->whereIn('id', $request->notification_ids)
            ->delete();
        
        return response()->json(['success' => true]);
    }
}
