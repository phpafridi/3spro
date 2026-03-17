<a href="{{ route('sales.index') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.index') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-tachometer-alt w-6"></i><span>Dashboard</span>
</a>

<div x-data="{ open: {{ request()->routeIs('sales.jobcards','sales.jc-changes') ? 'true' : 'false' }} }">
    <button @click="open=!open" class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-white/10 rounded-lg transition-colors">
        <span class="flex items-center gap-2"><i class="fas fa-file-alt w-6"></i>Jobcards</span>
        <i class="fas fa-chevron-down text-xs" :class="open?'rotate-180':''"></i>
    </button>
    <div x-show="open" class="ml-4 space-y-1">
        <a href="{{ route('sales.jobcards') }}" class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.jobcards') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">Unclose JCs</a>
        <a href="{{ route('sales.jc-changes') }}" class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.jc-changes') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">JC Changes</a>
    </div>
</div>

<div x-data="{ open: {{ request()->routeIs('sales.status.*') ? 'true' : 'false' }} }">
    <button @click="open=!open" class="w-full flex items-center justify-between px-4 py-3 text-sm hover:bg-white/10 rounded-lg transition-colors">
        <span class="flex items-center gap-2"><i class="fas fa-hourglass-half w-6"></i>Status</span>
        <i class="fas fa-chevron-down text-xs" :class="open?'rotate-180':''"></i>
    </button>
    <div x-show="open" class="ml-4 space-y-1">
        <a href="{{ route('sales.status.labor') }}" class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.status.labor') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">Labor</a>
        <a href="{{ route('sales.status.parts') }}" class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.status.parts') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">Parts</a>
        <a href="{{ route('sales.status.sublet') }}" class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.status.sublet') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">Sublet</a>
        <a href="{{ route('sales.status.consumable') }}" class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sales.status.consumable') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">Consumable</a>
    </div>
</div>

<a href="{{ route('sales.search') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.search') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-search w-6"></i><span>Search</span>
</a>
<a href="{{ route('sales.ac') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.ac') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-users w-6"></i><span>Active Customers</span>
</a>
<a href="{{ route('sales.problem-tray') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.problem-tray') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-inbox w-6"></i><span>Problem Tray</span>
</a>
<a href="{{ route('sales.vin-check') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.vin-check') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-car w-6"></i><span>VIN Check</span>
</a>
<a href="{{ route('sales.vin') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.vin') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-id-card w-6"></i><span>Unique VINs</span>
</a>
<a href="{{ route('sales.campaigns') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.campaigns') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-calendar-check w-6"></i><span>Campaigns</span>
</a>
<a href="{{ route('sales.uio') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.uio') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-chart-bar w-6"></i><span>UIO</span>
</a>
<a href="{{ route('sales.upload-vin') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.upload-vin') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-upload w-6"></i><span>Upload VINs</span>
</a>
<a href="{{ route('sales.reports') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.reports') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-chart-line w-6"></i><span>Reports</span>
</a>
<a href="{{ route('sales.reports-new') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.reports-new') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded-lg transition-colors">
    <i class="fas fa-chart-area w-6"></i><span>New Reports</span>
</a>
