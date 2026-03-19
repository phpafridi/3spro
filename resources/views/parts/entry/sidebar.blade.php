{{-- resources/views/parts/entry/sidebar.blade.php --}}

<style>
.parts-nav-wrap {
    position: relative;
    flex: 1;
    min-height: 0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.parts-nav {
    flex: 1;
    overflow-y: scroll;
    scrollbar-width: none;
    -ms-overflow-style: none;
    padding-bottom: 8px;
}
.parts-nav::-webkit-scrollbar { display: none; }

.parts-nav a, .parts-nav button {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 12px;
    border-radius: 8px;
    font-size: 13px;
    font-weight: 400;
    color: rgba(255,255,255,0.75);
    text-decoration: none;
    transition: background 0.15s, color 0.15s;
    width: 100%;
    border: none;
    background: none;
    cursor: pointer;
    text-align: left;
    line-height: 1.4;
    box-sizing: border-box;
}
.parts-nav a:hover, .parts-nav button:hover {
    background: rgba(255,255,255,0.1);
    color: #fff;
}
.parts-nav a.active {
    background: rgba(255,255,255,0.18);
    color: #fff;
    font-weight: 500;
}
.parts-nav .nav-icon {
    width: 16px;
    text-align: center;
    font-size: 13px;
    flex-shrink: 0;
    opacity: 0.8;
}
.parts-nav .nav-section-label {
    padding: 6px 12px 4px;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: rgba(255,255,255,0.35);
}
.parts-nav .nav-divider {
    margin: 6px 0;
    border: none;
    border-top: 1px solid rgba(255,255,255,0.12);
}
.parts-nav .dropdown-children {
    margin-left: 28px;
    margin-top: 2px;
    padding-left: 10px;
    border-left: 1px solid rgba(255,255,255,0.15);
}
.parts-nav .dropdown-children a {
    font-size: 12px;
    padding: 6px 10px;
    color: rgba(255,255,255,0.6);
}
.parts-nav .dropdown-children a:hover,
.parts-nav .dropdown-children a.active {
    color: #fff;
    background: rgba(255,255,255,0.08);
}
.parts-nav .chevron {
    margin-left: auto;
    font-size: 10px;
    opacity: 0.6;
    transition: transform 0.2s;
}
.parts-nav button[aria-expanded="true"] .chevron {
    transform: rotate(180deg);
}

/* Fade + arrow at bottom */
.parts-nav-fade {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 56px;
    background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.35) 100%);
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 8px;
    pointer-events: none;
    opacity: 0;
    transition: opacity 0.2s ease;
}
.parts-nav-fade.show {
    opacity: 1;
    pointer-events: auto;
}
.parts-scroll-btn {
    width: 26px;
    height: 26px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    border: 1px solid rgba(255,255,255,0.3);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.15s;
    animation: bounce-down 1.2s infinite;
}
.parts-scroll-btn:hover {
    background: rgba(255,255,255,0.35);
}
.parts-scroll-btn i {
    font-size: 11px;
    color: #fff;
}
@keyframes bounce-down {
    0%, 100% { transform: translateY(0); }
    50%       { transform: translateY(4px); }
}
</style>

<div class="parts-nav-wrap">

    <nav class="parts-nav flex flex-col gap-0.5" id="partsNav">

        <a href="{{ route('parts.index') }}" class="{{ request()->routeIs('parts.index') ? 'active' : '' }}">
            <i class="fa fa-wrench nav-icon"></i> Workshop
        </a>

        <a href="{{ route('parts.workshop-return') }}" class="{{ request()->routeIs('parts.workshop-return') ? 'active' : '' }}">
            <i class="fa fa-repeat nav-icon"></i> Workshop Return
        </a>

        <a href="{{ route('parts.estimates') }}" class="{{ request()->routeIs('parts.estimates') ? 'active' : '' }}">
            <i class="fa fa-file-text-o nav-icon"></i> Estimates
        </a>

        <a href="{{ route('parts.unclosed-req') }}"
           class="{{ request()->routeIs('parts.unclosed-req') ? 'active' : '' }}"
           style="color: #fca5a5;">
            <i class="fa fa-refresh nav-icon"></i> UnClose Requisitions
        </a>

        <hr class="nav-divider">

        {{-- Transactions Group (replaces 5 individual items) --}}
        @php $transactionsOpen = request()->routeIs('parts.purchase*') ||
                                  request()->routeIs('parts.purchase-return') ||
                                  request()->routeIs('parts.sale') ||
                                  request()->routeIs('parts.sale-return') ||
                                  request()->routeIs('parts.jobber-payment*'); @endphp

        <div x-data="{ open: {{ $transactionsOpen ? 'true' : 'false' }} }">
            <button @click="open = !open; $nextTick(() => window.partsNavCheck())" :aria-expanded="open.toString()">
                <i class="fa fa-exchange nav-icon"></i>
                <span>Transactions</span>
                <i class="fa fa-chevron-down chevron"></i>
            </button>
            <div class="dropdown-children" x-show="open" x-cloak>
                <a href="{{ route('parts.purchase') }}" class="{{ request()->routeIs('parts.purchase*') ? 'active' : '' }}">
                    <i class="fa fa-cart-plus nav-icon"></i> Purchase
                </a>
                <a href="{{ route('parts.purchase-return') }}" class="{{ request()->routeIs('parts.purchase-return') ? 'active' : '' }}">
                    <i class="fa fa-cart-arrow-down nav-icon"></i> Purchase Return
                </a>
                <a href="{{ route('parts.sale') }}"
                   class="{{ request()->routeIs('parts.sale') || request()->routeIs('parts.sale.invoice') ? 'active' : '' }}">
                    <i class="fa fa-usd nav-icon"></i> Sale
                </a>
                <a href="{{ route('parts.sale-return') }}" class="{{ request()->routeIs('parts.sale-return') ? 'active' : '' }}">
                    <i class="fa fa-undo nav-icon"></i> Sale Return
                </a>
                <a href="{{ route('parts.jobber-payment') }}" class="{{ request()->routeIs('parts.jobber-payment*') ? 'active' : '' }}">
                    <i class="fa fa-money nav-icon"></i> Vendors Payments
                </a>
            </div>
        </div>

        <hr class="nav-divider">

        {{-- Others Group (your original) --}}
        @php $othersOpen = request()->routeIs('parts.new-*') || request()->routeIs('parts.location-change'); @endphp
        <div x-data="{ open: {{ $othersOpen ? 'true' : 'false' }} }">
            <button @click="open = !open; $nextTick(() => window.partsNavCheck())" :aria-expanded="open.toString()">
                <i class="fa fa-th-list nav-icon"></i>
                <span>Others</span>
                <i class="fa fa-chevron-down chevron"></i>
            </button>
            <div class="dropdown-children" x-show="open" x-cloak>
                <a href="{{ route('parts.new-part') }}" class="{{ request()->routeIs('parts.new-part') ? 'active' : '' }}">
                    <i class="fa fa-plus-circle nav-icon"></i> Part Number
                </a>
                <a href="{{ route('parts.new-jobber') }}" class="{{ request()->routeIs('parts.new-jobber') ? 'active' : '' }}">
                    <i class="fa fa-user-plus nav-icon"></i> Jobber
                </a>
                {{-- <a href="{{ route('parts.new-cate-part') }}" class="{{ request()->routeIs('parts.new-cate-part') ? 'active' : '' }}">
                    <i class="fa fa-tag nav-icon"></i> IMC Category Part
                </a> --}}
                <a href="{{ route('parts.location-change') }}" class="{{ request()->routeIs('parts.location-change') ? 'active' : '' }}">
                    <i class="fa fa-map-marker nav-icon"></i> Edit Location
                </a>
            </div>
        </div>

        <hr class="nav-divider">

        {{-- Reports & Print Group (replaces 5 individual items) --}}
        @php $reportsOpen = request()->routeIs('parts.search') ||
                             request()->routeIs('parts.print-requisition*') ||
                             request()->routeIs('parts.reports') ||
                             request()->routeIs('parts.kpi-report') ||
                             request()->routeIs('parts.dpok-report'); @endphp

        <div x-data="{ open: {{ $reportsOpen ? 'true' : 'false' }} }">
            <button @click="open = !open; $nextTick(() => window.partsNavCheck())" :aria-expanded="open.toString()">
                <i class="fa fa-print nav-icon"></i>
                <span>Reports & Print</span>
                <i class="fa fa-chevron-down chevron"></i>
            </button>
            <div class="dropdown-children" x-show="open" x-cloak>
                <a href="{{ route('parts.search') }}" class="{{ request()->routeIs('parts.search') ? 'active' : '' }}">
                    <i class="fa fa-search nav-icon"></i> Search
                </a>
                <a href="{{ route('parts.print-requisition') }}" class="{{ request()->routeIs('parts.print-requisition*') ? 'active' : '' }}">
                    <i class="fa fa-print nav-icon"></i> Workshop Requisition
                </a>
                <a href="{{ route('parts.reports') }}" class="{{ request()->routeIs('parts.reports') ? 'active' : '' }}">
                    <i class="fa fa-bar-chart nav-icon"></i> Reports
                </a>
                <a href="{{ route('parts.kpi-report') }}" class="{{ request()->routeIs('parts.kpi-report') ? 'active' : '' }}">
                    <i class="fa fa-line-chart nav-icon"></i> KPI Reports
                </a>
                <a href="{{ route('parts.dpok-report') }}" class="{{ request()->routeIs('parts.dpok-report') ? 'active' : '' }}">
                    <i class="fa fa-pie-chart nav-icon"></i> DPOK Reports
                </a>
            </div>
        </div>

    </nav>

    {{-- Fade overlay + bouncing arrow --}}
    <div class="parts-nav-fade" id="partsNavFade">
        <div class="parts-scroll-btn" id="partsScrollBtn">
            <i class="fa fa-chevron-down"></i>
        </div>
    </div>

</div>

<script>
(function () {
    var nav  = document.getElementById('partsNav');
    var fade = document.getElementById('partsNavFade');
    var btn  = document.getElementById('partsScrollBtn');

    function check() {
        var notAtBottom = nav.scrollTop + nav.clientHeight < nav.scrollHeight - 6;
        var canScroll   = nav.scrollHeight > nav.clientHeight + 6;
        if (canScroll && notAtBottom) {
            fade.classList.add('show');
        } else {
            fade.classList.remove('show');
        }
    }

    window.partsNavCheck = check;

    btn.addEventListener('click', function () {
        nav.scrollBy({ top: 100, behavior: 'smooth' });
    });

    nav.addEventListener('scroll', check, { passive: true });
    window.addEventListener('resize', check);
    setTimeout(check, 400);
})();
</script>
