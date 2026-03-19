@extends('layouts.master')
@section('title', 'Estimate Labor — #' . $estmId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- ADD FORM --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Add Labor — Estimate #{{ $estmId }}</h2>

        <form method="POST" action="{{ route('jobcard.estimate.labor.store') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="job_id" value="{{ $estmId }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Labor <span class="text-red-500">*</span></label>
                <select name="jobrequest" id="labourList" required onchange="getprice()"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Labor --</option>
                    @foreach($laborList as $l)
                    <option value="{{ $l->Labor }}">{{ $l->Labor }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
                <input type="number" name="price" id="labor_cost" step="0.01" min="0" value="0"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                Add Labor
            </button>
        </form>

        {{-- Navigation to other estimate sections --}}
        <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('jobcard.estimate.labor', $estmId) }}"
               class="px-3 py-1 bg-yellow-500 text-white text-xs rounded">Labor</a>
            <a href="{{ route('jobcard.estimate.part', $estmId) }}"
               class="px-3 py-1 bg-yellow-500 text-white text-xs rounded">Parts</a>
            <a href="{{ route('jobcard.estimate.sublet', $estmId) }}"
               class="px-3 py-1 bg-yellow-500 text-white text-xs rounded">Sublet</a>
            <a href="{{ route('jobcard.estimate.consumable', $estmId) }}"
               class="px-3 py-1 bg-yellow-500 text-white text-xs rounded">Consumble</a>
        </div>
    </div>

    {{-- EXISTING ITEMS --}}
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-3">Labor Estimated — Estm #{{ $estmId }}</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Delete</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php $total = 0; @endphp
                @forelse($labors as $i => $l)
                @php $total += $l->cost; @endphp
                <tr>
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-sm">{{ $l->Labor }}</td>
                    <td class="px-3 py-2 text-sm">{{ number_format($l->cost, 0) }}</td>
                    <td class="px-3 py-2">
                        <button class="delete-btn px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded"
                                data-id="{{ $l->est_lab_id }}">Delete</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm">No labor added yet.</td></tr>
                @endforelse
                @if($labors->count())
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="2" class="px-3 py-2 text-right text-sm">Total</td>
                    <td class="px-3 py-2 text-sm">{{ number_format($total, 0) }}</td>
                    <td></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
// Auto-fill labor price — matches original files/Labor_cost.php
function getprice() {
    var partn   = document.getElementById('labourList').value;
    var variant = '{{ $estimate->variant ?? "" }}';
    if (!partn) return;
    $.ajax({
        type:    'POST',
        url:     '{{ route("jobcard.ajax.labor-cost") }}',
        data:    { _token: '{{ csrf_token() }}', partn: partn, variant: variant, type: 'Workshop' },
        success: function (price) {
            if (parseFloat(price) > 0) document.getElementById('labor_cost').value = price;
        }
    });
}

// Delete labor — matches original files/del_est_labor.php: POST id=est_lab_id
document.querySelectorAll('.delete-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        if (!confirm('Delete this item?')) return;
        $.ajax({
            type: 'POST',
            url:  '{{ route("jobcard.delete-estimate-item") }}',
            data: { _token: '{{ csrf_token() }}', id: this.dataset.id },
            success: function () { location.reload(); }
        });
    });
});
</script>
@endpush
@endsection
