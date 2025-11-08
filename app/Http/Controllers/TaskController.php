<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use App\Models\Department;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get IDs of users visible to logged-in user (self + members)
        $visibleUserIds = $user->visibleUsers()->pluck('id');

        $tasksQuery = Task::with('project', 'assignedUser', 'department');

        // 🔍 Filters
        if ($request->filled('project_id')) {
            $tasksQuery->where('project_id', $request->project_id);
        }

        if ($request->filled('assigned_to')) {
            $tasksQuery->where('assigned_to', $request->assigned_to);
        }

        if ($request->filled('status')) {
            $tasksQuery->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $tasksQuery->where('priority', $request->priority);
        }

        // 🔐 Access control: show tasks of visible users
        if (!$user->can('view all tasks')) {
            $tasksQuery->whereIn('assigned_to', $visibleUserIds);
        }

        $tasks = $tasksQuery->latest()->paginate(10)->withQueryString();

        // For filter dropdowns
        $projects = Project::all();     
        $users = User::whereIn('id', $visibleUserIds)->get(); // dropdown shows only visible users

        return view('tasks.index', compact('tasks', 'projects', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        $projects = Project::all();
        $departments = Department::all();

        return view('tasks.create', compact('users', 'projects', 'departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in progress,completed,cancelled',
            'due_date' => 'nullable|date',
        ]);

        Task::create([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'department_id' => $request->department_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::with('project', 'assignedUser', 'department')->findOrFail($id);

        $user = Auth::user();

        // Authorization check: user can view this task only if:
        // 1. They have 'view all tasks' permission
        // OR
        // 2. They are assigned to this task
        if (!$user->can('view task') && $task->assigned_to !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
         $task = Task::findOrFail($id);
        $users = User::all();
        $projects = Project::all();
        $departments = Department::all();

        return view('tasks.edit', compact('task', 'users', 'projects', 'departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title' => 'required|max:255',
            'project_id' => 'nullable|exists:projects,id',
            'assigned_to' => 'nullable|exists:users,id',
            'department_id' => 'nullable|exists:departments,id',
            'priority' => 'required|in:low,medium,high,urgent',
            'status' => 'required|in:pending,in progress,completed,cancelled',
            'due_date' => 'nullable|date',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'project_id' => $request->project_id,
            'assigned_to' => $request->assigned_to,
            'department_id' => $request->department_id,
            'priority' => $request->priority,
            'status' => $request->status,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('tasks.index')->with('success', 'Task updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }

     public function import(Request $request)
    {
dd("This feature is still under development—check out the other features in the meantime!.. Thank you :)");
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls',
        ]);
dd(class_exists('ZipArchive'));

        // File ko read karo
        $data = Excel::toCollection(null, $request->file('file'));
dd($data);
        $rows = $data[0]; // first sheet ka data

        foreach ($rows as $index => $row) {

            // Agar heading row me key name nahi aayi to manually check lagao
            if ($index === 0 && !isset($row['date'])) {
                continue; // skip header if needed
            }

            // Agar empty row hai to skip karo
            if (empty($row['name'])) continue;

            // Project find karo by name
            $project = Project::where('name', $row['projectname'] ?? '')->first();

            // Task create karo
            Task::create([
                'title' => $row['name'],
                'description' => 'Platform: ' . ($row['platform'] ?? '') . ', Calls: ' . ($row['calls'] ?? ''),
                'project_id' => $project ? $project->id : null,
                'priority' => 'medium', // default
                'status' => 'pending',  // default
                'due_date' => $row['date'] ?? null,
                'created_by' => Auth::id(),
            ]);
        }

        return redirect()->route('tasks.index')->with('success', 'Tasks imported successfully.');
    }
}
