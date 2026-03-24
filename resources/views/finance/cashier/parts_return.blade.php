@extends('layouts.master')
@include('finance.cashier.sidebar')

@section('title', 'Cashier - Parts Return')

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-repeat text-orange-500 mr-2"></i>
            Parts Return
        </h2>
    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('cashier.parts-return') }}" class="mb-8">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1">
                <input type="text"
                       name="search"
                       value="{{ $search ?? '' }}"
                       placeholder="Enter search value..."
                       class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="w-64">
                <select name="field" class="w-full px-4 py-2 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select field...</option>
                    <optgroup label="Customer">
                        <option value="customer_data-Customer_name" {{ $field == 'customer_data-Customer_name' ? 'selected' : '' }}>Customer Name</option>
                        <option value="customer_data-mobile" {{ $field == 'customer_data-mobile' ? 'selected' : '' }}>Mobile</option>
                        <option value="customer_data-Customer_id" {{ $field == 'customer_data-Customer_id' ? 'selected' : '' }}>Customer Code</option>
                    </optgroup>
                    <optgroup label="Vehicle">
                        <option value="vehicles_data-Registration" {{ $field == 'vehicles_data-Registration' ? 'selected' : '' }}>Registration</option>
                        <option value="vehicles_data-Frame_no" {{ $field == 'vehicles_data-Frame_no' ? 'selected' : '' }}>Frame Number</option>
                        <option value="vehicles_data-Vehicle_id" {{ $field == 'vehicles_data-Vehicle_id' ? 'selected' : '' }}>Vehicle Code</option>
                    </optgroup>
                </select>
            </div>
            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded transition-colors">
                <i class="fas fa-search mr-2"></i> Search
            </button>
        </div>
    </form>

    @if(isset($results) && $results->isNotEmpty())
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name/Registration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Details</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($results as $result)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $result->Customer_id ?? $result->Vehicle_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $result->Customer_name ?? $result->Registration }}
                    </td>
                    <td class="px-6 py-4">
                        @if(isset($result->mobile))
                            <span class="text-sm">Mobile: {{ $result->mobile }}</span>
                        @else
                            <span class="text-sm">Model: {{ $result->Model ?? 'N/A' }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="{{ isset($result->Customer_id) ? route('cashier.history', ['Cust_id' => $result->Customer_id]) : route('cashier.history', ['veh_id' => $result->Vehicle_id]) }}"
                           class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-history mr-1"></i> View History
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @elseif(isset($search))
    <div class="text-center py-8 text-gray-500">
        <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
        <p>No results found for "{{ $search }}"</p>
    </div>
    @endif
</div>
@endsection
