@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'DM Bills')

@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-file-invoice text-red-500 mr-2"></i> Pending DM / DMC Bills
    </h2>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm" id="dmTable">
            <thead class="bg-red-600">
                <tr>
                    @foreach(['Invoice','JC #','Customer','Vehicle Reg','SA','Type','Care Of','Labor','Parts','Sublet','Cons','Total','Date','Action'] as $h)
                    <th class="px-3 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($bills as $b)
                <tr class="hover:bg-yellow-50">
                    <td class="px-3 py-3 font-mono font-bold text-red-700">{{ $b->Invoice_id }}</td>
                    <td class="px-3 py-3 font-mono">{{ $b->Jobc_id }}</td>
                    <td class="px-3 py-3 font-medium text-gray-800">{{ $b->Customer_name }}</td>
                    <td class="px-3 py-3 text-gray-600">{{ $b->Veh_reg_no }}</td>
                    <td class="px-3 py-3 text-gray-500 text-xs">{{ $b->SA }}</td>
                    <td class="px-3 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $b->type === 'DMC' ? 'bg-red-100 text-purple-700' : 'bg-red-100 text-red-700' }}">
                            {{ $b->type }}
                        </span>
                    </td>
                    <td class="px-3 py-3 text-xs text-gray-600">{{ $b->careof }}</td>
                    <td class="px-3 py-3 text-right text-gray-700">{{ number_format($b->Lnet) }}</td>
                    <td class="px-3 py-3 text-right text-gray-700">{{ number_format($b->Pnet) }}</td>
                    <td class="px-3 py-3 text-right text-gray-700">{{ number_format($b->Snet) }}</td>
                    <td class="px-3 py-3 text-right text-gray-700">{{ number_format($b->Cnet) }}</td>
                    <td class="px-3 py-3 text-right font-bold text-red-600">Rs {{ number_format($b->Total) }}</td>
                    <td class="px-3 py-3 text-xs text-gray-500">{{ $b->bookingtime }}</td>
                    <td class="px-3 py-3">
                        <a href="{{ route('recovery.add-debt', ['id' => $b->Invoice_id]) }}"
                           class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs hover:bg-red-200 whitespace-nowrap">
                            <i class="fas fa-plus mr-1"></i>Add Debit
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="14" class="px-4 py-8 text-center text-gray-400">
                        No pending DM bills.
                    </td>
                </tr>
                @endforelse
            </tbody>
            @if($bills->count())
            <tfoot class="bg-gray-100 font-semibold">
                <tr>
                    <td colspan="7" class="px-3 py-3 text-right text-gray-700">Totals:</td>
                    <td class="px-3 py-3 text-right">{{ number_format($bills->sum('Lnet')) }}</td>
                    <td class="px-3 py-3 text-right">{{ number_format($bills->sum('Pnet')) }}</td>
                    <td class="px-3 py-3 text-right">{{ number_format($bills->sum('Snet')) }}</td>
                    <td class="px-3 py-3 text-right">{{ number_format($bills->sum('Cnet')) }}</td>
                    <td class="px-3 py-3 text-right text-red-700">Rs {{ number_format($bills->sum('Total')) }}</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>
@endsection

@push('scripts')
<link href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script>
$(function() {
    $('#dmTable').DataTable({ order: [[11, 'desc']], pageLength: 25 });
});
</script>
@endpush
