@extends('layouts.master')
@section('title', 'Status — Parts')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Parts Status</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">RO#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Registration</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Part Description</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Qty</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Unit Price</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Status</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Added</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php $grandTotal = 0; @endphp
                @forelse($parts as $p)
                @php $grandTotal += $p->total; @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm font-bold">#{{ $p->Jobc_id }}</td>
                    <td class="px-3 py-2 text-sm text-red-600">{{ $p->Registration }}</td>
                    <td class="px-3 py-2 text-sm">{{ $p->part_description }}</td>
                    <td class="px-3 py-2 text-sm">{{ $p->qty }}</td>
                    <td class="px-3 py-2 text-sm">{{ number_format($p->unitprice, 0) }}</td>
                    <td class="px-3 py-2 text-sm font-medium">{{ number_format($p->total, 0) }}</td>
                    <td class="px-3 py-2 text-sm">
                        @if($p->status == '1')
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs">Issued</span>
                        @else
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs">Pending</span>
                        @endif
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-500">
                        {{ $p->entry_datetime ? \Carbon\Carbon::parse($p->entry_datetime)->format('d/m/Y g:i A') : '—' }}
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No parts found.</td></tr>
                @endforelse
                @if($parts->count())
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="5" class="px-3 py-2 text-right text-sm">Total</td>
                    <td class="px-3 py-2 text-sm">{{ number_format($grandTotal, 0) }}</td>
                    <td colspan="2"></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
