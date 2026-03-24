@extends('parts.layout')
@section('title', 'Sale History')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Sale History — {{ $from }} to {{ $to }}</h2>
@include('parts.entry.reports._filter', ['showDates'=>true])
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-red-600 p-3">
        <h3 class="font-semibold text-white">Combined Workshop + Counter Sales by Part</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Total Qty Sold</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($parts as $i => $p)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium">{{ $p->part_no }}</td>
                    <td class="px-3 py-2 text-right font-bold">{{ $p->t_sale }}</td>
                </tr>
                @empty
                <tr><td colspan="3" class="px-4 py-8 text-center text-gray-400">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
