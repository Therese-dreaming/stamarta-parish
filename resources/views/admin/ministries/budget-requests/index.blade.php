@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Ministry Budget Requests</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">{{ session('error') }}</div>
    @endif

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="py-2 px-4">Date</th>
                    <th class="py-2 px-4">Ministry</th>
                    <th class="py-2 px-4">Purpose</th>
                    <th class="py-2 px-4">Amount</th>
                    <th class="py-2 px-4">Status</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $req->created_at->format('Y-m-d') }}</td>
                        <td class="py-2 px-4">{{ $req->ministry->name }}</td>
                        <td class="py-2 px-4">{{ $req->purpose }}</td>
                        <td class="py-2 px-4">₱{{ number_format($req->amount, 2) }}</td>
                        <td class="py-2 px-4">{{ ucfirst($req->status) }}</td>
                        <td class="py-2 px-4">
                            @if($req->status === 'pending')
                                <form action="{{ route('admin.ministries.budget-requests.approve', $req) }}" method="POST" class="inline">
                                    @csrf
                                    <button class="px-3 py-1 bg-green-600 text-white rounded" type="submit">Approve</button>
                                </form>
                                <form action="{{ route('admin.ministries.budget-requests.reject', $req) }}" method="POST" class="inline ml-2">
                                    @csrf
                                    <button class="px-3 py-1 bg-red-600 text-white rounded" type="submit">Reject</button>
                                </form>
                            @else
                                —
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $requests->links() }}
    </div>
</div>
@endsection


