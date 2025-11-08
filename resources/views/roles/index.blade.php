@extends('layouts.app')
@section('title', 'Roles')

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="font-bold text-gray-900 dark:text-gray-100">🎭 Roles</h1>
        <a href="{{ route('roles.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded shadow transition">
            + Create Role
        </a>
    </div>
@endsection

@section('content')
<div class="overflow-x-auto border dark:bg-gray-800 shadow rounded-lg">
    <table class="min-w-full text-left text-sm font-light text-gray-700 dark:text-gray-300">
        <thead class="border-b bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">Role Name</th>
                <th class="px-6 py-3">Parent Role</th>
                <th class="px-6 py-3 w-32 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $index => $role)
                <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $roles->firstItem() + $index }}</td>
                    <td class="px-6 py-4 font-medium">{{ $role->name }}</td>
                    <td class="px-6 py-4">
                        {{ $role->parent ? $role->parent->name : '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="{{ route('roles.edit', $role->id) }}"
                           class="inline-block px-3 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded hover:bg-yellow-200 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-500">
                            Edit
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center py-8 text-gray-500 dark:text-gray-400">
                        No roles found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $roles->links() }}
</div>
@endsection
