@extends('layouts.app')
@section('title', 'Edit Holiday')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">✏️ Edit Holiday</h1>

    <a href="{{ route('holidays.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Holidays
    </a>
</div>
@endsection

@section('content')

{{-- 🔴 Validation Errors --}}
@if($errors->any())
    <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded dark:bg-red-700 dark:border-red-500 dark:text-white">
        <ul class="list-disc list-inside text-sm">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- ✏️ Edit Holiday Form --}}
<form action="{{ route('holidays.update', $holiday->id) }}" method="POST"
      class="mx-auto space-y-6 border dark:bg-gray-800 px-4 py-6 rounded-lg shadow max-w-2xl">
    @csrf
    @method('PUT')

    {{-- Holiday Title --}}
    <div>
        <label for="title" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Holiday Title <span class="text-red-600">*</span>
        </label>
        <input type="text" name="title" id="title"
               value="{{ old('title', $holiday->title) }}"
               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
               required>
    </div>

    {{-- Date --}}
    <div>
        <label for="date" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Date <span class="text-red-600">*</span>
        </label>
        <input type="date" name="date" id="date"
               value="{{ old('date', $holiday->date->format('Y-m-d')) }}"
               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
               required>
    </div>

    {{-- Type --}}
    <div>
        <label for="type" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Type
        </label>
        <select name="type" id="type"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
            <option value="">-- Select Type --</option>
            <option value="Public" {{ old('type', $holiday->type) == 'Public' ? 'selected' : '' }}>Public</option>
            <option value="Company" {{ old('type', $holiday->type) == 'Company' ? 'selected' : '' }}>Company</option>
            <option value="Optional" {{ old('type', $holiday->type) == 'Optional' ? 'selected' : '' }}>Optional</option>
        </select>
    </div>

    {{-- Recurring --}}
    <div class="flex items-center">
        <input type="checkbox" name="is_recurring" id="is_recurring" value="1"
               {{ old('is_recurring', $holiday->is_recurring) ? 'checked' : '' }}
               class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded focus:ring-blue-500">
        <label for="is_recurring" class="ml-2 text-gray-700 dark:text-gray-200 text-sm">
            Repeat annually
        </label>
    </div>

    {{-- Description --}}
    <div>
        <label for="description" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Description
        </label>
        <textarea name="description" id="description" rows="4"
                  class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">{{ old('description', $holiday->description) }}</textarea>
    </div>

    {{-- Buttons --}}
    <div class="pb-4 flex flex-col sm:flex-row gap-2">
        <button type="submit"
                class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded shadow transition">
            💾 Update Holiday
        </button>

        <a href="{{ route('holidays.index') }}"
           class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-bold rounded shadow transition text-center">
            Cancel
        </a>
    </div>
</form>
@endsection
