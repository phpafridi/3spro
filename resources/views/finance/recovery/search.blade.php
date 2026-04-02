@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Search Customer')
@section('content')
<div class="bg-white rounded shadow-sm p-6 max-w-xl">
    <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-search text-red-500"></i> Search Customer
    </h2>

    <form method="POST" action="{{ route('recovery.search') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                <input type="text" name="typeahead" id="typeahead" required
                    value="{{ old('typeahead', $name ?? '') }}"
                    placeholder="Start typing name..."
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-red-400 outline-none">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Go to</label>
                <select name="required_search" class="w-full border border-gray-300 rounded px-3 py-2 text-sm">
                    <option value="ledger">Customer Ledger</option>
                    <option value="cust_clear">Clearance</option>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded text-sm font-semibold">
                Search
            </button>
        </div>
    </form>

    {{-- Disambiguation: multiple customers share this name --}}
    @if(isset($ids) && $ids->count() > 1)
    <div class="mt-6 border-t pt-5">
        <p class="text-sm font-semibold text-orange-700 mb-3">
            <i class="fas fa-exclamation-triangle mr-1"></i>
            Multiple customers found with the name "<strong>{{ $name }}</strong>". Select the correct one:
        </p>
        <div class="space-y-2">
            @foreach($ids as $c)
            <div class="flex items-center justify-between border border-gray-200 rounded px-4 py-3 hover:bg-gray-50">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ $c->cust_name }}</p>
                    <p class="text-xs text-gray-500">Contact: {{ $c->contact ?: '—' }} &nbsp;·&nbsp; Reg: {{ $c->Registration ?: '—' }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('recovery.customer-ledger', ['id' => $c->Customer_id]) }}"
                       class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200 font-medium">Ledger</a>
                    <a href="{{ route('recovery.clearance', ['id' => $c->Customer_id]) }}"
                       class="px-3 py-1 bg-gray-100 text-gray-700 rounded text-xs hover:bg-gray-200 font-medium">Clearance</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
