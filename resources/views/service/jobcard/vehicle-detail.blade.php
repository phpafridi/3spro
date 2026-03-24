@extends('layouts.master')
@section('title', 'Vehicle Details')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
@endif
@if(session('error'))
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">{{ session('error') }}</div>
@endif

<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold text-gray-800">Vehicle Details</h2>
        <a href="{{ route('jobcard.add-vehicle') }}" class="text-sm text-gray-500 hover:text-gray-700">
            <i class="fa fa-arrow-left mr-1"></i> Back
        </a>
    </div>

    {{-- Model / Year warning --}}
    @if($vehicle)
        @if(!$vehicle->model_year)
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md text-sm text-red-700 font-semibold">
            <i class="fa fa-exclamation-triangle mr-1"></i> MODEL YEAR MISSING!
        </div>
        @endif
        @if(!$modelValid && $vehicle->Model)
        <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-md text-sm text-red-700 font-semibold">
            <i class="fa fa-exclamation-triangle mr-1"></i>
            Correct the Model Code <strong>{{ $vehicle->Model }}</strong> or add it in Variant Codes.
        </div>
        @endif
    @endif

    {{-- Customer + Vehicle table --}}
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Type</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Customer Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Contact #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Variant</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Reg #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Frame #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Model</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $row)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $row->cust_type }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $row->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $row->mobile }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $row->Variant }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $row->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600 font-mono text-xs">{{ $row->Frame_no }}</td>
                    <td class="px-4 py-3 text-sm text-gray-600">{{ $row->Model }}</td>
                    <td class="px-4 py-3 text-center">
                        <div class="flex flex-wrap gap-1 justify-center">
                        @if($vehicle && $vehicle->model_year && $modelValid)
                        <a href="{{ route('jobcard.create', ['vehicle_id' => $row->Vehicle_id, 'customer_id' => $row->Customer_id]) }}"
                           class="inline-block px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                            New Jobcard
                        </a>
                        @else
                        <span class="text-red-600 text-xs font-semibold">Correction Needed!</span>
                        @endif
                        {{-- Edit Customer per row — matches original --}}
                        <a href="{{ route('jobcard.customer.edit', $row->Customer_id) }}?vehicle_id={{ $vehicleId }}"
                           class="inline-block px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors">
                            Edit Customer
                        </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-6 text-center text-gray-400 text-sm">
                        No customer linked to this vehicle.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Action buttons row --}}
    <div class="flex flex-wrap gap-3">
        {{-- Add new customer --}}
        <a href="{{ route('jobcard.add-customer', ['vehicle_id' => $vehicleId]) }}"
           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-user-plus mr-1"></i> New Customer
        </a>

{{-- Edit Vehicle --}}
        <a href="{{ route('jobcard.vehicle.edit') }}?vehicle_id={{ $vehicleId }}"
           class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-pencil mr-1"></i> Edit Veh Data
        </a>

        {{-- Vehicle history --}}
        <form method="POST" action="{{ route('jobcard.history') }}">
            @csrf
            <input type="hidden" name="veh_id" value="{{ $vehicleId }}">
            <button type="submit"
                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-history mr-1"></i> Vehicle History
            </button>
        </form>

        {{-- Link additional customer --}}
        <a href="{{ route('jobcard.multi-customer') }}?vehicle_id={{ $vehicleId }}"
           class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-user-plus mr-1"></i> Link Customer
        </a>
    </div>
</div>
@endsection
