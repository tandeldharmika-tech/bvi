@extends('layouts.app') 

@section('title', 'Edit Attendance')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-white dark:text-gray-100">
        🗂️ Edit Attendance
    </h1>
    <a href="{{ route('attendance.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Attendance List
    </a>
</div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto bg-gray-900 text-gray-100 rounded-lg shadow-lg p-6">
    <h1 class="font-bold mb-6">🧑‍💼 Update Attendance</h1>

    @if ($errors->any())
        <div class="bg-red-600 text-white p-3 rounded mb-4">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('attendance.update', $attendance->id) }}" class="space-y-4">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-1">👤 Select User</label>
                <select id="employeeSelect" name="user_id" required
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ $attendance->user_id == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold mb-1">🔄 Type</label>
                <select name="type" required
                    class="w-full rounded bg-gray-800 border-gray-700 p-2 focus:ring focus:ring-indigo-500">
                    <option value="in" {{ $attendance->type == 'in' ? 'selected' : '' }}>In</option>
                    <option value="out" {{ $attendance->type == 'out' ? 'selected' : '' }}>Out</option>
                </select>
            </div>
        </div>

        @php
    $date = \Carbon\Carbon::parse($attendance->punched_at)->format('Y-m-d');
    $time = \Carbon\Carbon::parse($attendance->punched_at)->format('H:i');
@endphp

<div class="grid grid-cols-2 gap-4">
    <div>
        <label class="block font-bold mb-1">📅 Date </label>
        <input type="date" name="date" required value="{{ $date }}"
            class="w-full rounded bg-gray-800 border-gray-700 p-2 focus:ring focus:ring-indigo-500">
    </div>
    <div>
        <label class="block font-bold mb-1">⏰ Time</label>
        <input type="time" name="time" required value="{{ $time }}"
            class="w-full rounded bg-gray-800 border-gray-700 p-2 focus:ring focus:ring-indigo-500">
    </div>
</div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block font-bold mb-1">📍 Location</label>
                <input type="text" name="location" required value="{{ $attendance->location }}"
                    class="w-full rounded bg-gray-800 border-gray-700 p-2 focus:ring focus:ring-indigo-500">
            </div>
            <div>
                <label class="block font-bold mb-1">📊 Status</label>
                <select name="status" required
                    class="w-full rounded bg-gray-800 border-gray-700 p-2 focus:ring focus:ring-indigo-500">
                    <option value="pending" {{ $attendance->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ $attendance->status == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ $attendance->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block font-bold mb-1">📝 Remarks</label>
            <textarea name="remarks" rows="3"
                class="w-full rounded bg-gray-800 border-gray-700 p-2 focus:ring focus:ring-indigo-500"
                placeholder="Optional notes...">{{ $attendance->remarks }}</textarea>
        </div>

        <div class="text-right">
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 px-5 py-2 rounded text-white font-bold transition">
                💾 Update Attendance
            </button>
        </div>
    </form>
</div>

<!-- TomSelect CDN -->
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

<script>
    new TomSelect("#employeeSelect", {
        create: false,
        sortField: { field: "text", direction: "asc" },
        placeholder: "Type to search employee...",
        allowEmptyOption: true
    });
</script>
@endsection
