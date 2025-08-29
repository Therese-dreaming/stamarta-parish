@extends(isset($isStaff) && $isStaff ? 'layouts.staff' : 'layouts.admin')

@section('title', 'Manage Pages')

@section('content')
@include('components.toast')
<div class="space-y-6">
    <!-- Header with colored background -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-xl shadow-md overflow-hidden">
        <div class="px-6 py-8 relative">
            <div class="absolute right-0 top-0 w-32 h-32 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-3xl font-bold text-white">Page Management</h1>
                    <p class="text-white/80 mt-2 flex items-center">
                        <i class="fas fa-file-alt mr-2"></i>Create and manage your website content
                    </p>
                </div>
                @if(!isset($isStaff) || !$isStaff)
                <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.create') : route('admin.cms.pages.create') }}" 
                   class="group px-4 py-2 rounded-lg bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow" title="Create Page">
                    <i class="fas fa-plus mr-2 text-sm group-hover:rotate-90 transition-transform duration-300"></i>
                    <span>Create Page</span>
                </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden">
        <!-- Card Header with Search and View Toggle -->
        <div class="p-4 border-b border-gray-200 bg-gray-50">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center space-y-4 md:space-y-0">
                <div class="flex items-center space-x-4">
                    <h2 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-file-text mr-2 text-[#0d5c2f]"></i>
                        Website Pages
                    </h2>
                    <span class="px-3 py-1 bg-[#0d5c2f]/10 text-[#0d5c2f] rounded-full text-sm font-medium">
                        {{ $pages->total() }} page{{ $pages->total() != 1 ? 's' : '' }}
                    </span>
                </div>
                
                <div class="flex items-center space-x-3">
                    <!-- Search Input -->
                    <div class="relative">
                        <input type="text" id="page-search" placeholder="Search pages..." 
                               class="w-full md:w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                        <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fas fa-search"></i>
                        </div>
                    </div>
                    
                    <!-- View Toggle -->
                    <div class="flex items-center bg-gray-100 rounded-lg p-1">
                        <button id="table-view-btn" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors view-btn active">
                            <i class="fas fa-table mr-1.5"></i>Table
                        </button>
                        <button id="card-view-btn" class="px-3 py-1.5 rounded-md text-sm font-medium transition-colors view-btn">
                            <i class="fas fa-th-large mr-1.5"></i>Cards
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if($pages->count() > 0)
                <!-- Table View -->
                <div id="table-view" class="view-content">
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Page</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Author</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($pages as $page)
                                <tr class="hover:bg-gray-50 transition-colors page-row">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="h-10 w-10 rounded-lg bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center border-2 border-gray-200 shadow-sm">
                                                <i class="fas fa-file-alt text-white text-lg"></i>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 page-title">{{ $page->title }}</div>
                                                <div class="text-sm text-gray-500 flex items-center">
                                                    <i class="fas fa-link mr-1 text-xs"></i>/{{ $page->slug }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($page->is_published)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Published
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>Draft
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f]"></i>
                                            {{ $page->updated_at->format('M d, Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="flex items-center">
                                            <i class="fas fa-user mr-2 text-[#0d5c2f]"></i>
                                            {{ $page->creator->name ?? 'Unknown' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.preview', $page) : route('admin.cms.pages.preview', $page) }}" 
                                               class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 flex items-center justify-center text-blue-600 transition-colors" title="Preview">
                                                <i class="fas fa-eye text-sm"></i>
                                            </a>
                                            <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.edit', $page) : route('admin.cms.pages.edit', $page) }}" 
                                               class="w-8 h-8 rounded-lg bg-green-50 hover:bg-green-100 flex items-center justify-center text-green-600 transition-colors" title="Edit">
                                                <i class="fas fa-edit text-sm"></i>
                                            </a>
                                            <button type="button" onclick="openModal('toggle-publish-modal-{{ $page->id }}')"
                                                    class="w-8 h-8 rounded-lg bg-{{ $page->is_published ? 'yellow' : 'green' }}-50 hover:bg-{{ $page->is_published ? 'yellow' : 'green' }}-100 flex items-center justify-center text-{{ $page->is_published ? 'yellow' : 'green' }}-600 transition-colors" 
                                                    title="{{ $page->is_published ? 'Unpublish' : 'Publish' }}">
                                                <i class="fas fa-{{ $page->is_published ? 'pause' : 'play' }} text-sm"></i>
                                            </button>
                                            <button type="button" onclick="openModal('delete-modal-{{ $page->id }}')"
                                                    class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600 transition-colors" title="Delete">
                                                <i class="fas fa-trash text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Card View -->
                <div id="card-view" class="view-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pages as $page)
                        <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-all duration-200 page-card overflow-hidden">
                            <div class="h-3 bg-[#0d5c2f]"></div>
                            <div class="p-6">
                                <div class="flex items-center mb-4">
                                    <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center border-2 border-gray-200 shadow-sm">
                                        <i class="fas fa-file-alt text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-lg font-semibold text-gray-900 page-title">{{ $page->title }}</h3>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-link mr-1"></i>/{{ $page->slug }}
                                        </p>
                                    </div>
                                </div>

                                <div class="space-y-3 mb-4">
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-user mr-2 text-[#0d5c2f] w-4"></i>
                                        <span>{{ $page->creator->name ?? 'Unknown' }}</span>
                                    </div>
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-calendar-alt mr-2 text-[#0d5c2f] w-4"></i>
                                        <span>{{ $page->updated_at->format('M d, Y') }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center justify-between">
                                    @if($page->is_published)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Published
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-clock mr-1"></i>Draft
                                        </span>
                                    @endif

                                    <div class="flex items-center space-x-2">
                                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.preview', $page) : route('admin.cms.pages.preview', $page) }}" 
                                           class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 flex items-center justify-center text-blue-600 transition-colors" title="Preview">
                                            <i class="fas fa-eye text-sm"></i>
                                        </a>
                                        <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.edit', $page) : route('admin.cms.pages.edit', $page) }}" 
                                           class="w-8 h-8 rounded-lg bg-green-50 hover:bg-green-100 flex items-center justify-center text-green-600 transition-colors" title="Edit">
                                            <i class="fas fa-edit text-sm"></i>
                                        </a>
                                        <button type="button" onclick="openModal('toggle-publish-modal-{{ $page->id }}')"
                                                class="w-8 h-8 rounded-lg bg-{{ $page->is_published ? 'yellow' : 'green' }}-50 hover:bg-{{ $page->is_published ? 'yellow' : 'green' }}-100 flex items-center justify-center text-{{ $page->is_published ? 'yellow' : 'green' }}-600 transition-colors" 
                                                title="{{ $page->is_published ? 'Unpublish' : 'Publish' }}">
                                            <i class="fas fa-{{ $page->is_published ? 'pause' : 'play' }} text-sm"></i>
                                        </button>
                                        <button type="button" onclick="openModal('delete-modal-{{ $page->id }}')"
                                                class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 flex items-center justify-center text-red-600 transition-colors" title="Delete">
                                            <i class="fas fa-trash text-sm"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- No Results Message (Hidden by default) -->
                <div id="no-search-results" class="hidden text-center py-12">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-search text-gray-400 text-xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No pages found</h3>
                    <p class="text-gray-600">Try adjusting your search criteria</p>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $pages->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full bg-gradient-to-br from-[#0d5c2f] to-[#0a4a26] flex items-center justify-center shadow-sm">
                        <i class="fas fa-file-alt text-white text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No pages found</h3>
                    <p class="text-gray-600 mb-6">Get started by creating your first page for your website.</p>
                    @if(!isset($isStaff) || !$isStaff)
                    <a href="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.create') : route('admin.cms.pages.create') }}" 
                       class="inline-flex items-center px-6 py-3 bg-[#0d5c2f] text-white rounded-lg hover:bg-[#0a4a26] transition-colors">
                        <i class="fas fa-plus mr-2"></i>Create First Page
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals for each page -->
@foreach($pages as $page)
    <!-- Toggle Publish Modal -->
    <x-modal 
        id="toggle-publish-modal-{{ $page->id }}"
        title="{{ $page->is_published ? 'Unpublish' : 'Publish' }} Page"
        message="Are you sure you want to {{ $page->is_published ? 'unpublish' : 'publish' }} '{{ $page->title }}'? {{ $page->is_published ? 'This will make the page unavailable to visitors.' : 'This will make the page visible to visitors.' }}"
        confirmText="{{ $page->is_published ? 'Unpublish' : 'Publish' }}"
        confirmClass="{{ $page->is_published ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }}">
        <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.toggle-publish', $page) : route('admin.cms.pages.toggle-publish', $page) }}" method="POST">
            @csrf
        </form>
    </x-modal>

    <!-- Delete Modal -->
    <x-modal 
        id="delete-modal-{{ $page->id }}"
        title="Delete Page"
        message="Are you sure you want to delete '{{ $page->title }}'? This action cannot be undone and will permanently remove the page and all its content."
        confirmText="Delete Page"
        confirmClass="bg-red-600 hover:bg-red-700">
        <form action="{{ isset($isStaff) && $isStaff ? route('staff.cms.pages.destroy', $page) : route('admin.cms.pages.destroy', $page) }}" method="POST">
            @csrf
            @method('DELETE')
        </form>
    </x-modal>
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tableViewBtn = document.getElementById('table-view-btn');
    const cardViewBtn = document.getElementById('card-view-btn');
    const tableView = document.getElementById('table-view');
    const cardView = document.getElementById('card-view');
    const viewBtns = document.querySelectorAll('.view-btn');
    const searchInput = document.getElementById('page-search');
    const noResultsDiv = document.getElementById('no-search-results');

    // Load saved preference
    const savedView = localStorage.getItem('page-view-preference') || 'table';
    setActiveView(savedView);

    tableViewBtn.addEventListener('click', function() {
        setActiveView('table');
        localStorage.setItem('page-view-preference', 'table');
    });

    cardViewBtn.addEventListener('click', function() {
        setActiveView('card');
        localStorage.setItem('page-view-preference', 'card');
    });

    // Search functionality
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase().trim();
        const pageRows = document.querySelectorAll('.page-row');
        const pageCards = document.querySelectorAll('.page-card');
        let visibleCount = 0;
        
        // Search in table view
        pageRows.forEach(row => {
            const pageTitle = row.querySelector('.page-title').textContent.toLowerCase();
            if (pageTitle.includes(searchTerm)) {
                row.classList.remove('hidden');
                visibleCount++;
            } else {
                row.classList.add('hidden');
            }
        });
        
        // Search in card view
        pageCards.forEach(card => {
            const pageTitle = card.querySelector('.page-title').textContent.toLowerCase();
            if (pageTitle.includes(searchTerm)) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });
        
        // Show/hide no results message
        if (visibleCount === 0 && searchTerm !== '') {
            noResultsDiv.classList.remove('hidden');
            tableView.classList.add('hidden');
            cardView.classList.add('hidden');
        } else {
            noResultsDiv.classList.add('hidden');
            if (savedView === 'table') {
                tableView.classList.remove('hidden');
            } else {
                cardView.classList.remove('hidden');
            }
        }
    });

    function setActiveView(view) {
        // Update button states
        viewBtns.forEach(btn => {
            btn.classList.remove('active', 'bg-white', 'text-gray-900');
            btn.classList.add('text-gray-600');
        });

        if (view === 'table') {
            tableViewBtn.classList.add('active', 'bg-white', 'text-gray-900');
            tableView.classList.remove('hidden');
            cardView.classList.add('hidden');
        } else {
            cardViewBtn.classList.add('active', 'bg-white', 'text-gray-900');
            cardView.classList.remove('hidden');
            tableView.classList.add('hidden');
        }
    }
});
</script>

<style>
.view-btn.active {
    background-color: white;
    color: #111827;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

.page-card:hover {
    transform: translateY(-2px);
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.page-row, .page-card {
    animation: fadeIn 0.3s ease-in-out;
}
</style>
@endsection 