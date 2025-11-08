<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Designation;
use App\Models\Department;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DesignationController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designations = Designation::with('department')->latest()->paginate(10);
        return view('designations.index', compact('designations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments = Department::all(); // Make sure to import Department model
        return view('designations.create', compact('departments'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'nullable|exists:departments,id', // or 'required' if mandatory
            'title'         => 'required|string|max:255|unique:designations,title',
            'description'   => 'nullable|string',
        ]);

        // Create the designation
        Designation::create([
            'department_id' => $request->department_id,
            'title'         => $request->title,
            'description'   => $request->description,
        ]);

        return redirect()->route('designations.index')->with('success', 'Designation created successfully.');
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
        $designation = Designation::findOrFail($id);
        $departments = Department::all();

        return view('designations.edit', compact('designation','departments'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $designation = Designation::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:255|unique:designations,title,' . $designation->id,
            'description'  => 'nullable|string',
            'department_id'=> 'nullable|exists:departments,id', // change to 'required' if mandatory
        ]);

        $designation->update([
            'title'         => $request->title,
            'description'   => $request->description,
            'department_id' => $request->department_id,
        ]);

        return redirect()->route('designations.index')->with('success', 'Designation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $designation = Designation::findOrFail($id);
        $designation->delete();
        return redirect()->route('designations.index')->with('success', 'Designation deleted successfully.');
    }

    // DesignationController.php
    public function getByDepartment(Department $department)
    {
        $designations = $department->designations()->select('id', 'title')->get();
        return response()->json($designations);
    }

}
