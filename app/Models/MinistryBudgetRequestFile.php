<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryBudgetRequestFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_request_id',    // Links file to specific budget request, used for organization and access control
        'path',                 // Storage path to the uploaded file, used for file retrieval and download functionality
        'original_name',        // Original filename when uploaded, used for display and download naming
        'uploaded_by',          // User ID who uploaded the file, used for audit trail and accountability
    ];

    public function request()
    {
        return $this->belongsTo(MinistryBudgetRequest::class, 'budget_request_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}


