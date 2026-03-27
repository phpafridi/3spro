@extends('layouts.master')
@section('title', 'Job Complete')
@section('sidebar-menu')
    @include('service.partials.jobcard-sidebar')
@endsection
@section('content')

@if(session('success'))
<div class="mb-4 p-3 bg-green-100 text-green-800 rounded-md text-sm">{{ session('success') }}</div>
@endif

<div class="bg-white rounded shadow-sm p-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6">Job Complete</h2>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-red-600">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Jobcard#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Reg#</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Customer</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">Labor</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">Parts</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">Sublet</th>
                    <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase">Consumble</th>
                    <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($jobs as $job)
                @php
                    // Labor: blue=none, red=pending workshop, green=all done
                    $laborAny    = \Illuminate\Support\Facades\DB::table('jobc_labor')->where('RO_no', $job->Jobc_id)->exists();
                    $laborPending= $laborAny ? \Illuminate\Support\Facades\DB::table('jobc_labor')
                        ->where('RO_no', $job->Jobc_id)->where('type','Workshop')
                        ->whereNotIn('status',['Job Not Done','Jobclose'])->exists() : false;

                    // Parts: blue=none, red=not issued(status=0), green=all issued
                    $partsAny    = \Illuminate\Support\Facades\DB::table('jobc_parts')->where('RO_no', $job->Jobc_id)->exists();
                    $partsPending= $partsAny ? \Illuminate\Support\Facades\DB::table('jobc_parts')
                        ->where('RO_no', $job->Jobc_id)->where('status','0')->exists() : false;

                    // Sublet: blue=none, red=workshop not done, green=all done
                    $subletAny    = \Illuminate\Support\Facades\DB::table('jobc_sublet')->where('RO_no', $job->Jobc_id)->exists();
                    $subletPending= $subletAny ? \Illuminate\Support\Facades\DB::table('jobc_sublet')
                        ->where('RO_no', $job->Jobc_id)->where('type','Workshop')->where('status','0')->exists() : false;

                    // Consumble: blue=none, red=not issued(status=0), green=all issued
                    $consAny    = \Illuminate\Support\Facades\DB::table('jobc_consumble')->where('RO_no', $job->Jobc_id)->exists();
                    $consPending= $consAny ? \Illuminate\Support\Facades\DB::table('jobc_consumble')
                        ->where('RO_no', $job->Jobc_id)->where('status','0')->exists() : false;

                    // All 4 must be green (1) to enable close button
                    $lb  = !$laborAny  || !$laborPending  ? 1 : 0;
                    $pr  = !$partsAny  || !$partsPending  ? 1 : 0;
                    $sb  = !$subletAny || !$subletPending ? 1 : 0;
                    $cns = !$consAny   || !$consPending   ? 1 : 0;
                    $allDone = ($lb + $pr + $sb + $cns) === 4;
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-sm font-bold">
                        <a href="{{ route('jobcard.additional', $job->Jobc_id) }}" class="text-blue-600 hover:underline">#{{ $job->Jobc_id }}</a>
                    </td>
                    <td class="px-4 py-3 text-sm font-medium text-red-600">{{ $job->Registration }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">
                        {{ $job->Customer_name }}<br>
                        <span class="text-xs text-gray-400">{{ $job->mobile }}</span>
                    </td>

                    {{-- Labor indicator --}}
                    <td class="px-4 py-3 text-center">
                        @if(!$laborAny)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-semibold">Labor</span>
                        @elseif($laborPending)
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded font-semibold animate-pulse">Labor</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-semibold">Labor ✓</span>
                        @endif
                    </td>

                    {{-- Parts indicator --}}
                    <td class="px-4 py-3 text-center">
                        @if(!$partsAny)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-semibold">Parts</span>
                        @elseif($partsPending)
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded font-semibold animate-pulse">Parts</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-semibold">Parts ✓</span>
                        @endif
                    </td>

                    {{-- Sublet indicator --}}
                    <td class="px-4 py-3 text-center">
                        @if(!$subletAny)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-semibold">Sublet</span>
                        @elseif($subletPending)
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded font-semibold animate-pulse">Sublet</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-semibold">Sublet ✓</span>
                        @endif
                    </td>

                    {{-- Consumble indicator --}}
                    <td class="px-4 py-3 text-center">
                        @if(!$consAny)
                            <span class="px-2 py-1 bg-blue-100 text-blue-700 text-xs rounded font-semibold">Consumble</span>
                        @elseif($consPending)
                            <span class="px-2 py-1 bg-red-100 text-red-700 text-xs rounded font-semibold animate-pulse">Consumble</span>
                        @else
                            <span class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded font-semibold">Consumble ✓</span>
                        @endif
                    </td>

                    {{-- Close button or In Progress --}}
                    <td class="px-4 py-3">
                        @if($allDone)
                        <form method="POST" action="{{ route('jobcard.complete.process') }}"
                              onsubmit="return confirm('Close RO #{{ $job->Jobc_id }}?')">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $job->Jobc_id }}">
                            <button type="submit"
                                    class="px-3 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold rounded transition-colors">
                                Jobcomplete
                            </button>
                        </form>
                        @else
                        <span class="text-gray-400 text-xs italic">In Progress</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                        <i class="fa fa-check-circle text-4xl block mb-2 text-green-400"></i>
                        No jobs pending completion.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
