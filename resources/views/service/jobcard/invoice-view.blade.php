@extends('layouts.master')
@section('title', 'Invoice — RO #' . $jobcard->Jobc_id)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Invoice Details — RO# {{ $jobcard->Jobc_id }}</h2>
            <a href="javascript:history.back()" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        {{-- Jobcard summary --}}
        <div class="mb-6 p-4 bg-gray-50 rounded-md grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
            <div><span class="text-gray-500 text-xs block uppercase">Customer</span><strong>{{ $jobcard->Customer_name }}</strong></div>
            <div><span class="text-gray-500 text-xs block uppercase">Mobile</span>{{ $jobcard->mobile }}</div>
            <div><span class="text-gray-500 text-xs block uppercase">Registration</span><strong class="text-red-600">{{ $jobcard->Registration }}</strong></div>
            <div><span class="text-gray-500 text-xs block uppercase">Variant</span>{{ $jobcard->Variant }}</div>
            <div><span class="text-gray-500 text-xs block uppercase">Campaign</span>{{ $jobcard->comp_appointed }}</div>
            <div><span class="text-gray-500 text-xs block uppercase">SA</span>{{ $jobcard->SA }}</div>
            <div><span class="text-gray-500 text-xs block uppercase">MSI</span>{{ $jobcard->MSI_cat }}</div>
            <div><span class="text-gray-500 text-xs block uppercase">Status</span>
                @if($jobcard->status >= 2)
                    <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs">Closed</span>
                @else
                    <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded text-xs">Open</span>
                @endif
            </div>
        </div>

        {{-- Totals summary table --}}
        <div class="mb-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-red-600">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-white uppercase">Description</th>
                        <th class="px-4 py-2 text-right text-xs font-medium text-white uppercase">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">Labor (Workshop)</td>
                        <td class="px-4 py-2 text-sm text-right font-medium">{{ number_format($totalLabor, 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">Parts</td>
                        <td class="px-4 py-2 text-sm text-right font-medium">{{ number_format($totalParts, 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">Sublet (Workshop)</td>
                        <td class="px-4 py-2 text-sm text-right font-medium">{{ number_format($totalSublet, 0) }}</td>
                    </tr>
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 text-sm">Consumable</td>
                        <td class="px-4 py-2 text-sm text-right font-medium">{{ number_format($totalConsumble, 0) }}</td>
                    </tr>
                    <tr class="bg-gray-100 font-bold">
                        <td class="px-4 py-2 text-sm">Grand Total</td>
                        <td class="px-4 py-2 text-sm text-right text-red-600">
                            {{ number_format($totalLabor + $totalParts + $totalSublet + $totalConsumble, 0) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Labor detail --}}
        @if($labors->count())
        <h3 class="font-semibold text-gray-700 mb-2 text-sm uppercase">Labor</h3>
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full divide-y divide-gray-200 text-sm mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Labor</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Type</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Cost</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($labors as $l)
                    <tr>
                        <td class="px-3 py-2">{{ $l->Labor }}</td>
                        <td class="px-3 py-2">{{ $l->type }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($l->cost, 0) }}</td>
                        <td class="px-3 py-2">{{ $l->status ?: 'Pending' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif

        {{-- Parts detail --}}
        @if($parts->count())
        <h3 class="font-semibold text-gray-700 mb-2 text-sm uppercase">Parts</h3>
        <div class="overflow-x-auto mb-4">
            <table class="min-w-full divide-y divide-gray-200 text-sm mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Unit</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($parts as $p)
                    <tr>
                        <td class="px-3 py-2">{{ $p->part_description }}</td>
                        <td class="px-3 py-2 text-right">{{ $p->qty }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($p->unitprice, 0) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($p->total, 0) }}</td>
                        <td class="px-3 py-2">
                            @if($p->status == '1')
                                <span class="text-xs text-green-600">Issued</span>
                            @else
                                <span class="text-xs text-yellow-600">Pending</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>
@endsection
