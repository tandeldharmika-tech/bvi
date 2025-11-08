@extends('layouts.app')
@section('title', 'Dashboard')
@section('header')
{{-- 🏠 Header --}}
<div class="bg-gradient-to-r from-blue-600 to-indigo-700 text-white p-3 rounded-lg shadow-md mb-0">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <h2 class="fw-semibold mb-0">👋 Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="mb-0 opacity-75">Today is {{ now()->format('l, F j, Y') }}</p>
        </div>
        <div>
            <i class="fa-solid fa-chart-pie fa-3x opacity-75"></i>
        </div>
    </div>
</div>
@endsection

@section('content')
{{-- 📊 Stats Overview --}}
<div class="row">

    {{-- Projects --}}
    @can('view projects')
    <div class="col-md-3 mb-4">
        <a href="{{ route('projects.index') }}" class="text-decoration-none">
            <div class="card bg-info border text-white shadow-sm hover:shadow-lg transition">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-dark font-bold">Projects</h6>
                        <i class="fa-solid fa-diagram-project text-primary fs-3"></i>
                    </div>
                    <h3 class="fw-bold mt-2 text-dark">{{ \App\Models\Project::count() }}</h3>
                    <small class="text-gray-400 text-dark">Active / Total</small>
                </div>
            </div>
        </a>
    </div>
    @endcan

    {{-- Tasks --}}
    @can('view task')
    <div class="col-md-3 mb-4">
        <a href="{{ route('tasks.index') }}" class="text-decoration-none">
            <div class="card bg-warning border text-white shadow-sm hover:shadow-lg transition">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-dark font-bold">Tasks</h6>
                        <i class="fa-solid fa-list-check text-success fs-3"></i>
                    </div>
                    <h3 class="fw-bold mt-2 text-dark">{{ \App\Models\Task::count() }}</h3>
                    <small class="text-gray-400 text-dark">All Assigned Tasks</small>
                </div>
            </div>
        </a>
    </div>
    @endcan

    {{-- Leaves --}}
    @can('view leaves')
    <div class="col-md-3 mb-4">
        <a href="{{ route('leaves.index') }}" class="text-decoration-none">
            <div class="card bg-success border text-white shadow-sm hover:shadow-lg transition">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-dark font-bold">Leaves</h6>
                        <i class="fa-solid fa-leaf text-warning fs-3"></i>
                    </div>
                    <h3 class="fw-bold mt-2 text-dark">
                        {{ \App\Models\LeaveRequest::where('status','approved')->count() }}
                    </h3>
                    <small class="text-gray-400 text-dark">Approved Leaves</small>
                </div>
            </div>
        </a>
    </div>
    @endcan

    {{-- Users --}}
    @can('manage users')
    <div class="col-md-3 mb-4">
        <a href="{{ route('users.index') }}" class="text-decoration-none">
            <div class="card bg-primary border text-white shadow-sm hover:shadow-lg transition">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="text-dark font-bold">Users</h6>
                        <i class="fa-solid fa-users text-info fs-3"></i>
                    </div>
                    <h3 class="fw-bold mt-2 text-dark">{{ \App\Models\User::count() }}</h3>
                    <small class="text-gray-400 text-dark">Total Users</small>
                </div>
            </div>
        </a>
    </div>
    @endcan

</div>

{{-- 🕒 Attendance Summary / Punch --}}
@can('mark attendance')
<div class="card bg-gray-800 border text-white shadow-md mb-4">
    <div class="card-header border-bottom border-gray-900  bg-success">
        <h5 class="mb-0"><i class="fa-regular fa-clock me-2 text-warning"></i> Attendance Punch</h5>
    </div>
    <div class="card-body">
        @php
        $nextPunch = ($lastPunchType ?? 'out') === 'in' ? 'out' : 'in';
        $btnClass = $nextPunch === 'in' ? 'btn-success' : 'btn-danger';
        @endphp

        <form method="POST" action="{{ route('attendance.punch') }}" id="punchForm" class="row g-3 align-items-end">
            @csrf
            <input type="hidden" name="type" value="{{ $nextPunch }}">

            <div class="col-md-5">
                <label class="form-label text-gray-300">📍 Location</label>
                <input type="text" name="location" value="Office" required
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
            </div>

            <div class="col-md-5">
                <label class="form-label text-gray-300">📝 Remarks</label>
                <input type="text" name="remarks" placeholder="Any notes..."
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-whitebg-gray-900 border-gray-700">
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn {{ $btnClass }} w-100 fw-semibold">
                    ⏺ Punch {{ ucfirst($nextPunch) }}
                </button>
            </div>
        </form>

        {{-- Summary --}}
        <div class="row mt-4 text-center">
            <div class="col-md-6">
                <div class="p-3 bg-green-900/20 border border-green-700 rounded text-green-400">
                    ✅ Present: <strong>{{ $attendanceSummary['present'] ?? 0 }}</strong>
                </div>
            </div>
            <div class="col-md-6">
                <div class="p-3 bg-red-900/20 border border-red-700 rounded text-red-400">
                    ❌ Absent: <strong>{{ $attendanceSummary['absent'] ?? 0 }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endcan

{{-- 📈 Charts --}}
<div class="row">
    @can('view attendance')
    <div class="col-md-6 mb-4">
        <div class="card bg-gray-800 border text-white shadow-sm">
            <div class="card-header border-bottom border-gray-700 bg-danger">
                <i class="fa-solid fa-chart-line text-warning me-2"></i> Attendance Trend
            </div>
            <div class="card-body">
                <canvas id="attendanceChart" height="200"></canvas>
            </div>
        </div>
    </div>
    @endcan

    @can('view task')
    <div class="col-md-6 mb-4">
        <div class="card bg-gray-800 border text-white shadow-sm">
            <div class="card-header border-bottom border-gray-700 bg-info">
                <i class="fa-solid fa-tasks text-dark me-2"></i> Task Progress
            </div>
            <div class="card-body">
                <canvas id="taskChart" height="200"></canvas>
            </div>
        </div>
    </div>
    @endcan
</div>

{{-- 🧾 Key Lists (Recent + Calendar + Announcements) --}}
<div class="row">
    {{-- Recent Activities --}}
    @can('view task')
    <div class="col-md-4 mb-4">
        <div class="card bg-gray-800 border text-white h-100 shadow-sm">
            <div class="card-header border-bottom border-gray-700 bg-success">
                <i class="fa-solid fa-clock-rotate-left text-warning me-2"></i> Recent Activities
            </div>
            <ul class="list-group list-group-flush">
                @foreach (\App\Models\Task::latest()->take(5)->get() as $task)
                <li class="list-group-item bg-transparent text-white border-gray-700">
                    📝 {{ $task->title }} – <strong>{{ ucfirst($task->status) }}</strong>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endcan

    {{-- Upcoming Holidays --}}
    @can('view holidays')
    <div class="col-md-4 mb-4">
        <div class="card bg-gray-800 border text-white h-100 shadow-sm">
            <div class="card-header border-bottom border-gray-700 bg-primary">
                <i class="fa-regular fa-calendar-days text-info me-2"></i> Upcoming Holidays
            </div>
            <ul class="list-group list-group-flush">
                @foreach(\App\Models\Holiday::orderBy('date','asc')->take(5)->get() as $holiday)
                <li class="list-group-item bg-transparent text-white border-gray-700">
                    🎉 {{ $holiday->title }} -
                    <strong>{{ \Carbon\Carbon::parse($holiday->date)->format('M d, Y') }}</strong>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
    @endcan

    {{-- Announcements --}}
    <div class="col-md-4 mb-4">
        <div class="card bg-gray-800 border text-white h-100 shadow-sm">
            <div class="card-header border-bottom border-gray-700 bg-warning">
                <i class="fa-solid fa-bullhorn text-danger me-2"></i> Announcements
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-transparent text-white border-gray-700">
                    🚀 New feature rollout next week!
                </li>
                <li class="list-group-item bg-transparent text-white border-gray-700">
                    🗓️ Company closed on <strong>Nov 15</strong> (Holiday)
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- Optional Punch Status (JS-based) --}}
<div id="punchStatus" class="text-light small mt-2"></div>

<script>
function submitPunch(type) {
    document.getElementById('punchType').value = type;
    document.getElementById('punchForm').submit();
    document.getElementById('punchStatus').innerText = "Punch " + type.toUpperCase() + " submitted...";
}
</script>
<!-- 
{{-- 🔹 Charts --}}
<div class="row">
    @can('view all attendance')
    <div class="col-md-6 mb-4">
        <div class="card border dark:bg-gray-800 rounded shadow text-light">
            <div class="card-header">📈 Attendance Trend</div>
            <div class="card-body" height="150">
                <canvas id="attendanceChart"></canvas>
            </div>
        </div>
    </div>
    @endcan

    @can('view task')
    <div class="col-md-6 mb-4">
        <div class="card border dark:bg-gray-800 rounded text-light shadow">
            <div class="card-header">✅ Task Status</div>
            <div class="card-body" height="100">
                <canvas id="taskChart"></canvas>
            </div>
        </div>
    </div>
    @endcan
</div>

<div class="row">
    {{-- 🔸 Recent Activities --}}
    <div class="col-md-6 mb-4">
        <div class="bg-gray-800 rounded border shadow-md p-3 h-100 text-white">
            <div class="flex items-center justify-between border-b border-gray-700 pb-2 mb-3">
                <h3 class="text-lg font-bold flex items-center space-x-2">
                    <span>📋</span>
                    <span>Recent Activities</span>
                </h3>
            </div>
            <ul class="space-y-2">
                @foreach (\App\Models\Task::latest()->take(5)->get() as $task)
                <li class="flex items-start bg-gray-900 hover:bg-gray-700 transition rounded-lg p-3">
                    <span class="text-xl mr-3">📝</span>
                    <span class="text-sm font-medium text-gray-100">
                        {{ $task->title }} - <strong>{{ ucfirst($task->status) }}</strong>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- 🔸 Calendar --}}
    <div class="col-md-6 mb-4">
        <div class="bg-gray-800 rounded border shadow-md p-3 h-100 text-white">
            <div class="flex items-center justify-between border-b border-gray-700 pb-2 mb-3">
                <h3 class="text-lg font-bold flex items-center space-x-2">
                    <span>📆</span>
                    <span>Calendar (Sample)</span>
                </h3>
            </div>
            <ul class="space-y-2">
                <li class="flex items-start bg-gray-900 hover:bg-gray-700 transition rounded-lg p-3">
                    <span class="text-xl mr-3">🎉</span>
                    <span class="text-sm font-medium text-gray-100">Diwali - <strong>Nov 12</strong></span>
                </li>
                <li class="flex items-start bg-gray-900 hover:bg-gray-700 transition rounded-lg p-3">
                    <span class="text-xl mr-3">📋</span>
                    <span class="text-sm font-medium text-gray-100">Task Deadline - <strong>Oct 20</strong></span>
                </li>
                <li class="flex items-start bg-gray-900 hover:bg-gray-700 transition rounded-lg p-3">
                    <span class="text-xl mr-3">🌿</span>
                    <span class="text-sm font-medium text-gray-100">Leave - <strong>Oct 17</strong></span>
                </li>
            </ul>
        </div>
    </div>
</div>

{{-- 🔹 Announcements --}}
<div class="bg-gray-800 rounded border shadow-md p-3 mb-3 text-white">
    <div class="flex items-center justify-between border-b border-gray-700 pb-2 mb-3">
        <h3 class="text-lg font-bold flex items-center space-x-2">
            <span>📢</span>
            <span>Announcements</span>
        </h3>
    </div>
    <ul class="space-y-1">
        <li class="flex items-start bg-gray-900 hover:bg-gray-700 transition rounded-lg p-3">
            <span class="text-xl mr-3">🗓️</span>
            <span class="text-sm font-medium text-gray-100">Company will remain closed on <strong>Oct 24</strong>
                (Holiday).</span>
        </li>
        <li class="flex items-start bg-gray-900 hover:bg-gray-700 transition rounded-lg p-3">
            <span class="text-xl mr-3">🚀</span>
            <span class="text-sm font-medium text-gray-100">New feature rollout coming <strong>next
                    week</strong>.</span>
        </li>
    </ul>
</div> -->
@endsection

@section('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
{{-- Attendance Chart --}}
<script>
const attendanceLabels = @json($attendanceLabels);
const attendanceCounts = @json($attendanceCounts);
console.log(attendanceCounts);
const ctxA = document.getElementById('attendanceChart').getContext('2d');
new Chart(ctxA, {
    type: 'line',
    data: {
        labels: attendanceLabels,
        datasets: [{
            label: 'Employees Present',
            data: attendanceCounts,
            borderColor: '#0d6efd',
            backgroundColor: 'rgba(13, 110, 253, 0.3)',
            fill: true,
            tension: 0.3,
            borderWidth: 2,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, 
        plugins: {
            legend: {
                labels: { color: '#fff' }
            }
        },
        scales: {
            y: { ticks: { color: '#fff' }, beginAtZero: true },
            x: { ticks: { color: '#fff' } }
        }
    }
});
</script>

{{-- Task Chart --}}
<script>
const taskLabels = @json($taskLabels);
const taskCounts = @json($taskCounts);
console.log(taskCounts);
const ctxT = document.getElementById('taskChart').getContext('2d');
new Chart(ctxT, {
    type: 'doughnut',
    data: {
        labels: taskLabels,
        datasets: [{
            label: 'Tasks',
            data: taskCounts,
            backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545'],
            borderColor: '#222',
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, 
        plugins: {
            legend: {
                labels: { color: '#fff' }
            }
        }
    }
});
</script>
@endsection