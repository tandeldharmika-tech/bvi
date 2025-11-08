@extends('layouts.app')
@section('title', 'Projects')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">📋 Projects</h1>

    @can('create project')
        <a href="{{ route('projects.create') }}"
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition">
            + Create Project
        </a>
    @endcan
</div>
@endsection

@section('content')

@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded dark:bg-green-700 dark:text-green-100 dark:border-green-500">
        {{ session('success') }}
    </div>
@endif

<div class="overflow-x-auto border dark:bg-gray-800 shadow rounded-lg">
    <table class="min-w-full text-left text-sm font-light text-gray-700 dark:text-gray-300">
        <thead class="border-b bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-6 py-3 w-12">#</th>
                <th class="px-6 py-3">Name</th>
                <th class="px-6 py-3">Department</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Start Date</th>
                <th class="px-6 py-3">End Date</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $index => $project)
                <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $projects->firstItem() + $index }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $project->department->name ?? '-' }}</td>
                    <td class="px-6 py-4 capitalize">{{ ucfirst(str_replace('_', ' ', $project->status)) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($project->start_date)->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ \Carbon\Carbon::parse($project->end_date)->format('d M Y') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        @can('view project')
                            <a href="{{ route('projects.show', $project->id) }}"
                               class="text-blue-600 hover:underline dark:text-blue-400 text-sm">View</a>
                        @endcan

                        @can('edit project')
                            <a href="{{ route('projects.edit', $project->id) }}"
                               class="text-yellow-600 hover:underline dark:text-yellow-400 text-sm">Edit</a>
                        @endcan

                        @can('delete project')
                            <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Delete this project?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline dark:text-red-400 text-sm">Delete</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">
                        No projects found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $projects->links() }}
</div>

@endsection
