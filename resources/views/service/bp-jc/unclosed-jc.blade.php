@extends('layouts.master')
@section('title', 'BP - Unclosed Jobcards')
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Unclosed Jobcards
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $unclosedJobs->count() }}</span>
        </h2>
    </div>
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
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">RO Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Open Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($unclosedJobs as $j)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $j->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $j->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $j->Variant }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $j->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $j->mobile }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $j->SA }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $j->RO_type }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($j->status=='0')<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Open</span>
                        @else<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">In Workshop</span>@endif
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($j->Open_date_time)->format('d/m/Y g:i A') }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ route('bp-jc.additional', $j->Jobc_id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded-md transition-colors">
                            <i class="fa fa-edit mr-1"></i> Manage
                        </a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="10" class="px-6 py-8 text-center text-gray-400">No unclosed jobcards.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
