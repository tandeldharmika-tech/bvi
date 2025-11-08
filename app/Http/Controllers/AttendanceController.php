<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class AttendanceController extends Controller
{
    use AuthorizesRequests;
    
     // List all attendances (Admin/Manager)
    public function index(Request $request)
    {
        $authUser = auth()->user();

        // Get IDs of users visible to the logged-in user
        $visibleUserIds = $authUser->visibleUsers()->pluck('id');

        $query = Attendance::whereIn('user_id', $visibleUserIds);

        // Filter by user name (partial)
        if ($request->filled('user')) {
            $userName = $request->user;
            $query->whereHas('user', function ($q) use ($userName, $visibleUserIds) {
                $q->whereIn('id', $visibleUserIds) // ensure user is visible
                ->where('name', 'like', '%' . $userName . '%');
            });
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date (punched_at)
        if ($request->filled('date')) {
            $query->whereDate('punched_at', $request->date);
        }

        $attendances = Attendance::whereIn('user_id', $visibleUserIds)
    ->orderBy('punched_at', 'desc')
    ->get()
    ->groupBy(function ($item) {
        return $item->user_id . '_' . \Carbon\Carbon::parse($item->punched_at)->format('Y-m-d');
    })
    ->map(function ($records) {
        $user = $records->first()->user;
        $date = \Carbon\Carbon::parse($records->first()->punched_at)->format('Y-m-d');

        $in = $records->firstWhere('type', 'in');
        $out = $records->firstWhere('type', 'out');

        return (object) [
            'user'        => $user,
            'date'        => $date,
            'in_time'     => $in ? $in->punched_at : null,
            'out_time'    => $out ? $out->punched_at : null,
            'location'    => $in->location ?? $out->location ?? '-',
            'status'      => $in->status ?? $out->status ?? '-',
            'approved_by' => $in->approved_by ?? $out->approved_by ?? null,
            'approved_by_name' => $in->approved_by_name ?? $out->approved_by_name ?? '-',
            'remarks'     => $in->remarks ?? $out->remarks ?? null,
        ];
    })
    ->values(); // reset keys

    // **Stats calculation**
        $today = now()->format('Y-m-d');
        $todayAttendances = Attendance::whereIn('user_id', $visibleUserIds)
            ->whereDate('punched_at', $today)
            ->where('type', 'in')
            ->orderBy('punched_at', 'asc')   // earliest first
            ->get()
            ->unique('user_id');

        $totalPresent = $todayAttendances->count();
        $onTime = $todayAttendances->filter(function($a){
            $hour = \Carbon\Carbon::parse($a->punched_at)->format('H:i');
            return $hour >= '09:00' && $hour <= '09:30';
        })->count();
        $late = $todayAttendances->count() - $onTime;
        $pendingApproval = $todayAttendances->where('status', 'pending')->count();
        return view('attendance.index', compact('attendances','totalPresent', 'onTime', 'late', 'pendingApproval'));
    }

    // Show attendance for a specific user
    public function userAttendance(User $user)
    {
    // Start the query for attendances of the user
    $query = Attendance::where('user_id', $user->id);

    // Filter by 'type' if present in request
    if ($type = request('type')) {
        $query->where('type', $type);
    }

    // Filter by 'status' if present in request
    if ($status = request('status')) {
        $query->where('status', $status);
    }

    // Order by punched_at descending and paginate
    $attendances = $query->orderBy('punched_at', 'desc')->paginate(30);

    // Append query params to pagination links to preserve filters
    $attendances->appends(request()->all());

    return view('attendance.user', compact('user', 'attendances'));
}


      // Approve pending attendance entries
  public function approvals(Request $request)
{
    $query = Attendance::where('status', 'pending');

    // Filter by user name (partial)
    if ($request->filled('user')) {
        $userName = $request->user;
        $query->whereHas('user', function($q) use ($userName) {
            $q->where('name', 'like', '%' . $userName . '%');
        });
    }

    // Filter by type
    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    // Filter by a single date (match the date part of punched_at)
    if ($request->filled('date')) {
        $date = $request->date;
        $query->whereDate('punched_at', $date);
    }

    $pendingAttendances = $query->orderBy('punched_at')->paginate(30);

    return view('attendance.approvals', compact('pendingAttendances'));
}


    // Approve pending attendance entries
   public function approve(Request $request, Attendance $attendance)
{
    if (!auth()->user()->can('approve attendance')) {
        abort(403);
    }

    $request->validate([
        'status' => 'required|in:approved,rejected',
    ]);

    $attendance->status = $request->status;
    $attendance->approved_by = Auth::id();
    $attendance->save();

    return back()->with('success', 'Attendance ' . $request->status);
}

public function bulkApprove(Request $request)
{
    if (!auth()->user()->can('approve attendance')) {
        abort(403);
    }

    $statuses = $request->input('statuses', []);

    foreach ($statuses as $attendanceId => $status) {
        if (in_array($status, ['approved', 'rejected'])) {
            $attendance = Attendance::find($attendanceId);
            if ($attendance && $attendance->status === 'pending') {
                $attendance->status = $status;
                $attendance->approved_by = auth()->id();
                $attendance->save();
            }
        }
    }

    return back()->with('success', 'Bulk approvals updated successfully.');
}


    // Export attendance (Excel or CSV)
    public function export()
    {
        // Use your preferred export package, e.g. Maatwebsite Excel
        // return Excel::download(new AttendanceExport, 'attendance.xlsx');

        // For now, simple CSV export example:
        $attendances = Attendance::all();

        $csv = "User ID,Type,Punched At,IP Address,Status,Approved By,Remarks\n";
        foreach ($attendances as $att) {
            $csv .= "{$att->user_id},{$att->type},{$att->punched_at},{$att->ip_address},{$att->status},{$att->approved_by},\"{$att->remarks}\"\n";
        }

        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="attendance.csv"');
    }

    // Punch in / out for attendance
    // public function punch(Request $request)
    // {
    //     $request->validate([
    //         'type' => 'required|in:in,out',
    //         'location' => 'required|string|max:255',
    //         'remarks' => 'nullable|string|max:500',
    //     ]);

    //     $user = Auth::user();

    //     $attendance = Attendance::create([
    //         'user_id' => $user->id,
    //         'type' => $request->type,
    //         'punched_at' => Carbon::now('Asia/Kolkata'),
    //         'location' => $request->location,
    //         'ip_address' => $request->ip(),
    //         'status' => 'pending',  // initially pending approval
    //         'remarks' => $request->remarks,
    //     ]);

    //     return redirect()->back()->with('success', 'Attendance punched ' . ucfirst($request->type) . ' successfully!');
    // }
    
    public function punch(Request $request)
    {
        $request->validate([
            'type' => 'required|in:in,out',
            'location' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();

        // Get last attendance record
        $lastPunch = Attendance::where('user_id', $user->id)
            ->latest('punched_at')
            ->first();

        // Check if last punch was IN
        if ($lastPunch && $lastPunch->type === 'in') {
            $hoursSinceLastPunch = Carbon::parse($lastPunch->punched_at)
                ->diffInHours(Carbon::now('Asia/Kolkata'));

            // If more than 20 hours passed, mark it as auto-closed
            if ($hoursSinceLastPunch >= 20) {
                $lastPunch->update([
                    'status' => 'auto-closed',
                    'remarks' => ($lastPunch->remarks ? $lastPunch->remarks . ' | ' : '') . 'Auto closed after 20 hours',
                ]);
            } elseif ($request->type === 'in') {
                // Prevent new punch-in if still active
                return redirect()->back()->with('error', 'You are already punched in. Please punch out first.');
            }
        }

        // Now create new punch entry
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'type' => $request->type,
            'punched_at' => Carbon::now('Asia/Kolkata'),
            'location' => $request->location,
            'ip_address' => $request->ip(),
            'status' => 'pending',
            'remarks' => $request->remarks,
        ]);

        return redirect()->back()->with('success', 'Attendance punched ' . ucfirst($request->type) . ' successfully!');
    }

    public function create()
    {
        $users = User::select('id', 'name')->get();
        return view('attendance.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:in,out',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:present,absent,pending',
        ]);

        $dateTime = Carbon::parse($request->date . ' ' . $request->time, 'Asia/Kolkata');

        Attendance::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'punched_at' => $dateTime,
            'location' => $request->location,
            'ip_address' => $request->ip(),
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance added successfully!');
    }
    public function edit(Attendance $attendance)
    {
        // Users fetch karo dropdown ke liye
        $users = User::select('id', 'name')->get();
        return view('attendance.edit', compact('attendance', 'users'));
    }
    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:in,out',
            'date' => 'required|date',
            'time' => 'required',
            'location' => 'required|string|max:255',
            'remarks' => 'nullable|string|max:500',
            'status' => 'required|in:present,absent,pending',
        ]);

        // Date + Time combine karo
        $dateTime = Carbon::parse($request->date . ' ' . $request->time, 'Asia/Kolkata');

        // Attendance update
        $attendance->update([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'punched_at' => $dateTime,
            'location' => $request->location,
            'ip_address' => $request->ip(),
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('attendance.index')->with('success', 'Attendance updated successfully!');
    }

}
