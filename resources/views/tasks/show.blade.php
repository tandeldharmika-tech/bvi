@extends('layouts.app')
@section('title', 'Task Details')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">📋 Task Details</h1>

    <a href="{{ route('tasks.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Tasks
    </a>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-gray-900 border border-gray-800 rounded-lg shadow-md text-white">

    {{-- Task Information --}}
    <div class="space-y-4 text-sm md:text-base">

        <div class="flex justify-between">
            <span class="text-gray-400">📌 Title:</span>
            <span class="text-gray-100 font-medium">{{ $task->title }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">🗂️ Project:</span>
            <span class="text-gray-100 font-medium">{{ $task->project->name ?? 'N/A' }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">👤 Assigned To:</span>
            <span class="text-gray-100 font-medium">{{ $task->assignedUser->name ?? 'Unassigned' }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">🏢 Department:</span>
            <span class="text-gray-100 font-medium">{{ $task->department->name ?? 'N/A' }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">⚠️ Priority:</span>
            <span class="capitalize text-gray-100 font-medium">{{ $task->priority ?? 'N/A' }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">📆 Due Date:</span>
            <span class="text-gray-100">
                {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : 'N/A' }}
            </span>
        </div>

        <div class="flex justify-between items-center">
            <span class="text-gray-400">📍 Status:</span>
            <span>
                @switch($task->status)
                    @case('completed')
                        <span class="px-3 py-1 bg-green-600 text-white text-sm rounded-full">Completed</span>
                        @break
                    @case('in progress')
                        <span class="px-3 py-1 bg-yellow-600 text-white text-sm rounded-full">In Progress</span>
                        @break
                    @case('pending')
                        <span class="px-3 py-1 bg-blue-600 text-white text-sm rounded-full">Pending</span>
                        @break
                    @case('cancelled')
                        <span class="px-3 py-1 bg-red-600 text-white text-sm rounded-full">Cancelled</span>
                        @break
                    @default
                        <span class="px-3 py-1 bg-gray-600 text-white text-sm rounded-full">{{ ucfirst($task->status) }}</span>
                @endswitch
            </span>
        </div>

        <div>
            <span class="text-gray-400 block">📝 Description:</span>
            <p class="text-gray-100 mt-1 bg-gray-800 px-3 py-2 rounded">
                {{ $task->description ?? 'No description provided.' }}
            </p>
        </div>

    </div>

    {{-- Buttons --}}
    <div class="mt-6 flex justify-between">

        @can('edit task')
            <a href="{{ route('tasks.edit', $task->id) }}"
               class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded shadow transition">
                ✏️ Edit Task
            </a>
        @endcan
    </div>

</div>
@endsection
