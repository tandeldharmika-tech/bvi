@extends('layouts.app')
@section('title', 'Approve Attendance')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-white dark:text-gray-100">
        🕒 Pending Attendance Approvals
    </h1>
    <a href="{{ route('attendance.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Attendance List
    </a>
</div>
@endsection

@section('content')
@if(session('success'))
<div class="mb-4 px-4 py-2 rounded bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">
    {{ session('success') }}
</div>
@endif

{{-- Filter form --}}
<div class="mb-6 border dark:bg-gray-800 p-4 rounded-lg shadow">
    <form method="GET" class="flex flex-wrap items-end gap-4">
        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300">User Name</label>
            <input type="text" name="user" value="{{ request('user') }}"
                class="rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600"
                placeholder="Search by user name">
        </div>

        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300">Type</label>
            <select name="type" class="rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
                <option value="">-- All --</option>
                <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>In</option>
                <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>Out</option>
            </select>
        </div>

        <div>
            <label class="block text-sm text-gray-700 dark:text-gray-300">Date</label>
            <input type="date" name="date" value="{{ request('date') }}"
                class="rounded border-gray-300 dark:bg-gray-700 dark:text-white dark:border-gray-600">
        </div>

        <div>
            <button type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 dark:bg-blue-500 dark:hover:bg-blue-600">
                Filter
            </button>
        </div>
        <div>
            <a href="{{ route('attendance.approvals') }}"
                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                Reset
            </a>
        </div>
    </form>
</div>

{{-- Bulk approval form --}}
@if($pendingAttendances->count())
<form method="POST" action="{{ route('attendance.bulk-approve') }}">
    @csrf
    <div class="border dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden">
        <div class="p-6 overflow-x-auto">
            <table class="w-full table-auto text-sm text-left text-gray-700 dark:text-gray-200">
                <thead class="bg-gray-100 dark:bg-gray-700 uppercase text-xs font-bold">
                    <tr>
                        <th class="px-4 py-3">User</th>
                        <th class="px-4 py-3">Type</th>
                        <th class="px-4 py-3">Punched At</th>
                        <th class="px-4 py-3">Location</th>
                        <th class="px-4 py-3">Remarks</th>
                        <th class="px-4 py-3">Decision</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($pendingAttendances as $attendance)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-4 py-3">
                            <a href="{{ route('attendance.user', $attendance->user_id) }}"
                                class="text-blue-600 hover:underline dark:text-blue-400 font-medium">
                                {{ $attendance->user->name ?? 'N/A' }}
                            </a>
                        </td>
                        <td class="px-4 py-3 capitalize">{{ $attendance->type }}</td>
                        <td class="px-4 py-3">
                            {{ \Carbon\Carbon::parse($attendance->punched_at)->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
                        </td>
                        <td class="px-4 py-3">{{ $attendance->location }}</td>
                        <td class="px-4 py-3">{{ $attendance->remarks }}</td>
                        <td class="px-4 py-3">
                            <select name="statuses[{{ $attendance->id }}]" required
                                class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                <option value="">-- Select --</option>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $pendingAttendances->appends(request()->query())->links() }}
            </div>

            {{-- Global submit --}}
            <div class="mt-6 text-right">
                <button type="submit"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 dark:bg-green-500 dark:hover:bg-green-600">
                    Submit Approvals
                </button>
            </div>
        </div>
    </div>
</form>
@else
<div class="text-center text-gray-500 dark:text-gray-400 py-10">
    <p class="text-lg">✅ No pending attendance approvals.</p>
</div>
@endif
@endsection