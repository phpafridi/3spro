{{-- SM / Service Manager Sidebar - exact match to original SM/menu.php --}}

<a href="{{ route('sm.index') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sm.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
    <i class="fa fa-wrench w-6"></i>
    <span>Opened Repair Orders</span>
</a>

{{-- Add accordion --}}
<div x-data="{ open: {{ request()->routeIs('sm.new*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center px-4 py-3 text-sm hover:bg-blue-700 rounded transition-colors">
        <i class="fa fa-plus w-6"></i>
        <span class="flex-1 text-left">Add</span>
        <i class="fa fa-chevron-down text-xs" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="ml-6 space-y-1">
        <a href="{{ route('sm.new-user') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.new-user') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">User</a>
        <a href="{{ route('sm.vendors') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.vendors') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Sublet Vendor</a>
        <a href="{{ route('sm.insurance') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.insurance') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Insurance Company</a>
        <a href="{{ route('sm.new-labor') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.new-labor') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Labor Description</a>
    </div>
</div>

{{-- Manage accordion --}}
<div x-data="{ open: {{ request()->routeIs('sm.master*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center px-4 py-3 text-sm hover:bg-blue-700 rounded transition-colors">
        <i class="fa fa-user w-6"></i>
        <span class="flex-1 text-left">Manage</span>
        <i class="fa fa-chevron-down text-xs" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="ml-6 space-y-1">
        <a href="{{ route('sm.master.bays') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.master.bays') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Bays</a>
        <a href="{{ route('sm.master.teams') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.master.teams') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Tech Teams</a>
        <a href="{{ route('sm.master.variants') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.master.variants') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Variant Category</a>
        <a href="{{ route('sm.master.labor') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.master.labor') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Labor Descriptions</a>
    </div>
</div>

{{-- Repair Orders accordion --}}
<div x-data="{ open: {{ request()->routeIs('sm.unclose','sm.jc-changes','sm.labor-change') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center px-4 py-3 text-sm hover:bg-blue-700 rounded transition-colors">
        <i class="fa fa-cog w-6"></i>
        <span class="flex-1 text-left">RepairOrders</span>
        <i class="fa fa-chevron-down text-xs" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="ml-6 space-y-1">
        <a href="{{ route('sm.unclose') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.unclose') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">ReOpen JC</a>
        <a href="{{ route('sm.jc-changes') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.jc-changes') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">JC Changes</a>
        <a href="{{ route('sm.labor-change') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.labor-change') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Labor Change</a>
    </div>
</div>

<a href="{{ route('sm.campaigns') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sm.campaigns') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
    <i class="fa fa-calendar-check-o w-6"></i>
    <span>Campaigns</span>
</a>

<div class="border-t border-white/20 my-2"></div>

<a href="{{ route('sm.search') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sm.search') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
    <i class="fa fa-search w-6"></i>
    <span>Search</span>
</a>

{{-- Status accordion --}}
<div x-data="{ open: {{ request()->routeIs('sm.status*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center px-4 py-3 text-sm hover:bg-blue-700 rounded transition-colors">
        <i class="fa fa-hourglass-3 w-6"></i>
        <span class="flex-1 text-left">Status</span>
        <i class="fa fa-chevron-down text-xs" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="ml-6 space-y-1">
        <a href="{{ route('sm.status-labor') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.status-labor') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Labor</a>
        <a href="{{ route('sm.status-parts') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.status-parts') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Parts</a>
        <a href="{{ route('sm.status-sublet') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.status-sublet') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Sublet</a>
        <a href="{{ route('sm.status-consumable') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('sm.status-consumable') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">Consumble</a>
    </div>
</div>

<a href="{{ route('sm.reports') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sm.reports') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
    <i class="fa fa-bar-chart w-6"></i>
    <span>Reports</span>
</a>
