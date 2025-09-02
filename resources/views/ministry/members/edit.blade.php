@extends('layouts.ministry')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Edit Member - {{ $ministry->name }}</h1>

    <form action="{{ route('ministry.members.update', $member) }}" method="POST" class="bg-white shadow rounded p-4">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name', $member->name) }}" class="mt-1 w-full border rounded px-3 py-2" required />
            @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Email</label>
            <input name="email" type="email" value="{{ old('email', $member->email) }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Phone</label>
            <input name="phone" value="{{ old('phone', $member->phone) }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('phone')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Position</label>
            <input name="position" value="{{ old('position', $member->position) }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('position')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Joined At</label>
            <input name="joined_at" type="date" value="{{ old('joined_at', optional($member->joined_at)->format('Y-m-d')) }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('joined_at')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" class="mr-2" {{ old('is_active', $member->is_active) ? 'checked' : '' }} /> Active
            </label>
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Notes</label>
            <textarea name="notes" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('notes', $member->notes) }}</textarea>
            @error('notes')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded" type="submit">Save</button>
            <a class="px-4 py-2 border rounded" href="{{ route('ministry.members.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection


