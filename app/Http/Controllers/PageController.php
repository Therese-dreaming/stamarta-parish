<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function show($slug)
    {
        $page = Page::where('slug', $slug)
                    ->where('is_published', true)
                    ->first();

        if (!$page) {
            // Check if page exists but is unpublished
            $unpublishedPage = Page::where('slug', $slug)->first();
            
            if ($unpublishedPage) {
                // Page exists but is unpublished - show deactivated page
                return view('pages.deactivated', compact('unpublishedPage'));
            }
            
            // Page doesn't exist at all - show 404
            abort(404);
        }

        return view('pages.show', compact('page'));
    }

    public function home()
    {
        $page = Page::where('slug', 'home')
                    ->where('is_published', true)
                    ->first();

        return view('pages.show', compact('page'));
    }
} 