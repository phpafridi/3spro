<?php

namespace App\Http\Controllers\Service\BPJC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Body & Paint Job Controller (BP_JC)
 *
 * Handles the Body & Paint department version of the Job Controller.
 * Logic is almost identical to the regular JC but uses 'body_PaintJC' role
 * and operates on the same database tables with type='Body & Paint'.
 *
 * Original files: SERVICE/BP_JC/
 */
class BPJobController extends Controller
{
    // ─────────────────────────────────────────────
    //  DASHBOARD - Pending Job Requests
    //  Original: BP_JC/index.php
    // ─────────────────────────────────────────────
    public function index()
    {
        $pendingJobs = DB::table('jobcard as jc')
            ->join('jobc_labor as jl', 'jc.Jobc_id', '=', 'jl.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '1')
            ->where(function ($q) {
                $q->where('jl.status', '')->orWhereNull('jl.status');
            })
            ->where('jl.type', 'Body & Paint')
            ->select(
                'jl.Labor_id', 'jl.RO_no', 'jl.Labor',
                'jl.entry_time', 'v.Variant', 'v.Registration', 'jc.SA'
            )
            ->orderByDesc('jl.entry_time')
            ->get();

        return view('service.bp-jc.index', compact('pendingJobs'));
    }

    // ─────────────────────────────────────────────
    //  CUSTOMERS (job-done page)
    //  Original: BP_JC/Customers.php
    //  Handles POST to mark job as done, then shows list
    // ─────────────────────────────────────────────
    public function customers()
    {
        $jobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jc.status', '1')
            ->select(
                'jc.Jobc_id', 'jc.SA', 'jc.Open_date_time',
                'v.Registration', 'v.Variant',
                'c.Customer_name', 'c.mobile'
            )
            ->orderByDesc('jc.Open_date_time')
            ->get();

        return view('service.bp-jc.customers', compact('jobs'));
    }

    public function jobDone(Request $request)
    {
        $request->validate([
            'Labor_id' => 'required|exists:jobc_labor,Labor_id',
        ]);

        DB::table('jobc_labor')
            ->where('Labor_id', $request->Labor_id)
            ->update([
                'status'   => 'Jobclose',
                'end_time' => now(),
            ]);

        return redirect()->route('bp-jc.customers')
            ->with('success', 'Job marked as closed.');
    }

    // ─────────────────────────────────────────────
    //  IN-PROGRESS JOBS
    //  Original: BP_JC/inprogress_jobs.php
    // ─────────────────────────────────────────────
    public function inprogress()
    {
        $inprogressJobs = DB::table('jobcard as jc')
            ->join('jobc_labor as jl', 'jc.Jobc_id', '=', 'jl.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '1')
            ->where('jl.status', 'Job Assign')
            ->where('jl.type', 'Body & Paint')
            ->select(
                'jl.Labor_id', 'jl.RO_no', 'jl.Labor',
                'jl.Assign_time', 'jl.team', 'jl.bay',
                'v.Registration', 'jc.SA'
            )
            ->orderByDesc('jl.Assign_time')
            ->get();

        return view('service.bp-jc.inprogress', compact('inprogressJobs'));
    }

    // ─────────────────────────────────────────────
    //  JOB ASSIGN FORM
    //  Original: BP_JC/JobAssign.php
    // ─────────────────────────────────────────────
    public function showAssignForm($laborId)
    {
        $labor = DB::table('jobc_labor as jl')
            ->join('jobcard as jc', 'jl.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jl.Labor_id', $laborId)
            ->select('jl.*', 'v.Variant', 'v.Registration', 'jc.SA', 'jc.Customer_name')
            ->first();

        if (!$labor) {
            return redirect()->route('bp-jc.index')->with('error', 'Labor not found.');
        }

        // Body & Paint teams/bays (category = 'BP')
        $teams = DB::table('s_techteams')
            ->where('status', 1)
            ->where('category', 'BP')
            ->get();
        $bays = DB::table('s_bays')
            ->where('status', 1)
            ->where('category', 'BP')
            ->get();
        $vendors = DB::table('s_vendor_list')
            ->where('status', 'Active')
            ->get();

        return view('service.bp-jc.job-assign', compact('labor', 'teams', 'bays', 'vendors'));
    }

    public function assignJob(Request $request)
    {
        $request->validate([
            'labor_id' => 'required|exists:jobc_labor,Labor_id',
            'category' => 'required|string',
        ]);

        $data = [
            'status'      => $request->category,
            'Assign_time' => now(),
            'jc'          => Auth::user()->login_id,
        ];

        if ($request->category === 'Job Assign') {
            $data['team']           = $request->team;
            $data['bay']            = $request->bay;
            $data['estimated_time'] = $request->estimatedtime;
        } elseif (in_array($request->category, ['Job Not Done', 'Job Stopage'])) {
            $data['remarks']    = $request->remarks;
            if ($request->category === 'Job Stopage') {
                $data['resumetime'] = $request->resumetime;
            }
        }

        DB::table('jobc_labor')
            ->where('Labor_id', $request->labor_id)
            ->update($data);

        return redirect()->route('bp-jc.index')
            ->with('success', 'Job ' . $request->category . ' successfully.');
    }

    // ─────────────────────────────────────────────
    //  SUBLET
    //  Original: BP_JC/sublet.php
    // ─────────────────────────────────────────────
    public function sublet()
    {
        $sublets = DB::table('jobcard as jc')
            ->join('jobc_sublet as js', 'jc.Jobc_id', '=', 'js.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '1')
            ->where('js.status', '')
            ->select(
                'js.sublet_id', 'js.RO_no', 'js.Sublet',
                'js.qty', 'js.entry_datetime',
                'v.Variant', 'v.Registration', 'jc.SA'
            )
            ->orderByDesc('js.entry_datetime')
            ->get();

        return view('service.bp-jc.sublet', compact('sublets'));
    }

    // ─────────────────────────────────────────────
    //  PART ADD (issue parts to a BP jobcard)
    //  Original: BP_JC/part_add.php
    // ─────────────────────────────────────────────
    public function partAdd()
    {
        $pendingParts = DB::table('jobcard as jc')
            ->join('jobc_parts as jp', 'jc.Jobc_id', '=', 'jp.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '<', '2')
            ->where('jp.status', '0')
            ->select(
                'jp.parts_sale_id', 'jp.RO_no', 'jp.part_description',
                'jp.qty', 'jp.entry_datetime', 'v.Registration', 'jc.SA'
            )
            ->orderByDesc('jp.entry_datetime')
            ->get();

        return view('service.bp-jc.part-add', compact('pendingParts'));
    }

    // ─────────────────────────────────────────────
    //  SEARCH
    //  Original: BP_JC/search.php
    // ─────────────────────────────────────────────
    public function search(Request $request)
    {
        $results = collect();
        $query   = $request->input('q');

        if ($query) {
            $results = DB::table('jobcard as jc')
                ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
                ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
                ->where(function ($q) use ($query) {
                    $q->where('v.Registration', 'LIKE', "%$query%")
                      ->orWhere('c.Customer_name', 'LIKE', "%$query%")
                      ->orWhere('jc.Jobc_id', 'LIKE', "%$query%");
                })
                ->select(
                    'jc.Jobc_id', 'jc.Open_date_time', 'jc.SA', 'jc.status',
                    'v.Registration', 'v.Variant',
                    'c.Customer_name', 'c.mobile'
                )
                ->orderByDesc('jc.Open_date_time')
                ->limit(50)
                ->get();
        }

        return view('service.bp-jc.search', compact('results', 'query'));
    }

    // ─────────────────────────────────────────────
    //  UNCLOSED JC
    //  Original: BP_JC/Unclosed_JC.php
    // ─────────────────────────────────────────────
    public function unclosedJC()
    {
        $unclosedJobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->whereIn('jc.status', ['0', '1'])
            ->select(
                'jc.Jobc_id', 'jc.Open_date_time', 'jc.SA', 'jc.status', 'jc.RO_type',
                'v.Registration', 'v.Variant',
                'c.Customer_name', 'c.mobile'
            )
            ->orderByDesc('jc.Open_date_time')
            ->get();

        return view('service.bp-jc.unclosed-jc', compact('unclosedJobs'));
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL JOB REQUEST (BP)
    //  Original: BP_JC/Additional_Jobrequest.php
    // ─────────────────────────────────────────────
    public function additionalJobrequest($jobId)
    {
        $jobcard   = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard) abort(404);
        $labors    = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
        $laborList = DB::table('labor_list')->get();
        return view('service.bp-jc.additional-jobrequest', compact('jobcard', 'labors', 'laborList', 'jobId'));
    }

    public function storeAdditionalJobrequest(Request $request)
    {
        if ($request->jobrequest) {
            $type  = $request->type;
            $price = ($type === 'Workshop') ? $request->price : 0;

            DB::table('jobc_labor')->insert([
                'RO_no'      => $request->job_id,
                'Labor'      => $request->jobrequest,
                'type'       => $type,
                'cost'       => $price,
                'reason'     => $request->reason ?? '',
                'Additional' => 1,
                'entry_time' => now(),
            ]);
        }
        return redirect()->route('bp-jc.additional.jobrequest', $request->job_id)
            ->with('success', 'Job request added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL PART (BP)
    //  Original: BP_JC/Additional_part_add.php
    // ─────────────────────────────────────────────
    public function additionalPart($jobId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard) abort(404);
        $parts   = DB::table('jobc_parts')->where('RO_no', $jobId)->get();
        return view('service.bp-jc.additional-part', compact('jobcard', 'parts', 'jobId'));
    }

    public function storeAdditionalPart(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            DB::table('jobc_parts')->insert([
                'RO_no'            => $request->job_id,
                'part_description' => $request->part_description,
                'qty'              => $request->qty,
                'unitprice'        => $request->unitprice,
                'total'            => $request->totalprice,
                'Additional'       => 1,
                'entry_datetime'   => now(),
            ]);
        }
        return redirect()->route('bp-jc.additional.part', $request->job_id)
            ->with('success', 'Part added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL CONSUMABLE (BP)
    //  Original: BP_JC/Additional_consumble.php
    // ─────────────────────────────────────────────
    public function additionalConsumable($jobId)
    {
        $jobcard    = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard) abort(404);
        $consumbles = DB::table('jobc_consumble')->where('RO_no', $jobId)->get();
        return view('service.bp-jc.additional-consumable', compact('jobcard', 'consumbles', 'jobId'));
    }

    public function storeAdditionalConsumable(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            DB::table('jobc_consumble')->insert([
                'RO_no'            => $request->job_id,
                'cons_description' => $request->part_description,
                'qty'              => $request->qty,
                'unitprice'        => $request->unitprice,
                'total'            => $request->totalprice,
                'Additional'       => 1,
                'entry_datetime'   => now(),
            ]);
        }
        return redirect()->route('bp-jc.additional.consumable', $request->job_id)
            ->with('success', 'Consumable added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL SUBLET (BP)
    //  Original: BP_JC/Additional_sublet.php
    // ─────────────────────────────────────────────
    public function additionalSublet($jobId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard) abort(404);
        $sublets = DB::table('jobc_sublet')->where('RO_no', $jobId)->get();
        return view('service.bp-jc.additional-sublet', compact('jobcard', 'sublets', 'jobId'));
    }

    public function storeAdditionalSublet(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            $type       = $request->type;
            $unitprice  = ($type === 'Workshop') ? $request->unitprice : 0;
            $totalprice = ($type === 'Workshop') ? $request->totalprice : 0;

            DB::table('jobc_sublet')->insert([
                'RO_no'          => $request->job_id,
                'Sublet'         => $request->sublet,
                'type'           => $type,
                'qty'            => $request->qty,
                'unitprice'      => $unitprice,
                'additional'     => 1,
                'total'          => $totalprice,
                'entry_datetime' => now(),
            ]);
        }
        return redirect()->route('bp-jc.additional.sublet', $request->job_id)
            ->with('success', 'Sublet added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL (BP) - main open jobcard view
    //  Original: BP_JC/Additional.php
    // ─────────────────────────────────────────────
    public function additional($jobId)
    {
        $jobcard = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jc.Jobc_id', $jobId)
            ->select('jc.*', 'v.Registration', 'v.Variant', 'c.Customer_name', 'c.mobile', 'c.Customer_id')
            ->first();

        if (!$jobcard) abort(404);

        $labors     = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
        $parts      = DB::table('jobc_parts')->where('RO_no', $jobId)->get();
        $consumbles = DB::table('jobc_consumble')->where('RO_no', $jobId)->get();
        $sublets    = DB::table('jobc_sublet')->where('RO_no', $jobId)->get();

        return view('service.bp-jc.additional', compact(
            'jobcard', 'labors', 'parts', 'consumbles', 'sublets', 'jobId'
        ));
    }

    // ─────────────────────────────────────────────
    //  REPORTS
    //  NEW METHODS FOR REPORTS
    // ─────────────────────────────────────────────

    /**
     * Labor Type Report
     * Shows labor jobs by type for Body & Paint
     */
    public function reportLabor(Request $request)
    {
        $query = DB::table('jobc_labor as jl')
            ->join('jobcard as jc', 'jl.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jl.type', 'Body & Paint');

        // Apply date filter if provided
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('jl.entry_time', [$request->from_date, $request->to_date]);
        }

        $labors = $query->select(
                'jl.*', 'v.Registration', 'v.Variant', 'jc.SA', 'jc.Customer_name'
            )
            ->orderByDesc('jl.entry_time')
            ->get();

        return view('service.bp-jc.reports.labor', compact('labors'));
    }

    /**
     * Labor Detail Report
     * Shows detailed labor information for Body & Paint
     */
    public function reportLaborDetail(Request $request)
    {
        $query = DB::table('jobc_labor as jl')
            ->join('jobcard as jc', 'jl.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jl.type', 'Body & Paint');

        // Apply date filter if provided
        if ($request->filled('from_date') && $request->filled('to_date')) {
            $query->whereBetween('jl.entry_time', [$request->from_date, $request->to_date]);
        }

        // Apply status filter if provided
        if ($request->filled('status')) {
            $query->where('jl.status', $request->status);
        }

        $labors = $query->select(
                'jl.*', 'v.Registration', 'v.Variant',
                'jc.SA', 'jc.Customer_name', 'c.mobile'
            )
            ->orderByDesc('jl.entry_time')
            ->get();

        return view('service.bp-jc.reports.labor-detail', compact('labors'));
    }

    // ─────────────────────────────────────────────
    //  AJAX: Team members
    // ─────────────────────────────────────────────
    public function getTeamMembers(Request $request)
    {
        $members = DB::table('s_techteams')
            ->where('team_name', $request->team)
            ->value('members');

        return response()->json(['members' => $members]);
    }
}
