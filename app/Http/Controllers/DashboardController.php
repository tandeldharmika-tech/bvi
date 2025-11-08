<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Carbon;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\Task;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
{
    use AuthorizesRequests;

    public function index(User $usr = null)
    {
        $user = $usr ?? Auth::user();

        // Get last punch (approved or not, latest)
        $last = Attendance::where('user_id', $user->id)
            ->orderByDesc('punched_at')
            ->first();

        $lastPunchType = $last ? $last->type : null;

        // Define current month's start and TODAY (not end of month)
        $startOfMonth = Carbon::now()->startOfMonth();
        $today = Carbon::today(); // 👈 Important: Only count until today

        if ($user->hasRole('employee')) {
            // Get punch-in dates (IN only, current month, up to today)
            $punchInDates = Attendance::where('user_id', $user->id)
                ->where('type', 'in')
                ->whereBetween('punched_at', [$startOfMonth, $today])
                ->pluck('punched_at')
                ->map(fn($date) => Carbon::parse($date)->toDateString())
                ->unique();

            // Get working days: Mon to Sat from 1st to today
            $workingDays = collect(CarbonPeriod::create($startOfMonth, $today))
                ->filter(fn($date) => $date->dayOfWeek !== Carbon::SUNDAY)
                ->map(fn($date) => $date->toDateString());

            // Absent = working days not in punch-in list
            $absentDays = $workingDays->diff($punchInDates);

            $attendanceSummary = [
                'present' => $punchInDates->count(),
                'absent' => $absentDays->count(),
            ];
        } else {
            // Admin summary (all IN punches this month until today)
            $punchInDates = Attendance::where('type', 'in')
                ->whereBetween('punched_at', [$startOfMonth, $today])
                ->pluck('punched_at')
                ->map(fn($date) => Carbon::parse($date)->toDateString())
                ->unique();

            $attendanceSummary = [
                'present' => $punchInDates->count(),
                'absent' => 0,
            ];
        }

        // 📊 Attendance (last 7 days)
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        // Fetch actual attendance data
        $attendanceData = Attendance::select(
                DB::raw('DATE(punched_at) as date'),
                DB::raw('COUNT(DISTINCT user_id) as total')
            )
            ->whereBetween('punched_at', [$startDate, $endDate])
            ->where('type', 'in')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get()
            ->keyBy('date'); // 🔹 so we can easily look up by date

        // Generate all 7 dates
        $allDates = collect();
        for ($i = 0; $i < 7; $i++) {
            $date = Carbon::now()->subDays(6 - $i)->toDateString();
            $allDates->push($date);
        }

        // Build chart arrays (fill missing dates with 0)
        $attendanceLabels = $allDates->map(fn($d) => Carbon::parse($d)->format('M d'));
        $attendanceCounts = $allDates->map(fn($d) => $attendanceData[$d]->total ?? 0);

        // --- Task Chart Data (status-wise count)
        $taskData = Task::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->get();

        $taskLabels = $taskData->pluck('status')->map(fn($s) => ucfirst($s));
        $taskCounts = $taskData->pluck('count');

        return view('dashboard.index', compact('lastPunchType', 'attendanceSummary','attendanceLabels','attendanceCounts','taskLabels','taskCounts'));
    }

    public function punch(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'location' => 'required|string|max:255',
            'remarks' => 'nullable|string',
        ]);

        $attendance = Attendance::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'location' => $request->location,
            'remarks' => $request->remarks,
            'ip_address' => $request->ip(),  // capture IP
            'status' => 'pending',           // default pending
            'approved_by' => null,
            'punched_at' => now(),
        ]);

        return redirect()->route('dashboard')->with('success', 'Punch ' . $request->type . ' recorded and awaiting approval.');
    }

}
