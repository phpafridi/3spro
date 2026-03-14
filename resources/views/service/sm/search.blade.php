@extends('layouts.master')
@section('title', 'SM - Search / Print')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
<div class="max-w-lg mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Search &amp; Print</h2>
        <form method="POST" action="{{ route('sm.search') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Search Type</label>
                <select name="field" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <option value="jobcard-instail">Jobcard (Instail / Open)</option>
                    <option value="jobcard-closed">Jobcard (Closed)</option>
                    <option value="Invoice">Invoice</option>
                    <option value="SalesTax">Sales Tax Invoice</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">RO / Invoice No</label>
                <input type="text" name="search" required placeholder="Enter RO or Invoice number..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-print mr-2"></i> Search &amp; Open Print
            </button>
        </form>
    </div>
</div>
@endsection
