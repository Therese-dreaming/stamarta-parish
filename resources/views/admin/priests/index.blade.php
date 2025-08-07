@extends('layouts.admin')

@section('title', 'Priest Management')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Priest Management</h1>
            <p class="text-gray-600 mt-1">Manage parish priests and their information</p>
        </div>
        <a href="{{ route('admin.priests.create') }}" class="bg-[#0d5c2f] text-white px-4 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
            <i class="fas fa-plus mr-2"></i>Add Priest
        </a>
    </div>

    <!-- Priests List -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            @if($priests->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Priest</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Years of Service</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($priests as $priest)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($priest->photo_path)
                                            <img class="h-10 w-10 rounded-full object-cover" src="{{ Storage::url($priest->photo_path) }}" alt="{{ $priest->name }}">
                                        @else
                                            <div class="h-10 w-10 rounded-full bg-[#0d5c2f] flex items-center justify-center">
                                                <i class="fas fa-cross text-white"></i>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $priest->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $priest->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $priest->phone ?? 'No phone' }}</div>
                                    <div class="text-sm text-gray-500">{{ $priest->address ?? 'No address' }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($priest->is_active)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <i class="fas fa-check-circle mr-1"></i>Active
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-times-circle mr-1"></i>Inactive
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    @if($priest->years_of_service)
                                        {{ $priest->years_of_service }} years
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.priests.show', $priest) }}" class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.priests.edit', $priest) }}" class="text-indigo-600 hover:text-indigo-900">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.priests.toggle-status', $priest) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="text-{{ $priest->is_active ? 'yellow' : 'green' }}-600 hover:text-{{ $priest->is_active ? 'yellow' : 'green' }}-900">
                                                <i class="fas fa-{{ $priest->is_active ? 'pause' : 'play' }}"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.priests.destroy', $priest) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this priest?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $priests->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-cross text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No priests found</h3>
                    <p class="text-gray-600 mb-6">Get started by adding your first priest to the system.</p>
                    <a href="{{ route('admin.priests.create') }}" class="bg-[#0d5c2f] text-white px-6 py-2 rounded-lg hover:bg-[#0d5c2f]/90 transition-colors">
                        <i class="fas fa-plus mr-2"></i>Add First Priest
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection 