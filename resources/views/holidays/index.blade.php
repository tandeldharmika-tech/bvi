@extends('layouts.app')

@section('title', 'Holidays')

@section('header')
<div class="flex items-center justify-between mb-0">
    <h1 class="font-bold text-gray-900 dark:text-gray-100">📅 Holidays</h1>

    @can('create holiday')
    <a href="{{ route('holidays.create') }}"
        class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded shadow transition">
        ➕ Add Holiday
    </a>
    @endcan
</div>
@endsection

@section('content')

{{-- ✅ Success Message --}}
@if(session('success'))
<div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded dark:bg-green-700 dark:text-green-100">
    {{ session('success') }}
</div>
@endif

{{-- 📋 Holidays Table --}}
<div class="overflow-x-auto border dark:bg-gray-800 shadow rounded-lg">
    <table class="min-w-full text-left text-sm font-light text-gray-700 dark:text-gray-300">
        <thead
            class="border-b bg-gray-50 dark:bg-gray-700 font-bold text-gray-900 dark:text-gray-100 uppercase tracking-wide">
            <tr>
                <th class="px-6 py-3 w-12">#</th>
                <th class="px-6 py-3">Holiday Name</th>
                <th class="px-6 py-3">Date</th>
                <th class="px-6 py-3">Type</th>
                <th class="px-6 py-3">Recurring</th>
                <th class="px-6 py-3 text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($holidays as $index => $holiday)
            <tr class="border-b hover:bg-gray-100 dark:hover:bg-gray-700 transition duration-150">
                <td class="px-6 py-4">{{ $holidays->firstItem() + $index }}</td>
                <td class="px-6 py-4 font-medium">{{ $holiday->title }}</td>
                <td class="px-6 py-4">
                    {{ \Carbon\Carbon::parse($holiday->date)->format('d M Y') }}
                </td>
                <td class="px-6 py-4">
                    {{ $holiday->type ?? '—' }}
                </td>
                <td class="px-6 py-4 text-center">
                    @if($holiday->is_recurring)
                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs font-bold dark:bg-green-700 dark:text-green-100">
                            Yes
                        </span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs font-bold dark:bg-gray-600 dark:text-gray-200">
                            No
                        </span>
                    @endif
                </td>
                <td class="px-6 py-4 text-center space-x-2">
                    @can('edit holiday')
                    <a href="{{ route('holidays.edit', $holiday->id) }}"
                        class="text-yellow-600 hover:underline dark:text-yellow-400 text-sm">
                        Edit
                    </a>
                    @endcan

                    @can('delete holiday')
                    <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST"
                        class="inline-block"
                        onsubmit="return confirm('Are you sure you want to delete this holiday?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline dark:text-red-400 text-sm">
                            Delete
                        </button>
                    </form>
                    @endcan
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">
                    No holidays found.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- 📄 Pagination --}}
<div class="mt-6">
    {{ $holidays->links() }}
</div>

@endsection
