{{-- resources/views/service/jc/inprogress.blade.php --}}
@extends('layouts.master')

@section('title', 'Job Controller - In Progress Jobs')

@section('sidebar-menu')
    <a href="{{ route('jc.dashboard') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-wrench w-6"></i>
        <span>Jobs Requests</span>
    </a>
    <a href="{{ route('jc.sublet') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.sublet') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-sign-out-alt w-6"></i>
        <span>Sublet Requests</span>
    </a>
    <a href="{{ route('jc.inprogress') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.inprogress') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-edit w-6"></i>
        <span>Inprogress Jobs</span>
    </a>
    <a href="{{ route('jc.parts-status') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.parts-status') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-search-plus w-6"></i>
        <span>Parts Status</span>
    </a>
@endsection

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">In-Progress Jobs</h2>
        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-3 py-1 rounded-full">{{ count($inprogressJobs) }} Active</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Labor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jobcard#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reg#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assign Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Team</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bay</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($inprogressJobs as $job)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $job->Labor }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">#{{ $job->RO_no }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($job->Assign_time)->format('d-M g:i A') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $job->team }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $job->bay }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $job->SA }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <form action="{{ route('jc.job-done') }}" method="POST">
                            @csrf
                            <input type="hidden" name="Labor_id" value="{{ $job->Labor_id }}">
                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors">
                                <i class="fas fa-check mr-1"></i> JOB DONE
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-clock text-4xl mb-3 text-gray-300"></i>
                        <p>No in-progress jobs found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
