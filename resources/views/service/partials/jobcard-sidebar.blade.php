{{-- Jobcard / Service Advisor Sidebar - matches original menu.php exactly --}}

<a href="{{ route('jobcard.add-vehicle') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jobcard.add-vehicle') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
    <i class="fa fa-cog w-6"></i>
    <span>Open New RO</span>
</a>

<a href="{{ route('jobcard.index') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jobcard.index') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
    <i class="fa fa-asterisk w-6"></i>
    <span>JobCards</span>
</a>

<a href="{{ route('jobcard.additional-list') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jobcard.additional-list') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
    <i class="fa fa-plus w-6"></i>
    <span>Additional</span>
</a>

<a href="{{ route('jobcard.complete') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jobcard.complete') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
    <i class="fa fa-thumbs-o-up w-6"></i>
    <span>JobCompleted</span>
</a>

{{-- Status accordion --}}
<div x-data="{ open: {{ request()->routeIs('jobcard.status*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center px-4 py-3 text-sm hover:bg-blue-700 rounded-lg transition-colors">
        <i class="fa fa-eye w-6"></i>
        <span class="flex-1 text-left">Status</span>
        <i class="fa fa-chevron-down text-xs" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="ml-6 space-y-1">
        <a href="{{ route('jobcard.status.labor') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('jobcard.status.labor') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">Labor</a>
        <a href="{{ route('jobcard.status.parts') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('jobcard.status.parts') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">Parts</a>
        <a href="{{ route('jobcard.status.sublet') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('jobcard.status.sublet') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">Sublet</a>
        <a href="{{ route('jobcard.status.consumable') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('jobcard.status.consumable') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">Consumble</a>
    </div>
</div>

{{-- Add New accordion --}}
<div x-data="{ open: {{ request()->routeIs('jobcard.new*') ? 'true' : 'false' }} }">
    <button @click="open = !open"
            class="w-full flex items-center px-4 py-3 text-sm hover:bg-blue-700 rounded-lg transition-colors">
        <i class="fa fa-plus-square w-6"></i>
        <span class="flex-1 text-left">Add New</span>
        <i class="fa fa-chevron-down text-xs" :class="open ? 'rotate-180' : ''"></i>
    </button>
    <div x-show="open" x-cloak class="ml-6 space-y-1">
        <a href="{{ route('jobcard.new.labor') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('jobcard.new.labor') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">New Labor</a>
        <a href="{{ route('jobcard.new.part') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('jobcard.new.part') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">Spare Part</a>
        <a href="{{ route('jobcard.new.consumable') }}"
           class="flex items-center px-4 py-2 text-sm {{ request()->routeIs('jobcard.new.consumable') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">Consumble</a>
    </div>
</div>

<a href="{{ route('jobcard.search') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jobcard.search') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded-lg transition-colors">
    <i class="fa fa-search-plus w-6"></i>
    <span>Search</span>
</a>
