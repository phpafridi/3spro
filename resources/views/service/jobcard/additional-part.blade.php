@extends('layouts.master')
@section('title', 'Add Part - RO# ' . $jobId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif

@php
    $storeRoute = ($jobcard->status >= 1)
        ? route('jobcard.additional.part.post-store')
        : route('jobcard.additional.part.store');
    $grandTotal = $parts->sum('total');
@endphp

<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    {{-- ADD PART FORM --}}
    <div class="md:col-span-2 bg-white rounded shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Add Part — RO# {{ $jobId }}</h2>
            <div class="flex items-center gap-2">
                
                <button type="button" onclick="openOverviewModal()"
                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-semibold rounded transition">
                    <i class="fa fa-eye mr-1"></i> Overview
                </button>
                <a href="{{ route('jobcard.additional-list') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa fa-arrow-left mr-1"></i>Back</a>
            </div>
        </div>
        <form method="POST" action="{{ $storeRoute }}" class="space-y-3">
            @csrf
            <input type="hidden" name="job_id" value="{{ $jobId }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Part Description <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" name="part_description" id="part_input" required autocomplete="off"
                           placeholder="Type to search part..."
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div id="parts_dropdown"
                         class="absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto hidden">
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-0.5">Select from list or type a custom description</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty <span class="text-red-500">*</span></label>
                    <input type="number" name="qty" id="qty_input" min="1" step="1" value="1" required onchange="calcTotal()"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price <span class="text-red-500">*</span></label>
                    <input type="number" name="unitprice" id="unitprice_input" step="0.01" min="0.01" required onchange="calcTotal()"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                <input type="number" name="totalprice" id="total_input" step="0.01" readonly
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-50">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-plus mr-2"></i> Add Part
            </button>
        </form>
    </div>

    {{-- CURRENT PARTS TABLE — split Standard / Additional --}}
    <div class="md:col-span-3 bg-white rounded shadow-sm p-6 space-y-5">
        @php
            $standardParts   = $parts->where('Additional', 0);
            $additionalParts = $parts->where('Additional', 1);
            $standardTotal   = $standardParts->sum('total');
            $additionalTotal = $additionalParts->sum('total');
        @endphp

        {{-- Summary badges --}}
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div class="flex flex-wrap gap-2 text-xs">
                <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-full font-semibold">
                    <i class="fas fa-list mr-1"></i>Standard: {{ $standardParts->count() }}
                </span>
                <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-full font-semibold">
                    <i class="fas fa-plus-circle mr-1"></i>Additional: {{ $additionalParts->count() }}
                </span>
            </div>
            <div class="text-right">
                <span class="text-xs text-gray-400 block">All Parts Total</span>
                <span class="text-xl font-bold text-green-700">{{ number_format($grandTotal, 2) }}</span>
            </div>
        </div>

        {{-- STANDARD PARTS --}}
        <div>
            <div class="flex items-center justify-between px-3 py-2 bg-gray-700 rounded-t">
                <h4 class="text-xs font-bold text-white uppercase tracking-wide"><i class="fas fa-list mr-1.5"></i>Standard Parts</h4>
                <span class="text-xs text-gray-300">Sub-total: <strong class="text-white">{{ number_format($standardTotal, 2) }}</strong></span>
            </div>
            <table class="min-w-full divide-y divide-gray-100 border border-gray-200 rounded-b overflow-hidden">
                <thead class="bg-gray-50"><tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Unit</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-gray-500 uppercase">Del</th>
                </tr></thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($standardParts as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 text-sm text-gray-800">{{ $p->part_description }}</td>
                        <td class="px-3 py-2 text-sm text-center text-gray-600">{{ $p->qty }}</td>
                        <td class="px-3 py-2 text-sm text-right text-gray-600">{{ number_format($p->unitprice,2) }}</td>
                        <td class="px-3 py-2 text-sm text-right font-semibold text-gray-800">{{ number_format($p->total,2) }}</td>
                        <td class="px-3 py-2 text-center text-xs">
                            @if($p->status >= 1)
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full">Issued</span>
                            @else
                                <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            @if($p->status == 0 && $p->issued_qty == 0)
                            <button class="delete-item-btn px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded"
                                    data-id="{{ $p->parts_sale_id }}" data-type="part">
                                <i class="fa fa-trash"></i>
                            </button>
                            @else<span class="text-gray-300 text-xs">—</span>@endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-3 text-center text-gray-400 text-sm italic">No standard parts.</td></tr>
                    @endforelse
                </tbody>
                @if($standardTotal > 0)
                <tfoot class="bg-gray-50 border-t border-gray-200">
                    <tr>
                        <td colspan="3" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Standard Sub-total:</td>
                        <td class="px-3 py-2 text-sm font-bold text-gray-700 text-right">{{ number_format($standardTotal, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- ADDITIONAL PARTS --}}
        <div>
            <div class="flex items-center justify-between px-3 py-2 bg-blue-600 rounded-t">
                <h4 class="text-xs font-bold text-white uppercase tracking-wide"><i class="fas fa-plus-circle mr-1.5"></i>Additional Parts</h4>
                <span class="text-xs text-blue-200">Sub-total: <strong class="text-white">{{ number_format($additionalTotal, 2) }}</strong></span>
            </div>
            <table class="min-w-full divide-y divide-blue-50 border border-blue-100 rounded-b overflow-hidden">
                <thead class="bg-blue-50"><tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-blue-600 uppercase">Description</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-blue-600 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-blue-600 uppercase">Unit</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-blue-600 uppercase">Total</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-blue-600 uppercase">Status</th>
                    <th class="px-3 py-2 text-center text-xs font-medium text-blue-600 uppercase">Del</th>
                </tr></thead>
                <tbody class="bg-white divide-y divide-blue-50">
                    @forelse($additionalParts as $p)
                    <tr class="hover:bg-blue-50">
                        <td class="px-3 py-2 text-sm text-gray-800">{{ $p->part_description }}</td>
                        <td class="px-3 py-2 text-sm text-center text-gray-600">{{ $p->qty }}</td>
                        <td class="px-3 py-2 text-sm text-right text-gray-600">{{ number_format($p->unitprice,2) }}</td>
                        <td class="px-3 py-2 text-sm text-right font-semibold text-blue-800">{{ number_format($p->total,2) }}</td>
                        <td class="px-3 py-2 text-center text-xs">
                            @if($p->status >= 1)
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded-full">Issued</span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded-full">Pending</span>
                            @endif
                        </td>
                        <td class="px-3 py-2 text-center">
                            @if($p->status == 0 && $p->issued_qty == 0)
                            <button class="delete-item-btn px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded"
                                    data-id="{{ $p->parts_sale_id }}" data-type="part">
                                <i class="fa fa-trash"></i>
                            </button>
                            @else<span class="text-gray-300 text-xs">—</span>@endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-3 text-center text-gray-400 text-sm italic">No additional parts yet.</td></tr>
                    @endforelse
                </tbody>
                @if($additionalTotal > 0)
                <tfoot class="bg-blue-50 border-t border-blue-200">
                    <tr>
                        <td colspan="3" class="px-3 py-2 text-xs font-semibold text-blue-700 text-right">Additional Sub-total:</td>
                        <td class="px-3 py-2 text-sm font-bold text-blue-700 text-right">{{ number_format($additionalTotal, 2) }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        {{-- Grand Total bar --}}
        <div class="flex items-center justify-between bg-green-600 rounded px-4 py-3">
            <span class="text-white font-semibold text-sm">Grand Parts Total</span>
            <span class="text-white text-xl font-bold">{{ number_format($grandTotal, 2) }}</span>
        </div>
    </div>
</div>

{{-- OVERVIEW MODAL — shows all Standard + Additional parts with totals --}}
<div id="overviewModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 hidden">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl mx-4 max-h-[85vh] flex flex-col">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="text-base font-bold text-gray-800">
                <i class="fas fa-eye text-indigo-600 mr-2"></i>Parts Overview — RO# {{ $jobId }}
            </h3>
            <button onclick="document.getElementById('overviewModal').classList.add('hidden')"
                    class="text-gray-400 hover:text-gray-700 text-xl"><i class="fas fa-times"></i></button>
        </div>
        <div class="overflow-y-auto p-6 space-y-4 text-sm">
            @php
                $stdParts  = $parts->where('Additional', 0);
                $addParts  = $parts->where('Additional', 1);
                $stdTotal  = $stdParts->sum('total');
                $addTotal  = $addParts->sum('total');
            @endphp

            {{-- Standard --}}
            <div>
                <div class="flex items-center justify-between px-3 py-2 bg-gray-700 rounded-t">
                    <span class="text-xs font-bold text-white uppercase">Standard Parts ({{ $stdParts->count() }})</span>
                    <span class="text-xs text-white font-semibold">{{ number_format($stdTotal, 2) }}</span>
                </div>
                <table class="min-w-full border border-gray-200 rounded-b overflow-hidden">
                    <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">Part</th>
                            <th class="px-3 py-2 text-center">Qty</th>
                            <th class="px-3 py-2 text-right">Total</th>
                            <th class="px-3 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($stdParts as $p)
                        <tr>
                            <td class="px-3 py-1.5 text-gray-800">{{ $p->part_description }}</td>
                            <td class="px-3 py-1.5 text-center text-gray-600">{{ $p->qty }}</td>
                            <td class="px-3 py-1.5 text-right font-semibold text-gray-700">{{ number_format($p->total,2) }}</td>
                            <td class="px-3 py-1.5 text-center">
                                @if($p->status >= 1)<span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">Issued</span>
                                @else<span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pending</span>@endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-3 py-3 text-center text-gray-400 italic">None</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Additional --}}
            <div>
                <div class="flex items-center justify-between px-3 py-2 bg-blue-600 rounded-t">
                    <span class="text-xs font-bold text-white uppercase">Additional Parts ({{ $addParts->count() }})</span>
                    <span class="text-xs text-white font-semibold">{{ number_format($addTotal, 2) }}</span>
                </div>
                <table class="min-w-full border border-blue-100 rounded-b overflow-hidden">
                    <thead class="bg-blue-50 text-xs text-blue-600 uppercase">
                        <tr>
                            <th class="px-3 py-2 text-left">Part</th>
                            <th class="px-3 py-2 text-center">Qty</th>
                            <th class="px-3 py-2 text-right">Total</th>
                            <th class="px-3 py-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-blue-50">
                        @forelse($addParts as $p)
                        <tr class="bg-white hover:bg-blue-50">
                            <td class="px-3 py-1.5 text-gray-800">{{ $p->part_description }}</td>
                            <td class="px-3 py-1.5 text-center text-gray-600">{{ $p->qty }}</td>
                            <td class="px-3 py-1.5 text-right font-semibold text-blue-800">{{ number_format($p->total,2) }}</td>
                            <td class="px-3 py-1.5 text-center">
                                @if($p->status >= 1)<span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full text-xs">Issued</span>
                                @else<span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full text-xs">Pending</span>@endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="px-3 py-3 text-center text-gray-400 italic">None</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Grand Total --}}
            <div class="flex items-center justify-between bg-green-600 rounded px-4 py-3 mt-2">
                <span class="text-white font-bold">Grand Parts Total</span>
                <span class="text-white text-xl font-bold">{{ number_format($grandTotal, 2) }}</span>
            </div>
        </div>
    </div>
</div>


@push('scripts')
<script>
const partsList = @json($partsList ?? []);

function calcTotal() {
    var qty = parseFloat(document.getElementById('qty_input').value) || 0;
    var up  = parseFloat(document.getElementById('unitprice_input').value) || 0;
    document.getElementById('total_input').value = (qty * up).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    const input    = document.getElementById('part_input');
    const dropdown = document.getElementById('parts_dropdown');

    function renderDropdown(items) {
        dropdown.innerHTML = '';
        if (!items.length) { dropdown.classList.add('hidden'); return; }
        items.forEach(function(item) {
            const div = document.createElement('div');
            div.textContent = item;
            div.className = 'px-3 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 cursor-pointer border-b border-gray-50 last:border-0';
            div.addEventListener('mousedown', function(e) {
                e.preventDefault();
                input.value = item;
                dropdown.classList.add('hidden');
            });
            dropdown.appendChild(div);
        });
        dropdown.classList.remove('hidden');
    }

    input.addEventListener('input', function () {
        const q = this.value.toLowerCase().trim();
        if (!q) { renderDropdown(partsList.slice(0, 20)); return; }
        const filtered = partsList.filter(function(p) { return p.toLowerCase().includes(q); }).slice(0, 20);
        renderDropdown(filtered);
    });

    input.addEventListener('focus', function () {
        const q = this.value.toLowerCase().trim();
        const items = q ? partsList.filter(function(p) { return p.toLowerCase().includes(q); }).slice(0, 20) : partsList.slice(0, 20);
        renderDropdown(items);
    });

    input.addEventListener('blur', function () {
        setTimeout(function() { dropdown.classList.add('hidden'); }, 150);
    });

    // Overview modal
    window.openOverviewModal = function() {
        document.getElementById('overviewModal').classList.remove('hidden');
    };
    document.getElementById('overviewModal').addEventListener('click', function(e) {
        if (e.target === this) this.classList.add('hidden');
    });

    document.querySelectorAll('.delete-item-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Delete this item?')) return;
            var postData = { _token: document.querySelector('meta[name=csrf-token]').content, Pid: this.dataset.id };
            fetch('{{ route("jobcard.delete-item") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams(postData)
            }).then(function () { location.reload(); });
        });
    });
});
</script>
@endpush
@endsection
