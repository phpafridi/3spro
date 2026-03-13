@extends('parts.layout')
@section('title', 'Search - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Search & Print</h2>
</div>
@if(session('error'))<div class="mb-4 p-4 bg-rose-50 border-l-4 border-rose-500 text-rose-700 rounded-r-xl">{{ session('error') }}</div>@endif
<div class="max-w-xl">
<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
<form action="{{ route('parts.search.redirect') }}" method="POST">
@csrf
<div class="space-y-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Search Type <span class="text-red-500">*</span></label>
        <select name="field" required class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
            <option value="">-- Select Type --</option>
            <option value="counter-sale">Counter Sale Invoice</option>
            <option value="purch-inv">Purchase Invoice</option>
            <option value="purch-inv-tax">Purchase Invoice (With Tax)</option>
            <option value="PRJV">Purchase Return (PRJV)</option>
            <option value="SRJV">Sale Return (SRJV)</option>
            <option value="WPR">Workshop Parts Return (WPR)</option>
            <option value="Payment">Vendor Payment</option>
        </select>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Invoice / Reference Number <span class="text-red-500">*</span></label>
        <input type="text" name="search" required
               class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500" placeholder="Enter number...">
    </div>
</div>
<div class="mt-6">
    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all">
        <i class="fa fa-search mr-2"></i>Search & Print
    </button>
</div>
</form>
</div>
</div>
@endsection
