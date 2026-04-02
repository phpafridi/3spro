@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovery - Manage Accounts')
@section('content')

{{-- ── Search Bar ─────────────────────────────────────────────── --}}
<div class="bg-white rounded shadow-sm p-6 mb-6">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">
        <i class="fas fa-search text-red-500 mr-2"></i>Search Customer Account
    </h2>
    <div class="relative max-w-lg">
        <input id="acct_search" type="text" placeholder="Type customer name…"
               autocomplete="off"
               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 pr-10 text-sm focus:ring-2 focus:ring-red-400 focus:outline-none">
        <i class="fas fa-search absolute right-3 top-3 text-gray-400 text-sm"></i>
        {{-- Dropdown --}}
        <div id="acct_dropdown"
             class="hidden absolute z-50 left-0 right-0 bg-white border border-gray-200 rounded-lg shadow-lg mt-1 max-h-72 overflow-y-auto">
        </div>
    </div>

    {{-- Result card shown after selection --}}
    <div id="acct_result" class="hidden mt-5 p-4 bg-red-50 border border-red-200 rounded-lg max-w-lg">
        <div class="flex items-center justify-between mb-3">
            <div>
                <p class="font-bold text-gray-800 text-base" id="res_name"></p>
                <p class="text-sm text-gray-500 mt-0.5">
                    <i class="fas fa-phone text-red-400 mr-1"></i>
                    <span id="res_mobile"></span>
                </p>
            </div>
            <button onclick="clearResult()" class="text-gray-400 hover:text-gray-600 text-lg">&times;</button>
        </div>
        <div class="flex gap-2 flex-wrap">
            <a id="btn_ledger" href="#"
               class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm rounded font-medium transition">
                <i class="fas fa-book mr-1"></i>View Ledger
            </a>
            <a id="btn_credit" href="#"
               class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm rounded font-medium transition">
                <i class="fas fa-plus-circle mr-1"></i>Add Credit
            </a>
            <span id="no_debit_msg"
                  class="hidden px-3 py-2 bg-yellow-100 text-yellow-700 text-xs rounded items-center">
                <i class="fas fa-info-circle mr-1"></i>No debit entry yet — add one from DM Bills
            </span>
        </div>
    </div>
</div>

{{-- ── Add New Account Form ─────────────────────────────────────── --}}
<div class="bg-white rounded shadow-sm p-6 max-w-3xl mb-6">
    <h2 class="text-xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-university text-red-500 mr-2"></i>Add New Account
    </h2>

    @if(session('success'))
    <div class="mb-4 px-4 py-2 bg-green-100 text-green-700 rounded text-sm">
        <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
    </div>
    @endif

    <form method="POST" action="{{ route('recovery.add-account.store') }}" class="p-5 bg-red-50 border border-red-200 rounded">
        @csrf
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="Name" required maxlength="38" value="{{ old('Name') }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500"
                       placeholder="Customer / company name">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Occupation</label>
                <input type="text" name="Occopation" maxlength="30" value="{{ old('Occopation') }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500"
                       placeholder="e.g. Businessman">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Primary Contact <span class="text-red-500">*</span></label>
                <input type="text" name="Primary_contact" required maxlength="20" value="{{ old('Primary_contact') }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500"
                       placeholder="03xxxxxxxxx">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Secondary Contact</label>
                <input type="text" name="Sec_contact" maxlength="20" value="{{ old('Sec_contact') }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500"
                       placeholder="Optional">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Email</label>
                <input type="email" name="email" maxlength="50" value="{{ old('email') }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Credit Limit (Rs)</label>
                <input type="number" name="amount_limit" value="{{ old('amount_limit', 0) }}"
                       class="w-full border border-gray-300 rounded px-4 py-2 text-sm focus:ring-2 focus:ring-red-500">
            </div>
        </div>
        <button type="submit" class="mt-4 px-5 py-2 bg-red-600 text-white rounded text-sm font-medium hover:bg-red-700 transition">
            <i class="fas fa-save mr-2"></i>Save Account
        </button>
    </form>
</div>

{{-- ── Existing Accounts Table ──────────────────────────────────── --}}
<div class="bg-white rounded shadow-sm p-6 max-w-3xl">
    <h3 class="font-semibold text-gray-700 mb-3 text-sm uppercase tracking-wider">Existing Accounts</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-red-600">
                <tr>
                    @foreach(['#','Name','Occupation','Primary Contact','Credit Limit','Officer','Status'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($accounts as $i => $a)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-400">{{ $i+1 }}</td>
                    <td class="px-4 py-3 font-medium">{{ $a->Name }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $a->Occopation }}</td>
                    <td class="px-4 py-3 font-mono">{{ $a->Primary_contact }}</td>
                    <td class="px-4 py-3 font-semibold text-red-600">Rs {{ number_format($a->amount_limit) }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $a->r_officer }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs
                            {{ $a->status === 'Active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ $a->status }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No accounts added yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
const searchInput  = document.getElementById('acct_search');
const dropdown     = document.getElementById('acct_dropdown');
const resultCard   = document.getElementById('acct_result');
let debounce;

searchInput.addEventListener('input', function () {
    clearTimeout(debounce);
    const q = this.value.trim();
    if (q.length < 2) { dropdown.classList.add('hidden'); return; }
    debounce = setTimeout(() => fetchAccounts(q), 280);
});

document.addEventListener('click', e => {
    if (!searchInput.contains(e.target) && !dropdown.contains(e.target))
        dropdown.classList.add('hidden');
});

function fetchAccounts(q) {
    fetch(`{{ route('recovery.account-lookup') }}?key=` + encodeURIComponent(q))
        .then(r => r.json())
        .then(data => renderDropdown(data));
}

function renderDropdown(items) {
    if (!items.length) {
        dropdown.innerHTML = '<p class="px-4 py-3 text-xs text-gray-400">No accounts found</p>';
    } else {
        dropdown.innerHTML = items.map(a => `
            <div onclick='selectAccount(${JSON.stringify(a)})'
                 class="flex items-center justify-between px-4 py-2.5 hover:bg-red-50 cursor-pointer border-b border-gray-100 last:border-0">
                <span class="font-medium text-gray-800 text-sm">${a.Name}</span>
                <span class="text-xs text-gray-400 font-mono">${a.Primary_contact || ''}</span>
            </div>`).join('');
    }
    dropdown.classList.remove('hidden');
}

function selectAccount(a) {
    dropdown.classList.add('hidden');
    searchInput.value = a.Name;

    document.getElementById('res_name').textContent   = a.Name;
    document.getElementById('res_mobile').textContent = a.Primary_contact || 'N/A';

    const ledgerBtn  = document.getElementById('btn_ledger');
    const creditBtn  = document.getElementById('btn_credit');
    const noDebitMsg = document.getElementById('no_debit_msg');

    if (a.has_debit && a.Customer_id) {
        ledgerBtn.href  = `/{{ request()->segment(1) }}/finance/recovery/customer-ledger?id=${a.Customer_id}`;
        creditBtn.href  = `/{{ request()->segment(1) }}/finance/recovery/add-credit?inv=&cust_id=${a.Customer_id}`;
        // Use proper named routes via data attributes
        ledgerBtn.href  = '{{ url("/finance/recovery/customer-ledger") }}?id=' + a.Customer_id;
        creditBtn.href  = '{{ url("/finance/recovery/add-credit") }}?cust_id=' + a.Customer_id;
        ledgerBtn.classList.remove('hidden');
        creditBtn.classList.remove('hidden');
        noDebitMsg.classList.add('hidden');
    } else {
        ledgerBtn.classList.add('hidden');
        creditBtn.classList.add('hidden');
        noDebitMsg.classList.remove('hidden');
        noDebitMsg.classList.add('flex');
    }

    resultCard.classList.remove('hidden');
}

function clearResult() {
    resultCard.classList.add('hidden');
    searchInput.value = '';
}
</script>
@endpush

@endsection
