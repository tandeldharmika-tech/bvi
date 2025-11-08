@extends('layouts.app')
@section('title', 'Attendance')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
        <i class="fa-solid fa-clock text-yellow-500"></i> <!-- Attendance Icon -->
        All Attendance Records
    </h1>
    <div>
        @can('create attendance')
        <a href="{{ route('attendance.create') }}"
            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded shadow transition">
            <i class="fa-solid fa-plus"></i>&nbsp; Create Attendance
        </a>
        @endcan
        @can('approve attendance')
        <a href="{{ route('attendance.approvals') }}"
            class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow transition">
            <i class="fa-solid fa-check"></i> &nbsp;Approve Attendance
        </a>
        @endcan
        @can('export attendance')
        <a href="{{ route('attendance.export') }}"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition">
            <i class="fa-solid fa-file-export"></i>&nbsp; Export Attendance
        </a>
        @endcan
    </div>
</div>
@endsection

@section('content')
@if (session('success'))
    <div class="bg-green-600 text-white p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif
<div class="grid grid-cols-4 gap-4 mb-6 text-white">
    <div class="bg-green-600 p-3 rounded shadow flex flex-col items-center border">
        <span class="text-2xl font-bold">{{ $totalPresent }}</span>
        <span>Today Present</span>
    </div>
    <div class="bg-blue-600 p-3 rounded shadow flex flex-col items-center border">
        <span class="text-2xl font-bold">{{ $onTime }}</span>
        <span>On Time (9-9:30)</span>
    </div>
    <div class="bg-red-600 p-3 rounded shadow flex flex-col items-center border">
        <span class="text-2xl font-bold">{{ $late }}</span>
        <span>Late (>9:30)</span>
    </div>
    <div class="bg-yellow-600 p-3 rounded shadow flex flex-col items-center border">
        <span class="text-2xl font-bold">{{ $pendingApproval }}</span>
        <span>Today Pending Approval</span>
    </div>
</div>


{{-- Filter Form --}}
<form method="GET" action="{{ route('attendance.index') }}"
    class="mb-6 border dark:bg-gray-800 p-4 rounded-lg shadow flex flex-wrap items-center gap-4">

    {{-- User Name --}}
    <div class="flex flex-col">
        <label for="user" class="text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">User Name</label>
        <input type="text" name="user" id="user" value="{{ request('user') }}" placeholder="Search by user name"
            class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm w-48">
    </div>

    {{-- Type --}}
    <div class="flex flex-col">
        <label for="type" class="text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">Type</label>
        <select name="type" id="type"
            class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm w-36">
            <option value="">All</option>
            <option value="checkin" {{ request('type') == 'checkin' ? 'selected' : '' }}>Checkin</option>
            <option value="checkout" {{ request('type') == 'checkout' ? 'selected' : '' }}>Checkout</option>
        </select>
    </div>

    {{-- Status --}}
    <div class="flex flex-col">
        <label for="status" class="text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">Status</label>
        <select name="status" id="status"
            class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm w-36">
            <option value="">All</option>
            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
    </div>

    {{-- Date --}}
    <div class="flex flex-col">
        <label for="date" class="text-gray-700 dark:text-gray-300 text-sm font-medium mb-1">Date</label>
        <input type="date" name="date" id="date" value="{{ request('date') }}"
            class="rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-3 py-2 text-sm w-36">
    </div>

    {{-- Buttons --}}
    <div class="flex gap-2 items-end">
        <button type="submit"
            class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition dark:bg-blue-500 dark:hover:bg-blue-600">
            Filter
        </button>
        <a href="{{ route('attendance.index') }}"
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
            <th class="px-4 py-3 w-12">#</th>
            <th class="px-4 py-3">Username</th>
            <th class="px-4 py-3">Date</th>
            <th class="px-4 py-3">In</th>
            <th class="px-4 py-3">Out</th>
            <th class="px-4 py-3">Location</th>
            <th class="px-4 py-3">Status</th>
            <th class="px-4 py-3">Approved By</th>
            <th class="px-4 py-3 text-center">Actions</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($attendances as $index => $a)
            <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                <td class="px-4 py-3">{{ $index + 1 }}</td>
                <td class="px-4 py-3">
                    <a href="{{ route('attendance.user', $a->user) }}" class="text-blue-600 hover:underline dark:text-blue-400 font-medium">
                    {{ $a->user->name ?? 'N/A' }}
                    </a>
                </td>
                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($a->date)->format('d M Y') }}</td>
                <td class="px-4 py-3">
                    {{ $a->in_time ? \Carbon\Carbon::parse($a->in_time)->timezone('Asia/Kolkata')->format('h:i A') : '-' }}
                </td>
                <td class="px-4 py-3">
                    {{ $a->out_time ? \Carbon\Carbon::parse($a->out_time)->timezone('Asia/Kolkata')->format('h:i A') : '-' }}
                </td>
                <td class="px-4 py-3">{{ $a->location }}</td>
                <td class="px-4 py-3">
                    @if ($a->status === 'approved')
                        <span class="px-2 py-1 text-xs font-bold bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100 rounded-full">Approved</span>
                    @elseif ($a->status === 'pending')
                        <span class="px-2 py-1 text-xs font-bold bg-yellow-100 text-yellow-800 dark:bg-yellow-600 dark:text-yellow-100 rounded-full">Pending</span>
                    @else
                        <span class="px-2 py-1 text-xs font-bold bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-100 rounded-full">{{ ucfirst($a->status) }}</span>
                    @endif
                </td>
                <td class="px-4 py-3">{{ $a->approved_by_name ?? '-' }}</td>
                <td class="px-4 py-3 text-center">
                    @can('edit attendance')
                        <a href="{{ route('attendance.edit', $a->user->id) }}" class="text-yellow-600 hover:underline dark:text-yellow-400 text-sm">
                            Edit
                        </a>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="9" class="text-center py-8 text-gray-500 dark:text-gray-400">No attendance records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

</div>

{{-- Pagination --}}
<div class="mt-6">
</div>

@endsection