@extends('parts.layout')
@section('title', 'Incentives - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Technician Incentives</h2>
</div>
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gradient-to-r from-indigo-50 to-purple-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">#</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Details</th>
                <th class="px-4 py-3 text-right text-xs font-semibold text-gray-600 uppercase">Amount</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse($incentives as $i => $inc)
        <tr class="hover:bg-indigo-50/30">
            <td class="px-4 py-3 text-gray-500">{{ $i+1 }}</td>
            <td class="px-4 py-3 text-gray-800">{{ $inc->details ?? '-' }}</td>
            <td class="px-4 py-3 text-right font-medium text-green-700">{{ number_format($inc->amount ?? 0, 2) }}</td>
        </tr>
        @empty
        <tr><td colspan="3" class="px-4 py-10 text-center text-gray-400">No incentives data available</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
