<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LeaveRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class LeaveRequestController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $user = auth()->user();

    // Get IDs of users visible to logged-in user
    $visibleUserIds = $user->visibleUsers()->pluck('id');

    // If user can approve leave requests (Admin/HR), show all
    if ($user->can('approve leave requests')) {
        $leaves = LeaveRequest::with('user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
    } else {
        // Show only leaves of self or visible users
        $leaves = LeaveRequest::with('user')
                    ->whereIn('user_id', $visibleUserIds)
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
    }

    return view('leaves.index', compact('leaves'));
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->can('create leave requests')) {
            abort(403);
        }

        // Agar logged-in user ka role 'admin' hai toh dusron ke liye leave create kar sakta hai
        if (auth()->user()->hasRole('admin')) {
            // Sabhi users except admin (ya current user) dikhaye jaa sakte hain
            $employees = User::whereDoesntHave('roles', function($query) {
                $query->where('name', 'admin');
            })->get();

            return view('leaves.create', compact('employees'));
        }

        // Non-admin users (employee, manager, etc.)
        return view('leaves.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
          if (!auth()->user()->can('create leave requests')) {
        abort(403);
    }

        $request->validate([
            'leave_type' => 'required|in:sick,casual,paid,unpaid',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string',
            'user_id' => 'sometimes|exists:users,id', // optional validation for admin input
        ]);

        $totalDays = Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;

        // User ID logic
    $userId = auth()->user()->can('create leave requests for others') ? $request->user_id : auth()->id();

        LeaveRequest::create([
            'user_id' => $userId,
            'leave_type' => $request->leave_type,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'total_days' => $totalDays,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('leaves.index')->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          if (!auth()->user()->can('view leaves')) {
        abort(403);
    }
    $leave = LeaveRequest::with('user')->findOrFail($id);

        return view('leaves.show', compact('leave'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
          if (!auth()->user()->can('approve leave requests')) {
        abort(403);
    }
    $leave = LeaveRequest::with('user')->findOrFail($id);

        return view('leaves.edit', compact('leave'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         if (!auth()->user()->can('approve leave requests')) {
        abort(403);
    }

        $request->validate([
            'status' => 'required',
        ]);
            
    $leave = LeaveRequest::findOrFail($id); 

        $leave->status = $request->status;
        $leave->approved_by = Auth::id();
        $leave->approved_at = now();
        $leave->save();

        return redirect()->route('leaves.index')->with('success', 'Leave request updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
