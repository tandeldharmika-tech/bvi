@extends('layouts.app')
@section('title', 'Create Designation')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">🏷️ Create Designation</h1>

    <a href="{{ route('designations.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Designations
    </a>
</div>
@endsection

@section('content')

@if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded dark:bg-red-700 dark:text-white dark:border-red-500">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('designations.store') }}" method="POST"
      class="mx-auto space-y-6 border dark:bg-gray-800 px-4 py-6 rounded-lg shadow max-w-2xl">
    @csrf

    {{-- Department --}}
    <div>
        <label for="department_id" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Department <span class="text-red-600">*</span>
        </label>
        <select name="department_id" id="department_id"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
                required>
            <option value="">-- Select Department --</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                    {{ $department->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Title --}}
    <div>
        <label for="title" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Designation Title <span class="text-red-600">*</span>
        </label>
        <input type="text" name="title" id="title" value="{{ old('title') }}"
               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
               placeholder="e.g. Senior Developer" required>
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Description
        </label>
        <textarea name="description" id="description" rows="4"
                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
                  placeholder="Optional description...">{{ old('description') }}</textarea>
    </div>

    {{-- Buttons --}}
    <div class="flex flex-col sm:flex-row gap-2 pb-4">
        <button type="submit"
                class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
            ➕ Create Designation
        </button>

        <a href="{{ route('designations.index') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white text-sm font-medium rounded shadow transition">
            Cancel
        </a>
    </div>
</form>
@endsection
