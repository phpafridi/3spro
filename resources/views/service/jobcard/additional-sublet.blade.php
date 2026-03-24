@extends('layouts.master')
@section('title', 'Add Sublet - RO# ' . $jobId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')

@push('scripts')
<script>
// Delete item — matches original delete_labor.php
// POST: sid=id
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-item-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Delete this item?')) return;
            var postData = { _token: document.querySelector('meta[name=csrf-token]').content };
            postData['sid'] = this.dataset.id;
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
            <h2 class="text-lg font-semibold text-gray-800">Add Sublet — RO# {{ $jobId }}</h2>
            <a href="{{ route('jobcard.additional', $jobId) }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa fa-arrow-left mr-1"></i>Back</a>
        </div>
        <form method="POST" action="{{ route('jobcard.additional.sublet.store') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="job_id" value="{{ $jobId }}">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Sublet Description <span class="text-red-500">*</span></label>
                <input type="text" name="sublet" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type <span class="text-red-500">*</span></label>
                <select name="type" id="stype" onchange="toggleSubletPrice(this)" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Workshop">Workshop</option><option value="Sublet">Sublet</option>
                    <option value="Warranty">Warranty</option><option value="Goodwill">Goodwill</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Qty <span class="text-red-500">*</span></label>
                <input type="number" name="qty" id="qty_input" min="1" value="1" required onchange="calcTotal()"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div id="price_section">
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
                        <input type="number" name="unitprice" id="up_input" step="0.01" min="0" value="0" onchange="calcTotal()"
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                        <input type="number" name="totalprice" id="total_input" step="0.01" readonly
                               class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-50">
                    </div>
                </div>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-plus mr-2"></i> Add Sublet
            </button>
        </form>
    </div>
    <div class="md:col-span-3 bg-white rounded shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-3">Current Sublets
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $sublets->count() }}</span>
        </h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sublet</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Unit</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($sublets as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $s->Sublet }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $s->type }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ $s->qty }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($s->unitprice,2) }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($s->total,2) }}</td>
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $s->status ?: 'Pending' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-4 text-center text-gray-400 text-sm italic">No sublets yet.</td></tr>
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
function toggleSubletPrice(sel) {
    document.getElementById('price_section').style.display = sel.value==='Workshop' ? '' : 'none';
    if (sel.value!=='Workshop') { document.getElementById('up_input').value=0; document.getElementById('total_input').value=0; }
}
document.addEventListener('DOMContentLoaded', function(){ toggleSubletPrice(document.getElementById('stype')); });
</script>
@endpush

@push('scripts')
<script>
// Delete item — matches original delete_labor.php
// POST: sid=id
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-item-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            if (!confirm('Delete this item?')) return;
            var postData = { _token: document.querySelector('meta[name=csrf-token]').content };
            postData['sid'] = this.dataset.id;
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
