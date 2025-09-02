<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MinistryController extends Controller
{
    public function index()
    {
        $ministries = Ministry::with('head')->orderBy('name')->paginate(15);
        return view('admin.ministries.index', compact('ministries'));
    }

    public function create()
    {
        $heads = User::orderBy('name')->get();
        return view('admin.ministries.create', compact('heads'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ministries,slug',
            'description' => 'nullable|string',
            'head_user_id' => 'nullable|exists:users,id',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = (bool)($data['is_active'] ?? true);
        Ministry::create($data);
        return redirect()->route('admin.ministries.index')->with('success', 'Ministry created.');
    }

    public function edit(Ministry $ministry)
    {
        $heads = User::orderBy('name')->get();
        return view('admin.ministries.edit', compact('ministry', 'heads'));
    }

    public function update(Request $request, Ministry $ministry)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ministries,slug,' . $ministry->id,
            'description' => 'nullable|string',
            'head_user_id' => 'nullable|exists:users,id',
            'is_active' => 'sometimes|boolean',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['is_active'] = (bool)($data['is_active'] ?? $ministry->is_active);
        $ministry->update($data);
        return redirect()->route('admin.ministries.index')->with('success', 'Ministry updated.');
    }

    public function destroy(Ministry $ministry)
    {
        $ministry->delete();
        return redirect()->route('admin.ministries.index')->with('success', 'Ministry deleted.');
    }
}


