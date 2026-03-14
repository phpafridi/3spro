@extends('layouts.master')
@section('title', 'SM - All Unclosed ROs')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">All Unclosed ROs
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $jobs->count() }}</span>
        </h2>
        <a href="{{ route('sm.index') }}" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors">
            <i class="fa fa-home mr-1"></i> Dashboard
        </a>
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
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Open Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $j)
                @php $hrs = \Carbon\Carbon::parse($j->Open_date_time)->diffInHours(now()); @endphp
                <tr class="hover:bg-gray-50 {{ $hrs>48 ? 'bg-red-50' : ($hrs>24 ? 'bg-yellow-50' : '') }}">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">#{{ $j->Jobc_id }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $j->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $j->Variant }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $j->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $j->mobile }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $j->SA }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $j->RO_type }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($j->Open_date_time)->format('d/m/Y g:i A') }}</td>
                    <td class="px-4 py-3 text-sm">
                        @if($j->status=='0')
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Open</span>
                        @else
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">In Workshop</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="9" class="px-6 py-8 text-center text-green-600"><i class="fa fa-check-circle mr-1"></i>All jobcards are closed!</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
