<?php

namespace App\Http\Controllers\Priest;

use App\Http\Controllers\Controller;
use App\Models\Priest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $priest = Auth::user()->priest;
        
        if (!$priest) {
            abort(404, 'Priest profile not found');
        }
        
        return view('priest.profile.edit', compact('priest'));
    }
    
    public function update(Request $request)
    {
        $priest = Auth::user()->priest;
        
        if (!$priest) {
            abort(404, 'Priest profile not found');
        }
        
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore(Auth::id())],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'ordination_date' => ['nullable', 'date', 'before:today'],
            'years_of_service' => ['nullable', 'integer', 'min:0', 'max:100'],
            'current_password' => ['nullable', 'string'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);
        
        // Update user information
        $user = Auth::user();
        $user->name = $request->name;
        $user->email = $request->email;
        
        // Update password if provided
        if ($request->filled('password')) {
            if (!$request->filled('current_password') || !Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect.']);
            }
            $user->password = Hash::make($request->password);
        }
        
        $user->save();
        
        // Update priest information
        $priest->update([
            'phone' => $request->phone,
            'address' => $request->address,
            'birth_date' => $request->birth_date,
            'ordination_date' => $request->ordination_date,
            'years_of_service' => $request->years_of_service,
        ]);
        
        return redirect()->route('priest.profile.edit')
            ->with('success', 'Profile updated successfully!');
    }
}
