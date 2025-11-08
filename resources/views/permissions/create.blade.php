@extends('layouts.app')
@section('title', 'Create Permission')

@section('header')
    <h1 class="font-bold text-gray-900 dark:text-gray-100 leading-tight">Add Permission</h1>
@endsection

@section('content')
<div class="mx-auto mt-0 p-6 border dark:bg-gray-800 rounded-lg shadow-md">
    <form method="POST" action="{{ route('permissions.store') }}">
        @csrf

        <div class="mb-3">
            <label for="name" class="block text-gray-700 dark:text-gray-300 font-medium mb-2">Permission Name</label>
            <input type="text" name="name" id="name" required
                class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100
                       focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition" />

            @error('name')
                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 rounded-md shadow-sm transition">
            Create Permission
        </button>
    </form>
</div>
@endsection
