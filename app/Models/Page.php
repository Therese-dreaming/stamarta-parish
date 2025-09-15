<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Page extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',                // Page title displayed in headers and navigation, used for identification and SEO
        'slug',                 // URL-friendly identifier for routing, auto-generated from title if not provided
        'content',              // Main page content in HTML format, used as fallback when content_blocks are not available
        'content_blocks',       // JSON array of structured content blocks, used for flexible page layout and content management
        'layout',               // Page layout type (one_column, image_left_text_right, etc.), used for display formatting
        'image_media_id',       // Reference to featured image in media table, used for page headers and social sharing
        'meta_title',           // SEO title for search engines, used in page head and search results
        'meta_description',     // SEO description for search engines, used in page head and search result snippets
        'is_published',         // Boolean flag for publication status, used for draft/published content management
        'published_at',         // Timestamp when page was published, used for publication tracking and display
        'created_by',           // User ID who created the page, used for audit trail and content ownership
        'updated_by',           // User ID who last updated the page, used for audit trail and change tracking
        'updated_at',           // Timestamp when page was last modified, used for change tracking and sorting
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
        'content_blocks' => 'array',
    ];

    public function image()
    {
        return $this->belongsTo(\App\Models\Media::class, 'image_media_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($page) {
            if (empty($page->slug)) {
                $page->slug = Str::slug($page->title);
            }
            if ($page->is_published && !$page->published_at) {
                $page->published_at = now();
            }
        });

        static::updating(function ($page) {
            if ($page->is_published && !$page->published_at) {
                $page->published_at = now();
            }
        });
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeDraft($query)
    {
        return $query->where('is_published', false);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
} 