{{-- resources/views/parts/entry/sale_part.blade.php --}}
@extends('parts.layout')

@section('title', 'Counter Sale - Parts')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">New Counter Sale</h2>
    <p class="text-sm text-gray-500 mt-1">Sale ID will be: <strong>{{ $maxInv }}</strong></p>
</div>

@if(session('success'))
    <div class="mb-4 p-4 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 rounded-r-xl">
        {{ session('success') }}
    </div>
@endif

<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
        <form action="{{ route('parts.sale.store') }}" method="POST">
            @csrf

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Jobber / Customer <span class="text-red-500">*</span>
                    </label>
                    <select name="required_jobber" required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Select --</option>
                        <option value="Cash">Cash</option>
                        <option value="Workshop">Workshop</option>
                        @foreach($jobbers as $jobber)
                            <option value="{{ $jobber->jbr_name }}">{{ $jobber->jbr_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Payment Method <span class="text-red-500">*</span>
                    </label>
                    <select name="payment_method" required
                            class="w-full border border-gray-300 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Select --</option>
                        <option value="Cash">Cash</option>
                        <option value="Cheque">Cheque</option>
                        <option value="Credit">Credit</option>
                        <option value="Online Transfer">Online Transfer</option>
                    </select>
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2.5 rounded-xl font-medium hover:from-indigo-700 hover:to-purple-700 transition-all">
                    Create Sale Invoice &rarr;
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
