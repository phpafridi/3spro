@extends('layouts.master')
@section('title', 'Open Jobcard — RO Details')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded shadow-sm p-6">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-semibold text-gray-800">Jobcard Details</h2>
            <a href="{{ route('jobcard.vehicle-detail', ['vehicle_id' => $vehicleId]) }}"
               class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>

        {{-- Customer & Vehicle summary --}}
        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-md grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
            <div><span class="text-gray-500 block text-xs uppercase">Customer</span>
                <strong>{{ $customer->Customer_name }}</strong></div>
            <div><span class="text-gray-500 block text-xs uppercase">Mobile</span>
                {{ $customer->mobile }}</div>
            <div><span class="text-gray-500 block text-xs uppercase">Registration</span>
                <strong class="text-red-600">{{ $vehicle->Registration }}</strong></div>
            <div><span class="text-gray-500 block text-xs uppercase">Variant</span>
                {{ $vehicle->Variant }}</div>
        </div>

        @if($errors->any())
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md text-sm">
            @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
        </div>
        @endif

        <form action="{{ route('jobcard.store') }}" method="POST" class="space-y-5">
            @csrf
            {{-- Hidden fields --}}
            <input type="hidden" name="veh_id"    value="{{ $vehicleId }}">
            <input type="hidden" name="cust_id"   value="{{ $customerId }}">
            <input type="hidden" name="veh_reg"   value="{{ $vehicle->Registration }}">
            <input type="hidden" name="cust_name" value="{{ $customer->Customer_name }}">
            <input type="hidden" name="Frame_no"  value="{{ $vehicle->Frame_no }}">
            <input type="hidden" name="cust_type" value="{{ $customer->cust_type }}">

            {{-- Hidden Recent Campaign --}}
            <input type="hidden" name="compaign" value="None">

            {{-- Customer Source & Other Fields --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                {{-- Empty div for spacing --}}
                <div></div>

                {{-- RO Type --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RO Type <span class="text-red-500">*</span></label>
                    <select name="ro_type" id="ro_type" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Mechanical"   {{ old('ro_type','Mechanical') === 'Mechanical'   ? 'selected' : '' }}>Mechanical</option>
                        <option value="Body / Paint" {{ old('ro_type') === 'Body / Paint' ? 'selected' : '' }}>Body / Paint</option>
                        <option value="Warranty"     {{ old('ro_type') === 'Warranty'     ? 'selected' : '' }}>Warranty</option>
                    </select>
                </div>

                {{-- MSI Category --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">MSI Category <span class="text-red-500">*</span></label>
                    <select name="msi_category" id="msi_category" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="NILL">NILL</option>
                        <option value="Oil & Oil Filter Change">Oil &amp; Oil Filter Change</option>
                        <option value="Super Light(SL)">Super Light (SL)</option>
                        <option value="Light(L)">Light (L)</option>
                        <option value="Medium(M)">Medium (M)</option>
                        <option value="Heavy(H)">Heavy (H)</option>
                        <option value="Others(engine tuning,brake service)">Others (engine tuning, brake service)</option>
                        <option value="General Repair(GR)">General Repair (GR)</option>
                        <option value="Body & Paint(B&P)">Body &amp; Paint (B&P)</option>
                        <option value="Pre-Delivery Service(PDS)">Pre-Delivery Service (PDS)</option>
                        <option value="First Free Service(FFS)">First Free Service (FFS)</option>
                        <option value="Service Campaigns">Service Campaigns</option>
                        <option value="CARWASH">CARWASH</option>
                    </select>
                </div>

                {{-- Service Nature --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Nature <span class="text-red-500">*</span></label>
                    <select name="serv_nature" id="serv_nature" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="PM">PM</option>
                        <option value="GR">GR</option>
                        <option value="BP">BP</option>
                        <option value="PDS">PDS</option>
                        <option value="FFS">FFS</option>
                        <option value="SFS">SFS</option>
                        <option value="TFS">TFS</option>
                    </select>
                </div>

                {{-- Fuel --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fuel Level <span class="text-red-500">*</span></label>
                    <select name="fuel" required
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Empty">Empty</option>
                        <option value="1/4">1/4</option>
                        <option value="Half" selected>Half</option>
                        <option value="3/4">3/4</option>
                        <option value="Full">Full</option>
                    </select>
                </div>

                {{-- Mileage --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Mileage (KM) <span class="text-red-500">*</span>
                    </label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="milage" id="NIC" required min="0"
                               value="{{ old('milage') }}"
                               class="flex-1 border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <span id="mileage_status" class="text-sm"></span>
                    </div>
                </div>

                {{-- Estimated Delivery Time --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Estimated Delivery Time</label>
                    <input type="datetime-local" name="estimat_time"required
                           value="{{ old('estimat_time') }}"
                           min="{{ now()->format('Y-m-d\TH:i') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Estimated Cost --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Estimated Cost <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="estimatedcost" required min="0"
                           value="{{ old('estimatedcost', '0') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- Diagnosed By --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Diagnosed By <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="Diagnozer" required
                           value="{{ old('Diagnozer') }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            {{-- VOC (Voice of Customer) --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    VOC — Customer Complaint <span class="text-red-500">*</span>
                </label>
                <textarea name="VOC" required rows="4" minlength="5"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Describe the customer's complaint in detail...">{{ old('VOC') }}</textarea>
            </div>

            <div class="pt-2 text-center">
               <button type="submit"
        class="px-8 py-3 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-gray-100 text-sm font-semibold rounded-lg shadow-md hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 focus:ring-offset-gray-800">
    <i class="fa fa-check-circle mr-2"></i> Open Jobcard
</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
// Debug logs


// ── Mileage check
var mileageInput = document.getElementById('NIC');


if (mileageInput) {


    mileageInput.addEventListener('change', function () {


        var nic    = this.value;
        var vehId  = '{{ $vehicleId }}';
        var status = document.getElementById('mileage_status');



        if (!nic || nic === '') {
            console.log('Mileage is empty, skipping');
            status.innerHTML = '';
            return;
        }

        status.innerHTML = '<i class="fa fa-spinner fa-spin text-gray-400"></i> Checking...';

        // Use Fetch API instead of jQuery
        fetch('{{ route("jobcard.check-mileage") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'text/plain'
            },
            body: new URLSearchParams({
                '_token': '{{ csrf_token() }}',
                'NIC': nic,
                'veh_id': vehId
            })
        })
        .then(response => {

            return response.text();
        })
        .then(data => {

            var msg = data.trim();
            if (msg === 'OK') {
                status.innerHTML = '<i class="fa fa-check text-green-500"></i> OK';
            } else {
                status.innerHTML = '<span class="text-red-500 text-xs font-semibold">⚠ ' + msg + '</span>';
            }
        })
        .catch(error => {

            status.innerHTML = '<span class="text-red-500 text-xs font-semibold">⚠ Error checking mileage</span>';
        });
    });
} else {
    console.error('Could not find element with id "NIC"');
}

// ── MSI Category → auto-fill RO Type + Service Nature
var msiSelect = document.getElementById('msi_category');
if (msiSelect) {


    msiSelect.addEventListener('change', function () {
        var msiId = this.value;


        // Skip if NILL is selected
        if (msiId === 'NILL' || !msiId) {
            return;
        }

        fetch('{{ route("jobcard.ajax.msi-details") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: new URLSearchParams({
                '_token': '{{ csrf_token() }}',
                'msi_id': msiId
            })
        })
        .then(response => {

            return response.json();
        })
        .then(response => {

            if (response && response.length > 0) {
                var roType = document.getElementById('ro_type');
                var servNature = document.getElementById('serv_nature');

                if (response[0].ro_type && roType) {
                    roType.value = response[0].ro_type;

                }
                if (response[0].service_nature && servNature) {
                    servNature.value = response[0].service_nature;

                }
            }
        })
        .catch(error => {
            console.error('MSI Fetch Error:', error);
        });
    });
} else {
    console.error('Could not find element with id "msi_category"');
}
</script>
@endpush
@endsection
