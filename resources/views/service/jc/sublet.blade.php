{{-- resources/views/service/jc/sublet.blade.php --}}
@extends('layouts.master')

@section('title', 'Job Controller - Sublet Requests')

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
        <h2 class="text-2xl font-semibold text-gray-800">Sublet Requests</h2>
        <span class="bg-purple-100 text-purple-800 text-xs font-medium px-3 py-1 rounded-full">{{ count($subletRequests) }} Pending</span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sublet</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jobcard#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assign Time</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($subletRequests as $sublet)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $sublet->Sublet }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 font-mono">#{{ $sublet->RO_no }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $sublet->Variant }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ \Carbon\Carbon::parse($sublet->entry_datetime)->format('d-M g:i A') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $sublet->qty }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $sublet->SA }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="flex space-x-2">
                            <a href="{{ route('jc.sublet-assign', $sublet->sublet_id) }}"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                                <i class="fas fa-tasks mr-1"></i> ASSIGN
                            </a>
                            <form action="{{ route('jc.sublet-done.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="sublet_id" value="{{ $sublet->sublet_id }}">
                                <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded-md transition-colors">
                                    <i class="fas fa-check mr-1"></i> DONE
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                        <p>No sublet requests found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
