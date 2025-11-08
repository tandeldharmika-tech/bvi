@extends('layouts.app')
@section('title', 'User Details')
@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100"> User Details</h1>

    <a href="{{ route('users.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Users
    </a>
</div>
@endsection
@section('content')
@can('view attendance')
<a href="{{ route('attendance.index') }}"
            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded shadow transition">
            Attendance
        </a>
        @endcan
@can('view task')
<a href="{{ route('tasks.index') }}"
            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded shadow transition">
            Tasks
        </a>
        @endcan
@can('view leaves')
<a href="{{ route('leaves.index') }}"
            class="inline-flex items-center px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded shadow transition">
            Leaves
        </a>
        @endcan
<div class="mx-auto p-3 bg-gray-900 border border-gray-800 rounded-lg shadow-md text-white">

    <h1 class="font-bold mb-3">User Name: {{ $user->name }}</h1>

    <div x-data="{ tab: 'personal' }">
        {{-- Tabs --}}
        <div class="flex space-x-4 mb-3">
            <button type="button" @click="tab='personal'"
                :class="tab==='personal' ? 'font-bold border-b-2 border-blue-500' : ''">Personal</button>
            <button type="button" @click="tab='contact'"
                :class="tab==='contact' ? 'font-bold border-b-2 border-blue-500' : ''">Contact</button>
            <button type="button" @click="tab='official'"
                :class="tab==='official' ? 'font-bold border-b-2 border-blue-500' : ''">Official</button>
            <button type="button" @click="tab='payroll'"
                :class="tab==='payroll' ? 'font-bold border-b-2 border-blue-500' : ''">Payroll</button>
            <button type="button" @click="tab='education'"
                :class="tab==='education' ? 'font-bold border-b-2 border-blue-500' : ''">Education</button>
            <button type="button" @click="tab='experience'"
                :class="tab==='experience' ? 'font-bold border-b-2 border-blue-500' : ''">Experience</button>
        </div>

        {{-- Personal Info --}}
        <div x-show="tab==='personal'">
            <fieldset class="border p-4 rounded mb-6">
                <legend class="font-bold">Personal Information</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><strong>First Name:</strong> {{ $user->personal['first_name'] ?? '-' }}</div>
                    <div><strong>Last Name:</strong> {{ $user->personal['last_name'] ?? '-' }}</div>
                    <div><strong>Date of Birth:</strong> {{ $user->personal['dob'] ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div><strong>Email:</strong> {{ $user->email ?? '-' }}</div>
                    <div><strong>Gender:</strong> {{ ucfirst($user->personal['gender'] ?? '-') }}</div>
                    <div><strong>Marital Status:</strong> {{ $user->personal['maritalStatus'] ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div><strong>Blood Group:</strong> {{ $user->personal['bloodGroup'] ?? '-' }}</div>
                    <div><strong>Nationality:</strong> {{ $user->personal['nationality'] ?? '-' }}</div>
                </div>

                <div class="mt-4">
                    <strong>Profile Photo:</strong><br>
                    @if(!empty($user->personal['photo']))
                    <img src="{{ asset('storage/'.$user->personal['photo']) }}" alt="Profile Photo"
                        class="mt-2 w-24 h-24 object-cover rounded">
                    @else
                    <span>No photo</span>
                    @endif
                </div>
            </fieldset>
        </div>

        {{-- Contact Info --}}
        <div x-show="tab==='contact'">
            <fieldset class="border p-4 rounded mb-6">
                <legend class="font-bold">Contact Information</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><strong>Phone:</strong> {{ $user->contact['phone'] ?? '-' }}</div>
                    <div><strong>Alternate Phone:</strong> {{ $user->contact['altPhone'] ?? '-' }}</div>
                    <div><strong>City:</strong> {{ $user->contact['city'] ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div><strong>Address:</strong> {{ $user->contact['address'] ?? '-' }}</div>
                    <div><strong>State:</strong> {{ $user->contact['state'] ?? '-' }}</div>
                    <div><strong>Pincode:</strong> {{ $user->contact['pincode'] ?? '-' }}</div>
                </div>
            </fieldset>
        </div>

        {{-- Official Info --}}
        <div x-show="tab==='official'">
            <fieldset class="border p-4 rounded mb-6">
                <legend class="font-bold">Official Information</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><strong>Employee Code:</strong> {{ $user->official['employeeCode'] ?? '-' }}</div>
                    <div><strong>Department:</strong>
                        {{ $departments->firstWhere('id', $user->official['department'])?->name ?? '-' }}
                    </div>
                    <div><strong>Designation:</strong>
                        {{ $designations->firstWhere('id', $user->official['designation'])?->name ?? '-' }}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div><strong>Joining Date:</strong> {{ $user->official['joiningDate'] ?? '-' }}</div>
                    <div><strong>Manager ID:</strong> {{ $user->official['managerId'] ?? '-' }}</div>
                    <div><strong>Location:</strong> {{ $user->official['location'] ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div><strong>Employment Type:</strong> {{ ucfirst($user->official['employmentType'] ?? '-') }}</div>
                    <div><strong>Role:</strong> {{ $user->official['role'] ?? '-' }}</div>
                    <div><strong>Status:</strong> {{ ucfirst($user->official['status'] ?? '-') }}</div>
                </div>

                <div class="mt-4">
                    <strong>Last Working Date:</strong> {{ $user->official['lastWorkingDate'] ?? '-' }}
                </div>
            </fieldset>
        </div>

        {{-- Payroll Info --}}
        <div x-show="tab==='payroll'">
            <fieldset class="border p-4 rounded mb-6">
                <legend class="font-bold">Payroll Information</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div><strong>Bank Name:</strong> {{ $user->payroll['bankName'] ?? '-' }}</div>
                    <div><strong>Account Number:</strong> {{ $user->payroll['accountNumber'] ?? '-' }}</div>
                    <div><strong>IFSC:</strong> {{ $user->payroll['ifsc'] ?? '-' }}</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div><strong>PAN:</strong> {{ $user->payroll['pan'] ?? '-' }}</div>
                    <div><strong>Salary:</strong> {{ $user->payroll['salary'] ?? '-' }}</div>
                    <div><strong>Last Salary Update:</strong> {{ $user->payroll['lastSalaryUpdate'] ?? '-' }}</div>
                </div>

                <div class="mt-4">
                    <strong>Cost Center:</strong> {{ $user->payroll['costCenter'] ?? '-' }}
                </div>
            </fieldset>
        </div>

        <div x-show="tab==='education'">
            <fieldset class="border p-4 rounded mb-6">
                <legend class="font-bold">Education</legend>
                @if(!empty($user->education))
                @foreach($user->education as $edu)
                <div class="border p-4 rounded relative bg-gray-50 dark:bg-gray-800 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="font-bold">Degree</label>
                            <div>{{ $edu['degree'] ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="font-bold">Specialization</label>
                            <div>{{ $edu['specialization'] ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="font-bold">Institution</label>
                            <div>{{ $edu['institution'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="font-bold">Year</label>
                            <div>{{ $edu['year'] ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="font-bold">Grade</label>
                            <div>{{ $edu['grade'] ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="font-bold">Document</label>
                            @if(!empty($edu['doc']))
                            <a href="{{ asset('storage/'.$edu['doc']) }}" target="_blank"
                                class="text-blue-500 underline">View</a>
                            @else
                            <div>-</div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div>No education details available</div>
                @endif
            </fieldset>
        </div>
        <div x-show="tab==='experience'">
            <fieldset class="border p-4 rounded mb-6">
                <legend class="font-bold">Experience</legend>
                @if(!empty($user->experience))
                @foreach($user->experience as $exp)
                <div class="border p-4 rounded relative bg-gray-50 dark:bg-gray-800 mb-4">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="font-bold">Company</label>
                            <div>{{ $exp['company'] ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="font-bold">Designation</label>
                            <div>{{ $exp['designation'] ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="font-bold">From</label>
                            <div>{{ $exp['from'] ?? '-' }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                        <div>
                            <label class="font-bold">To</label>
                            <div>{{ $exp['to'] ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="font-bold">Last Salary</label>
                            <div>{{ $exp['lastSalary'] ?? '-' }}</div>
                        </div>
                        <div>
                            <label class="font-bold">Responsibilities</label>
                            <div>{{ $exp['responsibilities'] ?? '-' }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
                @else
                <div>No experience details available</div>
                @endif
            </fieldset>
        </div>
    </div>
</div>
@endsection