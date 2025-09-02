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
            'head_user_id' => 'required|exists:users,id',
            'is_active' => 'sometimes|boolean',
        ]);
        $data['slug'] = $data['slug'] ?? Str::slug($data['name']);
        $data['is_active'] = (bool)($data['is_active'] ?? true);
        $ministry = Ministry::create($data);

        // Ensure selected head is given the ministry_head role
        if (!empty($data['head_user_id'])) {
            $head = \App\Models\User::find($data['head_user_id']);
            if ($head && $head->role !== 'ministry_head') {
                $head->update(['role' => 'ministry_head']);
            }
        }

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
            'head_user_id' => 'required|exists:users,id',
            'is_active' => 'sometimes|boolean',
        ]);
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }
        $data['is_active'] = (bool)($data['is_active'] ?? $ministry->is_active);

        $previousHeadId = $ministry->head_user_id;
        $ministry->update($data);

        // If head changed or set, ensure role
        if (!empty($data['head_user_id'])) {
            if ($previousHeadId != $data['head_user_id']) {
                $newHead = \App\Models\User::find($data['head_user_id']);
                if ($newHead && $newHead->role !== 'ministry_head') {
                    $newHead->update(['role' => 'ministry_head']);
                }
            }
        }
        // Optional: we could demote previous head if they no longer lead any ministry
        // Skipping demotion to avoid unintended role changes across the system.

        return redirect()->route('admin.ministries.index')->with('success', 'Ministry updated.');
    }

    public function destroy(Ministry $ministry)
    {
        $ministry->delete();
        return redirect()->route('admin.ministries.index')->with('success', 'Ministry deleted.');
    }
}


