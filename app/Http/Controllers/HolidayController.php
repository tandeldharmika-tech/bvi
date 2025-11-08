<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $holidays = Holiday::orderBy('date')->latest()->paginate(10);
        return view('holidays.index', compact('holidays'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('holidays.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
       $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date',
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        Holiday::create($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $holiday = Holiday::findOrFail($id);
        return view('holidays.edit', compact('holiday'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date|unique:holidays,date,' . $holiday->id,
            'type' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_recurring' => 'boolean',
        ]);

        $holiday->update($request->all());

        return redirect()->route('holidays.index')->with('success', 'Holiday updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $holiday->delete();

        return back()->with('success', 'Holiday deleted successfully.');
    }

    public function calendar()
    {
        $attendanceEvents = Attendance::with('user')->get()->map(function($a) {
            $userName = $a->user->name ?? 'Unknown';
            $bgColor = $a->type === 'in' ? '#16a34a' : '#dc2626';
            return [
                'title' => $userName . ' - ' . ucfirst($a->type),
                'start' => $a->punched_at, 
                'color' => $bgColor,
                'textColor' => '#fff',
                'type' => 'attendance', 
                'extendedProps' => [
                    'info' => $a->remarks ?? '', 
                    'location' => $a->location ?? '', 
                    'user' => $userName, 
                ],
            ];
        });

        $leaveEvents = LeaveRequest::with('user')->get()->map(function($l) {
            $userName = $l->user->name ?? 'Unknown';
            $bgColor = $l->status === 'approved' ? '#2563eb' : '#facc15';
            $txtColor = $l->status === 'approved' ? '#fff' : '#111';
            return [
                'title' => $userName . ' - Leave (' . ucfirst($l->status) . ')',
                'start' => $l->start_date,
                'end' => $l->end_date,
                'color' => $bgColor,
                'textColor' => $txtColor,
                'type' => 'leaves',
                'extendedProps' => [
                    'info' => $l->reason ?? '', 
                    'user' => $userName,
                ],
            ];
        });

        $holidayEvents = Holiday::all()->map(function($h) {
            return [
                'title' => 'Holiday: ' . $h->title,
                'start' => $h->date,
                'color' => '#9333ea',
                'textColor' => '#fff',
                'type' => 'holidays',
                'extendedProps' => ['info' => $h->description ?? ''],
            ];
        });

        $events = [
            'attendance' => $attendanceEvents->toArray(),
            'leaves' => $leaveEvents->toArray(),
            'holidays' => $holidayEvents->toArray(),
        ];

        return view('calendar', compact('events'));
    }
}
