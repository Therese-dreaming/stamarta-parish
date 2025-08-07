<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Priest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PriestController extends Controller
{
    public function index()
    {
        $priests = Priest::orderBy('name')->paginate(10);
        return view('admin.priests.index', compact('priests'));
    }

    public function create()
    {
        return view('admin.priests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:priests,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'birth_date' => 'nullable|date|before:today',
            'ordination_date' => 'nullable|date|before:today',
            'specializations' => 'nullable|array',
            'specializations.*' => 'string|max:255',
            'bio' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $validated;
        unset($data['photo']);

        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('priests', 'public');
            $data['photo_path'] = $path;
        }

        Priest::create($data);

        return redirect()->route('admin.priests.index')
            ->with('success', 'Priest created successfully.');
    }

    public function show(Priest $priest)
    {
        return view('admin.priests.show', compact('priest'));
    }

    public function edit(Priest $priest)
    {
        return view('admin.priests.edit', compact('priest'));
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