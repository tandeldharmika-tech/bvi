@extends('layouts.app')
@section('title', 'Create Task')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">📝 Create Task</h1>

    <a href="{{ route('tasks.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Tasks
    </a>
</div>
@endsection

@section('content')

@if ($errors->any())
<div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded">
    <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<form action="{{ route('tasks.store') }}" method="POST" class="max-w-7xl mx-auto space-y-6 border dark:bg-gray-800 px-4 rounded-lg shadow">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <!-- Title -->
        <div class="col-span-1 md:col-span-3">
            <label for="title" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Title <span class="text-red-600">*</span>
            </label>
            <input type="text" name="title" id="title" value="{{ old('title') }}"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
                required>
        </div>

        <!-- Description -->
        <div class="col-span-1 md:col-span-3">
            <label for="description" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Description
            </label>
            <textarea name="description" id="description" rows="4"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">{{ old('description') }}</textarea>
        </div>

        <!-- Project -->
        <div>
            <label for="project_id" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Project
            </label>
            <select name="project_id" id="project_id"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                <option value="">-- Select Project --</option>
                @foreach($projects as $project)
                <option value="{{ $project->id }}" @selected(old('project_id')==$project->id)>
                    {{ $project->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Assigned To -->
        <div>
            <label for="assigned_to" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Assign To
            </label>
            <select name="assigned_to" id="assigned_to"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                <option value="">-- Select User --</option>
                @foreach($users as $user)
                <option value="{{ $user->id }}" @selected(old('assigned_to')==$user->id)>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Department -->
        <div>
            <label for="department_id" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Department
            </label>
            <select name="department_id" id="department_id"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                <option value="">-- Select Department --</option>
                @foreach($departments as $department)
                <option value="{{ $department->id }}" @selected(old('department_id')==$department->id)>
                    {{ $department->name }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Priority -->
        <div>
            <label for="priority" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Priority <span class="text-red-600">*</span>
            </label>
            <select name="priority" id="priority" required
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                @foreach(['low', 'medium', 'high', 'urgent'] as $priority)
                <option value="{{ $priority }}" @selected(old('priority')==$priority)>
                    {{ ucfirst($priority) }}
                </option>
                @endforeach
            </select>
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Status <span class="text-red-600">*</span>
            </label>
            <select name="status" id="status" required
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                @foreach(['pending', 'in progress', 'completed', 'cancelled'] as $status)
                <option value="{{ $status }}" @selected(old('status')==$status)>
                    {{ ucfirst($status) }}
                </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="due_date" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Due Date
            </label>
            <input type="date" name="due_date" id="due_date" value="{{ old('due_date') }}"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pb-4">
        <button type="submit"
            class="w-full md:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
            Create Task
        </button>
    </div>
</form>

@endsection