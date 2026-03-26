@extends('layouts.master')
@section('title', 'New Delivery Order')
@include('sales-vehicle.partials.sidebar')

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush

@section('content')
<div class="max-w-4xl mx-auto" x-data="doForm()">

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">
            <i class="fas fa-file-alt mr-2 text-green-600"></i>New Delivery Order
        </h2>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg text-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-lg text-sm">
                @foreach($errors->all() as $e)<p>{{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('sv.store-do') }}">
            @csrf

            {{-- VEHICLE --}}
            <div class="mb-6 p-4 bg-blue-50 rounded-xl border border-blue-100">
                <h3 class="text-xs font-bold text-blue-700 uppercase tracking-widest mb-3">
                    <i class="fas fa-car mr-1"></i> Vehicle
                </h3>
                <select name="vehicle_id" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                    <option value="">— Select Vehicle —</option>
                    @foreach($availableVehicles as $v)
                        <option value="{{ $v->id }}"
                            {{ (old('vehicle_id', $vehicle?->id) == $v->id) ? 'selected' : '' }}>
                            {{ $v->model }} {{ $v->variant }} | {{ $v->color }} | {{ $v->vin }}
                            [{{ $v->status }}] | List: PKR {{ number_format($v->list_price) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- CUSTOMER --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-100">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                    <i class="fas fa-user mr-1"></i> Customer Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_name" value="{{ old('customer_name') }}" required
                               style="text-transform:uppercase"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone <span class="text-red-500">*</span></label>
                        <input type="text" name="customer_phone" value="{{ old('customer_phone') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">CNIC</label>
                        <input type="text" name="customer_cnic" value="{{ old('customer_cnic') }}"
                               placeholder="XXXXX-XXXXXXX-X"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">DO Date <span class="text-red-500">*</span></label>
                        <input type="date" name="do_date" value="{{ old('do_date', date('Y-m-d')) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" name="customer_address" value="{{ old('customer_address') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            {{-- PRICING --}}
            <div class="mb-6 p-4 bg-yellow-50 rounded-xl border border-yellow-100">
                <h3 class="text-xs font-bold text-yellow-700 uppercase tracking-widest mb-4">
                    <i class="fas fa-tags mr-1"></i> Pricing
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            On-road Price (PKR) <span class="text-red-500">*</span>
                            <span class="block text-xs text-gray-400 font-normal">ex-factory + reg + insurance</span>
                        </label>
                        <input type="number" name="onroad_price"
                               x-model.number="onroadPrice"
                               @input="calcCustomerPaid()"
                               value="{{ old('onroad_price') }}"
                               required min="1" step="1000"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Discount (PKR)
                            <span class="block text-xs text-gray-400 font-normal">leave 0 if none</span>
                        </label>
                        <input type="number" name="discount"
                               x-model.number="discount"
                               @input="calcCustomerPaid()"
                               value="{{ old('discount', 0) }}"
                               min="0" step="1000"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Customer Paid Amount (PKR)
                            <span class="block text-xs text-green-600 font-semibold">auto = On-road − Discount</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="customer_paid_amount"
                                   :value="customerPaidAmount"
                                   readonly
                                   class="w-full border-2 border-green-300 bg-green-50 rounded-lg px-3 py-2 text-sm font-bold text-green-800 cursor-not-allowed">
                            <span class="absolute right-3 top-2.5 text-green-400 text-xs">
                                <i class="fas fa-lock"></i>
                            </span>
                        </div>
                    </div>

                </div>
            </div>

            {{-- PAYMENT METHOD --}}
            <div class="mb-6 p-4 bg-white rounded-xl border-2 border-gray-200">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">
                    <i class="fas fa-wallet mr-1"></i> Payment Method
                </h3>

                <div class="flex gap-3 mb-5">
                    <button type="button"
                            @click="paymentType = 'Cash'"
                            :class="paymentType === 'Cash'
                                ? 'bg-green-600 text-white border-green-600 shadow-md'
                                : 'bg-white text-gray-500 border-gray-300 hover:border-green-400'"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl border-2 text-sm font-semibold transition-all">
                        <i class="fas fa-money-bill-wave"></i> Cash
                    </button>
                    <button type="button"
                            @click="paymentType = 'Installment'"
                            :class="paymentType === 'Installment'
                                ? 'bg-blue-600 text-white border-blue-600 shadow-md'
                                : 'bg-white text-gray-500 border-gray-300 hover:border-blue-400'"
                            class="flex items-center gap-2 px-6 py-2.5 rounded-xl border-2 text-sm font-semibold transition-all">
                        <i class="fas fa-university"></i> Installment / Finance
                    </button>
                </div>

                <input type="hidden" name="payment_type" :value="paymentType">

                {{-- CASH --}}
                <div x-show="paymentType === 'Cash'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-4 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-xs font-bold text-green-700 uppercase tracking-wide mb-3">
                        <i class="fas fa-check-circle mr-1"></i> Cash Payment Details
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Cash Received (PKR) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="cash_received"
                                   value="{{ old('cash_received') }}"
                                   :required="paymentType === 'Cash'"
                                   min="0" step="1000"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                            <input type="date" name="delivery_date"
                                   value="{{ old('delivery_date') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                        </div>
                    </div>
                </div>

                {{-- INSTALLMENT --}}
                <div x-show="paymentType === 'Installment'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-xs font-bold text-blue-700 uppercase tracking-wide mb-3">
                        <i class="fas fa-university mr-1"></i> Finance / Installment Details
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Bank / Leasing Company <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="bank_name"
                                   value="{{ old('bank_name') }}"
                                   :required="paymentType === 'Installment'"
                                   placeholder="e.g. Meezan Bank, HBL, MCB Leasing"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Finance Scheme</label>
                            <input type="text" name="finance_scheme"
                                   value="{{ old('finance_scheme') }}"
                                   placeholder="e.g. Meezan Easy Auto Finance"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Down Payment (PKR) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="down_payment"
                                   x-model.number="downPayment"
                                   @input="calcLoanAmount()"
                                   value="{{ old('down_payment', 0) }}"
                                   :required="paymentType === 'Installment'"
                                   min="0" step="1000"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Loan Amount (PKR)
                                <span class="block text-xs text-blue-600 font-semibold">auto = Customer Paid − Down Payment</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="loan_amount"
                                       :value="loanAmount"
                                       readonly
                                       class="w-full border-2 border-blue-300 bg-blue-50 rounded-lg px-3 py-2 text-sm font-bold text-blue-800 cursor-not-allowed">
                                <span class="absolute right-3 top-2.5 text-blue-400 text-xs">
                                    <i class="fas fa-lock"></i>
                                </span>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Tenure <span class="text-red-500">*</span>
                            </label>
                            <select name="tenure_months"
                                    :required="paymentType === 'Installment'"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option value="">— Select —</option>
                                @foreach([12, 18, 24, 30, 36, 48, 60, 72] as $t)
                                    <option value="{{ $t }}" {{ old('tenure_months') == $t ? 'selected' : '' }}>
                                        {{ $t }} months
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Monthly Installment (PKR) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="monthly_installment"
                                   value="{{ old('monthly_installment') }}"
                                   :required="paymentType === 'Installment'"
                                   min="0" step="100"
                                   placeholder="As per bank approval letter"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Delivery Date</label>
                            <input type="date" name="delivery_date"
                                   value="{{ old('delivery_date') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>

                    </div>

                    {{-- Summary Box --}}
                    <div class="mt-4 p-3 bg-white rounded-lg border border-blue-200">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Installment Summary</p>
                        <div class="grid grid-cols-3 gap-3 text-center text-sm">
                            <div>
                                <p class="text-gray-400 text-xs mb-0.5">On-road Price</p>
                                <p class="font-bold text-gray-700" x-text="'PKR ' + Number(onroadPrice).toLocaleString()"></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-0.5">Down Payment</p>
                                <p class="font-bold text-green-700" x-text="'PKR ' + Number(downPayment).toLocaleString()"></p>
                            </div>
                            <div>
                                <p class="text-gray-400 text-xs mb-0.5">Bank Finances</p>
                                <p class="font-bold text-blue-700" x-text="'PKR ' + Number(loanAmount).toLocaleString()"></p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            {{-- REMARKS --}}
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remarks') }}</textarea>
            </div>

            {{-- SUBMIT --}}
            <div class="flex gap-3">
                <button type="submit"
                        class="px-6 py-2.5 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition shadow-sm">
                    <i class="fas fa-file-alt mr-2"></i>Create Delivery Order
                </button>
                <a href="{{ route('sv.do-list') }}"
                   class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-lg transition">
                    Cancel
                </a>
            </div>

        </form>
    </div>
</div>

<script>
function doForm() {
    return {
        paymentType:        '{{ old("payment_type", "Cash") }}',
        onroadPrice:         {{ (float) old('onroad_price', 0) }},
        discount:            {{ (float) old('discount', 0) }},
        customerPaidAmount:  {{ (float) old('onroad_price', 0) - (float) old('discount', 0) }},
        downPayment:         {{ (float) old('down_payment', 0) }},
        loanAmount:          0,

        init() {
            this.calcCustomerPaid();
        },

        calcCustomerPaid() {
            this.customerPaidAmount = Math.max(0, this.onroadPrice - this.discount);
            this.calcLoanAmount();
        },

        calcLoanAmount() {
            this.loanAmount = Math.max(0, this.customerPaidAmount - this.downPayment);
        },
    }
}
</script>
@endsection
