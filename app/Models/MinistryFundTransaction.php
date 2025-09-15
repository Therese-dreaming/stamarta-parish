<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\MinistryBudgetRequest;

class MinistryFundTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',                  // Links transaction to specific ministry, used for fund tracking and balance calculations
        'type',                         // Transaction type (credit/debit), used for financial calculations and balance determination
        'amount',                       // Transaction amount in decimal format, used for balance calculations and reporting
        'description',                  // Human-readable description of the transaction, displayed in transaction lists and reports
        'reference_no',                 // Unique reference number for transaction tracking, used for audit trails and reconciliation
        'source_type',                  // Polymorphic source model class name, used for linking to originating records (budget requests, cash inflows)
        'source_id',                    // ID of the source record, used with source_type for polymorphic relationships
        'reversal_of_transaction_id',   // ID of transaction being reversed, used for transaction reversals and audit trails
        'entered_by_user_id',           // User who created the transaction, used for audit trail and accountability
        'approved_by_user_id',          // Admin who approved the transaction, used for approval workflow and authorization tracking
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

    /**
     * Get the budget request that created this transaction (if applicable)
     */
    public function budgetRequest()
    {
        if ($this->source_type === MinistryBudgetRequest::class) {
            return $this->belongsTo(MinistryBudgetRequest::class, 'source_id');
        }
        return null;
    }
}


