@extends('layouts.master')
@section('title', 'Sales - Search')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6 mb-4">
    <h2 class="text-2xl font-semibold text-gray-800 mb-5">Search</h2>
    <form method="GET" action="{{ route('sales.search') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Search In</label>
            <select name="type" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="customer"  {{ ($type??'')==='customer'  ? 'selected':'' }}>Customer</option>
                <option value="vehicle"   {{ ($type??'')==='vehicle'   ? 'selected':'' }}>Vehicle</option>
                <option value="jobcard"   {{ ($type??'')==='jobcard'   ? 'selected':'' }}>Jobcard</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keyword</label>
            <input type="text" name="q" value="{{ $query ?? '' }}" placeholder="Name, Registration, CNIC…"
                   class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-72">
        </div>
        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-search mr-1"></i> Search
        </button>
    </form>
</div>

@if($results->count())
<div class="bg-white rounded-lg shadow-sm p-6">
    <h3 class="font-semibold text-gray-700 mb-4">Results
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $results->count() }}</span>
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                @if(($type??'') === 'customer')
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mobile</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">City</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Update</th>
                </tr>
                @elseif(($type??'') === 'vehicle')
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Frame No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Variant</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Colour</th>
                </tr>
                @else
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO No</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Open Date</th>
                </tr>
                @endif
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($results as $row)
                <tr class="hover:bg-gray-50 transition-colors">
                    @if(($type??'') === 'customer')
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $row->Customer_id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $row->Customer_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $row->mobile }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $row->City ?? '—' }}</td>
                        <td class="px-4 py-3 text-sm">
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">{{ $row->cust_type }}</span>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $row->Update_date ?? '—' }}</td>
                    @elseif(($type??'') === 'vehicle')
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $row->Vehicle_id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $row->Registration }}</td>
                        <td class="px-4 py-3 text-sm font-mono text-gray-600">{{ $row->Frame_no }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->Variant }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $row->Model }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $row->Colour }}</td>
                    @else
                        <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $row->Jobc_id }}</td>
                        <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $row->Veh_reg_no }}</td>
                        <td class="px-4 py-3 text-sm text-gray-700">{{ $row->Customer_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $row->SA }}</td>
                        <td class="px-4 py-3 text-sm">
                            @if($row->status=='0')<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Open</span>
                            @elseif($row->status=='1')<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">In Workshop</span>
                            @else<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Closed</span>@endif
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-400">{{ $row->Open_date_time }}</td>
                    @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@elseif(request()->has('q') && request('q'))
<div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-lg">No results found.</div>
@endif
@endsection
