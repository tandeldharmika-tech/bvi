@extends('layouts.app')

@section('title', 'Company Calendar')

@section('content')
<div class="min-h-screen p-6 dark:bg-gray-800 text-gray-100">
    <div class="max-w-7xl mx-auto">
        <!-- 🧭 Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h1 class="font-bold">📅 Company Calendar</h1>
                <p class="text-gray-400 text-sm">Attendance • Leaves • Holidays overview</p>
            </div>
            <div class="space-x-2 mt-3 sm:mt-0">
                <button id="prevBtn" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm">← Prev</button>
                <button id="todayBtn" class="px-3 py-1 bg-indigo-600 hover:bg-indigo-500 rounded text-sm">Today</button>
                <button id="nextBtn" class="px-3 py-1 bg-gray-700 hover:bg-gray-600 rounded text-sm">Next →</button>
            </div>
        </div>

        <!-- 🔖 Legend -->
        <div class="flex flex-wrap gap-2 mb-4 text-sm">
            <span class="px-2 py-1 rounded bg-green-600">Present</span>
            <span class="px-2 py-1 rounded bg-red-600">Absent</span>
            <span class="px-2 py-1 rounded bg-blue-600">Approved Leave</span>
            <span class="px-2 py-1 rounded bg-yellow-500 text-gray-900">Pending Leave</span>
            <span class="px-2 py-1 rounded bg-purple-600">Holiday</span>
        </div>

        <!-- 📅 Calendar -->
        <div id="calendar" class="rounded-lg bg-gray-900 p-4 shadow-lg"></div>
    </div>
</div>
@endsection

@push('styles')
<style>
    /* -------- FullCalendar dark theme tweaks ---------- */
    #calendar {
        color: #e5e7eb;
    }
    .fc {
        background-color: transparent;
    }
    .fc-toolbar-title {
        color: #fff;
        font-weight: 600;
        font-size: 1.1rem;
    }
    .fc-daygrid-day {
        border-color: #374151; /* gray-700 */
    }
    .fc-theme-standard .fc-scrollgrid {
        border-color: #4b5563;
    }
    .fc-daygrid-day-number {
        color: #d1d5db;
    }
    .fc-day-today {
        background-color: rgba(79, 70, 229, 0.2) !important; /* indigo highlight */
    }
    .fc-daygrid-event {
        border: none;
        border-radius: 6px;
        font-size: 0.8rem;
        padding: 2px 4px;
    }
</style>
@endpush

@push('scripts')
<script type="module">
import { Calendar } from 'fullcalendar';
import dayGridPlugin from 'fullcalendar/daygrid';
import interactionPlugin from 'fullcalendar/interaction';

/* --- Static demo data --- */
const staticEvents = [
    { title: 'Present', start: '2025-10-01', color: '#16a34a' },
    { title: 'Absent', start: '2025-10-02', color: '#dc2626' },
    { title: 'Leave (Approved)', start: '2025-10-03', end: '2025-10-05', color: '#2563eb' },
    { title: 'Leave (Pending)', start: '2025-10-08', end: '2025-10-09', color: '#facc15', textColor: '#111' },
    { title: 'Holiday: Diwali', start: '2025-10-12', color: '#9333ea' },
    { title: 'Present', start: '2025-10-14', color: '#16a34a' },
    { title: 'Present', start: '2025-10-15', color: '#16a34a' },
];

document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('calendar');
    const calendar = new Calendar(calendarEl, {
        plugins: [dayGridPlugin, interactionPlugin],
        initialView: 'dayGridMonth',
        height: 'auto',
        events: staticEvents,
        eventClick: (info) => {
            alert(`Event: ${info.event.title}`);
        },
        eventDidMount: (info) => {
            info.el.setAttribute('title', info.event.title);
        },
    });

    calendar.render();

    /* Custom navigation buttons */
    document.getElementById('todayBtn').addEventListener('click', () => calendar.today());
    document.getElementById('nextBtn').addEventListener('click', () => calendar.next());
    document.getElementById('prevBtn').addEventListener('click', () => calendar.prev());
});
</script>
@endpush
