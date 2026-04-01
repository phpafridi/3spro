@extends('layouts.master')
@section('title', 'View RO — #' . $jobId)
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection

@section('content')

@php
    $laborTotal     = $labors->where('type', 'Workshop')->sum('cost');
    $partsTotal     = $parts->sum('total');
    $consumbleTotal = $consumbles->sum('total');
    $subletTotal    = $sublets->where('type', 'Workshop')->sum('total');
    $grandTotal     = $laborTotal + $partsTotal + $consumbleTotal + $subletTotal;
@endphp

{{-- Header --}}
<div class="bg-white rounded shadow-sm p-4 mb-4">
    <div class="flex flex-wrap items-start justify-between gap-3">
        <div>
            {{-- RO # with Eye Icon --}}
            <div class="flex items-center gap-2 mb-1">
                <h2 class="text-xl font-bold text-gray-800">RO# {{ $jobId }}</h2>
                <button onclick="showOverview({{ $jobId }})"
                    class="inline-flex items-center gap-1 px-2 py-0.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-xs font-semibold rounded transition-colors">
                    <i class="fa fa-eye"></i> View
                </button>
            </div>
            <div class="mt-1 flex flex-wrap gap-4 text-sm text-gray-600">
                <span><i class="fa fa-car mr-1 text-gray-400"></i> <span class="font-medium text-red-600">{{ $jobcard->Registration }}</span></span>
                <span><i class="fa fa-user mr-1 text-gray-400"></i> {{ $jobcard->Customer_name }}</span>
                <span><i class="fa fa-phone mr-1 text-gray-400"></i> {{ $jobcard->mobile }}</span>
                <span><i class="fa fa-tag mr-1 text-gray-400"></i> {{ $jobcard->Variant ?? '-' }}</span>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('jobcard.additional.jobrequest', $jobId) }}" class="px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-wrench mr-1"></i> Add Labor
            </a>
            <a href="{{ route('jobcard.additional.part', $jobId) }}" class="px-3 py-1.5 bg-cyan-600 hover:bg-cyan-700 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-cog mr-1"></i> Add Parts
            </a>
            <a href="{{ route('jobcard.additional.sublet', $jobId) }}" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-external-link mr-1"></i> Add Sublet
            </a>
            <a href="{{ route('jobcard.additional.consumable', $jobId) }}" class="px-3 py-1.5 bg-orange-500 hover:bg-orange-600 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-flask mr-1"></i> Consumable
            </a>
            <a href="{{ route('jobcard.additional-list') }}" class="px-3 py-1.5 bg-gray-500 hover:bg-gray-600 text-white text-xs font-medium rounded transition-colors">
                <i class="fa fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>
</div>

{{-- Grand Total Summary Bar --}}
<div class="grid grid-cols-2 sm:grid-cols-5 gap-3 mb-4">
    <div class="bg-yellow-50 border border-yellow-200 rounded p-3 text-center">
        <div class="text-xs text-yellow-600 font-medium uppercase mb-1">Labor</div>
        <div class="text-lg font-bold text-yellow-700">{{ number_format($laborTotal, 0) }}</div>
    </div>
    <div class="bg-cyan-50 border border-cyan-200 rounded p-3 text-center">
        <div class="text-xs text-cyan-600 font-medium uppercase mb-1">Parts</div>
        <div class="text-lg font-bold text-cyan-700">{{ number_format($partsTotal, 0) }}</div>
    </div>
    <div class="bg-orange-50 border border-orange-200 rounded p-3 text-center">
        <div class="text-xs text-orange-600 font-medium uppercase mb-1">Consumable</div>
        <div class="text-lg font-bold text-orange-700">{{ number_format($consumbleTotal, 0) }}</div>
    </div>
    <div class="bg-blue-50 border border-blue-200 rounded p-3 text-center">
        <div class="text-xs text-blue-600 font-medium uppercase mb-1">Sublet</div>
        <div class="text-lg font-bold text-blue-700">{{ number_format($subletTotal, 0) }}</div>
    </div>
    <div class="bg-green-600 rounded p-3 text-center col-span-2 sm:col-span-1">
        <div class="text-xs text-green-100 font-medium uppercase mb-1">Grand Total</div>
        <div class="text-lg font-bold text-white">{{ number_format($grandTotal, 0) }}</div>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-2 gap-4">

    {{-- Labor --}}
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-yellow-500">
            <h3 class="text-sm font-semibold text-white"><i class="fa fa-wrench mr-2"></i>Labor
                <span class="ml-1 px-1.5 py-0.5 bg-yellow-400 text-white text-xs rounded-full">{{ $labors->count() }}</span>
            </h3>
            <a href="{{ route('jobcard.additional.jobrequest', $jobId) }}" class="text-xs text-yellow-100 hover:text-white">+ Add</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labor</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Cost</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($labors as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ $l->Labor }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $l->type }}</td>
                    <td class="px-3 py-2 text-sm text-right font-medium text-gray-700">{{ number_format($l->cost, 0) }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if($l->Additional == 1 && (!$l->status || $l->status == '0'))
                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full">Additional</span>
                        @elseif($l->status && $l->status != '0')
                            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full">{{ $l->status }}</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No labor added.</td></tr>
                @endforelse
            </tbody>
            @if($labors->count() > 0)
            <tfoot class="bg-yellow-50 border-t-2 border-yellow-200">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Total:</td>
                    <td class="px-3 py-2 text-sm font-bold text-yellow-700 text-right">{{ number_format($laborTotal, 0) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- Parts --}}
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-cyan-600">
            <h3 class="text-sm font-semibold text-white"><i class="fa fa-cog mr-2"></i>Parts
                <span class="ml-1 px-1.5 py-0.5 bg-cyan-500 text-white text-xs rounded-full">{{ $parts->count() }}</span>
            </h3>
            <a href="{{ route('jobcard.additional.part', $jobId) }}" class="text-xs text-cyan-100 hover:text-white">+ Add</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($parts as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ $p->part_description }}</td>
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $p->qty }}</td>
                    <td class="px-3 py-2 text-sm text-right font-medium text-gray-700">{{ number_format($p->total, 0) }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if($p->Additional == 1 && $p->status == 0)
                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full">Additional</span>
                        @elseif($p->status >= 1)
                            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full">Issued</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No parts added.</td></tr>
                @endforelse
            </tbody>
            @if($parts->count() > 0)
            <tfoot class="bg-cyan-50 border-t-2 border-cyan-200">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Total:</td>
                    <td class="px-3 py-2 text-sm font-bold text-cyan-700 text-right">{{ number_format($partsTotal, 0) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- Consumable --}}
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-orange-500">
            <h3 class="text-sm font-semibold text-white"><i class="fa fa-flask mr-2"></i>Consumable
                <span class="ml-1 px-1.5 py-0.5 bg-orange-400 text-white text-xs rounded-full">{{ $consumbles->count() }}</span>
            </h3>
            <a href="{{ route('jobcard.additional.consumable', $jobId) }}" class="text-xs text-orange-100 hover:text-white">+ Add</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($consumbles as $c)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ $c->consumble_description ?? $c->description ?? '-' }}</td>
                    <td class="px-3 py-2 text-sm text-gray-500">{{ $c->qty ?? 1 }}</td>
                    <td class="px-3 py-2 text-sm text-right font-medium text-gray-700">{{ number_format($c->total ?? 0, 0) }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if(isset($c->status) && $c->status)
                            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full">Issued</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No consumables added.</td></tr>
                @endforelse
            </tbody>
            @if($consumbles->count() > 0)
            <tfoot class="bg-orange-50 border-t-2 border-orange-200">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Total:</td>
                    <td class="px-3 py-2 text-sm font-bold text-orange-700 text-right">{{ number_format($consumbleTotal, 0) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    {{-- Sublets --}}
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 bg-blue-600">
            <h3 class="text-sm font-semibold text-white"><i class="fa fa-external-link mr-2"></i>Sublets
                <span class="ml-1 px-1.5 py-0.5 bg-blue-500 text-white text-xs rounded-full">{{ $sublets->count() }}</span>
            </h3>
            <a href="{{ route('jobcard.additional.sublet', $jobId) }}" class="text-xs text-blue-100 hover:text-white">+ Add</a>
        </div>
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Sublet</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                    <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 uppercase">Total</th>
                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($sublets as $s)
                <tr class="hover:bg-gray-50">
                    <td class="px-3 py-2 text-sm text-gray-800">{{ $s->Sublet }}</td>
                    <td class="px-3 py-2 text-xs text-gray-500">{{ $s->type }}</td>
                    <td class="px-3 py-2 text-sm text-right font-medium text-gray-700">{{ number_format($s->total, 0) }}</td>
                    <td class="px-3 py-2 text-xs">
                        @if($s->additional == 1 && !$s->status)
                            <span class="px-1.5 py-0.5 bg-blue-100 text-blue-700 rounded-full">Additional</span>
                        @elseif($s->status)
                            <span class="px-1.5 py-0.5 bg-green-100 text-green-700 rounded-full">{{ $s->status }}</span>
                        @else
                            <span class="px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">Pending</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-3 py-4 text-center text-gray-400 text-sm italic">No sublets added.</td></tr>
                @endforelse
            </tbody>
            @if($sublets->count() > 0)
            <tfoot class="bg-blue-50 border-t-2 border-blue-200">
                <tr>
                    <td colspan="2" class="px-3 py-2 text-xs font-semibold text-gray-600 text-right">Total:</td>
                    <td class="px-3 py-2 text-sm font-bold text-blue-700 text-right">{{ number_format($subletTotal, 0) }}</td>
                    <td></td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

</div>

{{-- Grand total footer --}}
<div class="mt-4 bg-green-600 rounded p-4 flex items-center justify-between">
    <span class="text-white font-semibold text-sm">Grand Total (Workshop charges)</span>
    <span class="text-white text-xl font-bold">{{ number_format($grandTotal, 2) }}</span>
</div>

{{-- ── RO Overview Popup (same as jobcard index) ─────────────────── --}}

<div id="ov_backdrop" onclick="closeOverview()"
    style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45);"></div>

<div id="ov_modal"
    style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;
           background:#fff;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.25);
           width:90%;max-width:780px;max-height:85vh;flex-direction:column;overflow:hidden;">

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#4f46e5;flex-shrink:0;">
        <div>
            <div id="ov_title" style="color:#fff;font-weight:700;font-size:15px;">RO Overview</div>
            <div id="ov_sub"   style="color:#c7d2fe;font-size:12px;margin-top:2px;"></div>
        </div>
        <button onclick="closeOverview()" style="background:none;border:none;color:#fff;font-size:26px;cursor:pointer;line-height:1;">&times;</button>
    </div>

    <div id="ov_loading" style="padding:48px;text-align:center;color:#9ca3af;">
        <i class="fa fa-spinner fa-spin" style="font-size:28px;display:block;margin-bottom:8px;"></i>
        Loading…
    </div>

    <div id="ov_error" style="display:none;padding:48px;text-align:center;color:#ef4444;">
        <i class="fa fa-exclamation-triangle"></i> Failed to load data.
    </div>

    <div id="ov_content" style="display:none;flex:1;flex-direction:column;overflow:hidden;">

        <div style="display:grid;grid-template-columns:repeat(5,1fr);border-bottom:1px solid #e5e7eb;text-align:center;flex-shrink:0;">
            <div style="padding:12px 6px;border-right:1px solid #e5e7eb;">
                <div style="font-size:10px;color:#d97706;font-weight:600;text-transform:uppercase;">Labor</div>
                <div id="ov_tot_labor" style="font-size:14px;font-weight:700;color:#b45309;">0</div>
            </div>
            <div style="padding:12px 6px;border-right:1px solid #e5e7eb;">
                <div style="font-size:10px;color:#0891b2;font-weight:600;text-transform:uppercase;">Parts</div>
                <div id="ov_tot_parts" style="font-size:14px;font-weight:700;color:#0e7490;">0</div>
            </div>
            <div style="padding:12px 6px;border-right:1px solid #e5e7eb;">
                <div style="font-size:10px;color:#ea580c;font-weight:600;text-transform:uppercase;">Consumable</div>
                <div id="ov_tot_cons"  style="font-size:14px;font-weight:700;color:#c2410c;">0</div>
            </div>
            <div style="padding:12px 6px;border-right:1px solid #e5e7eb;">
                <div style="font-size:10px;color:#2563eb;font-weight:600;text-transform:uppercase;">Sublet</div>
                <div id="ov_tot_sublet" style="font-size:14px;font-weight:700;color:#1d4ed8;">0</div>
            </div>
            <div style="padding:12px 6px;background:#eef2ff;">
                <div style="font-size:10px;color:#4f46e5;font-weight:600;text-transform:uppercase;">Grand Total</div>
                <div id="ov_tot_grand" style="font-size:14px;font-weight:700;color:#4338ca;">0</div>
            </div>
        </div>

        <div style="display:flex;border-bottom:1px solid #e5e7eb;background:#f9fafb;flex-shrink:0;">
            <button onclick="ovTab('labor')"  id="ov_tab_labor"  style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-bottom:2px solid #f59e0b;background:#fff;color:#b45309;cursor:pointer;">
                <i class="fa fa-wrench"></i> Labor <span id="ov_cnt_labor"  style="background:#fef3c7;color:#b45309;padding:1px 6px;border-radius:9999px;font-size:11px;">0</span>
            </button>
            <button onclick="ovTab('parts')"  id="ov_tab_parts"  style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-bottom:2px solid transparent;background:transparent;color:#6b7280;cursor:pointer;">
                <i class="fa fa-cog"></i> Parts <span id="ov_cnt_parts"  style="background:#f3f4f6;color:#6b7280;padding:1px 6px;border-radius:9999px;font-size:11px;">0</span>
            </button>
            <button onclick="ovTab('cons')"   id="ov_tab_cons"   style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-bottom:2px solid transparent;background:transparent;color:#6b7280;cursor:pointer;">
                <i class="fa fa-flask"></i> Consumable <span id="ov_cnt_cons"   style="background:#f3f4f6;color:#6b7280;padding:1px 6px;border-radius:9999px;font-size:11px;">0</span>
            </button>
            <button onclick="ovTab('sublet')" id="ov_tab_sublet" style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-bottom:2px solid transparent;background:transparent;color:#6b7280;cursor:pointer;">
                <i class="fa fa-external-link"></i> Sublet <span id="ov_cnt_sublet" style="background:#f3f4f6;color:#6b7280;padding:1px 6px;border-radius:9999px;font-size:11px;">0</span>
            </button>
        </div>

        <div style="flex:1;overflow-y:auto;">
            <div id="ov_panel_labor"></div>
            <div id="ov_panel_parts"  style="display:none;"></div>
            <div id="ov_panel_cons"   style="display:none;"></div>
            <div id="ov_panel_sublet" style="display:none;"></div>
        </div>
    </div>
</div>

@push('styles')
<style>
.ov-tbl { width:100%;border-collapse:collapse;font-size:13px; }
.ov-tbl th { padding:8px 14px;text-align:left;font-size:10px;font-weight:600;text-transform:uppercase;color:#6b7280;background:#f9fafb;position:sticky;top:0;border-bottom:1px solid #e5e7eb; }
.ov-tbl td { padding:8px 14px;border-bottom:1px solid #f3f4f6;color:#374151; }
.ov-tbl tr:hover td { background:#f9fafb; }
.ov-tbl tfoot td { background:#f0fdf4;font-weight:700;border-top:2px solid #bbf7d0;padding:10px 14px; }
.ov-empty { padding:32px;text-align:center;color:#9ca3af;font-style:italic;font-size:13px; }
.ov-pill { display:inline-block;padding:1px 7px;border-radius:9999px;font-size:10px;font-weight:600; }
</style>
@endpush

@push('scripts')
<script>
(function(){
    var _baseUrl = '{{ url("service/jobcard") }}';
    var _tabCfg  = {
        labor:  {color:'#b45309',border:'#f59e0b',bg:'#fef3c7'},
        parts:  {color:'#0e7490',border:'#06b6d4',bg:'#cffafe'},
        cons:   {color:'#c2410c',border:'#f97316',bg:'#ffedd5'},
        sublet: {color:'#1d4ed8',border:'#3b82f6',bg:'#dbeafe'},
    };

    window.showOverview = function(jobId) {
        document.getElementById('ov_backdrop').style.display = 'block';
        document.getElementById('ov_modal').style.display    = 'flex';
        document.getElementById('ov_title').textContent      = 'RO #' + jobId + ' — Overview';
        document.getElementById('ov_sub').textContent        = '';
        document.getElementById('ov_loading').style.display  = 'block';
        document.getElementById('ov_error').style.display    = 'none';
        document.getElementById('ov_content').style.display  = 'none';

        fetch(_baseUrl + '/' + jobId + '/additional/overview-json', {
            headers: {'X-Requested-With':'XMLHttpRequest'}
        })
        .then(function(r){ if(!r.ok) throw new Error(r.status); return r.json(); })
        .then(function(d){ _render(d); })
        .catch(function(){
            document.getElementById('ov_loading').style.display = 'none';
            document.getElementById('ov_error').style.display   = 'block';
        });
    };

    window.closeOverview = function() {
        document.getElementById('ov_backdrop').style.display = 'none';
        document.getElementById('ov_modal').style.display    = 'none';
    };

    function _render(d) {
        document.getElementById('ov_loading').style.display  = 'none';
        document.getElementById('ov_content').style.display  = 'flex';

        var jc = d.jobcard;
        document.getElementById('ov_sub').textContent =
            [jc.Registration, jc.Customer_name, jc.mobile, jc.Variant].filter(Boolean).join('  ·  ');

        document.getElementById('ov_tot_labor').textContent  = _fmt(d.totals.labor);
        document.getElementById('ov_tot_parts').textContent  = _fmt(d.totals.parts);
        document.getElementById('ov_tot_cons').textContent   = _fmt(d.totals.consumble);
        document.getElementById('ov_tot_sublet').textContent = _fmt(d.totals.sublet);
        document.getElementById('ov_tot_grand').textContent  = _fmt(d.totals.grand);

        document.getElementById('ov_cnt_labor').textContent  = d.labors.length;
        document.getElementById('ov_cnt_parts').textContent  = d.parts.length;
        document.getElementById('ov_cnt_cons').textContent   = d.consumbles.length;
        document.getElementById('ov_cnt_sublet').textContent = d.sublets.length;

        document.getElementById('ov_panel_labor').innerHTML = _tbl(
            ['Labor / Job','Type','Cost','Status'], d.labors,
            function(r){ return '<td style="font-weight:500">'+_e(r.Labor)+'</td><td style="color:#6b7280">'+_e(r.type)+'</td><td style="text-align:right">'+_fmt(r.cost)+'</td><td>'+_pill(r.status,r.Additional)+'</td>'; },
            function(rows){ var t=rows.filter(function(r){return r.type==='Workshop';}).reduce(function(s,r){return s+parseFloat(r.cost||0);},0); return '<td colspan="2" style="text-align:right;color:#6b7280">Workshop Total:</td><td style="text-align:right;color:#b45309">'+_fmt(t)+'</td><td></td>'; }
        );
        document.getElementById('ov_panel_parts').innerHTML = _tbl(
            ['Part Description','Qty','Unit Price','Total','Status'], d.parts,
            function(r){ return '<td style="font-weight:500">'+_e(r.part_description)+'</td><td>'+(r.qty||0)+'</td><td style="text-align:right">'+_fmt(r.unitprice)+'</td><td style="text-align:right">'+_fmt(r.total)+'</td><td>'+_pill(r.status,r.Additional)+'</td>'; },
            function(rows){ var t=rows.reduce(function(s,r){return s+parseFloat(r.total||0);},0); return '<td colspan="3" style="text-align:right;color:#6b7280">Total:</td><td style="text-align:right;color:#0e7490">'+_fmt(t)+'</td><td></td>'; }
        );
        document.getElementById('ov_panel_cons').innerHTML = _tbl(
            ['Description','Qty','Unit Price','Total','Status'], d.consumbles,
            function(r){ return '<td style="font-weight:500">'+_e(r.cons_description||r.description)+'</td><td>'+(r.qty||0)+'</td><td style="text-align:right">'+_fmt(r.unitprice)+'</td><td style="text-align:right">'+_fmt(r.total)+'</td><td>'+_pill(r.status,r.Additional)+'</td>'; },
            function(rows){ var t=rows.reduce(function(s,r){return s+parseFloat(r.total||0);},0); return '<td colspan="3" style="text-align:right;color:#6b7280">Total:</td><td style="text-align:right;color:#c2410c">'+_fmt(t)+'</td><td></td>'; }
        );
        document.getElementById('ov_panel_sublet').innerHTML = _tbl(
            ['Sublet','Type','Qty','Total','Status'], d.sublets,
            function(r){ return '<td style="font-weight:500">'+_e(r.Sublet)+'</td><td style="color:#6b7280">'+_e(r.type)+'</td><td>'+(r.qty||0)+'</td><td style="text-align:right">'+_fmt(r.total)+'</td><td>'+_pill(r.status,r.additional)+'</td>'; },
            function(rows){ var t=rows.filter(function(r){return r.type==='Workshop';}).reduce(function(s,r){return s+parseFloat(r.total||0);},0); return '<td colspan="3" style="text-align:right;color:#6b7280">Workshop Total:</td><td style="text-align:right;color:#1d4ed8">'+_fmt(t)+'</td><td></td>'; }
        );

        ovTab('labor');
    }

    window.ovTab = function(name) {
        ['labor','parts','cons','sublet'].forEach(function(t){
            document.getElementById('ov_panel_'+t).style.display         = 'none';
            document.getElementById('ov_tab_'+t).style.borderBottomColor = 'transparent';
            document.getElementById('ov_tab_'+t).style.background        = 'transparent';
            document.getElementById('ov_tab_'+t).style.color             = '#6b7280';
            document.getElementById('ov_cnt_'+t).style.background        = '#f3f4f6';
            document.getElementById('ov_cnt_'+t).style.color             = '#6b7280';
        });
        var c = _tabCfg[name];
        document.getElementById('ov_panel_'+name).style.display         = 'block';
        document.getElementById('ov_tab_'+name).style.borderBottomColor = c.border;
        document.getElementById('ov_tab_'+name).style.background        = '#fff';
        document.getElementById('ov_tab_'+name).style.color             = c.color;
        document.getElementById('ov_cnt_'+name).style.background        = c.bg;
        document.getElementById('ov_cnt_'+name).style.color             = c.color;
    };

    function _tbl(headers, rows, rowFn, footFn) {
        var h = '<table class="ov-tbl"><thead><tr>'+headers.map(function(x){return '<th>'+x+'</th>';}).join('')+'</tr></thead><tbody>';
        if (!rows||rows.length===0) { h+='<tr><td colspan="'+headers.length+'" class="ov-empty">Nothing added yet.</td></tr></tbody>'; }
        else { rows.forEach(function(r){h+='<tr>'+rowFn(r)+'</tr>';}); h+='</tbody><tfoot><tr>'+footFn(rows)+'</tr></tfoot>'; }
        return h+'</table>';
    }
    function _pill(s,additional){
        if(additional==1&&(!s||s=='0')) return '<span class="ov-pill" style="background:#dbeafe;color:#1d4ed8">Additional</span>';
        if(s&&s!='0'&&s!=0) return '<span class="ov-pill" style="background:#d1fae5;color:#065f46">'+_e(s)+'</span>';
        return '<span class="ov-pill" style="background:#fef9c3;color:#92400e">Pending</span>';
    }
    function _fmt(n){ return parseFloat(n||0).toLocaleString('en',{minimumFractionDigits:2,maximumFractionDigits:2}); }
    function _e(s){ if(!s)return '-'; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
})();
</script>
@endpush
@endsection
