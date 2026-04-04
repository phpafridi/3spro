@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Bank Receipt Voucher')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-file-invoice-dollar text-sky-500 mr-2"></i> Bank Receipt Voucher
    </h2>

    <form method="POST" action="{{ route('accounts.brv') }}">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Voucher Type</label>
                <input type="text" name="vouchertype" value="BRV" readonly
                    class="w-full border border-gray-200 bg-gray-50 rounded px-4 py-2 text-sm font-mono font-bold">
            </div>
            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Ref No <span class="text-xs text-green-600 font-normal">(auto-generated)</span></label>
                <input type="text" readonly value="{{ $previewRefNo ?? '' }}"
                    class="w-full border border-gray-200 bg-gray-100 rounded px-4 py-2 text-sm font-mono font-bold text-green-700">
                <p class="text-xs text-gray-400 mt-0.5">Assigned automatically on save</p>
                <input type="hidden" name="voucherno" value="">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Voucher Date <span class="text-red-500">*</span></label>
                <input type="date" name="voucher_date" required value="{{ date('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded px-4 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Book No</label>
                <input type="text" name="cash_book_no"
                    class="w-full border border-gray-300 rounded px-4 py-2 text-sm">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Payee</label>
                <input type="text" name="Payee" placeholder="Payee / party name"
                    class="w-full border border-gray-300 rounded px-4 py-2 text-sm">
            </div>
        </div>
        <button type="submit" class="mt-6 w-full py-2 bg-sky-600 hover:bg-sky-700 text-white rounded font-medium text-sm">
            Create Voucher &amp; Add Line Items →
        </button>
    </form>
</div>
@endsection
