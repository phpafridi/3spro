@extends('layouts.master')
@section('title', 'SM - Labor History')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 mb-4">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">Labor History</h2>
    <form method="GET" action="{{ route('sm.history') }}" class="flex gap-2">
        <input type="text" name="job_id" value="{{ $jobId ?? '' }}" required placeholder="Enter RO No..."
               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
            <i class="fa fa-search mr-1"></i> Load History
        </button>
    </form>
</div>
@if($labors->isNotEmpty())
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="font-semibold text-gray-700 mb-3">Labor History — RO# {{ $jobId }}
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $labors->count() }}</span>
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bay</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Entry</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">End</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->Labor_id }}</td>
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $l->Labor }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->type }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($l->cost,0) }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->status ?: 'Pending' }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->team }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->bay }}</td>
                    <td class="px-4 py-2 text-sm text-gray-400">{{ $l->entry_time }}</td>
                    <td class="px-4 py-2 text-sm text-gray-400">{{ $l->end_time }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif($jobId ?? false)
<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">No labor found for RO# {{ $jobId }}.</div>
@endif
@endsection
