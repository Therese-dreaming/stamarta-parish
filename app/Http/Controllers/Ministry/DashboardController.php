<?php

namespace App\Http\Controllers\Ministry;

use App\Http\Controllers\Controller;
use App\Models\Ministry;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $ministry = Ministry::where('head_user_id', $user->id)->withCount(['members', 'activities', 'budgetRequests'])->first();
        return view('ministry.dashboard', compact('ministry'));
    }
}


