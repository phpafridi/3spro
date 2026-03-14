@extends('layouts.master')
@section('title', 'Create Estimate')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-5">
            <h2 class="text-xl font-semibold text-gray-800">New Estimate</h2>
            <a href="{{ route('jobcard.unclosed-estimates') }}" class="text-sm text-gray-500 hover:text-gray-700">
                <i class="fa fa-list mr-1"></i> Unclosed Estimates
            </a>
        </div>
        <form method="POST" action="{{ route('jobcard.estimate.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Estimate Type <span class="text-red-500">*</span></label>
                <select name="estimate_type" id="est_type" required onchange="toggleInsurance(this)"
                        class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="Self">Self</option>
                    <option value="Insurance">Insurance</option>
                    <option value="Fleet">Fleet</option>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Payment Mode</label>
                    <select name="payment_mode" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Cash</option><option>Cheque</option><option>Credit</option><option>Online</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Customer Type</label>
                    <select name="cust_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option>Individuals</option><option>Govt</option><option>Corporate</option><option>Force</option><option>Banks</option>
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Customer <span class="text-red-500">*</span></label>
                <select name="cust_id" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select Customer --</option>
                    @foreach($customers as $c)
                    <option value="{{ $c->Customer_id }}">{{ $c->Customer_name }} — {{ $c->mobile }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle ID <span class="text-red-500">*</span></label>
                <input type="number" name="veh_id" required placeholder="Enter vehicle ID"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div id="insurance_section" class="hidden space-y-3 border border-blue-100 bg-blue-50 rounded-lg p-4">
                <h3 class="text-sm font-semibold text-blue-700">Insurance Details</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Insurance Company</label>
                    <select name="insur_company" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">-- Select --</option>
                        @foreach($insurCompanies as $ic)
                        <option value="{{ $ic->company_name }}">{{ $ic->company_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Surveyor Name</label>
                        <input type="text" name="surv_name" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Surveyor Type</label>
                        <select name="surv_type" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option>In-House</option><option>External</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Surveyor Contact</label>
                    <input type="text" name="sur_cont" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Est. Delivery Date</label>
                <input type="date" name="est_delivery" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-save mr-2"></i> Create Estimate
            </button>
        </form>
    </div>
</div>
@push('scripts')
<script>
function toggleInsurance(sel) {
    document.getElementById('insurance_section').classList.toggle('hidden', sel.value !== 'Insurance');
}
</script>
@endpush
@endsection
