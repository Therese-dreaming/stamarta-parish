@extends('layouts.ministry')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">New Budget Request - {{ $ministry->name }}</h1>

    <form action="{{ route('ministry.budget-requests.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded p-4">
        @csrf
        <div class="mb-3">
            <label class="block text-sm font-medium">Amount</label>
            <input name="amount" type="number" step="0.01" min="0.01" value="{{ old('amount') }}" class="mt-1 w-full border rounded px-3 py-2" required />
            @error('amount')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Purpose</label>
            <input name="purpose" value="{{ old('purpose') }}" class="mt-1 w-full border rounded px-3 py-2" required />
            @error('purpose')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Details</label>
            <textarea name="details" class="mt-1 w-full border rounded px-3 py-2" rows="5">{{ old('details') }}</textarea>
            @error('details')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Attachments</label>
            <input type="file" name="attachments[]" multiple class="mt-1 w-full border rounded px-3 py-2" />
            <div class="text-xs text-gray-500 mt-1">Allowed: pdf, jpg, jpeg, png, doc, docx, xls, xlsx (max 8MB each)</div>
            @error('attachments.*')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded" type="submit">Submit</button>
            <a class="px-4 py-2 border rounded" href="{{ route('ministry.budget-requests.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection


