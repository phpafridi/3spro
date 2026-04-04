@extends('layouts.master')
@include('finance.accounts.sidebar')

@section('title', 'Finance Accounts — Reports')

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-4">
        <i class="fas fa-chart-bar text-red-500 mr-2"></i>Finance Reports
    </h2>

    {{-- Date Range Picker --}}
    <div class="mb-6 flex items-center gap-3">
        <label class="text-sm font-medium text-gray-700">Date Range:</label>
        <div class="relative">
            <input type="text" id="daterange" name="daterange"
                   class="border border-gray-300 rounded px-4 py-2 text-sm w-64 focus:ring-2 focus:ring-red-500"
                   value="{{ date('m/d/Y') . ' - ' . date('m/d/Y') }}">
        </div>
    </div>

    {{-- ── TRIAL BALANCE SECTION ── --}}
    <p class="text-xs font-bold text-gray-800 uppercase tracking-widest mb-3 mt-2">Trial Balances</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        {{-- GSL Analytical Report --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#dc2626;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-book mr-2"></i>Analytical Account (GSL)</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">Full ledger for a Sub-Ledger account with running balance &amp; opening.</p>
                <form method="POST" action="{{ route('accounts.report.gsl-report') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_gsl">
                    <label class="text-xs font-medium text-gray-700 mb-1 block">Select GSL Account <span class="text-red-500">*</span></label>
                    <input type="text" id="gsl_search" placeholder="Type to search GSL..."
                           class="w-full border border-gray-300 rounded px-3 py-1.5 text-xs mb-1 focus:ring-2 focus:ring-red-400">
                    <select name="GSL_code" id="gsl_select" required
                            class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs mb-3" size="4">
                        <option value="">— Select GSL —</option>
                        @foreach($gslList as $g)
                        <option value="{{ $g->GSL_code }}" data-name="{{ $g->GSL_name }}">
                            {{ $g->GSL_code }} – {{ $g->GSL_name }}
                        </option>
                        @endforeach
                    </select>
                    <input type="hidden" name="GLS_name" id="gls_name_hidden">
                    <button type="submit"
                            onclick="document.getElementById('res_gsl').value=document.getElementById('daterange').value;
                                     var sel=document.getElementById('gsl_select');
                                     document.getElementById('gls_name_hidden').value=sel.options[sel.selectedIndex]?.getAttribute('data-name')??'';"
                            class="w-full py-2 px-3 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

        {{-- GSL Trial Balance (GL-level) --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#2563eb;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-list-alt mr-2"></i>GSL Trial Balance</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">Trial balance of all GSLs within a selected General Ledger group.</p>
                <form method="POST" action="{{ route('accounts.report.trial-bal-gl') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_tbgl">
                    <label class="text-xs font-medium text-gray-700 mb-1 block">Select GL Group <span class="text-red-500">*</span></label>
                    <select name="GL_name" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs mb-3">
                        <option value="">— Choose GL Group —</option>
                        @foreach($glList as $gl)
                        <option value="{{ $gl->GL_id }}">{{ $gl->GL_name }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            onclick="document.getElementById('res_tbgl').value=document.getElementById('daterange').value;"
                            class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

        {{-- GL Trial Balances --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#16a34a;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-balance-scale mr-2"></i>GL Trial Balances</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">Summary trial balance across all General Ledger accounts as of the selected date.</p>
                <form method="POST" action="{{ route('accounts.report.trial-balances') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_trial">
                    <div class="mb-3 h-14"></div>
                    <button type="submit"
                            onclick="document.getElementById('res_trial').value=document.getElementById('daterange').value;"
                            class="w-full py-2 px-3 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- ── PROFIT & LOSS SECTION ── --}}
    <p class="text-xs font-bold text-gray-800 uppercase tracking-widest mb-3">Profit &amp; Loss</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        {{-- P&L Overall --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#e11d48;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-chart-line mr-2"></i>Profit &amp; Loss</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">Overall income vs expense with net profit/loss for the selected period.</p>
                <form method="POST" action="{{ route('accounts.report.profit-loss') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_pl">
                    <div class="mb-3 h-14"></div>
                    <button type="submit"
                            onclick="document.getElementById('res_pl').value=document.getElementById('daterange').value;"
                            class="w-full py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

        {{-- P&L Department --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#f97316;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-sitemap mr-2"></i>P&amp;L — By Department</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">Detailed GL-level profit &amp; loss breakdown for a single department.</p>
                <form method="POST" action="{{ route('accounts.report.profit-loss-dept') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_pld">
                    <label class="text-xs font-medium text-gray-700 mb-1 block">Select Department <span class="text-red-500">*</span></label>
                    <select name="dept" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs mb-3"
                            onchange="document.querySelector('[name=dept_name]').value=this.options[this.selectedIndex].text">
                        <option value="">— Choose Department —</option>
                        @foreach($depts as $d)
                        <option value="{{ $d->Code }}">{{ $d->Department }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="dept_name">
                    <button type="submit"
                            onclick="document.getElementById('res_pld').value=document.getElementById('daterange').value;"
                            class="w-full py-2 px-3 bg-orange-500 hover:bg-orange-600 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

        {{-- P&L Overall by Dept --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#b91c1c;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-globe mr-2"></i>P&amp;L — Overall (All Depts)</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">Side-by-side comparison of income &amp; expenses across every department.</p>
                <form method="POST" action="{{ route('accounts.report.profit-loss-overall') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_plo">
                    <div class="mb-3 h-14"></div>
                    <button type="submit"
                            onclick="document.getElementById('res_plo').value=document.getElementById('daterange').value;"
                            class="w-full py-2 px-3 bg-red-700 hover:bg-red-800 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- ── CASH FLOW SECTION ── --}}
    <p class="text-xs font-bold text-gray-800 uppercase tracking-widest mb-3">Cash Flow</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">

        {{-- Cash Flow Report --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#0891b2;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-water mr-2"></i>Cash Flow Report</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">Summary of all cash movements grouped by GL account for the period.</p>
                <form method="POST" action="{{ route('accounts.report.cash-flow') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_cf">
                    <div class="mb-3 h-14"></div>
                    <button type="submit"
                            onclick="document.getElementById('res_cf').value=document.getElementById('daterange').value;"
                            class="w-full py-2 px-3 bg-cyan-600 hover:bg-cyan-700 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

        {{-- Cash Flow by GL --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#0284c7;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-chart-area mr-2"></i>Cash Flow (by GL)</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">Detailed cash inflow &amp; outflow breakdown for a specific GL account.</p>
                <form method="POST" action="{{ route('accounts.report.cash-flow-gsl') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_cfg">
                    <label class="text-xs font-medium text-gray-700 mb-1 block">Select GL Account <span class="text-red-500">*</span></label>
                    <select name="GL_id" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs mb-3"
                            onchange="document.querySelector('[name=GL_name]').value=this.options[this.selectedIndex].text">
                        <option value="">— Select GL —</option>
                        @foreach($glList as $gl)
                        <option value="{{ $gl->GL_id }}">{{ $gl->GL_name }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="GL_name">
                    <button type="submit"
                            onclick="document.getElementById('res_cfg').value=document.getElementById('daterange').value;"
                            class="w-full py-2 px-3 bg-sky-600 hover:bg-sky-700 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

        {{-- Voucher Type Report --}}
        <div class="border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div style="background:#7c3aed;padding:12px 16px;">
                <h3 class="text-white font-semibold text-sm"><i class="fas fa-file-invoice mr-2"></i>Voucher Type Report</h3>
            </div>
            <div class="p-4">
                <p class="text-gray-500 text-xs mb-3">GL-level debit/credit totals filtered by a specific voucher type.</p>
                <form method="POST" action="{{ route('accounts.report.voucher-type') }}" target="_blank">
                    @csrf
                    <input type="hidden" name="reservation" id="res_vt">
                    <label class="text-xs font-medium text-gray-700 mb-1 block">Voucher Type <span class="text-red-500">*</span></label>
                    <select name="vch_type" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs mb-3">
                        <option value="">— Voucher Type —</option>
                        @foreach(['CPV','CRV','BPV','BRV','JV'] as $vt)
                        <option value="{{ $vt }}">{{ $vt }}</option>
                        @endforeach
                    </select>
                    <button type="submit"
                            onclick="document.getElementById('res_vt').value=document.getElementById('daterange').value;"
                            class="w-full py-2 px-3 bg-violet-600 hover:bg-violet-700 text-white rounded text-xs font-semibold transition">
                        <i class="fas fa-external-link-alt mr-1"></i>Generate Report
                    </button>
                </form>
            </div>
        </div>

    </div>

    {{-- Chart of Accounts --}}
    <a href="{{ route('accounts.coa') }}"
       class="inline-flex items-center px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm font-semibold transition shadow">
        <i class="fas fa-sitemap mr-2"></i>View Chart of Accounts
    </a>

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
        opens: 'left',
        autoUpdateInput: true,
        locale: { format: 'MM/DD/YYYY', cancelLabel: 'Clear' }
    });
    $('#daterange').val('{{ date("m/d/Y") }} - {{ date("m/d/Y") }}');

    // GSL live search filter
    $('#gsl_search').on('input', function() {
        var q = $(this).val().toLowerCase();
        $('#gsl_select option').each(function() {
            $(this).toggle(!q || $(this).text().toLowerCase().includes(q));
        });
    });
});
</script>
@endpush
