@extends('layouts.master')
@section('title', 'Delivery Orders')
@include('sales-vehicle.partials.sidebar')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Delivery Orders</h1>
        <a href="{{ route('sv.do-form') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition">
            <i class="fas fa-plus"></i> New DO
        </a>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ $q }}" placeholder="DO No, customer name, CNIC, phone…"
                   class="flex-1 min-w-[200px] border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                @foreach(['Pending','Approved','Delivered','Cancelled'] as $s)
                    <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('sv.do-list') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition">Reset</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">DO No</th>
                        <th class="px-4 py-3 text-left">Vehicle</th>
                        <th class="px-4 py-3 text-left">Customer</th>
                        <th class="px-4 py-3 text-left">Payment</th>
                        <th class="px-4 py-3 text-right">Paid Amount</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-left">DO Date</th>
                        <th class="px-4 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($orders as $do)
                    @php
                        $sc = ['Pending'=>'yellow','Approved'=>'blue','Delivered'=>'green','Cancelled'=>'red'];
                        $c  = $sc[$do->status] ?? 'gray';
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 font-mono font-semibold text-blue-700 whitespace-nowrap">{{ $do->do_no }}</td>
                        <td class="px-4 py-3">
                            @if($do->vehicle)
                                <p class="font-medium">{{ $do->vehicle->model }} {{ $do->vehicle->variant }}</p>
                                <p class="text-xs text-gray-400 font-mono">{{ $do->vehicle->vin }}</p>
                            @else
                                <span class="text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <p class="font-medium">{{ $do->customer_name }}</p>
                            <p class="text-xs text-gray-400">{{ $do->customer_phone }}</p>
                        </td>
                        <td class="px-4 py-3">
                            @if($do->payment_type === 'Cash')
                                <span class="px-2 py-0.5 bg-green-100 text-green-700 rounded text-xs font-medium">Cash</span>
                            @elseif($do->payment_type === 'Direct')
                                <span class="px-2 py-0.5 bg-purple-100 text-purple-700 rounded text-xs font-medium">Direct</span>
                            @else
                                <span class="px-2 py-0.5 bg-blue-100 text-blue-700 rounded text-xs font-medium">Bank Finance</span>
                                @if($do->bank_name)
                                    <p class="text-xs text-gray-400 mt-0.5">{{ $do->bank_name }}</p>
                                @endif
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-semibold">{{ number_format($do->customer_paid_amount) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 bg-{{ $c }}-100 text-{{ $c }}-700 rounded text-xs font-medium">{{ $do->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500 whitespace-nowrap">{{ $do->do_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($do->status === 'Pending')
                            <div class="flex items-center justify-center gap-1">
                                <form method="POST" action="{{ route('sv.do-status') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="do_id" value="{{ $do->id }}">
                                    <input type="hidden" name="action" value="Approved">
                                    <button type="submit"
                                            class="px-2 py-1 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded text-xs transition"
                                            onclick="return confirm('Approve this DO?')">Approve</button>
                                </form>
                                <form method="POST" action="{{ route('sv.do-status') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="do_id" value="{{ $do->id }}">
                                    <input type="hidden" name="action" value="Cancelled">
                                    <button type="submit"
                                            class="px-2 py-1 bg-red-100 text-red-700 hover:bg-red-200 rounded text-xs transition"
                                            onclick="return confirm('Cancel this DO?')">Cancel</button>
                                </form>
                            </div>
                            @elseif($do->status === 'Approved')
                            <form method="POST" action="{{ route('sv.do-status') }}" class="inline">
                                @csrf
                                <input type="hidden" name="do_id" value="{{ $do->id }}">
                                <input type="hidden" name="action" value="Delivered">
                                <button type="submit"
                                        class="px-2 py-1 bg-green-100 text-green-700 hover:bg-green-200 rounded text-xs transition"
                                        onclick="return confirm('Mark as Delivered? Vehicle will be marked Sold.')">
                                    <i class="fas fa-check mr-1"></i>Delivered
                                </button>
                            </form>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-4 py-10 text-center text-gray-400">No delivery orders found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($orders->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">{{ $orders->links() }}</div>
        @endif
    </div>
</div>
@endsection
