@extends('layouts.master')
@section('title', 'SM - Reports')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 mb-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Reports</h2>
    <form method="GET" action="{{ route('sm.reports') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
            <input type="date" name="from_date" value="{{ $fromDate }}" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
            <input type="date" name="to_date" value="{{ $toDate }}" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Report</label>
            <select name="tab" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="summary"  {{ $tab=='summary'  ? 'selected' : '' }}>Summary by SA</option>
                <option value="labor"    {{ $tab=='labor'    ? 'selected' : '' }}>Labor Analysis</option>
                <option value="parts"    {{ $tab=='parts'    ? 'selected' : '' }}>Parts Usage</option>
                <option value="sa"       {{ $tab=='sa'       ? 'selected' : '' }}>SA Performance</option>
            </select>
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-bar-chart mr-2"></i> Generate
        </button>
    </form>
</div>

@if($data->isNotEmpty())
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="font-semibold text-gray-700 mb-4">{{ ucfirst($tab) }} Report — {{ $fromDate }} to {{ $toDate }}</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @if($tab=='summary')
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total ROs</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Closed ROs</th>
                    @elseif($tab=='labor')
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Count</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Revenue</th>
                    @elseif($tab=='parts')
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Part Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Qty</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Value</th>
                    @elseif($tab=='sa')
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total ROs</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Regular</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Campaign</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Warranty</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($data as $row)
                <tr class="hover:bg-gray-50">
                    @if($tab=='summary')
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $row->SA }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->total }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->closed }}</td>
                    @elseif($tab=='labor')
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $row->Labor }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $row->type }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->count }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($row->total,0) }}</td>
                    @elseif($tab=='parts')
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $row->part_description }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->total_qty }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ number_format($row->total_value,0) }}</td>
                    @elseif($tab=='sa')
                        <td class="px-4 py-3 text-sm font-medium text-gray-800">{{ $row->SA }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->total_ros }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->regular }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->campaign }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->warranty }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif(request()->has('tab'))
<div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-lg">No data found for the selected period.</div>
@endif
@endsection
