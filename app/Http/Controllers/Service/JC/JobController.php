<?php
namespace App\Http\Controllers\Service\JC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JobController extends Controller
{
    /**
     * Display job requests dashboard
     */
    public function index()
    {
        $pendingJobs = DB::table('jobcard as jc')
            ->join('jobc_labor as jl', 'jc.Jobc_id', '=', 'jl.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->leftJoin('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('jc.status', 1)  // Use integer, not string
            ->where('jl.type', 'Workshop')
            ->where(function ($query) {
                $query->where('jl.status', '')
                    ->orWhereNull('jl.status')
                    ->orWhere('jl.status', '0');  // Sometimes status '0' might mean pending
            })
            ->select(
                'jl.Labor_id',
                'jl.RO_no',
                'jl.Labor',
                'jl.entry_time',
                'jl.status as labor_status',
                'v.Variant',
                'v.Registration',
                'jc.SA',
                'jc.Customer_name',
                DB::raw('COALESCE(c.mobile, "N/A") as mobile')
            )
            ->orderByDesc('jl.entry_time')
            ->get();

        return view('service.jc.index', compact('pendingJobs'));
    }

    /**
     * Display sublet requests
     */
    public function sublet()
    {
        $subletRequests = DB::table('jobcard as jc')
            ->join('jobc_sublet as js', 'jc.Jobc_id', '=', 'js.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '1')
            ->where('js.status', '0')
            ->select(
                'js.sublet_id',
                'js.RO_no',
                'js.Sublet',
                'js.qty',
                'js.entry_datetime',
                'v.Variant',
                'v.Registration',
                'jc.SA'
            )
            ->orderBy('js.entry_datetime', 'desc')
            ->get();

        return view('service.jc.sublet', compact('subletRequests'));
    }

    /**
     * Display in-progress jobs
     */
    public function inprogress()
    {
        $inprogressJobs = DB::table('jobcard as jc')
            ->join('jobc_labor as jl', 'jc.Jobc_id', '=', 'jl.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '1')
            ->where('jl.status', 'Job Assign')
            ->select(
                'jl.Labor_id',
                'jl.RO_no',
                'jl.Labor',
                'jl.Assign_time',
                'jl.team',
                'jl.bay',
                'v.Registration',
                'jc.SA'
            )
            ->orderBy('jl.Assign_time', 'desc')
            ->get();

        return view('service.jc.inprogress', compact('inprogressJobs'));
    }

    /**
     * Display parts status
     */
    public function partsStatus()
    {
        // Get parts
        $partsStatus = DB::table('jobcard as jc')
            ->join('jobc_parts as jp', 'jc.Jobc_id', '=', 'jp.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '<', '2')
            ->select(
                'jp.RO_no',
                'jp.part_description',
                'jp.entry_datetime',
                'jp.issue_time',
                'jp.status',
                'v.Registration'
            )
            ->orderBy('jp.status', 'asc')
            ->get();

        // Get consumables
        $consumableStatus = DB::table('jobcard as jc')
            ->join('jobc_consumble as jcns', 'jc.Jobc_id', '=', 'jcns.RO_no')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '<', '2')
            ->select(
                'jcns.RO_no',
                'jcns.cons_description',
                'jcns.entry_datetime',
                'jcns.issue_time',
                'jcns.status',
                'v.Registration'
            )
            ->orderBy('jcns.status', 'asc')
            ->get();

        return view('service.jc.parts-status', compact('partsStatus', 'consumableStatus'));
    }

    /**
     * Show assign job form
     */
    public function showAssignForm($laborId)
    {
        $labor = DB::table('jobc_labor as jl')
            ->join('jobcard as jc', 'jl.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jl.Labor_id', $laborId)
            ->select(
                'jl.*',
                'v.Variant',
                'v.Registration',
                'jc.SA',
                'jc.Customer_name'
            )
            ->first();

        if (!$labor) {
            return redirect()->route('jc.dashboard')->with('error', 'Labor not found');
        }

        // Get active teams and bays
        $teams = DB::table('s_techteams')->where('status', 1)->get();
        $bays = DB::table('s_bays')->where('status', 1)->get();

        // Get active vendors
        $vendors = DB::table('s_vendor_list')
            ->where('status', 'Active')
            ->select('vendor_name', 'work_type', 'contact_person', 'contact', 'Location', 'addedby', 'when')
            ->get();

        return view('service.jc.assign', compact('labor', 'teams', 'bays', 'vendors'));
    }

    /**
     * Process job assignment
     */
    public function assignJob(Request $request)
    {
        $request->validate([
            'labor_id' => 'required|exists:jobc_labor,Labor_id',
            'category' => 'required|string',
            'team' => 'required_if:category,Job Assign',
            'bay' => 'required_if:category,Job Assign',
            'estimatedtime' => 'required_if:category,Job Assign',
            'remarks' => 'required_if:category,Job Not Done,Job Stopage',
            'resumetime' => 'required_if:category,Job Stopage',
        ]);

        try {
            $updateData = [
                'status' => $request->category,
                'Assign_time' => now(),
                'jc' => Auth::user()->login_id
            ];

            if ($request->category == 'Job Assign') {
                $updateData['team'] = $request->team;
                $updateData['bay'] = $request->bay;
                $updateData['estimated_time'] = $request->estimatedtime;
            } elseif ($request->category == 'Job Not Done') {
                $updateData['remarks'] = $request->remarks;
                $updateData['cost'] = 0;
            } elseif ($request->category == 'Job Stopage') {
                $updateData['remarks'] = $request->remarks;
                $updateData['resumetime'] = $request->resumetime;
            }

            DB::table('jobc_labor')
                ->where('Labor_id', $request->labor_id)
                ->update($updateData);

            return redirect()->route('jc.dashboard')
                ->with('success', 'Job ' . $request->category . ' successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to process job: ' . $e->getMessage());
        }
    }

    /**
     * Mark job as done
     */
    public function jobDone(Request $request)
    {
        $request->validate([
            'Labor_id' => 'required|exists:jobc_labor,Labor_id'
        ]);

        DB::table('jobc_labor')
            ->where('Labor_id', $request->Labor_id)
            ->update([
                'status' => 'Jobclose',
                'end_time' => now()
            ]);

        return redirect()->back()->with('success', 'Job marked as done!');
    }

    /**
     * Show sublet assign form
     */
    public function showSubletAssignForm($subletId)
    {
        $sublet = DB::table('jobc_sublet')
            ->where('sublet_id', $subletId)
            ->first();

        if (!$sublet) {
            return redirect()->route('jc.sublet')->with('error', 'Sublet not found');
        }

        $vendors = DB::table('s_vendor_list')
            ->where('status', 'Active')
            ->select('vendor_name', 'work_type', 'contact_person', 'contact', 'Location', 'addedby', 'when')
            ->get();

        return view('service.jc.sublet_assign', compact('sublet', 'vendors'));
    }

    /**
     * Process sublet assignment
     */
    public function assignSublet(Request $request)
    {
        $request->validate([
            'sublet_id' => 'required|exists:jobc_sublet,sublet_id',
            'parts_details' => 'required|string',
            'Vendor' => 'required|string',
            'who_taking' => 'required|string',
        ]);

        try {
            DB::table('jobc_sublet')
                ->where('sublet_id', $request->sublet_id)
                ->update([
                    'Vendor' => $request->Vendor,
                    'status' => 'JobDone',
                    'parts_details' => $request->parts_details,
                    'Asign_time' => now(),
                    'who_taking' => $request->who_taking,
                    'jc' => Auth::user()->login_id
                ]);

            return redirect()->route('jc.sublet')
                ->with('success', 'Sublet assigned successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to assign sublet: ' . $e->getMessage());
        }
    }

    /**
     * Show sublet job done form
     */
    public function showSubletDoneForm($subletId)
    {
        $sublet = DB::table('jobc_sublet')
            ->where('sublet_id', $subletId)
            ->first();

        if (!$sublet) {
            return redirect()->route('jc.sublet')->with('error', 'Sublet not found');
        }

        $vendors = DB::table('s_vendor_list')
            ->where('status', 'Active')
            ->select('vendor_name', 'work_type', 'contact_person', 'contact', 'Location', 'addedby', 'when')
            ->get();

        return view('service.jc.sublet_assign', compact('sublet', 'vendors'))->with('jobDoneMode', true);
    }



    /**
     * Process sublet job done
     */
    public function subletDone(Request $request)
    {
        $request->validate([
            'sublet_id' => 'required|exists:jobc_sublet,sublet_id',
            'Vendorprice' => 'required|numeric',
            'Logistics' => 'required|numeric',
        ]);

        try {
            DB::table('jobc_sublet')
                ->where('sublet_id', $request->sublet_id)
                ->update([
                    'Vendor_price' => $request->Vendorprice,
                    'logistics' => $request->Logistics,
                    'status' => 'JobDone',
                    'end_time' => now()
                ]);

            return redirect()->route('jc.sublet')
                ->with('success', 'Sublet completed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to complete sublet: ' . $e->getMessage());
        }
    }

    /**
     * Get team members via AJAX
     */
    public function getTeamMembers(Request $request)
    {
        $team = $request->team;
        $members = DB::table('s_techteams')
            ->where('team_name', $team)
            ->value('members');

        return response()->json(['members' => $members]);
    }
}
