@extends('layouts.master')
@include('finance.cashier.sidebar')

@section('title', 'Cashier - Invoice #' . $jobId)

@push('styles')
<style>
    .radio-group {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin: 1rem 0;
    }
    .radio-option {
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        background: #f3f4f6;
        border-radius: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .radio-option:hover {
        background: #e5e7eb;
    }
    .radio-option input[type="radio"] {
        margin-right: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-file-invoice text-red-500 mr-2"></i>
            Invoice #{{ $jobId }}
        </h2>
        <a href="{{ route('cashier.index') }}" class="text-gray-600 hover:text-gray-900">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>

    <!-- Job Details -->
    <div class="bg-gray-50 rounded-lg p-4 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <p class="text-xs text-gray-500">Customer</p>
                <p class="font-medium">{{ $customer->Customer_name }}</p>
                <p class="text-sm text-gray-600">{{ $customer->mobile }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Vehicle</p>
                <p class="font-medium">{{ $vehicle->Variant }}</p>
                <p class="text-sm text-gray-600">{{ $vehicle->Registration }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-500">Service Advisor</p>
                <p class="font-medium">{{ $job->SA }}</p>
                <p class="text-sm text-gray-600">{{ $job->comp_appointed ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('cashier.save-invoice') }}" id="invoiceForm">
        @csrf
        <input type="hidden" name="ro_no" value="{{ $jobId }}">
        <input type="hidden" name="customer_id" value="{{ $customer->Customer_id }}">

        <!-- Invoice Items -->
        <div class="overflow-x-auto mb-6">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tax %</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tax Amt</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Discount</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Labor -->
                    <tr>
                        <td class="px-4 py-3 font-medium">Labor</td>
                        <td class="px-4 py-3">
                            <input type="number" name="Labor" id="labor" value="{{ $laborTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="ltax" id="ltax" oninput="calculateLabor()" value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="ltaxamount" id="ltaxamount" readonly value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="L_discount" id="L_discount" oninput="calculateLabor()" value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="l_nettotal" id="l_nettotal" value="{{ $laborTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                    </tr>

                    <!-- Parts -->
                    <tr>
                        <td class="px-4 py-3 font-medium">Parts</td>
                        <td class="px-4 py-3">
                            <input type="number" name="parts" id="parts" value="{{ $partsTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="ptax" id="ptax" oninput="calculateParts()" value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="ptaxamount" id="ptaxamount" readonly value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="pdiscount" id="pdiscount" oninput="calculateParts()" value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="pnettotal" id="pnettotal" value="{{ $partsTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                    </tr>

                    <!-- Sublet -->
                    <tr>
                        <td class="px-4 py-3 font-medium">Sublet</td>
                        <td class="px-4 py-3">
                            <input type="number" name="sublet" id="sublet" value="{{ $subletTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="stax" id="stax" oninput="calculateSublet()" value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="staxamount" id="staxamount" readonly value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="sdiscount" id="sdiscount" oninput="calculateSublet()" value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="snettotal" id="snettotal" value="{{ $subletTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                    </tr>

                    <!-- Consumable -->
                    <tr>
                        <td class="px-4 py-3 font-medium">Consumable</td>
                        <td class="px-4 py-3">
                            <input type="number" name="consumble" id="consumble" value="{{ $consumableTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="ctax" id="ctax" oninput="calculateConsumable()" value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <input type="text" name="ctaxamount" id="ctaxamount" readonly value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="cdiscount" id="cdiscount" oninput="calculateConsumable()" value="0"
                                   class="w-20 px-2 py-1 border border-gray-300 rounded">
                        </td>
                        <td class="px-4 py-3">
                            <input type="number" name="cnettotal" id="cnettotal" value="{{ $consumableTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-gray-50">
                        </td>
                    </tr>

                    <!-- Grand Total -->
                    <tr class="bg-gray-100 font-bold">
                        <td colspan="5" class="px-4 py-3 text-right">Grand Total</td>
                        <td class="px-4 py-3">
                            <input type="number" name="grandtotal" id="grandtotal" value="{{ $grandTotal }}" readonly
                                   class="w-24 px-2 py-1 border border-gray-300 rounded bg-white font-bold">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Invoice Type Radio Buttons -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Invoice Type</label>
            <div class="radio-group">
                @foreach(['CM', 'DM', 'DMC', 'JND', 'GW', 'COMP', 'FFS', 'PDS', 'WC', 'CBJ', 'CNI'] as $type)
                <label class="radio-option">
                    <input type="radio" name="radiob" value="{{ $type }}" {{ $loop->first ? 'checked' : '' }} onchange="toggleCareOf()">
                    {{ $type }}
                </label>
                @endforeach
            </div>
        </div>

        <!-- Care Of (for DM) -->
        <div id="careof-container" class="mb-6 hidden">
            <label class="block text-sm font-medium text-gray-700 mb-2">Care Of *</label>
            <select name="careoff" class="w-full md:w-96 px-3 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                <option value="">Select...</option>
                @foreach($recoveryAccounts as $account)
                <option value="{{ $account->Name }}">{{ $account->Name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-between items-center">
            <button type="submit" formaction="{{ route('cashier.save-invoice') }}"
                    class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-save mr-2"></i> Submit & Print
            </button>
            <button type="submit" formaction="{{ route('cashier.tax-invoice') }}"
                    class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                <i class="fas fa-file-invoice mr-2"></i> Sales Tax Invoice
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function toggleCareOf() {
        const selected = document.querySelector('input[name="radiob"]:checked').value;
        const container = document.getElementById('careof-container');
        container.classList.toggle('hidden', selected !== 'DM');
    }

    function calculateLabor() {
        const labor = parseFloat(document.getElementById('labor').value) || 0;
        const tax = parseFloat(document.getElementById('ltax').value) || 0;
        const discount = parseFloat(document.getElementById('L_discount').value) || 0;

        const taxAmount = (labor * tax / 100).toFixed(2);
        document.getElementById('ltaxamount').value = taxAmount;

        const net = labor - discount + parseFloat(taxAmount);
        document.getElementById('l_nettotal').value = net.toFixed(2);

        calculateGrandTotal();
    }

    function calculateParts() {
        const parts = parseFloat(document.getElementById('parts').value) || 0;
        const tax = parseFloat(document.getElementById('ptax').value) || 0;
        const discount = parseFloat(document.getElementById('pdiscount').value) || 0;

        const taxAmount = (parts * tax / 100).toFixed(2);
        document.getElementById('ptaxamount').value = taxAmount;

        const net = parts - discount + parseFloat(taxAmount);
        document.getElementById('pnettotal').value = net.toFixed(2);

        calculateGrandTotal();
    }

    function calculateSublet() {
        const sublet = parseFloat(document.getElementById('sublet').value) || 0;
        const tax = parseFloat(document.getElementById('stax').value) || 0;
        const discount = parseFloat(document.getElementById('sdiscount').value) || 0;

        const taxAmount = (sublet * tax / 100).toFixed(2);
        document.getElementById('staxamount').value = taxAmount;

        const net = sublet - discount + parseFloat(taxAmount);
        document.getElementById('snettotal').value = net.toFixed(2);

        calculateGrandTotal();
    }

    function calculateConsumable() {
        const cons = parseFloat(document.getElementById('consumble').value) || 0;
        const tax = parseFloat(document.getElementById('ctax').value) || 0;
        const discount = parseFloat(document.getElementById('cdiscount').value) || 0;

        const taxAmount = (cons * tax / 100).toFixed(2);
        document.getElementById('ctaxamount').value = taxAmount;

        const net = cons - discount + parseFloat(taxAmount);
        document.getElementById('cnettotal').value = net.toFixed(2);

        calculateGrandTotal();
    }

    function calculateGrandTotal() {
        const labor = parseFloat(document.getElementById('l_nettotal').value) || 0;
        const parts = parseFloat(document.getElementById('pnettotal').value) || 0;
        const sublet = parseFloat(document.getElementById('snettotal').value) || 0;
        const cons = parseFloat(document.getElementById('cnettotal').value) || 0;

        const total = labor + parts + sublet + cons;
        document.getElementById('grandtotal').value = total.toFixed(2);
    }
</script>
@endpush
@endsection
