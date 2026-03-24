@extends('layouts.master')
@section('title', 'Sales - Upload VINs')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif

<div class="bg-white rounded shadow-sm p-6 mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-5">Upload VIN / Frame Data</h2>
    <form method="POST" action="{{ route('sales.upload-vin.store') }}">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">List Name <span class="text-red-500">*</span></label>
                <input type="text" name="listname" required placeholder="e.g. Batch Jan 2025"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">VINs <span class="text-red-500">*</span></label>
                <p class="text-xs text-gray-400 mb-1">One VIN per line. Optionally add secondary info after a comma: <code>VIN123,Secondary Info</code></p>
                <textarea name="vins" rows="8" required placeholder="AAAAAAAA12345&#10;BBBBBBBB67890,Additional Info"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
        </div>
        <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-upload mr-2"></i> Upload VINs
        </button>
    </form>
</div>

<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Upload History
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $lists->count() }}</span>
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">List Name</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Uploaded By</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($lists as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $l->list_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $l->list_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $l->user }}</td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $l->upload_date }}</td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400">No uploads yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
