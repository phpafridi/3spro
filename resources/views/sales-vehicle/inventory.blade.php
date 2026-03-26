@extends('layouts.master')
@section('title', 'Car Inventory')
@include('sales-vehicle.partials.sidebar')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-800">Car Inventory</h1>
        <a href="{{ route('sv.add-vehicle') }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
            <i class="fas fa-plus"></i> Add Vehicle
        </a>
    </div>

    @if(session('success'))
        <div class="p-3 bg-green-100 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
    @endif

    {{-- Filters --}}
    <form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        <div class="flex flex-wrap gap-3">
            <input type="text" name="q" value="{{ $q }}" placeholder="Search VIN, model, color…"
                   class="flex-1 min-w-[180px] border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Status</option>
                @foreach(['In Stock','Reserved','Sold','In Transit'] as $s)
                    <option value="{{ $s }}" {{ $status === $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            <select name="model" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Models</option>
                @foreach($models as $m)
                    <option value="{{ $m }}" {{ $model === $m ? 'selected' : '' }}>{{ $m }}</option>
                @endforeach
            </select>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition">
                <i class="fas fa-search mr-1"></i> Filter
            </button>
            <a href="{{ route('sv.inventory') }}"
               class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm rounded-lg transition">Reset</a>
        </div>
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-xs text-gray-500 uppercase border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">VIN / Frame</th>
                        <th class="px-4 py-3 text-left">Model</th>
                        <th class="px-4 py-3 text-left">Variant</th>
                        <th class="px-4 py-3 text-left">Color</th>
                        <th class="px-4 py-3 text-left">Year</th>
                        <th class="px-4 py-3 text-right">List Price</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-left">Location</th>
                        <th class="px-4 py-3 text-left">Arrival</th>
                        <th class="px-4 py-3 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($vehicles as $v)
                    @php
                        $sc = ['In Stock'=>'green','Reserved'=>'yellow','Sold'=>'gray','In Transit'=>'blue'];
                        $c  = $sc[$v->status] ?? 'gray';
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3 text-gray-400">{{ $vehicles->firstItem() + $loop->index }}</td>
                        <td class="px-4 py-3 font-mono text-xs font-semibold text-gray-700">{{ $v->vin }}</td>
                        <td class="px-4 py-3 font-medium">{{ $v->model }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $v->variant }}</td>
                        <td class="px-4 py-3">{{ $v->color }}</td>
                        <td class="px-4 py-3">{{ $v->model_year }}</td>
                        <td class="px-4 py-3 text-right font-medium">{{ number_format($v->list_price) }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-2 py-0.5 bg-{{ $c }}-100 text-{{ $c }}-700 rounded text-xs">{{ $v->status }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-500">{{ $v->location }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $v->arrival_date?->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('sv.edit-vehicle', $v->id) }}"
                                   class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs hover:bg-yellow-200 transition">
                                    <i class="fas fa-edit"></i>
                                </a>
                                @if(in_array($v->status, ['In Stock','Reserved']))
                                <a href="{{ route('sv.do-form', ['vehicle_id' => $v->id]) }}"
                                   class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200 transition">
                                    <i class="fas fa-file-alt"></i> DO
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="px-4 py-10 text-center text-gray-400">
                            No vehicles found. <a href="{{ route('sv.add-vehicle') }}" class="text-blue-600 underline">Add one.</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($vehicles->hasPages())
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $vehicles->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
