@extends('layouts.ministry')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Add Activity - {{ $ministry->name }}</h1>

    <form action="{{ route('ministry.activities.store') }}" method="POST" class="bg-white shadow rounded p-4">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium">Title</label>
            <input name="title" value="{{ old('title') }}" class="mt-1 w-full border rounded px-3 py-2" required />
            @error('title')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Start</label>
            <input name="start_at" type="datetime-local" value="{{ old('start_at') }}" class="mt-1 w-full border rounded px-3 py-2" required />
            @error('start_at')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">End</label>
            <input name="end_at" type="datetime-local" value="{{ old('end_at') }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('end_at')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_all_day" value="1" class="mr-2" {{ old('is_all_day') ? 'checked' : '' }} /> All Day
            </label>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Location</label>
            <input name="location" value="{{ old('location') }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('location')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('description') }}</textarea>
            @error('description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_public" value="1" class="mr-2" {{ old('is_public') ? 'checked' : '' }} /> Public (visible to all)
            </label>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded" type="submit">Save</button>
            <a class="px-4 py-2 border rounded" href="{{ route('ministry.activities.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection


