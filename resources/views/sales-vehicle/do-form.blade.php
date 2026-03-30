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

            {{-- NVD HEADER: PBO No / Type / Sale Price --}}
            <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3">
                    <i class="fas fa-file-contract mr-1"></i> NVD Header
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">PBO No.</label>
                        <input type="text" name="pbo_no" value="{{ old('pbo_no') }}"
                               placeholder="e.g. -001"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Customer Type</label>
                        <select name="customer_type"
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach(['Individual','Investor','Corporate'] as $t)
                                <option value="{{ $t }}" {{ old('customer_type','Individual') === $t ? 'selected' : '' }}>{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

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
                        <label class="block text-sm font-medium text-gray-700 mb-1">S/o &amp; W/o (Son of / Wife of)</label>
                        <input type="text" name="customer_son_wife_of" value="{{ old('customer_son_wife_of') }}"
                               placeholder="Father / Husband name"
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
                               @input="calcAll()"
                               value="{{ old('onroad_price') }}"
                               required min="1"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Discount (PKR)
                            <span class="block text-xs text-gray-400 font-normal">leave 0 if none</span>
                        </label>
                        <input type="number" name="discount"
                               x-model.number="discount"
                               @input="calcAll()"
                               value="{{ old('discount', 0) }}"
                               min="0"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Customer Paid Amount (PKR)
                            <span class="block text-xs text-green-600 font-semibold">auto = On-road minus Discount</span>
                        </label>
                        <div class="relative">
                            <input type="number" name="customer_paid_amount"
                                   :value="customerPaidAmount"
                                   readonly
                                   class="w-full border-2 border-green-300 bg-green-50 rounded-lg px-3 py-2 text-sm font-bold text-green-800 cursor-not-allowed">
                            <span class="absolute right-3 top-2.5 text-green-400 text-xs"><i class="fas fa-lock"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- PAYMENT METHOD --}}
            <div class="mb-6 p-4 bg-white rounded-xl border-2 border-gray-200">
                <h3 class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-4">
                    <i class="fas fa-wallet mr-1"></i> Payment Method
                </h3>

                <div class="flex flex-wrap gap-3 mb-5">
                    <button type="button" @click="paymentType = 'Cash'; calcAll()"
                            :class="paymentType === 'Cash' ? 'bg-green-600 text-white border-green-600 shadow-md' : 'bg-white text-gray-500 border-gray-300 hover:border-green-400'"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 text-sm font-semibold transition-all">
                        <i class="fas fa-money-bill-wave"></i> Cash
                    </button>
                    <button type="button" @click="paymentType = 'Installment'; calcAll()"
                            :class="paymentType === 'Installment' ? 'bg-blue-600 text-white border-blue-600 shadow-md' : 'bg-white text-gray-500 border-gray-300 hover:border-blue-400'"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 text-sm font-semibold transition-all">
                        <i class="fas fa-university"></i> Bank Finance
                    </button>
                    <button type="button" @click="paymentType = 'Direct'; calcAll()"
                            :class="paymentType === 'Direct' ? 'bg-purple-600 text-white border-purple-600 shadow-md' : 'bg-white text-gray-500 border-gray-300 hover:border-purple-400'"
                            class="flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 text-sm font-semibold transition-all">
                        <i class="fas fa-handshake"></i> Direct Instalment <span class="text-xs opacity-75 ml-1">(no bank)</span>
                    </button>
                </div>

                <input type="hidden" name="payment_type" :value="paymentType">

                {{-- CASH --}}
                <div x-show="paymentType === 'Cash'" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-4 bg-green-50 rounded-xl border border-green-100">
                    <p class="text-xs font-bold text-green-700 uppercase tracking-wide mb-3">
                        <i class="fas fa-check-circle mr-1"></i> Cash Payment Details
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cash Received (PKR) <span class="text-red-500">*</span></label>
                            <input type="number" name="cash_received" value="{{ old('cash_received') }}"
                                   :required="paymentType === 'Cash'" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date *</label>
                            <input type="date" name="do_date" value="{{ old('do_date', date('Y-m-d')) }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-400">
                        </div>
                    </div>
                </div>

                {{-- BANK FINANCE --}}
                <div x-show="paymentType === 'Installment'" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-4 bg-blue-50 rounded-xl border border-blue-100">
                    <p class="text-xs font-bold text-blue-700 uppercase tracking-wide mb-3">
                        <i class="fas fa-university mr-1"></i> Bank Finance / Instalment Details
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank / Leasing Company <span class="text-red-500">*</span></label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}"
                                   :required="paymentType === 'Installment'"
                                   placeholder="e.g. Meezan Bank, HBL, MCB Leasing"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Finance Scheme</label>
                            <input type="text" name="finance_scheme" value="{{ old('finance_scheme') }}"
                                   placeholder="e.g. Meezan Easy Auto Finance"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Down Payment (PKR) <span class="text-red-500">*</span></label>
                            <input type="number" name="down_payment" x-model.number="downPayment" @input="calcAll()"
                                   value="{{ old('down_payment', 0) }}" :required="paymentType === 'Installment'" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Loan Amount (PKR)
                                <span class="block text-xs text-blue-600 font-semibold">auto = Customer Paid minus Down Payment</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="loan_amount" :value="loanAmount" readonly
                                       class="w-full border-2 border-blue-300 bg-blue-50 rounded-lg px-3 py-2 text-sm font-bold text-blue-800 cursor-not-allowed">
                                <span class="absolute right-3 top-2.5 text-blue-400 text-xs"><i class="fas fa-lock"></i></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tenure <span class="text-red-500">*</span></label>
                            <select name="tenure_months" x-model.number="tenureMonths" @change="calcAll()"
                                    :required="paymentType === 'Installment'"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                                <option value="">— Select —</option>
                                @foreach([12, 18, 24, 30, 36, 48, 60, 72] as $t)
                                    <option value="{{ $t }}" {{ old('tenure_months') == $t ? 'selected' : '' }}>{{ $t }} months</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Monthly Instalment (PKR)
                                <span class="block text-xs text-blue-600 font-semibold">auto = Loan Amount / Tenure</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="monthly_installment" :value="monthlyInstallment" readonly
                                       class="w-full border-2 border-blue-300 bg-blue-50 rounded-lg px-3 py-2 text-sm font-bold text-blue-800 cursor-not-allowed">
                                <span class="absolute right-3 top-2.5 text-blue-400 text-xs"><i class="fas fa-calculator"></i></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Expected Delivery Date</label>
                            <input type="date" name="delivery_date" value="{{ old('delivery_date') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-white rounded-lg border border-blue-200">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Finance Summary</p>
                        <div class="grid grid-cols-4 gap-3 text-center text-sm">
                            <div><p class="text-gray-400 text-xs mb-0.5">On-road Price</p><p class="font-bold text-gray-700" x-text="'PKR ' + Number(onroadPrice).toLocaleString()"></p></div>
                            <div><p class="text-gray-400 text-xs mb-0.5">Down Payment</p><p class="font-bold text-green-700" x-text="'PKR ' + Number(downPayment).toLocaleString()"></p></div>
                            <div><p class="text-gray-400 text-xs mb-0.5">Bank Finances</p><p class="font-bold text-blue-700" x-text="'PKR ' + Number(loanAmount).toLocaleString()"></p></div>
                            <div><p class="text-gray-400 text-xs mb-0.5">Monthly EMI</p><p class="font-bold text-purple-700" x-text="tenureMonths ? 'PKR ' + Number(monthlyInstallment).toLocaleString() : '—'"></p></div>
                        </div>
                    </div>
                </div>

                {{-- DIRECT INSTALMENT --}}
                <div x-show="paymentType === 'Direct'" x-cloak
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 -translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="p-4 bg-purple-50 rounded-xl border border-purple-200">
                    <p class="text-xs font-bold text-purple-700 uppercase tracking-wide mb-3">
                        <i class="fas fa-handshake mr-1"></i> Direct Instalment — Company / Dealer Financed (No Bank)
                    </p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Down Payment (PKR) <span class="text-red-500">*</span></label>
                            <input type="number" name="direct_down_payment" x-model.number="directDownPayment" @input="calcAll()"
                                   value="{{ old('direct_down_payment', 0) }}" :required="paymentType === 'Direct'" min="0"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Remaining Balance (PKR)
                                <span class="block text-xs text-purple-600 font-semibold">auto = Customer Paid minus Down Payment</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="direct_balance" :value="directBalance" readonly
                                       class="w-full border-2 border-purple-300 bg-purple-50 rounded-lg px-3 py-2 text-sm font-bold text-purple-800 cursor-not-allowed">
                                <span class="absolute right-3 top-2.5 text-purple-400 text-xs"><i class="fas fa-lock"></i></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">No. of Instalments <span class="text-red-500">*</span></label>
                            <select name="direct_tenure_months" x-model.number="directTenure" @change="calcAll()"
                                    :required="paymentType === 'Direct'"
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                                <option value="">— Select —</option>
                                @foreach([3, 6, 9, 12, 18, 24, 30, 36] as $t)
                                    <option value="{{ $t }}" {{ old('direct_tenure_months') == $t ? 'selected' : '' }}>{{ $t }} months</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Monthly Instalment (PKR)
                                <span class="block text-xs text-purple-600 font-semibold">auto = Balance / No. of Instalments</span>
                            </label>
                            <div class="relative">
                                <input type="number" name="direct_monthly_instalment" :value="directMonthly" readonly
                                       class="w-full border-2 border-purple-300 bg-purple-50 rounded-lg px-3 py-2 text-sm font-bold text-purple-800 cursor-not-allowed">
                                <span class="absolute right-3 top-2.5 text-purple-400 text-xs"><i class="fas fa-calculator"></i></span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guarantor Name</label>
                            <input type="text" name="guarantor_name" value="{{ old('guarantor_name') }}"
                                   style="text-transform:uppercase" placeholder="Optional"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Guarantor Phone</label>
                            <input type="text" name="guarantor_phone" value="{{ old('guarantor_phone') }}"
                                   placeholder="Optional"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                            <input type="date" name="direct_delivery_date" value="{{ old('delivery_date', date('Y-m-d')) }}" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-purple-400">
                        </div>
                    </div>
                    <div class="mt-4 p-3 bg-white rounded-lg border border-purple-200">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2">Direct Instalment Summary</p>
                        <div class="grid grid-cols-4 gap-3 text-center text-sm">
                            <div><p class="text-gray-400 text-xs mb-0.5">Total Price</p><p class="font-bold text-gray-700" x-text="'PKR ' + Number(customerPaidAmount).toLocaleString()"></p></div>
                            <div><p class="text-gray-400 text-xs mb-0.5">Down Payment</p><p class="font-bold text-green-700" x-text="'PKR ' + Number(directDownPayment).toLocaleString()"></p></div>
                            <div><p class="text-gray-400 text-xs mb-0.5">Balance</p><p class="font-bold text-purple-700" x-text="'PKR ' + Number(directBalance).toLocaleString()"></p></div>
                            <div><p class="text-gray-400 text-xs mb-0.5">Monthly</p><p class="font-bold text-red-600" x-text="directTenure ? 'PKR ' + Number(directMonthly).toLocaleString() : '—'"></p></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- REMARKS --}}
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="2"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('remarks') }}</textarea>
            </div>

            {{-- VEHICLE RECEIVER --}}
            <div class="mb-6 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                <h3 class="text-xs font-bold text-indigo-700 uppercase tracking-widest mb-3">
                    <i class="fas fa-id-card mr-1"></i> Vehicle Receiver Information
                    <span class="text-xs font-normal text-indigo-400 ml-2">(leave blank if same as customer)</span>
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Receiver Name</label>
                        <input type="text" name="receiver_name" value="{{ old('receiver_name') }}"
                               placeholder="Defaults to customer name"
                               style="text-transform:uppercase"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Father Name</label>
                        <input type="text" name="receiver_father_name" value="{{ old('receiver_father_name') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">NIC #</label>
                        <input type="text" name="receiver_cnic" value="{{ old('receiver_cnic') }}"
                               placeholder="XXXXX-XXXXXXX-X"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Mobile</label>
                        <input type="text" name="receiver_phone" value="{{ old('receiver_phone') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address</label>
                        <input type="text" name="receiver_address" value="{{ old('receiver_address') }}"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    </div>
                </div>
            </div>

            {{-- ACCESSORIES + NVD CHECKLIST (two columns) --}}
            <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Accessories --}}
                <div class="p-4 border border-gray-200 rounded-xl">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-3 text-center bg-gray-800 text-white py-1 rounded">
                        ACCESSORIES
                    </h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="acc_remote_control" value="1" {{ old('acc_remote_control', '1') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Keys
                            <input type="number" name="acc_keys_qty" value="{{ old('acc_keys_qty', 1) }}"
                                   min="1" max="5"
                                   class="w-12 border border-gray-300 rounded px-1 py-0.5 text-sm text-center">
                            / Remote Control
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="acc_toolkit_jack" value="1" {{ old('acc_toolkit_jack', '1') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            ToolKit/Jack with Handle
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="acc_spare_wheel" value="1" {{ old('acc_spare_wheel') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Spare Wheel/Rear TrunkMat
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="acc_battery_warranty" value="1" {{ old('acc_battery_warranty') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Battery/Warranty Card/Cassette/Player
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="acc_service_warranty" value="1" {{ old('acc_service_warranty', '1') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Service Warranty/Floor Mat
                        </label>
                    </div>

                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-widest mt-4 mb-2 text-center bg-gray-800 text-white py-1 rounded">
                        DOCUMENTS
                    </h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="doc_sales_invoice" value="1" {{ old('doc_sales_invoice') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Sales Invoice
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="doc_sales_certificate" value="1" {{ old('doc_sales_certificate') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Sales Certificate
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="doc_sales_cert_verification" value="1" {{ old('doc_sales_cert_verification') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Sales Certificate Verification Copy
                        </label>
                    </div>
                </div>

                {{-- NVD Checklist --}}
                <div class="p-4 border border-gray-200 rounded-xl">
                    <h3 class="text-xs font-bold text-gray-700 uppercase tracking-widest mb-3 text-center bg-gray-800 text-white py-1 rounded">
                        NVD CHECKLIST SHEET
                    </h3>
                    <div class="space-y-2">
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="nvd_warranty_terms" value="1" {{ old('nvd_warranty_terms') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Explained Warranty Terms &amp; Conditions
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="nvd_owners_manual" value="1" {{ old('nvd_owners_manual') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Explained Owner's Manual
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="nvd_ffs_pm_schedule" value="1" {{ old('nvd_ffs_pm_schedule') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Explained FFS &amp; PM Schedule
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="nvd_3s_visit" value="1" {{ old('nvd_3s_visit') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Conducted 3S Visit
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="nvd_ew_ppm" value="1" {{ old('nvd_ew_ppm') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Explained EW &amp; PPM
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="nvd_safety_features" value="1" {{ old('nvd_safety_features') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Explained Safety Features
                        </label>
                        <label class="flex items-center gap-2 text-sm">
                            <input type="checkbox" name="nvd_demonstrated_ops" value="1" {{ old('nvd_demonstrated_ops') ? 'checked' : '' }}
                                   class="w-4 h-4">
                            Demonstrated Operations of Features
                        </label>
                    </div>
                </div>
            </div>

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
        paymentType:         '{{ old("payment_type", "Cash") }}',
        onroadPrice:          {{ (float) old('onroad_price', 0) }},
        discount:             {{ (float) old('discount', 0) }},
        customerPaidAmount:   0,
        // Bank
        downPayment:          {{ (float) old('down_payment', 0) }},
        loanAmount:           0,
        tenureMonths:         {{ (int) old('tenure_months', 0) }},
        monthlyInstallment:   0,
        // Direct
        directDownPayment:    {{ (float) old('direct_down_payment', 0) }},
        directBalance:        0,
        directTenure:         {{ (int) old('direct_tenure_months', 0) }},
        directMonthly:        0,

        init() { this.calcAll(); },

        calcAll() {
            this.customerPaidAmount = Math.max(0, this.onroadPrice - this.discount);
            // Bank
            this.loanAmount = Math.max(0, this.customerPaidAmount - this.downPayment);
            this.monthlyInstallment = this.tenureMonths > 0 ? Math.ceil(this.loanAmount / this.tenureMonths) : 0;
            // Direct
            this.directBalance = Math.max(0, this.customerPaidAmount - this.directDownPayment);
            this.directMonthly = this.directTenure > 0 ? Math.ceil(this.directBalance / this.directTenure) : 0;
        },
    }
}
</script>
@endsection
