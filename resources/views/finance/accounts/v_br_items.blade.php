@extends('layouts.master')
@include('finance.accounts.sidebar')
@section('title', 'Bank Receipt Voucher — Items')
@section('content')
<div class="bg-white rounded shadow-sm p-6">

    {-- Voucher Header --}
    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-list text-sky-500 mr-2"></i>Bank Receipt Voucher — Line Items
            </h2>
            <p class="text-sm text-gray-400 mt-0.5">
                Ref: <strong>{{ $master->RefNo ?? '' }}</strong> &nbsp;|&nbsp;
                Date: <strong>{{ $master->VoucherDate ?? '' }}</strong> &nbsp;|&nbsp;
                Book: <strong>{{ $master->BookNo ?? '' }}</strong>
                @if($master->Payee)
                &nbsp;|&nbsp; Payee: <strong>{{ $master->Payee }}</strong>
                @endif
            </p>
        </div>
        <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-full text-xs font-bold">BRV</span>
    </div>

    {-- Add Line Item Form --}
    <form method="POST" action="{{ route('accounts.brv.items', ['serial_number' => $serialNo]) }}"
          class="mb-6 p-4 bg-gray-50 rounded border border-gray-200">
        @csrf
        <input type="hidden" name="serial_number" value="{{ $serialNo }}">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-3">
            <div class="md:col-span-2">
                <label class="block text-xs font-medium text-gray-600 mb-1">GSL Code <span class="text-red-500">*</span></label>
                <select name="GSL_code" required
                        class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                    <option value="">-- Select GSL --</option>
                    @foreach($gslList as $g)
                    <option value="{{ $g->GSL_code }}">{{ $g->GSL_code }} – {{ $g->GSL_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Department</label>
                <select name="Department" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
                    <option value="0">-- Dept --</option>
                    @foreach($depts as $d)
                    <option value="{{ $d->Code }}">{{ $d->Department }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Payee / Party</label>
                <input type="text" name="payee" placeholder="Party name"
                       class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>
        </div>
        <div class="grid grid-cols-3 gap-3">
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Description / Narration</label>
                <input type="text" name="Description" placeholder="Narration"
                       class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Debit (Rs)</label>
                <input type="number" name="Debit" step="0.01" value="0" min="0"
                       class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm font-mono">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-600 mb-1">Credit (Rs)</label>
                <input type="number" name="Credit" step="0.01" value="0" min="0"
                       class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm font-mono">
            </div>
        </div>
        <button type="submit"
                class="mt-3 px-5 py-1.5 bg-sky-600 hover:bg-sky-700 text-white rounded text-sm font-medium">
            <i class="fas fa-plus mr-1"></i>Add Line
        </button>
    </form>

    {-- Items Table --}
    <div class="overflow-x-auto mb-6">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    @foreach(['#','GSL Code','Description','Department','Payee','Debit','Credit'] as $h)
                    <th class="px-4 py-2 text-left text-xs text-gray-600 uppercase">{{$h}}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($items as $i => $item)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-gray-400">{{$i+1}}</td>
                    <td class="px-4 py-2 font-mono font-bold text-sky-700">{{ $item->GSL_code }}</td>
                    <td class="px-4 py-2 text-xs">{{ $item->Description }}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">
                        @php $dept = $depts->firstWhere('Code', $item->Department); @endphp
                        {{ $dept->Department ?? ($item->Department ?: '—') }}
                    </td>
                    <td class="px-4 py-2 text-xs text-gray-500">{{ $item->payee }}</td>
                    <td class="px-4 py-2 font-mono text-red-600">{{ $item->Debit ? number_format($item->Debit,2) : '' }}</td>
                    <td class="px-4 py-2 font-mono text-green-600">{{ $item->Credit ? number_format($item->Credit,2) : '' }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-6 text-center text-gray-400">No line items yet. Add one above.</td></tr>
                @endforelse
            </tbody>
            <tfoot class="bg-gray-50 font-bold">
                <tr>
                    <td colspan="5" class="px-4 py-2 text-right text-sm text-gray-700">Totals:</td>
                    <td class="px-4 py-2 font-mono text-red-600">{{ number_format($items->sum('Debit'),2) }}</td>
                    <td class="px-4 py-2 font-mono text-green-600">{{ number_format($items->sum('Credit'),2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {-- Balance indicator --}
    @php $diff = abs($items->sum('Debit') - $items->sum('Credit')); @endphp
    @if(count($items) > 0)
    <div class="mb-4 p-3 rounded text-sm border
        {{ $diff == 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-yellow-50 text-yellow-700 border-yellow-200' }}">
        @if($diff == 0)
            <i class="fas fa-check-circle mr-2"></i>Balanced — Debit equals Credit.
        @else
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Unbalanced by <strong>Rs {{ number_format($diff,2) }}</strong> — balance before submitting.
        @endif
    </div>
    @endif

    {-- Submit for authentication --}
    <form method="POST" action="{{ route('accounts.brv.items', ['serial_number' => $serialNo]) }}"
          onsubmit="return confirm('Submit this voucher for authentication?')">
        @csrf
        <input type="hidden" name="serial_number" value="{{ $serialNo }}">
        <input type="hidden" name="Submitit" value="{{ $serialNo }}">
        <button type="submit"
                {{ (count($items) == 0 || $diff != 0) ? 'disabled' : '' }}
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded font-medium text-sm
                       disabled:opacity-40 disabled:cursor-not-allowed">
            <i class="fas fa-paper-plane mr-2"></i>Submit for Authentication
        </button>
    </form>
</div>
@endsection
