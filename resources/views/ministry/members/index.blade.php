@extends('layouts.ministry')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">My Ministry Members - {{ $ministry->name }}</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <a href="{{ route('ministry.members.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Add Member</a>
    </div>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="py-2 px-4">Name</th>
                    <th class="py-2 px-4">Position</th>
                    <th class="py-2 px-4">Email</th>
                    <th class="py-2 px-4">Phone</th>
                    <th class="py-2 px-4">Joined</th>
                    <th class="py-2 px-4">Status</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($members as $member)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $member->name }}</td>
                        <td class="py-2 px-4">{{ $member->position }}</td>
                        <td class="py-2 px-4">{{ $member->email }}</td>
                        <td class="py-2 px-4">{{ $member->phone }}</td>
                        <td class="py-2 px-4">{{ optional($member->joined_at)->format('Y-m-d') }}</td>
                        <td class="py-2 px-4">{{ $member->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="py-2 px-4">
                            <a class="text-blue-600 hover:underline" href="{{ route('ministry.members.edit', $member) }}">Edit</a>
                            <form class="inline ml-2" action="{{ route('ministry.members.destroy', $member) }}" method="POST" onsubmit="return confirm('Delete this member?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-4 px-4 text-gray-500" colspan="7">No members yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $members->links() }}
    </div>
</div>
@endsection


