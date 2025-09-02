@extends('layouts.admin')

@section('content')
<div class="space-y-4">
	<div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
		<div class="px-6 py-6 relative">
			<div class="absolute right-0 top-0 w-24 h-24 bg-white/5 rounded-bl-full"></div>
			<div class="flex justify-between items-center relative z-10">
				<div>
					<h1 class="text-2xl font-bold text-white flex items-center"><i class="fas fa-people-group mr-2"></i>Edit Ministry</h1>
					<p class="text-white/80 mt-1 text-sm">Update ministry details and head</p>
				</div>
				<a href="{{ route('admin.ministries.index') }}" class="group px-3 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-sm">
					<i class="fas fa-arrow-left mr-2 text-sm group-hover:-translate-x-1 transition-transform duration-200"></i>
					<span>Back to Ministries</span>
				</a>
			</div>
		</div>
	</div>

	<div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
		<form action="{{ route('admin.ministries.update', $ministry) }}" method="POST" id="ministryForm">
			@csrf
			@method('PUT')
			<div class="p-4 border-b border-gray-200 bg-gray-50">
				<h2 class="text-lg font-semibold text-gray-900 flex items-center">
					<i class="fas fa-clipboard-list mr-2 text-[#0d5c2f]"></i>
					Ministry Details
				</h2>
			</div>

			<div class="p-4 space-y-4">
				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
					<div class="relative">
						<i class="fas fa-tag absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
						<input name="name" value="{{ old('name', $ministry->name) }}" class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm" required />
					</div>
					@error('name')<div class="text-red-600 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>@enderror
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
					<div class="relative">
						<i class="fas fa-link absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
						<input name="slug" value="{{ old('slug', $ministry->slug) }}" class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm" />
					</div>
					@error('slug')<div class="text-red-600 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>@enderror
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Head</label>
					<div class="relative">
						<i class="fas fa-user-tie absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
						<input type="hidden" name="head_user_id" id="head_user_id" value="{{ old('head_user_id', $ministry->head_user_id) }}">
						<input type="text" id="head_search" class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm" placeholder="Type name or email..." autocomplete="off" value="{{ optional($ministry->head)->name ? ($ministry->head->name . ' (' . $ministry->head->email . ')') : '' }}" />
						<div id="head_results" class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md hidden max-h-56 overflow-auto">
							<!-- Results injected here -->
						</div>
					</div>
					<p class="text-xs text-gray-500 mt-1 flex items-center"><i class="fas fa-info-circle mr-1"></i>Selecting a head automatically grants the Ministry Head role</p>
					@error('head_user_id')<div class="text-red-600 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>@enderror
				</div>

				<div>
					<label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
					<div class="relative">
						<i class="fas fa-align-left absolute left-3 top-3 text-gray-400"></i>
						<textarea name="description" rows="4" class="w-full pl-10 px-3 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">{{ old('description', $ministry->description) }}</textarea>
					</div>
					@error('description')<div class="text-red-600 text-sm mt-1 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</div>@enderror
				</div>
			</div>

			<div class="p-4 border-t bg-gray-50 flex justify-end">
				<button type="submit" class="px-4 py-2 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] text-sm flex items-center"><i class="fas fa-save mr-2"></i>Save Changes</button>
			</div>
		</form>
	</div>
</div>

@push('scripts')
<script>
(function(){
	const input = document.getElementById('head_search');
	const results = document.getElementById('head_results');
	const hidden = document.getElementById('head_user_id');
	let controller;

	function hide(){ results.classList.add('hidden'); results.innerHTML = ''; }
	function show(){ results.classList.remove('hidden'); }

	async function search(q){
		if(controller){ controller.abort(); }
		controller = new AbortController();
		const url = "{{ route('admin.users.search') }}?q=" + encodeURIComponent(q);
		const res = await fetch(url, { signal: controller.signal });
		if(!res.ok) return [];
		return await res.json();
	}

	input.addEventListener('input', async function(){
		hidden.value = '';
		const q = this.value.trim();
		if(q.length < 2){ hide(); return; }
		const items = await search(q);
		if(!items.length){ results.innerHTML = '<div class="px-3 py-2 text-sm text-gray-500">No results</div>'; show(); return; }
		results.innerHTML = items.map(u => `
			<button type=\"button\" data-id=\"${u.id}\" data-label=\"${u.name} (${u.email})\" class=\"w-full text-left px-3 py-2 hover:bg-gray-100 text-sm flex items-center\">
				<i class=\"fas fa-user mr-2 text-[#0d5c2f]\"></i>
				<span>${u.name} <span class=\"text-gray-500\">(${u.email})</span></span>
			</button>
		`).join('');
		show();
	});

	results.addEventListener('click', function(e){
		const btn = e.target.closest('button[data-id]');
		if(!btn) return;
		hidden.value = btn.getAttribute('data-id');
		input.value = btn.getAttribute('data-label');
		hide();
	});

	document.addEventListener('click', function(e){
		if(!results.contains(e.target) && e.target !== input){ hide(); }
	});

	document.getElementById('ministryForm').addEventListener('submit', function(e){
		if(!hidden.value){
			e.preventDefault();
			alert('Please select a ministry head from the suggestions.');
			input.focus();
		}
	});
})();
</script>
@endpush
@endsection


