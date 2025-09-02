<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryBudgetRequestFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget_request_id',
        'path',
        'original_name',
        'uploaded_by',
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


