@section('sidebar-menu')
<!-- Dashboard -->
<a href="{{ route('accountant.index') }}"
   class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
          {{ request()->routeIs('accountant.index') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
    <i class="fas fa-tachometer-alt w-6 text-lg"></i>
    <span>Dashboard</span>
</a>

<!-- Jobcard Management with Submenu -->
<div x-data="{ open: {{ request()->routeIs(['accountant.jobcard-status', 'accountant.reopen-jc']) ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl transition-all duration-200 text-white/80 hover:bg-white/10 hover:text-white">
        <span class="flex items-center gap-2"><i class="fas fa-file-alt w-6 text-lg"></i>Jobcard Management</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="ml-4 space-y-1 mt-1">
        <a href="{{ route('accountant.jobcard-status') }}"
           class="flex items-center px-4 py-2 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.jobcard-status') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-sign-out-alt w-5 mr-2"></i>Jobcard Status
        </a>
        <a href="{{ route('accountant.reopen-jc') }}"
           class="flex items-center px-4 py-2 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.reopen-jc') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-undo w-5 mr-2"></i>Reopen JC
        </a>
    </div>
</div>

<!-- Labor Update with Submenu -->
<div x-data="{ open: {{ request()->routeIs(['accountant.labor-request', 'accountant.labor-manual', 'accountant.labor-auto']) ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 text-sm rounded-xl transition-all duration-200 text-white/80 hover:bg-white/10 hover:text-white">
        <span class="flex items-center gap-2"><i class="fas fa-tools w-6 text-lg"></i>Labor Update</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="ml-4 space-y-1 mt-1">
        <a href="{{ route('accountant.labor-request') }}"
           class="flex items-center px-4 py-2 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.labor-request') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-clipboard-list w-5 mr-2"></i>Request
        </a>
        <a href="{{ route('accountant.labor-manual') }}"
           class="flex items-center px-4 py-2 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.labor-manual') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-edit w-5 mr-2"></i>Manual
        </a>
        <a href="{{ route('accountant.labor-auto') }}"
           class="flex items-center px-4 py-2 text-sm rounded-xl transition-all duration-200
                  {{ request()->routeIs('accountant.labor-auto') ? 'bg-white/20 text-white' : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
            <i class="fas fa-percentage w-5 mr-2"></i>Auto %
        </a>
    </div>
</div>

<!-- Management Section -->
<div x-data="{ open: {{ request()->routeIs(['accountant.new-user', 'accountant.new-part']) ? 'true' : 'false' }} }">
    <div class="pt-4 mt-4 border-t border-white/20">
        <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-white/50 uppercase tracking-wider hover:text-white/70">
            <span>Management</span>
            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
        </button>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="mt-1 space-y-1">
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
    </div>
</div>

<!-- Search Section -->
<div x-data="{ open: {{ request()->routeIs(['accountant.service-search', 'accountant.parts-search']) ? 'true' : 'false' }} }">
    <div class="pt-4 mt-4 border-t border-white/20">
        <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-white/50 uppercase tracking-wider hover:text-white/70">
            <span>Search</span>
            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
        </button>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="mt-1 space-y-1">
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
    </div>
</div>

<!-- Reports Section (without Recovery) -->
<div x-data="{ open: {{ request()->routeIs(['accountant.index', 'accountant.parts-reports', 'accountant.finance-reports']) ? 'true' : 'false' }} }">
    <div class="pt-4 mt-4 border-t border-white/20">
        <button @click="open = !open"
                class="w-full flex items-center justify-between px-4 py-2 text-xs font-semibold text-white/50 uppercase tracking-wider hover:text-white/70">
            <span>Reports</span>
            <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
        </button>
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 transform -translate-y-2"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="mt-1 space-y-1">
            <a href="{{ route('accountant.index') }}"
               class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
                      {{ request()->routeIs('accountant.index') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
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
        </div>
    </div>
</div>

<!-- Recovery - SEPARATE TOP LEVEL ITEM (always visible) -->
<a href="{{ route('recovery.index') }}"
   class="flex items-center px-4 py-3 text-sm rounded-xl transition-all duration-200
          {{ request()->routeIs('recovery.index') ? 'bg-white/20 text-white shadow-lg' : 'text-white/80 hover:bg-white/10 hover:text-white' }}">
    <i class="fas fa-dollar-sign w-6 text-lg"></i>
    <span>Recovery</span>
</a>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
@endsection
