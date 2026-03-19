@extends('layouts.master')
@section('title', 'Add Customer Details')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if($errors->any())
<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Add Customer Details</h2>
            <a href="{{ route('jobcard.vehicle-detail', ['vehicle_id' => $vehicleId]) }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        {{-- Form submits to storeCustomer → then to vehicle-detail (jobcard_2 equivalent) --}}
        <form id="myform" action="{{ route('jobcard.add-customer.store') }}" method="POST"
              class="space-y-4" novalidate>
            @csrf
            <input type="hidden" name="vehicle_id" value="{{ $vehicleId }}">
            <input type="hidden" name="customer_data" value="">

            {{-- Customer Type --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Type</label>
                <select name="cust_type"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option>Individuals</option>
                    <option>Govt</option>
                    <option>Force</option>
                    <option>Corporate</option>
                    <option>Banks</option>
                    <option>Investor</option>
                    <option>Others</option>
                </select>
            </div>

            {{-- Customer Name — id="countryname_1" for auto.js --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="countryname_1" required minlength="3"
                       style="text-transform:uppercase"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                {{-- Hidden field for auto.js customer lookup --}}
                <input type="hidden" id="country_no_1" name="cust_id">
            </div>

            {{-- Primary Contact — id="phone_code_1" --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Primary Contact # <span class="text-red-500">*</span></label>
                <input type="number" name="mobile" id="phone_code_1" required
                       value="03" minlength="10" maxlength="11"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Secondary Contact --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Secondary Contact #</label>
                <input type="number" name="off_phone" id="number"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- City & Region — exact same dropdowns as original --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">City &amp; Region</label>
                <div class="grid grid-cols-2 gap-3">
                    <select name="city" id="city" onchange="myfunction()"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Peshawar</option>
                        <option>Kohat</option>
                        <option>Islamabad</option>
                        <option>Charsadah</option>
                        <option>Mardan</option>
                        <option>DIK</option>
                        <option>Other</option>
                    </select>
                    <select name="region" id="region"
                            class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Hayatabad</option>
                        <option>Sadar</option>
                        <option>Ring Road</option>
                        <option>Kohat Road</option>
                        <option>Warsak Road</option>
                        <option>Industrail Zone</option>
                        <option>Other</option>
                    </select>
                </div>
            </div>

            {{-- Postal Address --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Postal Address <span class="text-red-500">*</span></label>
                <input type="text" name="address" id="engine_1" required minlength="3"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- DOB --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date of Birth</label>
                <input type="date" name="dob"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- CNIC --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
                <input type="text" name="cnic"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                       placeholder="xxxxx-xxxxxxx-x">
            </div>

            {{-- Email — id="country_code_1" for auto.js --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" id="country_code_1"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="pt-2">
                <button type="submit"
                        class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                    ADD Customer Details
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
// City → Region dropdown — matches original myfunction() exactly
function myfunction() {
    var city   = document.getElementById('city').value;
    var region = document.getElementById('region');
    region.options.length = 0;
    if (city === 'Peshawar') {
        ['Hayatabad','Sadar','Ring Road','Kohat Road','Industrail Zone','Khyber Bazar','Warsak Road']
            .forEach(function (r) { region.options[region.options.length] = new Option(r); });
    } else {
        region.options[region.options.length] = new Option('Other');
    }
}
</script>
@endpush
@endsection
