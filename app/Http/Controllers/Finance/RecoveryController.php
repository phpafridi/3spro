<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecoveryController extends Controller
{
    // recov_debt:      cust_id(PK), Customer_id(FK), cust_name, contact, Vehicle_name,
    //                  Registration, Invoice_no(int), Db_date, Debt_amount, Remarks, user, entytime
    // recov_cred:      cred_id(PK), Customer_id(FK), cust_name, dm_invoice, Payment_method,
    //                  RT_no, cr_date, cr_amount, remarks, user, entytime
    // recov_fallowons: id(PK), Customer_id(FK), cust_name, Datetime, Person_contacted,
    //                  Contact_type, Remarks
    // recov_accounts:  account_id, Name, Occopation, Primary_contact, Sec_contact,
    //                  email, amount_limit, r_officer, datetime, status

    // ─── Dashboard ────────────────────────────────────────────────────────────
    public function index()
    {
        // Group by Customer_id so two customers with the same name are never merged
        $debtors = DB::table('recov_debt')
            ->select(
                'Customer_id',
                DB::raw('MAX(cust_name) as cust_name'),   // display name (same for a given Customer_id)
                DB::raw('MAX(contact) as contact'),
                DB::raw('MAX(Vehicle_name) as Vehicle_name'),
                DB::raw('MAX(Db_date) as last_db_date'),
                DB::raw('SUM(Debt_amount) as total_debt')
            )
            ->groupBy('Customer_id')
            ->orderBy('last_db_date', 'asc')
            ->get();

        $today     = now()->toDateString();
        $last7days = now()->subDays(7)->toDateString();
        $last3days = now()->subDays(3)->toDateString();

        $totalDebit = 0;
        $totalc = 0; $new = 0; $close = 0; $active = 0;

        foreach ($debtors as $row) {
            $totalc++;
            if ($row->last_db_date >= $last7days) $new++;

            $totalCredit        = DB::table('recov_cred')->where('Customer_id', $row->Customer_id)->sum('cr_amount');
            $row->remain_amount = $row->total_debt - $totalCredit;
            $totalDebit        += $row->remain_amount;
            if ($row->remain_amount <= 0) $close++;

            try {
                $row->age = now()->diff(new \DateTime($row->last_db_date))->format('%yY %mM %dD');
            } catch (\Exception $e) {
                $row->age = '-';
            }
        }

        $followups = DB::table('recov_fallowons')
            ->select('Customer_id', DB::raw('MAX(Datetime) as last_f'))
            ->groupBy('Customer_id')
            ->get();

        foreach ($followups as $f) {
            if ($f->last_f >= $last3days && $f->last_f > $today) {
                $active++;
            }
        }

        $open    = $totalc - $close;
        $pending = $open   - $active;

        $dmBills = DB::table('jobc_invoice as inv')
            ->join('jobcard as jc', 'inv.Jobc_id', '=', 'jc.Jobc_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->whereIn('inv.type', ['DM', 'DMC'])
            ->where('inv.Rec_status', '')
            ->select(
                'inv.Invoice_id', 'inv.Jobc_id', 'inv.Total', 'inv.type', 'inv.careof',
                DB::raw("DATE_FORMAT(inv.datetime, '%Y-%m-%d') AS bookingtime"),
                'jc.Customer_name', 'jc.Veh_reg_no', 'jc.Customer_id'
            )
            ->orderBy('inv.datetime', 'desc')
            ->get();

        return view('finance.recovery.index', compact(
            'debtors', 'totalDebit', 'totalc', 'new', 'close', 'active', 'open', 'pending', 'dmBills'
        ));
    }

    // ─── Add Debt ─────────────────────────────────────────────────────────────
    public function addDebt(Request $request)
    {
        $invoice = null;
        if ($request->filled('id')) {
            $invoice = DB::table('jobc_invoice as inv')
                ->join('jobcard as jc',       'inv.Jobc_id',    '=', 'jc.Jobc_id')
                ->join('vehicles_data as v',  'jc.Vehicle_id',  '=', 'v.Vehicle_id')
                ->join('customer_data as c',  'jc.Customer_id', '=', 'c.Customer_id')
                ->where('inv.Invoice_id', $request->id)
                ->select('jc.Customer_name', 'jc.Customer_id', 'c.mobile',
                         'v.Registration', 'v.Variant',
                         'inv.Invoice_id', 'inv.Total', 'jc.closing_time')
                ->first();
        }
        return view('finance.recovery.add_debt', compact('invoice'));
    }

    public function addDebtStore(Request $request)
    {
        $request->validate([
            'typeahead'        => 'required|string|max:50',
            'required_invoice' => 'required|integer',
            'required_date'    => 'required|date',
            'required_amount'  => 'required|numeric|min:1',
        ]);

        $user = Auth::user()->login_id;

        // Prevent duplicate debit for the same invoice
        if (DB::table('recov_debt')->where('Invoice_no', (int) $request->required_invoice)->exists()) {
            return back()->withInput()
                ->with('error', 'A debit entry for Invoice #' . $request->required_invoice . ' already exists. To record a payment, use Add Credit.');
        }

        // Resolve Customer_id from jobc_invoice → jobcard
        $customerId = (int) ($request->customer_id ?? 0);
        if (!$customerId) {
            $customerId = (int) DB::table('jobc_invoice as inv')
                ->join('jobcard as jc', 'inv.Jobc_id', '=', 'jc.Jobc_id')
                ->where('inv.Invoice_id', (int) $request->required_invoice)
                ->value('jc.Customer_id') ?? 0;
        }

        DB::table('recov_debt')->insert([
            'Customer_id'  => $customerId,
            'cust_name'    => $request->typeahead,
            'contact'      => $request->required_contact      ?? '',
            'Vehicle_name' => $request->vehicle               ?? '',
            'Registration' => $request->required_registration ?? '',
            'Invoice_no'   => (int) $request->required_invoice,
            'Db_date'      => $request->required_date,
            'Debt_amount'  => (int) $request->required_amount,
            'Remarks'      => $request->remarks ?? '',
            'user'         => $user,
            'entytime'     => now(),
        ]);

        if ($request->filled('fallowup')) {
            DB::table('recov_fallowons')->insert([
                'Customer_id'      => $customerId,
                'cust_name'        => $request->typeahead,
                'Datetime'         => $request->fallowup,
                'Person_contacted' => '',
                'Contact_type'     => 'Followup',
                'Remarks'          => 'Followup scheduled on debit entry',
            ]);
        }

        DB::table('jobc_invoice')
            ->where('Invoice_id', (int) $request->required_invoice)
            ->update(['Rec_status' => '1']);

        return redirect()->route('recovery.customer-ledger', ['id' => $customerId])
            ->with('success', 'Debit entry added.');
    }

    // ─── Add Credit ───────────────────────────────────────────────────────────
    public function addCredit(Request $request)
    {
        $prefill    = null;

        if ($request->filled('cred_id')) {
            $prefill = DB::table('recov_cred')->where('cred_id', $request->cred_id)->first();

        } elseif ($request->filled('inv')) {
            // Coming from DM Bills — look up invoice to pre-fill
            $invoiceRef = DB::table('jobc_invoice as inv')
                ->join('jobcard as jc',      'inv.Jobc_id',    '=', 'jc.Jobc_id')
                ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
                ->where('inv.Invoice_id', (int) $request->inv)
                ->select('inv.Invoice_id', 'inv.Total', 'jc.Customer_name', 'jc.Customer_id', 'c.mobile')
                ->first();

            if ($invoiceRef) {
                $prefill = (object)[
                    'dm_invoice'  => $invoiceRef->Invoice_id,
                    'cr_amount'   => $invoiceRef->Total,
                    'cust_name'   => $invoiceRef->Customer_name,
                    'Customer_id' => $invoiceRef->Customer_id,
                ];
            }
        }

        return view('finance.recovery.add_cred', compact('prefill'));
    }

    public function addCreditStore(Request $request)
    {
        $request->validate([
            'required_dm'             => 'required|integer',
            'required_payment_method' => 'required|string|max:20',
            'required_date'           => 'required|date',
            'required_amount'         => 'required|numeric|min:1',
        ]);

        $user = Auth::user()->login_id;

        // Resolve the debt row — keyed by Invoice_no (unique per invoice)
        $debt = DB::table('recov_debt')
            ->where('Invoice_no', (int) $request->required_dm)
            ->first();

        if (!$debt) {
            return back()->withInput()
                ->with('error', 'Invoice #' . $request->required_dm . ' not found in debit records. Ensure the debit entry exists first.');
        }

        DB::table('recov_cred')->insert([
            'Customer_id'    => $debt->Customer_id,
            'cust_name'      => $debt->cust_name,
            'dm_invoice'     => (string) $request->required_dm,
            'Payment_method' => $request->required_payment_method,
            'RT_no'          => $request->required_rt ?? '',
            'cr_date'        => $request->required_date,
            'cr_amount'      => (string) $request->required_amount,
            'remarks'        => $request->remarks ?? '',
            'user'           => $user,
            'entytime'       => now(),
        ]);

        // Update Rec_status: always move out of pending (''), upgrade to recovered if fully paid
        $totalDebt   = DB::table('recov_debt')->where('Customer_id', $debt->Customer_id)->sum('Debt_amount');
        $totalCredit = DB::table('recov_cred')->where('Customer_id', $debt->Customer_id)->sum('cr_amount');
        $newStatus   = ($totalCredit >= $totalDebt) ? 'recovered' : '1';

        DB::table('jobc_invoice')
            ->where('Invoice_id', (int) $request->required_dm)
            ->update(['Rec_status' => $newStatus]);

        return redirect()->route('recovery.customer-ledger', ['id' => $debt->Customer_id])
            ->with('success', 'Credit entry saved successfully.');
    }

    public function addCreditUpdate(Request $request)
    {
        DB::table('recov_cred')->where('cred_id', $request->cred_id)->update([
            'dm_invoice'     => (string) $request->required_dm,
            'Payment_method' => $request->required_payment_method,
            'RT_no'          => $request->required_rt ?? '',
            'cr_date'        => $request->required_date,
            'cr_amount'      => (string) $request->required_amount,
            'remarks'        => $request->remarks ?? '',
        ]);
        return back()->with('success', 'Credit entry updated.');
    }

    // ─── Search ───────────────────────────────────────────────────────────────
    public function search(Request $request)
    {
        if ($request->isMethod('post') && $request->filled('typeahead')) {
            $name = $request->typeahead;
            // If multiple Customer_ids share this name, return a disambiguation list
            $ids = DB::table('recov_debt')
                ->where('cust_name', $name)
                ->select('Customer_id', 'cust_name', 'contact', 'Registration')
                ->distinct()
                ->get();

            if ($ids->count() === 1) {
                $customerId = $ids->first()->Customer_id;
                if ($request->required_search === 'cust_clear') {
                    return redirect()->route('recovery.clearance', ['id' => $customerId]);
                }
                return redirect()->route('recovery.customer-ledger', ['id' => $customerId]);
            }

            // Multiple customers with same name — show picker
            return view('finance.recovery.search', compact('ids', 'name'));
        }
        return view('finance.recovery.search');
    }

    public function searchAdvanced(Request $request)
    {
        $results = null;
        if ($request->filled('query')) {
            $q = $request->query;
            $results = DB::table('recov_debt')
                ->where('cust_name',     'like', "%$q%")
                ->orWhere('contact',     'like', "%$q%")
                ->orWhere('Invoice_no',  'like', "%$q%")
                ->orWhere('Registration','like', "%$q%")
                ->orderBy('Db_date', 'desc')
                ->get();
        }
        return view('finance.recovery.search_adv', compact('results'));
    }

    // AJAX — typeahead: return distinct customer name + id pairs
    public function searchName(Request $request)
    {
        $key = $request->key ?? '';
        return response()->json(
            DB::table('recov_debt')
                ->where('cust_name', 'like', "%$key%")
                ->select('cust_name', 'Customer_id', 'contact', 'Registration')
                ->distinct()
                ->get()
        );
    }

    // ─── Customer Ledger ──────────────────────────────────────────────────────
    // $id = Customer_id (integer) — unique per actual customer
    public function customerLedger(Request $request)
    {
        $id = (int) $request->id;

        $debts   = DB::table('recov_debt')->where('Customer_id', $id)->orderBy('Db_date', 'desc')->get();
        $credits = DB::table('recov_cred')->where('Customer_id', $id)->orderBy('cr_date', 'desc')->get();

        $custName    = $debts->first()->cust_name ?? 'Unknown';
        $totalDebt   = $debts->sum('Debt_amount');
        $totalCredit = $credits->sum('cr_amount');
        $balance     = $totalDebt - $totalCredit;

        return view('finance.recovery.customer_ledger',
            compact('id', 'custName', 'debts', 'credits', 'totalDebt', 'totalCredit', 'balance'));
    }

    // ─── Clearance ────────────────────────────────────────────────────────────
    public function clearance(Request $request)
    {
        $id      = (int) $request->id;
        $debts   = DB::table('recov_debt')->where('Customer_id', $id)->get();
        $credits = DB::table('recov_cred')->where('Customer_id', $id)->get();
        $balance = $debts->sum('Debt_amount') - $credits->sum('cr_amount');
        $custName = $debts->first()->cust_name ?? 'Unknown';

        return view('finance.recovery.clearance', compact('id', 'custName', 'debts', 'credits', 'balance'));
    }

    // ─── History ──────────────────────────────────────────────────────────────
    public function history(Request $request)
    {
        $id        = (int) $request->id;
        $followups = DB::table('recov_fallowons')
            ->where('Customer_id', $id)
            ->orderBy('Datetime', 'desc')
            ->get();

        $custName = DB::table('recov_debt')->where('Customer_id', $id)->value('cust_name') ?? '';

        return view('finance.recovery.history', compact('id', 'custName', 'followups'));
    }

    // ─── Followup ─────────────────────────────────────────────────────────────
    public function followup(Request $request)
    {
        $id = (int) $request->id;

        if ($request->isMethod('post')) {
            $custName = DB::table('recov_debt')->where('Customer_id', $id)->value('cust_name') ?? '';
            DB::table('recov_fallowons')->insert([
                'Customer_id'      => $id,
                'cust_name'        => $custName,
                'Datetime'         => $request->fdate,
                'Person_contacted' => $request->person_contacted ?? '',
                'Contact_type'     => $request->contact_type,
                'Remarks'          => $request->remarks ?? '',
            ]);
            return back()->with('success', 'Followup saved.');
        }

        $debt     = DB::table('recov_debt')->where('Customer_id', $id)->first();
        $custName = $debt->cust_name ?? '';
        $contact  = $debt->contact  ?? '';

        return view('finance.recovery.followon', compact('id', 'custName', 'contact'));
    }

    // ─── Not Contacted ────────────────────────────────────────────────────────
    public function notContacted()
    {
        $cutoff = now()->subDays(7)->toDateString();

        $list = DB::table('recov_debt as d')
            ->leftJoin('recov_fallowons as f', 'd.Customer_id', '=', 'f.Customer_id')
            ->select('d.Customer_id', 'd.cust_name', 'd.contact',
                     DB::raw('MAX(f.Datetime) as last_fol'))
            ->groupBy('d.Customer_id', 'd.cust_name', 'd.contact')
            ->havingRaw('last_fol IS NULL OR last_fol < ?', [$cutoff])
            ->orderBy('d.cust_name')
            ->get();

        return view('finance.recovery.not_contacted', compact('list'));
    }

    // ─── Recovered ───────────────────────────────────────────────────────────
    public function recovered()
    {
        $debtors = DB::table('recov_debt')
            ->select('Customer_id',
                     DB::raw('MAX(cust_name) as cust_name'),
                     DB::raw('MAX(contact) as contact'),
                     DB::raw('SUM(Debt_amount) as total_debt'))
            ->groupBy('Customer_id')
            ->get();

        $list = $debtors->filter(function ($row) {
            $totalCredit       = DB::table('recov_cred')->where('Customer_id', $row->Customer_id)->sum('cr_amount');
            $row->total_credit = $totalCredit;
            $row->balance      = $row->total_debt - $totalCredit;
            return $row->balance <= 0;
        })->values();

        return view('finance.recovery.recovered', compact('list'));
    }

    // ─── Stats ───────────────────────────────────────────────────────────────
    public function stats()
    {
        $debtors = DB::table('recov_debt')
            ->select('Customer_id',
                     DB::raw('MAX(cust_name) as cust_name'),
                     DB::raw('MAX(contact) as contact'),
                     DB::raw('MAX(Vehicle_name) as Vehicle_name'),
                     DB::raw('MAX(Db_date) AS last_db_date'),
                     DB::raw('SUM(Debt_amount) AS total_debt'))
            ->groupBy('Customer_id')
            ->orderBy('last_db_date', 'asc')
            ->get();

        $followups = DB::table('recov_fallowons')
            ->select('Customer_id', DB::raw('MAX(Datetime) AS last_f'))
            ->groupBy('Customer_id')
            ->pluck('last_f', 'Customer_id');

        $stats = $debtors->map(function ($row) use ($followups) {
            $row->total_credit  = DB::table('recov_cred')
                ->where('Customer_id', $row->Customer_id)->sum('cr_amount');
            $row->balance       = $row->total_debt - $row->total_credit;
            $row->last_followup = $followups[$row->Customer_id] ?? null;
            return $row;
        });

        return view('finance.recovery.stats', compact('stats'));
    }

    // ─── DM Bills ────────────────────────────────────────────────────────────
    public function dmBills()
    {
        $bills = DB::table('jobc_invoice as inv')
            ->join('jobcard as jc',      'inv.Jobc_id',    '=', 'jc.Jobc_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->whereIn('inv.type', ['DM', 'DMC'])
            ->where('inv.Rec_status', '')
            ->select(
                'inv.Invoice_id', 'inv.Jobc_id', 'inv.Lnet', 'inv.Pnet',
                'inv.Snet', 'inv.Cnet', 'inv.Total', 'inv.type', 'inv.careof',
                DB::raw("DATE_FORMAT(inv.datetime, '%m/%d/%Y') AS bookingtime"),
                'jc.Customer_name', 'jc.Veh_reg_no', 'jc.SA', 'jc.Customer_id',
                'c.mobile', 'c.cust_type'
            )
            ->orderBy('inv.datetime', 'desc')
            ->get();

        return view('finance.recovery.dm_bills', compact('bills'));
    }

    // ─── Add Account ──────────────────────────────────────────────────────────
    public function addAccount()
    {
        $accounts = DB::table('recov_accounts')->orderBy('account_id', 'desc')->get();
        return view('finance.recovery.add_account', compact('accounts'));
    }

    public function addAccountStore(Request $request)
    {
        $request->validate([
            'Name'            => 'required|string|max:38',
            'Occopation'      => 'nullable|string|max:30',
            'Primary_contact' => 'required|numeric',
        ]);

        DB::table('recov_accounts')->insert([
            'Name'            => $request->Name,
            'Occopation'      => $request->Occopation      ?? '',
            'Primary_contact' => $request->Primary_contact,
            'Sec_contact'     => $request->Sec_contact     ?? 0,
            'email'           => $request->email           ?? '',
            'amount_limit'    => $request->amount_limit    ?? 0,
            'r_officer'       => Auth::user()->login_id,
            'datetime'        => now(),
            'status'          => 'Active',
        ]);

        return back()->with('success', 'Account added.');
    }

    // ─── AJAX: Check Invoice ──────────────────────────────────────────────────
    public function checkInvoice(Request $request)
    {
        $inv = DB::table('jobc_invoice as inv')
            ->join('jobcard as jc',      'inv.Jobc_id',    '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id',  '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('inv.Invoice_id', $request->invoice_id)
            ->select('jc.Customer_name', 'jc.Customer_id', 'c.mobile',
                     'v.Registration', 'v.Variant',
                     'inv.Invoice_id', 'inv.Total', 'jc.closing_time', 'inv.Jobc_id')
            ->first();

        if (!$inv) return response()->json(['error' => 'Invoice not found'], 404);
        return response()->json($inv);
    }

    public function emailStatus()
    {
        return view('finance.recovery.email_status');
    }
}
