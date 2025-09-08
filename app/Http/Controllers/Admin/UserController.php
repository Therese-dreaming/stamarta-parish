<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Ministry;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'date_of_birth' => 'nullable|date|before:today',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['user', 'staff', 'priest', 'ministry_head', 'admin'])],
        ], [
            'date_of_birth.before' => 'Date of birth must be before today.',
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $validated['email_verified_at'] = now(); // Auto-verify admin-created users

        User::create($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        $ministries = Ministry::orderBy('name')->get();
        return view('admin.users.show', compact('user', 'ministries'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['user', 'staff', 'priest', 'ministry_head', 'admin'])],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function promoteToMinistryHead(Request $request, User $user)
    {
        $data = $request->validate([
            'ministry_id' => ['required', 'exists:ministries,id'],
        ]);

        // Set role and assign as head of the ministry
        $user->update(['role' => 'ministry_head']);

        $ministry = Ministry::findOrFail($data['ministry_id']);
        $ministry->update(['head_user_id' => $user->id]);

        return redirect()->route('admin.users.show', $user)->with('success', 'User promoted to Ministry Head and assigned to ministry.');
    }

    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $q = $request->input('q');
        $users = User::query()
            ->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id', 'name', 'email']);

        return response()->json($users);
    }

    public function destroy(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function toggleStatus(User $user)
    {
        // Prevent admin from deactivating themselves
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot deactivate your own account.');
        }

        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return redirect()->route('admin.users.index')
            ->with('success', "User {$status} successfully.");
    }
} 