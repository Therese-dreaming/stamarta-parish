<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Services\NotificationService;
use App\Services\AdminActionCounterService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $type = $request->get('type', 'all');
        
        $query = Notification::with(['user', 'createdBy', 'booking.service'])
            ->orderBy('created_at', 'desc');
        
        // Role-based notification filtering
        switch ($user->role) {
            case 'admin':
                // Admin sees all notifications from all users
                // No additional filtering needed - they see everything
                break;
                
            case 'staff':
                // Staff sees notifications from priests, users, and their own notifications
                $query->where(function($q) use ($user) {
                    $q->where('type', 'user') // User notifications
                      ->orWhere('type', 'priest') // Priest notifications
                      ->orWhere(function($subQ) use ($user) {
                          $subQ->where('type', 'staff')
                               ->where('user_id', $user->id); // Only their own staff notifications
                      });
                });
                break;
                
            case 'priest':
                // Priest sees their own notifications (booking assignments, completions, etc.)
                $query->where('user_id', $user->id);
                break;
                
            case 'ministry_head':
                // Ministry heads see only their own notifications
                $query->where('user_id', $user->id);
                break;
                
            case 'user':
                // Users see only their own notifications
                $query->where('user_id', $user->id);
                break;
        }
        
        // Filter by type if specified
        if ($type === 'admin_staff') {
            $query->where('type', 'admin_staff');
        } elseif ($type === 'user') {
            $query->where('type', 'user');
        } elseif ($type === 'priest') {
            $query->where('type', 'priest');
        } elseif ($type === 'staff') {
            $query->where('type', 'staff');
        }
        
        $notifications = $query->paginate(20);
        
        // Pre-process notification messages based on user role
        $notifications->getCollection()->transform(function ($notification) use ($user) {
            $notification->display_message = $notification->getMessageForRole($user->role);
            return $notification;
        });
        
        // Get counts for tabs based on role
        $counts = $this->getNotificationCounts($user);
        
        // Return role-specific views
        $viewPath = match($user->role) {
            'admin' => 'admin.notifications.index',
            'staff' => 'staff.notifications.index',
            'priest' => 'priest.notifications.index',
            'ministry_head' => 'user.notifications.index', // Ministry heads use the same view as regular users
            'user' => 'user.notifications.index',
            default => 'user.notifications.index' // Fallback to user view
        };
        
        return view($viewPath, compact('notifications', 'type', 'counts', 'user'));
    }
    
    private function getNotificationCounts($user)
    {
        $counts = [];
        
        switch ($user->role) {
            case 'admin':
                // Admin sees all types
                $counts['all'] = Notification::count();
                $counts['admin_staff'] = Notification::where('type', 'admin_staff')->count();
                $counts['user'] = Notification::where('type', 'user')->count();
                $counts['priest'] = Notification::where('type', 'priest')->count();
                $counts['staff'] = Notification::where('type', 'staff')->count();
                break;
                
            case 'staff':
                // Staff sees user, priest, and their own staff notifications
                $counts['all'] = Notification::where(function($q) use ($user) {
                    $q->where('type', 'user')
                      ->orWhere('type', 'priest')
                      ->orWhere(function($subQ) use ($user) {
                          $subQ->where('type', 'staff')
                               ->where('user_id', $user->id);
                      });
                })->count();
                $counts['user'] = Notification::where('type', 'user')->count();
                $counts['priest'] = Notification::where('type', 'priest')->count();
                $counts['staff'] = Notification::where('type', 'staff')->where('user_id', $user->id)->count();
                break;
                
            case 'priest':
                // Priest sees only their own notifications
                $counts['all'] = Notification::where('user_id', $user->id)->count();
                $counts['booking'] = Notification::where('user_id', $user->id)
                    ->where('action', 'like', '%booking%')->count();
                $counts['system'] = Notification::where('user_id', $user->id)
                    ->where('action', 'not like', '%booking%')->count();
                break;
                
            case 'ministry_head':
                // Ministry head sees only their own notifications
                $counts['all'] = Notification::where('user_id', $user->id)->count();
                $counts['booking'] = Notification::where('user_id', $user->id)
                    ->where('action', 'like', '%booking%')->count();
                $counts['system'] = Notification::where('user_id', $user->id)
                    ->where('action', 'not like', '%booking%')->count();
                break;
                
            case 'user':
                // User sees only their own notifications
                $counts['all'] = Notification::where('user_id', $user->id)->count();
                $counts['booking'] = Notification::where('user_id', $user->id)
                    ->where('action', 'like', '%booking%')->count();
                $counts['system'] = Notification::where('user_id', $user->id)
                    ->where('action', 'not like', '%booking%')->count();
                break;
        }
        
        return $counts;
    }

    public function markAsRead(Request $request)
    {
        $request->validate([
            'notification_ids' => 'required|array',
            'notification_ids.*' => 'exists:notifications,id'
        ]);

        $user = auth()->user();
        $notificationIds = $request->notification_ids;
        
        // Role-based permission filtering for marking notifications as read
        switch ($user->role) {
            case 'admin':
                // Admin can mark any notification as read
                break;
                
            case 'staff':
                // Staff can mark user, priest, and their own staff notifications as read
                $notificationIds = Notification::whereIn('id', $notificationIds)
                    ->where(function($q) use ($user) {
                        $q->where('type', 'user')
                          ->orWhere('type', 'priest')
                          ->orWhere(function($subQ) use ($user) {
                              $subQ->where('type', 'staff')
                                   ->where('user_id', $user->id);
                          });
                    })
                    ->pluck('id')
                    ->toArray();
                break;
                
            case 'priest':
            case 'ministry_head':
            case 'user':
                // Priest, ministry head, and user can only mark their own notifications as read
                $notificationIds = Notification::whereIn('id', $notificationIds)
                    ->where('user_id', $user->id)
                    ->pluck('id')
                    ->toArray();
                break;
        }
        
        if (!empty($notificationIds)) {
            NotificationService::markAsRead($notificationIds, in_array($user->role, ['user', 'ministry_head']) ? $user->id : null);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read'
        ]);
    }

    public function markAllAsRead(Request $request)
    {
        $user = auth()->user();
        $type = $request->get('type', 'all');
        
        $query = Notification::where('is_read', false);
        
        // Role-based filtering for marking all notifications as read
        switch ($user->role) {
            case 'admin':
                // Admin can mark all notifications as read
                break;
                
            case 'staff':
                // Staff can mark user, priest, and their own staff notifications as read
                $query->where(function($q) use ($user) {
                    $q->where('type', 'user')
                      ->orWhere('type', 'priest')
                      ->orWhere(function($subQ) use ($user) {
                          $subQ->where('type', 'staff')
                               ->where('user_id', $user->id);
                      });
                });
                break;
                
            case 'priest':
            case 'ministry_head':
            case 'user':
                // Priest, ministry head, and user can only mark their own notifications as read
                $query->where('user_id', $user->id);
                break;
        }
        
        // Filter by type if specified
        if ($type === 'admin_staff') {
            $query->where('type', 'admin_staff');
        } elseif ($type === 'user') {
            $query->where('type', 'user');
        } elseif ($type === 'priest') {
            $query->where('type', 'priest');
        } elseif ($type === 'staff') {
            $query->where('type', 'staff');
        } elseif ($type === 'booking') {
            $query->where('action', 'like', '%booking%');
        } elseif ($type === 'system') {
            $query->where('action', 'not like', '%booking%');
        }
        
        $notificationIds = $query->pluck('id')->toArray();
        
        if (!empty($notificationIds)) {
            NotificationService::markAsRead($notificationIds, in_array($user->role, ['user', 'ministry_head']) ? $user->id : null);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    public function getUnreadCount(Request $request)
    {
        $user = auth()->user();
        $limit = $request->get('limit');
        
        // Role-based unread count
        $count = 0;
        switch ($user->role) {
            case 'admin':
                $count = Notification::where('is_read', false)->count();
                break;
                
            case 'staff':
                $count = Notification::where('is_read', false)
                    ->where(function($q) use ($user) {
                        $q->where('type', 'user')
                          ->orWhere('type', 'priest')
                          ->orWhere(function($subQ) use ($user) {
                              $subQ->where('type', 'staff')
                                   ->where('user_id', $user->id);
                          });
                    })->count();
                break;
                
            case 'priest':
            case 'ministry_head':
            case 'user':
                $count = Notification::where('is_read', false)
                    ->where('user_id', $user->id)->count();
                break;
        }
        
        $response = ['count' => $count];
        
        // If limit is provided, also return recent notifications
        if ($limit) {
            $query = Notification::with(['user', 'createdBy', 'booking.service'])
                ->orderBy('created_at', 'desc'); // Show both read and unread notifications
            
            // Role-based filtering for recent notifications
            switch ($user->role) {
                case 'admin':
                    // Admin sees all recent notifications
                    break;
                    
                case 'staff':
                    // Staff sees user, priest, and their own staff notifications
                    $query->where(function($q) use ($user) {
                        $q->where('type', 'user')
                          ->orWhere('type', 'priest')
                          ->orWhere(function($subQ) use ($user) {
                              $subQ->where('type', 'staff')
                                   ->where('user_id', $user->id);
                          });
                    });
                    break;
                    
                case 'priest':
                case 'ministry_head':
                case 'user':
                    // Priest, ministry head, and user see only their own notifications
                    $query->where('user_id', $user->id);
                    break;
            }
            
            $notifications = $query->limit($limit)->get()->map(function($notification) use ($user) {
                return [
                    'id' => $notification->id,
                    'message' => $notification->getMessageForRole($user->role),
                    'is_read' => $notification->is_read,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'type' => $notification->type,
                    'action' => $notification->action
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

        $user = auth()->user();
        $notificationIds = $request->notification_ids;
        
        // Role-based permission filtering for deleting notifications
        switch ($user->role) {
            case 'admin':
                // Admin can delete any notification
                break;
                
            case 'staff':
                // Staff can delete user, priest, and their own staff notifications
                $notificationIds = Notification::whereIn('id', $notificationIds)
                    ->where(function($q) use ($user) {
                        $q->where('type', 'user')
                          ->orWhere('type', 'priest')
                          ->orWhere(function($subQ) use ($user) {
                              $subQ->where('type', 'staff')
                                   ->where('user_id', $user->id);
                          });
                    })
                    ->pluck('id')
                    ->toArray();
                break;
                
            case 'priest':
            case 'ministry_head':
            case 'user':
                // Priest, ministry head, and user can only delete their own notifications
                $notificationIds = Notification::whereIn('id', $notificationIds)
                    ->where('user_id', $user->id)
                    ->pluck('id')
                    ->toArray();
                break;
        }
        
        if (!empty($notificationIds)) {
            Notification::whereIn('id', $notificationIds)->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Notifications deleted successfully'
        ]);
    }

    public function getAdminActionCounts(Request $request)
    {
        $user = auth()->user();
        
        // Only allow admin and staff to access this endpoint
        if (!in_array($user->role, ['admin', 'staff'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $actionCounter = new AdminActionCounterService();
        $formattedCounts = $actionCounter->getFormattedCounts();
        
        return response()->json($formattedCounts);
    }
}
