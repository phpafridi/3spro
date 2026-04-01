<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountantController extends Controller
{
    public function index()
    {
        return view('finance.accountant.index');
    }

    // jobcard: Jobc_id, Customer_name, SA, closing_time, MSI_cat, status, Vehicle_id, Customer_id
    // vehicles_data: Vehicle_id, Registration, Variant
    // customer_data: Customer_id, mobile
    // jobc_invoice: Invoice_id, Jobc_id, Total, Rec_status
    public function jobcardStatus()
    {
        $jobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->leftJoin('jobc_invoice as inv', 'jc.Jobc_id', '=', 'inv.Jobc_id')
            ->whereIn('jc.status', [2, 3])
            ->select('jc.Jobc_id','jc.Customer_name','jc.SA','jc.MSI_cat','jc.status',
                     'jc.closing_time','v.Variant','v.Registration','c.mobile',
                     'inv.Invoice_id','inv.Total','inv.type')
            ->orderBy('jc.Jobc_id', 'desc')
            ->get();



        return view('finance.accountant.jobcard_status', compact('jobs'));
    }

    // s_unclosed_jc: unjc_Id, jobc_id, SM_reason, SM, sm_datetime, fin_reason, fin_datetime, fin_guy, status(int)
    public function reopenJc()
    {
        $pending = DB::table('s_unclosed_jc')
            ->where('status', 1)
            ->orderBy('unjc_Id', 'desc')
            ->get();

        return view('finance.accountant.reopen_jc', compact('pending'));
    }

    public function reopenJcProcess(Request $request)
    {
        $user = Auth::user()->login_id;

        if ($request->filled('unclose_id') && !$request->filled('Jobc_id')) {
            DB::table('s_unclosed_jc')
                ->where('unjc_Id', $request->unclose_id)
                ->update(['status' => 3, 'fin_guy' => $user, 'fin_datetime' => now()]);
            return back()->with('success', 'Request rejected.');
        }

        if ($request->filled('Jobc_id')) {
            DB::table('jobcard')->where('Jobc_id', $request->Jobc_id)->update(['status' => 1]);
            DB::table('jobc_invoice')->where('Jobc_id', $request->Jobc_id)->delete();
            DB::table('s_unclosed_jc')
                ->where('unjc_Id', $request->unjc_Id)
                ->update(['status' => 2, 'fin_guy' => $user, 'fin_datetime' => now()]);
            return back()->with('success', 'Jobcard reopened successfully.');
        }

        return back()->with('error', 'Invalid request.');
    }

    // s_labor_request: req_id, labor, cate1-5, remarks, status, who_req, when_req, who_acept, when_acept
    public function laborRequest()
    {
        $requests = DB::table('s_labor_request')
            ->where('status', 'Pending')
            ->orderBy('req_id', 'desc')
            ->get();

        return view('finance.accountant.labor_request', compact('requests'));
    }

    public function laborRequestProcess(Request $request)
    {
        $user   = Auth::user()->login_id;
        $req_id = $request->req_id;

        if ($request->filled('rejected')) {
            DB::table('s_labor_request')
                ->where('req_id', $req_id)
                ->update(['status' => 'Rejected', 'who_acept' => $user, 'when_acept' => now()]);
            return back()->with('success', 'Labor request rejected.');
        }

        if ($request->filled('addto_list')) {
            // labor_list: Labor_ID(pk auto), Labor, Cate1, Cate2, Cate3, Cate4, Cate5
            $inserted = DB::statement("
                INSERT INTO labor_list (Labor, Cate1, Cate2, Cate3, Cate4, Cate5)
                SELECT labor, cate1, cate2, cate3, cate4, cate5
                FROM s_labor_request WHERE req_id = ?
            ", [$req_id]);

            $status = $inserted ? 'Accepted' : 'Duplicate';
            DB::table('s_labor_request')
                ->where('req_id', $req_id)
                ->update(['status' => $status, 'who_acept' => $user, 'when_acept' => now()]);

            return back()->with($inserted ? 'success' : 'error',
                $inserted ? 'Labor added to list.' : 'Duplicate labor — not accepted.');
        }

        return back();
    }

    // labor_list: Labor_ID, Labor, Cate1, Cate2, Cate3, Cate4, Cate5
    public function laborManual()
    {
        $labors = DB::table('labor_list')->orderBy('Labor')->get();
        return view('finance.accountant.labor_manual', compact('labors'));
    }

    public function laborAuto()
    {
        return view('finance.accountant.labor_auto');
    }

    public function laborAutoUpdate(Request $request)
    {
        $request->validate([
            'whatupdate' => 'required|in:Increase,Decrease',
            'update'     => 'required|numeric|min:0.01|max:100',
        ]);

        $p = $request->update / 100;

        if ($request->whatupdate === 'Increase') {
            DB::statement("UPDATE labor_list SET
                Cate1=ROUND(Cate1+Cate1*?,0), Cate2=ROUND(Cate2+Cate2*?,0),
                Cate3=ROUND(Cate3+Cate3*?,0), Cate4=ROUND(Cate4+Cate4*?,0),
                Cate5=ROUND(Cate5+Cate5*?,0)", array_fill(0, 5, $p));
        } else {
            DB::statement("UPDATE labor_list SET
                Cate1=ROUND(Cate1-Cate1*?,0), Cate2=ROUND(Cate2-Cate2*?,0),
                Cate3=ROUND(Cate3-Cate3*?,0), Cate4=ROUND(Cate4-Cate4*?,0),
                Cate5=ROUND(Cate5-Cate5*?,0)", array_fill(0, 5, $p));
        }

        return back()->with('success', 'Labor prices updated successfully.');
    }

    // users: id, login_id, Name, password, email, last_login, mobile, dept, position, last_logout
    public function newUser()
    {
        return view('finance.accountant.new_user');
    }

    public function newUserStore(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:34',
            'login_id'  => 'required|string|max:35|unique:users,login_id',
            'password2' => 'required|string|min:3',
            'email'     => 'nullable|email|max:34',
            'phone'     => 'nullable|string|max:23',
            'position'  => 'required|string',
        ]);

        $parts = explode('-', $request->position, 2);

        if ($request->hasFile('fileup')) {
            $request->file('fileup')->storeAs('profile', $request->login_id . '.jpg', 'public');
        }

        DB::table('users')->insert([
            'login_id'    => $request->login_id,
            'Name'        => $request->name,
            'password'    => Hash::make($request->password2),
            'email'       => $request->email ?? '',
            'mobile'      => $request->phone ?? '',
            'dept'        => $parts[0] ?? '',
            'position'    => $parts[1] ?? $request->position,
            'last_login'  => now(),
            'last_logout' => now(),
        ]);

        return back()->with('success', 'User created successfully.');
    }

    // p_parts: part_id, Part_no, Description, Location, catetype, part_type, Model, ReOrder, user, datetime
    // msi_category: id, MSI_CAT, Description, CPUS_Warranty, PM_GM, Labor
    public function newPart()
    {
        $categories = DB::table('msi_category')->orderBy('MSI_CAT')->get();
        return view('finance.accountant.new_part', compact('categories'));
    }

    public function newPartStore(Request $request)
    {
        $request->validate([
            'partnumber'  => 'required|string|max:35',
            'description' => 'required|string',
            'catetype'    => 'required|string|max:15',
            'parttype'    => 'required|string|max:15',
        ]);

        DB::table('p_parts')->insert([
            'Part_no'     => $request->partnumber,
            'Description' => $request->description,
            'catetype'    => $request->catetype,
            'part_type'   => $request->parttype,
            'Model'       => $request->modelcode ?? '',
            'ReOrder'     => $request->reorder   ?? 0,
            'Location'    => $request->Location  ?? '',
            'user'        => Auth::user()->login_id,
            'datetime'    => now(),
        ]);

        return back()->with('success', 'Part added successfully.');
    }

    public function serviceSearch()
    {
        return view('finance.accountant.service_search');
    }

    public function serviceSearchRedirect(Request $request)
    {
        switch ($request->field) {
            case 'jobcard-instail': return redirect()->route('cashier.print-initial-ro', ['job_id' => $request->search]);
            case 'jobcard-closed':  return redirect()->route('cashier.print-close-ro',   ['job_id' => $request->search]);
            case 'Invoice':         return redirect()->route('cashier.print-invoice',     ['id'     => $request->search]);
            case 'SalesTax':        return redirect()->route('cashier.tax-invoice-get',   ['ro_no'  => $request->search]);
        }
        return back()->with('error', 'Invalid search type.');
    }

    public function partsSearch()
    {
        return view('finance.accountant.parts_search');
    }

    public function partsSearchRedirect(Request $request)
    {
        switch ($request->field) {
            case 'counter-sale': return redirect()->route('parts.print.sale-invoice',  ['inv_no'     => $request->search]);
            case 'purch-prof':   return redirect()->route('parts.print.purchase',       ['invoice_no' => $request->search]);
            case 'purch-inv':    return redirect()->route('parts.print.purchase',       ['invoice_no' => $request->search]);
        }
        return back()->with('error', 'Invalid search type.');
    }

    public function history(Request $request)
    {
        $jobs = null;
        if ($request->filled('search')) {
            $s = $request->search;
            $jobs = DB::table('jobcard as jc')
                ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
                ->join('customer_data as c',  'jc.Customer_id', '=', 'c.Customer_id')
                ->leftJoin('jobc_invoice as inv', 'jc.Jobc_id', '=', 'inv.Jobc_id')
                ->where(function ($q) use ($s) {
                    $q->where('jc.Jobc_id', $s)
                      ->orWhere('jc.Customer_name', 'like', "%$s%")
                      ->orWhere('v.Registration',   'like', "%$s%");
                })
                ->select('jc.Jobc_id','jc.Customer_name','jc.SA','jc.closing_time','jc.status',
                         'v.Variant','v.Registration','c.mobile','inv.Total','inv.Invoice_id')
                ->orderBy('jc.Jobc_id', 'desc')
                ->get();
        }

        return view('finance.accountant.history', compact('jobs'));
    }

    public function financeReports()
    {
        return view('finance.accountant.finance_reports');
    }

    public function partsReports()
    {
        return view('finance.accountant.parts_reports');
    }

    /**
     * Cancel an issued part or consumable and restore stock.
     * POST /finance/accountant/cancel-part
     */
    public function cancelPart(Request $request)
    {
        $request->validate([
            'cancel'    => 'required|in:jobc_parts,jobc_consumble',
            'record_id' => 'required|integer',
        ]);

        $table = $request->cancel;
        $pk    = $table === 'jobc_parts' ? 'parts_sale_id' : 'cons_sale_id';
        $id    = $request->record_id;
        $user  = Auth::user()->login_id;

        $rec = DB::table($table)->where($pk, $id)->first();
        if (!$rec) {
            return back()->with('error', 'Record not found.');
        }

        // Restore stock quantity
        DB::table('p_purch_stock')
            ->where('stock_id', $rec->Stock_id)
            ->increment('Qty', $rec->qty);

        // Log the cancellation
        DB::table('jobc_cancelparts')->insert([
            'sa_shey'     => $table,
            'part_no'     => $rec->part_invoice_no ?? ($rec->cons_req_no ?? ''),
            'cancel_time' => now(),
            'issue_time'  => $rec->issue_time ?? now(),
            'stock_id'    => $rec->Stock_id,
            'qty'         => $rec->qty,
            'amount'      => $rec->total,
            'issue_by'    => $rec->issued_by ?? '',
            'cancel_by'   => $user,
            'RO'          => $rec->RO_no,
        ]);

        // Remove from jobcard table
        DB::table($table)->where($pk, $id)->delete();

        return back()->with('success', 'Part cancelled and stock restored successfully.');
    }

}
