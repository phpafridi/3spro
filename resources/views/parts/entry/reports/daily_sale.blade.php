@extends('parts.layout')
@section('title', 'Daily Sale Report')
@section('content')
@include('partials.company-header')

<h2 class="text-xl font-bold text-gray-800 mb-4">Daily Sale Report — {{ $from }} to {{ $to }}</h2>

@include('parts.entry.reports._filter', ['showDates'=>true])

<div class="space-y-5">
    {{-- Workshop Parts --}}
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-red-600 p-3 flex justify-between items-center">
            <h3 class="font-semibold text-white">Workshop Parts</h3>
            <span class="text-sm text-white">Total: Rs {{ number_format($workshopParts->sum('total_amount'),0) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Category</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Amount</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($workshopParts as $i => $p)
                    <tr class="hover:bg-red-50">
                        <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                        <td class="px-3 py-2 font-medium">{{ $p->part_no }}</td>
                        <td class="px-3 py-2 text-xs text-gray-600">{{ $p->Description }}</td>
                        <td class="px-3 py-2 text-xs">{{ $p->catetype }}</td>
                        <td class="px-3 py-2 text-right">{{ $p->sale_qty }}</td>
                        <td class="px-3 py-2 text-right font-medium">{{ number_format($p->total_amount,0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Workshop Consumables --}}
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-red-700 p-3 flex justify-between items-center">
            <h3 class="font-semibold text-white">Workshop Consumables</h3>
            <span class="text-sm text-white">Total: Rs {{ number_format($workshopCons->sum('total_amount'),0) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Amount</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($workshopCons as $i => $p)
                    <tr class="hover:bg-red-50">
                        <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                        <td class="px-3 py-2 font-medium">{{ $p->part_no }}</td>
                        <td class="px-3 py-2 text-xs text-gray-600">{{ $p->Description }}</td>
                        <td class="px-3 py-2 text-right">{{ $p->sale_qty }}</td>
                        <td class="px-3 py-2 text-right font-medium">{{ number_format($p->total_amount,0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Counter Sale --}}
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
        <div class="bg-gray-700 p-3 flex justify-between items-center">
            <h3 class="font-semibold text-white">Counter Sale (Jobber)</h3>
            <span class="text-sm text-white">Total: Rs {{ number_format($counterSale->sum('total_amount'),0) }}</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Jobber</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Amount</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($counterSale as $i => $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                        <td class="px-3 py-2 font-medium">{{ $p->part_no }}</td>
                        <td class="px-3 py-2 text-xs text-gray-600">{{ $p->Description }}</td>
                        <td class="px-3 py-2 text-xs">{{ $p->Jobber }}</td>
                        <td class="px-3 py-2 text-right">{{ $p->sale_qty }}</td>
                        <td class="px-3 py-2 text-right font-medium">{{ number_format($p->total_amount,0) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Grand Total --}}
    <div class="bg-red-600 rounded p-4 text-white flex justify-between items-center">
        <span class="font-bold text-lg">Grand Total</span>
        <span class="font-bold text-2xl">Rs {{ number_format($totalWorkshop + $totalCounter, 0) }}</span>
    </div>
</div>
@endsection
