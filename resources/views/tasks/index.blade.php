@extends('layouts.app')
@section('title', 'Tasks')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">📝 Tasks</h1>

    @can('create task')
    <div class="flex flex-col sm:flex-row items-center gap-3">
        <a href="{{ route('tasks.create') }}"
            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow transition">
            + Create Task
        </a>
        <!-- Import Form -->
        <!-- <form action="{{ route('tasks.import') }}" method="POST" enctype="multipart/form-data"
            class="flex items-center gap-2 bg-gray-800 p-2 rounded-md shadow border border-gray-700 max-w-md mx-auto text-sm">
            @csrf

            <label class="text-xs font-medium text-green-400 whitespace-nowrap">
                Import:
            </label>

            <input type="file" name="file"
                class="text-xs text-white file:text-xs file:px-2 file:py-1 file:rounded file:bg-gray-700 border border-gray-600 rounded-md w-40"
                required>

            <button type="submit"
                class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded shadow transition whitespace-nowrap">
                📤 Upload
            </button>
        </form> -->
    </div>
    @endcan
</div>
@endsection

@section('content')

@if(session('success'))
<div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
    {{ session('success') }}
</div>
@endif
<form method="GET" action="{{ route('tasks.index') }}"
    class="mb-6 border dark:bg-gray-800 p-4 rounded-lg shadow flex flex-wrap gap-4 items-center">
    {{-- Project Filter --}}
    <div>
        <label class="text-sm text-gray-600 dark:text-gray-300">Project</label>
        <select name="project_id" class="block w-full px-3 py-2 rounded dark:bg-gray-700 dark:text-white">
            <option value="">All</option>
            @foreach($projects as $project)
            <option value="{{ $project->id }}" @selected(request('project_id')==$project->id)>{{ $project->name }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Assigned To Filter --}}
    <div>
        <label class="text-sm text-gray-600 dark:text-gray-300">Assigned To</label>
        <select name="assigned_to" class="block w-full px-3 py-2 rounded dark:bg-gray-700 dark:text-white">
            <option value="">All</option>
            @foreach($users as $user)
            <option value="{{ $user->id }}" @selected(request('assigned_to')==$user->id)>
                {{ $user->name }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Status Filter --}}
    <div>
        <label class="text-sm text-gray-600 dark:text-gray-300">Status</label>
        <select name="status" class="block w-full px-3 py-2 rounded dark:bg-gray-700 dark:text-white">
            <option value="">All</option>
            @foreach(['not_started', 'in_progress', 'completed', 'on_hold'] as $status)
            <option value="{{ $status }}" @selected(request('status')==$status)>
                {{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Priority Filter --}}
    <div>
        <label class="text-sm text-gray-600 dark:text-gray-300">Priority</label>
        <select name="priority" class="block w-full px-3 py-2 rounded dark:bg-gray-700 dark:text-white">
            <option value="">All</option>
            @foreach(['low', 'medium', 'high'] as $priority)
            <option value="{{ $priority }}" @selected(request('priority')==$priority)>{{ ucfirst($priority) }}</option>
            @endforeach
        </select>
    </div>

    {{-- Filter Button --}}
    <div class="mt-6 gap-2 items-end">
        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
            Filter
        </button>
        <a href="{{ route('tasks.index') }}"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            Reset
        </a>
    </div>
</form>

<div class="overflow-x-auto border dark:bg-gray-800 shadow rounded-lg">
    <table class="min-w-full text-left text-sm font-light text-gray-700 dark:text-gray-300">
        <thead class="border-b bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">Title</th>
                <th class="px-6 py-3">Project</th>
                <th class="px-6 py-3">Assigned To</th>
                <th class="px-6 py-3">Department</th>
                <th class="px-6 py-3">Priority</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Due Date</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $index => $task)
            <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                <td class="px-6 py-4 whitespace-nowrap">{{ $tasks->firstItem() + $index }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $task->title }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <a href="{{ route('projects.show', $task->project) }}"
                        class="text-blue-600 hover:underline dark:text-blue-400 font-medium">
                        {{ $task->project->name ?? '-' }}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if(auth()->user()->can('view user dashboard'))
                    <a href="{{ route('dashboard.user', $task->assignedUser->id) }}"
                        class="text-blue-600 hover:underline dark:text-blue-400 font-medium">
                        {{ $task->assignedUser->name }}
                    </a>
                    @else
                    <span class="text-gray-700 dark:text-gray-300">
                        {{ $task->assignedUser->name }}
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $task->department->name ?? '-' }}</td>
                <td class="px-6 py-4 capitalize">{{ $task->priority }}</td>
                <td class="px-6 py-4 capitalize">{{ $task->status }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('d M Y') : '-' }}</td>
                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                    @can('view task')
                    <a href="{{ route('tasks.show', $task->id) }}"
                        class="text-blue-600 hover:underline dark:text-blue-400 text-sm">View</a>
                    @endcan

                    @can('edit task')
                    <a href="{{ route('tasks.edit', $task->id) }}"
                        class="text-yellow-600 hover:underline dark:text-yellow-400 text-sm">Edit</a>
                    @endcan

                    @can('delete task')
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST"
                        onsubmit="return confirm('Delete this task?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-600 hover:underline dark:text-red-400 text-sm">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-500 dark:text-gray-400">
                    No tasks found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $tasks->links() }}
</div>

@endsection