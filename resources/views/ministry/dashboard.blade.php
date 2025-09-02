@extends('layouts.ministry')

@section('title', 'Dashboard')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Welcome, {{ Auth::user()->name }}</h1>
    @if($ministry)
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-600">Ministry</div>
                <div class="text-xl font-semibold">{{ $ministry->name }}</div>
            </div>
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-600">Members</div>
                <div class="text-xl font-semibold">{{ $ministry->members_count }}</div>
            </div>
            <div class="bg-white shadow rounded p-4">
                <div class="text-gray-600">Activities</div>
                <div class="text-xl font-semibold">{{ $ministry->activities_count }}</div>
            </div>
        </div>
    @else
        <div class="bg-yellow-50 border border-yellow-300 text-yellow-900 rounded p-4">
            No ministry is assigned to your account yet. Please contact an administrator.
        </div>
    @endif
</div>
@endsection


