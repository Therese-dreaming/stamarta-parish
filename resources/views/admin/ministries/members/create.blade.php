@extends("layouts.admin")

@section("title", "Add Member - " . $ministry->name)
@section("content")
<div class="space-y-4">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-6 relative">
            <div class="absolute right-0 top-0 w-20 h-20 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <div class="flex items-center mb-2">
                        <a href="{{ route("admin.ministries.members.index", $ministry) }}" class="text-white/80 hover:text-white transition-colors mr-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <h1 class="text-2xl font-bold text-white flex items-center">
                            <i class="fas fa-user-plus mr-2"></i>
                            Add Member - {{ $ministry->name }}
                        </h1>
                    </div>
                    <p class="text-white/80 text-sm">Add a new member to this ministry</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 border-b bg-gray-50">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                <i class="fas fa-user-edit mr-2 text-[#0d5c2f]"></i>
                Member Information
            </h2>
        </div>
        
        <form id="memberForm" action="{{ route("admin.ministries.members.store", $ministry) }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- User Typeahead Selector -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-search mr-1 text-gray-500"></i>
                    Find Existing User <span class="text-red-500">*</span>
                </label>
                <input type="hidden" name="user_id" id="user_id" />
                <div class="relative">
                    <input id="member_search" type="text" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200" 
                           placeholder="Type a name or email to search users" autocomplete="off" />
                    <div id="member_results" class="absolute z-20 mt-1 w-full bg-white border border-gray-200 rounded-lg shadow-md hidden max-h-64 overflow-auto"></div>
                </div>
                <p id="user_error" class="hidden text-red-600 text-sm mt-2 flex items-center"><i class="fas fa-exclamation-circle mr-1"></i>Please select an existing user.</p>
            </div>
            
            <!-- Name and Email Row (auto-filled, read-only) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-user mr-1 text-gray-500"></i>
                        Full Name
                    </label>
                    <input name="name" id="member_name" value="{{ old("name") }}" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700" 
                           placeholder="Select a user above" readonly />
                    @error("name")
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-1 text-gray-500"></i>
                        Email Address
                    </label>
                    <input name="email" id="member_email" type="email" value="{{ old("email") }}" 
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-700" 
                           placeholder="Select a user above" readonly />
                    @error("email")
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Phone and Position Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-phone mr-1 text-gray-500"></i>
                        Phone Number
                    </label>
                    <input name="phone" value="{{ old("phone") }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error("phone") border-red-500 @enderror" 
                           placeholder="Enter phone number (numbers only)" />
                    <p class="text-sm text-gray-500 mt-1 flex items-center">
                        <i class="fas fa-info-circle mr-1"></i>Numbers only (e.g., 09123456789)
                    </p>
                    @error("phone")
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-briefcase mr-1 text-gray-500"></i>
                        Position/Title
                    </label>
                    <input name="position" value="{{ old("position") }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error("position") border-red-500 @enderror" 
                           placeholder="Enter position or title" />
                    @error("position")
                        <div class="text-red-600 text-sm mt-2 flex items-center">
                            <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                        </div>
                    @enderror
                </div>
            </div>

            <!-- Role Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user-tag mr-1 text-gray-500"></i>
                    Ministry Role <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <label class="flex items-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                        <input type="radio" name="role" value="member" {{ old('role') == 'member' ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Member</div>
                            <div class="text-xs text-gray-500">Regular ministry member</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                        <input type="radio" name="role" value="officer" {{ old('role') == 'officer' ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Officer</div>
                            <div class="text-xs text-gray-500">Ministry officer</div>
                        </div>
                    </label>
                    
                    <label class="flex items-center p-4 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer transition-colors">
                        <input type="radio" name="role" value="assistant_ministry_head" {{ old('role') == 'assistant_ministry_head' ? 'checked' : '' }}
                               class="rounded border-gray-300 text-[#0d5c2f] focus:ring-[#0d5c2f]">
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Assistant Ministry Head</div>
                            <div class="text-xs text-gray-500">Max 2 per ministry</div>
                        </div>
                    </label>
                </div>
                @error("role")
                    <div class="text-red-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Joined Date -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-calendar-plus mr-1 text-gray-500"></i>
                    Joined Date
                </label>
                <input name="joined_at" type="date" value="{{ old("joined_at") }}" 
                       max="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error("joined_at") border-red-500 @enderror" />
                @error("joined_at")
                    <div class="text-red-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Notes -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-sticky-note mr-1 text-gray-500"></i>
                    Notes
                </label>
                <textarea name="notes" rows="4" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#0d5c2f] focus:border-transparent transition-all duration-200 @error("notes") border-red-500 @enderror" 
                          placeholder="Enter any additional notes about this member">{{ old("notes") }}</textarea>
                @error("notes")
                    <div class="text-red-600 text-sm mt-2 flex items-center">
                        <i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}
                    </div>
                @enderror
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route("admin.ministries.members.index", $ministry) }}" 
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 flex items-center">
                    <i class="fas fa-times mr-2"></i>
                    Cancel
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors duration-200 flex items-center shadow-sm hover:shadow">
                    <i class="fas fa-save mr-2"></i>
                    Add Member
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
(function(){
    const searchInput = document.getElementById('member_search');
    const resultsBox = document.getElementById('member_results');
    const userIdInput = document.getElementById('user_id');
    const nameInput = document.getElementById('member_name');
    const emailInput = document.getElementById('member_email');
    const userError = document.getElementById('user_error');
    const form = document.getElementById('memberForm');

    let debounceHandle = null;

    function hideResults(){ resultsBox.classList.add('hidden'); resultsBox.innerHTML=''; }

    function renderResults(users){
        if(!users || users.length === 0){ hideResults(); return; }
        resultsBox.innerHTML = users.map(u => (
            `<button type="button" class="w-full text-left px-3 py-2 hover:bg-gray-50 flex items-center" data-id="${u.id}" data-name="${u.name}" data-email="${u.email}">
                <i class="fas fa-user mr-2 text-gray-500"></i>
                <span class="font-medium text-gray-900">${u.name}</span>
                <span class="ml-2 text-xs text-gray-500">${u.email}</span>
            </button>`
        )).join('');
        resultsBox.classList.remove('hidden');
        resultsBox.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', function(){
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                const email = this.getAttribute('data-email');
                userIdInput.value = id;
                nameInput.value = name;
                emailInput.value = email;
                searchInput.value = `${name} <${email}>`;
                userError.classList.add('hidden');
                hideResults();
            });
        });
    }

    function doSearch(q){
        if(!q || q.trim().length < 2){ hideResults(); return; }
        fetch(`{{ route('admin.users.search') }}?q=${encodeURIComponent(q)}`)
            .then(r => r.json())
            .then(data => renderResults(data.users || data))
            .catch(() => hideResults());
    }

    searchInput.addEventListener('input', function(){
        userIdInput.value = '';
        nameInput.value = '';
        emailInput.value = '';
        if(debounceHandle) clearTimeout(debounceHandle);
        debounceHandle = setTimeout(() => doSearch(this.value), 250);
    });

    document.addEventListener('click', function(e){
        if(!resultsBox.contains(e.target) && e.target !== searchInput){ hideResults(); }
    });

    form.addEventListener('submit', function(e){
        if(!userIdInput.value){
            e.preventDefault();
            userError.classList.remove('hidden');
            searchInput.focus();
        }
    });
})();
</script>
@endpush
@endsection