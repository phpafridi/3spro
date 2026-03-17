<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SalesController extends Controller
{
    // ─── DASHBOARD ───────────────────────────────────────────────
    public function index()
    {
        // Today's labor/parts revenue by RO type
        $todayRevenue = DB::table('jobc_invoice')
            ->join('jobcard', 'jobc_invoice.Jobc_id', '=', 'jobcard.Jobc_id')
            ->whereDate('jobc_invoice.datetime', today())
            ->selectRaw('jobcard.ro_type, SUM(jobc_invoice.Lnet) AS Labor, SUM(jobc_invoice.Pnet+jobc_invoice.Cnet) AS Parts')
            ->groupBy('jobcard.ro_type')
            ->get();

        // Last 20 days chart data
        $chartData = DB::table('jobc_invoice')
            ->where('datetime', '>=', now()->subDays(20))
            ->selectRaw("DATE(datetime) AS day_date, DATE_FORMAT(MIN(datetime),'%d %b') AS day, SUM(Lnet+Snet) AS labor, SUM(Pnet+Cnet) AS parts, COUNT(*) AS ros")
            ->groupByRaw('DATE(datetime)')
            ->orderByRaw('DATE(datetime)')
            ->get();

        // Customer ratings avg
        $ratings = DB::table('customer_ratings')
            ->selectRaw('AVG(Management) AS management, AVG(Services) AS services, AVG(prices) AS prices, AVG(cleanance) AS cleanance')
            ->first();

        // Unclosed jobcards count
        $unclosedCount = DB::table('jobcard')->where('status', '<', 3)->count();

        // Pending problems
        $pendingProblems = DB::table('cr_problem_tray')
            ->where('ActionTaken', 'Forward to Service Dept')
            ->where('Completed', '')->count();

        // VIN check pending
        $pendingVin = DB::table('s_vin_check')->where('ActionTaken', 0)->count();

        return view('sales.index', compact(
            'todayRevenue', 'chartData', 'ratings',
            'unclosedCount', 'pendingProblems', 'pendingVin'
        ));
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
}
