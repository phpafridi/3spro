@extends('layouts.master')
@section('title', 'Service Manager Dashboard')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
{{-- Alert Counters --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-red-500">
        <div class="text-3xl font-bold text-red-500">{{ $alertCount }}</div>
        <div class="text-sm text-gray-500 mt-1">Jobs Open &gt; 24 hrs</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-yellow-500">
        <div class="text-3xl font-bold text-yellow-500">{{ $unclosedJobs->count() }}</div>
        <div class="text-sm text-gray-500 mt-1">Total Unclosed ROs</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-blue-500">
        <div class="text-3xl font-bold text-blue-500">{{ $unclosedJobs->where('status','1')->count() }}</div>
        <div class="text-sm text-gray-500 mt-1">In Workshop</div>
    </div>
    <div class="bg-white rounded-lg shadow-sm p-5 border-l-4 border-green-500">
        <div class="text-3xl font-bold text-green-500">{{ $unclosedJobs->where('status','0')->count() }}</div>
        <div class="text-sm text-gray-500 mt-1">Open / Pending</div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('sm.unclosed-ros') }}"      class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded transition-colors"><i class="fa fa-list mr-1"></i>Unclosed ROs</a>
        <a href="{{ route('sm.status-labor') }}"      class="px-3 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm rounded transition-colors"><i class="fa fa-wrench mr-1"></i>Labor Status</a>
        <a href="{{ route('sm.status-parts') }}"      class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded transition-colors"><i class="fa fa-cogs mr-1"></i>Parts Status</a>
        <a href="{{ route('sm.status-sublet') }}"     class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"><i class="fa fa-external-link mr-1"></i>Sublet Status</a>
        <a href="{{ route('sm.status-consumable') }}" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"><i class="fa fa-tint mr-1"></i>Consumable</a>
        <a href="{{ route('sm.search') }}"            class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors"><i class="fa fa-search mr-1"></i>Search</a>
        <a href="{{ route('sm.reports') }}"           class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded transition-colors"><i class="fa fa-bar-chart mr-1"></i>Reports</a>
        <a href="{{ route('sm.problem-box') }}"       class="px-3 py-2 bg-red-700 hover:bg-red-800 text-white text-sm rounded transition-colors"><i class="fa fa-exclamation-triangle mr-1"></i>Problem Box</a>
    </div>
</div>

{{-- Unclosed ROs Table --}}
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Unclosed Job Cards
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $unclosedJobs->count() }}</span>
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="dashTable">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Open Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($unclosedJobs as $j)
                @php $hours = \Carbon\Carbon::parse($j->Open_date_time)->diffInHours(now()); @endphp
                <tr class="hover:bg-gray-50 {{ $hours > 48 ? 'bg-red-50' : ($hours > 24 ? 'bg-yellow-50' : '') }}">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $j->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $j->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $j->Variant }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $j->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $j->SA }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $j->RO_type }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($j->Open_date_time)->format('d/m/Y g:i A') }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($hours > 24)
                            <span class="px-2 py-0.5 bg-red-100 text-red-700 text-xs rounded-full font-medium">{{ $hours }}h</span>
                        @else
                            <span class="text-gray-500">{{ $hours }}h</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($j->status=='0')
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Open</span>
                        @else
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">In Workshop</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
