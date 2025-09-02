<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryActivity;
use Illuminate\Http\Request;

class ActivityController extends Controller
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
        $activities = $ministry->activities()->orderBy('start_at', 'desc')->paginate(20);
        return view('ministry.activities.index', compact('ministry', 'activities'));
    }

    public function create()
    {
        $ministry = $this->getHeadMinistryOrAbort();
        return view('ministry.activities.create', compact('ministry'));
    }

    public function store(Request $request)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_all_day' => 'sometimes|boolean',
            'location' => 'nullable|string|max:255',
            'is_public' => 'sometimes|boolean',
        ]);
        $data['is_all_day'] = (bool)($data['is_all_day'] ?? false);
        $data['is_public'] = (bool)($data['is_public'] ?? false);
        $ministry->activities()->create($data);
        return redirect()->route('ministry.activities.index')->with('success', 'Activity created.');
    }

    public function edit(MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }
        return view('ministry.activities.edit', compact('ministry', 'activity'));
    }

    public function update(Request $request, MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_at' => 'required|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'is_all_day' => 'sometimes|boolean',
            'location' => 'nullable|string|max:255',
            'is_public' => 'sometimes|boolean',
        ]);
        $data['is_all_day'] = (bool)($data['is_all_day'] ?? false);
        $data['is_public'] = (bool)($data['is_public'] ?? false);
        $activity->update($data);
        return redirect()->route('ministry.activities.index')->with('success', 'Activity updated.');
    }

    public function destroy(MinistryActivity $activity)
    {
        $ministry = $this->getHeadMinistryOrAbort();
        if ($activity->ministry_id !== $ministry->id) {
            abort(403);
        }
        $activity->delete();
        return redirect()->route('ministry.activities.index')->with('success', 'Activity deleted.');
    }
}


