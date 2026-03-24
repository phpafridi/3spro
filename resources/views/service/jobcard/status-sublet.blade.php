@extends('layouts.master')
@section('title', 'Status — Sublet')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Sublet Status</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">RO#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Registration</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Sublet</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Type</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Qty</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Unit Price</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sublets as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm font-bold">#{{ $s->Jobc_id }}</td>
                    <td class="px-3 py-2 text-sm text-red-600">{{ $s->Registration }}</td>
                    <td class="px-3 py-2 text-sm">{{ $s->Sublet }}</td>
                    <td class="px-3 py-2 text-sm">{{ $s->type }}</td>
                    <td class="px-3 py-2 text-sm">{{ $s->qty }}</td>
                    <td class="px-3 py-2 text-sm">{{ number_format($s->unitprice, 0) }}</td>
                    <td class="px-3 py-2 text-sm font-medium">{{ number_format($s->total, 0) }}</td>
                    <td class="px-3 py-2 text-sm">
                        @if($s->status == 0)
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-800 rounded text-xs">Pending</span>
                        @else
                            <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-xs">{{ $s->status }}</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-6 py-8 text-center text-gray-400">No sublet items found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
