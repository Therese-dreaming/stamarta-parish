<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriestLeave extends Model
{
    use HasFactory;

    protected $fillable = [
        'priest_id',            // Links leave request to specific priest, used for priest-specific leave tracking
        'leave_type',           // Type of leave (vacation, sick, pilgrimage, etc.), used for categorization and reporting
        'start_date',           // Leave start date, used for scheduling conflicts and availability checking
        'end_date',             // Leave end date, used for duration calculation and availability checking
        'reason',               // Reason for leave request, used for documentation and approval process
        'contact_info',         // Contact information during leave, used for emergency communication
        'emergency_contact',    // Emergency contact details, used for urgent communication during leave
        'status',               // Leave request status (pending, approved, rejected, completed), used for workflow management
        'submitted_at',         // Timestamp when leave was submitted, used for audit trail and processing time tracking
        'approved_at',          // Timestamp when leave was approved, used for audit trail and approval tracking
        'approved_by',          // User ID who approved the leave, used for accountability and authorization tracking
        'notes',                // Additional notes about the leave, used for internal documentation and comments
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
    ];

    public function priest()
    {
        return $this->belongsTo(Priest::class);
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getDurationAttribute()
    {
        return $this->start_date->diffInDays($this->end_date) + 1;
    }
}
