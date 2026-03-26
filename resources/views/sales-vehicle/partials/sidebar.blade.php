@section('sidebar-menu')

<a href="{{ route('sv.index') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sv.index') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-tachometer-alt w-6"></i><span>Dashboard</span>
</a>

<a href="{{ route('sv.inventory') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sv.inventory') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-car w-6"></i><span>Car Inventory</span>
</a>

<a href="{{ route('sv.add-vehicle') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sv.add-vehicle') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-plus-circle w-6"></i><span>Add Vehicle</span>
</a>

<div class="border-t border-white/20 my-2"></div>

<a href="{{ route('sv.do-form') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sv.do-form') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-file-alt w-6"></i><span>New Delivery Order</span>
</a>

<a href="{{ route('sv.do-list') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sv.do-list') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-list-alt w-6"></i><span>DO List</span>
</a>

<div class="border-t border-white/20 my-2"></div>

<a href="{{ route('sv.search-sold') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sv.search-sold') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-search w-6"></i><span>Search Sold Cars</span>
</a>

@endsection
