@extends('layouts.app')

@section('title', 'Calendar')

@section('header')
<div class="flex flex-col md:flex-row items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100 mb-2 md:mb-0">📅 Calendar</h1>

    <div class="flex flex-wrap gap-2 text-sm">
        @can('create attendance')
        <button data-type="attendance" class="px-4 py-2 rounded border bg-success text-white"
            onclick="window.location='{{ route('attendance.create') }}'">
            Attendance +
        </button>
        @endcan
        @can('create leave requests')
        <button data-type="leaves" class="px-4 py-2 rounded border bg-primary text-white"
            onclick="window.location='{{ route('leaves.create') }}'">
            Leaves +
        </button>
        @endcan
        @can('create holiday')
        <button data-type="holidays" class="px-4 py-2 rounded border bg-warning text-white"
            onclick="window.location='{{ route('holidays.create') }}'">
            Holidays +
        </button>
        @endcan
    </div>

</div>
@endsection

@section('content')
<div class="flex flex-wrap gap-2 mb-3 text-sm">
    <button id="toggleAllBtn"
        class="px-4 py-2 rounded border bg-gray-800 text-white hover:bg-gray-700 transition">Select All</button>
    <button data-type="attendance"
        class="filter-btn px-4 py-2 rounded border bg-gradient-to-r from-green-400 to-green-600 text-white hover:from-green-500 hover:to-green-700 transition">Attendance</button>
    <button data-type="leaves"
        class="filter-btn px-4 py-2 rounded border bg-gradient-to-r from-blue-400 to-blue-600 text-white hover:from-blue-500 hover:to-blue-700 transition">Leaves</button>
    <button data-type="holidays"
        class="filter-btn px-4 py-2 rounded border bg-gradient-to-r from-purple-400 to-purple-600 text-white hover:from-purple-500 hover:to-purple-700 transition">Holidays</button>
</div>

<div class="min-h-screen text-gray-100">
    <div class="max-w-7xl mx-auto border rounded">
        <div id="calendar" class="rounded-xl p-6 shadow-xl hover:shadow-2xl"></div>
    </div>
</div>

<div id="eventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-900 rounded-lg shadow-2xl p-6 max-w-md w-full relative animate-fade-in">
        <!-- Close Button -->
        <button id="closeModal"
            class="absolute top-3 right-3 text-gray-400 hover:text-white font-bold text-2xl">&times;</button>

        <!-- Event Type Badge -->
        <span id="modalTypeBadge"
            class="inline-block px-3 py-1 text-sm font-bold rounded-full mb-3 text-white bg-blue-600"></span>

        <!-- Event Title -->
        <h3 id="modalTitle" class="font-bold mb-2 text-white"></h3>

        <!-- Date & Time -->
        <p id="modalDate" class="text-gray-300 mb-3"></p>

        <!-- User Info -->
        <div class="flex items-center mb-3">
            <div class="w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white font-bold mr-3"
                id="modalUserInitials">
                <!-- User initials -->
            </div>
            <div>
                <p class="text-gray-200 font-medium" id="modalUserName"></p>
                <p class="text-gray-400 text-sm" id="modalLocation"></p>
            </div>
        </div>

        <!-- Event Details / Remarks -->
        <div class="bg-gray-800 rounded-md p-3">
            <h4 class="text-gray-300 font-bold mb-1">Details:</h4>
            <p id="modalInfo" class="text-gray-400 text-sm"></p>
        </div>
    </div>
</div>
<script>
// Pass Laravel events to JS
window.eventsData = [
    ...@json($events['attendance']),
    ...@json($events['leaves']),
    ...@json($events['holidays'])
];
console.log('eventsData from Blade:', window.eventsData);
</script>
@endsection