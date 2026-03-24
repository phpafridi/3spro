@section('sidebar-menu')
    @foreach([
        ['recovery.index',       'fas fa-home',        'Dashboard'],
        ['recovery.add-debt',    'fas fa-minus-circle','Add Debit'],
        ['recovery.add-credit',    'fas fa-plus-circle', 'Add Credit'],
        ['recovery.search',      'fas fa-search',      'Search'],
        ['recovery.dm-bills',    'fas fa-file-invoice','DM Bills'],
        ['recovery.recovered',   'fas fa-check-circle','Recovered'],
        ['recovery.not-contacted','fas fa-bell-slash', 'Non-Active'],
        ['recovery.stats',       'fas fa-chart-bar',   'Statistics'],
        ['recovery.add-account', 'fas fa-university',  'Add Account'],
    ] as [$route, $icon, $label])
    <a href="{{ route($route) }}"
       class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
              {{ request()->routeIs($route) ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="{{ $icon }} w-6 text-lg"></i>
        <span>{{ $label }}</span>
    </a>
    @endforeach
    <div class="pt-4 mt-4 border-t border-white/20">
        <a href="{{ route('accountant.index') }}"
           class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200 text-white/80 hover:bg-white/10 hover:text-white">
            <i class="fas fa-arrow-left w-6 text-lg"></i>
            <span>Back to Accountant</span>
        </a>
    </div>
@endsection
