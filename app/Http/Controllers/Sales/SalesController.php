<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    // ─── DASHBOARD — redirects to CRM Reminder (main operational page) ──────
    public function index()
    {
        return redirect()->route('sales.crm-reminder');
    }

    // ─── SEARCH ──────────────────────────────────────────────────
    public function search(Request $request)
    {
        $results = collect();
        $type    = $request->input('type');
        $query   = $request->input('q', '');

        if ($query && $type) {
            if ($type === 'customer') {
                $results = DB::table('customer_data')
                    ->where('Customer_name', 'LIKE', "%$query%")
                    ->orWhere('mobile', 'LIKE', "%$query%")
                    ->orWhere('CNIC', 'LIKE', "%$query%")
                    ->orderBy('Customer_id', 'desc')
                    ->limit(20)
                    ->get();
            } elseif ($type === 'vehicle') {
                $results = DB::table('vehicles_data')
                    ->where('Registration', 'LIKE', "%$query%")
                    ->orWhere('Frame_no', 'LIKE', "%$query%")
                    ->orWhere('Engine_Code', 'LIKE', "%$query%")
                    ->orderBy('Vehicle_id', 'desc')
                    ->limit(20)
                    ->get();
            } elseif ($type === 'jobcard') {
                $results = DB::table('jobcard')
                    ->where('Jobc_id', 'LIKE', "%$query%")
                    ->orWhere('Veh_reg_no', 'LIKE', "%$query%")
                    ->orWhere('Customer_name', 'LIKE', "%$query%")
                    ->orderBy('Jobc_id', 'desc')
                    ->limit(20)
                    ->get();
            }
        }

        return view('sales.search', compact('results', 'type', 'query'));
    }

    // ─── ACTIVE CUSTOMERS ────────────────────────────────────────
    public function activeCustomers(Request $request)
    {
        $customers = DB::table('customer_data')
            ->orderBy('Customer_id', 'desc')
            ->paginate(50);

        $typeStats = DB::table('customer_data')
            ->selectRaw('cust_type, COUNT(*) AS total')
            ->groupBy('cust_type')
            ->get();

        $cityStats = DB::table('customer_data')
            ->selectRaw('City, COUNT(*) AS total')
            ->groupBy('City')
            ->orderBy('total', 'desc')
            ->get();

        return view('sales.ac', compact('customers', 'typeStats', 'cityStats'));
    }

    public function updateCustomerType(Request $request)
    {
        DB::table('customer_data')
            ->where('Customer_id', $request->cust_id)
            ->update(['cust_type' => $request->cust_type]);

        return response()->json(['status' => 'ok']);
    }

    // ─── VIN / UNIQUE VINs ───────────────────────────────────────
    public function vin()
    {
        $vins = DB::table('vehicles_data')
            ->selectRaw('Make, COUNT(*) AS total')
            ->groupBy('Make')
            ->orderBy('total', 'desc')
            ->get();

        $totalVehicles   = DB::table('vehicles_data')->count();
        $registeredCount = DB::table('vehicles_data')->whereNotNull('Registration')->where('Registration', '!=', '')->count();
        $regRate         = $totalVehicles > 0 ? round($registeredCount / $totalVehicles * 100, 2) : 0;

        return view('sales.vin', compact('vins', 'totalVehicles', 'registeredCount', 'regRate'));
    }

    // ─── VIN CHECK ───────────────────────────────────────────────
    public function vinCheck(Request $request)
    {
        $pending = DB::table('s_vin_check')
            ->join('s_upload_listname', 's_upload_listname.list_id', '=', 's_vin_check.listid')
            ->where('s_vin_check.ActionTaken', 0)
            ->select('s_vin_check.*', 's_upload_listname.list_name')
            ->orderBy('s_vin_check.vin_id', 'desc')
            ->get();

        if ($request->isMethod('post') && $request->frameno) {
            $frameno = $request->frameno;
            DB::table('s_vin_check')
                ->where('frameno', $frameno)
                ->update(['ActionTaken' => 1, 'doneondate' => now()]);

            return back()->with('success', "VIN $frameno marked as done.");
        }

        return view('sales.vin-check', compact('pending'));
    }

    // ─── UIO ─────────────────────────────────────────────────────
    public function uio()
    {
        $uios = DB::table('uio')->orderBy('UIO_Year', 'desc')->get();
        return view('sales.uio', compact('uios'));
    }

    public function uioUpdate(Request $request)
    {
        DB::table('uio')
            ->where('UIO_Year', $request->year)
            ->update(['UIO' => $request->UIO, 'datentime' => now(), 'user' => session('login_id')]);

        return back()->with('success', 'UIO updated.');
    }

    // ─── CAMPAIGNS ───────────────────────────────────────────────
    public function campaigns()
    {
        $campaigns = DB::table('s_campaigns')->orderBy('campaign_id', 'desc')->get();
        return view('sales.campaigns', compact('campaigns'));
    }

    public function campaignStore(Request $request)
    {
        $request->validate(['campaign_name' => 'required']);

        $exists = DB::table('s_campaigns')->where('campaign_name', $request->campaign_name)->exists();
        if ($exists) return back()->with('error', 'Campaign name already exists.');

        DB::table('s_campaigns')->insert([
            'campaign_name' => $request->campaign_name,
            'nature'        => $request->nature,
            'c_from'        => $request->cfrom,
            'c_to'          => $request->cto,
            'user'          => session('login_id'),
            'datetime'      => now(),
        ]);

        return back()->with('success', 'Campaign added.');
    }

    public function campaignToggle(Request $request)
    {
        $newStatus = $request->status;
        DB::table('s_campaigns')
            ->where('campaign_id', $request->id)
            ->update(['status' => $newStatus]);

        return back()->with('success', "Campaign $newStatus.");
    }

    // ─── PROBLEM TRAY ────────────────────────────────────────────
    public function problemTray()
    {
        $problems = DB::table('cr_problem_tray')
            ->where('Completed', '')
            ->orderBy('p_id', 'desc')
            ->get();

        return view('sales.problem-tray', compact('problems'));
    }

    public function problemTrayAction(Request $request)
    {
        $action = $request->action;
        $id     = $request->problem_id;
        $user   = session('login_id');

        if ($action === 'forward') {
            DB::table('cr_problem_tray')->where('p_id', $id)->update([
                'ActionTaken'     => 'Forward to Service Dept',
                'ActionCompleted' => now(),
                'messageforsa'    => $request->message ?? '',
            ]);
        } elseif ($action === 'terminate') {
            DB::table('cr_problem_tray')->where('p_id', $id)->update([
                'ActionTaken'     => 'Terminated',
                'ActionCompleted' => 'Terminated',
                'Completed'       => 'Terminated',
            ]);
        }

        return back()->with('success', 'Action applied.');
    }

    // ─── UPLOAD VINs ─────────────────────────────────────────────
    public function uploadVin()
    {
        $lists = DB::table('s_upload_listname')->orderBy('list_id', 'desc')->get();
        return view('sales.upload-vin', compact('lists'));
    }

    public function uploadVinStore(Request $request)
    {
        $request->validate(['listname' => 'required', 'vins' => 'required']);

        $listId = DB::table('s_upload_listname')->insertGetId([
            'list_name'   => $request->listname,
            'user'        => session('login_id'),
            'upload_date' => now(),
        ]);

        $lines = preg_split('/\r\n|\r|\n/', trim($request->vins));
        foreach ($lines as $line) {
            $line = trim($line);
            if (!$line) continue;
            $parts     = explode(',', $line, 2);
            $vin       = strtoupper(trim($parts[0]));
            $secondary = trim($parts[1] ?? '');
            $last7     = substr($vin, -7);

            DB::table('s_uploaded_frams')->insert([
                'uploaded_id'    => $listId,
                'VIN'            => $last7,
                'full_VIN'       => $vin,
                'secondary_info' => $secondary,
                'uploaded_date'  => now(),
            ]);
        }

        return back()->with('success', 'VINs uploaded successfully.');
    }

    // ─── REPORTS ─────────────────────────────────────────────────
    public function reports()
    {
        return view('sales.reports');
    }

    public function reportsNew()
    {
        return view('sales.reports-new');
    }

    // ─── JOBCARDS (unclose list) ──────────────────────────────────
    public function jobcards()
    {
        $jobcards = DB::table('jobcard')
            ->where('status', '<', 3)
            ->selectRaw("*, DATE_FORMAT(Open_date_time,' %d %b %h:%i %p') AS bookingtime")
            ->orderBy('Jobc_id', 'desc')
            ->get();

        foreach ($jobcards as $jc) {
            $jc->labors     = DB::table('jobc_labor')->where('RO_no', $jc->Jobc_id)->get();
            $jc->openLabors = DB::table('jobc_labor')
                ->where('RO_no', $jc->Jobc_id)
                ->where('type', 'Workshop')
                ->whereNotIn('status', ['Job Not Done', 'Jobclose'])
                ->count();
        }

        return view('sales.jobcards', compact('jobcards'));
    }

    // ─── JC CHANGES ──────────────────────────────────────────────
    public function jcChanges(Request $request)
    {
        $jobId  = $request->input('jobc_id');
        $jobcard = null;
        $changes = collect();

        if ($jobId) {
            $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
            if ($jobcard) {
                // Update mileage if posted
                if ($request->isMethod('post') && $request->mileage) {
                    if ($jobcard->status < 2) {
                        DB::table('jobcard')->where('Jobc_id', $jobId)
                            ->update(['Mileage' => $request->mileage]);
                    }
                }
                $changes = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
            }
        }

        return view('sales.jc-changes', compact('jobId', 'jobcard', 'changes'));
    }

    // ─── STATUS PAGES ────────────────────────────────────────────
    public function statusLabor()
    {
        $jobcards = DB::table('jobcard')->where('status', 1)->orderBy('Jobc_id', 'desc')->get();
        $laborData = [];
        foreach ($jobcards as $jc) {
            $laborData[$jc->Jobc_id] = DB::table('jobc_labor')->where('RO_no', $jc->Jobc_id)->get();
        }
        return view('sales.status-labor', compact('jobcards', 'laborData'));
    }

    public function statusParts()
    {
        $jobcards = DB::table('jobcard')
            ->where('status', 1)
            ->where('SA', session('login_id'))
            ->orderBy('Jobc_id', 'desc')->get();
        $partsData = [];
        foreach ($jobcards as $jc) {
            $partsData[$jc->Jobc_id] = DB::table('jobc_parts')->where('RO_no', $jc->Jobc_id)->get();
        }
        return view('sales.status-parts', compact('jobcards', 'partsData'));
    }

    public function statusSublet()
    {
        $jobcards = DB::table('jobcard')->where('status', 1)->orderBy('Jobc_id', 'desc')->get();
        $subletData = [];
        foreach ($jobcards as $jc) {
            $subletData[$jc->Jobc_id] = DB::table('jobc_sublet')->where('RO_no', $jc->Jobc_id)->get();
        }
        return view('sales.status-sublet', compact('jobcards', 'subletData'));
    }

    public function statusConsumable()
    {
        $jobcards = DB::table('jobcard')->where('status', 1)->orderBy('Jobc_id', 'desc')->get();
        $consData = [];
        foreach ($jobcards as $jc) {
            $consData[$jc->Jobc_id] = DB::table('jobc_consumble')->where('RO_no', $jc->Jobc_id)->get();
        }
        return view('sales.status-consumable', compact('jobcards', 'consData'));
    }

    // ─── CRM: FOLLOW-UP REMINDER ────────────────────────────────────────────────
    // Main CRM operational page (also serves as index redirect target).
    // Supports date-range filter (default last 60 days).
    public function followUpReminder(Request $request)
    {
        // Date range — default last 60 days
        $dateFrom = $request->input('date_from', now()->subDays(60)->toDateString());
        $dateTo   = $request->input('date_to',   now()->toDateString());

        // Recently completed jobcards in date range (status >= 3)
        $recentJobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('jc.status', '>=', 3)
            ->whereDate('jc.Open_date_time', '>=', $dateFrom)
            ->whereDate('jc.Open_date_time', '<=', $dateTo)
            ->select(
                'jc.Jobc_id', 'jc.Open_date_time', 'jc.SA', 'jc.RO_type',
                'v.Registration', 'v.Variant', 'v.Make',
                'c.Customer_name', 'c.mobile', 'c.Customer_id'
            )
            ->orderByDesc('jc.Jobc_id')
            ->get();

        $jobIds = $recentJobs->pluck('Jobc_id')->toArray();

        // Consumable counts per jobcard
        $consumableCounts = DB::table('jobc_consumble')
            ->whereIn('RO_no', $jobIds)
            ->selectRaw('RO_no, COUNT(*) as cnt, SUM(total) as total_amount')
            ->groupBy('RO_no')
            ->get()
            ->keyBy('RO_no');

        // Parts counts per jobcard (for overview tooltip)
        $partsCounts = DB::table('jobc_parts')
            ->whereIn('RO_no', $jobIds)
            ->selectRaw('RO_no, COUNT(*) as cnt, SUM(total) as total_amount')
            ->groupBy('RO_no')
            ->get()
            ->keyBy('RO_no');

        // Annotate each job
        $jobs = $recentJobs->map(function ($job) use ($consumableCounts, $partsCounts) {
            $job->had_consumable   = isset($consumableCounts[$job->Jobc_id]);
            $job->consumable_count = $consumableCounts[$job->Jobc_id]->cnt ?? 0;
            $job->consumable_total = $consumableCounts[$job->Jobc_id]->total_amount ?? 0;
            $job->parts_count      = $partsCounts[$job->Jobc_id]->cnt ?? 0;
            $job->parts_total      = $partsCounts[$job->Jobc_id]->total_amount ?? 0;
            return $job;
        });

        $allJobs        = $jobs;
        $consumableJobs = $jobs->filter(fn($j) => $j->had_consumable)->values();

        // Call logs grouped by jobc_id
        $callLogsRaw = DB::table('crm_call_logs')
            ->whereIn('jobc_id', $jobIds)
            ->orderByDesc('called_at')
            ->get();

        $callLogs    = $callLogsRaw->groupBy('jobc_id');
        $callLogsAll = $callLogsRaw->values();

        // Due today / overdue
        $dueToday = DB::table('crm_call_logs')
            ->whereNotNull('next_followup_date')
            ->where('next_followup_date', '<=', now()->toDateString())
            ->orderBy('next_followup_date')
            ->get()
            ->unique('jobc_id')
            ->values();

        // Recent call history
        $recentLogs = DB::table('crm_call_logs')
            ->orderByDesc('called_at')
            ->limit(200)
            ->get();

        // ── New Cars Delivered tab (sv_delivery_orders status=Delivered) ─────
        $deliveredOrders = DB::table('sv_delivery_orders as do')
            ->join('sv_vehicles as v', 'do.vehicle_id', '=', 'v.id')
            ->whereDate('do.delivery_date', '>=', $dateFrom)
            ->whereDate('do.delivery_date', '<=', $dateTo)
            ->select(
                'do.id', 'do.do_no', 'do.customer_name', 'do.customer_phone',
                'do.delivery_date', 'do.payment_type', 'do.customer_paid_amount',
                'do.status', 'do.pbo_no',
                'v.model', 'v.variant', 'v.color', 'v.vin', 'v.model_year'
            )
            ->orderByDesc('do.delivery_date')
            ->get();

        // CRM call logs for delivery orders (keyed by do_no used as reference)
        $doIds       = $deliveredOrders->pluck('id')->toArray();
        $doCallLogs  = DB::table('crm_call_logs')
            ->whereIn('jobc_id', $doIds)
            ->where('call_type', 'NVD')    // NVD = New Vehicle Delivery follow-up type
            ->orderByDesc('called_at')
            ->get()
            ->groupBy('jobc_id');

        return view('sales.crm-reminder', compact(
            'allJobs', 'consumableJobs', 'callLogs', 'callLogsAll',
            'dueToday', 'recentLogs',
            'deliveredOrders', 'doCallLogs',
            'dateFrom', 'dateTo'
        ));
    }

    // ─────────────────────────────────────────────
    //  CRM — LOG A CALL
    // ─────────────────────────────────────────────
    public function crmLogCall(Request $request)
    {
        $request->validate([
            'jobc_id'    => 'required|integer',
            'call_type'  => 'required|in:FFS,PSFU,ASFU,CSF,CFU,NVD',
            'call_status'=> 'required|in:Contacted,Not Reachable,Callback Requested,Voicemail,Wrong Number',
            'called_at'  => 'required|date',
        ]);

        // Fetch customer info from the jobcard for denormalized storage
        $jobInfo = DB::table('jobcard as jc')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.Jobc_id', $request->jobc_id)
            ->select('c.Customer_name', 'c.mobile', 'c.Customer_id', 'v.Registration')
            ->first();

        DB::table('crm_call_logs')->insert([
            'jobc_id'           => $request->jobc_id,
            'customer_id'       => $jobInfo?->Customer_id,
            'customer_name'     => $jobInfo?->Customer_name,
            'mobile'            => $jobInfo?->mobile,
            'registration'      => $jobInfo?->Registration,
            'call_type'         => $request->call_type,
            'call_status'       => $request->call_status,
            'remarks'           => $request->remarks,
            'next_followup_date'=> $request->next_followup_date ?: null,
            'called_at'         => $request->called_at,
            'called_by'         => Auth::user()->login_id ?? Auth::user()->name ?? 'System',
            'created_at'        => now(),
            'updated_at'        => now(),
        ]);

        return redirect()->route('sales.crm-reminder')->with('crm_success', 'Call logged successfully.');
    }
    // ─────────────────────────────────────────────
    //  PARTS FILTER — Filter cars by recent parts used
    // ─────────────────────────────────────────────
    public function partsFilter(Request $request)
    {
        $part     = $request->input('part');
        $dateFrom = $request->input('date_from');
        $dateTo   = $request->input('date_to');

        $results = null;

        if ($part || $dateFrom || $dateTo) {
            $query = DB::table('jobc_parts as jp')
                ->join('jobcard as jc', 'jp.RO_no', '=', 'jc.Jobc_id')
                ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
                ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
                ->select(
                    'jc.Jobc_id',
                    'jc.Open_date_time as job_date',
                    'v.Registration',
                    'v.Make',
                    'v.Variant',
                    'c.Customer_name',
                    'c.mobile',
                    'jp.part_description',
                    'jp.qty',
                    'jp.total'
                );

            if ($part) {
                $query->where('jp.part_description', 'LIKE', '%' . $part . '%');
            }

            if ($dateFrom) {
                $query->whereDate('jc.Open_date_time', '>=', $dateFrom);
            }

            if ($dateTo) {
                $query->whereDate('jc.Open_date_time', '<=', $dateTo);
            }

            $results = $query->orderByDesc('jc.Open_date_time')->get();
        }

        return view('sales.parts-filter', compact('results', 'part', 'dateFrom', 'dateTo'));
    }

}
