<?php

namespace App\Http\Controllers;

use App\Models\Page;

class PageController extends Controller
{
    public function show(Page $page)
    {
        // Only show published pages to public users
        if (!$page->is_published) {
            abort(404);
        }

        return view('pages.show', compact('page'));
    }

    public function index()
    {
        // Public pages directory - show all published pages
        $pages = Page::published()
            ->with('creator')
            ->orderBy('title')
            ->paginate(12);

        return view('pages.index', compact('pages'));
    }
}