@extends('parts.layout')
@section('title', 'Reorder Report')
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Reorder Report — Parts Below Reorder Level</h2>
<div class="mb-4 flex gap-3">
    <a href="{{ route('parts.reports') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-sm">← Reports</a>
    <button onclick="window.print()" class="px-4 py-2 bg-gray-700 text-white rounded text-sm">Print</button>
</div>
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="bg-red-600 p-3">
        <h3 class="font-semibold text-white">{{ $parts->count() }} parts need reordering</h3>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50"><tr>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Part#</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Description</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Category</th>
                <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Location</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Current</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Reorder Lvl</th>
                <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Shortage</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($parts as $i => $p)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-3 py-2 font-medium text-red-600">{{ $p->Part_no }}</td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $p->Description }}</td>
                    <td class="px-3 py-2 text-xs">{{ $p->catetype }}</td>
                    <td class="px-3 py-2 text-xs">{{ $p->Location }}</td>
                    <td class="px-3 py-2 text-right {{ $p->current_stock==0 ? 'text-red-500 font-bold' : '' }}">{{ $p->current_stock }}</td>
                    <td class="px-3 py-2 text-right">{{ $p->ReOrder }}</td>
                    <td class="px-3 py-2 text-right font-bold text-red-600">{{ $p->ReOrder - $p->current_stock }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="px-4 py-8 text-center text-green-600 font-medium">All parts are above reorder level ✓</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
