@extends('layouts.master')
@section('title', 'SM - Problem Box')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Stopped / Not Done Jobs
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-sm rounded-full">{{ $problems->count() }}</span>
        </h2>
    </div>
    @if($problems->isEmpty())
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded"><i class="fa fa-check-circle mr-2"></i>No problems reported.</div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entry</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($problems as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $p->RO_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $p->Labor }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($p->status=='Job Stopage')
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">Stopped</span>
                        @else
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Not Done</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $p->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $p->Variant }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $p->SA }}</td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $p->entry_time }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('sm.problem-box') }}" class="flex gap-2 items-center">
                            @csrf
                            <input type="hidden" name="labor_id" value="{{ $p->Labor_id }}">
                            <select name="action" class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none">
                                <option value="Job Assign">Re-Assign</option>
                                <option value="Jobclose">Close Job</option>
                                <option value="Job Not Done">Not Done</option>
                            </select>
                            <button type="submit" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                <i class="fa fa-check mr-1"></i>Apply
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
