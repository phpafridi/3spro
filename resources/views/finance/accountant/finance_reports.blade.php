@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Finance Reports')

@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-chart-bar text-indigo-500 mr-2"></i> Finance / Service Reports
    </h2>

    {{-- Date range --}}
    <div class="mb-6">
        <input type="text" id="fin_reservation"
               class="border border-gray-300 rounded-lg px-3 py-2 text-sm w-72"
               value="{{ date('m/d/Y') }} - {{ date('m/d/Y') }}">
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
        {{-- All Types --}}
        <form method="POST" action="{{ route('cashier.all-report') }}" target="_blank">
            @csrf
            <input type="hidden" name="daterange" id="dt_all">
            <button type="submit" onclick="document.getElementById('dt_all').value=document.getElementById('fin_reservation').value;"
                class="w-full px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl text-sm font-medium">
                All Types
            </button>
        </form>

        @foreach(['CM','DM','DMC','COMP','GW','JND','FFS','PDS','WC','CBJ','CNI'] as $type)
        <form method="POST" action="{{ route('cashier.report-download') }}" target="_blank">
            @csrf
            <input type="hidden" name="type" value="{{ $type }}">
            <input type="hidden" name="daterange" id="dt_{{ $type }}">
            <button type="submit"
                onclick="document.getElementById('dt_{{ $type }}').value=document.getElementById('fin_reservation').value;"
                class="w-full px-3 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-medium">
                {{ $type }}
            </button>
        </form>
        @endforeach

        {{-- Business Summary --}}
        <form method="POST" action="{{ route('cashier.business-summary') }}" target="_blank">
            @csrf
            <input type="hidden" name="daterange" id="dt_summary">
            <button type="submit" onclick="document.getElementById('dt_summary').value=document.getElementById('fin_reservation').value;"
                class="w-full px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium">
                Business Summary
            </button>
        </form>

        {{-- MSI Report --}}
        <form method="POST" action="{{ route('cashier.msi-report') }}" target="_blank">
            @csrf
            <input type="hidden" name="daterange" id="dt_msi">
            <button type="submit" onclick="document.getElementById('dt_msi').value=document.getElementById('fin_reservation').value;"
                class="w-full px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-xl text-sm font-medium">
                MSI Report
            </button>
        </form>

        {{-- PM Export --}}
        <form method="POST" action="{{ route('cashier.pm-export') }}" target="_blank">
            @csrf
            <input type="hidden" name="daterange" id="dt_pm">
            <button type="submit" onclick="document.getElementById('dt_pm').value=document.getElementById('fin_reservation').value;"
                class="w-full px-3 py-2 bg-orange-600 hover:bg-orange-700 text-white rounded-xl text-sm font-medium">
                PM Export (Excel)
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script src="https://cdn.jsdelivr.net/npm/moment/moment.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script>$('#fin_reservation').daterangepicker({locale:{format:'MM/DD/YYYY'}});</script>
@endpush
