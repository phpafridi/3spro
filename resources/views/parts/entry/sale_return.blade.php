@extends('parts.layout')
@section('title', 'Sale Return - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Sale Return Form</h2>
    <p class="text-sm text-gray-500">SRJV #{{ $maxSRJV }}</p>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
@if($errors->any())<div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl">@foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach</div>@endif
<div class="max-w-2xl">
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
<form action="{{ route('parts.sale-return.store') }}" method="POST">
@csrf
<input type="hidden" name="SRJV" value="{{ $maxSRJV }}">
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sale Invoice Number (GRN) <span class="text-red-500">*</span></label>
        <input type="text" name="GRN" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stock ID <span class="text-red-500">*</span></label>
        <input type="text" name="required_stock_id" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Sell ID <span class="text-red-500">*</span></label>
        <input type="text" name="sell_id" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div class="grid grid-cols-2 gap-3">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price</label>
            <input type="number" name="unit_price" step="0.01" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Return Qty <span class="text-red-500">*</span></label>
            <input type="number" name="required_qty" required min="1" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Return By <span class="text-red-500">*</span></label>
        <input type="text" name="return_by" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
        <input type="text" name="reason" class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
    </div>
</div>
<div class="mt-6">
    <button type="submit" class="w-full bg-gradient-to-r from-red-500 to-rose-600 text-white py-2.5 rounded-xl font-medium hover:from-red-600 hover:to-rose-700 transition-all">Record Sale Return</button>
</div>
</form>
</div>
</div>
@endsection
