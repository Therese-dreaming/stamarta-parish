<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Media;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_pages' => Page::count(),
            'published_pages' => Page::published()->count(),
            'draft_pages' => Page::draft()->count(),
            'total_media' => Media::count(),
            'total_users' => User::count(),
            'total_priests' => \App\Models\Priest::count(),
            'total_services' => \App\Models\Service::count(),
            'recent_pages' => Page::with('creator')->latest()->take(5)->get(),
            'recent_media' => Media::with('uploader')->latest()->take(5)->get(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
} 