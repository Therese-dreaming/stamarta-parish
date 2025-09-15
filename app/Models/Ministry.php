<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',             // Ministry name displayed in lists and forms, used for identification and organization
        'slug',             // URL-friendly identifier for routing, auto-generated from name if not provided
        'description',      // Detailed description of ministry purpose and activities, used for documentation
        'head_user_id',     // User ID of ministry head, used for authorization and role assignment
        'is_active',        // Boolean flag for ministry status, used for filtering and access control
    ];

    public function head()
    {
        return $this->belongsTo(User::class, 'head_user_id');
    }

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function transactions()
    {
        return $this->hasMany(MinistryFundTransaction::class);
    }

    public function budgetRequests()
    {
        return $this->hasMany(MinistryBudgetRequest::class);
    }

    public function members()
    {
        return $this->hasMany(MinistryMember::class);
    }

    public function activities()
    {
        return $this->hasMany(MinistryActivity::class);
    }

    public function manualCashInflows()
    {
        return $this->hasMany(ManualCashInflow::class);
    }
}


