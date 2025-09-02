@extends('layouts.admin')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-2xl font-bold">Ministries</h1>
        <a href="{{ route('admin.ministries.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">New Ministry</a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif

    <div class="bg-white shadow rounded overflow-x-auto">
        <table class="min-w-full text-sm">
            <thead>
                <tr class="text-left">
                    <th class="py-2 px-4">Name</th>
                    <th class="py-2 px-4">Head</th>
                    <th class="py-2 px-4">Status</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ministries as $ministry)
                    <tr class="border-t">
                        <td class="py-2 px-4">{{ $ministry->name }}</td>
                        <td class="py-2 px-4">{{ optional($ministry->head)->name ?: '—' }}</td>
                        <td class="py-2 px-4">{{ $ministry->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="py-2 px-4">
                            <a class="text-blue-600 hover:underline" href="{{ route('admin.ministries.edit', $ministry) }}">Edit</a>
                            <form class="inline ml-2" action="{{ route('admin.ministries.destroy', $ministry) }}" method="POST" onsubmit="return confirm('Delete this ministry?')">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-600 hover:underline" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="py-4 px-4 text-gray-500" colspan="4">No ministries yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $ministries->links() }}
    </div>
</div>
@endsection


