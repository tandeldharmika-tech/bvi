@extends('layouts.app')
@section('title', 'Leave Details')

@section('header')
    <div class="flex items-center justify-between mb-0">
        <h1 class="font-bold text-gray-100">📄 Leave Request Details</h1>
    </div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto p-6 bg-gray-900 border border-gray-800 rounded-lg shadow-md text-white">

    {{-- Leave Information --}}
    <div class="space-y-4 text-sm md:text-base">

        <div class="flex justify-between">
            <span class="text-gray-400">👤 Employee:</span>
            <span class="text-gray-100 font-medium">{{ $leave->user->name ?? 'N/A' }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">🗂️ Leave Type:</span>
            <span class="capitalize text-gray-100 font-medium">{{ $leave->leave_type }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">📅 From:</span>
            <span class="text-gray-100">{{ \Carbon\Carbon::parse($leave->start_date)->format('d M Y') }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">📅 To:</span>
            <span class="text-gray-100">{{ \Carbon\Carbon::parse($leave->end_date)->format('d M Y') }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-400">📆 Total Days:</span>
            <span class="text-gray-100">{{ $leave->total_days }}</span>
        </div>

        <div>
            <span class="text-gray-400 block">📝 Reason:</span>
            <p class="text-gray-100 mt-1 bg-gray-800 px-3 py-2 rounded">{{ $leave->reason ?: 'No reason provided.' }}</p>
        </div>

        <div class="flex justify-between items-center">
            <span class="text-gray-400">📌 Status:</span>
            <span>
                @switch($leave->status)
                    @case('approved')
                        <span class="px-3 py-1 bg-green-600 text-white text-sm rounded-full">Approved</span>
                        @break
                    @case('pending')
                        <span class="px-3 py-1 bg-yellow-600 text-white text-sm rounded-full">Pending</span>
                        @break
                    @case('rejected')
                        <span class="px-3 py-1 bg-red-600 text-white text-sm rounded-full">Rejected</span>
                        @break
                    @default
                        <span class="px-3 py-1 bg-gray-600 text-white text-sm rounded-full">{{ ucfirst($leave->status) }}</span>
                @endswitch
            </span>
        </div>
    </div>

    {{-- Back Button --}}
    <div class="mt-6">
        <a href="{{ route('leaves.index') }}"
           class="inline-flex items-center px-5 py-2 bg-gray-700 hover:bg-gray-600 text-white text-sm font-medium rounded shadow transition">
            ← Back to Leaves
        </a>
    </div>
</div>
@endsection
