@extends('layouts.master')
@include('finance.accountant.sidebar')
@section('title', 'Accountant - Jobcard Status')
@section('content')
    <div class="bg-white rounded shadow-sm p-6">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">
            <i class="fas fa-sign-out-alt text-red-500 mr-2"></i> Jobcard Status
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-red-600">
                    <tr>
                        @foreach (['JC #', 'Customer', 'Vehicle', 'Reg', 'Mobile', 'MSI', 'SA', 'Status', 'Invoice', 'Total', 'Rec', 'Action'] as $h)
                            <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">{{ $h }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($jobs as $j)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-mono">{{ $j->Jobc_id }}</td>
                            <td class="px-4 py-3">{{ $j->Customer_name }}</td>
                            <td class="px-4 py-3">{{ $j->Variant }}</td>
                            <td class="px-4 py-3">{{ $j->Registration }}</td>
                            <td class="px-4 py-3">{{ $j->mobile }}</td>
                            <td class="px-4 py-3">{{ $j->MSI_cat }}</td>
                            <td class="px-4 py-3">{{ $j->SA }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-medium
                            {{ $j->status == '2' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                                    {{ $j->status == '2' ? 'Ready' : 'Invoiced' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-mono">{{ $j->Invoice_id ?? '—' }}</td>
                            <td class="px-4 py-3">{{ $j->Total ? 'Rs ' . number_format($j->Total) : '—' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-1 rounded-full text-xs
    {{ $j->type == 'CM' || $j->type == 'DMC'
        ? 'bg-green-100 text-green-700'
        : ($j->type == 'DM'
            ? 'bg-red-100 text-red-700'
            : 'bg-yellow-100 text-yellow-700') }}">
                                    {{ $j->type}}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($j->Invoice_id)
                                    <a href="{{ route('cashier.print-invoice', ['id' => $j->Invoice_id]) }}" target="_blank"
                                        class="text-red-600 hover:underline text-xs">Print</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="12" class="px-4 py-8 text-center text-gray-400">No jobs found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
