<?php

namespace App\Http\Controllers\Service\SM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SMController extends Controller
{
    // ─────────────────────────────────────────────
    //  DASHBOARD  (index.php / index2.php)
    // ─────────────────────────────────────────────
    public function index()
    {
        // Unclosed jobcards (status < 2) with blinking-alert logic
        $unclosedJobs = DB::table('jobcard')
            ->where('status', '<', 2)
            ->orderBy('Jobc_id', 'desc')
            ->get();

        // Count alerts (jobs open > 24 hrs)
        $alertCount = DB::table('jobcard')
            ->where('status', '<', 2)
            ->whereRaw("TIMESTAMPDIFF(HOUR, Open_date_time, NOW()) > 24")
            ->count();

        return view('service.sm.index', compact('unclosedJobs', 'alertCount'));
    }

    // ─────────────────────────────────────────────
    //  UNCLOSED ROs  (Unclosed_ROs.php)
    // ─────────────────────────────────────────────
    public function unclosedROs()
    {
        $jobs = DB::table('jobcard')
            ->where('status', '<', 2)
            ->orderBy('Jobc_id', 'desc')
            ->get();

        return view('service.sm.unclosed-ros', compact('jobs'));
    }

    // ─────────────────────────────────────────────
    //  SEARCH  (search.php)  – redirects to print pages
    // ─────────────────────────────────────────────
    public function search(Request $request)
    {
        if ($request->isMethod('post')) {
            $search = $request->input('search');
            $field  = $request->input('field');

            switch ($field) {
                case 'jobcard-instail':
                    return redirect()->route('cashier.print.open', ['job_id' => $search]);
                case 'jobcard-closed':
                    return redirect()->route('cashier.print.closed', ['job_id' => $search]);
                case 'Invoice':
                    return redirect()->route('sm.print-invoice', ['id' => $search]);
                case 'SalesTax':
                    return redirect()->route('cashier.tax-invoice', ['inv_tax' => $search]);
            }
        }

        return view('service.sm.search');
    }

    // ─────────────────────────────────────────────
    //  STATUS PAGES
    // ─────────────────────────────────────────────
    public function statusLabor()
    {
        // All in-workshop jobcards (status=1) with their labor
        $jobcards = DB::table('jobcard')
            ->where('status', '1')
            ->orderBy('Jobc_id', 'desc')
            ->get();

        $laborData = [];
        foreach ($jobcards as $jc) {
            $laborData[$jc->Jobc_id] = DB::table('jobc_labor')
                ->where('RO_no', $jc->Jobc_id)
                ->get();
        }

        return view('service.sm.status-labor', compact('jobcards', 'laborData'));
    }

    public function statusParts()
    {
        $jobcards = DB::table('jobcard')
            ->where('status', '1')
            ->orderBy('Jobc_id', 'desc')
            ->get();

        $partsData = [];
        foreach ($jobcards as $jc) {
            $partsData[$jc->Jobc_id] = DB::table('jobc_parts')
                ->where('RO_no', $jc->Jobc_id)
                ->get();
        }

        return view('service.sm.status-parts', compact('jobcards', 'partsData'));
    }

    public function statusSublet()
    {
        $jobcards = DB::table('jobcard')
            ->where('status', '1')
            ->orderBy('Jobc_id', 'desc')
            ->get();

        $subletData = [];
        foreach ($jobcards as $jc) {
            $subletData[$jc->Jobc_id] = DB::table('jobc_sublet')
                ->where('RO_no', $jc->Jobc_id)
                ->get();
        }

        return view('service.sm.status-sublet', compact('jobcards', 'subletData'));
    }

    public function statusConsumable()
    {
        $jobcards = DB::table('jobcard')
            ->where('status', '1')
            ->orderBy('Jobc_id', 'desc')
            ->get();

        $consData = [];
        foreach ($jobcards as $jc) {
            $consData[$jc->Jobc_id] = DB::table('jobc_consumble')
                ->where('RO_no', $jc->Jobc_id)
                ->get();
        }

        return view('service.sm.status-consumable', compact('jobcards', 'consData'));
    }

    // ─────────────────────────────────────────────
    //  HISTORY  (history.php)
    // ─────────────────────────────────────────────
    public function history(Request $request)
    {
        $jobId  = $request->input('job_id');
        $labors = collect();

        if ($jobId) {
            $labors = DB::table('jobc_labor')
                ->where('RO_no', $jobId)
                ->orderBy('Labor_id')
                ->get();
        }

        return view('service.sm.history', compact('labors', 'jobId'));
    }

    // ─────────────────────────────────────────────
    //  JC CHANGES  (jc_changes.php)
    // ─────────────────────────────────────────────
    public function jcChanges(Request $request)
    {
        $jobId   = $request->input('jobc_id');
        $changes = collect();

        if ($jobId) {
            // Labor changes log
            $changes = DB::table('jobc_labor')
                ->where('RO_no', $jobId)
                ->orderBy('Labor_id')
                ->get();
        }

        return view('service.sm.jc-changes', compact('changes', 'jobId'));
    }

    // ─────────────────────────────────────────────
    //  LABOR CHANGE  (laborchange.php) – price increase only
    // ─────────────────────────────────────────────
    public function laborChange(Request $request)
    {
        $jobId  = $request->input('jobc_id');
        $labors = collect();
        $error  = null;

        if ($jobId) {
            $labors = DB::table('jobc_labor')
                ->where('RO_no', $jobId)
                ->orderBy('Labor_id')
                ->get();
        }

        return view('service.sm.labor-change', compact('labors', 'jobId', 'error'));
    }

    public function laborChangeUpdate(Request $request)
    {
        $laborId = $request->input('Labor_id');
        $cost    = $request->input('cost');
        $orgCost = $request->input('orgcost');
        $roNo    = $request->input('ro_no');

        // Check jobcard is open AND new price > old price
        $jc = DB::table('jobcard')
            ->where('Jobc_id', $roNo)
            ->where('status', '<', 2)
            ->first();

        if ($jc && $cost > $orgCost) {
            DB::table('jobc_labor')
                ->where('Labor_id', $laborId)
                ->update(['cost' => $cost]);
            return redirect()->route('sm.labor-change', ['jobc_id' => $roNo])
                ->with('success', 'Labor price updated.');
        }

        return redirect()->route('sm.labor-change', ['jobc_id' => $roNo])
            ->with('error', 'JOBCARD CLOSED or new price is not higher than original. Only price increase is allowed.');
    }

    // ─────────────────────────────────────────────
    //  HIDDEN LABOR CHANGE  (hidden_laborchange.php)
    //  SM override – any price change + delete labor + change sublet total
    // ─────────────────────────────────────────────
    public function hiddenLaborChange(Request $request)
    {
        $jobId  = $request->input('jobc_id');
        $labors = collect();
        $sublets = collect();

        if ($jobId) {
            $labors  = DB::table('jobc_labor')->where('RO_no', $jobId)->orderBy('Labor_id')->get();
            $sublets = DB::table('jobc_sublet')->where('RO_no', $jobId)->orderBy('sublet_id')->get();
        }

        return view('service.sm.hidden-labor-change', compact('labors', 'sublets', 'jobId'));
    }

    public function hiddenLaborUpdate(Request $request)
    {
        $roNo = $request->input('ro_no');

        // Update labor cost (no increase restriction)
        if ($request->filled('Labor_id')) {
            $jc = DB::table('jobcard')->where('Jobc_id', $roNo)->where('status', '<', 2)->first();
            if ($jc) {
                DB::table('jobc_labor')
                    ->where('Labor_id', $request->input('Labor_id'))
                    ->update(['cost' => $request->input('cost')]);
            }
        }

        // Delete labor
        if ($request->filled('deleted_Labor_id')) {
            $jc = DB::table('jobcard')->where('Jobc_id', $roNo)->where('status', '<', 2)->first();
            if ($jc) {
                DB::table('jobc_labor')->where('Labor_id', $request->input('deleted_Labor_id'))->delete();
            }
        }

        // Update sublet total
        if ($request->filled('sublet_id')) {
            $jc = DB::table('jobcard')->where('Jobc_id', $roNo)->where('status', '<', 2)->first();
            if ($jc) {
                DB::table('jobc_sublet')
                    ->where('sublet_id', $request->input('sublet_id'))
                    ->update(['total' => $request->input('total')]);
            }
        }

        return redirect()->route('sm.hidden-labor-change', ['jobc_id' => $roNo])
            ->with('success', 'Updated successfully.');
    }

    // ─────────────────────────────────────────────
    //  UNCLOSE JOBCARD  (unclose.php)
    // ─────────────────────────────────────────────
    public function unclose()
    {
        return view('service.sm.unclose');
    }

    public function uncloseProcess(Request $request)
    {
        $jobcId  = $request->input('jobc_id');
        $reason  = $request->input('reason');
        $passwrd = $request->input('passwrd');

        // Password from config (converted from hardcoded '123MG123')
        if ($passwrd !== config('service.unclose_password', '123MG123')) {
            return back()->with('error', 'Password is incorrect!');
        }

        $sm = Auth::user()->login_id ?? Auth::user()->name;

        // Insert unclosed log
        DB::table('s_Unclosed_jc')->insert([
            'jobc_id'     => $jobcId,
            'SM_reason'   => $reason,
            'SM'          => $sm,
            'sm_datetime' => now(),
        ]);

        // Get the new record id
        $maxId = DB::table('s_Unclosed_jc')->max('unjc_Id');

        // Update total_invoice from jobc_invoice
        DB::statement("
            UPDATE s_Unclosed_jc
            INNER JOIN jobc_invoice ON s_Unclosed_jc.jobc_id = jobc_invoice.Jobc_id
            SET s_Unclosed_jc.total_invoice   = jobc_invoice.Total,
                s_Unclosed_jc.old_inv_datime  = jobc_invoice.datetime
            WHERE unjc_Id = ?
        ", [$maxId]);

        // Reopen jobcard
        DB::table('jobcard')->where('Jobc_id', $jobcId)->update(['status' => 1]);

        return back()->with('success', "Jobcard #{$jobcId} has been reopened.");
    }

    // ─────────────────────────────────────────────
    //  ACTIVE CUSTOMERS  (AC.php)
    // ─────────────────────────────────────────────
    public function activeCustomers()
    {
        $customers = DB::table('customer_data')
            ->orderBy('Customer_id', 'desc')
            ->paginate(50);

        return view('service.sm.ac', compact('customers'));
    }

    public function updateCustomerType(Request $request)
    {
        DB::table('customer_data')
            ->where('Customer_id', $request->input('cust_id'))
            ->update(['cust_type' => $request->input('cust_type')]);

        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────
    //  UIO  (UIO.php)
    // ─────────────────────────────────────────────
    public function uio()
    {
        $uios = DB::table('uio')->orderBy('UIO_Year')->get();
        return view('service.sm.uio', compact('uios'));
    }

    public function uioUpdate(Request $request)
    {
        $user = Auth::user()->login_id ?? Auth::user()->name;

        DB::table('uio')
            ->where('UIO_Year', $request->input('year'))
            ->update([
                'UIO'       => $request->input('UIO'),
                'datentime' => now(),
                'user'      => $user,
            ]);

        return back()->with('success', 'UIO updated.');
    }

    // ─────────────────────────────────────────────
    //  VIN  (VIN.php / vin_check.php)
    // ─────────────────────────────────────────────
    public function vin()
    {
        $vins = DB::table('vehicles_data')
            ->select('Frame_no')
            ->distinct()
            ->orderBy('Frame_no')
            ->get();

        return view('service.sm.vin', compact('vins'));
    }

    public function vinCheck(Request $request)
    {
        $vin    = $request->input('vin');
        $result = null;

        if ($vin) {
            $result = DB::table('s_vin_check')
                ->where('VIN', $vin)
                ->orWhere('VIN', 'like', "%{$vin}%")
                ->get();
        }

        return view('service.sm.vin-check', compact('vin', 'result'));
    }

    // ─────────────────────────────────────────────
    //  CAMPAIGNS  (campaign.php)
    // ─────────────────────────────────────────────
    public function campaigns()
    {
        $campaigns = DB::table('s_campaigns')->orderBy('campaign_id', 'desc')->get();
        return view('service.sm.campaigns', compact('campaigns'));
    }

    public function campaignStore(Request $request)
    {
        $request->validate([
            'campaign_name' => 'required|unique:s_campaigns,campaign_name',
            'nature'        => 'required',
        ]);

        $user = Auth::user()->login_id ?? Auth::user()->name;

        DB::table('s_campaigns')->insert([
            'campaign_name' => $request->input('campaign_name'),
            'nature'        => $request->input('nature'),
            'c_from'        => $request->input('cfrom'),
            'c_to'          => $request->input('cto'),
            'user'          => $user,
            'datetime'      => now(),
            'status' => 'Active',
            'LC' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Campaign added.');
    }

    public function campaignToggle(Request $request)
    {
        $id     = $request->input('id');
        $status = $request->input('status'); // 'Active' or 'Inactive'

        DB::table('s_campaigns')
            ->where('campaign_id', $id)
            ->update(['status' => $status]);

        return back()->with('success', 'Campaign status updated.');
    }

    // ─────────────────────────────────────────────
    //  CAMPAIGN LABOUR  (compaingh_labour.php)
    // ─────────────────────────────────────────────
    public function campaignLabour($campId)
    {
        $campaign = DB::table('s_campaigns')->where('campaign_id', $campId)->first();
        $labours  = DB::table('s_compaingh_labour')->where('compaingh_id', $campId)->get();
        $laborList = DB::table('labor_list')->orderBy('Labor')->get();



        return view('service.sm.campaign-labour', compact('campaign', 'labours', 'laborList', 'campId'));
    }

    public function campaignLabourStore(Request $request, $campId)
    {
        DB::table('s_compaingh_labour')->insert([
            'compaingh_id'  => $campId,
            'labour_des'    => $request->input('labours'),
            'labour_cost'   => $request->input('labourcost'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Labour added to campaign.');
    }

    public function campaignLabourDelete(Request $request)
    {
        DB::table('s_compaingh_labour')
            ->where('id', $request->input('id'))
            ->delete();

        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────
    //  SMS MANAGEMENT  (SMS_manage.php)
    // ─────────────────────────────────────────────
    public function smsManage()
    {
        $smsList = DB::table('sms_table')->orderBy('id')->get();
        return view('service.sm.sms-manage', compact('smsList'));
    }

    public function smsUpdate(Request $request)
    {
        $user = Auth::user()->login_id ?? Auth::user()->name;

        DB::table('sms_table')
            ->where('id', $request->input('edit_sms_id'))
            ->update([
                'sms_text' => $request->input('message'),
                'edit_by'  => $user,
                'edit_on'  => now(),
            ]);

        return back()->with('success', 'SMS template updated.');
    }

    // ─────────────────────────────────────────────
    //  NEW LABOR REQUEST  (new_labor.php)
    // ─────────────────────────────────────────────
    public function newLabor()
    {
        $requests = DB::table('s_labor_request')
            ->orderBy('when_req', 'desc')
            ->get();

        return view('service.sm.new-labor', compact('requests'));
    }

    public function newLaborStore(Request $request)
    {
        $request->validate(['labor' => 'required']);

        $user = Auth::user()->login_id ?? Auth::user()->name;

        DB::table('s_labor_request')->insert([
            'labor'    => $request->input('labor'),
            'cate1'    => $request->input('cate1'),
            'cate2'    => $request->input('cate2'),
            'cate3'    => $request->input('cate3'),
            'cate4'    => $request->input('cate4'),
            'cate5'    => $request->input('cate5'),
            'remarks'  => $request->input('remarks'),
            'who_req'  => $user,
            'status'   => 'Pending',
            'who_acept' => 'null',
            'when_acept' => now(),
            'when_req' => now(),
        ]);

        return back()->with('success', 'Labor request submitted.');
    }

    // ─────────────────────────────────────────────
    //  NEW VENDOR  (new_vendor.php)
    // ─────────────────────────────────────────────
    public function vendors()
    {
        $vendors = DB::table('s_vendor_list')->orderBy('v_id', 'desc')->get();
        return view('service.sm.vendors', compact('vendors'));
    }

    public function vendorStore(Request $request)
    {
        $request->validate(['jobber' => 'required']);
        $user = Auth::user()->login_id ?? Auth::user()->name;

        DB::table('s_vendor_list')->insert([
            'vendor_name'    => $request->input('jobber'),
            'contact_person' => $request->input('contactperson'),
            'contact'        => $request->input('contact'),
            'Location'       => $request->input('address'),
            'work_type'      => $request->input('worktype'),
            'addedby'        => $user,
            'status'         => 'Active',
            'when'           => now(),
        ]);

        return back()->with('success', 'Vendor added.');
    }

    public function vendorToggle(Request $request)
    {
        $status = $request->input('action') === 'suspend' ? 'Suspended' : 'Active';
        DB::table('s_vendor_list')
            ->where('v_id', $request->input('v_id'))
            ->update(['status' => $status]);

        return back()->with('success', "Vendor {$status}.");
    }

    // ─────────────────────────────────────────────
    //  NEW INSURANCE COMPANY  (new_insurance.php)
    // ─────────────────────────────────────────────
    public function insuranceCompanies()
    {
        $companies = DB::table('s_insurance_companies')->orderBy('c_id', 'desc')->get();
        return view('service.sm.insurance', compact('companies'));
    }

    public function insuranceStore(Request $request)
    {
        $request->validate(['jobber' => 'required']);
        $user = Auth::user()->login_id ?? Auth::user()->name;

        DB::table('s_insurance_companies')->insert([
            'company_name'   => $request->input('jobber'),
            'contact'        => $request->input('contact'),
            'email'          => $request->input('email'),
            'contact_person' => $request->input('contactperson'),
            'ntn'            => $request->input('ntn'),
            'Location'       => $request->input('address'),
            'addedby'        => $user,
            'status'         => 'Active',
            'Surveyors_names' => 'null',
            'when'           => now(),
        ]);

        return back()->with('success', 'Insurance company added.');
    }

    public function insuranceToggle(Request $request)
    {
        $status = $request->input('action') === 'suspend' ? 'Suspended' : 'Active';
        DB::table('s_insurance_companies')
            ->where('c_id', $request->input('c_id'))
            ->update(['status' => $status]);

        return back()->with('success', "Company {$status}.");
    }

    // ─────────────────────────────────────────────
    //  NEW USER  (new_user.php)
    // ─────────────────────────────────────────────
    public function newUser()
    {
        return view('service.sm.new-user');
    }

    public function newUserStore(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'login_id'  => 'required|unique:users,login_id',
            'password2' => 'required|min:6',
            'position'  => 'required',
        ]);

        $depPos     = explode('-', $request->input('position'));
        $department = $depPos[0] ?? '';
        $position   = $depPos[1] ?? '';

        $imageName = null;
        if ($request->hasFile('fileup')) {
            $file      = $request->file('fileup');
            $imageName = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/profile'), $imageName);
        }

        DB::table('users')->insert([
            'name'       => strtoupper($request->input('name')),
            'login_id'   => $request->input('login_id'),
            'password'   => Hash::make($request->input('password2')),
            'email'      => $request->input('email'),
            'mobile'      => $request->input('phone'),
            'dept' => $department,
            'last_login' => now(),
            'last_logout' => now(),
            'position'   => $position,
            'image'      => $imageName,
            'created_at' => now(),
        ]);

        return back()->with('success', 'User created successfully.');
    }

    // ─────────────────────────────────────────────
    //  PROBLEM BOX  (problem_box.php)
    // ─────────────────────────────────────────────
    public function problemBox(Request $request)
    {
        // Handle action on a problem item
        if ($request->isMethod('post') && $request->filled('action')) {
            $laborId = $request->input('labor_id');
            $action  = $request->input('action');

            DB::table('jobc_labor')
                ->where('Labor_id', $laborId)
                ->update(['status' => $action]);

            return back()->with('success', 'Status updated.');
        }

        // Jobs that are stopped or have problem status
        $problems = DB::table('jobc_labor')
            ->join('jobcard', 'jobc_labor.RO_no', '=', 'jobcard.Jobc_id')
            ->where('jobcard.status', '<', 2)
            ->whereIn('jobc_labor.status', ['Job Stopage', 'Job Not Done'])
            ->select('jobc_labor.*', 'jobcard.Registration', 'jobcard.Variant', 'jobcard.SA')
            ->orderBy('jobc_labor.Labor_id', 'desc')
            ->get();

        return view('service.sm.problem-box', compact('problems'));
    }

    // ─────────────────────────────────────────────
    //  UPLOAD FRAME LIST  (upload_fram.php)
    // ─────────────────────────────────────────────
    public function uploadFrame()
    {
        $frames = DB::table('s_frame_list')->orderBy('id', 'desc')->take(100)->get();
        return view('service.sm.upload-frame', compact('frames'));
    }

    public function uploadFrameStore(Request $request)
    {
        $request->validate(['frame_no' => 'required']);
        $user = Auth::user()->login_id ?? Auth::user()->name;

        DB::table('s_frame_list')->insert([
            'frame_no'  => strtoupper($request->input('frame_no')),
            'added_by'  => $user,
            'added_on'  => now(),
        ]);

        return back()->with('success', 'Frame number added.');
    }

    // ─────────────────────────────────────────────
    //  REPORTS  (reports.php)
    // ─────────────────────────────────────────────
    public function reports(Request $request)
    {
        $tab      = $request->input('tab', 'summary');
        $fromDate = $request->input('from_date', now()->startOfMonth()->toDateString());
        $toDate   = $request->input('to_date', now()->toDateString());

        $data = [];

        switch ($tab) {
            case 'summary':
                $data = DB::table('jobcard')
                    ->selectRaw('SA, COUNT(*) as total, SUM(CASE WHEN status=2 THEN 1 ELSE 0 END) as closed')
                    ->whereBetween(DB::raw('DATE(Open_date_time)'), [$fromDate, $toDate])
                    ->groupBy('SA')
                    ->get();
                break;

            case 'labor':
                $data = DB::table('jobc_labor')
                    ->join('jobcard', 'jobc_labor.RO_no', '=', 'jobcard.Jobc_id')
                    ->selectRaw('jobc_labor.Labor, jobc_labor.type, COUNT(*) as count, SUM(jobc_labor.cost) as total')
                    ->whereBetween(DB::raw('DATE(jobcard.Open_date_time)'), [$fromDate, $toDate])
                    ->groupBy('jobc_labor.Labor', 'jobc_labor.type')
                    ->orderByDesc('count')
                    ->get();
                break;

            case 'parts':
                $data = DB::table('jobc_parts')
                    ->join('jobcard', 'jobc_parts.RO_no', '=', 'jobcard.Jobc_id')
                    ->selectRaw('jobc_parts.part_description, SUM(jobc_parts.qty) as total_qty, SUM(jobc_parts.total) as total_value')
                    ->whereBetween(DB::raw('DATE(jobcard.Open_date_time)'), [$fromDate, $toDate])
                    ->groupBy('jobc_parts.part_description')
                    ->orderByDesc('total_value')
                    ->get();
                break;

            case 'sa':
                $data = DB::table('jobcard')
                    ->selectRaw('SA, COUNT(*) as total_ros,
                        SUM(CASE WHEN RO_type="Regular" THEN 1 ELSE 0 END) as regular,
                        SUM(CASE WHEN RO_type="Campaign" THEN 1 ELSE 0 END) as campaign,
                        SUM(CASE WHEN RO_type="Warranty" THEN 1 ELSE 0 END) as warranty')
                    ->whereBetween(DB::raw('DATE(Open_date_time)'), [$fromDate, $toDate])
                    ->groupBy('SA')
                    ->get();
                break;
        }

        return view('service.sm.reports', compact('tab', 'fromDate', 'toDate', 'data'));
    }

    // ─────────────────────────────────────────────
    //  MASTER DATA: BAYS
    // ─────────────────────────────────────────────
    public function masterBays()
    {
        $bays = DB::table('s_bays')->orderBy('id')->get();
        return view('service.sm.master.bays', compact('bays'));
    }

    public function masterBaysStore(Request $request)
    {
        $request->validate(['bay_name' => 'required']);
        DB::table('s_bays')->insert([
            'bay_name' => $request->input('bay_name'),
            'category' => $request->input('category'),
            'bay_type' => $request->input('bay_type'),
            'status' => 1,
            'selection' => 1,
        ]);
        return back()->with('success', 'Bay added.');
    }

    public function masterBaysUpdate(Request $request)
    {
        DB::table('s_bays')->where('id', $request->input('id'))->update([
            'bay_name' => $request->input('bay_name'),
            'category' => $request->input('category'),
            'bay_type' => $request->input('bay_type'),
        ]);
        return back()->with('success', 'Bay updated.');
    }

    public function masterBaysDelete(Request $request)
    {
        DB::table('s_bays')->where('id', $request->input('id'))->delete();
        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────
    //  MASTER DATA: LABOR LIST
    // ─────────────────────────────────────────────
    public function masterLabor()
    {
        $laborList = DB::table('labor_list')->orderBy('Labor_ID')->get();
        return view('service.sm.master.labor-list', compact('laborList'));
    }

    public function masterLaborStore(Request $request)
    {
        $request->validate(['Labor' => 'required']);
        DB::table('labor_list')->insert([
            'Labor' => $request->input('Labor'),
            'Cate1' => $request->input('Cate1'),
            'Cate2' => $request->input('Cate2'),
            'Cate3' => $request->input('Cate3'),
            'Cate4' => $request->input('Cate4'),
            'Cate5' => $request->input('Cate5'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Labor added.');
    }

    public function masterLaborUpdate(Request $request)
    {
        DB::table('labor_list')->where('Labor_ID', $request->input('Labor_ID'))->update([
            'Labor' => $request->input('Labor'),
            'Cate1' => $request->input('Cate1'),
            'Cate2' => $request->input('Cate2'),
            'Cate3' => $request->input('Cate3'),
            'Cate4' => $request->input('Cate4'),
            'Cate5' => $request->input('Cate5'),
        ]);
        return back()->with('success', 'Labor updated.');
    }

    public function masterLaborDelete(Request $request)
    {
        DB::table('labor_list')->where('Labor_ID', $request->input('id'))->delete();
        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────
    //  MASTER DATA: TECH TEAMS
    // ─────────────────────────────────────────────
    public function masterTeams()
    {
        $teams = DB::table('s_techteams')->orderBy('team_id')->get();
        return view('service.sm.master.teams', compact('teams'));
    }

    public function masterTeamsStore(Request $request)
    {
        $request->validate(['team_name' => 'required']);
        DB::table('s_techteams')->insert([
            'team_name' => $request->input('team_name'),
            'members'   => $request->input('members'),
            'category'  => $request->input('category'),
            'status'    => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Team added.');
    }

    public function masterTeamsUpdate(Request $request)
    {
        DB::table('s_techteams')->where('team_id', $request->input('team_id'))->update([
            'team_name' => $request->input('team_name'),
            'members'   => $request->input('members'),
            'category'  => $request->input('category'),
        ]);
        return back()->with('success', 'Team updated.');
    }

    public function masterTeamsDelete(Request $request)
    {
        DB::table('s_techteams')->where('team_id', $request->input('id'))->delete();
        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────
    //  MASTER DATA: VARIANT CODES
    // ─────────────────────────────────────────────
    public function masterVariants()
    {
        $variants = DB::table('variant_codes')->orderBy('variant_id')->get();
        return view('service.sm.master.variants', compact('variants'));
    }

    public function masterVariantsStore(Request $request)
    {
        $request->validate(['Variant' => 'required']);
        DB::table('variant_codes')->insert([
            'Variant'  => $request->input('Variant'),
            'Model'    => $request->input('Model'),
            'Make'     => $request->input('Make'),
            'Fram'     => $request->input('Fram'),
            'Engine'   => $request->input('Engine'),
            'Category' => $request->input('Category'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return back()->with('success', 'Variant added.');
    }

    public function masterVariantsUpdate(Request $request)
    {
        DB::table('variant_codes')->where('variant_id', $request->input('variant_id'))->update([
            'Variant'  => $request->input('Variant'),
            'Model'    => $request->input('Model'),
            'Make'     => $request->input('Make'),
            'Fram'     => $request->input('Fram'),
            'Engine'   => $request->input('Engine'),
            'Category' => $request->input('Category'),
        ]);
        return back()->with('success', 'Variant updated.');
    }

    public function masterVariantsDelete(Request $request)
    {
        DB::table('variant_codes')->where('variant_id', $request->input('id'))->delete();
        return response()->json(['status' => 'ok']);
    }
}
