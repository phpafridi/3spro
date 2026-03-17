<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecoveryController extends Controller
{
    // recov_debt:      cust_id, cust_name, contact, Vehicle_name, Registration,
    //                  Invoice_no(int), Db_date, Debt_amount, Remarks, user, entytime
    // recov_cred:      cred_id, cust_name, dm_invoice, Payment_method, RT_no,
    //                  cr_date, cr_amount, remarks, user, entytime
    // recov_fallowons: id, cust_name, Datetime(date), Person_contacted, Contact_type, Remarks
    // recov_accounts:  account_id, Name, Occopation, Primary_contact, Sec_contact,
    //                  email, amount_limit, r_officer, datetime, status

    // ─── Dashboard ────────────────────────────────────────────────────────────
    public function index()
    {
        $debtors = DB::table('recov_debt')
            ->select('cust_name', 'contact', 'Vehicle_name',
                     DB::raw('MAX(Db_date) as last_db_date'),
                     DB::raw('SUM(Debt_amount) as total_debt'))
            ->groupBy('cust_name', 'contact', 'Vehicle_name')
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

            $totalCredit       = DB::table('recov_cred')->where('cust_name', $row->cust_name)->sum('cr_amount');
            $row->remain_amount = $row->total_debt - $totalCredit;
            $totalDebit        += $row->remain_amount;
            if ($row->remain_amount <= 0) $close++;

            // Age since last debit
            try {
                $row->age = now()->diff(new \DateTime($row->last_db_date))->format('%yY %mM %dD');
            } catch (\Exception $e) {
                $row->age = '-';
            }
        }

        // Active = has a future followup scheduled
        $followups = DB::table('recov_fallowons')
            ->select('cust_name', DB::raw('MAX(Datetime) as last_f'))
            ->groupBy('cust_name')
            ->get();

        foreach ($followups as $f) {
            if ($f->last_f >= $last3days && $f->last_f > $today) {
                $active++;
            }
        }

        $open    = $totalc - $close;
        $pending = $open   - $active;

        return view('finance.recovery.index', compact(
            'debtors', 'totalDebit', 'totalc', 'new', 'close', 'active', 'open', 'pending'
        ));
    }

    // ─── Add Debt ─────────────────────────────────────────────────────────────
    public function addDebt(Request $request)
    {
        $invoice = null;
        if ($request->filled('id')) {
            // Pre-fill from jobc_invoice
            $invoice = DB::table('jobc_invoice as inv')
                ->join('jobcard as jc',       'inv.Jobc_id',    '=', 'jc.Jobc_id')
                ->join('vehicles_data as v',  'jc.Vehicle_id',  '=', 'v.Vehicle_id')
                ->join('customer_data as c',  'jc.Customer_id', '=', 'c.Customer_id')
                ->where('inv.Invoice_id', $request->id)
                ->select('jc.Customer_name','c.mobile','v.Registration','v.Variant',
                         'inv.Invoice_id','inv.Total','jc.closing_time')
                ->first();
        }
        return view('finance.recovery.add_debt', compact('invoice'));
    }

    public function addDebtStore(Request $request)
    {
        $request->validate([
            'typeahead'        => 'required|string|max:50',
            'required_invoice' => 'required',
            'required_date'    => 'required|date',
            'required_amount'  => 'required|numeric|min:1',
        ]);

        $user = Auth::user()->login_id;

        DB::table('recov_debt')->insert([
            'cust_name'    => $request->typeahead,
            'contact'      => $request->required_contact    ?? '',
            'Vehicle_name' => $request->vehicle             ?? '',
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
                'cust_name'        => $request->typeahead,
                'Datetime'         => $request->fallowup,
                'Person_contacted' => '',
                'Contact_type'     => 'Followup',
                'Remarks'          => 'Followup Time',
            ]);
        }

        // Mark invoice as in-recovery
        DB::table('jobc_invoice')
            ->where('Jobc_id', $request->required_invoice)
            ->update(['Rec_status' => '1']);

        return redirect()->route('recovery.customer-ledger', ['id' => $request->typeahead])
            ->with('success', 'Debit entry added.');
    }

    // ─── Add Credit ───────────────────────────────────────────────────────────
    public function addCredit(Request $request)
    {
        $prefill = null;
        if ($request->filled('id')) {
            $prefill = DB::table('recov_cred')->where('cred_id', $request->id)->first();
        }
        if ($request->filled('inv')) {
            $prefill = (object)['dm_invoice' => $request->inv];
        }
        return view('finance.recovery.add_cred', compact('prefill'));
    }

    public function addCreditStore(Request $request)
    {
        $request->validate([
            'required_dm'             => 'required',
            'required_payment_method' => 'required|string|max:20',
            'required_date'           => 'required|date',
            'required_amount'         => 'required|numeric|min:1',
        ]);

        $user = Auth::user()->login_id;

        // Verify invoice exists in recov_debt
        $debt = DB::table('recov_debt')
            ->where('Invoice_no', (int) $request->required_dm)
            ->first();

        if (!$debt) {
            return back()->with('error', 'Invoice number is wrong — not found in debit records.');
        }

        DB::table('recov_cred')->insert([
            'cust_name'      => $debt->cust_name,
            'dm_invoice'     => (string) $request->required_dm,
            'Payment_method' => $request->required_payment_method,
            'RT_no'          => $request->required_rt    ?? '',
            'cr_date'        => $request->required_date,
            'cr_amount'      => (string) $request->required_amount,
            'remarks'        => $request->remarks ?? '',
            'user'           => $user,
            'entytime'       => now(),
        ]);

        return redirect()->route('recovery.customer-ledger', ['id' => $debt->cust_name])
            ->with('success', 'Credit entry added.');
    }

    public function addCreditUpdate(Request $request)
    {
        DB::table('recov_cred')->where('cred_id', $request->cred_id)->update([
            'dm_invoice'     => (string) $request->required_dm,
            'Payment_method' => $request->required_payment_method,
            'RT_no'          => $request->required_rt   ?? '',
            'cr_date'        => $request->required_date,
            'cr_amount'      => (string) $request->required_amount,
            'remarks'        => $request->remarks ?? '',
        ]);
        return back()->with('success', 'Credit updated.');
    }

    // ─── Search ───────────────────────────────────────────────────────────────
    public function search(Request $request)
    {
        if ($request->isMethod('post') && $request->filled('typeahead')) {
            $name = $request->typeahead;
            if ($request->required_search === 'cust_clear') {
                return redirect()->route('recovery.clearance', ['id' => $name]);
            }
            return redirect()->route('recovery.customer-ledger', ['id' => $name]);
        }
        return view('finance.recovery.search');
    }

    public function searchAdvanced(Request $request)
    {
        $results = null;
        if ($request->filled('query')) {
            $q = $request->query;
            $results = DB::table('recov_debt')
                ->where('cust_name',    'like', "%$q%")
                ->orWhere('contact',    'like', "%$q%")
                ->orWhere('Invoice_no', 'like', "%$q%")
                ->orWhere('Registration','like', "%$q%")
                ->orderBy('Db_date', 'desc')
                ->get();
        }
        return view('finance.recovery.search_adv', compact('results'));
    }

    // AJAX — typeahead customer name suggestions
    public function searchName(Request $request)
    {
        $key = $request->key ?? '';
        return response()->json(
            DB::table('recov_debt')->where('cust_name', 'like', "%$key%")
              ->distinct()->pluck('cust_name')
        );
    }

    // ─── Customer Ledger ──────────────────────────────────────────────────────
    public function customerLedger(Request $request)
    {
        $id = $request->id;

        $debts   = DB::table('recov_debt')->where('cust_name', $id)->orderBy('Db_date', 'desc')->get();
        $credits = DB::table('recov_cred')->where('cust_name', $id)->orderBy('cr_date', 'desc')->get();

        $totalDebt   = $debts->sum('Debt_amount');
        $totalCredit = $credits->sum('cr_amount');
        $balance     = $totalDebt - $totalCredit;

        return view('finance.recovery.customer_ledger',
            compact('id', 'debts', 'credits', 'totalDebt', 'totalCredit', 'balance'));
    }

    // ─── Clearance ────────────────────────────────────────────────────────────
    public function clearance(Request $request)
    {
        $id      = $request->id;
        $debts   = DB::table('recov_debt')->where('cust_name', $id)->get();
        $credits = DB::table('recov_cred')->where('cust_name', $id)->get();
        $balance = $debts->sum('Debt_amount') - $credits->sum('cr_amount');

        return view('finance.recovery.clearance', compact('id', 'debts', 'credits', 'balance'));
    }

    // ─── History ─────────────────────────────────────────────────────────────
    // recov_fallowons: id, cust_name, Datetime, Person_contacted, Contact_type, Remarks
    public function history(Request $request)
    {
        $id        = $request->id;
        $followups = DB::table('recov_fallowons')
            ->where('cust_name', $id)
            ->orderBy('Datetime', 'desc')
            ->get();

        return view('finance.recovery.history', compact('id', 'followups'));
    }

    // ─── Followup ─────────────────────────────────────────────────────────────
    public function followup(Request $request)
    {
        $id = $request->id;

        if ($request->isMethod('post')) {
            DB::table('recov_fallowons')->insert([
                'cust_name'        => $id,
                'Datetime'         => $request->fdate,
                'Person_contacted' => $request->person_contacted ?? '',
                'Contact_type'     => $request->contact_type,
                'Remarks'          => $request->remarks ?? '',
            ]);
            return back()->with('success', 'Followup saved.');
        }

        $contact = DB::table('recov_debt')->where('cust_name', $id)->value('contact');
        return view('finance.recovery.followon', compact('id', 'contact'));
    }

    // ─── Not Contacted ────────────────────────────────────────────────────────
    public function notContacted()
    {
        $cutoff = now()->subDays(7)->toDateString();

        $list = DB::table('recov_debt as d')
            ->leftJoin('recov_fallowons as f', 'd.cust_name', '=', 'f.cust_name')
            ->select('d.cust_name', 'd.contact', DB::raw('MAX(f.Datetime) as last_fol'))
            ->groupBy('d.cust_name', 'd.contact')
            ->havingRaw('last_fol IS NULL OR last_fol < ?', [$cutoff])
            ->orderBy('d.cust_name')
            ->get();

        return view('finance.recovery.not_contacted', compact('list'));
    }

    // ─── Recovered ───────────────────────────────────────────────────────────
    public function recovered()
    {
        $debtors = DB::table('recov_debt')
            ->select('cust_name', 'contact', DB::raw('SUM(Debt_amount) as total_debt'))
            ->groupBy('cust_name', 'contact')
            ->get();

        $list = $debtors->filter(function ($row) {
            $cr           = DB::table('recov_cred')->where('cust_name', $row->cust_name)->sum('cr_amount');
            $row->balance = $row->total_debt - $cr;
            return $row->balance <= 0;
        });

        return view('finance.recovery.recovered', compact('list'));
    }

    // ─── Stats ───────────────────────────────────────────────────────────────
    // PHP original: per-customer summary (debt, credit, last followup, balance)
    public function stats()
    {
        $debtors = DB::table('recov_debt')
            ->select(
                'cust_name', 'contact', 'Vehicle_name',
                DB::raw('MAX(Db_date) AS last_db_date'),
                DB::raw('SUM(Debt_amount) AS total_debt')
            )
            ->groupBy('cust_name', 'contact', 'Vehicle_name')
            ->orderBy('last_db_date', 'asc')
            ->get();

        $followups = DB::table('recov_fallowons')
            ->select('cust_name', DB::raw('MAX(Datetime) AS last_f'))
            ->groupBy('cust_name')
            ->pluck('last_f', 'cust_name');

        $stats = $debtors->map(function ($row) use ($followups) {
            $row->total_credit  = DB::table('recov_cred')
                ->where('cust_name', $row->cust_name)->sum('cr_amount');
            $row->balance       = $row->total_debt - $row->total_credit;
            $row->last_followup = $followups[$row->cust_name] ?? null;
            return $row;
        });

        return view('finance.recovery.stats', compact('stats'));
    }

    // ─── DM Bills ────────────────────────────────────────────────────────────
    // PHP: WHERE (type='DM' OR type='DMC') AND Rec_status='' (empty string, varchar)
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
                'jc.Customer_name', 'jc.Veh_reg_no', 'jc.SA',
                'c.mobile', 'c.cust_type'
            )
            ->orderBy('inv.datetime', 'desc')
            ->get();

        return view('finance.recovery.dm_bills', compact('bills'));
    }

    // ─── Add Account ──────────────────────────────────────────────────────────
    // recov_accounts: account_id, Name, Occopation, Primary_contact, Sec_contact,
    //                 email, amount_limit, r_officer, datetime, status
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
            ->select('jc.Customer_name','c.mobile','v.Registration','v.Variant',
                     'inv.Invoice_id','inv.Total','jc.closing_time','inv.Jobc_id')
            ->first();

        if (!$inv) return response()->json(['error' => 'Invoice not found'], 404);
        return response()->json($inv);
    }

    public function emailStatus()
    {
        return view('finance.recovery.email_status');
    }
}
