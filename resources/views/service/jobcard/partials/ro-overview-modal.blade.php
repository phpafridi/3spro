{{--
    RO Overview Modal — include this in any additional-* page.
    Requires: $jobId variable in scope.
    Usage:    @include('service.jobcard.partials.ro-overview-modal')
--}}

{{-- Floating Overview Button --}}
<button id="ro_overview_btn" onclick="roOverviewOpen()"
    class="fixed bottom-5 right-5 z-50 flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-full shadow-xl transition-colors">
    <i class="fa fa-clipboard-list"></i>
    <span>RO Overview</span>
    <span id="ro_overview_badge" class="hidden bg-white text-indigo-700 text-xs font-bold px-1.5 py-0.5 rounded-full"></span>
</button>

{{-- Backdrop --}}
<div id="ro_overview_backdrop" onclick="roOverviewClose()"
    class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden"></div>

{{-- Slide-in Drawer from right --}}
<div id="ro_overview_drawer"
    class="fixed top-0 right-0 h-full w-full max-w-2xl z-50 bg-white shadow-2xl flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">

    {{-- Drawer Header --}}
    <div class="flex items-center justify-between px-5 py-4 bg-indigo-600 shrink-0">
        <div>
            <h3 class="text-white font-bold text-base">RO# {{ $jobId }} — Full Overview</h3>
            <p id="ro_overview_vehicle" class="text-indigo-200 text-xs mt-0.5"></p>
        </div>
        <button onclick="roOverviewClose()" class="text-white hover:text-indigo-200 text-2xl leading-none">&times;</button>
    </div>

    {{-- Loading spinner --}}
    <div id="ro_overview_loading" class="flex-1 flex items-center justify-center">
        <div class="text-center text-gray-400">
            <i class="fa fa-spinner fa-spin text-3xl mb-2 block"></i>
            <span class="text-sm">Loading RO data…</span>
        </div>
    </div>

    {{-- Content (filled by JS) --}}
    <div id="ro_overview_content" class="hidden flex-1 overflow-hidden flex flex-col">

        {{-- Totals summary strip --}}
        <div class="grid grid-cols-5 gap-0 border-b border-gray-200 shrink-0 text-center">
            <div class="py-3 px-2 border-r border-gray-200">
                <div class="text-xs text-yellow-600 font-medium uppercase">Labor</div>
                <div id="tot_labor" class="text-sm font-bold text-yellow-700">0</div>
            </div>
            <div class="py-3 px-2 border-r border-gray-200">
                <div class="text-xs text-cyan-600 font-medium uppercase">Parts</div>
                <div id="tot_parts" class="text-sm font-bold text-cyan-700">0</div>
            </div>
            <div class="py-3 px-2 border-r border-gray-200">
                <div class="text-xs text-orange-600 font-medium uppercase">Consumable</div>
                <div id="tot_cons" class="text-sm font-bold text-orange-700">0</div>
            </div>
            <div class="py-3 px-2 border-r border-gray-200">
                <div class="text-xs text-blue-600 font-medium uppercase">Sublet</div>
                <div id="tot_sublet" class="text-sm font-bold text-blue-700">0</div>
            </div>
            <div class="py-3 px-2 bg-indigo-50">
                <div class="text-xs text-indigo-600 font-medium uppercase">Grand</div>
                <div id="tot_grand" class="text-sm font-bold text-indigo-700">0</div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="flex border-b border-gray-200 shrink-0 bg-gray-50">
            <button onclick="roTab('labor')"  id="tab_labor"  class="ro-tab-btn flex-1 py-2.5 text-xs font-semibold border-b-2 border-yellow-500 bg-white" style="color:#b45309">
                <i class="fa fa-wrench mr-1"></i>Labor <span id="cnt_labor"  class="ml-1 px-1.5 py-0.5 bg-yellow-100 text-yellow-700 rounded-full">0</span>
            </button>
            <button onclick="roTab('parts')"  id="tab_parts"  class="ro-tab-btn flex-1 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500">
                <i class="fa fa-cog mr-1"></i>Parts <span id="cnt_parts"  class="ml-1 px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full">0</span>
            </button>
            <button onclick="roTab('cons')"   id="tab_cons"   class="ro-tab-btn flex-1 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500">
                <i class="fa fa-flask mr-1"></i>Consumable <span id="cnt_cons"   class="ml-1 px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full">0</span>
            </button>
            <button onclick="roTab('sublet')" id="tab_sublet" class="ro-tab-btn flex-1 py-2.5 text-xs font-semibold border-b-2 border-transparent text-gray-500">
                <i class="fa fa-external-link mr-1"></i>Sublet <span id="cnt_sublet" class="ml-1 px-1.5 py-0.5 bg-gray-100 text-gray-600 rounded-full">0</span>
            </button>
        </div>

        {{-- Tab panels --}}
        <div class="flex-1 overflow-y-auto">
            <div id="panel_labor"></div>
            <div id="panel_parts"  class="hidden"></div>
            <div id="panel_cons"   class="hidden"></div>
            <div id="panel_sublet" class="hidden"></div>
        </div>
    </div>

    {{-- Error state --}}
    <div id="ro_overview_error" class="hidden flex-1 flex items-center justify-center text-red-500 text-sm">
        <i class="fa fa-exclamation-triangle mr-2"></i> Failed to load RO data.
    </div>
</div>

@push('styles')
<style>
#ro_overview_drawer { transition: transform 0.28s cubic-bezier(.4,0,.2,1); }
.ro-tab-btn { transition: color 0.15s, background 0.15s; }
.ro-tbl { width: 100%; border-collapse: collapse; font-size: 13px; }
.ro-tbl th { padding: 8px 14px; text-align: left; font-size: 10px; font-weight: 600; text-transform: uppercase; color: #6b7280; background: #f9fafb; position: sticky; top: 0; z-index: 1; border-bottom: 1px solid #e5e7eb; }
.ro-tbl td { padding: 8px 14px; border-bottom: 1px solid #f3f4f6; color: #374151; }
.ro-tbl tr:hover td { background: #f9fafb; }
.ro-tbl tfoot td { background: #f0fdf4; font-weight: 700; border-top: 2px solid #bbf7d0; padding: 10px 14px; }
.ro-empty { padding: 36px; text-align: center; color: #9ca3af; font-style: italic; font-size: 13px; }
.ro-badge { display: inline-block; padding: 1px 7px; border-radius: 9999px; font-size: 10px; font-weight: 600; }
</style>
@endpush

@push('scripts')
<script>
(function() {
var _jobId   = {{ $jobId }};
var _url     = '{{ route("jobcard.additional.overview-json", $jobId) }}';
var _data    = null;
var _fetched = false;

window.roOverviewOpen = function() {
    document.getElementById('ro_overview_backdrop').classList.remove('hidden');
    document.getElementById('ro_overview_drawer').classList.remove('translate-x-full');
    if (!_fetched) { _fetched = true; _fetch(); }
};
window.roOverviewClose = function() {
    document.getElementById('ro_overview_backdrop').classList.add('hidden');
    document.getElementById('ro_overview_drawer').classList.add('translate-x-full');
};

function _fetch() {
    show('ro_overview_loading');
    hide('ro_overview_content');
    hide('ro_overview_error');
    fetch(_url, { headers: {'X-Requested-With':'XMLHttpRequest'} })
        .then(function(r){ return r.json(); })
        .then(function(d){ _data = d; _render(d); })
        .catch(function(){ hide('ro_overview_loading'); show('ro_overview_error'); });
}

function _render(d) {
    hide('ro_overview_loading');
    show('ro_overview_content');

    var jc = d.jobcard;
    el('ro_overview_vehicle').textContent =
        [jc.Registration, jc.Customer_name, jc.mobile, jc.Variant].filter(Boolean).join('  ·  ');

    el('tot_labor').textContent  = fmt(d.totals.labor);
    el('tot_parts').textContent  = fmt(d.totals.parts);
    el('tot_cons').textContent   = fmt(d.totals.consumble);
    el('tot_sublet').textContent = fmt(d.totals.sublet);
    el('tot_grand').textContent  = fmt(d.totals.grand);

    el('cnt_labor').textContent  = d.labors.length;
    el('cnt_parts').textContent  = d.parts.length;
    el('cnt_cons').textContent   = d.consumbles.length;
    el('cnt_sublet').textContent = d.sublets.length;

    var total = d.labors.length + d.parts.length + d.consumbles.length + d.sublets.length;
    var badge = el('ro_overview_badge');
    badge.textContent = total;
    badge.classList.remove('hidden');

    // Labor
    el('panel_labor').innerHTML = buildTable(
        ['Labor / Job', 'Type', 'Cost', 'Status'],
        ['right:0','right:0','right:1','right:0'],
        d.labors,
        function(l) {
            return '<td style="font-weight:500">' + esc(l.Labor) + '</td>'
                 + '<td style="color:#6b7280">' + esc(l.type) + '</td>'
                 + '<td style="text-align:right">' + fmt(l.cost) + '</td>'
                 + '<td>' + badge_s(l.status, l.Additional) + '</td>';
        },
        function(rows) {
            var t = rows.filter(function(l){ return l.type === 'Workshop'; })
                        .reduce(function(s,l){ return s + parseFloat(l.cost||0); }, 0);
            return '<td colspan="2" style="text-align:right;color:#6b7280">Workshop Total:</td>'
                 + '<td style="text-align:right;color:#b45309">' + fmt(t) + '</td><td></td>';
        }
    );

    // Parts
    el('panel_parts').innerHTML = buildTable(
        ['Part Description', 'Qty', 'Unit Price', 'Total', 'Status'],
        ['right:0','right:0','right:1','right:1','right:0'],
        d.parts,
        function(p) {
            return '<td style="font-weight:500">' + esc(p.part_description) + '</td>'
                 + '<td>' + (p.qty||0) + '</td>'
                 + '<td style="text-align:right">' + fmt(p.unitprice) + '</td>'
                 + '<td style="text-align:right">' + fmt(p.total) + '</td>'
                 + '<td>' + badge_s(p.status, p.Additional) + '</td>';
        },
        function(rows) {
            var t = rows.reduce(function(s,p){ return s + parseFloat(p.total||0); }, 0);
            return '<td colspan="3" style="text-align:right;color:#6b7280">Total:</td>'
                 + '<td style="text-align:right;color:#0e7490">' + fmt(t) + '</td><td></td>';
        }
    );

    // Consumable
    el('panel_cons').innerHTML = buildTable(
        ['Description', 'Qty', 'Unit Price', 'Total', 'Status'],
        ['right:0','right:0','right:1','right:1','right:0'],
        d.consumbles,
        function(c) {
            var desc = c.cons_description || c.description || '-';
            return '<td style="font-weight:500">' + esc(desc) + '</td>'
                 + '<td>' + (c.qty||0) + '</td>'
                 + '<td style="text-align:right">' + fmt(c.unitprice) + '</td>'
                 + '<td style="text-align:right">' + fmt(c.total) + '</td>'
                 + '<td>' + badge_s(c.status, c.Additional) + '</td>';
        },
        function(rows) {
            var t = rows.reduce(function(s,c){ return s + parseFloat(c.total||0); }, 0);
            return '<td colspan="3" style="text-align:right;color:#6b7280">Total:</td>'
                 + '<td style="text-align:right;color:#c2410c">' + fmt(t) + '</td><td></td>';
        }
    );

    // Sublet
    el('panel_sublet').innerHTML = buildTable(
        ['Sublet', 'Type', 'Qty', 'Total', 'Status'],
        ['right:0','right:0','right:0','right:1','right:0'],
        d.sublets,
        function(s) {
            return '<td style="font-weight:500">' + esc(s.Sublet) + '</td>'
                 + '<td style="color:#6b7280">' + esc(s.type) + '</td>'
                 + '<td>' + (s.qty||0) + '</td>'
                 + '<td style="text-align:right">' + fmt(s.total) + '</td>'
                 + '<td>' + badge_s(s.status, s.additional) + '</td>';
        },
        function(rows) {
            var t = rows.filter(function(s){ return s.type === 'Workshop'; })
                        .reduce(function(acc,s){ return acc + parseFloat(s.total||0); }, 0);
            return '<td colspan="3" style="text-align:right;color:#6b7280">Workshop Total:</td>'
                 + '<td style="text-align:right;color:#1d4ed8">' + fmt(t) + '</td><td></td>';
        }
    );

    roTab('labor');
}

function buildTable(headers, _align, rows, rowFn, footFn) {
    var h = '<table class="ro-tbl"><thead><tr>'
          + headers.map(function(h){ return '<th>' + h + '</th>'; }).join('')
          + '</tr></thead><tbody>';
    if (rows.length === 0) {
        h += '<tr><td colspan="' + headers.length + '" class="ro-empty">Nothing added yet.</td></tr>';
    } else {
        rows.forEach(function(r){ h += '<tr>' + rowFn(r) + '</tr>'; });
        h += '</tbody><tfoot><tr>' + footFn(rows) + '</tr></tfoot>';
    }
    h += '</table>';
    return h;
}

window.roTab = function(name) {
    ['labor','parts','cons','sublet'].forEach(function(t) {
        el('panel_' + t).classList.add('hidden');
        var btn = el('tab_' + t);
        btn.classList.remove('bg-white');
        btn.classList.add('border-transparent');
        btn.classList.remove('border-yellow-500','border-cyan-500','border-orange-500','border-blue-500');
        btn.style.color = '#6b7280';
    });
    el('panel_' + name).classList.remove('hidden');
    var b = el('tab_' + name);
    b.classList.add('bg-white');
    b.classList.remove('border-transparent');
    var map = {
        labor:  {color:'#b45309', border:'border-yellow-500'},
        parts:  {color:'#0e7490', border:'border-cyan-500'},
        cons:   {color:'#c2410c', border:'border-orange-500'},
        sublet: {color:'#1d4ed8', border:'border-blue-500'},
    };
    b.style.color = map[name].color;
    b.classList.add(map[name].border);
};

function badge_s(s, additional) {
    if (additional == 1 && (!s || s == '0'))
        return '<span class="ro-badge" style="background:#dbeafe;color:#1d4ed8">Additional</span>';
    if (s && s != '0' && s != 0)
        return '<span class="ro-badge" style="background:#d1fae5;color:#065f46">' + esc(s) + '</span>';
    return '<span class="ro-badge" style="background:#fef9c3;color:#92400e">Pending</span>';
}

function fmt(n) {
    return parseFloat(n||0).toLocaleString('en', {minimumFractionDigits:2, maximumFractionDigits:2});
}
function esc(s) {
    if (!s) return '-';
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}
function el(id) { return document.getElementById(id); }
function show(id) { el(id).classList.remove('hidden'); }
function hide(id) { el(id).classList.add('hidden'); }

})();
</script>
@endpush
