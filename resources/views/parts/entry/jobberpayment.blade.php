{{-- resources/views/parts/entry/jobberpayment.blade.php --}}
@extends('parts.layout')

@section('title', 'Vendor Payments - Parts')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Vendor / Jobber Payments</h2>
    <p class="text-sm text-gray-500 mt-1">Record payment to or receipt from a vendor</p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">
        {{ session('success') }}
    </div>
@endif
@if($errors->any())
    <div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl">
        @foreach($errors->all() as $e) <p>{{ $e }}</p> @endforeach
    </div>
@endif

<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('parts.jobber-payment.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jobber / Vendor <span class="text-red-500">*</span></label>
                    <select name="jobber" required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Select Vendor --</option>
                        @foreach($jobbers as $jobber)
                            <option value="{{ $jobber->jobber_id }}">{{ $jobber->jbr_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Type <span class="text-red-500">*</span></label>
                    <select name="trans_type" required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Select --</option>
                        <option value="Paid">Paid (Payment to Vendor)</option>
                        <option value="Received">Received (Receipt from Vendor)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                    <input type="number" name="amount" required min="0.01" step="0.01"
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500"
                           placeholder="0.00">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                    <select name="payment_method" required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Select --</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Online Transfer">Online Transfer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Received / Paid By <span class="text-red-500">*</span></label>
                    <input type="text" name="rec_paid_by" required
                           class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500"
                           placeholder="Person name">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                    <textarea name="remarks" rows="2"
                              class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500"
                              placeholder="Optional remarks"></textarea>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all">
                    Record Payment
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
