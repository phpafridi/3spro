@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovery Statistics')

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-chart-bar text-red-500 mr-2"></i> Recovery Status — All Accounts
        </h2>
        <div class="text-sm text-gray-500">
            Total records: <strong>{{ $stats->count() }}</strong>
            &nbsp;|&nbsp; Outstanding: <strong class="text-red-600">
                Rs {{ number_format($stats->where('balance', '>', 0)->sum('balance')) }}
            </strong>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm" id="statsTable">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Customer</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Contact</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Vehicle</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Last Debit</th>
                    <th class="px-3 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Total Debt</th>
                    <th class="px-3 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Total Credit</th>
                    <th class="px-3 py-3 text-right text-xs font-medium text-white uppercase tracking-wider">Balance</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Last Followup</th>
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($stats as $s)
                @php $bal = (float)$s->balance; @endphp
                <tr class="hover:bg-gray-50 {{ $bal <= 0 ? 'bg-green-50' : '' }}">
                    <td class="px-3 py-3 font-medium text-gray-800">{{ $s->cust_name }}</td>
                    <td class="px-3 py-3 text-gray-600">{{ $s->contact }}</td>
                    <td class="px-3 py-3 text-gray-600">{{ $s->Vehicle_name }}</td>
                    <td class="px-3 py-3 text-gray-500 text-xs">{{ $s->last_db_date }}</td>
                    <td class="px-3 py-3 text-right text-red-600 font-medium">
                        Rs {{ number_format($s->total_debt) }}
                    </td>
                    <td class="px-3 py-3 text-right text-green-600 font-medium">
                        Rs {{ number_format($s->total_credit) }}
                    </td>
                    <td class="px-3 py-3 text-right font-bold {{ $bal <= 0 ? 'text-green-700' : 'text-red-700' }}">
                        Rs {{ number_format($bal) }}
                    </td>
                    <td class="px-3 py-3 text-xs {{ $s->last_followup ? 'text-gray-600' : 'text-red-500 font-medium' }}">
                        {{ $s->last_followup ?? 'Never contacted' }}
                    </td>
                    <td class="px-3 py-3">
                        <div class="flex gap-2 text-xs">
                            <a href="{{ route('recovery.customer-ledger', ['id' => $s->cust_name]) }}"
                               class="text-red-600 hover:text-indigo-900" title="Ledger">
                                <i class="fas fa-book"></i>
                            </a>
                            <a href="{{ route('recovery.followup', ['id' => $s->cust_name]) }}"
                               class="text-green-600 hover:text-green-900" title="Followup">
                                <i class="fas fa-phone"></i>
                            </a>
                            <a href="{{ route('recovery.clearance', ['id' => $s->cust_name]) }}"
                               class="text-orange-600 hover:text-orange-900" title="Clearance">
                                <i class="fas fa-check-circle"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-4 py-8 text-center text-gray-400">No recovery records found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(function() {
    $('#statsTable').DataTable({
        order: [[6, 'desc']],
        pageLength: 25
    });
});
</script>
@endpush
