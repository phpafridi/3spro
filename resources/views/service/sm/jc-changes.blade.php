@extends('layouts.master')
@section('title', 'SM - JC Changes')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6 mb-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">JC Changes Viewer</h2>
    <form method="GET" action="{{ route('sm.jc-changes') }}" class="flex gap-2">
        <input type="text" name="jobc_id" value="{{ $jobId ?? '' }}" required placeholder="RO No..."
               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
            <i class="fa fa-search mr-1"></i> View Changes
        </button>
    </form>
</div>
@if($changes->isNotEmpty())
<div class="bg-white rounded shadow-sm p-6">
    <h3 class="font-semibold text-gray-700 mb-4">Labor for RO# {{ $jobId }}</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Additional</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entry</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($changes as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->Labor_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $c->Labor }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->type }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($c->cost,0) }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $c->status ?: 'Pending' }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($c->Additional)<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">Yes</span>
                        @else<span class="px-2 py-0.5 bg-gray-100 text-gray-500 text-xs rounded-full">No</span>@endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $c->entry_time }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif($jobId ?? false)
<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded">No data for RO# {{ $jobId }}.</div>
@endif
@endsection
