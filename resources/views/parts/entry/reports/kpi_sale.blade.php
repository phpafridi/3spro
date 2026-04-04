@extends('parts.layout')
@section('title','KPI Report')
@section('content')
@include('partials.company-header')
<h2 class="text-xl font-bold text-gray-800 mb-4">KPI OEM {{ ucfirst(str_replace('kpi_','',"kpi_sale")) }} Report</h2>
@include('parts.entry.reports._filter',['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-purple-600 p-3"><h3 class="font-semibold text-white">{{ $reportType }} Summary</h3></div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Period</th>
                @if("kpi_sale" === 'kpi_sale' || "kpi_sale" === 'kpi_profit')
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Sale</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Cost</th>
                @endif
                @if("kpi_sale" === 'kpi_profit')
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Profit</th>
                @endif
                @if("kpi_sale" === 'kpi_purch')
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Invoices</th>
                @endif
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $i => $r)
                <tr class="hover:bg-purple-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $r->period }}</td>
                    @if("kpi_sale" === 'kpi_sale' || "kpi_sale" === 'kpi_profit')
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->sale,0) }}</td>
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->cost,0) }}</td>
                    @endif
                    @if("kpi_sale" === 'kpi_profit')
                    <td class="px-3 py-2 text-right {{ $r->profit >= 0 ? 'text-green-600' : 'text-red-600' }} font-semibold">Rs {{ number_format($r->profit,0) }}</td>
                    @endif
                    @if("kpi_sale" === 'kpi_purch')
                    <td class="px-3 py-2 text-right">Rs {{ number_format($r->total,0) }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->invoices }}</td>
                    @endif
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-6 text-center text-gray-400">No records found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
