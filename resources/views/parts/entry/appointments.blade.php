@extends('parts.layout')
@section('title', 'Appointments Parts - Parts')
@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Appointment Parts Required</h2>
    <p class="text-sm text-gray-500 mt-1">Update availability status for appointment parts</p>
</div>
@if(session('success'))<div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">{{ session('success') }}</div>@endif
<div class="bg-white rounded shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gradient-to-r from-red-50 to-red-50">
            <tr>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">App ID</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Customer</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Reg No</th>
                <th class="px-4 py-3 text-left text-xs font-semibold text-gray-600 uppercase">Date</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Status</th>
                <th class="px-4 py-3 text-center text-xs font-semibold text-gray-600 uppercase">Action</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
        @forelse($appointments as $app)
        <tr class="hover:bg-red-50/30">
            <td class="px-4 py-3 font-medium text-gray-800">{{ $app->app_id }}</td>
            <td class="px-4 py-3 text-gray-700">{{ $app->customer_name ?? '-' }}</td>
            <td class="px-4 py-3 text-gray-500">{{ $app->reg_no ?? '-' }}</td>
            <td class="px-4 py-3 text-gray-500 text-xs">{{ $app->app_date ?? '-' }}</td>
            <td class="px-4 py-3 text-center">
                <span class="px-2 py-1 rounded-full text-xs {{ $app->parts_status == '0' ? 'bg-yellow-100 text-yellow-700' : 'bg-blue-100 text-blue-700' }}">
                    {{ $app->parts_status == '0' ? 'Pending' : 'In Progress' }}
                </span>
            </td>
            <td class="px-4 py-3">
                <div class="flex gap-2 justify-center">
                    <form action="{{ route('parts.appointments.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="availble" value="{{ $app->app_id }}">
                        <button type="submit" class="px-3 py-1 bg-emerald-500 text-white text-xs rounded hover:bg-emerald-600 transition-colors">Available</button>
                    </form>
                    <form action="{{ route('parts.appointments.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="notavailable" value="{{ $app->app_id }}">
                        <button type="submit" class="px-3 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600 transition-colors">N/A</button>
                    </form>
                </div>
            </td>
        </tr>
        @empty
        <tr><td colspan="6" class="px-4 py-10 text-center text-gray-400"><i class="fa fa-calendar text-3xl mb-2 block"></i>No pending appointments</td></tr>
        @endforelse
        </tbody>
    </table>
    </div>
</div>
@endsection
