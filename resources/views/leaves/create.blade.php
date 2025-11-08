@extends('layouts.app')
@section('title', 'Create Leave')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-100">🌿 Apply for Leave</h1>

    <a href="{{ route('leaves.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Leave List
    </a>
</div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-gray-900 border border-gray-800 rounded-lg shadow-md text-white">

    {{-- Show Validation Errors --}}
    @if ($errors->any())
    <div
        class="mb-4 p-4 bg-red-100 text-red-800 rounded border border-red-300 dark:bg-red-800 dark:text-red-100 dark:border-red-600">
        <strong>There were some problems with your input:</strong>
        <ul class="mt-2 list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST" action="{{ route('leaves.store') }}" class="space-y-6">
        @csrf
        {{-- Grid layout for Leave Type & Start Date --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Admin/HR: Select User --}}
            @can('create leave requests for others')
            <div>
                <label for="user_id" class="block mb-1 text-sm font-medium text-gray-300">
                    Select User <span class="text-red-500">*</span>
                </label>
                <select name="user_id" id="user_id" required
                    class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Select User --</option>
                    @foreach($employees as $employee)
                    <option value="{{ $employee->id }}" {{ old('user_id') == $employee->id ? 'selected' : '' }}>
                        {{ $employee->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @else
            {{-- Employee: show name (readonly) + hidden input --}}
            <div>
                <label class="block mb-1 text-sm font-medium text-gray-300">User</label>
                <input type="text" value="{{ auth()->user()->name }}" disabled
                    class="w-full px-4 py-2 rounded bg-gray-700 border border-gray-600 text-gray-400 cursor-not-allowed">
                <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            </div>
            @endcan


            {{-- Leave Type --}}
            <div>
                <label for="leave_type" class="block mb-1 text-sm font-medium text-gray-300">Leave Type <span
                        class="text-red-500">*</span></label>
                <select name="leave_type" id="leave_type" required
                    class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="">-- Select Leave Type --</option>
                    <option value="sick" {{ old('leave_type') == 'sick' ? 'selected' : '' }}>Sick</option>
                    <option value="casual" {{ old('leave_type') == 'casual' ? 'selected' : '' }}>Casual</option>
                    <option value="paid" {{ old('leave_type') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="unpaid" {{ old('leave_type') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                </select>
            </div>

        </div>

        {{-- Grid layout for End Date & Reason --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            {{-- Start Date --}}
            <div>
                <label for="start_date" class="block mb-1 text-sm font-medium text-gray-300">Start Date <span
                        class="text-red-500">*</span></label>
                <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}"
                    class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none text-white"
                    required>
            </div>
            {{-- End Date --}}
            <div>
                <label for="end_date" class="block mb-1 text-sm font-medium text-gray-300">End Date <span
                        class="text-red-500">*</span></label>
                <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}"
                    class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none text-white"
                    required>
            </div>

        </div>
        {{-- Reason --}}
        <div>
            <label for="reason" class="block mb-1 text-sm font-medium text-gray-300">Reason (optional)</label>
            <textarea name="reason" id="reason" rows="3"
                class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 focus:ring-2 focus:ring-blue-500 focus:outline-none text-white"
                placeholder="Mention reason for leave...">{{ old('reason') }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition">
                🚀 Submit Leave Request
            </button>
        </div>
    </form>

</div>
@endsection