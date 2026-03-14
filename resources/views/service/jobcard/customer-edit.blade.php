@extends('layouts.master')
@section('title', 'Edit Customer')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-1">Edit Customer</h2>
        <p class="text-sm text-gray-400 mb-5">ID: {{ $customer->Customer_id }}</p>
        @if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
        <form method="POST" action="{{ route('jobcard.customer.update') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="cust_id" value="{{ $customer->Customer_id }}">
            @if($roNo)
                <input type="hidden" name="ro_no" value="{{ $roNo }}">
            @else
                <input type="hidden" name="veh_idd" value="{{ $vehicleId }}">
            @endif
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Type</label>
                    <select name="cust_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['Individuals','Govt','Force','Corporate','Banks','Investor','Others'] as $type)
                        <option value="{{ $type }}" {{ $customer->cust_type==$type?'selected':'' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required value="{{ $customer->Customer_name }}" style="text-transform:uppercase"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Primary Mobile <span class="text-red-500">*</span></label>
                    <input type="text" name="mobile" required value="{{ $customer->mobile }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Secondary Phone</label>
                    <input type="text" name="off_phone" value="{{ $customer->off_phone }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                    <select name="city" id="city" onchange="updateRegions()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['Peshawar','Kohat','Islamabad','Charsadah','Mardan','DIK','Other'] as $city)
                        <option value="{{ $city }}" {{ $customer->City==$city?'selected':'' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Region</label>
                    <select name="region" id="region" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="{{ $customer->Region }}" selected>{{ $customer->Region }}</option>
                    </select>
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Postal Address</label>
                    <input type="text" name="address" value="{{ $customer->Address }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">DOB</label>
                    <input type="date" name="dob" value="{{ $customer->DOB }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
                    <input type="text" name="cnic" value="{{ $customer->CNIC }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ $customer->email }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">NTN</label>
                    <input type="text" name="ntn" value="{{ $customer->NTN }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">STRN</label>
                    <input type="text" name="strn" value="{{ $customer->STRN }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supplier No.</label>
                    <input type="text" name="supplier" value="{{ $customer->Supplier }}"
                           class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-save mr-2"></i> Update Customer Details
            </button>
        </form>
    </div>
</div>
@push('scripts')
<script>
var peshawarRegions = ['Hayatabad','Sadar','Ring Road','Kohat Road','Industrail Zone','Khyber Bazar','Warsak Road'];
function updateRegions() {
    var city = document.getElementById('city').value;
    var sel  = document.getElementById('region');
    sel.options.length = 0;
    (city === 'Peshawar' ? peshawarRegions : ['Other']).forEach(r => sel.options[sel.options.length] = new Option(r));
}
</script>
@endpush
@endsection
