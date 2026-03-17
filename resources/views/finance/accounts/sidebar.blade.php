@section('sidebar-menu')
    @foreach([
        ['accounts.index',      'fas fa-chart-line',  'Finance Reports'],
        ['accounts.cpv',        'fas fa-money-bill',  'CPV'],
        ['accounts.crv',        'fas fa-receipt',     'CRV'],
        ['accounts.bpv',        'fas fa-university',  'BPV'],
        ['accounts.brv',        'fas fa-piggy-bank',  'BRV'],
        ['accounts.jv',         'fas fa-book',        'JV'],
        ['accounts.search',     'fas fa-search',      'Search Voucher'],
    ] as [$route, $icon, $label])
    <a href="{{ route($route) }}"
       class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
              {{ request()->routeIs($route) ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="{{ $icon }} w-6 text-lg"></i>
        <span>{{ $label }}</span>
    </a>
    @endforeach

    <div class="pt-4 mt-4 border-t border-white/20">
        <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-1">Voucher Queue</p>
        <a href="{{ route('accounts.pending-vouchers') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accounts.pending-vouchers') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-clock w-6 text-lg"></i>
            <span>Pending</span>
            @if(($counts['foredit'] ?? 0) > 0)
            <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $counts['foredit'] }}</span>
            @endif
        </a>
        <a href="{{ route('accounts.authenticate') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accounts.authenticate') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-stamp w-6 text-lg"></i>
            <span>Authenticate</span>
            @if(($counts['forwardCount'] ?? 0) > 0)
            <span class="ml-auto bg-orange-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $counts['forwardCount'] }}</span>
            @endif
        </a>
        <a href="{{ route('accounts.reopened-vouchers') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accounts.reopened-vouchers') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-redo w-6 text-lg"></i>
            <span>Reopened</span>
            @if(($counts['Reopened'] ?? 0) > 0)
            <span class="ml-auto bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $counts['Reopened'] }}</span>
            @endif
        </a>
    </div>

    <div class="pt-4 mt-4 border-t border-white/20">
        <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-1">Master Data</p>
        @foreach([
            ['accounts.coa',    'fas fa-sitemap','COA'],
            ['accounts.add-gl', 'fas fa-layer-group','Add GL'],
            ['accounts.add-gsl','fas fa-list','Add GSL'],
            ['accounts.add-sh', 'fas fa-indent','Add Sub Head'],
        ] as [$route, $icon, $label])
        <a href="{{ route($route) }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs($route) ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="{{ $icon }} w-6 text-lg"></i>
            <span>{{ $label }}</span>
        </a>
        @endforeach
    </div>
@endsection
