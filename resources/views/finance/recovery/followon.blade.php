@extends('layouts.master')
@include('finance.recovery.sidebar')
@section('title', 'Recovery - Add Followup')
@section('content')
<div class="bg-white rounded-2xl shadow-sm p-6 max-w-xl">
    <h2 class="text-2xl font-semibold text-gray-800 mb-1">
        <i class="fas fa-phone text-green-500 mr-2"></i>Add Followup
    </h2>
    <p class="text-sm text-gray-400 mb-6">
        Customer: <strong class="text-gray-700">{{ $id }}</strong>
        @if($contact)
        &nbsp;| Contact: <strong class="text-gray-700">{{ $contact }}</strong>
        @endif
    </p>

    {{-- recov_fallowons: id, cust_name, Datetime(date), Person_contacted, Contact_type, Remarks --}}
    <form method="POST" action="{{ route('recovery.followup', ['id' => $id]) }}" class="space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Follow-up Date <span class="text-red-500">*</span></label>
            <input type="date" name="fdate" required value="{{ date('Y-m-d') }}"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400">
            <p class="text-xs text-gray-400 mt-1">The date of the contact / scheduled follow-up</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Person Contacted</label>
            <input type="text" name="person_contacted" maxlength="35"
                   placeholder="Name of person spoken to"
                   class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contact Type <span class="text-red-500">*</span></label>
            <select name="contact_type" required
                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400">
                <option value="">-- Select --</option>
                <option value="Phone Call">Phone Call</option>
                <option value="SMS">SMS</option>
                <option value="WhatsApp">WhatsApp</option>
                <option value="Visit">Visit</option>
                <option value="Fallowup">Followup (Scheduled)</option>
                <option value="Email">Email</option>
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
            <textarea name="remarks" rows="3"
                      placeholder="What was discussed / outcome..."
                      class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-green-400 text-sm"></textarea>
        </div>
        <div class="flex gap-3">
            <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-xl text-sm font-medium hover:bg-green-700 transition">
                <i class="fas fa-save mr-2"></i>Save Followup
            </button>
            <a href="{{ route('recovery.history', ['id' => $id]) }}"
               class="px-6 py-2 bg-gray-100 text-gray-700 rounded-xl text-sm font-medium hover:bg-gray-200 transition">
                View History
            </a>
        </div>
    </form>
</div>
@endsection
