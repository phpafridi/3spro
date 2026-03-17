@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Parts Search')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-search text-indigo-500 mr-2"></i> Parts Search
    </h2>
    <form method="POST" action="{{ route('accountant.parts-search.redirect') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Type</label>
            <select name="field" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="counter-sale">Counter Sale Invoice</option>
                <option value="jobcard-closed">Jobcard (Closed)</option>
                <option value="purch-prof">Purchase Profile</option>
                <option value="purch-inv">Purchase Invoice</option>
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Value</label>
            <input type="text" name="search" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium">
            Search &amp; Open
        </button>
    </form>
    {{-- Cancel Parts --}}
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Cancel Issued Part / Consumable</h3>
        <form method="POST" action="{{ route('accountant.cancel-part') }}">
            @csrf
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Table</label>
                    <select name="cancel" class="w-full border border-gray-300 rounded px-2 py-1 text-sm">
                        <option value="jobc_parts">Parts</option>
                        <option value="jobc_consumble">Consumable</option>
                    </select>
                </div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Record ID</label>
                    <input type="text" name="id" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Part Number</label>
                    <input type="text" name="part_number" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Description</label>
                    <input type="text" name="cons_description" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Stock ID</label>
                    <input type="text" name="Stock_id" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Qty</label>
                    <input type="number" name="issued_qty" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Unit Price</label>
                    <input type="number" name="unitprice" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Issued By</label>
                    <input type="text" name="issue_by" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></div>
                <div><label class="block text-xs font-medium text-gray-600 mb-1">Issue Time</label>
                    <input type="text" name="issue_time" class="w-full border border-gray-300 rounded px-2 py-1 text-sm"></div>
            </div>
            <button type="submit" class="mt-4 px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium">
                Cancel Part & Restore Stock
            </button>
        </form>
    </div>
</div>
@endsection
