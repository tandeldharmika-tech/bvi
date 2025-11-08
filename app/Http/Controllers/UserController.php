<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Department;
use App\Models\Designation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $users = $user->visibleUsers()->with('roles')->paginate(10);
        $roles = Role::all();

        return view('users.index', compact('users', 'roles'));
    }

    public function assignRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'Role assigned to user.');
    }

    public function updateRoles(Request $request, User $user)
    {
        $request->validate([
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name',
        ]);

        $user->syncRoles($request->roles ?? []);

        return redirect()->back()->with('success', 'User roles updated.');
    }
    
    public function removeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        if ($user->hasRole($request->role)) {
            $user->removeRole($request->role);
        }

        return redirect()->back()->with('success', 'Role removed from user.');
    }

     // Show create form
    public function create()
    {
        $user = Auth::user();

        // 1️⃣ Get all roles
        $roles = Role::all();

        // 2️⃣ Get all departments
        $departments = Department::all();

        // 3️⃣ Generate next employee code
        $lastUser = User::latest('id')->first();
        $nextNumber = $lastUser ? $lastUser->id + 1 : 1;
        $employeeCode = 'EMP' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        // 4️⃣ Dynamic Parent Users based on role hierarchy
        $userRole = $user->roles->first(); // assuming single role per user
        $parentRoleIds = $this->getChildRoleIds($userRole->id);

        $parentUsers = User::whereHas('roles', function ($q) use ($parentRoleIds) {
            $q->whereIn('id', $parentRoleIds);
        })->get();

        return view('users.create', compact(
            'roles',
            'departments',
            'employeeCode',
            'parentUsers'
        ));
    }

    /**
     * Get all child role IDs for a given role (recursive)
     */
    private function getChildRoleIds($roleId): Collection
    {
        $childIds = collect();
        $childRoles = Role::where('parent_id', $roleId)->get();

        foreach ($childRoles as $child) {
            $childIds->push($child->id);
            $childIds = $childIds->merge($this->getChildRoleIds($child->id));
        }

        return $childIds;
    }

    // Store new user
    public function store(Request $request)
    {
        // 🔹 Step 1: Validate main fields
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'password'   => 'required|string|min:6',
            'parent_id'  => 'nullable',
            'roles'      => 'nullable|array',
            'roles.*'    => 'exists:roles,name',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 🔹 Step 2: Auto-generate email if not provided
        $email = $request->email ?? strtolower($request->first_name . '.' . $request->last_name) . '@gmail.com';
        $request->merge(['email' => $email]);
        $request->validate([
            'email' => 'required|email|unique:users,email',
        ]);

        // 🔹 Step 3: Handle profile photo upload
        $photoPath = null;
        if ($request->hasFile('profile_photo')) {
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if (!empty($request->experience)) {
            foreach ($request->experience as $key => $exp) {
                // Check if the doc exists and is a file
                if (isset($exp['doc']) && $exp['doc'] instanceof \Illuminate\Http\UploadedFile) {
                    // Store the file
                    $path = $exp['doc']->store('experience_docs', 'public');
                    // Replace the input with stored path
                    $request->experience[$key]['doc'] = $path;
                } else {
                    $request->experience[$key]['doc'] = null; // optional
                }
            }
        }

        // 🔹 Step 4: Build JSON structure using nested inputs
        $defaultJson = [
            'personal' => [
                'first_name'     => $request->personal['first_name'] ?? $request->first_name,
                'last_name'      => $request->personal['last_name'] ?? $request->last_name,
                'dob'            => $request->personal['dob'] ?? null,
                'gender'         => $request->personal['gender'] ?? null,
                'maritalStatus'  => $request->personal['maritalStatus'] ?? null,
                'bloodGroup'     => $request->personal['bloodGroup'] ?? null,
                'photo'          => $photoPath,
                'nationality'    => $request->personal['nationality'] ?? null,
            ],
            'contact' => [
                'phone'     => $request->contact['phone'] ?? null,
                'altPhone'  => $request->contact['altPhone'] ?? null,
                'address'   => $request->contact['address'] ?? null,
                'city'      => $request->contact['city'] ?? null,
                'state'     => $request->contact['state'] ?? null,
                'pincode'   => $request->contact['pincode'] ?? null,
            ],
            'official' => [
                'employeeCode'   => $request->official['employeeCode'] ?? null,
                'designation'    => $request->official['designation'] ?? null,
                'department'     => $request->official['department'] ?? null,
                'joiningDate'    => $request->official['joiningDate'] ?? null,
                'managerId'      => $request->parent_id ?? null,
                'location'       => $request->official['location'] ?? null,
                'employmentType' => $request->official['employmentType'] ?? null,
                'role'           => $request->official['role'] ?? null,
                'status'         => $request->official['status'] ?? null,
                'lastWorkingDate'=> $request->official['lastWorkingDate'] ?? null,
            ],
            'payroll' => [
                'bankName'        => $request->payroll['bankName'] ?? null,
                'accountNumber'   => $request->payroll['accountNumber'] ?? null,
                'ifsc'            => $request->payroll['ifsc'] ?? null,
                'pan'             => $request->payroll['pan'] ?? null,
                'salary'          => $request->payroll['salary'] ?? null,
                'lastSalaryUpdate'=> $request->payroll['lastSalaryUpdate'] ?? null,
                'costCenter'      => $request->payroll['costCenter'] ?? null,
            ],
            'education'          => $request->education ?? [],
            'experience'         => $request->experience ?? [],
            'salary_history'     => $request->salary_history ?? [],
            'documents'          => $request->documents ?? [],
            'emergency_contacts' => $request->emergency_contacts ?? [],
            'notes'              => $request->notes ?? [],
            'audit'              => [],
        ];

        // 🔹 Step 5: Create user
        $user = User::create([
            'name'       => trim($request->first_name . ' ' . $request->last_name),
            'email'      => $email,
            'parent_id'  => $request->parent_id,
            'password'   => Hash::make($request->password),
            'personal'   => $defaultJson['personal'], 
            'contact'    => $defaultJson['contact'],
            'official'   => $defaultJson['official'],
            'payroll'    => $defaultJson['payroll'],
            'education'  => $defaultJson['education'],
            'experience' => $defaultJson['experience'],
            'salary_history' => $defaultJson['salary_history'],
            'documents'      => $defaultJson['documents'],
            'emergency_contacts' => $defaultJson['emergency_contacts'],
            'notes'      => $defaultJson['notes'],
            'audit'      => $defaultJson['audit'],
        ]);

        // 🔹 Step 6: Assign roles
        if (!empty($request->official['role'])) {
            $user->syncRoles($request->official['role']);
        }


        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Show edit form
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        $departments = Department::all();
        $designations = Designation::all();

        $currentUser = Auth::user();

        // Get the current user's primary role (assuming single role)
        $userRole = $currentUser->roles->first();

        if ($userRole) {
            // Get all child role IDs dynamically
            $childRoleIds = $this->getChildRoleIds($userRole->id);

            // Fetch users having those roles
            $parentUsers = User::whereHas('roles', function ($q) use ($childRoleIds) {
                $q->whereIn('id', $childRoleIds);
            })->get();
        } else {
            $parentUsers = collect(); // empty if no role assigned
        }

        return view('users.edit', compact(
            'user',
            'roles',
            'departments',
            'designations',
            'parentUsers'
        ));
    }

    // Update user
    public function update(Request $request, User $user)
    {
        // 🔹 Step 1: Validate main fields
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'password'   => 'nullable|string|min:6', // optional on update
            'parent_id'  => 'nullable',
            'roles'      => 'nullable|array',
            'roles.*'    => 'exists:roles,name',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        // 🔹 Step 2: Handle email uniqueness (ignore current user)
        $email = $request->email ?? strtolower($request->first_name . '.' . $request->last_name) . '@gmail.com';
        $request->merge(['email' => $email]);
        $request->validate([
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        // 🔹 Step 3: Handle profile photo update
        $photoPath = $user->personal['photo'] ?? null;
        if ($request->hasFile('profile_photo')) {
            if ($photoPath && \Storage::disk('public')->exists($photoPath)) {
                \Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // 🔹 Step 4: Handle experience documents
        $experience = $request->experience ?? [];

        foreach ($experience as $key => $exp) {
            if (isset($exp['doc']) && $exp['doc'] instanceof \Illuminate\Http\UploadedFile) {
                // Store uploaded file
                $experience[$key]['doc'] = $exp['doc']->store('experience_docs', 'public');
            } else {
                // If no file provided, keep null or existing doc
                $experience[$key]['doc'] = $exp['doc'] ?? null;
            }
        }

        // 🔹 Step 5: Prepare JSON data
        $personal = [
            'first_name'    => $request->personal['first_name'] ?? $request->first_name,
            'last_name'     => $request->personal['last_name'] ?? $request->last_name,
            'dob'           => $request->personal['dob'] ?? null,
            'gender'        => $request->personal['gender'] ?? null,
            'maritalStatus' => $request->personal['maritalStatus'] ?? null,
            'bloodGroup'    => $request->personal['bloodGroup'] ?? null,
            'photo'         => $photoPath,
            'nationality'   => $request->personal['nationality'] ?? null,
        ];

        $official = [
            'employeeCode'    => $request->official['employeeCode'] ?? null,
            'designation'     => $request->official['designation'] ?? null,
            'department'      => $request->official['department'] ?? null,
            'joiningDate'     => $request->official['joiningDate'] ?? null,
            'managerId'       => $request->parent_id ?? null,
            'location'        => $request->official['location'] ?? null,
            'employmentType'  => $request->official['employmentType'] ?? null,
            'role'            => $request->official['role'] ?? null,
            'status'          => $request->official['status'] ?? null,
            'lastWorkingDate' => $request->official['lastWorkingDate'] ?? null,
        ];

        $payroll = [
            'bankName'        => $request->payroll['bankName'] ?? null,
            'accountNumber'   => $request->payroll['accountNumber'] ?? null,
            'ifsc'            => $request->payroll['ifsc'] ?? null,
            'pan'             => $request->payroll['pan'] ?? null,
            'salary'          => $request->payroll['salary'] ?? null,
            'lastSalaryUpdate'=> $request->payroll['lastSalaryUpdate'] ?? null,
            'costCenter'      => $request->payroll['costCenter'] ?? null,
        ];

        // 🔹 Step 6: Update user
        $user->update([
            'name'       => trim($request->first_name . ' ' . $request->last_name),
            'parent_id'  => $request->parent_id,
            'email'      => $email,
            'password'   => $request->password ? Hash::make($request->password) : $user->password,
            'personal'   => $personal,
            'contact'    => $request->contact ?? $user->contact,
            'official'   => $official,
            'payroll'    => $payroll,
            'education'  => $request->education ?? $user->education,
            'experience' => $request->experience ?? $user->experience,
            'salary_history' => $request->salary_history ?? $user->salary_history,
            'documents'      => $request->documents ?? $user->documents,
            'emergency_contacts' => $request->emergency_contacts ?? $user->emergency_contacts,
            'notes'      => $request->notes ?? $user->notes,
            'audit'      => $user->audit,
        ]);

        // 🔹 Step 7: Sync roles
        if (!empty($request->official['role'])) {
            $user->syncRoles($request->official['role']);
        }

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Show user details
    public function show(string $id)
    {
        $user = User::findOrFail($id);

        // Optional: load related models if needed
        $roles = Role::all();
        $departments = Department::all();
        $designations = Designation::all();

        return view('users.show', compact('user', 'roles', 'departments', 'designations'));
    }

    // Delete user
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
