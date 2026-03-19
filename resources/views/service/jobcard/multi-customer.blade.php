@extends('layouts.master')
@section('title', 'Link Customer to Vehicle')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Link Existing Customer to Vehicle</h2>
            <a href="{{ route('jobcard.vehicle-detail', ['vehicle_id' => $vehicleId]) }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
        @endif

        <p class="text-sm text-gray-500 mb-4">
            Search and select an existing customer to link them to this vehicle.
        </p>

        <form action="{{ route('jobcard.multi-customer.store') }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicleId }}">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Search Customer <span class="text-red-500">*</span>
                </label>
                <input type="text" id="cust_search" placeholder="Type name or mobile to filter..."
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2">
                <select name="customer_id" required id="cust_select"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        size="8">
                    @foreach($customers as $c)
                    <option value="{{ $c->Customer_id }}">
                        {{ $c->Customer_name }} — {{ $c->mobile }} ({{ $c->cust_type }})
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                    <i class="fa fa-link mr-2"></i> Link Customer to Vehicle
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('cust_search').addEventListener('keyup', function () {
    const val = this.value.toLowerCase();
    Array.from(document.getElementById('cust_select').options).forEach(opt => {
        opt.hidden = !opt.text.toLowerCase().includes(val);
    });
});
</script>
@endpush
@endsection
