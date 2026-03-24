@extends('layouts.master')
@include('finance.cashier.sidebar')

@section('title', 'Cashier - Pending Invoices')

@section('content')
<!-- Auto-refresh meta tag -->
<meta http-equiv="refresh" content="10">

<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">
            <i class="fas fa-clock text-yellow-500 mr-2"></i>
            Pending Invoices
        </h2>
        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-3 py-1 rounded-full">
            {{ count($pendingInvoices) }} Pending
        </span>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-blue-600 to-blue-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Jobcard#</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Vehicle</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Customer</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Mobile</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">MSI</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Registration</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Clock Off</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">SA</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pendingInvoices as $invoice)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">
                        <a href="{{ route('cashier.print-initial-ro', ['job_id' => $invoice->Jobc_id]) }}"
                           target="_blank"
                           class="text-blue-600 hover:text-blue-900">
                            #{{ $invoice->Jobc_id }}
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invoice->Variant }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invoice->Customer_name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invoice->mobile }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invoice->MSI_cat }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invoice->Registration }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                        {{ \Carbon\Carbon::parse($invoice->closing_time)->format('d-M g:i A') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">{{ $invoice->SA }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <form action="{{ route('cashier.invoice') }}" method="POST" target="_blank">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $invoice->Jobc_id }}">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded transition-colors">
                                <i class="fas fa-file-invoice mr-2"></i> Invoice
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                        <i class="fas fa-check-circle text-4xl mb-3 text-green-400"></i>
                        <p>No pending invoices found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Auto-refresh notification -->
<div class="fixed bottom-4 right-4 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-2 rounded shadow-lg text-sm">
    <i class="fas fa-sync-alt mr-2 animate-spin"></i> Page auto-refreshes every 10 seconds
</div>
@endsection
