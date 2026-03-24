@extends('layouts.master')
@section('title', $heading)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">{{ $heading }}</h2>
        <a href="javascript:history.back()" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa fa-arrow-left mr-1"></i> Back
        </a>
    </div>

    @forelse($jobcards as $jc)
    <div class="mb-6 border border-gray-200 rounded overflow-hidden">
        {{-- Header row --}}
        <div class="bg-gray-50 border-b border-gray-200 px-4 py-3 grid grid-cols-2 md:grid-cols-5 gap-2 text-sm">
            <div>
                <span class="text-gray-500 text-xs uppercase block">RO#</span>
                <strong class="text-blue-600">{{ $jc->Jobc_id }}</strong>
            </div>
            <div>
                <span class="text-gray-500 text-xs uppercase block">Registration</span>
                <strong class="text-red-600">{{ $jc->Veh_reg_no }}</strong>
            </div>
            <div>
                <span class="text-gray-500 text-xs uppercase block">Customer</span>
                {{ $jc->Customer_name }}
            </div>
            <div>
                <span class="text-gray-500 text-xs uppercase block">MSI Category</span>
                {{ $jc->MSI_cat }}
            </div>
            <div>
                <span class="text-gray-500 text-xs uppercase block">Mileage / Closed</span>
                {{ number_format($jc->Mileage) }} km<br>
                <span class="text-xs text-gray-400">{{ $jc->bookingtime }}</span>
            </div>
        </div>

        {{-- VOC --}}
        @if($jc->VOC)
        <div class="px-4 py-2 bg-yellow-50 border-b border-gray-200 text-sm">
            <span class="text-gray-500 text-xs uppercase">VOC: </span>{{ $jc->VOC }}
        </div>
        @endif

        {{-- Items grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 divide-x divide-gray-100 text-sm">
            <div class="p-3">
                <div class="text-xs font-semibold text-blue-600 uppercase mb-1">Labor</div>
                @if($jc->labors && $jc->labors->labor_list)
                    {!! $jc->labors->labor_list !!}
                    <div class="text-xs text-gray-500 mt-1">Total: {{ number_format($jc->labors->total_labor ?? 0, 0) }}</div>
                @else
                    <span class="text-gray-400 text-xs">—</span>
                @endif
            </div>
            <div class="p-3">
                <div class="text-xs font-semibold text-cyan-600 uppercase mb-1">Parts</div>
                @if($jc->parts && $jc->parts->parts_list)
                    {!! $jc->parts->parts_list !!}
                    <div class="text-xs text-gray-500 mt-1">Total: {{ number_format($jc->parts->total_parts ?? 0, 0) }}</div>
                @else
                    <span class="text-gray-400 text-xs">—</span>
                @endif
            </div>
            <div class="p-3">
                <div class="text-xs font-semibold text-orange-600 uppercase mb-1">Consumble</div>
                @if($jc->consumbles && $jc->consumbles->cons_list)
                    {!! $jc->consumbles->cons_list !!}
                    <div class="text-xs text-gray-500 mt-1">Total: {{ number_format($jc->consumbles->total_cons ?? 0, 0) }}</div>
                @else
                    <span class="text-gray-400 text-xs">—</span>
                @endif
            </div>
            <div class="p-3">
                <div class="text-xs font-semibold text-yellow-600 uppercase mb-1">Sublet</div>
                @if($jc->sublets && $jc->sublets->sub_list)
                    {!! $jc->sublets->sub_list !!}
                    <div class="text-xs text-gray-500 mt-1">Total: {{ number_format($jc->sublets->total_sub ?? 0, 0) }}</div>
                @else
                    <span class="text-gray-400 text-xs">—</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="text-center py-12 text-gray-400">
        <i class="fa fa-history text-4xl block mb-3"></i>
        No jobcard history found.
    </div>
    @endforelse
</div>
@endsection
