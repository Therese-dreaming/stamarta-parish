<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryMember;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    private function getHeadMinistryOrAbort(): Ministry
    {
        $user = auth()->user();
        $ministry = Ministry::where('head_user_id', $user->id)->first();
        if (!$ministry) {
            abort(403);
        }
        return $ministry;
    }

    public function index()
    {
        $ministry = $this->getHeadMinistryOrAbort();
        $members = $ministry->members()->orderBy('name')->paginate(20);
        return view('ministry.members.index', compact('ministry', 'members'));
    }

    public function create()
    {
        $ministry = $this->getHeadMinistryOrAbort();
        return view('ministry.members.create', compact('ministry'));
    }

    public function store(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'position' => 'nullable|string|max:100',
            'joined_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $user = \App\Models\User::find($data['user_id']);
        // Prevent duplicate membership
        $exists = $ministry->members()->where('user_id', $user->id)->exists();
        if ($exists) {
            return back()->with('error', 'This user is already a member of the ministry.')->withInput();
        }
        $data['name'] = $data['name'] ?: $user->name;
        $data['email'] = $data['email'] ?: $user->email;

        $ministry->members()->create($data);
        return redirect()->route('ministry.members.index')->with('success', 'Member added.');
    }

    public function edit(MinistryMember $member)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($member->ministry_id !== $ministry->id) {
            abort(403);
        }
        return view('ministry.members.edit', compact('ministry', 'member'));
    }

    public function update(Request $request, MinistryMember $member)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($member->ministry_id !== $ministry->id) {
            abort(403);
        }

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
        return redirect()->route('ministry.members.index')->with('success', 'Member updated.');
    }

    public function destroy(MinistryMember $member)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($member->ministry_id !== $ministry->id) {
            abort(403);
        }
        $member->delete();
        return redirect()->route('ministry.members.index')->with('success', 'Member deleted.');
    }

    public function searchUsers(Request $request)
    {
        $this->getHeadMinistryOrAbort();
        $q = trim($request->query('q', ''));
        if ($q === '') {
            return response()->json([]);
        }
        $users = \App\Models\User::query()
            ->where(function($query) use ($q) {
                $query->where('name', 'like', "%$q%")
                      ->orWhere('email', 'like', "%$q%");
            })
            ->orderBy('name')
            ->limit(10)
            ->get(['id','name','email']);
        return response()->json($users);
    }
}


