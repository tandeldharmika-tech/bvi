@extends('layouts.app')
@section('title', 'User Attendance')

@section('header')
<h1 class="font-bold text-gray-900 dark:text-gray-100 mb-0">
    🗂️ Attendance for {{ $user->name }}
</h1>
@endsection

@section('content')

{{-- Filter Form --}}
<form method="GET" action="{{ route('attendance.user', $user->id) }}"
    class="mb-6 border dark:bg-gray-800 p-4 rounded-lg shadow flex flex-wrap items-center gap-4">

    <div class="flex flex-col">
        <label for="type" class="text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">Type</label>
        <select name="type" id="type"
            class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm w-36">
            <option value="">All Types</option>
            <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>in</option>
            <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>out</option>
        </select>
    </div>

    <div class="flex flex-col">
        <label for="status" class="text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">Status</label>
        <select name="status" id="status"
            class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm w-36">
            <option value="">All Statuses</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>

    <div class="flex gap-2 items-end">
        <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition dark:bg-blue-500 dark:hover:bg-blue-600">
            Filter
        </button>
        <a href="{{ route('attendance.user', $user->id) }}"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
            Reset
        </a>
    </div>
</form>

{{-- Attendance Table --}}
<div class="overflow-x-auto border dark:bg-gray-800 shadow rounded-lg">
    <table class="min-w-full text-left text-sm font-light text-gray-700 dark:text-gray-300">
        <thead class="border-b bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-gray-100">
            <tr>
                <th class="px-6 py-3 w-12">#</th>
                <th class="px-6 py-3">Type</th>
                <th class="px-6 py-3">Punched At</th>
                <th class="px-6 py-3">Location</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Approved By</th>
                <th class="px-6 py-3">Remarks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attendances as $index => $attendance)
            <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                <td class="px-6 py-4 whitespace-nowrap">{{ $attendances->firstItem() + $index }}</td>
                <td class="px-6 py-4 capitalize">{{ $attendance->type }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    {{ \Carbon\Carbon::parse($attendance->punched_at)->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                </td>
                <td class="px-6 py-4">{{ $attendance->location }}</td>
                <td class="px-6 py-4">
                    @if($attendance->status === 'approved')
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100">
                        Approved
                    </span>
                    @elseif($attendance->status === 'pending')
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100">
                        Pending
                    </span>
                    @elseif($attendance->status === 'rejected')
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100">
                        Rejected
                    </span>
                    @else
                    <span
                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-bold bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                        {{ ucfirst($attendance->status) }}
                    </span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $attendance->approved_by_name ?? '-' }}</td>
                <td class="px-6 py-4 max-w-xs truncate" title="{{ $attendance->remarks }}">
                    {{ $attendance->remarks ?: '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                    No attendance found for this user.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
<div class="mt-6">
    {{ $attendances->links() }}
</div>

@endsection