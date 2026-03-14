@extends('layouts.master')
@section('title', 'SM - Consumable Status')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-800">Consumable Status — In Workshop</h2>
    <a href="{{ route('sm.index') }}" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"><i class="fa fa-home mr-1"></i>Dashboard</a>
</div>
@if($jobcards->isEmpty())
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg"><i class="fa fa-check-circle mr-2"></i>No jobcards in workshop.</div>
@else
    @foreach($jobcards as $jc)
    @if(isset($consData[$jc->Jobc_id]) && count($consData[$jc->Jobc_id]))
    <div class="bg-white rounded-lg shadow-sm p-5 mb-4">
        <div class="flex items-center gap-4 mb-3 pb-3 border-b border-gray-100">
            <span class="font-bold text-gray-800">RO# {{ $jc->Jobc_id }}</span>
            <span class="text-gray-500 text-sm">SA: {{ $jc->SA }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($consData[$jc->Jobc_id] as $c)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $c->cons_description }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ $c->qty }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($c->unitprice,2) }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($c->total,2) }}</td>
                        <td class="px-4 py-2 text-sm">
                            @if($c->status=='0')<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                            @else<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Issued</span>@endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
    @endforeach
@endif
@endsection
