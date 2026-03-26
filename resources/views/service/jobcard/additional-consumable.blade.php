@extends('layouts.master')
@section('title', 'Add Consumable - RO# ' . $jobId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif

@php
    $storeRoute = ($jobcard->status >= 1)
        ? route('jobcard.additional.consumable.post-store')
        : route('jobcard.additional.consumable.store');
    $grandTotal = $consumbles->sum('total');
@endphp

<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    {{-- ADD CONSUMABLE FORM --}}
    <div class="md:col-span-2 bg-white rounded shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Add Consumable — RO# {{ $jobId }}</h2>
            <a href="{{ route('jobcard.additional', $jobId) }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa fa-arrow-left mr-1"></i>Back</a>
        </div>
        <form method="POST" action="{{ $storeRoute }}" class="space-y-3">
            @csrf
            <input type="hidden" name="job_id" value="{{ $jobId }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <div class="relative">
                    <input type="text" name="part_description" id="cons_input" required autocomplete="off"
                           placeholder="Type to search consumable..."
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                    <div id="cons_dropdown"
                         class="absolute z-50 w-full bg-white border border-gray-200 rounded-md shadow-lg mt-1 max-h-48 overflow-y-auto hidden">
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-0.5">Select from list or type a custom description</p>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty <span class="text-red-500">*</span></label>
                    <input type="number" name="qty" id="qty_input" min="1" value="1" required onchange="calcTotal()"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price <span class="text-red-500">*</span></label>
                    <input type="number" name="unitprice" id="up_input" step="0.01" min="0.01" required onchange="calcTotal()"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                <input type="number" name="totalprice" id="total_input" step="0.01" readonly
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-50">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-plus mr-2"></i> Add Consumable
            </button>
        </form>
    </div>

    {{-- CURRENT CONSUMABLES TABLE --}}
    <div class="md:col-span-3 bg-white rounded shadow-sm p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-700">Current Consumables
                <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $consumbles->count() }}</span>
            </h3>
            @if($grandTotal > 0)
            <div class="text-right">
                <span class="text-xs text-gray-500 block">Consumables Total</span>
                <span class="text-lg font-bold text-orange-600">{{ number_format($grandTotal, 2) }}</span>
            </div>
            @endif
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Del</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($consumbles as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $c->cons_description }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $c->qty }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($c->unitprice,2) }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($c->total,2) }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if($c->Additional == 1 && $c->status == 0)
                            <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs rounded-full">Additional</span>
                        @elseif($c->status >= 1)
                            <span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Issued</span>
                        @else
                            <span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                        @endif
                    </td>
                    <td class="px-4 py-2">
                        @if($c->status == 0 && $c->issued_qty == 0)
                        <button class="delete-item-btn px-2 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded"
                                data-id="{{ $c->cons_sale_id }}" data-type="consumable">
                            <i class="fa fa-trash"></i>
                        </button>
                        @else
                        <span class="text-gray-300 text-xs">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400 text-sm italic">No consumables yet.</td></tr>
                @endforelse
            </tbody>
            @if($grandTotal > 0)
            <tfoot>
                <tr class="bg-orange-50 border-t-2 border-orange-200">
                    <td colspan="3" class="px-4 py-2 text-sm font-semibold text-gray-700 text-right">Grand Total:</td>
                    <td class="px-4 py-2 text-sm font-bold text-orange-600">{{ number_format($grandTotal, 2) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

@push('scripts')
<script>
const consumableList = @json($consumableList ?? []);

function calcTotal() {
    var qty = parseFloat(document.getElementById('qty_input').value) || 0;
    var up  = parseFloat(document.getElementById('up_input').value) || 0;
    document.getElementById('total_input').value = (qty * up).toFixed(2);
}

document.addEventListener('DOMContentLoaded', function () {
    const input    = document.getElementById('cons_input');
    const dropdown = document.getElementById('cons_dropdown');

    function renderDropdown(items) {
        dropdown.innerHTML = '';
        if (!items.length) { dropdown.classList.add('hidden'); return; }
        items.forEach(function(item) {
            const div = document.createElement('div');
            div.textContent = item;
            div.className = 'px-3 py-2 text-sm text-gray-700 hover:bg-orange-50 hover:text-orange-700 cursor-pointer border-b border-gray-50 last:border-0';
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
        if (!q) { renderDropdown(consumableList.slice(0, 20)); return; }
        const filtered = consumableList.filter(function(c) { return c.toLowerCase().includes(q); }).slice(0, 20);
        renderDropdown(filtered);
    });

    input.addEventListener('focus', function () {
        const q = this.value.toLowerCase().trim();
        const items = q ? consumableList.filter(function(c) { return c.toLowerCase().includes(q); }).slice(0, 20) : consumableList.slice(0, 20);
        renderDropdown(items);
    });

    input.addEventListener('blur', function () {
        setTimeout(function() { dropdown.classList.add('hidden'); }, 150);
    });

    document.querySelectorAll('.delete-item-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Delete this item?')) return;
            var postData = { _token: document.querySelector('meta[name=csrf-token]').content, cnid: this.dataset.id };
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
