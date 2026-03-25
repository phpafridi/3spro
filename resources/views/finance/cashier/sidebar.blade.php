@section('sidebar-menu')
    <a href="{{ route('cashier.index') }}"
       class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
              {{ request()->routeIs('cashier.index') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fas fa-home w-6 text-lg"></i>
        <span>Jobcard Invoice</span>
        <!-- <span class="ml-auto bg-white/20 px-2 py-0.5 rounded-full text-xs"></span> -->
    </a>

    <a href="{{ route('cashier.search') }}"
       class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
              {{ request()->routeIs('cashier.search') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fas fa-search-plus w-6 text-lg"></i>
        <span>Search</span>
    </a>

    <a href="{{ route('cashier.parts-return') }}"
       class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
              {{ request()->routeIs('cashier.parts-return') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fas fa-repeat w-6 text-lg"></i>
        <span>Parts Return</span>
    </a>

    <div class="pt-4 mt-4 border-t border-white/20">
        <h3 class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider">Prints</h3>

        <a href="{{ route('cashier.print-initial') }}"
           class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200 mt-2
                  {{ request()->routeIs('cashier.print-initial') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-print w-6 text-lg"></i>
            <span>Initial RO Print</span>
        </a>

        <a href="{{ route('cashier.print-close') }}"
           class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
                  {{ request()->routeIs('cashier.print-close') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-print w-6 text-lg"></i>
            <span>Close RO Print</span>
        </a>

        <a href="{{ route('cashier.reports') }}"
           class="flex items-center px-4 py-3 text-sm rounded transition-all duration-200
                  {{ request()->routeIs('cashier.reports') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-line-chart w-6 text-lg"></i>
            <span>Reports</span>
        </a>
    </div>
@endsection
