@extends('layouts.app')
@section('title', 'Users')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">👥 Manage User Roles</h1>
    @can('create user')
    <a href="{{ route('users.create') }}"
        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow transition">
        + Create User
    </a>
    @endcan
</div>
@endsection

@section('content')
@if(session('success'))
<div class="mb-4 p-4 bg-green-100 text-green-800 rounded dark:bg-green-700 dark:text-green-100">
    {{ session('success') }}
</div>
@endif

<div class="overflow-x-auto border dark:bg-gray-800 shadow rounded-lg">
    <table class="min-w-full text-left text-sm font-light text-gray-700 dark:text-gray-300">
        <thead class="border-b bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-6 py-3">#</th>
                <th class="px-6 py-3">Profile Photo</th>
                <th class="px-6 py-3">Employee Code</th>
                <th class="px-6 py-3">User Name</th>
                <th class="px-6 py-3">Roles</th>
                <th class="px-6 py-3">Email</th>
                <th class="px-6 py-3">Parent Name</th>
                <th class="px-6 py-3">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $index => $user)
            <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ $users->firstItem() + $index }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    @if(!empty($user->personal['photo']))
                    <img src="{{ asset('storage/'.$user->personal['photo']) }}" alt="Profile Photo"
                        class="w-10 h-10 rounded-full object-cover">
                    @else
                    <span>-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->official['employeeCode'] ?? '-' }}</td>
                <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                <td class="px-6 py-4 font-bold">
                    {{ $user->getRoleNames()->join(', ') ?: '-' }}
                </td>
                <td class="px-6 py-4">{{ $user->email }}</td>
                <td class="px-6 py-4">{{ $user->manager?->name ?? '-' }}</td>
                <td class="px-6 py-4">
                    @can('view user')
                    <a href="{{ route('users.show', $user->id) }}"
                        class="text-blue-600 hover:underline dark:text-blue-400 text-sm">View</a>
                    @endcan

                    @can('edit user')
                    <a href="{{ route('users.edit', $user->id) }}"
                        class="text-yellow-600 hover:underline dark:text-yellow-400 text-sm ml-2">Edit</a>
                    @endcan

                    @can('delete user')
                    <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                        onsubmit="return confirm('Delete this user?');" class="inline ml-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="text-red-600 hover:underline dark:text-red-400 text-sm">Delete</button>
                    </form>
                    @endcan
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection