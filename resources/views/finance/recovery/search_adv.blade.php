@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Advanced Search')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">
        <i class="fas fa-search-plus text-indigo-500 mr-2"></i> Advanced Search
    </h2>
    <form method="GET" action="{{ route('recovery.search-adv') }}" class="flex gap-3 mb-6">
        <input type="text" name="query" value="{{ request('query') }}"
            placeholder="Name / Contact / Invoice / Registration..."
            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <button class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium">Search</button>
    </form>
    @if($results !== null)
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    @foreach(['Customer','Contact','Vehicle','Reg','Invoice','Date','Amount'] as $h)
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-600 uppercase">{{ $h }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($results as $r)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <a href="{{ route('recovery.customer-ledger', ['id'=>$r->cust_name]) }}" class="text-indigo-600 hover:underline">{{ $r->cust_name }}</a>
                    </td>
                    <td class="px-4 py-3">{{ $r->contact }}</td>
                    <td class="px-4 py-3">{{ $r->Vehicle_name }}</td>
                    <td class="px-4 py-3">{{ $r->Registration }}</td>
                    <td class="px-4 py-3 font-mono">{{ $r->Invoice_no }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $r->Db_date }}</td>
                    <td class="px-4 py-3 font-medium">Rs {{ number_format($r->Debt_amount) }}</td>
                </tr>
                @empty
                <tr><td colspan="7" class="px-4 py-8 text-center text-gray-400">No results found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
