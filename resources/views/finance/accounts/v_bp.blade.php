@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Bank Payment Voucher')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-2xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-file-invoice-dollar text-purple-500 mr-2"></i> Bank Payment Voucher
    </h2>

    <form method="POST" action="{{ route('accounts.bpv') }}">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Voucher Type</label>
                <input type="text" name="vouchertype" value="BPV" readonly
                    class="w-full border border-gray-200 bg-gray-50 rounded-xl px-4 py-2 text-sm font-mono font-bold">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ref No <span class="text-red-500">*</span></label>
                <input type="text" name="voucherno" required placeholder="e.g. 5BPV"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Voucher Date <span class="text-red-500">*</span></label>
                <input type="date" name="voucher_date" required value="{{ date('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Book No</label>
                <input type="text" name="cash_book_no"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Payee</label>
                <input type="text" name="Payee" placeholder="Payee / party name"
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 text-sm">
            </div>
        </div>
        <button type="submit" class="mt-6 w-full py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl font-medium text-sm">
            Create Voucher &amp; Add Line Items →
        </button>
    </form>
</div>
@endsection
