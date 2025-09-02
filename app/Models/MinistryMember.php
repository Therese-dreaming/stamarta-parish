<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',
        'user_id',
        'name',
        'email',
        'phone',
        'position',
        'is_active',
        'joined_at',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'joined_at' => 'date',
    ];

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


