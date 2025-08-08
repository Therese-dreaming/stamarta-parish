<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::with(['creator', 'updater'])
            ->orderBy('updated_at', 'desc')
            ->paginate(15);

        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('cms.pages.index', compact('pages', 'isStaff'));
    }

    public function create()
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('cms.pages.create', compact('isStaff'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        Page::create($validated);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        
        return view('cms.pages.edit', compact('page', 'isStaff'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pages')->ignore($page)],
            'content' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $validated['updated_by'] = Auth::id();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        $page->update($validated);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy(Page $page)
    {
        $page->delete();

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function togglePublish(Page $page)
    {
        $page->update([
            'is_published' => !$page->is_published,
            'updated_by' => Auth::id(),
        ]);

        $status = $page->is_published ? 'published' : 'unpublished';
        return redirect()->route('admin.cms.pages.index')
            ->with('success', "Page {$status} successfully.");
    }

    public function preview(Page $page)
    {
        return view('cms.pages.preview', compact('page'));
    }
} 