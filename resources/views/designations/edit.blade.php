@extends('layouts.app')
@section('title', 'Edit Designation')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">✏️ Edit Designation</h1>

    <a href="{{ route('designations.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Designations
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

<form action="{{ route('designations.update', $designation->id) }}" method="POST"
      class="mx-auto space-y-6 border dark:bg-gray-800 px-4 rounded-lg shadow">
    @csrf
    @method('PUT')

    <!-- Designation Title -->
    <div>
        <label for="title" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Designation Title <span class="text-red-600">*</span>
        </label>
        <input type="text" name="title" id="title"
               value="{{ old('title', $designation->title) }}"
               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
               required>
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
                <option value="{{ $department->id }}"
                    {{ old('department_id', $designation->department_id) == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Description
        </label>
        <textarea name="description" id="description" rows="4"
                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">{{ old('description', $designation->description) }}</textarea>
    </div>

    <!-- Buttons -->
    <div class="pb-4 flex flex-col sm:flex-row gap-2">
        <button type="submit"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded shadow transition">
            Update Designation
        </button>

        <a href="{{ route('designations.index') }}"
           class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded shadow transition text-center">
            Cancel
        </a>
    </div>
</form>
@endsection
