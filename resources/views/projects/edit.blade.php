@extends('layouts.app')
@section('title', 'Edit Project')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">✏️ Edit Project</h1>

    <a href="{{ route('projects.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Projects
    </a>
</div>
@endsection

@section('content')

@if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded dark:bg-red-700 dark:border-red-500 dark:text-white">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('projects.update', $project->id) }}" method="POST"
      class="mx-auto space-y-6 border dark:bg-gray-800 px-4 rounded-lg shadow">
    @csrf
    @method('PUT')

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Project Name -->
        <div class="md:col-span-2">
            <label for="name" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Project Name <span class="text-red-600">*</span>
            </label>
            <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}"
                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2" required>
        </div>

        <!-- Description -->
        <div class="md:col-span-2">
            <label for="description" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Description
            </label>
            <textarea name="description" id="description" rows="4"
                      class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">{{ old('description', $project->description) }}</textarea>
        </div>

        <!-- Department -->
        <div>
            <label for="department_id" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Department
            </label>
            <select name="department_id" id="department_id"
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                <option value="">-- None --</option>
                @foreach($departments as $department)
                    <option value="{{ $department->id }}"
                            @selected(old('department_id', $project->department_id) == $department->id)>
                        {{ $department->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <!-- Start Date -->
        <div>
            <label for="start_date" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Start Date
            </label>
            <input type="date" name="start_date" id="start_date"
                   value="{{ old('start_date', $project->start_date) }}"
                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
        </div>

        <!-- End Date -->
        <div>
            <label for="end_date" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                End Date
            </label>
            <input type="date" name="end_date" id="end_date"
                   value="{{ old('end_date', $project->end_date) }}"
                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
        </div>

        <!-- Status -->
        <div>
            <label for="status" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
                Status
            </label>
            <select name="status" id="status"
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                @foreach(['not_started', 'in_progress', 'completed', 'on_hold'] as $status)
                    <option value="{{ $status }}" @selected(old('status', $project->status) == $status)>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Submit Button -->
    <div class="pb-4">
        <button type="submit"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded shadow transition">
            Update Project
        </button>
    </div>
</form>
@endsection
