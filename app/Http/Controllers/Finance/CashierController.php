<?php
namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    /**
     * Display pending invoices (index page)
     */
    public function index()
    {
        $pendingInvoices = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('jc.status', '2')
            ->select(
                'jc.Jobc_id',
                'jc.Customer_name',
                'jc.SA',
                'jc.closing_time',
                'jc.MSI_cat',
                'v.Variant',
                'v.Registration',
                'c.mobile'
            )
            ->orderBy('jc.Jobc_id', 'desc')
            ->get();

        return view('finance.cashier.index', compact('pendingInvoices'));
    }

    /**
     * Search page
     */
    public function search()
    {
        return view('finance.cashier.search');
    }

    /**
     * Parts return page
     */
    public function partsReturn(Request $request)
    {
        $results = null;
        $search = $request->search;
        $field = $request->field;

        if ($search && $field) {
            $results = $this->searchParts($search, $field);
        }

        return view('finance.cashier.parts_return', compact('results', 'search', 'field'));
    }

    /**
     * Process parts return
     */
    public function processReturn(Request $request)
    {
        $request->validate([
            'table' => 'required',
            'tfield' => 'required',
            'sid' => 'required'
        ]);

        DB::table($request->table)
            ->where($request->tfield, $request->sid)
            ->update(['p_return' => '1']);

        return redirect()->back()->with('success', 'Return processed successfully!');
    }

    /**
     * Print initial RO list
     */
    public function printInitialRO()
    {
        $openJobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('jc.status', '1')
            ->select(
                'jc.Jobc_id',
                'jc.Open_date_time',
                'jc.SA',
                'v.Variant',
                'v.Registration',
                'c.Customer_name',
                'c.mobile'
            )
            ->orderBy('jc.Jobc_id', 'desc')
            ->get();

        return view('finance.cashier.print_open_ro', compact('openJobs'));
    }

    /**
     * Print close RO list
     */
    public function printCloseRO()
    {
        $closedJobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('jc.status', '3')
            ->select(
                'jc.Jobc_id',
                'jc.closing_time',
                'jc.SA',
                'v.Variant',
                'v.Registration',
                'c.Customer_name',
                'c.mobile'
            )
            ->orderBy('jc.Jobc_id', 'desc')
            ->get();

        return view('finance.cashier.print_close_ro', compact('closedJobs'));
    }

    /**
     * Reports page
     */
    public function reports()
    {
        return view('finance.cashier.reports');
    }

    /**
     * Generate invoice form
     */
    public function invoice(Request $request)
    {
        $jobId = $request->job_id;

        $job = DB::table('jobcard')
            ->where('Jobc_id', $jobId)
            ->first();

        if (!$job) {
            return redirect()->route('cashier.index')->with('error', 'Job not found');
        }

        $vehicle = DB::table('vehicles_data')
            ->where('Vehicle_id', $job->Vehicle_id)
            ->first();

        $customer = DB::table('customer_data')
            ->where('Customer_id', $job->Customer_id)
            ->first();

        // Get totals
        $laborTotal = DB::table('jobc_labor')
            ->where('RO_no', $jobId)
            ->where('status', 'Jobclose')
            ->sum('cost') ?? 0;

        $partsTotal = DB::table('jobc_parts')
            ->where('RO_no', $jobId)
            ->whereIn('status', ['1', '3'])
            ->sum('total') ?? 0;

        $subletTotal = DB::table('jobc_sublet')
            ->where('RO_no', $jobId)
            ->where('status', 'JobDone')
            ->sum('total') ?? 0;

        $consumableTotal = DB::table('jobc_consumble')
            ->where('RO_no', $jobId)
            ->whereIn('status', ['1', '3'])
            ->sum('total') ?? 0;

        $grandTotal = $laborTotal + $partsTotal + $subletTotal + $consumableTotal;

        // Get recovery accounts for DM option
        $recoveryAccounts = DB::table('recov_accounts')
            ->select('Name')
            ->get();

        return view('finance.cashier.invoice', compact(
            'jobId', 'job', 'vehicle', 'customer',
            'laborTotal', 'partsTotal', 'subletTotal', 'consumableTotal',
            'grandTotal', 'recoveryAccounts'
        ));
    }

    /**
     * Process and save invoice
     */
    public function saveInvoice(Request $request)
    {
        $data = $request->all();

        // Check if invoice already exists
        $exists = DB::table('jobc_invoice')
            ->where('Jobc_id', $data['ro_no'])
            ->exists();

        if ($exists) {
            return redirect()->route('cashier.print-invoice', ['id' => $data['ro_no']])
                ->with('error', 'Invoice already exists!');
        }

        // Get old invoice time if unclosed
        $oldTime = DB::table('s_unclosed_jc')
            ->where('jobc_id', $data['ro_no'])
            ->orderBy('unjc_Id', 'desc')
            ->value('old_inv_datime');

        $dateTime = ($oldTime && $oldTime != '0000-00-00 00:00:00')
            ? $oldTime
            : now();

        $invoiceData = [
            'Jobc_id' => $data['ro_no'],
            'Labor' => $data['Labor'],
            'Parts' => $data['parts'],
            'Sublet' => $data['sublet'],
            'Consumble' => $data['consumble'],
            'Ltax' => $data['ltaxamount'] ?? 0,
            'Ptax' => $data['ptaxamount'] ?? 0,
            'Stax' => $data['staxamount'] ?? 0,
            'Ctax' => $data['ctaxamount'] ?? 0,
            'Ldiscount' => $data['L_discount'] ?? 0,
            'Pdiscount' => $data['pdiscount'] ?? 0,
            'Sdiscount' => $data['sdiscount'] ?? 0,
            'Cdiscount' => $data['cdiscount'] ?? 0,
            'Lnet' => $data['l_nettotal'],
            'Pnet' => $data['pnettotal'],
            'Snet' => $data['snettotal'],
            'Cnet' => $data['cnettotal'],
            'Total' => $data['grandtotal'],
            'type' => $data['radiob'],
            'careof' => $data['careoff'] ?? null,
            'cashier' => Auth::user()->login_id,
            'created_at' => now(),
            'updated_at' => now(),
            'Rec_status' => '',
            'datetime' => $dateTime
        ];

        DB::table('jobc_invoice')->insert($invoiceData);

        // Update jobcard status to closed (3)
        DB::table('jobcard')
            ->where('Jobc_id', $data['ro_no'])
            ->update(['status' => '3']);

        // ─── AUTO-OPEN RECOVERY ACCOUNT WHEN INVOICE TYPE IS DM (Debit Memo) ────
        // DM = Debit Memo means the amount is NOT paid by the customer at the counter.
        // The cashier is essentially deferring payment, so we must automatically
        // register this in the Recovery module so it can be tracked and collected.
        if (isset($data['radiob']) && $data['radiob'] === 'DM') {

            // Fetch job, vehicle, and customer details for the recovery record
            $jobDetails = DB::table('jobcard as jc')
                ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
                ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
                ->where('jc.Jobc_id', $data['ro_no'])
                ->select(
                    'c.Customer_name', 'c.mobile', 'c.Customer_id',
                    'v.Registration', 'v.Variant', 'v.Make',
                    'jc.closing_time'
                )
                ->first();

            if ($jobDetails) {
                $custName    = $jobDetails->Customer_name;
                $contact     = $jobDetails->mobile ?? '';
                $vehicleName = trim($jobDetails->Make . ' ' . $jobDetails->Variant);
                $registration = $jobDetails->Registration ?? '';
                $careOf      = $data['careoff'] ?? $custName;
                $debtAmount  = (int) $data['grandtotal'];

                // 1. Auto-create recov_accounts entry if not already present
                $accountExists = DB::table('recov_accounts')
                    ->where('Name', $custName)
                    ->exists();

                if (!$accountExists) {
                    DB::table('recov_accounts')->insert([
                        'Name'            => $custName,
                        'Occopation'      => '',
                        'Primary_contact' => (int) preg_replace('/\D/', '', $contact) ?: 0,
                        'Sec_contact'     => 0,
                        'email'           => '',
                        'amount_limit'    => $debtAmount,
                        'r_officer'       => Auth::user()->login_id ?? '',
                        'datetime'        => now(),
                        'status'          => 'Active',
                        'created_at'      => now(),
                        'updated_at'      => now(),
                    ]);
                }

                // 2. Insert debit entry in recov_debt
                DB::table('recov_debt')->insert([
                    'cust_name'    => $custName,
                    'contact'      => $contact,
                    'Vehicle_name' => $vehicleName,
                    'Registration' => $registration,
                    'Invoice_no'   => (int) $data['ro_no'],
                    'Db_date'      => now()->toDateString(),
                    'Debt_amount'  => $debtAmount,
                    'Remarks'      => 'Auto-created from DM invoice #' . $data['ro_no'] . ($careOf !== $custName ? ' — Care of: ' . $careOf : ''),
                    'user'         => Auth::user()->login_id ?? 'cashier',
                    'entytime'     => now(),
                ]);

                // 3. Mark invoice Rec_status as pending recovery (empty string = pending)
                DB::table('jobc_invoice')
                    ->where('Jobc_id', $data['ro_no'])
                    ->update(['Rec_status' => '']);
            }
        }

        return redirect()->route('cashier.print-invoice', ['id' => $data['ro_no']]);
    }

    /**
     * Print invoice
     */
    public function printInvoice($id)
    {
        $invoice = DB::table('jobc_invoice')
            ->where('Jobc_id', $id)
            ->first();



        if (!$invoice) {

            // If no invoice, show print initial RO
            return redirect()->route('cashier.print-initial-ro', ['job_id' => $id]);
        }

        $job = DB::table('jobcard')->where('Jobc_id', $id)->first();
        $vehicle = DB::table('vehicles_data')->where('Vehicle_id', $job->Vehicle_id)->first();
        $customer = DB::table('customer_data')->where('Customer_id', $job->Customer_id)->first();

        // Get detailed items
        $laborItems = DB::table('jobc_labor')
            ->where('RO_no', $id)
            ->where('status', 'Jobclose')
            ->get();

        $partsItems = DB::table('jobc_parts')
            ->where('RO_no', $id)
            ->whereIn('status', ['1', '3'])
            ->get();

        $subletItems = DB::table('jobc_sublet')
            ->where('RO_no', $id)
            ->where('status', 'JobDone')
            ->get();

        $consumableItems = DB::table('jobc_consumble')
            ->where('RO_no', $id)
            ->whereIn('status', ['1', '3'])
            ->get();

        $discount = $invoice->Ldiscount + $invoice->Sdiscount + $invoice->Cdiscount + $invoice->Pdiscount;
        $tax = $invoice->Ltax + $invoice->Ptax + $invoice->Stax + $invoice->Ctax;

        return view('finance.cashier.print_invoice', compact(
            'invoice', 'job', 'vehicle', 'customer',
            'laborItems', 'partsItems', 'subletItems', 'consumableItems',
            'discount', 'tax'
        ));
    }

    /**
     * Search jobs for AJAX
     */

    /**
     * POST /search  – redirect to the right print page by type
     * Mirrors PHP search.php redirect logic
     */
    public function searchRedirect(Request $request)
    {
        $search = trim($request->search ?? '');
        $field  = $request->field;

        if (!$search) return back()->with('error', 'Please enter a search value.');

        switch ($field) {
            case 'jobcard-instail':
                return redirect()->route('cashier.print-initial-ro', ['job_id' => $search]);
            case 'jobcard-closed':
                return redirect()->route('cashier.print-close-ro', ['job_id' => $search]);
            case 'Invoice':
                return redirect()->route('cashier.print-invoice', ['id' => $search]);
            case 'SalesTax':
                return redirect()->route('cashier.tax-invoice-get', ['ro_no' => $search]);
            default:
                return back()->with('error', 'Invalid search type.');
        }
    }

    public function searchJobs(Request $request)
    {
        $query = $request->get('query');

        $results = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('jc.Jobc_id', 'LIKE', "%{$query}%")
            ->orWhere('v.Registration', 'LIKE', "%{$query}%")
            ->orWhere('c.Customer_name', 'LIKE', "%{$query}%")
            ->orWhere('c.mobile', 'LIKE', "%{$query}%")
            ->select(
                'jc.Jobc_id',
                'jc.Customer_name',
                'jc.status',
                'v.Registration',
                'v.Variant',
                'c.mobile'
            )
            ->limit(20)
            ->get();

        return response()->json($results);
    }

    /**
     * Search for parts return
     */
    private function searchParts($search, $field)
    {
        $tablefield = explode('-', $field);
        $table = $tablefield[0];
        $field = $tablefield[1];

        if ($table == 'customer_data') {
            return DB::table('customer_data')
                ->where($field, 'LIKE', "%{$search}%")
                ->orderBy('Customer_id', 'desc')
                ->get();
        } elseif ($table == 'vehicles_data') {
            return DB::table('vehicles_data')
                ->where($field, 'LIKE', "%{$search}%")
                ->orderBy('Vehicle_id', 'desc')
                ->get();
        }

        return null;
    }

    /**
     * Vehicle history
     */
    public function history(Request $request)
    {
        $vehId = $request->veh_id;
        $custId = $request->Cust_id;

        $query = DB::table('jobcard as jc')
            ->leftJoin('jobc_labor as jl', 'jc.Jobc_id', '=', 'jl.RO_no')
            ->leftJoin('jobc_parts as jp', 'jc.Jobc_id', '=', 'jp.RO_no')
            ->leftJoin('jobc_sublet as js', 'jc.Jobc_id', '=', 'js.RO_no')
            ->leftJoin('jobc_consumble as jcns', 'jc.Jobc_id', '=', 'jcns.RO_no')
            ->select(
                'jc.Jobc_id',
                'jc.Customer_name',
                'jc.Veh_reg_no',
                'jc.VOC',
                'jc.Mileage',
                'jc.MSI_cat',
                'jc.closing_time',
                'jc.SA',
                DB::raw('GROUP_CONCAT(DISTINCT jl.Labor SEPARATOR "<br>") as labor'),
                DB::raw('GROUP_CONCAT(DISTINCT jl.cost SEPARATOR "<br>") as labor_cost'),
                DB::raw('GROUP_CONCAT(DISTINCT jp.part_description SEPARATOR "<br>") as parts'),
                DB::raw('GROUP_CONCAT(DISTINCT jp.total SEPARATOR "<br>") as parts_cost'),
                DB::raw('GROUP_CONCAT(DISTINCT js.Sublet SEPARATOR "<br>") as sublet'),
                DB::raw('GROUP_CONCAT(DISTINCT js.total SEPARATOR "<br>") as sublet_cost'),
                DB::raw('GROUP_CONCAT(DISTINCT jcns.cons_description SEPARATOR "<br>") as consumable'),
                DB::raw('GROUP_CONCAT(DISTINCT jcns.total SEPARATOR "<br>") as consumable_cost')
            )
            ->groupBy('jc.Jobc_id');

        if ($vehId) {
            $query->where('jc.Vehicle_id', $vehId);
        } elseif ($custId) {
            $query->where('jc.Customer_id', $custId);
        }

        $history = $query->orderBy('jc.Jobc_id', 'desc')->get();

        return view('finance.cashier.history', compact('history'));
    }
}
