@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Search Customer')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-search text-indigo-500 mr-2"></i> Search Customer
    </h2>
    <form method="POST" action="{{ route('recovery.search') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Type</label>
                <select name="required_search" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="cust_ledger">Customer Ledger</option>
                    <option value="cust_clear">Customer Clearance</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="text" name="typeahead" required id="custSearch" autocomplete="off"
                    placeholder="Type customer name..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
            </div>
        </div>
        <button type="submit" class="mt-6 w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium">
            Search
        </button>
    </form>
    <div class="mt-4 text-center">
        <a href="{{ route('recovery.search-adv') }}" class="text-sm text-indigo-600 hover:underline">
            Advanced Search →
        </a>
    </div>
</div>
@endsection
