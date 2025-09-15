<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Media extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',    // Original filename when uploaded, used for display and search functionality
        'file_name',        // Generated unique filename for storage, prevents conflicts and ensures security
        'file_path',        // Storage path to the file, used for file retrieval and URL generation
        'file_size',        // File size in bytes, used for storage management and display formatting
        'mime_type',        // File MIME type (image/jpeg, application/pdf, etc.), used for type filtering and validation
        'alt_text',         // Alternative text for images, used for accessibility and search functionality
        'description',      // Optional description of the media file, used for documentation and search
        'folder',           // Organizational folder name, used for file categorization and filtering
        'uploaded_by',      // User ID who uploaded the file, used for audit trail and permissions
    ];

    protected $appends = [
        'url',
        'formatted_size',
        'is_image',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute()
    {
        if (!$this->file_path || empty($this->file_path)) {
            return null;
        }
        return Storage::url($this->file_path);
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getIsImageAttribute()
    {
        return str_starts_with($this->mime_type, 'image/');
    }

    public function scopeImages($query)
    {
        return $query->where('mime_type', 'like', 'image/%');
    }

    public function scopeDocuments($query)
    {
        return $query->where('mime_type', 'not like', 'image/%');
    }

    public function scopeInFolder($query, $folder)
    {
        return $query->where('folder', $folder);
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'id';
    }
} 