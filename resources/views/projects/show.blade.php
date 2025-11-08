@extends('layouts.app')
@section('title', 'Project Details')

@section('header')
    <div class="flex items-center justify-between mb-0">
        <h1 class="font-bold text-gray-100">📁 Project Details</h1>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto p-6 border border-gray-800 rounded-lg shadow-md text-white space-y-6">

    <div class="space-y-4 text-sm md:text-base">

        <div class="flex justify-between">
            <span class="text-gray-400">🏷️ Name:</span>
            <span class="text-gray-100 font-medium">{{ $project->name }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">📝 Description:</span>
            <span class="text-gray-100">{{ $project->description ?: 'No description provided.' }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">🏢 Department:</span>
            <span class="text-gray-100 font-medium">{{ $project->department->name ?? '-' }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">📊 Status:</span>
            <span class="capitalize px-3 py-1 rounded-full
                {{ $project->status === 'active' ? 'bg-green-600' : ($project->status === 'completed' ? 'bg-blue-600' : 'bg-gray-600') }} 
                text-white text-sm font-bold">
                {{ ucfirst($project->status) }}
            </span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">📅 Start Date:</span>
            <span class="text-gray-100">{{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">📅 End Date:</span>
            <span class="text-gray-100">{{ \Carbon\Carbon::parse($project->end_date)->format('d M Y') }}</span>
        </div>

    </div>

    <div class="mt-6">
        <a href="{{ route('projects.index') }}"
           class="inline-flex items-center px-5 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
            ← Back to Projects
        </a>
    </div>
</div>
@endsection
