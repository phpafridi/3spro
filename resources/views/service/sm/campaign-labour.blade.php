@extends('layouts.master')
@section('title', 'SM - Campaign Labour')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection
@section('content')
@if(session('success'))<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>@endif
<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    <div class="md:col-span-2 bg-white rounded shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Add Labour<br><span class="text-sm font-normal text-gray-500">{{ $campaign->campaign_name }}</span></h2>
            <a href="{{ route('sm.campaigns') }}" class="text-sm text-gray-500 hover:text-gray-700"><i class="fa fa-arrow-left mr-1"></i>Back</a>
        </div>
        <form method="POST" action="{{ route('sm.campaign-labour.store', $campId) }}" class="space-y-3">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Labour</label>
                <select name="labours" required class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">-- Select --</option>
                    @foreach($laborList as $l)
                    <option value="{{ $l->Labor }}">{{ $l->Labor }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cost</label>
                <input type="number" name="labourcost" step="0.01" min="0" value="0"
                       class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                <i class="fa fa-plus mr-2"></i> Add
            </button>
        </form>
    </div>
    <div class="md:col-span-3 bg-white rounded shadow-sm p-6">
        <h3 class="font-semibold text-gray-700 mb-3">Assigned Labour
            <span class="ml-2 px-2 py-0.5 bg-gray-100 text-gray-600 text-sm rounded-full">{{ $labours->count() }}</span>
        </h3>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Labour</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($labours as $l)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-500">{{ $l->compaingh_id }}</td>
                    <td class="px-4 py-2 text-sm font-medium text-gray-800">{{ $l->labour_des }}</td>
                    <td class="px-4 py-2 text-sm text-gray-700">{{ number_format($l->labour_cost,2) }}</td>
                    <td class="px-4 py-2">
                        <button class="del-labour px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors" data-id="{{ $l->compaingh_id }}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="px-4 py-4 text-center text-gray-400 text-sm italic">No labour assigned.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@push('scripts')
<script>
document.querySelectorAll('.del-labour').forEach(btn => {
    btn.addEventListener('click', function() {
        if (!confirm('Delete this labour?')) return;
        fetch('{{ route("sm.campaign-labour.delete") }}', {
            method: 'POST',
            headers: {'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({id: this.dataset.id})
        }).then(r=>r.json()).then(res=>{ if(res.status==='ok') location.reload(); });
    });
});
</script>
@endpush
@endsection
