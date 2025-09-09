<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\Media;
use App\Services\NotificationService;
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
        $mediaImages = Media::images()->orderBy('created_at', 'desc')->limit(48)->get();
        return view('cms.pages.create', compact('isStaff', 'mediaImages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'content' => 'nullable|string', // Made optional since we're using blocks
            'content_blocks_data' => 'nullable|string', // JSON string of blocks
            'layout' => 'nullable|in:one_column,image_left_text_right,image_right_text_left,image_top_text_bottom,text_top_image_bottom',
            'image_media_id' => 'nullable|exists:media,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $validated['created_by'] = Auth::id();
        $validated['updated_by'] = Auth::id();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Process content blocks
        if (!empty($validated['content_blocks_data'])) {
            $validated['content_blocks'] = json_decode($validated['content_blocks_data'], true);
            unset($validated['content_blocks_data']);
        }

        // Generate content from blocks if no content provided
        if (empty($validated['content']) && !empty($validated['content_blocks'])) {
            $validated['content'] = $this->generateContentFromBlocks($validated['content_blocks']);
        }

        $page = Page::create($validated);

        // If called by staff, notify admins
        if (auth()->user()->role === 'staff') {
            NotificationService::staffPageCreated($page, auth()->user()->name);
        }

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page created successfully.');
    }

    public function edit(Page $page)
    {
        // Check if user is staff
        $isStaff = auth()->user()->role === 'staff';
        $mediaImages = Media::images()->orderBy('created_at', 'desc')->limit(48)->get();
        return view('cms.pages.edit', compact('page', 'isStaff', 'mediaImages'));
    }

    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pages')->ignore($page)],
            'content' => 'nullable|string', // Made optional since we're using blocks
            'content_blocks_data' => 'nullable|string', // JSON string of blocks
            'layout' => 'nullable|in:one_column,image_left_text_right,image_right_text_left,image_top_text_bottom,text_top_image_bottom',
            'image_media_id' => 'nullable|exists:media,id',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'is_published' => 'boolean',
        ]);

        $validated['updated_by'] = Auth::id();

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }

        // Process content blocks
        if (!empty($validated['content_blocks_data'])) {
            $validated['content_blocks'] = json_decode($validated['content_blocks_data'], true);
            unset($validated['content_blocks_data']);
        }

        // Generate content from blocks if no content provided
        if (empty($validated['content']) && !empty($validated['content_blocks'])) {
            $validated['content'] = $this->generateContentFromBlocks($validated['content_blocks']);
        }

        $page->update($validated);

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page updated successfully.');
    }

    public function destroy($pageId)
    {
        $page = Page::findOrFail($pageId);
        $page->delete();

        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page deleted successfully.');
    }

    public function togglePublish($pageId)
    {
        $page = Page::findOrFail($pageId);
        
        $page->update([
            'is_published' => !$page->is_published,
            'updated_by' => Auth::id(),
        ]);

        $status = $page->is_published ? 'published' : 'unpublished';
        
        return redirect()->route('admin.cms.pages.index')
            ->with('success', "Page {$status} successfully.");
    }

    public function preview($pageId)
    {
        $page = Page::findOrFail($pageId);
        return view('cms.pages.preview', compact('page'));
    }

    /**
     * Generate HTML content from content blocks
     */
    private function generateContentFromBlocks($blocks)
    {
        if (empty($blocks)) {
            return '';
        }

        $html = '';
        foreach ($blocks as $block) {
            $html .= $this->renderBlockToHtml($block);
        }

        return $html;
    }

    /**
     * Render a single block to HTML
     */
    private function renderBlockToHtml($block)
    {
        if (!isset($block['type'])) {
            return '';
        }

        switch ($block['type']) {
            case 'text':
                $content = $block['data']['content'] ?? '';
                return $content ? "<div class=\"prose max-w-none mb-6\">" . nl2br(htmlspecialchars($content)) . "</div>" : '';
                
            case 'image':
                $imageUrl = $block['data']['image_url'] ?? '';
                $caption = $block['data']['caption'] ?? '';
                if ($imageUrl) {
                    $captionHtml = $caption ? "<p class=\"text-sm text-gray-600 mt-2 text-center\">" . htmlspecialchars($caption) . "</p>" : '';
                    return "<div class=\"mb-6\"><img src=\"" . htmlspecialchars($imageUrl) . "\" alt=\"" . htmlspecialchars($caption ?: 'Image') . "\" class=\"w-full h-auto rounded-lg shadow-sm\">{$captionHtml}</div>";
                }
                return '';
                
            case 'gallery':
                $images = $block['data']['images'] ?? [];
                if (!empty($images)) {
                    $imageHtml = '';
                    foreach ($images as $image) {
                        $imageHtml .= "<img src=\"" . htmlspecialchars($image['url']) . "\" alt=\"" . htmlspecialchars($image['name']) . "\" class=\"w-full h-32 object-cover rounded border\">";
                    }
                    return "<div class=\"grid grid-cols-3 gap-4 mb-6\">{$imageHtml}</div>";
                }
                return '';
                
            case 'columns':
                $columns = intval($block['data']['columns'] ?? 2);
                $columnContent = '';
                for ($i = 1; $i <= $columns; $i++) {
                    $content = $block['data']["column{$i}"] ?? '';
                    $columnContent .= "<div class=\"prose max-w-none\">" . nl2br(htmlspecialchars($content)) . "</div>";
                }
                return "<div class=\"grid md:grid-cols-{$columns} gap-6 mb-6\">{$columnContent}</div>";
                
            case 'image_text':
                $imageUrl = $block['data']['image_url'] ?? '';
                $content = $block['data']['content'] ?? '';
                $layout = $block['data']['layout'] ?? 'image_left';
                
                if ($imageUrl || $content) {
                    $imageHtml = $imageUrl ? "<img src=\"" . htmlspecialchars($imageUrl) . "\" alt=\"Image\" class=\"w-full h-auto rounded-lg shadow-sm\">" : '<div class="h-32 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">No image</div>';
                    $textHtml = $content ? "<div class=\"prose max-w-none\">" . nl2br(htmlspecialchars($content)) . "</div>" : '<p class="text-gray-500 italic">No content yet...</p>';
                    
                    if ($layout === 'image_right') {
                        return "<div class=\"grid md:grid-cols-2 gap-6 mb-6\"><div>{$textHtml}</div><div>{$imageHtml}</div></div>";
                    } else {
                        return "<div class=\"grid md:grid-cols-2 gap-6 mb-6\"><div>{$imageHtml}</div><div>{$textHtml}</div></div>";
                    }
                }
                return '';
                
            case 'divider':
                $style = $block['data']['style'] ?? 'line';
                $text = $block['data']['text'] ?? '';
                
                if ($style === 'text' && $text) {
                    return "<div class=\"flex items-center my-6\"><div class=\"flex-1 border-t border-gray-300\"></div><span class=\"px-4 text-gray-500 text-sm\">" . htmlspecialchars($text) . "</span><div class=\"flex-1 border-t border-gray-300\"></div></div>";
                } else {
                    $borderStyle = $style === 'dashed' ? 'border-dashed' : ($style === 'dotted' ? 'border-dotted' : 'border-solid');
                    return "<div class=\"border-t {$borderStyle} border-gray-300 my-6\"></div>";
                }
                
            case 'spacer':
                $height = intval($block['data']['height'] ?? 40);
                return "<div style=\"height: {$height}px;\"></div>";
                
            case 'button':
                $text = $block['data']['text'] ?? 'Click here';
                $url = $block['data']['url'] ?? '#';
                $style = $block['data']['style'] ?? 'primary';
                
                $styleClasses = [
                    'primary' => 'bg-[#0d5c2f] text-white hover:bg-[#0a4a26]',
                    'secondary' => 'bg-gray-600 text-white hover:bg-gray-700',
                    'outline' => 'border border-[#0d5c2f] text-[#0d5c2f] hover:bg-[#0d5c2f] hover:text-white'
                ];
                
                $class = $styleClasses[$style] ?? $styleClasses['primary'];
                return "<div class=\"text-center mb-6\"><a href=\"" . htmlspecialchars($url) . "\" class=\"inline-block px-6 py-3 rounded-lg transition-colors {$class}\">" . htmlspecialchars($text) . "</a></div>";
                
            default:
                return '';
        }
    }
} 