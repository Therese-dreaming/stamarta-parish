<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs
     */
    public function index()
    {
        $faqs = Faq::with('creator')
            ->orderBy('order')
            ->orderBy('created_at')
            ->paginate(15);

        $categories = Faq::select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('admin.faqs.index', compact('faqs', 'categories'));
    }

    /**
     * Show the form for creating a new FAQ
     */
    public function create()
    {
        $categories = Faq::select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('admin.faqs.create', compact('categories'));
    }

    /**
     * Store a newly created FAQ
     */
    public function store(Request $request)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'required|string|max:100',
            'keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        $keywords = null;
        if ($request->filled('keywords')) {
            $keywords = array_map('trim', explode(',', $request->keywords));
            $keywords = array_filter($keywords);
        }

        Faq::create([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'keywords' => $keywords,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ created successfully.');
    }

    /**
     * Show the form for editing the specified FAQ
     */
    public function edit(Faq $faq)
    {
        $categories = Faq::select('category')
            ->distinct()
            ->pluck('category')
            ->filter()
            ->values();

        return view('admin.faqs.edit', compact('faq', 'categories'));
    }

    /**
     * Update the specified FAQ
     */
    public function update(Request $request, Faq $faq)
    {
        $request->validate([
            'question' => 'required|string|max:500',
            'answer' => 'required|string',
            'category' => 'required|string|max:100',
            'keywords' => 'nullable|string',
            'is_active' => 'boolean',
            'order' => 'nullable|integer|min:0'
        ]);

        $keywords = null;
        if ($request->filled('keywords')) {
            $keywords = array_map('trim', explode(',', $request->keywords));
            $keywords = array_filter($keywords);
        }

        $faq->update([
            'question' => $request->question,
            'answer' => $request->answer,
            'category' => $request->category,
            'keywords' => $keywords,
            'is_active' => $request->boolean('is_active', true),
            'order' => $request->order ?? 0
        ]);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ updated successfully.');
    }

    /**
     * Remove the specified FAQ
     */
    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'FAQ deleted successfully.');
    }

    /**
     * Toggle FAQ active status
     */
    public function toggleStatus(Faq $faq)
    {
        $faq->update(['is_active' => !$faq->is_active]);

        return response()->json([
            'success' => true,
            'is_active' => $faq->is_active,
            'message' => $faq->is_active ? 'FAQ activated successfully.' : 'FAQ deactivated successfully.'
        ]);
    }

    /**
     * Reorder FAQs
     */
    public function reorder(Request $request)
    {
        $request->validate([
            'faqs' => 'required|array',
            'faqs.*.id' => 'required|exists:faqs,id',
            'faqs.*.order' => 'required|integer|min:0'
        ]);

        foreach ($request->faqs as $faqData) {
            Faq::where('id', $faqData['id'])->update(['order' => $faqData['order']]);
        }

        return response()->json(['success' => true]);
    }
} 