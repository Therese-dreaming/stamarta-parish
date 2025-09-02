<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinistryFundTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',
        'type',
        'amount',
        'description',
        'reference_no',
        'source_type',
        'source_id',
        'reversal_of_transaction_id',
        'entered_by_user_id',
        'approved_by_user_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    const TYPE_CREDIT = 'credit';
    const TYPE_DEBIT = 'debit';

    public function ministry()
    {
        return $this->belongsTo(Ministry::class);
    }

    public function source()
    {
        return $this->morphTo();
    }

    public function reversalOf()
    {
        return $this->belongsTo(MinistryFundTransaction::class, 'reversal_of_transaction_id');
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by_user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by_user_id');
    }
}


