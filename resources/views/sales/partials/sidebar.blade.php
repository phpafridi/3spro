@section('sidebar-menu')
<!-- CRM Follow-Up Reminder — only item kept in Sales/CRM sidebar -->
<a href="{{ route('sales.crm-reminder') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.crm-reminder') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-phone-alt w-6"></i><span>CRM Reminder</span>
    <span class="ml-auto px-1.5 py-0.5 bg-orange-400 text-white text-xs rounded-full">Calls</span>
</a>

<!-- Parts Filter by Date -->
<a href="{{ route('sales.parts-filter') }}"
   class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('sales.parts-filter') ? 'bg-white/20' : 'hover:bg-white/10' }} rounded transition-colors">
    <i class="fas fa-filter w-6"></i><span>Parts Filter</span>
</a>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
@endpush
