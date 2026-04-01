@extends('layouts.master')
@section('title', 'Additional Jobs')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')
<div class="bg-white rounded shadow-sm p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Additional Jobs</h2>
        <input type="text" id="search_input" placeholder="Search..."
               class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200" id="jobs_table">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Jobcard #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Reg #</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Customer</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Open Date</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">MSI Cat</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $job)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-4 py-3 text-sm font-bold text-gray-900">
                        #{{ $job->Jobc_id }}
                        <button onclick="showOverview({{ $job->Jobc_id }})"
                            class="ml-1 px-2 py-0.5 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 text-xs rounded transition-colors">
                            <i class="fa fa-eye"></i> View
                        </button>
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $job->Customer_name }}</td>
                    <td class="px-4 py-3 text-sm text-gray-500">
                        {{ \Carbon\Carbon::parse($job->Open_date_time)->format('d/m/Y g:i A') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-gray-500">{{ $job->MSI_cat }}</td>
                    <td class="px-4 py-3">
                        <div class="flex flex-wrap gap-1">
                            <a href="{{ route('jobcard.additional.jobrequest', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-yellow-500 hover:bg-yellow-600 text-white text-xs rounded">JobRequest</a>
                            <a href="{{ route('jobcard.additional.part', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-cyan-600 hover:bg-cyan-700 text-white text-xs rounded">Parts</a>
                            <a href="{{ route('jobcard.additional.sublet', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded">Sublet</a>
                            <a href="{{ route('jobcard.additional.consumable', $job->Jobc_id) }}"
                               class="px-2 py-1 bg-orange-500 hover:bg-orange-600 text-white text-xs rounded">Consumble</a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-10 text-center text-gray-400">
                        <i class="fa fa-inbox text-3xl block mb-2"></i>
                        No jobs in workshop.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Backdrop --}}
<div id="ov_backdrop" onclick="closeOverview()"
    style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.45);"></div>

{{-- Modal --}}
<div id="ov_modal"
    style="display:none;position:fixed;top:50%;left:50%;transform:translate(-50%,-50%);z-index:10000;
           background:#fff;border-radius:12px;box-shadow:0 20px 60px rgba(0,0,0,0.25);
           width:90%;max-width:820px;max-height:85vh;flex-direction:column;overflow:hidden;">

    <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 20px;background:#4f46e5;flex-shrink:0;">
        <div>
            <div id="ov_title" style="color:#fff;font-weight:700;font-size:15px;">RO Overview</div>
            <div id="ov_sub"   style="color:#c7d2fe;font-size:12px;margin-top:2px;"></div>
        </div>
        <button onclick="closeOverview()" style="background:none;border:none;color:#fff;font-size:26px;cursor:pointer;line-height:1;">&times;</button>
    </div>

    <div id="ov_loading" style="padding:48px;text-align:center;color:#9ca3af;">
        <i class="fa fa-spinner fa-spin" style="font-size:28px;display:block;margin-bottom:8px;"></i>
        Loading&hellip;
    </div>
    <div id="ov_error" style="display:none;padding:48px;text-align:center;color:#ef4444;">
        <i class="fa fa-exclamation-triangle"></i> Failed to load data.
    </div>

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
                <div id="ov_tot_cons" style="font-size:14px;font-weight:700;color:#c2410c;">0</div>
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
            <button onclick="ovTab('labor')"  id="ov_tab_labor"
                style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-bottom:2px solid #f59e0b;background:#fff;color:#b45309;cursor:pointer;">
                <i class="fa fa-wrench"></i> Labor
                <span id="ov_cnt_labor" style="background:#fef3c7;color:#b45309;padding:1px 6px;border-radius:9999px;font-size:11px;">0</span>
            </button>
            <button onclick="ovTab('parts')"  id="ov_tab_parts"
                style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-bottom:2px solid transparent;background:transparent;color:#6b7280;cursor:pointer;">
                <i class="fa fa-cog"></i> Parts
                <span id="ov_cnt_parts" style="background:#f3f4f6;color:#6b7280;padding:1px 6px;border-radius:9999px;font-size:11px;">0</span>
            </button>
            <button onclick="ovTab('cons')"   id="ov_tab_cons"
                style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-bottom:2px solid transparent;background:transparent;color:#6b7280;cursor:pointer;">
                <i class="fa fa-flask"></i> Consumable
                <span id="ov_cnt_cons" style="background:#f3f4f6;color:#6b7280;padding:1px 6px;border-radius:9999px;font-size:11px;">0</span>
            </button>
            <button onclick="ovTab('sublet')" id="ov_tab_sublet"
                style="flex:1;padding:9px 4px;font-size:12px;font-weight:600;border:none;border-bottom:2px solid transparent;background:transparent;color:#6b7280;cursor:pointer;">
                <i class="fa fa-external-link"></i> Sublet
                <span id="ov_cnt_sublet" style="background:#f3f4f6;color:#6b7280;padding:1px 6px;border-radius:9999px;font-size:11px;">0</span>
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
.ov-sect-hdr { padding:6px 14px;font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;background:#f1f5f9;color:#64748b;border-bottom:1px solid #e5e7eb; }
.ov-empty { padding:24px;text-align:center;color:#9ca3af;font-style:italic;font-size:13px; }
.ov-pill { display:inline-block;padding:1px 7px;border-radius:9999px;font-size:10px;font-weight:600; }
</style>
@endpush

@push('scripts')
<script>
(function(){
    var _baseUrl = '{{ url("service/jobcard") }}';
    var _tabCfg = {
        labor:  {color:'#b45309', border:'#f59e0b', bg:'#fef3c7'},
        parts:  {color:'#0e7490', border:'#06b6d4', bg:'#cffafe'},
        cons:   {color:'#c2410c', border:'#f97316', bg:'#ffedd5'},
        sublet: {color:'#1d4ed8', border:'#3b82f6', bg:'#dbeafe'},
    };

    window.showOverview = function(jobId) {
        document.getElementById('ov_backdrop').style.display = 'block';
        document.getElementById('ov_modal').style.display    = 'flex';
        document.getElementById('ov_title').textContent      = 'RO #' + jobId + ' \u2014 Overview';
        document.getElementById('ov_sub').textContent        = '';
        document.getElementById('ov_loading').style.display  = 'block';
        document.getElementById('ov_error').style.display    = 'none';
        document.getElementById('ov_content').style.display  = 'none';

        fetch(_baseUrl + '/' + jobId + '/additional/overview-json', {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
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
            [jc.Registration, jc.Customer_name, jc.mobile, jc.Variant].filter(Boolean).join('  \u00b7  ');

        document.getElementById('ov_tot_labor').textContent  = _fmt(d.totals.labor);
        document.getElementById('ov_tot_parts').textContent  = _fmt(d.totals.parts);
        document.getElementById('ov_tot_cons').textContent   = _fmt(d.totals.consumble);
        document.getElementById('ov_tot_sublet').textContent = _fmt(d.totals.sublet);
        document.getElementById('ov_tot_grand').textContent  = _fmt(d.totals.grand);

        document.getElementById('ov_cnt_labor').textContent  = d.labors.length;
        document.getElementById('ov_cnt_parts').textContent  = d.parts.length;
        document.getElementById('ov_cnt_cons').textContent   = d.consumbles.length;
        document.getElementById('ov_cnt_sublet').textContent = d.sublets.length;

        // Labor: Standard + Additional sections
        document.getElementById('ov_panel_labor').innerHTML = _tblSections(
            ['Labor / Job','Type','Cost','Status'],
            d.labors,
            function(r){ return '<td style="font-weight:500">'+_e(r.Labor)+'</td><td style="color:#6b7280">'+_e(r.type)+'</td><td style="text-align:right">'+_fmt(r.cost)+'</td><td>'+_pill(r.status,r.Additional)+'</td>'; },
            function(rows){ var t=rows.filter(function(r){return r.type==='Workshop';}).reduce(function(s,r){return s+parseFloat(r.cost||0);},0); return '<td colspan="2" style="text-align:right;color:#6b7280">Workshop Total:</td><td style="text-align:right;color:#b45309">'+_fmt(t)+'</td><td></td>'; }
        );

        // Parts: Standard + Additional sections
        document.getElementById('ov_panel_parts').innerHTML = _tblSections(
            ['Part Description','Qty','Unit Price','Total','Status'],
            d.parts,
            function(r){ return '<td style="font-weight:500">'+_e(r.part_description)+'</td><td>'+(r.qty||0)+'</td><td style="text-align:right">'+_fmt(r.unitprice)+'</td><td style="text-align:right">'+_fmt(r.total)+'</td><td>'+_pill(r.status,r.Additional)+'</td>'; },
            function(rows){ var t=rows.reduce(function(s,r){return s+parseFloat(r.total||0);},0); return '<td colspan="3" style="text-align:right;color:#6b7280">Total:</td><td style="text-align:right;color:#0e7490">'+_fmt(t)+'</td><td></td>'; }
        );

        // Consumable: Standard + Additional sections
        document.getElementById('ov_panel_cons').innerHTML = _tblSections(
            ['Description','Qty','Unit Price','Total','Status'],
            d.consumbles,
            function(r){ return '<td style="font-weight:500">'+_e(r.cons_description||r.description)+'</td><td>'+(r.qty||0)+'</td><td style="text-align:right">'+_fmt(r.unitprice)+'</td><td style="text-align:right">'+_fmt(r.total)+'</td><td>'+_pill(r.status,r.Additional)+'</td>'; },
            function(rows){ var t=rows.reduce(function(s,r){return s+parseFloat(r.total||0);},0); return '<td colspan="3" style="text-align:right;color:#6b7280">Total:</td><td style="text-align:right;color:#c2410c">'+_fmt(t)+'</td><td></td>'; }
        );

        // Sublet: Standard + Additional sections
        document.getElementById('ov_panel_sublet').innerHTML = _tblSections(
            ['Sublet','Type','Qty','Total','Status'],
            d.sublets,
            function(r){ return '<td style="font-weight:500">'+_e(r.Sublet)+'</td><td style="color:#6b7280">'+_e(r.type)+'</td><td>'+(r.qty||0)+'</td><td style="text-align:right">'+_fmt(r.total)+'</td><td>'+_pill(r.status,r.additional)+'</td>'; },
            function(rows){ var t=rows.filter(function(r){return r.type==='Workshop';}).reduce(function(s,r){return s+parseFloat(r.total||0);},0); return '<td colspan="3" style="text-align:right;color:#6b7280">Workshop Total:</td><td style="text-align:right;color:#1d4ed8">'+_fmt(t)+'</td><td></td>'; }
        );

        ovTab('labor');
    }

    // Render table split into Standard / Additional sections
    function _tblSections(headers, rows, rowFn, footFn) {
        var std = rows.filter(function(r){ return !r.Additional || r.Additional == 0; });
        var add = rows.filter(function(r){ return r.Additional == 1; });
        var cols = headers.length;
        var h = '<table class="ov-tbl">';
        h += '<thead><tr>'+headers.map(function(x){return '<th>'+x+'</th>';}).join('')+'</tr></thead>';
        h += '<tbody>';

        // Standard section
        h += '<tr><td colspan="'+cols+'" class="ov-sect-hdr" style="background:#f8fafc;color:#374151;">Standard ('+std.length+')</td></tr>';
        if(std.length === 0) {
            h += '<tr><td colspan="'+cols+'" class="ov-empty">No standard items.</td></tr>';
        } else {
            std.forEach(function(r){ h += '<tr>'+rowFn(r)+'</tr>'; });
        }

        // Additional section
        h += '<tr><td colspan="'+cols+'" class="ov-sect-hdr" style="background:#eff6ff;color:#1d4ed8;">Additional ('+add.length+')</td></tr>';
        if(add.length === 0) {
            h += '<tr><td colspan="'+cols+'" class="ov-empty">No additional items.</td></tr>';
        } else {
            add.forEach(function(r){ h += '<tr style="background:#fafbff;">'+rowFn(r)+'</tr>'; });
        }

        h += '</tbody>';
        if(rows.length > 0) {
            h += '<tfoot><tr>'+footFn(rows)+'</tr></tfoot>';
        }
        h += '</table>';
        return h;
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

    function _pill(s, additional) {
        if(additional==1 && (!s||s=='0')) return '<span class="ov-pill" style="background:#dbeafe;color:#1d4ed8">Additional</span>';
        if(s && s!='0' && s!=0)          return '<span class="ov-pill" style="background:#d1fae5;color:#065f46">'+_e(s)+'</span>';
        return '<span class="ov-pill" style="background:#fef9c3;color:#92400e">Pending</span>';
    }
    function _fmt(n){ return parseFloat(n||0).toLocaleString('en',{minimumFractionDigits:2,maximumFractionDigits:2}); }
    function _e(s){ if(!s)return '-'; return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }

    // Search filter
    document.getElementById('search_input').addEventListener('keyup', function() {
        var val = this.value.toLowerCase();
        document.querySelectorAll('#jobs_table tbody tr').forEach(function(tr) {
            tr.style.display = tr.textContent.toLowerCase().includes(val) ? '' : 'none';
        });
    });
})();
</script>
@endpush
@endsection
