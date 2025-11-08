@extends('layouts.app')
@section('title', ' Leaves')

@section('header')
    <div class="flex items-center justify-between mb-0">
        <h1 class="font-bold text-gray-900 dark:text-gray-100">🌿 Leave Requests</h1>

        @can('create leave requests')
            <a href="{{ route('leaves.create') }}"
               class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow transition">
                + Apply for Leave
            </a>
        @endcan
    </div>
@endsection

@section('content')

{{-- Success Message --}}
@if(session('success'))
    <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded">
        {{ session('success') }}
    </div>
@endif

{{-- Leave Requests Table --}}
<div class="overflow-x-auto border dark:bg-gray-800 shadow rounded-lg">
    <table class="min-w-full text-left text-sm font-light text-gray-700 dark:text-gray-300">
        <thead class="border-b bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-6 py-3 w-12">#</th>
                <th class="px-6 py-3">Employee</th>
                <th class="px-6 py-3">Leave Type</th>
                <th class="px-6 py-3">Dates</th>
                <th class="px-6 py-3">Days</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($leaves as $index => $leave)
                <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $leaves->firstItem() + $index }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ route('attendance.user', $leave->user) }}"
                        class="text-blue-600 hover:underline dark:text-blue-400 font-medium">
                        {{ $leave->user->name ?? 'N/A' }}
</a>
                    </td>
                    <td class="px-6 py-4 capitalize">
                        {{ $leave->leave_type }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }} 
                        &rarr; 
                        {{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4">
                        {{ $leave->total_days }}
                    </td>
                    <td class="px-6 py-4">
                        @switch($leave->status)
                            @case('approved')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                                    Approved
                                </span>
                                @break

                            @case('pending')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100">
                                    Pending
                                </span>
                                @break

                            @case('rejected')
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">
                                    Rejected
                                </span>
                                @break

                            @default
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                                    {{ ucfirst($leave->status) }}
                                </span>
                        @endswitch
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                        <a href="{{ route('leaves.show', $leave->id) }}"
                           class="text-blue-600 hover:underline dark:text-blue-400 text-sm">View</a>

                        @can('approve leave requests')
                            <a href="{{ route('leaves.edit', $leave->id) }}"
                               class="text-yellow-600 hover:underline dark:text-yellow-400 text-sm">Edit</a>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">
                        No leave requests found.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $leaves->links() }}
</div>

@endsection
