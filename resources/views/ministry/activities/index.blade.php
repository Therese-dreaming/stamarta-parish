@extends('layouts.ministry')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">My Ministry Activities - {{ $ministry->name }}</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <a href="{{ route('ministry.activities.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">Add Activity</a>
    </div>

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="py-2 px-4">Title</th>
                    <th class="py-2 px-4">Start</th>
                    <th class="py-2 px-4">End</th>
                    <th class="py-2 px-4">Location</th>
                    <th class="py-2 px-4">Visibility</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activities as $act)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $act->title }}</td>
                        <td class="py-2 px-4">{{ $act->is_all_day ? $act->start_at->format('Y-m-d') : $act->start_at->format('Y-m-d H:i') }}</td>
                        <td class="py-2 px-4">{{ $act->end_at ? ($act->is_all_day ? $act->end_at->format('Y-m-d') : $act->end_at->format('Y-m-d H:i')) : '—' }}</td>
                        <td class="py-2 px-4">{{ $act->location ?: '—' }}</td>
                        <td class="py-2 px-4">{{ $act->is_public ? 'Public' : 'Internal' }}</td>
                        <td class="py-2 px-4">
                            <a class="text-blue-600 hover:underline" href="{{ route('ministry.activities.edit', $act) }}">Edit</a>
                            <form class="inline ml-2" action="{{ route('ministry.activities.destroy', $act) }}" method="POST" onsubmit="return confirm('Delete this activity?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-4 px-4 text-gray-500" colspan="6">No activities yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $activities->links() }}
    </div>
</div>
@endsection


