<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Priest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Models\Booking;

class PriestController extends Controller
{
    public function index()
    {
        $priests = Priest::orderBy('name')->paginate(10);
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.priests.index', compact('priests', 'isStaff'));
    }

    public function create()
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.priests.create', compact('isStaff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email|unique:priests,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'ordination_date' => 'nullable|date|before:today',
            'years_of_service' => 'nullable|integer|min:0|max:100',
            'leave_status' => 'required|in:active,on_leave,pilgrimage,sabbatical,retired',
            'leave_reason' => 'nullable|string|max:500',
            'leave_start_date' => 'nullable|date|after_or_equal:today',
            'leave_end_date' => 'nullable|date|after:leave_start_date',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'create_user_account' => 'boolean',
            'password' => 'required_if:create_user_account,true|string|min:8|confirmed',
        ]);

        // Generate password if not provided
        if ($validated['create_user_account'] && !$request->filled('password')) {
            $validated['password'] = Str::random(12);
        }

        // Create user account first
        $user = null;
        if ($validated['create_user_account']) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'priest',
                'email_verified_at' => now(),
            ]);
        }

        // Prepare priest data
        $priestData = $validated;
        unset($priestData['photo'], $priestData['create_user_account'], $priestData['password'], $priestData['password_confirmation']);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('priests', 'public');
            $priestData['photo_path'] = $path;
        }

        // Link to user if created
        if ($user) {
            $priestData['user_id'] = $user->id;
        }

        $priest = Priest::create($priestData);

        $message = 'Priest created successfully.';
        if ($user) {
            $message .= ' User account created with email: ' . $user->email;
            if (!$request->filled('password')) {
                $message .= ' Password: ' . $validated['password'];
            }
        }

        return redirect()->route('admin.priests.index')
            ->with('success', $message);
    }

    public function show(Priest $priest)
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.priests.show', compact('priest', 'isStaff'));
    }

    public function bookings(Priest $priest, Request $request)
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';

        $bookingsQuery = Booking::with(['user', 'service'])
            ->where('priest_id', $priest->id);

        // Optional status filter via query string ?status=pending|acknowledged|approved|completed|cancelled|rejected
        $status = $request->query('status');
        if ($status) {
            $bookingsQuery->where('status', $status);
        }

        $bookings = $bookingsQuery
            ->orderBy('service_date', 'desc')
            ->orderBy('service_time', 'desc')
            ->paginate(15)
            ->appends($request->only('status'));

        // Simple stats
        $stats = [
            'total' => Booking::where('priest_id', $priest->id)->count(),
            'pending' => Booking::where('priest_id', $priest->id)->where('status', 'pending')->count(),
            'acknowledged' => Booking::where('priest_id', $priest->id)->where('status', 'acknowledged')->count(),
            'approved' => Booking::where('priest_id', $priest->id)->where('status', 'approved')->count(),
            'completed' => Booking::where('priest_id', $priest->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('priest_id', $priest->id)->where('status', 'cancelled')->count(),
            'rejected' => Booking::where('priest_id', $priest->id)->where('status', 'rejected')->count(),
        ];

        return view('admin.priests.bookings', compact('priest', 'bookings', 'stats', 'isStaff', 'status'));
    }

    public function edit(Priest $priest)
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('admin.priests.edit', compact('priest', 'isStaff'));
    }

    public function update(Request $request, Priest $priest)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('priests')->ignore($priest->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'ordination_date' => 'nullable|date|before:today',
            'years_of_service' => 'nullable|integer|min:0|max:100',
            'leave_status' => 'required|in:active,on_leave,pilgrimage,sabbatical,retired',
            'leave_reason' => 'nullable|string|max:500',
            'leave_start_date' => 'nullable|date',
            'leave_end_date' => 'nullable|date|after:leave_start_date',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $validated;
        unset($data['photo']);

        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($priest->photo_path) {
                Storage::disk('public')->delete($priest->photo_path);
            }
            
            $path = $request->file('photo')->store('priests', 'public');
            $data['photo_path'] = $path;
        }

        $priest->update($data);

        // Update linked user if exists
        if ($priest->user) {
            $priest->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);
        }

        return redirect()->route('admin.priests.index')
            ->with('success', 'Priest updated successfully.');
    }

    public function destroy(Priest $priest)
    {
        if ($priest->photo_path) {
            Storage::disk('public')->delete($priest->photo_path);
        }
        
        $priest->delete();

        return redirect()->route('admin.priests.index')
            ->with('success', 'Priest deleted successfully.');
    }

    public function toggleStatus(Priest $priest)
    {
        $priest->update(['is_active' => !$priest->is_active]);
        
        $status = $priest->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.priests.index')
            ->with('success', "Priest {$status} successfully.");
    }
} 