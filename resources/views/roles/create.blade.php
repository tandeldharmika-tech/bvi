@extends('layouts.app')
@section('title', 'Create Role')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">Create Role</h1>
    <a href="{{ route('roles.index') }}"
        class="inline-block px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">
        ← Back
    </a>
</div>
@endsection

@section('content')
<div class="mx-auto p-6 border dark:bg-gray-800 rounded-lg shadow-md">

    @if($errors->any())
    <div class="mb-6 p-4 bg-red-100 dark:bg-red-700 text-red-700 dark:text-red-100 rounded">
        <ul class="list-disc list-inside space-y-1">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('roles.store') }}" method="POST" class="space-y-6">
        @csrf

        {{-- Role Name --}}
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Role Name</label>
            <input type="text" name="name" id="name" placeholder="e.g. admin" required
                class="w-full rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>

        {{-- Parent Role --}}
        <div>
            <label for="parent_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Parent
                Role</label>
            <select name="parent_id" id="parent_id"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                <option value="">-- No Parent --</option>
                @foreach ($roles as $role)
                <option value="{{ $role->id }}" @selected(old('parent_id')==$role->id)>
                    {{ $role->name }}
                </option>
                @endforeach
            </select>
        </div>

        {{-- Permissions --}}
        <div>
            <h3 class="mb-4 text-gray-700 dark:text-gray-300 font-bold text-lg">Assign Permissions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($permissions as $permission)
                <label for="perm-{{ $permission->id }}"
                    class="flex items-center space-x-3 cursor-pointer dark:text-gray-200">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        id="perm-{{ $permission->id }}"
                        class="w-5 h-5 text-blue-600 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500">
                    <span class="select-none">{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit"
                class="w-full px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-bold rounded-md shadow transition">
                Save Role
            </button>
        </div>
    </form>
</div>
@endsection