@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Add Debit Entry')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-2xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-minus-circle text-red-500 mr-2"></i> Debit Entry Form
    </h2>
    <form method="POST" action="{{ route('recovery.add-debt.store') }}">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="text" name="typeahead" id="typeahead" required
                    value="{{ $invoice->Customer_name ?? old('typeahead') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm"
                    placeholder="Start typing customer name...">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Contact</label>
                <input type="text" name="required_contact" value="{{ $invoice->mobile ?? '' }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle</label>
                <input type="text" name="vehicle" value="{{ $invoice->Variant ?? '' }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Registration</label>
                <input type="text" name="required_registration" value="{{ $invoice->Registration ?? '' }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Invoice No</label>
                <input type="text" name="required_invoice" required value="{{ $invoice->Invoice_id ?? '' }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input type="date" name="required_date" required
                    value="{{ isset($invoice->closing_time) ? date('Y-m-d', strtotime($invoice->closing_time)) : date('Y-m-d') }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Debt Amount (Rs)</label>
                <input type="number" name="required_amount" required value="{{ $invoice->Total ?? '' }}"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
            <div class="col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Followup Date</label>
                <input type="date" name="fallowup"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
            </div>
        </div>
        <button type="submit" class="mt-6 px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded font-medium">
            Add Debit Entry
        </button>
    </form>
    {{-- Check Invoice --}}
    <div class="mt-8 border-t pt-6">
        <h3 class="text-base font-semibold text-gray-700 mb-3">Load from Invoice</h3>
        <form method="GET" action="{{ route('recovery.add-debt') }}" class="flex gap-3">
            <input type="text" name="id" placeholder="Invoice ID"
                class="flex-1 border border-gray-300 rounded px-3 py-2 text-sm">
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm">Load</button>
        </form>
    </div>
</div>
@endsection
