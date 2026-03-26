@extends('layouts.master')
@section('title', 'Search Sold Cars')
@include('sales-vehicle.partials.sidebar')

@section('content')
<div class="space-y-5">

    <h1 class="text-2xl font-bold text-gray-800">Search Sold Cars</h1>

    {{-- Search Box --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
        <label class="block text-sm font-medium text-gray-700 mb-2">
            Search by VIN, Model, Color, Engine No, Customer Name, CNIC, Phone, or DO No
        </label>
        <div class="flex gap-3">
            <input type="text" name="q" value="{{ $q }}" autofocus
                   placeholder="e.g. MH5A…  or  Ali Khan  or  DO-2026-0012"
                   class="flex-1 border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
            <button type="submit"
                    class="px-5 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-medium rounded-lg transition">
                <i class="fas fa-search mr-2"></i>Search
            </button>
        </div>
    </form>

    {{-- Results --}}
    @if($q)
        <p class="text-sm text-gray-500">
            Found <strong>{{ $results->count() }}</strong> result(s) for "<strong>{{ $q }}</strong>"
        </p>

        @if($results->isEmpty())
            <div class="bg-white rounded-xl p-10 text-center text-gray-400 border border-gray-100">
                <i class="fas fa-car text-4xl mb-3 opacity-30"></i>
                <p>No sold vehicles found matching your search.</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($results as $v)
                @php $do = $v->latestDO; @endphp
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
                    <div class="flex items-start justify-between gap-4 flex-wrap">
                        {{-- Vehicle Info --}}
                        <div class="flex-1 min-w-[200px]">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-0.5 bg-gray-200 text-gray-600 rounded text-xs font-medium">SOLD</span>
                                <span class="font-bold text-gray-800 text-lg">{{ $v->model }} {{ $v->variant }}</span>
                                <span class="text-gray-400 text-sm">{{ $v->model_year }}</span>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-1 text-sm mt-2">
                                <div>
                                    <span class="text-gray-400 text-xs">VIN</span>
                                    <p class="font-mono font-semibold text-gray-700">{{ $v->vin }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-xs">Engine No</span>
                                    <p class="font-mono font-semibold text-gray-700">{{ $v->engine_no ?: '—' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-xs">Color</span>
                                    <p class="font-medium text-gray-700">{{ $v->color ?: '—' }}</p>
                                </div>
                                <div>
                                    <span class="text-gray-400 text-xs">Transmission</span>
                                    <p class="font-medium text-gray-700">{{ $v->transmission }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($do)
                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs text-gray-400 uppercase font-semibold tracking-wide mb-2">
                            Latest Delivery Order — {{ $do->do_no }}
                        </p>
                        <div class="grid grid-cols-2 md:grid-cols-5 gap-x-6 gap-y-1 text-sm">
                            <div>
                                <span class="text-gray-400 text-xs">Customer</span>
                                <p class="font-semibold text-gray-800">{{ $do->customer_name }}</p>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs">Phone</span>
                                <p class="font-medium text-gray-700">{{ $do->customer_phone }}</p>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs">CNIC</span>
                                <p class="font-medium text-gray-700">{{ $do->customer_cnic ?: '—' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs">Payment</span>
                                @if($do->payment_type === 'Cash')
                                    <p class="font-medium text-green-700">Cash — PKR {{ number_format($do->cash_received) }}</p>
                                @else
                                    <p class="font-medium text-blue-700">
                                        Installment<br>
                                        <span class="text-xs">{{ $do->bank_name }} | {{ $do->tenure_months }}m</span>
                                    </p>
                                @endif
                            </div>
                            <div>
                                <span class="text-gray-400 text-xs">Sale Price</span>
                                <p class="font-bold text-gray-800">PKR {{ number_format($do->agreed_price) }}</p>
                            </div>
                        </div>
                        @if($do->customer_address)
                        <p class="text-xs text-gray-400 mt-2"><i class="fas fa-map-marker-alt mr-1"></i>{{ $do->customer_address }}</p>
                        @endif
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        @endif
    @endif
</div>
@endsection
