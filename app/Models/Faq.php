<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    protected $fillable = [
        'question',         // FAQ question text displayed to users, used in search functionality and chatbot responses
        'answer',           // Detailed answer content for the FAQ, displayed in expandable sections and search results
        'category',         // FAQ grouping category (general, booking, payment, etc.), used for filtering and organization
        'keywords',         // JSON array of search keywords, used for enhanced search matching and chatbot intelligence
        'is_active',        // Boolean flag to show/hide FAQ from public display, used for content management and moderation
        'order',            // Integer for custom display ordering within categories, used for prioritized FAQ presentation
        'created_by'        // User ID who created the FAQ, used for audit trail and content management accountability
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('created_at');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getFormattedKeywordsAttribute()
    {
        return $this->keywords ? implode(', ', $this->keywords) : '';
    }
} 