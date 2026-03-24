@extends('layouts.master')
@section('title', 'Add Consumable - RO# ' . $jobId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')

@push('scripts')
<script>
// Delete item — matches original delete_labor.php
// POST: cnid=id
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-item-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Delete this item?')) return;
            var postData = { _token: document.querySelector('meta[name=csrf-token]').content };
            postData['cnid'] = this.dataset.id;
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
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    <div class="md:col-span-2 bg-white rounded shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Add Consumable — RO# {{ $jobId }}</h2>
            <a href="{{ route('jobcard.additional', $jobId) }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa fa-arrow-left mr-1"></i>Back</a>
        </div>
        <form method="POST" action="{{ route('jobcard.additional.consumable.store') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="job_id" value="{{ $jobId }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Description <span class="text-red-500">*</span></label>
                <input type="text" name="part_description" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty <span class="text-red-500">*</span></label>
                    <input type="number" name="qty" id="qty_input" min="1" value="1" required onchange="calcTotal()"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price <span class="text-red-500">*</span></label>
                    <input type="number" name="unitprice" id="up_input" step="0.01" min="0.01" required onchange="calcTotal()"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                <input type="number" name="totalprice" id="total_input" step="0.01" readonly
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-50">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-plus mr-2"></i> Add Consumable
            </button>
        </form>
    </div>
    <div class="md:col-span-3 bg-white rounded shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-3">Current Consumables
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $consumbles->count() }}</span>
        </h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($consumbles as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $c->cons_description }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $c->qty }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($c->unitprice,2) }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($c->total,2) }}</td>
                    <td class="px-4 py-2 text-sm">
                        @if($c->status=='0')<span class="px-2 py-0.5 bg-yellow-100 text-yellow-700 text-xs rounded-full">Pending</span>
                        @else<span class="px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Issued</span>@endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-4 py-4 text-center text-gray-400 text-sm italic">No consumables yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
function calcTotal() {
    document.getElementById('total_input').value = ((parseFloat(document.getElementById('qty_input').value)||0)*(parseFloat(document.getElementById('up_input').value)||0)).toFixed(2);
}
</script>
@endpush

@push('scripts')
<script>
// Delete item — matches original delete_labor.php
// POST: cnid=id
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-item-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Delete this item?')) return;
            var postData = { _token: document.querySelector('meta[name=csrf-token]').content };
            postData['cnid'] = this.dataset.id;
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
