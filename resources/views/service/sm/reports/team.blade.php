@extends('layouts.master')
@section('title','Team Report')
@section('sidebar-menu')@include('service.partials.sm-sidebar')@endsection
@section('content')
<h2 class="text-xl font-bold text-gray-800 mb-4">Team Report — {{ $from }} to {{ $to }}</h2>
@include('service.sm.reports._filter',['showDates'=>true])
<div style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;overflow:hidden;">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead style="background:#dc2626;color:#fff;"><tr>
                <th class="px-3 py-2 text-left">Team</th>
                <th class="px-3 py-2 text-right">ROs</th>
                <th class="px-3 py-2 text-right">PM</th>
                <th class="px-3 py-2 text-right">GR</th>
                <th class="px-3 py-2 text-right">CBJ</th>
                <th class="px-3 py-2 text-right">Labor Revenue</th>
            </tr></thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($rows as $r)
                <tr class="hover:bg-red-50">
                    <td class="px-3 py-2 font-medium">{{ $r->team ?: 'Unassigned' }}</td>
                    <td class="px-3 py-2 text-right font-bold">{{ $r->ROs }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->PM }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->GR }}</td>
                    <td class="px-3 py-2 text-right">{{ $r->CBJ }}</td>
                    <td style="padding:6px 12px;text-align:right;font-weight:700;color:#dc2626;">{{ number_format($r->Labor,0) }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-4 py-8 text-center text-gray-400">No data</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
