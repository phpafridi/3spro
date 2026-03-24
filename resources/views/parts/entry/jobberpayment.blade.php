@extends('parts.layout')
@section('title', 'Vendor / Jobber Payments')
@section('content')

<div class="mb-4">
    <h2 class="text-xl font-bold text-gray-800">Vendor / Jobber Payments</h2>
    <p class="text-sm text-gray-500">Record payment to or receipt from a vendor</p>
</div>

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-800 text-sm">{{ session('success') }}</div>
@endif
@if($errors->any())
<div class="mb-4 p-3 bg-red-100 border-l-4 border-red-500 text-red-800 text-sm">
    @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="bg-white rounded shadow-sm border border-gray-200 p-5">
        <form action="{{ route('parts.jobber-payment.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Jobber / Vendor <span class="text-red-500">*</span></label>
                <select name="jobber" id="jobberSelect" required onchange="loadBalance(this.value)"
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select Vendor --</option>
                    @foreach($jobbers as $j)
                    <option value="{{ $j->jobber_id }}">{{ $j->jbr_name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Current balance display --}}
            <div id="balanceBox" class="mb-3 p-3 bg-gray-50 border border-gray-200 rounded text-sm hidden">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Current Balance:</span>
                    <span id="balanceAmount" class="font-bold text-lg text-red-600">0</span>
                </div>
                <div class="text-xs text-gray-400 mt-1" id="lastUpdate"></div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Transaction Type <span class="text-red-500">*</span></label>
                <select name="trans_type" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select --</option>
                    <option value="Paid">Paid (Payment to Vendor)</option>
                    <option value="Received">Received (Receipt from Vendor)</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Amount <span class="text-red-500">*</span></label>
                <input type="number" name="amount" id="amountInput" required min="0.01" step="0.01"
                       oninput="updateWords(this.value)"
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="0.00">
                {{-- Amount in words --}}
                <div id="amountWords" class="mt-1 text-xs text-blue-600 font-medium italic min-h-4"></div>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Payment Method <span class="text-red-500">*</span></label>
                <select name="payment_method" required
                        class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                    <option value="">-- Select --</option>
                    <option value="Cash">Cash</option>
                    <option value="Cheque">Cheque</option>
                    <option value="Online Transfer">Online Transfer</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="block text-sm font-medium text-gray-700 mb-1">Received / Paid By <span class="text-red-500">*</span></label>
                <input type="text" name="rec_paid_by" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                       placeholder="Person name">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                <textarea name="remarks" rows="2"
                          class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500"
                          placeholder="Optional"></textarea>
            </div>

            <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded font-medium text-sm transition-colors">
                Record Payment
            </button>
        </form>
    </div>

    {{-- All jobbers balances --}}
    <div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-4 bg-red-600">
            <h3 class="font-semibold text-white">Vendor Balances</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Vendor</th>
                        <th class="px-3 py-2 text-right text-xs text-gray-500 uppercase">Balance</th>
                        <th class="px-3 py-2 text-left text-xs text-gray-500 uppercase">Last Update</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($jobbers as $j)
                    <tr class="hover:bg-red-50">
                        <td class="px-3 py-2 font-medium text-gray-800">{{ $j->jbr_name }}</td>
                        <td class="px-3 py-2 text-right font-bold {{ $j->Balance_status < 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ number_format($j->Balance_status, 0) }}
                        </td>
                        <td class="px-3 py-2 text-xs text-gray-400">{{ $j->latest_update }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Load jobber balance when selected
function loadBalance(jobberId) {
    if (!jobberId) {
        document.getElementById('balanceBox').classList.add('hidden');
        return;
    }
    axios.get('{{ route("parts.ajax.jobber-balance") }}', { params: { jobber_id: jobberId } })
        .then(function(res) {
            var d = res.data;
            document.getElementById('balanceAmount').textContent = Number(d.balance).toLocaleString();
            document.getElementById('lastUpdate').textContent    = 'Last: ' + (d.updated || '');
            document.getElementById('balanceBox').classList.remove('hidden');
        });
}

// Amount to words — matches original JS exactly
var th = ['', ' Thousand', ' Million', ' Billion', ' Trillion'];
var dg = ['zero','One','Two','Three','Four','Five','Six','Seven','Eight','Nine'];
var tn = ['Ten','Eleven','Twelve','Thirteen','Fourteen','Fifteen','Sixteen','Seventeen','Eighteen','Nineteen'];
var tw = ['Twenty','Thirty','Forty','Fifty','Sixty','Seventy','Eighty','Ninety'];

function toWords(s) {
    s = s.toString(); s = s.replace(/[\, ]/g,'');
    if (s != parseFloat(s)) return 'not a number';
    var x = s.indexOf('.'); var n, str, sk, i;
    if (x == -1) n = s; else n = s.substring(0,x);
    if (n.length > 15) return 'too big';
    var ar = n.split('').reverse();
    var w = '';
    for (i=0; i<ar.length; i++) {
        if ((i+1)%3==1 && i>0) w = th[Math.floor(i/3)] + w;
        var d = parseInt(ar[i]);
        if ((i+1)%3==2) {
            var next = parseInt(ar[i-1]);
            if (d==1) { w = tn[next] + w; ar[i-1]=-1; }
            else if (d>1) w = tw[d-2] + (next>0 ? ' ' + dg[next] : '') + w;
        } else if ((i+1)%3!=0 || i==0) {
            if (ar[i]!=-1) w = (w != '' && ar[i]>0 ? ' ' : '') + dg[d] + w;
        } else {
            if (ar[i]!=0) w = dg[d] + ' Hundred' + (w!='' ? ' ' : '') + w;
        }
    }
    return w.trim();
}

function updateWords(val) {
    var el = document.getElementById('amountWords');
    if (!val || isNaN(val) || parseFloat(val) <= 0) { el.textContent = ''; return; }
    try { el.textContent = toWords(parseInt(val)) + ' Rupees Only'; }
    catch(e) { el.textContent = ''; }
}
</script>
@endpush
@endsection
