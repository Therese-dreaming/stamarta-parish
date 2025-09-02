@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">{{ $ministry->name }} Fund Overview</h1>

    <div class="bg-white shadow rounded p-4 mb-6">
        <div class="text-gray-600">Current Balance</div>
        <div class="text-3xl font-semibold">₱{{ number_format($balance, 2) }}</div>
    </div>

    <div class="bg-white shadow rounded">
        <div class="p-4 border-b font-semibold">Recent Transactions</div>
        <div class="p-4 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left">
                        <th class="py-2 pr-4">Date</th>
                        <th class="py-2 pr-4">Type</th>
                        <th class="py-2 pr-4">Amount</th>
                        <th class="py-2 pr-4">Description</th>
                        <th class="py-2 pr-4">Source</th>
                        <th class="py-2 pr-4">Entered By</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $tx)
                        <tr class="border-t">
                            <td class="py-2 pr-4">{{ $tx->created_at->format('Y-m-d H:i') }}</td>
                            <td class="py-2 pr-4">
                                <span class="px-2 py-1 rounded text-white {{ $tx->type === 'credit' ? 'bg-green-500' : 'bg-red-500' }}">
                                    {{ ucfirst($tx->type) }}
                                </span>
                            </td>
                            <td class="py-2 pr-4">₱{{ number_format($tx->amount, 2) }}</td>
                            <td class="py-2 pr-4">{{ $tx->description }}</td>
                            <td class="py-2 pr-4">{{ class_basename($tx->source_type) }} #{{ $tx->source_id }}</td>
                            <td class="py-2 pr-4">{{ optional($tx->enteredBy)->name }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="py-4 text-center text-gray-500" colspan="6">No transactions yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </div>
</div>
@endsection


