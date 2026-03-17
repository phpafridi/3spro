<?php

namespace App\Http\Controllers\Finance;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AccountsController extends Controller
{
    // fin_mainaccounts: ma_id, main_account, ma_user, ma_datetime, ma_status, rang_start, rang_end
    // fin_gl:  GL_id, ma_id, GL_name, user, datetime, gl_status, rang_start, rang_end, GlCode
    // fin_gsl: GSL_ID, GL_id, GSL_code, GSL_name, Description, gsl_user, gsl_datetime, gsl_status, GLCode, GSLCode
    // fin_vch_mas:  mas_vch_id, VoucherNo, vchr_type, RefNo, VoucherDate, Payee, BookNo, UserName,
    //               A_T, Authenticate, Cancel, submiton, complete_submition, VType
    // fin_vch_chld: chld_vch_id, mas_vch_id, GSL_code, SNO, VoucherNo, RefNo, Department, GSL,
    //               Description, Debit, Credit, DM_TradeIn, DM_No, TradeIN_info, PBO_No, Investor,
    //               Variant, ModeOfPayment, Activity, mType, Unit, Region, vchr_type, user, submittime, payee
    // fin_dept: Code, Department, dep_auto

    private function voucherCounts(): array
    {
        $row = DB::table('fin_vch_mas')->selectRaw("
            SUM(CASE WHEN A_T = 'Forward'  THEN 1 ELSE 0 END) AS forwardCount,
            SUM(CASE WHEN A_T IS NULL      THEN 1 ELSE 0 END) AS foredit,
            SUM(CASE WHEN A_T = 'Reopened' THEN 1 ELSE 0 END) AS Reopened
        ")->first();
        return (array) $row;
    }

    // ─── Reports / Dashboard ──────────────────────────────────────────────────
    public function index()
    {
        $counts  = $this->voucherCounts();
        $gslList = DB::table('fin_gsl')->orderBy('GSL_code')->get();
        $glList  = DB::table('fin_gl')->orderBy('GL_name')->get();
        $depts   = DB::table('fin_dept')->orderBy('Department')->get();
        return view('finance.accounts.index', compact('counts','gslList','glList','depts'));
    }

    // ────────────────────────────────────────────────────────────────────────
    // FINANCIAL REPORTS  (all open in a new tab via POST forms)
    // ────────────────────────────────────────────────────────────────────────

    private function parseDateRange(?string $reservation): array
    {
        $parts = explode(' - ', trim($reservation ?? ''));
        $from  = date('Y-m-d', strtotime(trim($parts[0])));
        $to    = isset($parts[1]) ? date('Y-m-d', strtotime(trim($parts[1]))) : $from;
        return [$from, $to,
                date('d-M-y', strtotime($from)),
                date('d-M-y', strtotime($to))];
    }

    // 1. GL Trial Balances  (all GLs, as-on FROM date)
    public function reportTrialBalances(Request $request)
    {
        [$from] = $this->parseDateRange($request->reservation);

        $rows = DB::select("
            SELECT gl.GL_id, gl.rang_start, gl.rang_end, gl.GL_name,
                   COALESCE(SUM(ch.Debit),0)  AS Debit,
                   COALESCE(SUM(ch.Credit),0) AS Credit,
                   COALESCE(SUM(ch.Debit),0) - COALESCE(SUM(ch.Credit),0) AS TrialBalance
            FROM fin_gl gl
            LEFT JOIN fin_gsl gsl ON gl.GL_id = gsl.GL_id
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_vch_mas  mas ON ch.mas_vch_id = mas.mas_vch_id
            WHERE mas.VoucherDate <= ? AND mas.A_T = 'Yes'
            GROUP BY gl.GL_id ORDER BY gl.GL_id
        ", [$from]);

        return view('finance.accounts.reports.trial_balances',
            compact('rows') + ['asOn' => $from]);
    }

    // 2. GSL Trial Balance for a specific GL
    public function reportTrialBalGL(Request $request)
    {
        [$from, , $ff] = $this->parseDateRange($request->reservation);
        $glId   = $request->GL_name;          // GL_id sent from index form

        $rows = DB::select("
            SELECT gsl.GSL_ID, gsl.GSL_code, gsl.GSL_name,
                   COALESCE(SUM(ch.Debit),0)  AS Debit,
                   COALESCE(SUM(ch.Credit),0) AS Credit,
                   COALESCE(SUM(ch.Debit - ch.Credit),0) AS Balance
            FROM fin_gsl gsl
            JOIN fin_gl gl ON gsl.GL_id = gl.GL_id
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_vch_mas  mas ON ch.mas_vch_id = mas.mas_vch_id
            WHERE DATE(mas.VoucherDate) <= ? AND gl.GL_id = ? AND mas.A_T = 'Yes'
            GROUP BY gsl.GSL_code
        ", [$from, $glId]);

        $total = DB::selectOne("
            SELECT gl.rang_start, gl.rang_end, gl.GL_name,
                   COALESCE(SUM(ch.Debit),0)  AS Debit,
                   COALESCE(SUM(ch.Credit),0) AS Credit,
                   COALESCE(SUM(ch.Debit),0) - COALESCE(SUM(ch.Credit),0) AS TrialBalance
            FROM fin_gl gl
            LEFT JOIN fin_gsl gsl ON gl.GL_id = gsl.GL_id
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_vch_mas  mas ON ch.mas_vch_id = mas.mas_vch_id
            WHERE mas.VoucherDate <= ? AND gl.GL_id = ? AND mas.A_T = 'Yes'
            GROUP BY gl.GL_id
        ", [$from, $glId]);

        $reservation = $request->reservation;
        return view('finance.accounts.reports.trial_bal_gl',
            compact('rows','total','reservation') + ['asOn' => $ff]);
    }

    // 3. GSL Analytical Report (ledger for a single GSL code)
    public function reportGslReport(Request $request)
    {
        [$from, $to, $ff, $tt] = $this->parseDateRange($request->reservation);
        $gslCode = $request->GSL_code;
        $gslName = $request->GLS_name;

        // Opening balance (before $from)
        $openingRow = DB::selectOne("
            SELECT SUM(ch.Debit - ch.Credit) AS OpeningBalance
            FROM fin_vch_chld ch
            JOIN fin_vch_mas mas ON ch.mas_vch_id = mas.mas_vch_id
            WHERE DATE_FORMAT(mas.VoucherDate,'%Y-%m-%d') < ?
              AND ch.GSL_code = ? AND mas.A_T = 'Yes'
        ", [$from, $gslCode]);
        $opening = $openingRow->OpeningBalance ?? 0;

        $rows = DB::select("
            SELECT ch.Debit, ch.Credit, ch.Description, ch.RefNo, ch.vchr_type,
                   mas.VoucherDate
            FROM fin_vch_chld ch
            JOIN fin_vch_mas mas ON ch.mas_vch_id = mas.mas_vch_id
            WHERE DATE_FORMAT(mas.VoucherDate,'%Y-%m-%d') BETWEEN ? AND ?
              AND ch.GSL_code = ? AND mas.A_T = 'Yes'
            ORDER BY DATE(mas.VoucherDate) ASC
        ", [$from, $to, $gslCode]);

        $totalDr = collect($rows)->sum('Debit');
        $totalCr = collect($rows)->sum('Credit');

        return view('finance.accounts.reports.gsl_report', compact(
            'rows','opening','totalDr','totalCr','gslCode','gslName'
        ) + ['from' => $ff, 'to' => $tt]);
    }

    // 4. Voucher Type Report
    public function reportVoucherType(Request $request)
    {
        [$from, $to, $ff, $tt] = $this->parseDateRange($request->reservation);
        $vchType = $request->vch_type;

        $rows = DB::select("
            SELECT gl.GL_id, gl.rang_start, gl.rang_end, gl.GL_name,
                   COALESCE(SUM(ch.Debit),0)  AS TotalDebit,
                   COALESCE(SUM(ch.Credit),0) AS TotalCredit
            FROM fin_gl gl
            LEFT JOIN fin_gsl gsl ON gl.GL_id = gsl.GL_id
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_vch_mas  mas ON ch.mas_vch_id = mas.mas_vch_id
            WHERE mas.VoucherDate BETWEEN ? AND ? AND mas.vchr_type = ?
            GROUP BY gl.GL_id ORDER BY gl.GL_id
        ", [$from, $to, $vchType]);

        return view('finance.accounts.reports.voucher_type', compact('rows','vchType')
            + ['from' => $ff, 'to' => $tt]);
    }

    // 5. Profit & Loss
    public function reportProfitLoss(Request $request)
    {
        [$from, $to, $ff, $tt] = $this->parseDateRange($request->reservation);

        $income = DB::select("
            SELECT gsl.GSL_code, gsl.GSL_name,
                   COALESCE(SUM(CASE WHEN ma.main_account='Revenues' THEN ch.Credit - ch.Debit ELSE 0 END),0) AS TotalIncome
            FROM fin_gsl gsl
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_gl gl        ON gsl.GL_id = gl.GL_id
            LEFT JOIN fin_vch_mas mas  ON ch.mas_vch_id = mas.mas_vch_id
            LEFT JOIN fin_mainaccounts ma ON gl.ma_id = ma.ma_id
            WHERE mas.VoucherDate BETWEEN ? AND ? AND mas.A_T = 'Yes'
            GROUP BY gsl.GSL_code, gsl.GSL_name
            HAVING TotalIncome <> 0 ORDER BY gsl.GSL_code
        ", [$from, $to]);

        $expenses = DB::select("
            SELECT gsl.GSL_code, gsl.GSL_name,
                   COALESCE(SUM(CASE WHEN ma.main_account='Expenses' THEN ch.Debit - ch.Credit ELSE 0 END),0) AS TotalExpense
            FROM fin_gsl gsl
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_gl gl        ON gsl.GL_id = gl.GL_id
            LEFT JOIN fin_vch_mas mas  ON ch.mas_vch_id = mas.mas_vch_id
            LEFT JOIN fin_mainaccounts ma ON gl.ma_id = ma.ma_id
            WHERE mas.VoucherDate BETWEEN ? AND ? AND mas.A_T = 'Yes'
            GROUP BY gsl.GSL_code, gsl.GSL_name
            HAVING TotalExpense <> 0 ORDER BY gsl.GSL_code
        ", [$from, $to]);

        $totalIncome  = collect($income)->sum('TotalIncome');
        $totalExpense = collect($expenses)->sum('TotalExpense');
        $net          = $totalIncome - $totalExpense;

        return view('finance.accounts.reports.profit_loss', compact(
            'income','expenses','totalIncome','totalExpense','net'
        ) + ['from' => $ff, 'to' => $tt]);
    }

    // 6. P&L by Department
    public function reportProfitLossDept(Request $request)
    {
        [$from, $to, $ff, $tt] = $this->parseDateRange($request->reservation);
        $dept     = $request->dept;
        $deptName = $request->dept_name;

        $incomeRow = DB::selectOne("
            SELECT COALESCE(SUM(CASE WHEN ma.main_account='Revenues' THEN ch.Credit - ch.Debit ELSE 0 END),0) AS TotalIncome
            FROM fin_gsl gsl
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_gl gl        ON gsl.GL_id = gl.GL_id
            LEFT JOIN fin_vch_mas mas  ON ch.mas_vch_id = mas.mas_vch_id
            LEFT JOIN fin_mainaccounts ma ON gl.ma_id = ma.ma_id
            WHERE mas.VoucherDate BETWEEN ? AND ? AND ch.Department = ? AND mas.A_T = 'Yes'
        ", [$from, $to, $dept]);

        $expRow = DB::selectOne("
            SELECT COALESCE(SUM(CASE WHEN ma.main_account='Expenses' THEN ch.Debit - ch.Credit ELSE 0 END),0) AS TotalExpense
            FROM fin_gsl gsl
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_gl gl        ON gsl.GL_id = gl.GL_id
            LEFT JOIN fin_vch_mas mas  ON ch.mas_vch_id = mas.mas_vch_id
            LEFT JOIN fin_mainaccounts ma ON gl.ma_id = ma.ma_id
            WHERE mas.VoucherDate BETWEEN ? AND ? AND ch.Department = ? AND mas.A_T = 'Yes'
        ", [$from, $to, $dept]);

        $totalIncome  = $incomeRow->TotalIncome  ?? 0;
        $totalExpense = $expRow->TotalExpense     ?? 0;
        $net          = $totalIncome - $totalExpense;

        return view('finance.accounts.reports.profit_loss_dept', compact(
            'totalIncome','totalExpense','net','deptName'
        ) + ['from' => $ff, 'to' => $tt]);
    }

    // 7. P&L Overall (all departments)
    public function reportProfitLossOverall(Request $request)
    {
        [$from, $to, $ff, $tt] = $this->parseDateRange($request->reservation);

        $rows = DB::select("
            SELECT (SELECT fd.Department FROM fin_dept fd WHERE fd.Code = ch.Department) AS Department,
                   COALESCE(SUM(CASE WHEN ma.main_account='Expenses' THEN ch.Debit - ch.Credit ELSE 0 END),0) AS TotalExpense,
                   COALESCE(SUM(CASE WHEN ma.main_account='Revenues' THEN ch.Credit - ch.Debit ELSE 0 END),0) AS TotalRevenue,
                   (COALESCE(SUM(CASE WHEN ma.main_account='Revenues' THEN ch.Credit - ch.Debit ELSE 0 END),0) -
                    COALESCE(SUM(CASE WHEN ma.main_account='Expenses' THEN ch.Debit - ch.Credit ELSE 0 END),0)) AS ProfitLoss
            FROM fin_gsl gsl
            LEFT JOIN fin_gl gl        ON gsl.GL_id = gl.GL_id
            LEFT JOIN fin_vch_chld ch  ON gsl.GSL_code = ch.GSL_code
            LEFT JOIN fin_vch_mas mas  ON ch.mas_vch_id = mas.mas_vch_id
            LEFT JOIN fin_mainaccounts ma ON gl.ma_id = ma.ma_id
            WHERE mas.VoucherDate BETWEEN ? AND ? AND mas.A_T = 'Yes'
            GROUP BY ch.Department
            HAVING TotalExpense <> 0 OR TotalRevenue <> 0
            ORDER BY ch.Department
        ", [$from, $to]);

        $totalRev = collect($rows)->sum('TotalRevenue');
        $totalExp = collect($rows)->sum('TotalExpense');

        return view('finance.accounts.reports.profit_loss_overall', compact('rows','totalRev','totalExp')
            + ['from' => $ff, 'to' => $tt]);
    }

    // 8. Cash Flow Report (BRV/CRV receipts & payments by GL)
    public function reportCashFlow(Request $request)
    {
        [$from, $to, $ff, $tt] = $this->parseDateRange($request->reservation);

        $rows = DB::select("
            SELECT gl.GL_id, gl.GL_name,
                   COALESCE(SUM(ch.Debit),0)  AS totalDebit,
                   COALESCE(SUM(ch.Credit),0) AS totalCredit
            FROM fin_vch_mas vm
            JOIN fin_vch_chld ch  ON vm.mas_vch_id = ch.mas_vch_id
            JOIN fin_gsl gsl      ON ch.GSL_code = gsl.GSL_code
            JOIN fin_gl gl        ON gsl.GL_id = gl.GL_id
            WHERE vm.vchr_type IN ('BRV','CRV')
              AND DATE_FORMAT(vm.VoucherDate,'%Y-%m-%d') BETWEEN ? AND ?
            GROUP BY gl.GL_id
        ", [$from, $to]);

        // Opening balance (before period) for each GL
        $rows = collect($rows)->map(function ($row) use ($from) {
            $ob = DB::selectOne("
                SELECT COALESCE(SUM(ch.Debit - ch.Credit),0) AS opening
                FROM fin_vch_mas vm
                JOIN fin_vch_chld ch ON vm.mas_vch_id = ch.mas_vch_id
                JOIN fin_gsl gsl     ON ch.GSL_code = gsl.GSL_code
                JOIN fin_gl gl       ON gsl.GL_id = gl.GL_id
                WHERE DATE_FORMAT(vm.VoucherDate,'%Y-%m-%d') < ? AND gl.GL_id = ?
            ", [$from, $row->GL_id]);
            $row->opening = $ob->opening ?? 0;
            return $row;
        })->all();

        return view('finance.accounts.reports.cash_flow', compact('rows')
            + ['from' => $ff, 'to' => $tt]);
    }

    // 9. Cash Flow by GL (receipts/payments at GSL level for one GL)
    public function reportCashFlowGsl(Request $request)
    {
        [$from, $to, $ff, $tt] = $this->parseDateRange($request->reservation);
        $glId   = $request->GL_id;
        $glName = $request->GL_name;

        $rows = DB::select("
            SELECT ch.GSL_code, gsl.GSL_name,
                   COALESCE(SUM(ch.Debit),0)  AS totalDebit,
                   COALESCE(SUM(ch.Credit),0) AS totalCredit
            FROM fin_vch_mas vm
            JOIN fin_vch_chld ch ON vm.mas_vch_id = ch.mas_vch_id
            JOIN fin_gsl gsl     ON ch.GSL_code = gsl.GSL_code
            JOIN fin_gl gl       ON gsl.GL_id = gl.GL_id
            WHERE gl.GL_id = ? AND vm.vchr_type IN ('BRV','CRV')
              AND DATE_FORMAT(vm.VoucherDate,'%Y-%m-%d') BETWEEN ? AND ?
            GROUP BY gsl.GSL_code
        ", [$glId, $from, $to]);

        $netClosing = collect($rows)->sum(fn($r) => $r->totalCredit - $r->totalDebit);

        return view('finance.accounts.reports.cash_flow_gsl', compact('rows','glName','netClosing')
            + ['from' => $ff, 'to' => $tt]);
    }

    // ─── Generic Voucher Master Creator ──────────────────────────────────────
    private function createVoucher(Request $request, string $view, string $itemsRoute)
    {
        $counts = $this->voucherCounts();

        if ($request->filled('voucher_date')) {
            // Get next VoucherNo for this type
            $lastNo = DB::table('fin_vch_mas')
                ->where('vchr_type', $request->vouchertype)
                ->max('VoucherNo') ?? 0;

            $id = DB::table('fin_vch_mas')->insertGetId([
                'VoucherNo'          => $lastNo + 1,
                'vchr_type'          => $request->vouchertype,
                'RefNo'              => $request->voucherno,
                'VoucherDate'        => $request->voucher_date,
                'BookNo'             => $request->cash_book_no ?? '',
                'UserName'           => Auth::user()->login_id,
                'submiton'           => now(),
                'complete_submition' => now(),
                'VType'              => '',
            ]);

            return redirect()->route($itemsRoute, ['serial_number' => $id]);
        }

        return view("finance.accounts.$view", compact('counts'));
    }

    // ─── Voucher Items (shared logic) ─────────────────────────────────────────
    private function voucherItems(Request $request, string $view, string $submitRoute)
    {
        $serialNo = $request->serial_number;
        $user     = Auth::user()->login_id;
        $counts   = $this->voucherCounts();

        // Add a line item
        if ($request->filled('GSL_code')) {
            $gsl = DB::table('fin_gsl')->where('GSL_code', $request->GSL_code)->first();
            $master = DB::table('fin_vch_mas')->where('mas_vch_id', $serialNo)->first();

            DB::table('fin_vch_chld')->insert([
                'mas_vch_id'   => $serialNo,
                'GSL_code'     => $request->GSL_code,
                'GSL'          => $gsl ? $gsl->GSL_ID : 0,
                'Description'  => $request->Description ?? '',
                'Debit'        => $request->Debit  ?? 0,
                'Credit'       => $request->Credit ?? 0,
                'vchr_type'    => $master ? $master->vchr_type : '',
                'user'         => $user,
                'submittime'   => now(),
                'payee'        => $request->payee ?? '',
                'RefNo'        => $master ? $master->RefNo : '',
                'Department'   => $request->Department ?? 0,
                'mType'        => 1,
            ]);
        }

        // Submit / forward voucher for authentication
        if ($request->filled('Submitit')) {
            DB::table('fin_vch_mas')
                ->where('mas_vch_id', $serialNo)
                ->update(['A_T' => 'Forward', 'complete_submition' => now()]);

            return redirect()->route($submitRoute)->with('success', 'Voucher submitted for authentication.');
        }

        $master  = DB::table('fin_vch_mas')->where('mas_vch_id', $serialNo)->first();
        $items   = DB::table('fin_vch_chld')->where('mas_vch_id', $serialNo)->get();
        $gslList = DB::table('fin_gsl')->orderBy('GSL_code')->get();
        $depts   = DB::table('fin_dept')->orderBy('dep_auto')->get();

        return view("finance.accounts.$view",
            compact('master', 'items', 'gslList', 'depts', 'counts', 'serialNo'));
    }

    // ─── CPV ─────────────────────────────────────────────────────────────────
    public function cpv(Request $request)      { return $this->createVoucher($request, 'v_cp', 'accounts.cpv.items'); }
    public function cpvItems(Request $request) { return $this->voucherItems($request, 'v_cp_items', 'accounts.cpv'); }

    // ─── CRV ─────────────────────────────────────────────────────────────────
    public function crv(Request $request)      { return $this->createVoucher($request, 'v_cr', 'accounts.crv.items'); }
    public function crvItems(Request $request) { return $this->voucherItems($request, 'v_cr_items', 'accounts.crv'); }

    // ─── BPV ─────────────────────────────────────────────────────────────────
    public function bpv(Request $request)      { return $this->createVoucher($request, 'v_bp', 'accounts.bpv.items'); }
    public function bpvItems(Request $request) { return $this->voucherItems($request, 'v_bp_items', 'accounts.bpv'); }

    // ─── BRV ─────────────────────────────────────────────────────────────────
    public function brv(Request $request)      { return $this->createVoucher($request, 'v_br', 'accounts.brv.items'); }
    public function brvItems(Request $request) { return $this->voucherItems($request, 'v_br_items', 'accounts.brv'); }

    // ─── JV ──────────────────────────────────────────────────────────────────
    public function jv(Request $request)       { return $this->createVoucher($request, 'v_jv', 'accounts.jv.items'); }
    public function jvItems(Request $request)  { return $this->voucherItems($request, 'v_jv_items', 'accounts.jv'); }

    // ─── Pending Vouchers ─────────────────────────────────────────────────────
    public function pendingVouchers(Request $request)
    {
        $user   = Auth::user()->login_id;
        $pos    = Auth::user()->position ?? '';
        $counts = $this->voucherCounts();

        if ($request->filled('vch_status_cancel')) {
            DB::table('fin_vch_mas')->where('mas_vch_id', $request->vch_status_cancel)
                ->update(['A_T' => 'Trashed', 'Authenticate' => $user, 'complete_submition' => now()]);
        }

        if ($request->filled('Submitit')) {
            DB::table('fin_vch_mas')->where('mas_vch_id', $request->Submitit)
                ->update(['A_T' => 'Forward', 'complete_submition' => now()]);
        }

        $vouchers = DB::table('fin_vch_mas')
            ->when($pos !== 'FManager', fn($q) => $q->where('UserName', $user))
            ->whereNull('A_T')
            ->orderBy('mas_vch_id', 'desc')
            ->get();

        return view('finance.accounts.pending_vouchers', compact('vouchers', 'counts'));
    }

    // ─── Authenticate ─────────────────────────────────────────────────────────
    public function authenticate(Request $request)
    {
        $user   = Auth::user()->login_id;
        $counts = $this->voucherCounts();

        if ($request->filled('vch_status_change')) {
            DB::table('fin_vch_mas')->where('mas_vch_id', $request->vch_status_change)
                ->update(['A_T' => 'Yes', 'Authenticate' => $user]);
        }

        if ($request->filled('vch_status_cancel')) {
            DB::table('fin_vch_mas')->where('mas_vch_id', $request->vch_status_cancel)
                ->update(['A_T' => 'Cancel', 'Authenticate' => $user]);
        }

        $vouchers = DB::table('fin_vch_mas')
            ->where('A_T', 'Forward')
            ->orderBy('mas_vch_id', 'desc')
            ->get();

        return view('finance.accounts.authenticate', compact('vouchers', 'counts'));
    }

    // ─── Reopened Vouchers ────────────────────────────────────────────────────
    public function reopenedVouchers(Request $request)
    {
        $user   = Auth::user()->login_id;
        $counts = $this->voucherCounts();

        if ($request->filled('vch_status_cancel')) {
            DB::table('fin_vch_mas')->where('mas_vch_id', $request->vch_status_cancel)
                ->update(['A_T' => 'Trashed', 'Authenticate' => $user, 'complete_submition' => now()]);
        }

        if ($request->filled('Submitit')) {
            DB::table('fin_vch_mas')->where('mas_vch_id', $request->Submitit)
                ->update(['A_T' => 'Forward', 'complete_submition' => now()]);
        }

        $vouchers = DB::table('fin_vch_mas')
            ->where('A_T', 'Reopened')
            ->orderBy('mas_vch_id', 'desc')
            ->get();

        return view('finance.accounts.reopened_vouchers', compact('vouchers', 'counts'));
    }

    // ─── Search / Reopen Voucher ──────────────────────────────────────────────
    // fin_vch_mas_edit: request_id, request_reason, who_requested, request_DD, mas_vch_id,
    //                   VoucherNo, vchr_type, RefNo, VoucherDate, Payee, BookNo, UserName,
    //                   A_T, Authenticate, Cancel, submiton, complete_submition
    // fin_vch_chld_edit: id, request_id, chld_vch_id, mas_vch_id, ... (same as chld)
    public function search(Request $request)
    {
        $user    = Auth::user()->login_id;
        $counts  = $this->voucherCounts();
        $voucher = null;
        $items   = null;

        if ($request->filled('reopen_voucher')) {
            $masterId = $request->reopen_voucher;
            $master   = DB::table('fin_vch_mas')->where('mas_vch_id', $masterId)->first();

            if ($master) {
                $newReqId = DB::table('fin_vch_mas_edit')->insertGetId([
                    'request_reason'     => $request->reason,
                    'who_requested'      => $user,
                    'mas_vch_id'         => $master->mas_vch_id,
                    'VoucherNo'          => $master->VoucherNo,
                    'vchr_type'          => $master->vchr_type,
                    'RefNo'              => $master->RefNo,
                    'VoucherDate'        => $master->VoucherDate,
                    'Payee'              => $master->Payee,
                    'BookNo'             => $master->BookNo,
                    'UserName'           => $master->UserName,
                    'A_T'                => $master->A_T,
                    'Authenticate'       => $master->Authenticate,
                    'Cancel'             => $master->Cancel,
                    'submiton'           => $master->submiton,
                    'complete_submition' => $master->complete_submition,
                ]);

                // Copy child lines
                $children = DB::table('fin_vch_chld')->where('mas_vch_id', $masterId)->get();
                foreach ($children as $child) {
                    DB::table('fin_vch_chld_edit')->insert([
                        'request_id'   => $newReqId,
                        'chld_vch_id'  => $child->chld_vch_id,
                        'mas_vch_id'   => $child->mas_vch_id,
                        'GSL_code'     => $child->GSL_code,
                        'SNO'          => $child->SNO,
                        'VoucherNo'    => $child->VoucherNo,
                        'RefNo'        => $child->RefNo,
                        'Department'   => $child->Department,
                        'GSL'          => $child->GSL,
                        'Description'  => $child->Description,
                        'Credit'       => $child->Credit,
                        'Debit'        => $child->Debit,
                        'DM_TradeIn'   => $child->DM_TradeIn,
                        'DM_No'        => $child->DM_No,
                        'TradeIN_info' => $child->TradeIN_info,
                        'PBO_No'       => $child->PBO_No,
                        'Investor'     => $child->Investor,
                        'Variant'      => $child->Variant,
                        'ModeOfPayment'=> $child->ModeOfPayment,
                        'Activity'     => $child->Activity,
                        'mType'        => $child->mType,
                        'Unit'         => $child->Unit,
                        'Region'       => 0,
                        'vchr_type'    => $child->vchr_type,
                        'user'         => $user,
                        'submittime'   => now(),
                    ]);
                }

                return back()->with('success', 'Reopen request submitted successfully.');
            }
        }

        if ($request->filled('search_voucher')) {
            $voucher = DB::table('fin_vch_mas')->where('mas_vch_id', $request->search_voucher)->first();
            if ($voucher) {
                $items = DB::table('fin_vch_chld')->where('mas_vch_id', $voucher->mas_vch_id)->get();
            }
        }

        return view('finance.accounts.search', compact('voucher', 'items', 'counts'));
    }

    // ─── Charts of Accounts (COA) ─────────────────────────────────────────────
    public function coa(Request $request)
    {
        $counts = $this->voucherCounts();

        if ($request->filled('main_acount')) {
            DB::table('fin_mainaccounts')->insert([
                'main_account' => $request->main_acount,
                'rang_start'   => $request->rang_start,
                'rang_end'     => $request->rang_end,
                'ma_user'      => Auth::user()->login_id,
                'ma_status'    => 'Active',
                'ma_datetime'  => now(),
            ]);
            return back()->with('success', 'Main account added.');
        }

        $mainAccounts = DB::table('fin_mainaccounts')->orderBy('rang_start')->get();
        return view('finance.accounts.coa', compact('mainAccounts', 'counts'));
    }

    // ─── Add GL ───────────────────────────────────────────────────────────────
    public function addGL(Request $request)
    {
        $counts       = $this->voucherCounts();
        $mainAccounts = DB::table('fin_mainaccounts')->orderBy('rang_start')->get();

        // AJAX: return next available GL range for a chosen main account
        if ($request->filled('get_next_range')) {
            $ma = DB::table('fin_mainaccounts')->where('ma_id', $request->get_next_range)->first();
            if (!$ma) return response()->json(['error' => 'Not found'], 404);

            // Find last GL inside this main account's range
            $lastGL = DB::table('fin_gl')
                ->where('rang_start', '>=', $ma->rang_start)
                ->where('rang_end',   '<=', $ma->rang_end)
                ->orderBy('rang_end', 'desc')
                ->first();

            // GL ranges are 1000-wide
            $nextStart = $lastGL ? ($lastGL->rang_end + 1) : $ma->rang_start;
            $nextEnd   = $nextStart + 999;

            return response()->json(['rang_start' => $nextStart, 'rang_end' => $nextEnd]);
        }

        // Save new GL
        if ($request->filled('GL_name')) {
            DB::table('fin_gl')->insert([
                'ma_id'      => $request->ma_id,
                'GL_name'    => $request->GL_name,
                'GlCode'     => $request->GlCode,
                'rang_start' => $request->rang_start,
                'rang_end'   => $request->rang_end,
                'user'       => Auth::user()->login_id,
                'gl_status'  => 'Active',
                'datetime'   => now(),
            ]);
            return back()->with('success', 'GL added successfully.');
        }

        $filterMaId = $request->ma_id ?? null;
        $glQuery    = DB::table('fin_gl')->orderBy('rang_start');

        if ($filterMaId) {
            $ma = DB::table('fin_mainaccounts')->where('ma_id', $filterMaId)->first();
            if ($ma) {
                $glQuery->where('rang_start', '>=', $ma->rang_start)
                        ->where('rang_end',   '<=', $ma->rang_end);
            }
        }

        $glList = $glQuery->get();
        return view('finance.accounts.add_gl', compact('glList', 'mainAccounts', 'counts', 'filterMaId'));
    }

    // ─── Add GSL ──────────────────────────────────────────────────────────────
    public function addGSL(Request $request)
    {
        $counts = $this->voucherCounts();
        $glList = DB::table('fin_gl')->orderBy('rang_start')->get();

        // AJAX: return next available GSL code for a chosen GL
        if ($request->filled('get_next_gsl')) {
            $gl = DB::table('fin_gl')->where('GL_id', $request->get_next_gsl)->first();
            if (!$gl) return response()->json(['error' => 'Not found'], 404);

            $lastGSL = DB::table('fin_gsl')
                ->where('GSL_code', '>=', $gl->rang_start)
                ->where('GSL_code', '<=', $gl->rang_end)
                ->orderBy('GSL_code', 'desc')
                ->first();

            $nextCode = $lastGSL ? ($lastGSL->GSL_code + 1) : $gl->rang_start;
            return response()->json(['GSL_code' => $nextCode]);
        }

        // Save new GSL
        if ($request->filled('GSL_name')) {
            $gl = DB::table('fin_gl')->where('GL_id', $request->GL_id)->first();

            DB::table('fin_gsl')->insert([
                'GL_id'        => $request->GL_id,
                'GSL_code'     => $request->GSL_code,
                'GSL_name'     => $request->GSL_name,
                'Description'  => $request->Description ?? $request->GSL_name,
                'GLCode'       => $gl->GlCode ?? 0,
                'GSLCode'      => $request->GSL_code,
                'gsl_user'     => Auth::user()->login_id,
                'gsl_status'   => 'Active',
                'gsl_datetime' => now(),
            ]);
            return back()->with('success', 'GSL added successfully.');
        }

        $filterGlId = $request->GL_id ?? null;
        $gslQuery   = DB::table('fin_gsl')->orderBy('GSL_code');
        if ($filterGlId) $gslQuery->where('GL_id', $filterGlId);
        $gslList = $gslQuery->get();

        return view('finance.accounts.add_gsl', compact('gslList', 'glList', 'counts', 'filterGlId'));
    }

    // ─── Add Sub Head ─────────────────────────────────────────────────────────
    public function addSH(Request $request)
    {
        $counts  = $this->voucherCounts();
        $gslList = DB::table('fin_gsl')->orderBy('GSL_code')->get();

        if ($request->filled('SH_title')) {
            DB::table('fin_gsl')->insert([
                'SH_title' => $request->SH_title,
                'GSL_code' => $request->GSL_code,
            ]);
            return back()->with('success', 'Sub-head added.');
        }

        $shList = DB::table('fin_gsl')->orderBy('GSL_code')->get();
        return view('finance.accounts.add_sh', compact('shList', 'gslList', 'counts'));
    }
}
