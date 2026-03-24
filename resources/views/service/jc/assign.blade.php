{{-- resources/views/service/jc/assign.blade.php --}}
@extends('layouts.master')

@section('title', 'Job Controller - Assign Job')

@section('sidebar-menu')
    <a href="{{ route('jc.dashboard') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-wrench w-6"></i>
        <span>Jobs Requests</span>
    </a>
    <a href="{{ route('jc.sublet') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.sublet') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-sign-out-alt w-6"></i>
        <span>Sublet Requests</span>
    </a>
    <a href="{{ route('jc.inprogress') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.inprogress') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-edit w-6"></i>
        <span>Inprogress Jobs</span>
    </a>
    <a href="{{ route('jc.parts-status') }}" class="flex items-center px-4 py-3 text-sm {{ request()->routeIs('jc.parts-status') ? 'bg-blue-700' : 'hover:bg-blue-700' }} rounded transition-colors">
        <i class="fas fa-search-plus w-6"></i>
        <span>Parts Status</span>
    </a>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Job Details Card -->
    <div class="bg-white rounded shadow-sm overflow-hidden mb-6">
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-white">
                    <i class="fas fa-tasks mr-2"></i> Assign Job #{{ $labor->RO_no }}
                </h2>
                <a href="{{ route('jc.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-white text-blue-600 text-sm font-medium rounded hover:bg-gray-50 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i> Back
                </a>
            </div>
        </div>

        <div class="p-6">
            <!-- Job Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-gray-50 rounded p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Job Information</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Labor:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $labor->Labor }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Job Card:</dt>
                            <dd class="text-sm font-mono font-medium text-gray-900">#{{ $labor->RO_no }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Customer:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $labor->Customer_name }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="bg-gray-50 rounded p-4">
                    <h3 class="text-sm font-medium text-gray-500 mb-3">Vehicle Information</h3>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Model:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $labor->Variant }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Registration:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $labor->Registration }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Service Advisor:</dt>
                            <dd class="text-sm font-medium text-gray-900">{{ $labor->SA }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Assignment Form -->
            <form action="{{ route('jc.assign.process') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="labor_id" value="{{ $labor->Labor_id }}">
                <input type="hidden" name="RO_no" value="{{ $labor->RO_no }}">

                <!-- Job Progress -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Job Progress</label>
                    <select name="category" id="category" required class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        <option value="Job Assign">Job Assign</option>
                        <option value="Job Not Done">Job Not Done</option>
                    </select>
                </div>

                <!-- Dynamic Fields Container -->
                <div id="dynamic-fields" class="space-y-4">
                    <!-- Default Job Assign fields -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Bay</label>
                        <select name="bay" required class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choose a bay...</option>
                            @foreach($bays as $bay)
                                <option value="{{ $bay->bay_name }}">{{ $bay->bay_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Team</label>
                        <select name="team" id="team" required class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Choose a team...</option>
                            @foreach($teams as $team)
                                <option value="{{ $team->team_name }}">{{ $team->team_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Completion Time</label>
                        <input type="datetime-local" name="estimatedtime" required
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                               min="{{ date('Y-m-d\TH:i') }}">
                    </div>
                </div>

                <!-- Team Members Display -->
                <div id="team-members" class="bg-blue-50 rounded p-4 text-sm text-blue-700">
                    Select a team to see members
                </div>

                <!-- Form Actions -->
                <div class="flex items-center space-x-3 pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded transition-colors">
                        <i class="fas fa-check mr-2"></i> Assign Job
                    </button>
                    <button type="reset" class="px-6 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded transition-colors">
                        Reset
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Vendors List Card -->
    <div class="bg-white rounded shadow-sm overflow-hidden">
        <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-800">
                <i class="fas fa-truck mr-2"></i> Active Vendors
            </h3>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Work Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Person</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Location</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($vendors as $vendor)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ $vendor->vendor_name }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $vendor->work_type }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $vendor->contact }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $vendor->contact_person }}</td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $vendor->Location }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">No vendors found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Team members AJAX
    document.getElementById('team')?.addEventListener('change', function() {
        const team = this.value;
        if (!team) return;

        fetch('{{ route("jc.team-members") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ team: team })
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('team-members').innerHTML =
                '<strong>Team Members:</strong> ' + data.members;
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Dynamic form fields based on category
    document.getElementById('category')?.addEventListener('change', function() {
        const category = this.value;
        const container = document.getElementById('dynamic-fields');
        let html = '';

        if (category === 'Job Not Done') {
            html = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Reason</label>
                    <select name="remarks" required class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select reason...</option>
                        <option value="Machine">Machine Issue</option>
                        <option value="Technician">Technician Issue</option>
                        <option value="Time">Time Constraint</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
            `;
        } else {
            html = `
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Bay</label>
                    <select name="bay" required class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Choose a bay...</option>
                        @foreach($bays as $bay)
                            <option value="{{ $bay->bay_name }}">{{ $bay->bay_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Select Team</label>
                    <select name="team" id="team" required class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Choose a team...</option>
                        @foreach($teams as $team)
                            <option value="{{ $team->team_name }}">{{ $team->team_name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimated Completion Time</label>
                    <input type="datetime-local" name="estimatedtime" required
                           class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500"
                           min="{{ date('Y-m-d\TH:i') }}">
                </div>
            `;
        }

        container.innerHTML = html;

        // Re-attach event listener for team select if it exists
        if (category === 'Job Assign') {
            document.getElementById('team')?.addEventListener('change', function() {
                const team = this.value;
                if (!team) return;

                fetch('{{ route("jc.team-members") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ team: team })
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('team-members').innerHTML =
                        '<strong>Team Members:</strong> ' + data.members;
                });
            });
        }
    });
</script>
@endpush
