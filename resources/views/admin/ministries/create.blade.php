@extends('layouts.admin')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Create Ministry</h1>

    <form action="{{ route('admin.ministries.store') }}" method="POST" class="bg-white shadow rounded p-4">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name') }}" class="mt-1 w-full border rounded px-3 py-2" required />
            @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Slug</label>
            <input name="slug" value="{{ old('slug') }}" class="mt-1 w-full border rounded px-3 py-2" placeholder="auto-generated if empty" />
            @error('slug')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Head (optional)</label>
            <select name="head_user_id" class="mt-1 w-full border rounded px-3 py-2">
                <option value="">— None —</option>
                @foreach($heads as $u)
                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})</option>
                @endforeach
            </select>
            @error('head_user_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('description') }}</textarea>
            @error('description')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="inline-flex items-center">
                <input type="checkbox" name="is_active" value="1" class="mr-2" checked /> Active
            </label>
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded" type="submit">Save</button>
            <a class="px-4 py-2 border rounded" href="{{ route('admin.ministries.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection


