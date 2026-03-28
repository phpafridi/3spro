@extends('layouts.master')
@section('title', 'JobCards - Unclosed')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Open Repair Orders</h2>
        <a href="{{ route('jobcard.add-vehicle') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors">
            <i class="fa fa-plus mr-2"></i> Open New RO
        </a>
    </div>
    @if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
    @if(session('error'))<div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>@endif
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Jobcard#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Vehicle</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Registration</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Open Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($unclosedJobs ?? [] as $job)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">
                        #{{ $job->Jobc_id }}
                        <button onclick="showOverview({{ $job->Jobc_id }})"
                            class="ml-1 px-2 py-0.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-xs rounded transition-colors">
                            <i class="fa fa-eye"></i> View
                        </button>
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Variant }}</td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ \Carbon\Carbon::parse($job->Open_date_time)->format('d/m/Y g:i A') }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            <a href="{{ route('jobcard.additional.jobrequest', $job->Jobc_id) }}" class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">JobRequest</a>
                            <a href="{{ route('jobcard.additional.part', $job->Jobc_id) }}" class="px-2 py-1 bg-cyan-600 hover:bg-cyan-700 text-white text-xs rounded transition-colors">Spare Parts</a>
                            <a href="{{ route('jobcard.additional.sublet', $job->Jobc_id) }}" class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded transition-colors">Sublet</a>
                            <a href="{{ route('jobcard.additional.consumable', $job->Jobc_id) }}" class="px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded transition-colors">Consumble</a>
                            @if($job->status == 0)
                            <form method="POST" action="{{ route('jobcard.start-working') }}" class="inline"
                                  onsubmit="return confirm('Send RO #{{ $job->Jobc_id }} to workshop?')">
                                @csrf
                                <input type="hidden" name="job_id"         value="{{ $job->Jobc_id }}">
                                <input type="hidden" name="comp_appointed" value="{{ $job->comp_appointed }}">
                                <button type="submit" class="px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors">Start Working</button>
                            </form>
                            @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-semibold">In Workshop</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-8 text-center text-gray-400"><i class="fa fa-inbox text-3xl block mb-2"></i>No open jobcards found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── RO Overview Popup ─────────────────────────────────────────── --}}

{{-- Backdrop --}}
<div id="ov_backdrop" onclick="closeOverview()"
    style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45);"></div>

{{-- Modal --}}
<div id="ov_modal"
    style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;
           background:#fff;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.25);
           width:90%;max-width:780px;max-height:85vh;flex-direction:column;overflow:hidden;">

    {{-- Header --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#4f46e5;flex-shrink:0;">
        <div>
            <div id="ov_title" style="color:#fff;font-weight:700;font-size:15px;">RO Overview</div>
            <div id="ov_sub"   style="color:#c7d2fe;font-size:12px;margin-top:2px;"></div>
        </div>
        <button onclick="closeOverview()" style="background:none;border:none;color:#fff;font-size:26px;cursor:pointer;line-height:1;">&times;</button>
    </div>

    {{-- Loading --}}
    <div id="ov_loading" style="padding:48px;text-align:center;color:#9ca3af;">
        <i class="fa fa-spinner fa-spin" style="font-size:28px;display:block;margin-bottom:8px;"></i>
        Loading…
    </div>

    {{-- Error --}}
    <div id="ov_error" style="display:none;padding:48px;text-align:center;color:#ef4444;">
        <i class="fa fa-exclamation-triangle"></i> Failed to load data.
    </div>

    {{-- Content --}}
    <div id="ov_content" style="display:none;flex:1;flex-direction:column;overflow:hidden;">

        {{-- Totals strip --}}
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

        {{-- Tabs --}}
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

        {{-- Panels --}}
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
            function(r){ return '<td style="font-weight:500">'+_e(r.part_description)+'</td><td>'+( r.qty||0)+'</td><td style="text-align:right">'+_fmt(r.unitprice)+'</td><td style="text-align:right">'+_fmt(r.total)+'</td><td>'+_pill(r.status,r.Additional)+'</td>'; },
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
