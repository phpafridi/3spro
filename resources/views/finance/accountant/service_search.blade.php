@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Service Search')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-search-plus text-indigo-500 mr-2"></i> Service Search
    </h2>
    <form method="POST" action="{{ route('accountant.service-search.redirect') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Type</label>
            <select name="field" required class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <option value="jobcard-instail">Jobcard (Initial / Open)</option>
                <option value="jobcard-closed">Jobcard (Closed)</option>
                <option value="Invoice">Invoice Number</option>
                <option value="SalesTax">Sales Tax Invoice</option>
            </select>
        </div>
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-1">Search Value</label>
            <input type="text" name="search" required placeholder="Enter JC / Invoice number"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        </div>
        <button type="submit" class="w-full py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-medium">
            Search &amp; Open
        </button>
    </form>
</div>
@endsection
