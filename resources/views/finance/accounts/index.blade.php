@extends('layouts.master')
@include('finance.accounts.sidebar')

@section('title', 'Finance — Reports Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">

  {{-- Page Header --}}
  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
      <span class="inline-flex items-center justify-center w-10 h-10 rounded-lg bg-red-600 text-white shadow">
        <i class="fas fa-chart-bar text-base"></i>
      </span>
      Finance Reports
    </h1>
    <p class="text-sm text-gray-500 mt-1 pl-14">Select a date range and generate any financial report below.</p>
  </div>

  {{-- Date Range Bar --}}
  <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-4 mb-6 flex flex-wrap items-center gap-4">
    <div class="flex items-center gap-3 flex-1 min-w-[260px]">
      <label class="text-sm font-semibold text-gray-600 whitespace-nowrap">
        <i class="fas fa-calendar-alt text-red-500 mr-1"></i> Date Range
      </label>
      <input type="text" id="daterange" name="daterange"
             class="border border-gray-300 rounded-lg px-4 py-2 text-sm w-full max-w-xs focus:ring-2 focus:ring-red-500 focus:border-red-400 bg-gray-50"
             value="{{ date('m/d/Y') . ' - ' . date('m/d/Y') }}">
    </div>
    <div class="text-xs text-gray-400 italic hidden md:block">
      <i class="fas fa-info-circle mr-1"></i>
      All reports open in a new tab for easy printing &amp; export.
    </div>
  </div>

  {{-- ── SECTION: Ledger Reports ────────────────────────────────────────────── --}}
  <div class="mb-6">
    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
      <span class="w-5 h-px bg-gray-300 flex-shrink-0"></span>
      Ledger Reports
      <span class="flex-1 h-px bg-gray-100"></span>
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

      {{-- GSL Analytical Report --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-book text-white"></i>
          <span class="text-white text-sm font-semibold">Analytical Account (GSL)</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.gsl-report') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">Full ledger for a Sub-Ledger account with running balance &amp; opening.</p>
          <div class="mb-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Select GSL Account <span class="text-red-500">*</span></label>
            <div class="relative">
              <input type="text" class="gsl-filter w-full border border-gray-300 rounded-t-lg px-3 py-2 text-xs focus:ring-2 focus:ring-purple-400 bg-white" placeholder="&#xf002; Type to search GSL…" autocomplete="off">
            </div>
            <select name="GSL_code" required size="4"
                    class="gsl-select w-full border border-gray-300 border-t-0 rounded-b-lg px-2 py-1 text-xs bg-white focus:ring-2 focus:ring-purple-400">
              <option value="">— Select GSL —</option>
              @foreach($gslList as $g)
              <option value="{{ $g->GSL_code }}" data-name="{{ $g->GSL_name }}">{{ $g->GSL_code }} – {{ $g->GSL_name }}</option>
              @endforeach
            </select>
            <input type="hidden" name="GLS_name" class="gls-name-hidden">
          </div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate Report
          </button>
        </form>
      </div>

      {{-- GSL Trial Balance by GL --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-list-alt text-white"></i>
          <span class="text-white text-sm font-semibold">GSL Trial Balance</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.trial-bal-gl') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">Trial balance of all GSLs within a selected General Ledger group.</p>
          <div class="mb-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Select GL Group <span class="text-red-500">*</span></label>
            <select name="GL_name" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-blue-400 bg-white">
              <option value="">— Choose GL Group —</option>
              @foreach($glList as $gl)
              <option value="{{ $gl->GL_id }}">{{ $gl->GL_name }}</option>
              @endforeach
            </select>
          </div>
          <div class="mb-3 h-4"></div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate Report
          </button>
        </form>
      </div>

      {{-- GL Trial Balances --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-green-600 to-green-700 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-balance-scale text-white"></i>
          <span class="text-white text-sm font-semibold">GL Trial Balances</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.trial-balances') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">Summary trial balance across all General Ledger accounts as of the selected date.</p>
          <div class="mb-3 h-12"></div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-green-600 hover:bg-green-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate Report
          </button>
        </form>
      </div>

    </div>
  </div>

  {{-- ── SECTION: Profit & Loss ──────────────────────────────────────────────── --}}
  <div class="mb-6">
    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
      <span class="w-5 h-px bg-gray-300 flex-shrink-0"></span>
      Profit &amp; Loss
      <span class="flex-1 h-px bg-gray-100"></span>
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">

      {{-- P&L Statement --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-chart-line text-white"></i>
          <span class="text-white text-sm font-semibold">Profit &amp; Loss Statement</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.profit-loss') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">Overall income vs expense with net profit/loss for the selected period.</p>
          <div class="mb-3 h-12"></div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate Report
          </button>
        </form>
      </div>

      {{-- P&L by Department --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-orange-500 to-orange-600 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-sitemap text-white"></i>
          <span class="text-white text-sm font-semibold">P&amp;L — By Department</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.profit-loss-dept') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">Detailed GL-level profit &amp; loss breakdown for a single department.</p>
          <div class="mb-3">
            <label class="block text-xs font-medium text-gray-600 mb-1">Select Department <span class="text-red-500">*</span></label>
            <select name="dept" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-orange-400 bg-white"
                    onchange="this.form.querySelector('[name=dept_name]').value=this.options[this.selectedIndex].text">
              <option value="">— Choose Department —</option>
              @foreach($depts as $d)
              <option value="{{ $d->Code }}">{{ $d->Department }}</option>
              @endforeach
            </select>
            <input type="hidden" name="dept_name">
          </div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-orange-500 hover:bg-orange-600 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate Report
          </button>
        </form>
      </div>

      {{-- P&L Overall --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-rose-600 to-rose-700 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-globe text-white"></i>
          <span class="text-white text-sm font-semibold">P&amp;L — All Departments</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.profit-loss-overall') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">Side-by-side comparison of income &amp; expenses across every department.</p>
          <div class="mb-3 h-12"></div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate Report
          </button>
        </form>
      </div>

    </div>
  </div>

  {{-- ── SECTION: Cash, Bank & Vouchers ─────────────────────────────────────── --}}
  <div class="mb-6">
    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-3 flex items-center gap-2">
      <span class="w-5 h-px bg-gray-300 flex-shrink-0"></span>
      Cash, Bank &amp; Vouchers
      <span class="flex-1 h-px bg-gray-100"></span>
    </h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

      {{-- Cash Flow --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-cyan-600 to-cyan-700 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-water text-white"></i>
          <span class="text-white text-sm font-semibold">Cash Flow</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.cash-flow') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">Overall cash inflows and outflows for the period.</p>
          <div class="mb-3 h-8"></div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-cyan-600 hover:bg-cyan-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate
          </button>
        </form>
      </div>

      {{-- Cash Flow by GL --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-sky-600 to-sky-700 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-chart-area text-white"></i>
          <span class="text-white text-sm font-semibold">Cash Flow (by GL)</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.cash-flow-gsl') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">Drill-down cash flow for a GL group at GSL level.</p>
          <div class="mb-3">
            <select name="GL_id" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-sky-400 bg-white"
                    onchange="this.form.querySelector('[name=GL_name]').value=this.options[this.selectedIndex].text">
              <option value="">— Select GL —</option>
              @foreach($glList as $gl)
              <option value="{{ $gl->GL_id }}">{{ $gl->GL_name }}</option>
              @endforeach
            </select>
            <input type="hidden" name="GL_name">
          </div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-sky-600 hover:bg-sky-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate
          </button>
        </form>
      </div>

      {{-- Voucher Type Report --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-red-600 to-red-700 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-file-invoice text-white"></i>
          <span class="text-white text-sm font-semibold">Voucher Type Report</span>
        </div>
        <form method="POST" action="{{ route('accounts.report.voucher-type') }}" target="_blank" class="p-4">
          @csrf
          <input type="hidden" name="reservation" class="res-input">
          <p class="text-xs text-gray-400 mb-3">GL-level totals for a specific voucher type (CPV, BPV etc).</p>
          <div class="mb-3">
            <select name="vch_type" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-xs focus:ring-2 focus:ring-red-400 bg-white">
              <option value="">— Select Type —</option>
              <option value="CPV">CPV — Cash Payment</option>
              <option value="CRV">CRV — Cash Receipt</option>
              <option value="BPV">BPV — Bank Payment</option>
              <option value="BRV">BRV — Bank Receipt</option>
              <option value="JV">JV — Journal Voucher</option>
            </select>
          </div>
          <button type="submit" onclick="prepareForm(this)"
                  class="w-full py-2 px-4 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2">
            <i class="fas fa-external-link-alt"></i> Generate
          </button>
        </form>
      </div>

      {{-- Chart of Accounts --}}
      <div class="bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow overflow-hidden">
        <div class="bg-gradient-to-r from-gray-700 to-gray-800 px-4 py-3 flex items-center gap-2">
          <i class="fas fa-sitemap text-white"></i>
          <span class="text-white text-sm font-semibold">Chart of Accounts</span>
        </div>
        <div class="p-4">
          <p class="text-xs text-gray-400 mb-3">Full hierarchical chart of all accounts (Main → GL → GSL).</p>
          <div class="mb-3 h-8"></div>
          <a href="{{ route('accounts.coa') }}"
             class="block w-full py-2 px-4 bg-gray-700 hover:bg-gray-800 text-white rounded-lg text-xs font-semibold transition-colors text-center">
            <i class="fas fa-eye mr-1"></i> View Chart of Accounts
          </a>
        </div>
      </div>

    </div>
  </div>

</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>
$(document).ready(function() {
    $('#daterange').daterangepicker({
        opens: 'right',
        autoUpdateInput: true,
        locale: { format: 'MM/DD/YYYY', cancelLabel: 'Clear' }
    });
    $('#daterange').val('{{ date("m/d/Y") }} - {{ date("m/d/Y") }}');

    // Live filter for GSL select boxes
    document.querySelectorAll('.gsl-filter').forEach(function(input) {
        var sel = input.parentNode.parentNode.querySelector('.gsl-select');
        var allOptions = Array.from(sel.options);
        input.addEventListener('input', function() {
            var q = this.value.toLowerCase();
            allOptions.forEach(function(opt, i) {
                if (i === 0) return;
                opt.hidden = q.length > 0 && !opt.text.toLowerCase().includes(q);
            });
        });
    });
});

function prepareForm(btn) {
    var form = btn.closest('form');
    form.querySelector('.res-input').value = document.getElementById('daterange').value;
    // Set GSL name if present
    var gslSel = form.querySelector('.gsl-select');
    if (gslSel && gslSel.value) {
        var nameHidden = form.querySelector('.gls-name-hidden');
        var opt = gslSel.options[gslSel.selectedIndex];
        if (nameHidden && opt) nameHidden.value = opt.getAttribute('data-name') || opt.text;
    }
}
</script>
@endpush
