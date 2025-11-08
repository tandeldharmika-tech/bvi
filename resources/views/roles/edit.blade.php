@extends('layouts.app')
@section('title', 'Edit Role')

@section('header')
<div class="flex items-center justify-between">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">Edit Role</h1>
    <a href="{{ route('roles.index') }}"
        class="inline-block px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-400 dark:hover:bg-gray-600 transition">
        ← Back
    </a>
</div>
@endsection

@section('content')
<div class="mx-auto p-6 border dark:bg-gray-800 rounded-lg shadow-md">

    <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- Role Name --}}
        <div>
            <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Role Name</label>
            <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}" required
                class="w-full rounded-md border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('name')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        
        {{-- Parent Role --}}
        <div>
            <label for="parent_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Parent Role</label>
            <select name="parent_id" id="parent_id"
                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                <option value="">-- No Parent --</option>
                @foreach ($roles as $r)
                    @if ($r->id !== $role->id) {{-- prevent self-selection --}}
                    <option value="{{ $r->id }}" @selected(old('parent_id', $role->parent_id) == $r->id)>
                        {{ $r->name }}
                    </option>
                    @endif
                @endforeach
            </select>
        </div>

        {{-- Permissions --}}
        <div>
            <h3 class="mb-4 text-gray-700 dark:text-gray-300 font-bold text-lg">Assign Permissions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach ($permissions as $permission)
                <label for="permission_{{ $permission->id }}"
                    class="flex items-center space-x-3 cursor-pointer dark:text-gray-200">
                    <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                        id="permission_{{ $permission->id }}"
                        class="w-5 h-5 text-blue-600 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-blue-500"
                        {{ in_array($permission->name, $rolePermissions) ? 'checked' : '' }}>
                    <span class="select-none">{{ $permission->name }}</span>
                </label>
                @endforeach
            </div>
            @error('permissions')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Submit --}}
        <div>
            <button type="submit"
                class="w-full px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-md shadow transition">
                Update Role
            </button>
        </div>
    </form>
</div>
@endsection