<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'head_user_id',
        'is_active',
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


