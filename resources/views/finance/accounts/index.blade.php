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
        <label class="text-sm font-medium text-gray-600">Date Range:</label>
        <div class="relative">
            <input type="text" id="daterange" name="daterange"
                   class="border border-gray-300 rounded px-4 py-2 text-sm w-64 focus:ring-2 focus:ring-red-500"
                   value="{{ date('m/d/Y') . ' - ' . date('m/d/Y') }}">
        </div>
    </div>

    {{-- Report Buttons Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">

        {{-- GSL Analytical Report --}}
        <form method="POST" action="{{ route('accounts.report.gsl-report') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_gsl">
            <div class="mb-2">
                <select name="GSL_code" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs">
                    <option value="">-- Select GSL --</option>
                    @foreach($gslList as $g)
                    <option value="{{ $g->GSL_code }}" data-name="{{ $g->GSL_name }}">
                        {{ $g->GSL_code }} – {{ $g->GSL_name }}
                    </option>
                    @endforeach
                </select>
                <input type="hidden" name="GLS_name" id="gls_name_hidden">
            </div>
            <button type="submit" onclick="document.getElementById('res_gsl').value=document.getElementById('daterange').value;
                    var sel=document.querySelector('[name=GSL_code]'); document.getElementById('gls_name_hidden').value=sel.options[sel.selectedIndex].getAttribute('data-name');"
                    class="w-full py-2 px-3 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium">
                <i class="fas fa-book mr-1"></i>Analytical Account (GSL)
            </button>
        </form>

        {{-- GL Trial Balances --}}
        <form method="POST" action="{{ route('accounts.report.trial-balances') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_trial">
            <div class="mb-2 h-7"></div>
            <button type="submit" onclick="document.getElementById('res_trial').value=document.getElementById('daterange').value;"
                    class="w-full py-2 px-3 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-medium">
                <i class="fas fa-balance-scale mr-1"></i>GL Trial Balances
            </button>
        </form>

        {{-- GSL Trial Balance (GL-level) --}}
        <form method="POST" action="{{ route('accounts.report.trial-bal-gl') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_tbgl">
            <div class="mb-2">
                <select name="GL_name" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs">
                    <option value="">-- Select GL --</option>
                    @foreach($glList as $gl)
                    <option value="{{ $gl->GL_id }}">{{ $gl->GL_name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" onclick="document.getElementById('res_tbgl').value=document.getElementById('daterange').value;"
                    class="w-full py-2 px-3 bg-blue-600 hover:bg-blue-700 text-white rounded text-xs font-medium">
                <i class="fas fa-list-alt mr-1"></i>GSL Trial Balance
            </button>
        </form>

        {{-- Voucher Type Report --}}
        <form method="POST" action="{{ route('accounts.report.voucher-type') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_vt">
            <div class="mb-2">
                <select name="vch_type" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs">
                    <option value="">-- Voucher Type --</option>
                    @foreach(['CPV','CRV','BPV','BRV','JV'] as $vt)
                    <option value="{{ $vt }}">{{ $vt }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" onclick="document.getElementById('res_vt').value=document.getElementById('daterange').value;"
                    class="w-full py-2 px-3 bg-red-600 hover:bg-red-700 text-white rounded text-xs font-medium">
                <i class="fas fa-file-invoice mr-1"></i>Voucher Type Report
            </button>
        </form>

        {{-- Profit & Loss --}}
        <form method="POST" action="{{ route('accounts.report.profit-loss') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_pl">
            <div class="mb-2 h-7"></div>
            <button type="submit" onclick="document.getElementById('res_pl').value=document.getElementById('daterange').value;"
                    class="w-full py-2 px-3 bg-green-600 hover:bg-green-700 text-white rounded text-xs font-medium">
                <i class="fas fa-chart-line mr-1"></i>Profit & Loss
            </button>
        </form>

        {{-- P&L Department --}}
        <form method="POST" action="{{ route('accounts.report.profit-loss-dept') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_pld">
            <div class="mb-2">
                <select name="dept" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs"
                        onchange="document.querySelector('[name=dept_name]').value=this.options[this.selectedIndex].text">
                    <option value="">-- Department --</option>
                    @foreach($depts as $d)
                    <option value="{{ $d->Code }}">{{ $d->Department }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="dept_name">
            </div>
            <button type="submit" onclick="document.getElementById('res_pld').value=document.getElementById('daterange').value;"
                    class="w-full py-2 px-3 bg-orange-600 hover:bg-orange-700 text-white rounded text-xs font-medium">
                <i class="fas fa-sitemap mr-1"></i>P&L — Department
            </button>
        </form>

        {{-- P&L Overall --}}
        <form method="POST" action="{{ route('accounts.report.profit-loss-overall') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_plo">
            <div class="mb-2 h-7"></div>
            <button type="submit" onclick="document.getElementById('res_plo').value=document.getElementById('daterange').value;"
                    class="w-full py-2 px-3 bg-rose-600 hover:bg-rose-700 text-white rounded text-xs font-medium">
                <i class="fas fa-globe mr-1"></i>P&L — Overall
            </button>
        </form>

        {{-- Cash Flow Report --}}
        <form method="POST" action="{{ route('accounts.report.cash-flow') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_cf">
            <div class="mb-2 h-7"></div>
            <button type="submit" onclick="document.getElementById('res_cf').value=document.getElementById('daterange').value;"
                    class="w-full py-2 px-3 bg-cyan-600 hover:bg-cyan-700 text-white rounded text-xs font-medium">
                <i class="fas fa-water mr-1"></i>Cash Flow Report
            </button>
        </form>

        {{-- Cash Flow by GL --}}
        <form method="POST" action="{{ route('accounts.report.cash-flow-gsl') }}" target="_blank">
            @csrf
            <input type="hidden" name="reservation" id="res_cfg">
            <div class="mb-2">
                <select name="GL_id" required class="w-full border border-gray-300 rounded px-2 py-1.5 text-xs"
                        onchange="document.querySelector('[name=GL_name]').value=this.options[this.selectedIndex].text">
                    <option value="">-- Select GL --</option>
                    @foreach($glList as $gl)
                    <option value="{{ $gl->GL_id }}">{{ $gl->GL_name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="GL_name">
            </div>
            <button type="submit" onclick="document.getElementById('res_cfg').value=document.getElementById('daterange').value;"
                    class="w-full py-2 px-3 bg-sky-600 hover:bg-sky-700 text-white rounded text-xs font-medium">
                <i class="fas fa-chart-area mr-1"></i>Cash Flow (by GL)
            </button>
        </form>

        {{-- Chart of Accounts --}}
        <a href="{{ route('accounts.coa') }}"
           class="flex items-center justify-center py-2 px-3 bg-gray-700 hover:bg-gray-800 text-white rounded text-xs font-medium mt-7">
            <i class="fas fa-sitemap mr-1"></i>View Chart of Accounts
        </a>

    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush

@push('scripts')
<!-- jQuery first (required for daterangepicker) -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

<script>
$(document).ready(function() {
    $('#daterange').daterangepicker({
        opens: 'left',
        autoUpdateInput: true,
        locale: {
            format: 'MM/DD/YYYY',
            cancelLabel: 'Clear'
        }
    });

    // Set initial value
    $('#daterange').val('{{ date("m/d/Y") }} - {{ date("m/d/Y") }}');
});
</script>
@endpush
