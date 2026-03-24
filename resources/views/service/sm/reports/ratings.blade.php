@extends('layouts.master')
@section('title','Customer Ratings')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Customer Ratings — {{ $from }} to {{ $to }}</h2>
@include('service.sm.reports._filter',['showDates'=>true])

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-5">
    {{-- By SA --}}
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
        <div style="background:#dc2626;padding:10px 16px;"><h3 style="font-weight:600;color:#fff;font-size:13px;">Ratings by SA</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background:#f9fafb;"><tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">SA</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Behaviour</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Professionalism</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Expertise</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bySa as $r)
                    <tr class="hover:bg-red-50">
                        <td class="px-3 py-2 font-medium">{{ $r->SA }}</td>
                        <td class="px-3 py-2 text-right">{{ $r->total }}</td>
                        <td style="padding:6px 12px;text-align:right;color:{{ $r->behv>=4 ? '#16a34a' : ($r->behv>=3 ? '#d97706' : '#ef4444') }}">{{ number_format($r->behv,2) }}</td>
                        <td style="padding:6px 12px;text-align:right;color:{{ $r->prof>=4 ? '#16a34a' : ($r->prof>=3 ? '#d97706' : '#ef4444') }}">{{ number_format($r->prof,2) }}</td>
                        <td style="padding:6px 12px;text-align:right;color:{{ $r->exprt>=4 ? '#16a34a' : ($r->exprt>=3 ? '#d97706' : '#ef4444') }}">{{ number_format($r->exprt,2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-400">No ratings data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- By RO Type --}}
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
        <div style="background:#b91c1c;padding:10px 16px;"><h3 style="font-weight:600;color:#fff;font-size:13px;">Ratings by RO Type</h3></div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead style="background:#f9fafb;"><tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Management</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Services</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Prices</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Cleanliness</th>
                </tr></thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($byType as $r)
                    <tr class="hover:bg-red-50">
                        <td class="px-3 py-2 font-medium">{{ $r->RO_type }}</td>
                        <td class="px-3 py-2 text-right">{{ $r->total }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($r->mgmt,2) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($r->svc,2) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($r->prices,2) }}</td>
                        <td class="px-3 py-2 text-right">{{ number_format($r->clean,2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-4 py-6 text-center text-gray-400">No data</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
