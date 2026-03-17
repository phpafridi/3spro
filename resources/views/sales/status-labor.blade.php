@extends('layouts.master')
@section('title', 'Sales - Labor Status')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-xl font-semibold text-gray-800">Labor Status — In Workshop</h2>
    <a href="{{ route('sales.index') }}" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"><i class="fa fa-home mr-1"></i>Dashboard</a>
</div>
@if($jobcards->isEmpty())
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>No jobcards in workshop.</div>
@else
    @foreach($jobcards as $jc)
    <div class="bg-white rounded-lg shadow-sm p-5 mb-4">
        <div class="flex items-center gap-4 mb-3 pb-3 border-b border-gray-100">
            <span class="font-bold text-gray-900">RO# {{ $jc->Jobc_id }}</span>
            <span class="text-gray-500 text-sm">SA: {{ $jc->SA }}</span>
            <span class="text-gray-400 text-sm">{{ \Carbon\Carbon::parse($jc->Open_date_time)->format('d/m/Y g:i A') }}</span>
        </div>
        @if(isset($laborData[$jc->Jobc_id]) && count($laborData[$jc->Jobc_id]))
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Team</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Bay</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Est. Time</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Assign Time</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr></thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($laborData[$jc->Jobc_id] as $l)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $l->Labor }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $l->type }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $l->team }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $l->bay }}</td>
                        <td class="px-4 py-2 text-sm text-gray-500">{{ $l->estimated_time }}</td>
                        <td class="px-4 py-2 text-sm text-gray-400">{{ $l->Assign_time }}</td>
                        <td class="px-4 py-2 text-sm">
                            @if(!$l->status)<span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Pending</span>
                            @elseif($l->status=='Job Assign')<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">Assigned</span>
                            @elseif($l->status=='Jobclose')<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Done</span>
                            @elseif($l->status=='Job Stopage')<span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full">Stopped</span>
                            @elseif($l->status=='Job Not Done')<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Not Done</span>
                            @else<span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $l->status }}</span>@endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
            <p class="text-gray-400 text-sm italic">No labor added yet.</p>
        @endif
    </div>
    @endforeach
@endif
@endsection
