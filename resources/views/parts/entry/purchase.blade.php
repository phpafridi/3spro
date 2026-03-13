{{-- resources/views/parts/entry/purchase.blade.php --}}
@extends('parts.layout')

@section('title', 'Purchase - Parts')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">New Purchase Invoice</h2>
    <p class="text-sm text-gray-500 mt-1">Create a new purchase invoice from a vendor/jobber</p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">
        {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl">
        {{ session('error') }}
    </div>
@endif

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-5">Invoice Details</h3>

        <form action="{{ route('parts.purchase.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jobber / Vendor <span class="text-red-500">*</span>
                    </label>
                    <select name="required_jobber" id="required_jobber" required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Select Jobber --</option>
                        @foreach($jobbers as $jobber)
                            <option value="{{ $jobber->jbr_name }}">{{ $jobber->jbr_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Bill Number <span id="billStatus" class="ml-2 text-sm"></span>
                    </label>
                    <input type="text" name="invo" id="billNumber"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="Enter bill/invoice number">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Purchase Requisition <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="required_preq" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                           placeholder="PR number">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select name="required_payment_method" required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">-- Select --</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Credit">Credit</option>
                        <option value="Online Transfer">Online Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Date</label>
                    <input type="date" name="mdate" value="{{ date('Y-m-d') }}"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Note</label>
                    <input type="text" name="deleverynote"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Consignment Note</label>
                    <input type="text" name="consignmentnote"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Receiver Name</label>
                    <input type="text" name="Receivername"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all">
                    Create Invoice &rarr; Add Parts
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('billNumber').addEventListener('blur', function() {
    const nic    = this.value;
    const jobber = document.getElementById('required_jobber').value;
    if (!nic || !jobber) return;

    const statusEl = document.getElementById('billStatus');
    statusEl.innerHTML = '<span class="text-gray-400">Checking...</span>';

    fetch('{{ route("parts.ajax.check-invoice") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ NIC: nic, jobber: jobber })
    })
    .then(r => r.text())
    .then(msg => {
        statusEl.innerHTML = msg === 'OK'
            ? '<span class="text-green-600">&#10003; Available</span>'
            : '<span class="text-red-600">' + msg + '</span>';
    });
});
</script>
@endpush
