@extends('layouts.master')
@section('title', 'Sales Vehicle — Dashboard')
@include('sales-vehicle.partials.sidebar')

@section('content')
<div class="space-y-6">

    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Sales Vehicle Dashboard</h1>
            <p class="text-sm text-gray-500 mt-1">New Car Inventory & Delivery Orders</p>
        </div>
        <a href="{{ route('sv.add-vehicle') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
            <i class="fas fa-plus"></i> Add Vehicle
        </a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide">In Stock</p>
            <p class="text-3xl font-bold text-green-600 mt-1">{{ $stats['in_stock'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Reserved</p>
            <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $stats['reserved'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Sold This Month</p>
            <p class="text-3xl font-bold text-blue-600 mt-1">{{ $stats['sold_month'] }}</p>
        </div>
        <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Pending DOs</p>
            <p class="text-3xl font-bold text-red-500 mt-1">{{ $stats['pending_do'] }}</p>
        </div>
    </div>

    {{-- Quick Links --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <a href="{{ route('sv.inventory') }}"
           class="flex items-center gap-3 bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600">
                <i class="fas fa-car"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Car Inventory</p>
                <p class="text-xs text-gray-400">All vehicles list</p>
            </div>
        </a>
        <a href="{{ route('sv.do-form') }}"
           class="flex items-center gap-3 bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-green-600">
                <i class="fas fa-file-alt"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">New DO</p>
                <p class="text-xs text-gray-400">Create delivery order</p>
            </div>
        </a>
        <a href="{{ route('sv.search-sold') }}"
           class="flex items-center gap-3 bg-white rounded-xl p-5 shadow-sm border border-gray-100 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-lg bg-purple-100 flex items-center justify-center text-purple-600">
                <i class="fas fa-search"></i>
            </div>
            <div>
                <p class="font-semibold text-gray-800 text-sm">Search Sold</p>
                <p class="text-xs text-gray-400">Find sold vehicles</p>
            </div>
        </a>
    </div>

    {{-- Recent DOs --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Recent Delivery Orders</h2>
            <a href="{{ route('sv.do-list') }}" class="text-blue-600 text-sm hover:underline">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                    <tr>
                        <th class="px-4 py-3 text-left">DO No</th>
                        <th class="px-4 py-3 text-left">Vehicle</th>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left">Payment</th>
                        <th class="px-4 py-3 text-right">Amount</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($recentDOs as $do)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono font-medium text-blue-700">{{ $do->do_no }}</td>
                        <td class="px-4 py-3">
                            @if($do->vehicle)
                                <span class="font-medium">{{ $do->vehicle->model }}</span>
                                <span class="text-gray-400 text-xs ml-1">{{ $do->vehicle->variant }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ $do->customer_name }}</td>
                        <td class="px-4 py-3">
                            @if($do->payment_type === 'Cash')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs">Cash</span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs">Installment</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-medium">{{ number_format($do->customer_paid_amount) }}</td>
                        <td class="px-4 py-3 text-center">
                            @php
                                $sc = ['Pending'=>'yellow','Approved'=>'blue','Delivered'=>'green','Cancelled'=>'red'];
                                $c = $sc[$do->status] ?? 'gray';
                            @endphp
                            <span class="px-2 py-0.5 bg-{{ $c }}-100 text-{{ $c }}-700 rounded text-xs">{{ $do->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $do->do_date?->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No delivery orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
