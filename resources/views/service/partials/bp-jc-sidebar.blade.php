{{-- BP-JC Sidebar - exact match to original BP-JC/menu.php --}}

<a href="{{ route('bp-jc.index') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('bp-jc.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
    <i class="fa fa-wrench w-6"></i>
    <span>Pending Jobs</span>
</a>

<a href="{{ route('bp-jc.sublet') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('bp-jc.sublet') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
    <i class="fa fa-sign-out w-6"></i>
    <span>Sublet</span>
</a>

<a href="{{ route('bp-jc.inprogress') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('bp-jc.inprogress') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
    <i class="fa fa-edit w-6"></i>
    <span>Inprogress Jobs</span>
</a>

{{-- Reports accordion --}}
<div x-data="{ open: {{ request()->routeIs('bp-jc.report*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center px-4 py-3 text-sm hover:bg-blue-700 rounded-lg transition-colors">
        <i class="fa fa-bar-chart-o w-6"></i>
        <span class="flex-1 text-left">Reports</span>
        <i class="fa fa-chevron-down text-xs" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="ml-6 space-y-1">
        <a href="{{ route('bp-jc.report.labor') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('bp-jc.report.labor') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">Labor Type</a>
        <a href="{{ route('bp-jc.report.labor-detail') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('bp-jc.report.labor-detail') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">Labor (Detail)</a>
    </div>
</div>
