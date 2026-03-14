@extends('layouts.master')
@section('title', 'Body & Paint - Pending Jobs')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('bp-jc.index') }}" class="px-3 py-2 bg-blue-600 text-white text-sm rounded transition-colors"><i class="fa fa-clock-o mr-1"></i>Pending</a>
    <a href="{{ route('bp-jc.inprogress') }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded transition-colors"><i class="fa fa-spinner mr-1"></i>In Progress</a>
    <a href="{{ route('bp-jc.sublet') }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded transition-colors"><i class="fa fa-external-link mr-1"></i>Sublets</a>
    <a href="{{ route('bp-jc.unclosed') }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded transition-colors"><i class="fa fa-list mr-1"></i>Unclosed JC</a>
    <a href="{{ route('bp-jc.search') }}" class="px-3 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded transition-colors"><i class="fa fa-search mr-1"></i>Search</a>
</div>
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Pending Job Requests
            <span class="ml-2 px-2 py-0.5 bg-yellow-100 text-yellow-700 text-sm rounded-full">{{ $pendingJobs->count() }}</span>
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor / Job</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entry Time</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pendingJobs as $i => $job)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $job->RO_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Labor }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Variant }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->SA }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->entry_time }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('bp-jc.assign', $job->Labor_id) }}" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors">
                            <i class="fa fa-check mr-1"></i> Assign Job
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-green-600"><i class="fa fa-check-circle mr-1"></i>No pending jobs.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
