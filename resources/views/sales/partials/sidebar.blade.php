@section('sidebar-menu')
<!-- Dashboard -->
<a href="{{ route('sales.index') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.index') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-tachometer-alt w-6"></i><span>Dashboard</span>
</a>

<!-- Jobcards Section -->
<div x-data="{ open: {{ request()->routeIs('sales.jobcards*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-white/10 rounded transition-colors">
        <span class="flex items-center gap-2"><i class="fas fa-file-alt w-6"></i>Jobcards</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="ml-4 space-y-1 mt-1">
        <a href="{{ route('sales.jobcards') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.jobcards') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-folder-open w-5 mr-1"></i>Unclose JCs
        </a>
        <a href="{{ route('sales.jc-changes') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.jc-changes') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-edit w-5 mr-1"></i>JC Changes
        </a>
    </div>
</div>

<!-- Status Section -->
<div x-data="{ open: {{ request()->routeIs('sales.status*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-white/10 rounded transition-colors">
        <span class="flex items-center gap-2"><i class="fas fa-hourglass-half w-6"></i>Status</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="ml-4 space-y-1 mt-1">
        @foreach([
            ['sales.status.labor', 'fas fa-wrench', 'Labor'],
            ['sales.status.parts', 'fas fa-cogs', 'Parts'],
            ['sales.status.sublet', 'fas fa-truck', 'Sublet'],
            ['sales.status.consumable', 'fas fa-oil-can', 'Consumable']
        ] as [$route, $icon, $label])
        <a href="{{ route($route) }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs($route) ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="{{ $icon }} w-5 mr-1"></i>{{ $label }}
        </a>
        @endforeach
    </div>
</div>

<!-- Search & Customers Section -->
<div x-data="{ open: {{ request()->routeIs('sales.search','sales.ac') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-white/10 rounded transition-colors">
        <span class="flex items-center gap-2"><i class="fas fa-search w-6"></i>Search & Customers</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="ml-4 space-y-1 mt-1">
        <a href="{{ route('sales.search') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.search') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-search w-5 mr-1"></i>Search Vouchers
        </a>
        <a href="{{ route('sales.ac') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.ac') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-users w-5 mr-1"></i>Active Customers
        </a>
    </div>
</div>

<!-- Problem Tray -->
<a href="{{ route('sales.problem-tray') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.problem-tray') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-inbox w-6"></i><span>Problem Tray</span>
</a>

<!-- VIN Management Section -->
<div x-data="{ open: {{ request()->routeIs('sales.vin*','sales.upload-vin') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-white/10 rounded transition-colors">
        <span class="flex items-center gap-2"><i class="fas fa-car w-6"></i>VIN Management</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="ml-4 space-y-1 mt-1">
        <a href="{{ route('sales.vin-check') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.vin-check') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-check-circle w-5 mr-1"></i>VIN Check
        </a>
        <a href="{{ route('sales.vin') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.vin') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-id-card w-5 mr-1"></i>Unique VINs
        </a>
        <a href="{{ route('sales.upload-vin') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.upload-vin') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-upload w-5 mr-1"></i>Upload VINs
        </a>
    </div>
</div>

<!-- Campaigns -->
<a href="{{ route('sales.campaigns') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.campaigns') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-calendar-check w-6"></i><span>Campaigns</span>
</a>

<!-- UIO -->
<a href="{{ route('sales.uio') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.uio') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-chart-bar w-6"></i><span>UIO</span>
</a>

<!-- Reports Section -->
<div x-data="{ open: {{ request()->routeIs('sales.reports*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-white/10 rounded transition-colors">
        <span class="flex items-center gap-2"><i class="fas fa-chart-line w-6"></i>Reports</span>
        <i class="fas fa-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
    </button>
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         class="ml-4 space-y-1 mt-1">
        <a href="{{ route('sales.reports') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.reports') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-chart-line w-5 mr-1"></i>Classic Reports
        </a>
        <a href="{{ route('sales.reports-new') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.reports-new') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
            <i class="fas fa-chart-area w-5 mr-1"></i>New Reports
        </a>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
