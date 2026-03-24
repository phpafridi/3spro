@extends('layouts.master')
@include('finance.cashier.sidebar')

@section('title', 'Cashier - Vehicle History')

@push('styles')
<style>
    .history-table td {
        vertical-align: top;
        padding: 1rem;
    }
    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 0.5rem;
    }
    .details-table td {
        border: 1px solid #e5e7eb;
        padding: 0.5rem;
    }
    .details-header {
        background-color: #f3f4f6;
        font-weight: 600;
        text-align: center;
    }
</style>
@endpush

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-history text-purple-500 mr-2"></i>
            Vehicle History
        </h2>
        <a href="{{ route('cashier.search') }}" class="text-blue-600 hover:text-blue-800">
            <i class="fas fa-arrow-left mr-1"></i> Back to Search
        </a>
    </div>

    @forelse($history as $record)
    <div class="mb-8 border border-gray-200 rounded overflow-hidden">
        <!-- Main Job Card Info -->
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs opacity-75">Job Card #</p>
                    <p class="text-lg font-bold">
                        <a href="{{ route('cashier.print-initial-ro', ['job_id' => $record->Jobc_id]) }}"
                           target="_blank"
                           class="text-white hover:text-yellow-300 underline">
                            #{{ $record->Jobc_id }}
                        </a>
                    </p>
                </div>
                <div>
                    <p class="text-xs opacity-75">Customer</p>
                    <p class="font-semibold">{{ $record->Customer_name }}</p>
                </div>
                <div>
                    <p class="text-xs opacity-75">Registration</p>
                    <p class="font-semibold">{{ $record->Veh_reg_no }}</p>
                </div>
                <div>
                    <p class="text-xs opacity-75">Mileage / SA</p>
                    <p>{{ $record->Mileage }} / {{ $record->SA }}</p>
                </div>
            </div>
        </div>

        <!-- VOC (Customer Complaint) -->
        <div class="bg-yellow-100 p-3 text-sm">
            <span class="font-semibold">VOC:</span> {{ $record->VOC }}
        </div>

        <!-- Details Table -->
        <div class="p-4">
            <table class="details-table">
                <thead>
                    <tr>
                        <td class="details-header">Labor</td>
                        <td class="details-header">Parts</td>
                        <td class="details-header">Consumable</td>
                        <td class="details-header">Sublet</td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <!-- Labor -->
                        <td class="align-top">
                            {!! nl2br($record->labor ?? '-') !!}
                        </td>
                        <td class="align-top">
                            {!! nl2br($record->parts ?? '-') !!}
                        </td>
                        <td class="align-top">
                            {!! nl2br($record->consumable ?? '-') !!}
                        </td>
                        <td class="align-top">
                            {!! nl2br($record->sublet ?? '-') !!}
                        </td>
                    </tr>
                    <tr class="bg-gray-50 font-semibold">
                        <td>{{ number_format($record->labor_cost ?? 0, 0) }}</td>
                        <td>{{ number_format($record->parts_cost ?? 0, 0) }}</td>
                        <td>{{ number_format($record->consumable_cost ?? 0, 0) }}</td>
                        <td>{{ number_format($record->sublet_cost ?? 0, 0) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    @empty
    <div class="text-center py-12">
        <i class="fas fa-history text-5xl text-gray-300 mb-4"></i>
        <p class="text-gray-500">No history found for this vehicle/customer</p>
    </div>
    @endforelse
</div>
@endsection
