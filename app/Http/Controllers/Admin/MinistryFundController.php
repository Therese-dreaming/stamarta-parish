<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\MinistryFundTransaction;

class MinistryFundController extends Controller
{
    public function index(Ministry $ministry)
    {
        $this->authorize('access-ministry');

        $transactions = $ministry->transactions()
            ->latest()
            ->limit(50)
            ->get();

        $balance = $ministry->transactions()
            ->selectRaw("SUM(CASE WHEN type = 'credit' THEN amount ELSE 0 END) - SUM(CASE WHEN type = 'debit' THEN amount ELSE 0 END) as balance")
            ->value('balance') ?? 0;

        return view('admin.ministries.fund-overview', compact('ministry', 'transactions', 'balance'));
    }
}


