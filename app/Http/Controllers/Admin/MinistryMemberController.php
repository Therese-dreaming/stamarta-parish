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
            'phone' => 'nullable|string|regex:/^[0-9]+$/|max:15',
            'position' => 'nullable|string|max:100',
            'role' => 'required|in:member,officer,assistant_ministry_head',
            'joined_at' => 'nullable|date|before_or_equal:today',
            'notes' => 'nullable|string',
        ], [
            'phone.regex' => 'Phone number must contain only numbers.',
            'joined_at.before_or_equal' => 'Join date must be today or earlier.',
            'role.required' => 'Please select a role for the member.',
        ]);

        // Check Assistant Ministry Head limit
        if ($data['role'] === 'assistant_ministry_head') {
            if (!MinistryMember::canAddAssistantMinistryHead($ministry->id)) {
                return back()->with('error', 'Cannot add more than 2 Assistant Ministry Heads per ministry.')->withInput();
            }
        }

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
            'phone' => 'nullable|string|regex:/^[0-9]+$/|max:15',
            'position' => 'nullable|string|max:100',
            'role' => 'required|in:member,officer,assistant_ministry_head',
            'joined_at' => 'nullable|date|before_or_equal:today',
            'is_active' => 'sometimes|boolean',
            'notes' => 'nullable|string',
        ], [
            'phone.regex' => 'Phone number must contain only numbers.',
            'joined_at.before_or_equal' => 'Join date must be today or earlier.',
            'role.required' => 'Please select a role for the member.',
        ]);

        // Check Assistant Ministry Head limit (only if changing to assistant_ministry_head)
        if ($data['role'] === 'assistant_ministry_head' && $member->role !== 'assistant_ministry_head') {
            if (!MinistryMember::canAddAssistantMinistryHead($ministry->id)) {
                return back()->with('error', 'Cannot add more than 2 Assistant Ministry Heads per ministry.')->withInput();
            }
        }

        $member->update($data);

        return redirect()->route('admin.ministries.members.index', $ministry)->with('success', 'Member updated.');
    }

    public function destroy(Ministry $ministry, MinistryMember $member)
    {
        $member->delete();
        return redirect()->route('admin.ministries.members.index', $ministry)->with('success', 'Member deleted.');
    }
}


