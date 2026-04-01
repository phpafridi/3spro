@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovery - Dashboard')
@section('content')

{{-- Stats Row --}}
<div class="grid grid-cols-3 md:grid-cols-6 gap-4 mb-6">
    @foreach([
        ['Total',   $totalc,    'bg-red-600'],
        ['New',     $new,       'bg-sky-500'],
        ['Open',    $open,      'bg-orange-500'],
        ['Close',   $close,     'bg-green-500'],
        ['Active',  $active,    'bg-yellow-500'],
        ['Pending', $pending,   'bg-red-500'],
    ] as [$label, $val, $color])
    <div class="bg-white rounded shadow-sm p-4 text-center">
        <div class="text-2xl font-bold text-gray-800">{{ $val }}</div>
        <div class="text-xs text-gray-500 mt-1">{{ $label }}</div>
        <div class="{{ $color }} h-1 rounded-full mt-2 opacity-60"></div>
    </div>
    @endforeach
</div>

{{-- Total Debit Banner --}}
<div class="bg-red-600 text-white rounded p-4 mb-6 flex items-center justify-between">
    <div>
        <p class="text-sm opacity-80">Total Outstanding Debit</p>
        <p class="text-3xl font-bold mt-1">Rs {{ number_format($totalDebit) }}</p>
    </div>
    <a href="{{ route('recovery.dm-bills') }}"
       class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded transition">
        <i class="fas fa-file-invoice mr-1"></i> DM Bills
    </a>
</div>

{{-- Outstanding Debtors Table --}}
<div class="bg-white rounded shadow-sm p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Outstanding Accounts</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gradient-to-r from-red-600 to-rose-600">
                <tr>
                    @foreach(['#','Customer','Contact','Age','Debit Amount','Actions'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($debtors as $d)
                @if(($d->remain_amount ?? 0) > 0)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $loop->iteration }}</td>
                    <td class="px-4 py-3 font-medium">
                        <a href="{{ route('recovery.customer-ledger', ['id'=>$d->cust_name]) }}" class="text-red-600 hover:underline">
                            {{ $d->cust_name }}
                        </a>
                    </td>
                    <td class="px-4 py-3">{{ $d->contact }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $d->age ?? '' }}</td>
                    <td class="px-4 py-3 font-medium text-red-600">
                        <a href="{{ route('recovery.clearance', ['id'=>$d->cust_name]) }}" class="hover:underline">
                            Rs {{ number_format($d->remain_amount) }}
                        </a>
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('recovery.history', ['id'=>$d->cust_name]) }}"
                           class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs hover:bg-yellow-200">History</a>
                        <a href="{{ route('recovery.followup', ['id'=>$d->cust_name]) }}"
                           class="px-2 py-1 bg-green-100 text-green-700 rounded text-xs hover:bg-green-200">Followup</a>
                    </td>
                </tr>
                @endif
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No debtors found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── Pending DM / DMC Bills (inline, no redirect needed) ─────── --}}
@if(isset($dmBills) && $dmBills->count())
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold text-gray-800">
            <i class="fas fa-file-invoice text-red-500 mr-2"></i>
            Pending DM / DMC Bills
            <span class="ml-2 px-2 py-0.5 bg-red-100 text-red-700 rounded-full text-xs font-bold">{{ $dmBills->count() }}</span>
        </h2>
        <a href="{{ route('recovery.dm-bills') }}" class="text-xs text-red-600 hover:underline">View All →</a>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-600">
                <tr>
                    @foreach(['Invoice','JC #','Customer','Reg','Type','Care Of','Total','Date','Action'] as $h)
                    <th class="px-3 py-2 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @foreach($dmBills->take(10) as $b)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 font-mono font-bold text-red-700">{{ $b->Invoice_id }}</td>
                    <td class="px-3 py-2 font-mono text-gray-600">{{ $b->Jobc_id }}</td>
                    <td class="px-3 py-2 font-medium text-gray-800">{{ $b->Customer_name }}</td>
                    <td class="px-3 py-2 text-gray-600">{{ $b->Veh_reg_no }}</td>
                    <td class="px-3 py-2">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $b->type === 'DMC' ? 'bg-purple-100 text-purple-700' : 'bg-red-100 text-red-700' }}">
                            {{ $b->type }}
                        </span>
                    </td>
                    <td class="px-3 py-2 text-xs text-gray-600">{{ $b->careof }}</td>
                    <td class="px-3 py-2 font-bold text-red-600">Rs {{ number_format($b->Total) }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $b->bookingtime }}</td>
                    <td class="px-3 py-2">
                        <button onclick="openDebitForm('{{ $b->Invoice_id }}','{{ addslashes($b->Customer_name) }}','{{ $b->Veh_reg_no }}','{{ $b->Total }}','{{ $b->bookingtime }}')"
                            class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition whitespace-nowrap">
                            <i class="fas fa-plus mr-1"></i>Add Debit
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

{{-- ── Debit Entry Slide-in Panel ───────────────────────────────── --}}
<div id="debit_backdrop" onclick="closeDebitForm()"
    style="display:none;position:fixed;inset:0;z-index:9000;background:rgba(0,0,0,0.4);"></div>

<div id="debit_panel"
    style="display:none;position:fixed;top:0;right:0;height:100%;width:100%;max-width:480px;z-index:9001;
           background:#fff;box-shadow:-4px 0 30px rgba(0,0,0,0.2);overflow-y:auto;transition:transform .25s ease;">

    <div style="background:#dc2626;padding:16px 20px;display:flex;align-items:center;justify-content:space-between;">
        <div>
            <div style="color:#fff;font-weight:700;font-size:15px;"><i class="fas fa-minus-circle mr-2"></i>Add Debit Entry</div>
            <div id="debit_panel_sub" style="color:#fca5a5;font-size:12px;margin-top:3px;"></div>
        </div>
        <button onclick="closeDebitForm()" style="background:none;border:none;color:#fff;font-size:24px;cursor:pointer;line-height:1;">&times;</button>
    </div>

    <div style="padding:24px;">
        <form method="POST" action="{{ route('recovery.add-debt.store') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Customer Name</label>
                <input type="text" name="typeahead" id="dp_customer" required
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Registration</label>
                    <input type="text" name="required_registration" id="dp_reg"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Invoice No</label>
                    <input type="text" name="required_invoice" id="dp_invoice" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm bg-red-50 font-mono font-bold focus:ring-2 focus:ring-red-500">
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Debt Amount (Rs)</label>
                    <input type="number" name="required_amount" id="dp_amount" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm font-bold text-red-700 focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Date</label>
                    <input type="date" name="required_date" id="dp_date" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-red-500">
                </div>
            </div>
            <div>
                <label class="block text-xs font-semibold text-gray-600 mb-1 uppercase tracking-wide">Followup Date <span class="text-gray-400 normal-case font-normal">(optional)</span></label>
                <input type="date" name="fallowup"
                    class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-2 focus:ring-red-500">
            </div>
            <button type="submit"
                class="w-full py-3 bg-red-600 hover:bg-red-700 text-white rounded font-semibold text-sm transition mt-2">
                <i class="fas fa-save mr-2"></i>Save Debit Entry
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openDebitForm(invoiceId, customer, reg, amount, date) {
    document.getElementById('dp_customer').value = customer;
    document.getElementById('dp_reg').value      = reg;
    document.getElementById('dp_invoice').value  = invoiceId;
    document.getElementById('dp_amount').value   = amount;
    // Parse date to YYYY-MM-DD
    var d = date ? new Date(date) : new Date();
    var ds = d.getFullYear()+'-'+String(d.getMonth()+1).padStart(2,'0')+'-'+String(d.getDate()).padStart(2,'0');
    document.getElementById('dp_date').value = ds;
    document.getElementById('debit_panel_sub').textContent = 'Invoice: ' + invoiceId + '  ·  ' + customer;

    document.getElementById('debit_backdrop').style.display = 'block';
    document.getElementById('debit_panel').style.display    = 'block';
}
function closeDebitForm() {
    document.getElementById('debit_backdrop').style.display = 'none';
    document.getElementById('debit_panel').style.display    = 'none';
}
</script>
@endpush

@endsection
