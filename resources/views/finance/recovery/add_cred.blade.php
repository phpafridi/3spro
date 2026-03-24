@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Add Credit Entry')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-plus-circle text-green-500 mr-2"></i> Credit Entry Form
    </h2>
    <form method="POST" action="{{ route('recovery.add-credit.store') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">DM Invoice No</label>
                <input type="text" name="required_dm" required value="{{ $prefill->dm_invoice ?? ($prefill->Invoice_no ?? '') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method</label>
                <select name="required_payment_method" required class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="">-- Select --</option>
                    @foreach(['Cash','Cheque','Online Transfer','IBFT','Bank Draft'] as $m)
                    <option value="{{ $m }}" {{ isset($prefill->Payment_method) && $prefill->Payment_method==$m ? 'selected':'' }}>{{ $m }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">RT / Reference No</label>
                <input type="text" name="required_rt" value="{{ $prefill->RT_no ?? '' }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="required_date" required value="{{ $prefill->cr_date ?? date('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount (Rs)</label>
                <input type="number" name="required_amount" required value="{{ $prefill->cr_amount ?? '' }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="2" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">{{ $prefill->remarks ?? '' }}</textarea>
            </div>
        </div>
        <button type="submit" class="mt-6 px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-medium">
            Add Credit Entry
        </button>
    </form>
</div>
@endsection
