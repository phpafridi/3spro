@extends('parts.layout')
@section('title', 'Estimates - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Estimates</h2>
</div>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
<table class="w-full text-sm">
    <thead class="bg-red-50"><tr>
        <th class="px-4 py-3 text-left text-xs text-gray-600 uppercase">Est #</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600 uppercase">Registration</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600 uppercase">Variant</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600 uppercase">Type</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600 uppercase">Cust Type</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600 uppercase">Payment</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600 uppercase">Date</th>
        <th class="px-4 py-3 text-left text-xs text-gray-600 uppercase">By</th>
    </tr></thead>
    <tbody class="divide-y divide-gray-100">
    @forelse($estimates as $e)
    <tr class="hover:bg-red-50/30">
        <td class="px-4 py-3 font-medium">{{ $e->est_id }}</td>
        <td class="px-4 py-3">{{ $e->Registration ?? '-' }}</td>
        <td class="px-4 py-3">{{ $e->Variant ?? '-' }}</td>
        <td class="px-4 py-3">{{ $e->estimate_type ?? '-' }}</td>
        <td class="px-4 py-3">{{ $e->cust_type ?? '-' }}</td>
        <td class="px-4 py-3">{{ $e->payment_mode ?? '-' }}</td>
        <td class="px-4 py-3 text-xs text-gray-500">
            {{ $e->entry_datetime ? \Carbon\Carbon::parse($e->entry_datetime)->format('d M h:i A') : '-' }}
        </td>
        <td class="px-4 py-3 text-xs text-gray-500">{{ $e->user ?? '-' }}</td>
    </tr>
    @empty
    <tr><td colspan="8" class="px-4 py-10 text-center text-gray-400">No estimates found</td></tr>
    @endforelse
    </tbody>
</table>
</div>
@endsection
