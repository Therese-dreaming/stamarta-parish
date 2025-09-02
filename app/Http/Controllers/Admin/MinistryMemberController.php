<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryMember;
use Illuminate\Http\Request;

class MinistryMemberController extends Controller
{
    public function index(Ministry $ministry)
    {
        $members = $ministry->members()->orderBy('name')->paginate(20);
        return view('admin.ministries.members.index', compact('ministry', 'members'));
    }

    public function create(Ministry $ministry)
    {
        return view('admin.ministries.members.create', compact('ministry'));
    }

    public function store(Request $request, Ministry $ministry)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:100',
            'joined_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $ministry->members()->create($data);

        return redirect()->route('admin.ministries.members.index', $ministry)->with('success', 'Member added.');
    }

    public function edit(Ministry $ministry, MinistryMember $member)
    {
        return view('admin.ministries.members.edit', compact('ministry', 'member'));
    }

    public function update(Request $request, Ministry $ministry, MinistryMember $member)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:100',
            'joined_at' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ]);

        $member->update($data);

        return redirect()->route('admin.ministries.members.index', $ministry)->with('success', 'Member updated.');
    }

    public function destroy(Ministry $ministry, MinistryMember $member)
    {
        $member->delete();
        return redirect()->route('admin.ministries.members.index', $ministry)->with('success', 'Member deleted.');
    }
}


