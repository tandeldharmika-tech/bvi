@extends('layouts.app')
@section('title', 'Create User')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">📝 Create User</h1>

    <a href="{{ route('users.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Users
    </a>
</div>
@endsection

@section('content')

@if ($errors->any())
<div
    class="mb-4 p-4 bg-red-100 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white border-red-300 text-red-800 rounded">
    <ul class="list-disc list-inside">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div x-data="{ tab: 'personal' }">
    {{-- Bootstrap Nav Tabs --}}
    <ul class="flex flex-wrap gap-2 mb-2 border-bottom">
        <li>
            <a href="#" @click.prevent="tab = 'personal'"
                :class="tab === 'personal' ? 'bg-light text-dark' : 'bg-gray-600 text-white hover:bg-gray-700'"
                class="px-4 py-2 rounded shadow transition-all duration-200 block">
                Personal Info
            </a>
        </li>
        <li>
            <a href="#" @click.prevent="tab = 'contact'"
                :class="tab === 'contact' ? 'bg-light text-dark' : 'bg-gray-600 text-white hover:bg-gray-700'"
                class="px-4 py-2 rounded shadow transition-all duration-200 block">
                Contact Info
            </a>
        </li>
        <li>
            <a href="#" @click.prevent="tab = 'official'"
                :class="tab === 'official' ? 'bg-light text-dark' : 'bg-gray-600 text-white hover:bg-gray-700'"
                class="px-4 py-2 rounded shadow transition-all duration-200 block">
                Official Info
            </a>
        </li>
        <li>
            <a href="#" @click.prevent="tab = 'payroll'"
                :class="tab === 'payroll' ? 'bg-light text-dark' : 'bg-gray-600 text-white hover:bg-gray-700'"
                class="px-4 py-2 rounded shadow transition-all duration-200 block">
                Payroll Info
            </a>
        </li>
        <li>
            <a href="#" @click.prevent="tab = 'education'"
                :class="tab === 'education' ? 'bg-light text-dark' : 'bg-gray-600 text-white hover:bg-gray-700'"
                class="px-4 py-2 rounded shadow transition-all duration-200 block">
                Education
            </a>
        </li>
        <li>
            <a href="#" @click.prevent="tab = 'experience'"
                :class="tab === 'experience' ? 'bg-light text-dark' : 'bg-gray-600 text-white hover:bg-gray-700'"
                class="px-4 py-2 rounded shadow transition-all duration-200 block">
                Experience
            </a>
        </li>
    </ul>

    <form action="{{ route('users.store') }}" method="POST"  enctype="multipart/form-data" class="mx-auto space-y-6 text-light">
        @csrf

        {{-- Basic User Info --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-1">
            {{-- First Name --}}
            <div>
                <label for="first_name" class="font-bold">First Name <span class="text-red-600">*</span></label>
                <input type="text" name="first_name" id="first_name" required
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
                    value="{{ old('first_name') }}">
            </div>

            {{-- Last Name --}}
            <div>
                <label for="last_name" class="font-bold">Last Name <span class="text-red-600">*</span></label>
                <input type="text" name="last_name" id="last_name" required
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
                    value="{{ old('last_name') }}">
            </div>

            {{-- Email (auto-generated) --}}
            <div>
                <label for="email" class="font-bold">Email <span class="text-red-600">*</span></label>
                <input type="email" name="email" id="email" readonly required
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
                    value="{{ old('email') }}">
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="font-bold">Password <span class="text-red-600">*</span></label>
                <input type="password" name="password" id="password" required
                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
            </div>
        </div>

        {{-- Personal Info --}}
        <div x-show="tab === 'personal'">
            <fieldset class="border p-3 rounded">
                <legend class="font-bold">Personal Information</legend>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label>Date of Birth</label>
                        <input type="date" name="personal[dob]" value="{{ old('personal.dob') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Gender</label>
                        <select name="personal[gender]"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                            <option value="">-- Select Gender --</option>
                            <option value="Male" @selected(old('personal.gender')=='Male' )>Male</option>
                            <option value="Female" @selected(old('personal.gender')=='Female' )>Female</option>
                            <option value="Other" @selected(old('personal.gender')=='Other' )>Other</option>
                        </select>
                    </div>
                    <div>
                        <label>Marital Status</label>
                        <select name="personal[maritalStatus]"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                            <option value="">-- Select Marital Status --</option>
                            <option value="Single" @selected(old('personal.maritalStatus')=='Single' )>Single</option>
                            <option value="Married" @selected(old('personal.maritalStatus')=='Married' )>Married
                            </option>
                            <option value="Other" @selected(old('personal.maritalStatus')=='Other' )>Other</option>
                        </select>
                    </div>
                    <div>
                        <label>Blood Group</label>
                        <input type="text" name="personal[bloodGroup]" value="{{ old('personal.bloodGroup') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>

                    <div>
                        <label>Nationality</label>
                        <input type="text" name="personal[nationality]" value="{{ old('personal.nationality') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label for="profile_photo" class="block font-medium text-gray-700 dark:text-gray-200">
                            Profile Photo
                        </label>
                        <input type="file" id="profile_photo" name="profile_photo" accept="image/*"
                            class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2 focus:ring focus:ring-indigo-500 focus:border-indigo-500">

                        {{-- Live Preview --}}
                        <div class="mt-3 flex justify-start">
                            <img id="photoPreview"
                                src="{{ old('profile_photo') ? asset('storage/' . old('profile_photo')) : 'https://via.placeholder.com/100x100?text=Preview' }}"
                                alt="Preview"
                                class="h-24 w-24 rounded-full object-cover border border-gray-300 dark:border-gray-600">
                        </div>
                    </div>
                </div>
            </fieldset>
        </div>

        {{-- Contact Info --}}
        <div x-show="tab === 'contact'">
            <fieldset class="border p-3 rounded">
                <legend class="font-bold">Contact Information</legend>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label>Phone</label>
                        <input type="text" name="contact[phone]" value="{{ old('contact.phone') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Alternate Phone</label>
                        <input type="text" name="contact[altPhone]" value="{{ old('contact.altPhone') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>

                    <div>
                        <label>Address</label>
                        <textarea name="contact[address]"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">{{ old('contact.address') }}</textarea>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label>City</label>
                        <input type="text" name="contact[city]" value="{{ old('contact.city') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>State</label>
                        <input type="text" name="contact[state]" value="{{ old('contact.state') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Pincode</label>
                        <input type="text" name="contact[pincode]" value="{{ old('contact.pincode') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                </div>
            </fieldset>
        </div>

        {{-- Official Info --}}
        <div x-show="tab === 'official'">
            <fieldset class="border p-3 rounded">
                <legend class="font-bold">Official Information</legend>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label>Employee Code</label>
                        <input type="text" name="official[employeeCode]" value="{{ $employeeCode }}" readonly
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Department</label>
                        <select id="departmentSelect" name="official[department]"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                            <option value="">-- Select Department --</option>
                            @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Designation</label>
                        <select id="designationSelect" name="official[designation]"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                            <option value="">-- Select Designation --</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label>Joining Date</label>
                        <input type="date" name="official[joiningDate]" value="{{ old('official.joiningDate') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Location</label>
                        <input type="text" name="official[location]" value="{{ old('official.location') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Employment Type</label>
                        <select name="official[employmentType]"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                            <option value="">-- Select Employment Type --</option>
                            <option value="permanent" @selected(old('official.employmentType')=='permanent' )>Permanent
                            </option>
                            <option value="contract" @selected(old('official.employmentType')=='contract' )>Contract
                            </option>
                            <option value="intern" @selected(old('official.employmentType')=='intern' )>Intern</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label>Role</label>
                        <select name="official[role]"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                            <option value="">-- Select Role --</option>
                            @foreach ($roles as $role)
                            <option value="{{ $role->name }}" class="uppercase">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Parent User</label>
                        <select id="parentUserSelect" name="parent_id" placeholder="Type to search..." class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                            <option value="">-- Select Parent User --</option>
                            @foreach ($parentUsers as $pUser)
                                <option value="{{ $pUser->id }}">{{ $pUser->name }} ({{ $pUser->roles->pluck('name')->join(', ') }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Status</label>
                        <select name="official[status]"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                            <option value="">-- Select Status --</option>
                            <option value="active" @selected(old('official.status')=='active' )>Active</option>
                            <option value="inactive" @selected(old('official.status')=='inactive' )>Inactive</option>
                            <option value="suspended" @selected(old('official.status')=='suspended' )>Suspended</option>
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <label>Last Working Date</label>
                    <input type="date" name="official[lastWorkingDate]" value="{{ old('official.lastWorkingDate') }}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
            </fieldset>
        </div>

        {{-- Payroll Info --}}
        <div x-show="tab === 'payroll'">
            <fieldset class="border p-3 rounded">
                <legend class="font-bold">Payroll Information</legend>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label>Bank Name</label>
                        <input type="text" name="payroll[bankName]" value="{{ old('payroll.bankName') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Account Number</label>
                        <input type="text" name="payroll[accountNumber]" value="{{ old('payroll.accountNumber') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>IFSC</label>
                        <input type="text" name="payroll[ifsc]" value="{{ old('payroll.ifsc') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                    <div>
                        <label>PAN</label>
                        <input type="text" name="payroll[pan]" value="{{ old('payroll.pan') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                    <div>
                        <label>Salary</label>
                        <input type="number" name="payroll[salary]" value="{{ old('payroll.salary') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
                            step="0.01">
                    </div>
                    <div>
                        <label>Last Salary Update</label>
                        <input type="date" name="payroll[lastSalaryUpdate]"
                            value="{{ old('payroll.lastSalaryUpdate') }}"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                    </div>
                </div>

                <div class="mt-4">
                    <label>Cost Center</label>
                    <input type="text" name="payroll[costCenter]" value="{{ old('payroll.costCenter') }}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
            </fieldset>
        </div>

        {{-- Education Section --}}
        <div x-show="tab === 'education'">
            <fieldset class="border p-3 rounded mt-6">
                <legend class="font-bold">Education</legend>

                <div id="education-container" class="space-y-4">
                    <!-- Education items will be appended here -->
                </div>

                <button type="button" id="add-education-btn"
                    class="mt-3 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                    + Add Education
                </button>
            </fieldset>
        </div>

        {{-- Experience Section --}}
        <div x-show="tab === 'experience'">
            <fieldset class="border p-3 rounded mt-6">
                <legend class="font-bold">Experience</legend>

                <div id="experience-container" class="space-y-4">
                    <!-- Experience items will be appended here -->
                </div>

                <button type="button" id="add-experience-btn"
                    class="mt-3 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Add Experience
                </button>
            </fieldset>

            <button type="submit" class="px-6 py-2 bg-success text-white rounded hover:bg-indigo-700 mt-2">Create
                User</button>
        </div>
    </form>
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
    <script>
    // Education template
    function getEducationTemplate(index, data = {}) {
        return `
        <div class="border p-4 rounded relative bg-gray-50 dark:bg-gray-800">
            <button type="button" onclick="removeExperience(this)"
                style="position:absolute; top:8px; right:8px; color:#dc2626; font-weight:bold; cursor:pointer;">
                &times;
            </button>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="font-bold">Degree</label>
                    <input type="text" name="education[${index}][degree]" value="${data.degree || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
                <div>
                    <label class="font-bold">Specialization</label>
                    <input type="text" name="education[${index}][specialization]" value="${data.specialization || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
                <div>
                    <label class="font-bold">Institution</label>
                    <input type="text" name="education[${index}][institution]" value="${data.institution || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="font-bold">Year</label>
                    <input type="number" name="education[${index}][year]" value="${data.year || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2" min="1900" max="2100">
                </div>
                <div>
                    <label class="font-bold">Grade</label>
                    <input type="text" name="education[${index}][grade]" value="${data.grade || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
                <div>
                    <label class="font-bold">Document (Image/PDF)</label>
                    <input type="file" name="education[${index}][doc]" 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2"
                        accept=".jpg,.jpeg,.png,.pdf">
                </div>
            </div>
        </div>
        `;
    }

    // Experience template
    function getExperienceTemplate(index, data = {}) {
        return `
        <div class="border p-4 rounded relative bg-gray-50 dark:bg-gray-800">
           <button type="button" onclick="removeExperience(this)"
            style="position:absolute; top:8px; right:8px; color:#dc2626; font-weight:bold; cursor:pointer;">
            &times;
        </button>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="font-bold">Company</label>
                    <input type="text" name="experience[${index}][company]" value="${data.company || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
                <div>
                    <label class="font-bold">Designation</label>
                    <input type="text" name="experience[${index}][designation]" value="${data.designation || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
                <div>
                    <label class="font-bold">From</label>
                    <input type="date" name="experience[${index}][from]" value="${data.from || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="font-bold">To</label>
                    <input type="date" name="experience[${index}][to]" value="${data.to || ''}"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
                <div>
                    <label class="font-bold">Last Salary</label>
                    <input type="number" name="experience[${index}][lastSalary]" value="${data.lastSalary || ''}" step="0.01"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">
                </div>
                <div>
                    <label class="font-bold">Responsibilities</label>
                    <textarea name="experience[${index}][responsibilities]" rows="2"
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded px-3 py-2">${data.responsibilities || ''}</textarea>
                </div>
            </div>
        </div>
        `;
    }

    let educationCount = 0;
    let experienceCount = 0;

    document.getElementById('add-education-btn').addEventListener('click', () => {
        const container = document.getElementById('education-container');
        container.insertAdjacentHTML('beforeend', getEducationTemplate(educationCount));
        educationCount++;
    });

    document.getElementById('add-experience-btn').addEventListener('click', () => {
        const container = document.getElementById('experience-container');
        container.insertAdjacentHTML('beforeend', getExperienceTemplate(experienceCount));
        experienceCount++;
    });

    function removeEducation(button) {
        button.closest('div.border').remove();
    }

    function removeExperience(button) {
        button.closest('div.border').remove();
    }

    // Optionally, add one empty section on page load
    window.onload = () => {
        document.getElementById('add-education-btn').click();
        document.getElementById('add-experience-btn').click();
    };
    </script>
    <script>
        new TomSelect("#parentUserSelect", {
            create: false,        // don't allow new entries
            sortField: { field: "text", direction: "asc" },
            placeholder: "Type to search parent user..."
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const firstName = document.getElementById('first_name');
        const lastName = document.getElementById('last_name');
        const email = document.getElementById('email');

        function generateEmail() {
            const f = firstName.value.trim().toLowerCase().replace(/\s+/g, '');
            const l = lastName.value.trim().toLowerCase().replace(/\s+/g, '');
            if (f && l) {
                email.value = `${f}.${l}@bvinfy.com`;
            } else {
                email.value = '';
            }
        }

        firstName.addEventListener('input', generateEmail);
        lastName.addEventListener('input', generateEmail);
    });
    </script>

    <script>
    document.getElementById('departmentSelect').addEventListener('change', function() {
        const departmentId = this.value;
        const designationSelect = document.getElementById('designationSelect');
        designationSelect.innerHTML = '<option value="">Loading...</option>';

        if (departmentId) {
            fetch(`/departments/${departmentId}/designations`)
                .then(res => res.json())
                .then(data => {
                    designationSelect.innerHTML = '<option value="">-- Select Designation --</option>';
                    data.forEach(item => {
                        designationSelect.innerHTML +=
                            `<option value="${item.id}">${item.title}</option>`;
                    });
                });
        } else {
            designationSelect.innerHTML = '<option value="">-- Select Designation --</option>';
        }
    });
    </script>

<script>
document.getElementById('profile_photo')?.addEventListener('change', function (event) {
    const [file] = event.target.files;
    if (file) {
        document.getElementById('photoPreview').src = URL.createObjectURL(file);
    }
});
</script>
@endsection