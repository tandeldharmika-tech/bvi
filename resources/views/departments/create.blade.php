@extends('layouts.app')

@section('title', 'Create Department')
@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">🏢 Create Department</h1>

    <a href="{{ route('departments.index') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Departments
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

<form action="{{ route('departments.store') }}" method="POST" class="mx-auto space-y-6 border dark:bg-gray-800 px-4 pb-4 rounded-lg shadow">
    @csrf

    <div>
        <label for="name" class="block mb-1 font-medium text-gray-700 dark:text-gray-200">
            Department Name <span class="text-red-600">*</span>
        </label>
        <input type="text" name="name" id="name" value="{{ old('name') }}"
               class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
               placeholder="Enter department name" required>
    </div>

    <div class="flex flex-col sm:flex-row gap-2">
        <button type="submit"
                class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
            Create Department
        </button>

        <a href="{{ route('departments.index') }}"
           class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white text-sm font-medium rounded shadow transition">
            Cancel
        </a>
    </div>
</form>
@endsection
