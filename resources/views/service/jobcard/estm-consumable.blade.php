@extends('layouts.master')
@section('title', 'Estimate Consumable — #' . $estmId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white rounded shadow-sm p-6">
        <h2 class="text-lg font-semibold text-gray-800 mb-4">Add Consumable — Estimate #{{ $estmId }}</h2>

        <form method="POST" action="{{ route('jobcard.estimate.consumable.store') }}" class="space-y-3">
            @csrf
            <input type="hidden" name="job_id" value="{{ $estmId }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Consumable <span class="text-red-500">*</span></label>
                <input type="text" name="part_description" required style="text-transform:uppercase"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Qty <span class="text-red-500">*</span></label>
                    <input type="number" name="qty" id="qty" required min="1" oninput="calculate()"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price <span class="text-red-500">*</span></label>
                    <input type="number" name="unitprice" id="unitprice" required min="0" step="0.01" oninput="calculate()"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Total</label>
                    <input type="number" name="totalprice" id="total" readonly
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm bg-gray-50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                <select name="type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    
                    <option value="Local">Local</option>
                </select>
            </div>
            <button type="submit"
                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                Add Consumable
            </button>
        </form>

        <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
            <a href="{{ route('jobcard.estimate.labor', $estmId) }}"      class="px-3 py-1 bg-yellow-500 text-white text-xs rounded">Labor</a>
            <a href="{{ route('jobcard.estimate.part', $estmId) }}"       class="px-3 py-1 bg-yellow-500 text-white text-xs rounded">Parts</a>
            <a href="{{ route('jobcard.estimate.sublet', $estmId) }}"     class="px-3 py-1 bg-yellow-500 text-white text-xs rounded">Sublet</a>
            <a href="{{ route('jobcard.estimate.consumable', $estmId) }}" class="px-3 py-1 bg-yellow-500 text-white text-xs rounded">Consumble</a>
        </div>
    </div>

    <div class="bg-white rounded shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-3">Consumable Requested — Estm #{{ $estmId }}</h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Del</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @php $total = 0; @endphp
                @forelse($consumbles as $i => $c)
                @php $total += $c->total; @endphp
                <tr>
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $i+1 }}</td>
                    <td class="px-3 py-2 text-sm">{{ $c->part_description }}</td>
                    <td class="px-3 py-2 text-sm">{{ $c->qty }}</td>
                    <td class="px-3 py-2 text-sm">{{ number_format($c->total, 0) }}</td>
                    <td class="px-3 py-2">
                        <button class="delete-btn px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded"
                                data-cnid="{{ $c->estm_part_id }}">Del</button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="px-3 py-4 text-center text-gray-400 text-sm">No consumables added yet.</td></tr>
                @endforelse
                @if($consumbles->count())
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="3" class="px-3 py-2 text-right text-sm">Total</td>
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
function calculate() {
    var qty   = parseFloat(document.getElementById('qty').value) || 0;
    var price = parseFloat(document.getElementById('unitprice').value) || 0;
    document.getElementById('total').value = (qty * price).toFixed(2);
}
document.querySelectorAll('.delete-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
        if (!confirm('Delete?')) return;
        $.ajax({
            type: 'POST', url: '{{ route("jobcard.delete-estimate-item") }}',
            data: { _token: '{{ csrf_token() }}', cnid: this.dataset.cnid },
            success: function () { location.reload(); }
        });
    });
});
</script>
@endpush
@endsection
