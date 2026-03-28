@extends('layouts.master')
@section('title', 'View RO — #' . $jobId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')

@php
    $laborTotal     = $labors->where('type', 'Workshop')->sum('cost');
    $partsTotal     = $parts->sum('total');
    $consumbleTotal = $consumbles->sum('total');
    $subletTotal    = $sublets->where('type', 'Workshop')->sum('total');
    $grandTotal     = $laborTotal + $partsTotal + $consumbleTotal + $subletTotal;
@endphp

{{-- Header --}}
<div class="bg-white rounded shadow-sm p-4 mb-4">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-800">RO# {{ $jobId }}</h2>
            <div class="mt-1 flex flex-wrap gap-4 text-sm text-gray-600">
                <span><i class="fa fa-car mr-1 text-gray-400"></i> <span class="font-medium text-red-600">{{ $jobcard->Registration }}</span></span>
                <span><i class="fa fa-user mr-1 text-gray-400"></i> {{ $jobcard->Customer_name }}</span>
                <span><i class="fa fa-phone mr-1 text-gray-400"></i> {{ $jobcard->mobile }}</span>
                <span><i class="fa fa-tag mr-1 text-gray-400"></i> {{ $jobcard->Variant ?? '-' }}</span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('jobcard.additional.jobrequest', $jobId) }}" class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-wrench mr-1"></i> Add Labor
            </a>
            <a href="{{ route('jobcard.additional.part', $jobId) }}" class="px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-cog mr-1"></i> Add Parts
            </a>
            <a href="{{ route('jobcard.additional.sublet', $jobId) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-external-link mr-1"></i> Add Sublet
            </a>
            <a href="{{ route('jobcard.additional.consumable', $jobId) }}" class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-flask mr-1"></i> Consumable
            </a>
            <a href="{{ route('jobcard.additional-list') }}" class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>
</div>

{{-- Grand Total Summary Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-4">
    <div class="bg-yellow-50 border border-yellow-200 rounded p-3 text-center">
        <div class="text-xs text-yellow-600 font-medium uppercase mb-1">Labor</div>
        <div class="text-lg font-bold text-yellow-700">{{ number_format($laborTotal, 0) }}</div>
    </div>
    <div class="bg-cyan-50 border border-cyan-200 rounded p-3 text-center">
        <div class="text-xs text-cyan-600 font-medium uppercase mb-1">Parts</div>
        <div class="text-lg font-bold text-cyan-700">{{ number_format($partsTotal, 0) }}</div>
    </div>
    <div class="bg-orange-50 border border-orange-200 rounded p-3 text-center">
        <div class="text-xs text-orange-600 font-medium uppercase mb-1">Consumable</div>
        <div class="text-lg font-bold text-orange-700">{{ number_format($consumbleTotal, 0) }}</div>
    </div>
    <div class="bg-blue-50 border border-blue-200 rounded p-3 text-center">
        <div class="text-xs text-blue-600 font-medium uppercase mb-1">Sublet</div>
        <div class="text-lg font-bold text-blue-700">{{ number_format($subletTotal, 0) }}</div>
    </div>
    <div class="bg-green-600 rounded p-3 text-center col-span-2 sm:col-span-1">
        <div class="text-xs text-green-100 font-medium uppercase mb-1">Grand Total</div>
        <div class="text-lg font-bold text-white">{{ number_format($grandTotal, 0) }}</div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

    {{-- Labor --}}
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-yellow-500">
            <h3 class="text-sm font-semibold text-white"><i class="fa fa-wrench mr-2"></i>Labor
                <span class="ml-1 px-1.5 py-0.5 bg-yellow-400 text-white text-xs rounded-full">{{ $labors->count() }}</span>
            </h3>
            <a href="{{ route('jobcard.additional.jobrequest', $jobId) }}" class="text-xs text-yellow-100 hover:text-white">+ Add</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ $l->Labor }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $l->type }}</td>
                    <td class="px-3 py-2 text-sm text-right font-medium text-gray-700">{{ number_format($l->cost, 0) }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if($l->Additional == 1 && (!$l->status || $l->status == '0'))
                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full">Additional</span>
                        @elseif($l->status && $l->status != '0')
                            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full">{{ $l->status }}</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No labor added.</td></tr>
                @endforelse
            </tbody>
            @if($labors->count() > 0)
            <tfoot class="bg-yellow-50 border-t-2 border-yellow-200">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Total:</td>
                    <td class="px-3 py-2 text-sm font-bold text-yellow-700 text-right">{{ number_format($laborTotal, 0) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- Parts --}}
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-cyan-600">
            <h3 class="text-sm font-semibold text-white"><i class="fa fa-cog mr-2"></i>Parts
                <span class="ml-1 px-1.5 py-0.5 bg-cyan-500 text-white text-xs rounded-full">{{ $parts->count() }}</span>
            </h3>
            <a href="{{ route('jobcard.additional.part', $jobId) }}" class="text-xs text-cyan-100 hover:text-white">+ Add</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($parts as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ $p->part_description }}</td>
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $p->qty }}</td>
                    <td class="px-3 py-2 text-sm text-right font-medium text-gray-700">{{ number_format($p->total, 0) }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if($p->Additional == 1 && $p->status == 0)
                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full">Additional</span>
                        @elseif($p->status >= 1)
                            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full">Issued</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No parts added.</td></tr>
                @endforelse
            </tbody>
            @if($parts->count() > 0)
            <tfoot class="bg-cyan-50 border-t-2 border-cyan-200">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Total:</td>
                    <td class="px-3 py-2 text-sm font-bold text-cyan-700 text-right">{{ number_format($partsTotal, 0) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- Consumable --}}
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-orange-500">
            <h3 class="text-sm font-semibold text-white"><i class="fa fa-flask mr-2"></i>Consumable
                <span class="ml-1 px-1.5 py-0.5 bg-orange-400 text-white text-xs rounded-full">{{ $consumbles->count() }}</span>
            </h3>
            <a href="{{ route('jobcard.additional.consumable', $jobId) }}" class="text-xs text-orange-100 hover:text-white">+ Add</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($consumbles as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ $c->consumble_description ?? $c->description ?? '-' }}</td>
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $c->qty ?? 1 }}</td>
                    <td class="px-3 py-2 text-sm text-right font-medium text-gray-700">{{ number_format($c->total ?? 0, 0) }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if(isset($c->status) && $c->status)
                            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full">Issued</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No consumables added.</td></tr>
                @endforelse
            </tbody>
            @if($consumbles->count() > 0)
            <tfoot class="bg-orange-50 border-t-2 border-orange-200">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Total:</td>
                    <td class="px-3 py-2 text-sm font-bold text-orange-700 text-right">{{ number_format($consumbleTotal, 0) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- Sublets --}}
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-blue-600">
            <h3 class="text-sm font-semibold text-white"><i class="fa fa-external-link mr-2"></i>Sublets
                <span class="ml-1 px-1.5 py-0.5 bg-blue-500 text-white text-xs rounded-full">{{ $sublets->count() }}</span>
            </h3>
            <a href="{{ route('jobcard.additional.sublet', $jobId) }}" class="text-xs text-blue-100 hover:text-white">+ Add</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sublet</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($sublets as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ $s->Sublet }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $s->type }}</td>
                    <td class="px-3 py-2 text-sm text-right font-medium text-gray-700">{{ number_format($s->total, 0) }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if($s->additional == 1 && !$s->status)
                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full">Additional</span>
                        @elseif($s->status)
                            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full">{{ $s->status }}</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No sublets added.</td></tr>
                @endforelse
            </tbody>
            @if($sublets->count() > 0)
            <tfoot class="bg-blue-50 border-t-2 border-blue-200">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Total:</td>
                    <td class="px-3 py-2 text-sm font-bold text-blue-700 text-right">{{ number_format($subletTotal, 0) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

</div>

{{-- Grand total footer --}}
<div class="mt-4 bg-green-600 rounded p-4 flex items-center justify-between">
    <span class="text-white font-semibold text-sm">Grand Total (Workshop charges)</span>
    <span class="text-white text-xl font-bold">{{ number_format($grandTotal, 2) }}</span>
</div>

@endsection
