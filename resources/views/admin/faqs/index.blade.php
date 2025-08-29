@extends('layouts.admin')

@section('title', 'Manage FAQs')

@section('content')
@include('components.toast')
<div class="space-y-5">
    <!-- Header -->
    <div class="bg-gradient-to-r from-[#0d5c2f] to-[#0d5c2f]/90 rounded-lg shadow-md overflow-hidden">
        <div class="px-5 py-5 relative">
            <div class="absolute right-0 top-0 w-16 h-16 bg-white/5 rounded-bl-full"></div>
            <div class="flex justify-between items-center relative z-10">
                <div>
                    <h1 class="text-xl font-bold text-white">Manage FAQs</h1>
                    <p class="text-white/80 mt-1.5 text-sm flex items-center">
                        <i class="fas fa-question-circle mr-2 text-sm"></i>Manage frequently asked questions for the chatbot
                    </p>
                </div>
                <a href="{{ route('admin.faqs.create') }}" 
                   class="group px-3.5 py-1.5 rounded-md bg-white/20 hover:bg-white/30 flex items-center justify-center text-white transition-all duration-200 shadow-sm hover:shadow text-sm">
                    <i class="fas fa-plus mr-1.5 text-sm group-hover:scale-110 transition-transform duration-200"></i>
                    <span>Add FAQ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-question-circle text-blue-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Total FAQs</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $faqs->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-check-circle text-green-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Active</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $faqs->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tags text-yellow-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Categories</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $categories->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
            <div class="flex items-center">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-robot text-purple-600 text-lg"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-600">Chatbot Ready</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $faqs->where('is_active', true)->count() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" id="search" placeholder="Search FAQs..." 
                           class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                    <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 text-sm"></i>
                </div>
                <select id="category-filter" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category }}">{{ ucfirst($category) }}</option>
                    @endforeach
                </select>
                <select id="status-filter" class="px-3 py-2 border border-gray-300 rounded-md focus:ring-[#0d5c2f] focus:border-[#0d5c2f] text-sm">
                    <option value="">All Status</option>
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>
            <div class="flex items-center space-x-2">
                <button id="view-toggle-table" class="px-3 py-1.5 bg-[#0d5c2f] text-white rounded-md text-xs hover:bg-[#0a4a26] transition-colors">
                    <i class="fas fa-table mr-1"></i>Table
                </button>
                <button id="view-toggle-cards" class="px-3 py-1.5 bg-gray-200 text-gray-700 rounded-md text-xs hover:bg-gray-300 transition-colors">
                    <i class="fas fa-th-large mr-1"></i>Cards
                </button>
            </div>
        </div>
    </div>

    <!-- FAQs Table View -->
    <div id="table-view" class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Question</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="faqs-table-body">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">
                                <span class="bg-gray-100 px-2 py-1 rounded text-xs font-medium">{{ $faq->order }}</span>
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900">{{ Str::limit($faq->question, 60) }}</div>
                                <div class="text-xs text-gray-500 mt-1">{{ Str::limit($faq->answer, 80) }}</div>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    {{ ucfirst($faq->category) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $faq->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $faq->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                {{ $faq->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <button onclick="toggleFaqStatus({{ $faq->id }})" 
                                            class="text-[#0d5c2f] hover:text-[#0a4a26] transition-colors" 
                                            title="{{ $faq->is_active ? 'Deactivate' : 'Activate' }}">
                                        <i class="fas fa-{{ $faq->is_active ? 'eye-slash' : 'eye' }} text-sm"></i>
                                    </button>
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" 
                                       class="text-blue-600 hover:text-blue-900 transition-colors" 
                                       title="Edit">
                                        <i class="fas fa-edit text-sm"></i>
                                    </a>
                                    <button onclick="deleteFaq({{ $faq->id }})" 
                                            class="text-red-600 hover:text-red-900 transition-colors" 
                                            title="Delete">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                                <div class="flex flex-col items-center">
                                    <i class="fas fa-question-circle text-4xl text-gray-300 mb-2"></i>
                                    <p class="text-sm">No FAQs found</p>
                                    <a href="{{ route('admin.faqs.create') }}" class="mt-2 text-[#0d5c2f] hover:text-[#0a4a26] text-sm">
                                        Create your first FAQ
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- FAQs Card View -->
    <div id="card-view" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="faqs-card-body">
            @forelse($faqs as $faq)
                <div class="bg-white rounded-lg shadow-md border border-gray-200 p-4 hover:shadow-lg transition-shadow">
                    <div class="flex items-start justify-between mb-3">
                        <span class="bg-gray-100 px-2 py-1 rounded text-xs font-medium text-gray-600">{{ $faq->order }}</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $faq->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $faq->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    
                    <h3 class="font-medium text-gray-900 mb-2 text-sm">{{ Str::limit($faq->question, 80) }}</h3>
                    <p class="text-xs text-gray-600 mb-3">{{ Str::limit($faq->answer, 120) }}</p>
                    
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ ucfirst($faq->category) }}
                        </span>
                        <div class="flex items-center space-x-2">
                            <button onclick="toggleFaqStatus({{ $faq->id }})" 
                                    class="text-[#0d5c2f] hover:text-[#0a4a26] transition-colors" 
                                    title="{{ $faq->is_active ? 'Deactivate' : 'Activate' }}">
                                <i class="fas fa-{{ $faq->is_active ? 'eye-slash' : 'eye' }} text-sm"></i>
                            </button>
                            <a href="{{ route('admin.faqs.edit', $faq) }}" 
                               class="text-blue-600 hover:text-blue-900 transition-colors" 
                               title="Edit">
                                <i class="fas fa-edit text-sm"></i>
                            </a>
                            <button onclick="deleteFaq({{ $faq->id }})" 
                                    class="text-red-600 hover:text-red-900 transition-colors" 
                                    title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-8 text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class="fas fa-question-circle text-4xl text-gray-300 mb-2"></i>
                        <p class="text-sm">No FAQs found</p>
                        <a href="{{ route('admin.faqs.create') }}" class="mt-2 text-[#0d5c2f] hover:text-[#0a4a26] text-sm">
                            Create your first FAQ
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    @if($faqs->hasPages())
        <div class="bg-white rounded-lg shadow-md border border-gray-200 px-4 py-3">
            {{ $faqs->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div id="delete-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mt-4">Delete FAQ</h3>
            <div class="mt-2 px-7 py-3">
                <p class="text-sm text-gray-500">Are you sure you want to delete this FAQ? This action cannot be undone.</p>
            </div>
            <div class="flex justify-center space-x-3 mt-4">
                <button id="delete-cancel" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition-colors">
                    Cancel
                </button>
                <button id="delete-confirm" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentView = 'table';
let faqToDelete = null;

// View toggle functionality
document.getElementById('view-toggle-table').addEventListener('click', function() {
    setView('table');
});

document.getElementById('view-toggle-cards').addEventListener('click', function() {
    setView('cards');
});

function setView(view) {
    currentView = view;
    
    if (view === 'table') {
        document.getElementById('table-view').classList.remove('hidden');
        document.getElementById('card-view').classList.add('hidden');
        document.getElementById('view-toggle-table').classList.add('bg-[#0d5c2f]', 'text-white');
        document.getElementById('view-toggle-table').classList.remove('bg-gray-200', 'text-gray-700');
        document.getElementById('view-toggle-cards').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('view-toggle-cards').classList.remove('bg-[#0d5c2f]', 'text-white');
    } else {
        document.getElementById('table-view').classList.add('hidden');
        document.getElementById('card-view').classList.remove('hidden');
        document.getElementById('view-toggle-cards').classList.add('bg-[#0d5c2f]', 'text-white');
        document.getElementById('view-toggle-cards').classList.remove('bg-gray-200', 'text-gray-700');
        document.getElementById('view-toggle-table').classList.add('bg-gray-200', 'text-gray-700');
        document.getElementById('view-toggle-table').classList.remove('bg-[#0d5c2f]', 'text-white');
    }
    
    localStorage.setItem('faq-view', view);
}

// Load saved view preference
document.addEventListener('DOMContentLoaded', function() {
    const savedView = localStorage.getItem('faq-view') || 'table';
    setView(savedView);
});

// Toggle FAQ status
function toggleFaqStatus(faqId) {
    fetch(`/admin/faqs/${faqId}/toggle-status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Delete FAQ
function deleteFaq(faqId) {
    faqToDelete = faqId;
    document.getElementById('delete-modal').classList.remove('hidden');
}

document.getElementById('delete-cancel').addEventListener('click', function() {
    document.getElementById('delete-modal').classList.add('hidden');
    faqToDelete = null;
});

document.getElementById('delete-confirm').addEventListener('click', function() {
    if (faqToDelete) {
        fetch(`/admin/faqs/${faqToDelete}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
    document.getElementById('delete-modal').classList.add('hidden');
    faqToDelete = null;
});

// Search and filter functionality
document.getElementById('search').addEventListener('input', filterFaqs);
document.getElementById('category-filter').addEventListener('change', filterFaqs);
document.getElementById('status-filter').addEventListener('change', filterFaqs);

function filterFaqs() {
    const searchTerm = document.getElementById('search').value.toLowerCase();
    const categoryFilter = document.getElementById('category-filter').value;
    const statusFilter = document.getElementById('status-filter').value;
    
    const rows = document.querySelectorAll('#faqs-table-body tr');
    const cards = document.querySelectorAll('#faqs-card-body > div');
    
    rows.forEach(row => {
        const question = row.querySelector('td:nth-child(2)')?.textContent.toLowerCase() || '';
        const category = row.querySelector('td:nth-child(3)')?.textContent.toLowerCase() || '';
        const status = row.querySelector('td:nth-child(4)')?.textContent.toLowerCase() || '';
        
        const matchesSearch = question.includes(searchTerm);
        const matchesCategory = !categoryFilter || category.includes(categoryFilter.toLowerCase());
        const matchesStatus = !statusFilter || status.includes(statusFilter === '1' ? 'active' : 'inactive');
        
        row.style.display = matchesSearch && matchesCategory && matchesStatus ? '' : 'none';
    });
    
    cards.forEach(card => {
        const question = card.querySelector('h3')?.textContent.toLowerCase() || '';
        const category = card.querySelector('.bg-blue-100')?.textContent.toLowerCase() || '';
        const status = card.querySelector('.bg-green-100, .bg-red-100')?.textContent.toLowerCase() || '';
        
        const matchesSearch = question.includes(searchTerm);
        const matchesCategory = !categoryFilter || category.includes(categoryFilter.toLowerCase());
        const matchesStatus = !statusFilter || status.includes(statusFilter === '1' ? 'active' : 'inactive');
        
        card.style.display = matchesSearch && matchesCategory && matchesStatus ? '' : 'none';
    });
}
</script>
@endsection 