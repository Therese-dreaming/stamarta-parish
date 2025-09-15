<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryMember extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',          // Links member to specific ministry, used for organization and access control
        'user_id',              // Optional link to user account, used for authentication and system integration
        'name',                 // Member's full name, used for display and identification in member lists
        'email',                // Member's email address, used for communication and contact information
        'phone',                // Member's phone number, used for contact and communication purposes
        'position',             // Member's specific role/title within the ministry, used for organizational hierarchy
        'role',                 // Member's permission level (member, officer, assistant_ministry_head), used for authorization
        'is_active',            // Boolean flag for member status, used for filtering active/inactive members
        'joined_at',            // Date when member joined the ministry, used for membership duration tracking
        'notes',                // Additional notes about the member, used for internal documentation and comments
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

    // Role methods
    public function isMember()
    {
        return $this->role === 'member';
    }

    public function isOfficer()
    {
        return $this->role === 'officer';
    }

    public function isAssistantMinistryHead()
    {
        return $this->role === 'assistant_ministry_head';
    }

    public function getRoleLabelAttribute()
    {
        $labels = [
            'member' => 'Member',
            'officer' => 'Officer',
            'assistant_ministry_head' => 'Assistant Ministry Head',
        ];

        return $labels[$this->role] ?? 'Unknown';
    }

    public function getRoleBadgeAttribute()
    {
        $badges = [
            'member' => 'bg-blue-100 text-blue-800',
            'officer' => 'bg-green-100 text-green-800',
            'assistant_ministry_head' => 'bg-purple-100 text-purple-800',
        ];

        return $badges[$this->role] ?? 'bg-gray-100 text-gray-800';
    }

    // Validation methods
    public static function canAddAssistantMinistryHead($ministryId)
    {
        $count = self::where('ministry_id', $ministryId)
            ->where('role', 'assistant_ministry_head')
            ->where('is_active', true)
            ->count();
        
        return $count < 2;
    }

    public static function getAssistantMinistryHeadCount($ministryId)
    {
        return self::where('ministry_id', $ministryId)
            ->where('role', 'assistant_ministry_head')
            ->where('is_active', true)
            ->count();
    }
}


