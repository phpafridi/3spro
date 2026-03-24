<div class="bg-white rounded shadow-sm border border-gray-200 p-4 mb-5">
    <form method="GET" class="flex flex-wrap gap-3 items-end">
        @if(isset($showVendor) && $showVendor)
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Vendor</label>
            <select name="vendor" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">All Vendors</option>
                @foreach($jobbers ?? [] as $j)
                <option value="{{ $j }}" {{ ($vendor??'')===$j ? 'selected':'' }}>{{ $j }}</option>
                @endforeach
            </select>
        </div>
        @endif
        @if(isset($showDates) && $showDates !== false)
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">From</label>
            <input type="date" name="from" value="{{ $from ?? today()->subMonth()->toDateString() }}"
                   class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">To</label>
            <input type="date" name="to" value="{{ $to ?? today()->toDateString() }}"
                   class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        </div>
        @endif
        @if(isset($showMonths) && $showMonths)
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Months</label>
            <select name="months" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                @foreach([1,2,3,6,12] as $m)
                <option value="{{ $m }}" {{ ($months??3)==$m ? 'selected':'' }}>{{ $m }} Month(s)</option>
                @endforeach
            </select>
        </div>
        @endif
        @if(isset($showType) && $showType)
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1">Type</label>
            <select name="type" class="border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="all"   {{ ($type??'all')==='all'   ? 'selected':'' }}>All</option>
                <option value="imc"   {{ ($type??'')==='imc'      ? 'selected':'' }}>IMC</option>
                <option value="local" {{ ($type??'')==='local'     ? 'selected':'' }}>Local</option>
            </select>
        </div>
        @endif
        <button type="submit"
                style="background:#dc2626;color:#fff;padding:7px 20px;font-size:13px;font-weight:600;border-radius:4px;border:none;cursor:pointer;">
            Generate
        </button>
        <a href="{{ route('parts.reports') }}"
           class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded text-sm transition-colors">
            ← Reports
        </a>
        <button type="button" onclick="window.print()"
                style="background:#374151;color:#fff;padding:6px 14px;font-size:13px;font-weight:600;border-radius:4px;text-decoration:none;">
            Print
        </button>
    </form>
</div>
