@section('sidebar-menu')
    <!-- Finance Reports (Main Menu) -->
    <a href="{{ route('accounts.index') }}"
       class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
              {{ request()->routeIs('accounts.index') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fas fa-chart-line w-6 text-lg"></i>
        <span>Finance Reports</span>
    </a>

    <!-- Voucher Entries with Submenu -->
    <div x-data="{ open: {{ request()->routeIs(['accounts.cpv', 'accounts.crv', 'accounts.bpv', 'accounts.brv', 'accounts.jv']) ? 'true' : 'false' }} }" class="relative">
        <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-3 text-sm rounded transition-all duration-200 text-white/80 hover:bg-white/10 hover:text-white">
            <div class="flex items-center">
                <i class="fas fa-file-invoice w-6 text-lg"></i>
                <span>Voucher Entries</span>
            </div>
            <i class="fas fa-chevron-down text-xs transition-transform duration-200"
               :class="{ 'rotate-180': open }"></i>
        </button>

        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform -translate-y-2"
             class="mt-1 ml-4 space-y-1">

            @foreach([
                ['accounts.cpv', 'fas fa-money-bill', 'CPV (Cash Payment)'],
                ['accounts.crv', 'fas fa-receipt', 'CRV (Cash Receipt)'],
                ['accounts.bpv', 'fas fa-university', 'BPV (Bank Payment)'],
                ['accounts.brv', 'fas fa-landmark-flag', 'BRV (Bank Receipt)'],
                ['accounts.jv', 'fas fa-book', 'JV (Journal Voucher)'],
            ] as [$route, $icon, $label])
            <a href="{{ route($route) }}"
               class="flex items-center px-4 py-2 text-sm rounded transition-all duration-200
                      {{ request()->routeIs($route) ? 'bg-white/20 text-white shadow-lg' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                <i class="{{ $icon }} w-6 text-sm"></i>
                <span>{{ $label }}</span>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Search Voucher -->
    <a href="{{ route('accounts.search') }}"
       class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
              {{ request()->routeIs('accounts.search') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fas fa-search w-6 text-lg"></i>
        <span>Search Voucher</span>
    </a>

    <!-- Voucher Queue Section with Submenu -->
    <div x-data="{ open: {{ request()->routeIs(['accounts.pending-vouchers', 'accounts.authenticate', 'accounts.reopened-vouchers']) ? 'true' : 'false' }} }" class="relative">
        <div class="pt-4 mt-4 border-t border-white/20">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-white/50 uppercase tracking-wider hover:text-white/70">
                <span>Voucher Queue</span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                   :class="{ 'rotate-180': open }"></i>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="mt-1 space-y-1">

                <a href="{{ route('accounts.pending-vouchers') }}"
                   class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
                          {{ request()->routeIs('accounts.pending-vouchers') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-clock w-6 text-lg"></i>
                    <span>Pending</span>
                    @if(($counts['foredit'] ?? 0) > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $counts['foredit'] }}</span>
                    @endif
                </a>

                <a href="{{ route('accounts.authenticate') }}"
                   class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
                          {{ request()->routeIs('accounts.authenticate') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-stamp w-6 text-lg"></i>
                    <span>Authenticate</span>
                    @if(($counts['forwardCount'] ?? 0) > 0)
                    <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $counts['forwardCount'] }}</span>
                    @endif
                </a>

                <a href="{{ route('accounts.reopened-vouchers') }}"
                   class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
                          {{ request()->routeIs('accounts.reopened-vouchers') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="fas fa-redo w-6 text-lg"></i>
                    <span>Reopened</span>
                    @if(($counts['Reopened'] ?? 0) > 0)
                    <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $counts['Reopened'] }}</span>
                    @endif
                </a>
            </div>
        </div>
    </div>

    <!-- Master Data Section with Submenu -->
    <div x-data="{ open: {{ request()->routeIs(['accounts.coa', 'accounts.add-gl', 'accounts.add-gsl', 'accounts.add-sh']) ? 'true' : 'false' }} }" class="relative">
        <div class="pt-4 mt-4 border-t border-white/20">
            <button @click="open = !open"
                    class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-white/50 uppercase tracking-wider hover:text-white/70">
                <span>Master Data</span>
                <i class="fas fa-chevron-down text-xs transition-transform duration-200"
                   :class="{ 'rotate-180': open }"></i>
            </button>

            <div x-show="open"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 class="mt-1 space-y-1">

                @foreach([
                    ['accounts.coa', 'fas fa-sitemap', 'Chart of Accounts'],
                    ['accounts.add-gl', 'fas fa-layer-group', 'Add GL'],
                    ['accounts.add-gsl', 'fas fa-list', 'Add GSL'],

                ] as [$route, $icon, $label])
                <a href="{{ route($route) }}"
                   class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
                          {{ request()->routeIs($route) ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
                    <i class="{{ $icon }} w-6 text-lg"></i>
                    <span>{{ $label }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
