@extends('layouts.app')
@section('title', ' Permission')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">🛡️ Permissions</h1>
    <a href="{{ route('permissions.create') }}"
        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition">
        + Add Permission
    </a>
</div>
@endsection

@section('content')
<div class="container mx-auto">
    @if(session('success'))
    <div class="mb-4 p-4 bg-green-100 text-green-800 rounded dark:bg-green-700 dark:text-green-100">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto border dark:bg-gray-800 shadow rounded-lg">
        <table class="min-w-full text-sm text-left text-gray-700 dark:text-gray-300">
            <thead
                class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-gray-100 text-sm uppercase tracking-wider">
                <tr>
                    <th class="px-6 py-3 w-12">#</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Guard</th>
                    <th class="px-6 py-3">Created At</th>
                    <th class="px-6 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permissions as $index => $permission)
                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <td class="p-3">{{ $permissions->firstItem() + $loop->index }}</td>
                    <td class="p-3">{{ $permission->name }}</td>
                    <td class="p-3">{{ $permission->guard_name }}</td>
                    <td class="p-3">{{ $permission->created_at->format('Y-m-d') }}</td>
                    <td class="p-3 text-center">
                        <a href="{{ route('permissions.edit', $permission->id) }}"
                            class="inline-block px-3 py-1 text-xs font-medium text-yellow-800 bg-yellow-100 rounded hover:bg-yellow-200 dark:bg-yellow-600 dark:text-white dark:hover:bg-yellow-500">
                            Edit
                        </a>

                        <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST"
                            class="inline-block ml-1" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="px-3 py-1 text-xs font-medium text-red-800 bg-red-100 rounded hover:bg-red-200 dark:bg-red-600 dark:text-white dark:hover:bg-red-500">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-6 text-center text-gray-500 dark:text-gray-400">
                        No permissions found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-6">
        {{ $permissions->links() }}
    </div>
</div>
@endsection