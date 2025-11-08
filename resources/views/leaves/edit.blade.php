@extends('layouts.app')

@section('title', 'Edit Leave')
@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-100">✏️ Update Leave Request</h1>
    <a href="{{ route('leaves.index') }}"
        class="inline-flex items-center px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
        ← Back to Leave List
    </a>
</div>
@endsection

@section('content')
<div class="max-w-xl mx-auto p-6 bg-gray-900 border border-gray-800 rounded-lg shadow-md text-white">

    {{-- Show Validation Errors --}}
    @if ($errors->any())
    <div
        class="mb-4 p-4 bg-red-100 text-red-800 rounded border border-red-300 dark:bg-red-800 dark:text-red-100 dark:border-red-600">
        <strong>There were some problems:</strong>
        <ul class="mt-2 list-disc pl-5 text-sm">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('leaves.update', $leave->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        {{-- 📄 Leave Info Box --}}
        <div class="bg-gray-800 p-4 rounded-md text-sm text-gray-200 shadow-md space-y-1">
            <p><strong>🧑 Requested By:</strong> {{ $leave->user->name ?? 'N/A' }}</p>
            <p><strong>📅 Leave Type:</strong> {{ ucfirst($leave->leave_type) }}</p>
            <p><strong>🗓️ Dates:</strong> {{ $leave->start_date }} to {{ $leave->end_date }} ({{ $leave->total_days }}
                days)</p>
            <p><strong>📌 Current Status:</strong>
                <span class="capitalize inline-block px-2 py-1 rounded text-sm
                @if($leave->status === 'approved') bg-green-700 text-green-100
                @elseif($leave->status === 'rejected') bg-red-700 text-red-100
                @elseif($leave->status === 'cancelled') bg-yellow-700 text-yellow-100
                @else bg-gray-700 text-gray-100 @endif">
                    {{ $leave->status }}
                </span>
            </p>
        </div>

        {{-- 🔁 Update Status --}}
            <div>
                <label for="status" class="block mb-1 text-sm font-medium text-gray-300">
                    Update Status <span class="text-red-500">*</span>
                </label>
                <select name="status" id="status" required
                    class="w-full px-4 py-2 rounded bg-gray-800 border border-gray-700 text-white focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <option value="approved" {{ $leave->status == 'approved' ? 'selected' : '' }}>✅ Approve</option>
                    <option value="rejected" {{ $leave->status == 'rejected' ? 'selected' : '' }}>❌ Reject</option>
                    <option value="cancelled" {{ $leave->status == 'cancelled' ? 'selected' : '' }}>🚫 Cancel</option>
                </select>
            </div>

        {{-- ✅ Submit --}}
        <div class="flex justify-end">
            <button type="submit"
                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded shadow transition">
                💾 Update Leave
            </button>
        </div>
    </form>

</div>
@endsection