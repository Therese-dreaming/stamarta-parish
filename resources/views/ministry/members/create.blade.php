@extends('layouts.ministry')

@section('content')
<div class="p-6 max-w-2xl">
    <h1 class="text-2xl font-bold mb-4">Add Member - {{ $ministry->name }}</h1>

    <form action="{{ route('ministry.members.store') }}" method="POST" class="bg-white shadow rounded p-4">
        @csrf
        <div class="mb-3 relative">
            <label class="block text-sm font-medium">Link Existing User <span class="text-red-600">*</span></label>
            <input id="userSearch" type="text" placeholder="Search name or email to select a user" class="mt-1 w-full border rounded px-3 py-2" autocomplete="off" />
            <input type="hidden" name="user_id" id="selectedUserId" />
            <div id="userResults" class="absolute z-50 mt-1 w-full bg-white border rounded shadow-lg hidden max-h-60 overflow-auto"></div>
            @error('user_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
            <div class="text-xs text-gray-500 mt-1">You must link an existing user as a member.</div>
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Name</label>
            <input name="name" value="{{ old('name') }}" class="mt-1 w-full border rounded px-3 py-2 bg-gray-100" readonly />
            @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Email</label>
            <input name="email" type="email" value="{{ old('email') }}" class="mt-1 w-full border rounded px-3 py-2 bg-gray-100" readonly />
            @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Phone</label>
            <input name="phone" value="{{ old('phone') }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('phone')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Position</label>
            <input name="position" value="{{ old('position') }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('position')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="block text-sm font-medium">Joined At</label>
            <input name="joined_at" type="date" value="{{ old('joined_at') }}" class="mt-1 w-full border rounded px-3 py-2" />
            @error('joined_at')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="mb-4">
            <label class="block text-sm font-medium">Notes</label>
            <textarea name="notes" class="mt-1 w-full border rounded px-3 py-2" rows="4">{{ old('notes') }}</textarea>
            @error('notes')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>
        <div class="flex gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded" type="submit">Save</button>
            <a class="px-4 py-2 border rounded" href="{{ route('ministry.members.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const searchInput = document.getElementById('userSearch');
    const results = document.getElementById('userResults');
    const selectedUserId = document.getElementById('selectedUserId');

    let debounce;
    searchInput.addEventListener('input', () => {
        clearTimeout(debounce);
        const q = searchInput.value.trim();
        if (q.length < 2) {
            results.classList.add('hidden');
            results.innerHTML = '';
            selectedUserId.value = '';
            return;
        }
        debounce = setTimeout(() => {
            fetch(`{{ route('ministry.members.search-users') }}?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(list => {
                    if (!Array.isArray(list) || list.length === 0) {
                        results.classList.add('hidden');
                        results.innerHTML = '';
                        return;
                    }
                    results.classList.remove('hidden');
                    results.innerHTML = list.map((u, idx) => `
                        <button type="button" class="user-option w-full text-left px-3 py-2 hover:bg-gray-50 border-b ${idx === 0 ? 'bg-gray-50' : ''}" data-id="${u.id}" data-name="${u.name}" data-email="${u.email}">
                            <div class="font-medium text-sm">${u.name}</div>
                            <div class="text-xs text-gray-600">${u.email}</div>
                        </button>
                    `).join('');
                    Array.from(results.querySelectorAll('button.user-option')).forEach(btn => {
                        btn.addEventListener('click', () => {
                            selectedUserId.value = btn.dataset.id;
                            document.querySelector('input[name="name"]').value = btn.dataset.name;
                            document.querySelector('input[name="email"]').value = btn.dataset.email;
                            results.classList.add('hidden');
                            results.innerHTML = '';
                            searchInput.value = `${btn.dataset.name} <${btn.dataset.email}>`;
                        });
                    });
                });
        }, 250);
    });

    // Close dropdown on outside click
    document.addEventListener('click', (e) => {
        if (!results.contains(e.target) && e.target !== searchInput) {
            results.classList.add('hidden');
        }
    });
});
</script>
@endpush


