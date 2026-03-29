@extends('parts.layout')
@section('title','Technician Incentive Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Tech Incentive Report</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-yellow-500 p-3 flex justify-between items-center">
        <h3 class="font-semibold text-white">Incentives</h3>
        <span class="text-sm text-white">Total: Rs {{ number_format($totalIncentive,0) }}</span>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Jobcard#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Customer</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Vehicle</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Incentive</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Date</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-yellow-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->jobcard_no }}</td>
                    <td class="px-3 py-2">{{ $r->customer_name }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->vehicle_no }}</td>
                    <td class="px-3 py-2 text-right font-semibold text-yellow-700">Rs {{ number_format($r->incentive_amount,0) }}</td>
                    <td class="px-3 py-2 text-gray-500">{{ $r->created_at }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
