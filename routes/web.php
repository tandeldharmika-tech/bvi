<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\DesignationController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\HolidayController;


/*
|--------------------------------------------------------------------------
| Redirect root '/' to login
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/dashboard/user/{user}', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard.user');

Route::post('/attendance/punch', [DashboardController::class, 'punch'])
    ->middleware(['auth', 'verified'])
    ->name('attendance.punch');

/*
|--------------------------------------------------------------------------
| Role Management
|--------------------------------------------------------------------------
*/
Route::resource('roles', RoleController::class);

/*
|--------------------------------------------------------------------------
| User Role Assignment
|--------------------------------------------------------------------------
*/
Route::get('/users', [UserController::class, 'index'])->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::get('/users/{id}', [UserController::class, 'show'])->name('users.show');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');
Route::put('/users/{user}/update-role', [UserController::class, 'updateRoles'])->name('users.updateRoles');
Route::post('/users/{user}/remove-role', [UserController::class, 'removeRole'])->name('users.removeRole');

/*
|--------------------------------------------------------------------------
| Attendance Routes (Requires Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/user/{user}', [AttendanceController::class, 'userAttendance'])->name('attendance.user');
    Route::put('/attendance/{attendance}/approve', [AttendanceController::class, 'approve'])->name('attendance.approve');
    Route::get('/attendance/approvals', [AttendanceController::class, 'approvals'])->name('attendance.approvals');
    Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
    Route::post('/attendance/bulk-approve', [AttendanceController::class, 'bulkApprove'])->name('attendance.bulk-approve');
    Route::get('/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/{attendance}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
});

/*
|--------------------------------------------------------------------------
| Leave, Project, Department, Designation Routes, Task Management, Permission Management
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::resource('leaves', LeaveRequestController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('designations', DesignationController::class);
    Route::resource('tasks', TaskController::class);
    Route::resource('permissions', PermissionController::class);
    Route::resource('holidays', HolidayController::class);

    Route::get('/departments/{department}/designations', [DesignationController::class, 'getByDepartment']);
    Route::get('/calendar', [HolidayController::class, 'calendar'])->name('calendar.index');
    Route::post('/tasks/import', [TaskController::class, 'import'])->name('tasks.import');
});

/*
|--------------------------------------------------------------------------
| User Profile
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';