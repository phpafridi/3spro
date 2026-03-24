@extends('layouts.master')
@section('title', 'Sales - Unclose Jobcards')
@section('sidebar-menu') @include('sales.partials.sidebar') @endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Unclosed Jobcards
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 text-sm rounded-full">{{ count($jobcards) }}</span>
        </h2>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600"><tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Jobcard#</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Registration</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Customer</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">SA</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Open Time</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Labor Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Status</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobcards as $jc)
                @php $hrs = \Carbon\Carbon::parse($jc->Open_date_time)->diffInHours(now()); @endphp
                <tr class="hover:bg-gray-50 {{ $hrs > 48 ? 'bg-red-50' : ($hrs > 24 ? 'bg-yellow-50' : '') }}">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $jc->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $jc->Veh_reg_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $jc->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $jc->SA }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $jc->bookingtime }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($jc->openLabors > 0)
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">{{ $jc->openLabors }} Open</span>
                        @else
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">All Done</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-sm">
                        @if($jc->status=='0')
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Open</span>
                        @elseif($jc->status=='1')
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">In Workshop</span>
                        @else
                            <span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Status {{ $jc->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-6 py-8 text-center text-green-600">
                    <i class="fa fa-check-circle mr-1"></i>All jobcards closed!
                </td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
