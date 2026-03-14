@extends('layouts.master')
@section('title', 'SM - VIN Check')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 mb-4">
    <h2 class="text-xl font-semibold text-gray-800 mb-4">VIN Check</h2>
    <form method="GET" action="{{ route('sm.vin-check') }}" class="flex gap-2">
        <input type="text" name="vin" value="{{ $vin ?? '' }}" required placeholder="Enter VIN / Frame No..."
               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-72">
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors">
            <i class="fa fa-search mr-1"></i> Check
        </button>
    </form>
</div>
@if($result)
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="font-semibold text-gray-700 mb-3">Results for "{{ $vin }}"</h3>
    @if($result->isEmpty())
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">VIN not found in check list.</div>
    @else
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">VIN</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Added By</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($result as $r)
                <tr class="bg-emerald-50">
                    <td class="px-4 py-3 text-sm font-mono font-bold text-gray-800">{{ $r->VIN }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $r->details ?? '' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $r->added_by ?? '' }}</td>
                    <td class="px-4 py-3 text-sm text-gray-400">{{ $r->added_on ?? '' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endif
@endsection
