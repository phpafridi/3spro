@extends('layouts.master')
@section('title', 'SM - Tech Teams')
@section('sidebar-menu')
    @include('service.partials.sm-sidebar')
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded-md">{{ session('error') }}</div>
    @endif

    <!-- Stack on mobile, grid on desktop -->
    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Add Team Form - Full width on mobile, 1/3 on desktop -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded shadow-sm p-4 sm:p-6">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Add Team</h2>

                <form method="POST" action="{{ route('sm.master.teams.store') }}">
                    @csrf

                    <div class="space-y-3 sm:space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">
                                Team Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="team_name" required
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Members</label>
                            <input type="text" name="members" placeholder="Comma-separated names"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                            <select name="category"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="M">Mechanical</option>
                                <option value="DP">Dent Paint</option>
                            </select>
                        </div>

                        <button type="submit"
                            class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors">
                            <i class="fa fa-plus mr-2"></i> Add Team
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Teams List - Full width on mobile, 2/3 on desktop -->
        <div class="w-full lg:w-2/3">
            <div class="bg-white rounded shadow-sm p-4 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-2">
                    <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Teams</h2>
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 text-sm rounded-full self-start sm:self-auto">
                        {{ $teams->count() }} Total
                    </span>
                </div>

                <!-- Horizontal scroll on mobile -->
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <div class="overflow-hidden border border-gray-200 sm:rounded">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            ID</th>
                                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Team Name</th>
                                        <th
                                            class="hidden sm:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Members</th>
                                        <th
                                            class="hidden md:table-cell px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Category</th>
                                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Status</th>
                                        <th class="px-3 sm:px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                                            Action</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($teams as $t)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                                {{ $t->team_id }}</td>
                                            <td
                                                class="px-3 sm:px-4 py-3 text-sm font-medium text-gray-800 whitespace-nowrap">
                                                {{ $t->team_name }}</td>
                                            <td
                                                class="hidden sm:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                                {{ $t->members ?? '-' }}</td>
                                            <td
                                                class="hidden md:table-cell px-3 sm:px-4 py-3 text-sm text-gray-500 whitespace-nowrap">
                                                {{ $t->category ?? '-' }}</td>
                                            <td class="px-3 sm:px-4 py-3 text-sm whitespace-nowrap">
                                                @if ($t->status == 'Active')
                                                    <span
                                                        class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full">Active</span>
                                                @else
                                                    <span
                                                        class="px-2 py-1 bg-gray-100 text-gray-600 text-xs rounded-full">{{ $t->status ?? 'Inactive' }}</span>
                                                @endif
                                            </td>
                                            <td class="px-3 sm:px-4 py-3 text-sm whitespace-nowrap">
                                                <form method="POST" action="{{ route('sm.master.teams.delete') }}"
                                                    class="inline" onsubmit="return confirm('Delete this team?')">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $t->team_id }}">
                                                    <button type="submit" class="text-red-600 hover:text-red-800 p-1"
                                                        title="Delete">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="px-3 sm:px-4 py-8 text-center text-gray-400">
                                                No teams found.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Mobile view hint -->
                <div class="block sm:hidden mt-2 text-xs text-gray-400 text-center">
                    <i class="fa fa-arrow-left mr-1"></i> Scroll horizontally to see more <i
                        class="fa fa-arrow-right ml-1"></i>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Optional: Add any custom JavaScript here
            });
        </script>
    @endpush
@endsection
