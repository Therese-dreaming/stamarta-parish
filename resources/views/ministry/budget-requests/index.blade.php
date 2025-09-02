@extends('layouts.ministry')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">My Budget Requests - {{ $ministry->name }}</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <a href="{{ route('ministry.budget-requests.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">New Request</a>
    </div>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Purpose</th>
                    <th class="py-2 px-4">Amount</th>
                    <th class="py-2 px-4">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $req->created_at->format('Y-m-d') }}</td>
                        <td class="py-2 px-4">{{ $req->purpose }}</td>
                        <td class="py-2 px-4">₱{{ number_format($req->amount, 2) }}</td>
                        <td class="py-2 px-4">{{ ucfirst($req->status) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-4 px-4 text-gray-500" colspan="4">No budget requests yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection


