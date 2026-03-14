@extends('layouts.master')
@section('title', 'BP - Jobcard RO# ' . $jobId)
@section('sidebar-menu')
    @include('service.partials.bp-jc-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif

{{-- Vehicle & Customer Info --}}
<div class="bg-white rounded-lg shadow-sm p-5 mb-4">
    <div class="flex justify-between items-start mb-3">
        <h2 class="text-xl font-semibold text-gray-800">RO# {{ $jobId }}</h2>
        <a href="{{ route('bp-jc.unclosed') }}" class="px-3 py-1.5 bg-gray-200 hover:bg-gray-300 text-gray-700 text-sm rounded transition-colors">
            <i class="fa fa-arrow-left mr-1"></i> Back
        </a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
        <div><span class="text-gray-500">Registration:</span> <span class="font-medium text-red-600">{{ $jobcard->Registration }}</span></div>
        <div><span class="text-gray-500">Variant:</span> <span class="font-medium text-gray-800">{{ $jobcard->Variant }}</span></div>
        <div><span class="text-gray-500">Customer:</span> <span class="font-medium text-gray-800">{{ $jobcard->Customer_name }}</span></div>
        <div><span class="text-gray-500">Mobile:</span> <span class="font-medium text-gray-800">{{ $jobcard->mobile }}</span></div>
        <div><span class="text-gray-500">SA:</span> <span class="font-medium text-gray-800">{{ $jobcard->SA }}</span></div>
        <div><span class="text-gray-500">RO Type:</span> <span class="font-medium text-gray-800">{{ $jobcard->RO_type }}</span></div>
        <div><span class="text-gray-500">Open Date:</span> <span class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($jobcard->Open_date_time)->format('d/m/Y g:i A') }}</span></div>
        <div><span class="text-gray-500">Mileage:</span> <span class="font-medium text-gray-800">{{ $jobcard->Mileage }}</span></div>
    </div>
</div>

{{-- Quick Add Buttons --}}
<div class="flex flex-wrap gap-2 mb-4">
    <a href="{{ route('bp-jc.additional.jobrequest', $jobId) }}" class="px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded transition-colors"><i class="fa fa-plus mr-1"></i>Add Labor</a>
    <a href="{{ route('bp-jc.additional.part', $jobId) }}" class="px-3 py-2 bg-cyan-600 hover:bg-cyan-700 text-white text-sm rounded transition-colors"><i class="fa fa-cogs mr-1"></i>Add Part</a>
    <a href="{{ route('bp-jc.additional.consumable', $jobId) }}" class="px-3 py-2 bg-yellow-500 hover:bg-yellow-600 text-white text-sm rounded transition-colors"><i class="fa fa-tint mr-1"></i>Add Consumable</a>
    <a href="{{ route('bp-jc.additional.sublet', $jobId) }}" class="px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm rounded transition-colors"><i class="fa fa-external-link mr-1"></i>Add Sublet</a>
</div>

{{-- Labor --}}
<div class="bg-white rounded-lg shadow-sm p-5 mb-4">
    <h3 class="font-semibold text-gray-700 mb-3">Labor
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $labors->count() }}</span>
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Team / Bay</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Entry</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $l->Labor }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->type }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($l->cost,0) }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if(!$l->status)<span class="px-2 py-0.5 bg-gray-100 text-gray-600 text-xs rounded-full">Pending</span>
                        @elseif($l->status=='Job Assign')<span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">Assigned</span>
                        @elseif($l->status=='Jobclose')<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Done</span>
                        @else<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">{{ $l->status }}</span>@endif
                    </td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->team }} / {{ $l->bay }}</td>
                    <td class="px-4 py-2 text-sm text-gray-400">{{ $l->entry_time }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400 text-sm italic">No labor added.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Parts & Consumables --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
    <div class="bg-white rounded-lg shadow-sm p-5">
        <h3 class="font-semibold text-gray-700 mb-3">Parts
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $parts->count() }}</span>
        </h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($parts as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-700">{{ $p->part_description }}</td>
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $p->qty }}</td>
                    <td class="px-3 py-2 text-sm text-gray-700">{{ number_format($p->total,2) }}</td>
                    <td class="px-3 py-2 text-sm">
                        @if($p->status=='0')<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                        @else<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Issued</span>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No parts.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-5">
        <h3 class="font-semibold text-gray-700 mb-3">Consumables
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $consumbles->count() }}</span>
        </h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($consumbles as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-700">{{ $c->cons_description }}</td>
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $c->qty }}</td>
                    <td class="px-3 py-2 text-sm text-gray-700">{{ number_format($c->total,2) }}</td>
                    <td class="px-3 py-2 text-sm">
                        @if($c->status=='0')<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                        @else<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Issued</span>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No consumables.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Sublets --}}
<div class="bg-white rounded-lg shadow-sm p-5">
    <h3 class="font-semibold text-gray-700 mb-3">Sublets
        <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $sublets->count() }}</span>
    </h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sublet</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sublets as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $s->Sublet }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $s->type }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $s->qty }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($s->unitprice,2) }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($s->total,2) }}</td>
                    <td class="px-4 py-2 text-sm"><span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">{{ $s->status ?: 'Pending' }}</span></td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400 text-sm italic">No sublets.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
