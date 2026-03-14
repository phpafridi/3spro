@extends('layouts.master')
@section('title', 'BP - Search')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 mb-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Search Jobcard</h2>
    <form method="GET" action="{{ route('bp-jc.search') }}">
        <div class="flex gap-2 max-w-lg">
            <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Search by Registration, Customer, or RO No..."
                   class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-search mr-1"></i> Search
            </button>
        </div>
    </form>
</div>

@if(isset($results) && $results->isNotEmpty())
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="font-semibold text-gray-700 mb-4">Results
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $results->count() }}</span>
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Open Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($results as $r)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $r->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $r->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $r->Variant }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $r->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $r->mobile }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $r->SA }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($r->status=='0')<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Open</span>
                        @elseif($r->status=='1')<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">In Workshop</span>
                        @else<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Closed</span>@endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($r->Open_date_time)->format('d/m/Y g:i A') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('bp-jc.additional', $r->Jobc_id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                            <i class="fa fa-edit mr-1"></i> Manage
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif(isset($query) && $query)
<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">No results found for "{{ $query }}".</div>
@endif
@endsection
