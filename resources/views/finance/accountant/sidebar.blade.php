@section('sidebar-menu')
    <a href="{{ route('accountant.index') }}"
       class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
              {{ request()->routeIs('accountant.index') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fas fa-tachometer-alt w-6 text-lg"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('accountant.jobcard-status') }}"
       class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
              {{ request()->routeIs('accountant.jobcard-status') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fas fa-sign-out-alt w-6 text-lg"></i>
        <span>Jobcard Status</span>
    </a>

    <a href="{{ route('accountant.reopen-jc') }}"
       class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
              {{ request()->routeIs('accountant.reopen-jc') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
        <i class="fas fa-undo w-6 text-lg"></i>
        <span>Reopen JC</span>
    </a>

    {{-- Labor Update sub-menu --}}
    <div class="pt-2">
        <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-1">Labor Update</p>
        <a href="{{ route('accountant.labor-request') }}"
           class="flex items-center px-6 py-2 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.labor-request') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-clipboard-list w-6"></i><span>Request</span>
        </a>
        <a href="{{ route('accountant.labor-manual') }}"
           class="flex items-center px-6 py-2 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.labor-manual') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-edit w-6"></i><span>Manual</span>
        </a>
        <a href="{{ route('accountant.labor-auto') }}"
           class="flex items-center px-6 py-2 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.labor-auto') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-percentage w-6"></i><span>Auto %</span>
        </a>
    </div>

    <div class="pt-4 mt-4 border-t border-white/20">
        <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-1">Management</p>
        <a href="{{ route('accountant.new-user') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.new-user') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-user-plus w-6 text-lg"></i>
            <span>New User</span>
        </a>
        <a href="{{ route('accountant.new-part') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.new-part') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-cloud-upload-alt w-6 text-lg"></i>
            <span>New Part</span>
        </a>
    </div>

    <div class="pt-4 mt-4 border-t border-white/20">
        <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-1">Search</p>
        <a href="{{ route('accountant.service-search') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.service-search') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-search-plus w-6 text-lg"></i>
            <span>Service</span>
        </a>
        <a href="{{ route('accountant.parts-search') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.parts-search') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-search w-6 text-lg"></i>
            <span>Parts</span>
        </a>
    </div>

    <div class="pt-4 mt-4 border-t border-white/20">
        <p class="px-4 text-xs font-semibold text-white/50 uppercase tracking-wider mb-1">Reports</p>
        <a href="{{ route('accountant.index') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 text-white/80 hover:bg-white/10 hover:text-white">
            <i class="fas fa-wrench w-6 text-lg"></i>
            <span>Service Dept</span>
        </a>
        <a href="{{ route('accountant.parts-reports') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.parts-reports') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-wpforms w-6 text-lg"></i>
            <span>Parts Dept</span>
        </a>
        <a href="{{ route('accountant.finance-reports') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.finance-reports') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-key w-6 text-lg"></i>
            <span>Finance</span>
        </a>
        <a href="{{ route('recovery.index') }}"
           class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200 text-white/80 hover:bg-white/10 hover:text-white">
            <i class="fas fa-dollar-sign w-6 text-lg"></i>
            <span>Recovery</span>
        </a>
    </div>
@endsection
