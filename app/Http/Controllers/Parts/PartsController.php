<?php

namespace App\Http\Controllers\Parts;

use App\Http\Controllers\Controller;
use App\Models\PPart;
use App\Models\PJobber;
use App\Models\PJobberPayment;
use App\Models\PPurchInv;
use App\Models\PPurchStock;
use App\Models\PPurchReturn;
use App\Models\PSaleInv;
use App\Models\PSalePart;
use App\Models\PSaleReturn;
use App\Models\PPartsSubcat;
use App\Models\PTechIncentive;
use App\Models\CrAppointment;
use App\Models\JobcPart;
use App\Models\JobcConsumble;
use App\Models\JobcLabor;
use App\Models\SEstimate;
use App\Models\SEstPart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartsController extends Controller
{
    // ==================== DASHBOARD / INDEX ====================

    public function index()
    {
        // jobc_parts has no job_id — it joins jobcard via RO_no = jobcard.Jobc_id
        $workshopParts = DB::table('jobc_parts as jp')
            ->join('jobcard as jc', 'jp.RO_no', '=', 'jc.Jobc_id')
            ->join('p_parts as pp', 'jp.part_number', '=', 'pp.Part_no')
            ->select('jp.*', 'jc.RO_no as RO_display', 'jc.Customer_name', 'pp.Description', 'pp.Location')
            ->where('jp.status', '0')
            ->whereNull('jp.p_return')
            ->where('jc.status', '1')
            ->orderByDesc('jp.parts_sale_id')
            ->get();

        $workshopConsumbles = DB::table('jobc_consumble as jc2')
            ->join('jobcard as jc', 'jc2.RO_no', '=', 'jc.Jobc_id')
            ->select('jc2.*', 'jc.Customer_name', 'jc.RO_no as RO_display')
            ->where('jc2.status', '0')
            ->whereNull('jc2.p_return')
            ->where('jc.status', '1')
            ->orderByDesc('jc2.cons_sale_id')
            ->get();

        return view('parts.entry.index', compact('workshopParts', 'workshopConsumbles'));
    }

    // ==================== WORKSHOP RETURN ====================

    public function workshopReturn()
    {
        $returnParts = DB::table('jobc_parts as jp')
            ->join('jobcard as jc', 'jp.RO_no', '=', 'jc.Jobc_id')
            ->join('p_parts as pp', 'jp.part_number', '=', 'pp.Part_no')
            ->select('jp.*', 'jc.RO_no as RO_display', 'jc.Customer_name', 'pp.Description')
            ->where('jp.p_return', '1')
            ->orderByDesc('jp.parts_sale_id')
            ->get();

        $returnConsumbles = DB::table('jobc_consumble as jc2')
            ->join('jobcard as jc', 'jc2.RO_no', '=', 'jc.Jobc_id')
            ->select('jc2.*', 'jc.RO_no as RO_display', 'jc.Customer_name')
            ->where('jc2.p_return', '1')
            ->orderByDesc('jc2.cons_sale_id')
            ->get();

        return view('parts.entry.wp_return', compact('returnParts', 'returnConsumbles'));
    }

    public function workshopReturnUpdate(Request $request)
    {
        if ($request->has('not_available_id')) {
            DB::table('jobc_parts')
                ->where('parts_sale_id', $request->not_available_id)
                ->update(['p_return' => '2']);
        }

        if ($request->has('not_available_cons')) {
            DB::table('jobc_consumble')
                ->where('cons_sale_id', $request->not_available_cons)
                ->update(['p_return' => '2']);
        }

        return back()->with('success', 'Status updated successfully.');
    }

    // ==================== ESTIMATES ====================

    public function estimates()
    {
        $estimates = DB::table('s_estimates as se')
            ->join('vehicles_data as vd', 'se.veh_id', '=', 'vd.Vehicle_id')
            ->select('se.*', 'vd.Registration', 'vd.Variant')
            ->where('se.est_status', '0')
            ->orderByDesc('se.est_id')
            ->get();

        return view('parts.entry.estimates', compact('estimates'));
    }

    // ==================== UNCLOSED REQUISITIONS ====================

    public function unclosedRequisitions()
    {
        // jobc_labor queried directly — no jobcard join needed
        $unclosed = DB::table('jobc_labor as jl')
            ->select('jl.*')
            ->where('jl.status', '!=', 'Jobclose')
            ->orderByDesc('jl.Labor_id')
            ->get();

        return view('parts.entry.unclosed_req', compact('unclosed'));
    }

    public function closeRequisition(Request $request)
    {
        $request->validate(['Labor_id' => 'required|integer']);

        DB::table('jobc_labor')
            ->where('Labor_id', $request->Labor_id)
            ->update(['status' => 'Jobclose', 'end_time' => now()]);

        return back()->with('success', 'Requisition closed successfully.');
    }

    // ==================== PURCHASE ====================

    public function purchase()
    {
        $jobbers = PJobber::orderBy('jbr_name')->get();
        return view('parts.entry.purchase', compact('jobbers'));
    }

    public function purchaseStore(Request $request)
    {
        $request->validate([
            'required_jobber'         => 'required|string',
            'required_preq'           => 'required|string',
            'required_payment_method' => 'required|string',
        ]);

        $inv = PPurchInv::create([
            'Invoice_number'  => $request->invo,
            'jobber'          => $request->required_jobber,
            'payment_method'  => $request->required_payment_method,
            'Purchase_Requis' => $request->required_preq,
            'deleverynote'    => $request->deleverynote,
            'consignmentnote' => $request->consignmentnote,
            'Receivername'    => $request->Receivername,
            'mdate'           => $request->mdate ?? now()->toDateString(),
            'user'            => session('login_id'),
        ]);

        return redirect()->route('parts.purchase.detail', $inv->Invoice_no)
            ->with('success', 'Purchase invoice created. Now add parts.');
    }

    public function purchaseDetail($invoice_no)
    {
        $invoice = PPurchInv::with('jobber')->findOrFail($invoice_no);
        $stocks  = PPurchStock::where('Invoice_no', $invoice_no)->get();
        $parts   = PPart::orderBy('Part_no')->get();

        return view('parts.entry.pur_det', compact('invoice', 'stocks', 'parts'));
    }

    public function purchaseDetailStore(Request $request, $invoice_no)
    {
        $request->validate([
            'typeahead'       => 'required|string',
            'required_qty'    => 'required|numeric|min:0.01',
            'required_uprice' => 'required|numeric|min:0',
        ]);

        $qty       = $request->required_qty;
        $uprice    = $request->required_uprice;
        $netamount = $qty * $uprice;

        PPurchStock::create([
            'Invoice_no'  => $invoice_no,
            'part_no'     => $request->typeahead,
            'Description' => $request->desc,
            'unit'        => $request->unit,
            'quantity'    => $qty,
            'remain_qty'  => $qty,
            'Price'       => $uprice,
            'Netamount'   => $netamount,
            'cate_type'   => $request->category,
            'user'        => session('login_id'),
        ]);

        $total = PPurchStock::where('Invoice_no', $invoice_no)->sum('Netamount');
        PPurchInv::where('Invoice_no', $invoice_no)->update(['Total_amount' => $total]);

        return back()->with('success', 'Part added to invoice.');
    }

    public function purchaseDetailView($invoice_no)
    {
        $invoice = PPurchInv::with('jobber', 'stockItems')->findOrFail($invoice_no);
        return view('parts.entry.pur_det2', compact('invoice'));
    }

    public function purchaseEdit(Request $request)
    {
        $request->validate([
            'edit_invoice_no' => 'required',
            'edit_stock_id'   => 'required',
            'required_qty'    => 'required|numeric',
            'required_uprice' => 'required|numeric',
        ]);

        $invoice_no = $request->edit_invoice_no;
        $netprice   = $request->required_qty * $request->required_uprice;

        PPurchStock::where('stock_id', $request->edit_stock_id)->update([
            'part_no'     => $request->typeahead,
            'Description' => $request->desc,
            'unit'        => $request->unit,
            'quantity'    => $request->required_qty,
            'Price'       => $request->required_uprice,
            'Netamount'   => $netprice,
            'cate_type'   => $request->category,
        ]);

        $total = PPurchStock::where('Invoice_no', $invoice_no)->sum('Netamount');
        PPurchInv::where('Invoice_no', $invoice_no)->update(['Total_amount' => $total]);

        return redirect()->route('parts.purchase.detail.view', $invoice_no)
            ->with('success', 'Stock item updated.');
    }

    // ==================== PURCHASE RETURN ====================

    public function purchaseReturn()
    {
        $maxPRJV = PPurchReturn::max('PRJV') + 1;
        return view('parts.entry.purchase_return', compact('maxPRJV'));
    }

    public function purchaseReturnStore(Request $request)
    {
        $request->validate([
            'GRN'               => 'required|string',
            'required_stock_id' => 'required',
            'required_qty'      => 'required|numeric|min:1',
            'return_by'         => 'required|string',
            'PRJV'              => 'required|integer',
        ]);

        PPurchStock::where('stock_id', $request->required_stock_id)
            ->decrement('remain_qty', $request->required_qty);
        PPurchStock::where('stock_id', $request->required_stock_id)
            ->update(['purch_return' => '1']);

        PPurchReturn::create([
            'PRJV'       => $request->PRJV,
            'invoice_no' => $request->GRN,
            'stock_id'   => $request->required_stock_id,
            'unit_price' => $request->unit_price,
            'return_qty' => $request->required_qty,
            'return_by'  => $request->return_by,
            'reason'     => $request->reason,
            'user'       => session('login_id'),
        ]);

        return redirect()->route('parts.print.purchase-return', ['invoice_no' => $request->PRJV])
            ->with('success', 'Purchase return recorded.');
    }

    // ==================== SALE ====================

    public function sale()
    {
        $jobbers = PJobber::orderBy('jbr_name')->get();
        $maxInv  = PSaleInv::max('sale_inv') + 1;
        return view('parts.entry.sale_part', compact('jobbers', 'maxInv'));
    }

    public function saleStore(Request $request)
    {
        $request->validate([
            'required_jobber' => 'required|string',
            'payment_method'  => 'required|string',
        ]);

        $inv = PSaleInv::create([
            'Jobber'         => $request->required_jobber,
            'payment_method' => $request->payment_method,
            'user'           => session('login_id'),
        ]);

        return redirect()->route('parts.sale.invoice', $inv->sale_inv);
    }

    public function saleInvoice($sale_inv)
    {
        $invoice = PSaleInv::findOrFail($sale_inv);
        $parts   = PSalePart::where('sale_inv', $sale_inv)->get();

        return view('parts.entry.sale.sale_inv', compact('invoice', 'parts'));
    }

    public function salePartStore(Request $request)
    {
        $request->validate([
            'sale_inv'   => 'required|integer',
            'stock_id'   => 'required|integer',
            'quantity'   => 'required|numeric|min:1',
            'sale_price' => 'required|numeric|min:0',
        ]);

        $netamount = $request->quantity * $request->sale_price;

        PSalePart::create([
            'sale_inv'   => $request->sale_inv,
            'stock_id'   => $request->stock_id,
            'part_no'    => $request->part_no,
            'quantity'   => $request->quantity,
            'sale_price' => $request->sale_price,
            'netamount'  => $netamount,
            'remain_qty' => $request->quantity,
            'user'       => session('login_id'),
        ]);

        PPurchStock::where('stock_id', $request->stock_id)
            ->decrement('remain_qty', $request->quantity);

        $total = PSalePart::where('sale_inv', $request->sale_inv)->sum('netamount');
        PSaleInv::where('sale_inv', $request->sale_inv)->update(['Total_amount' => $total]);

        return back()->with('success', 'Part added to sale.');
    }

    // ==================== SALE RETURN ====================

    public function saleReturn()
    {
        $maxSRJV = PSaleReturn::max('SRJV') + 1;
        return view('parts.entry.sale_return', compact('maxSRJV'));
    }

    public function saleReturnStore(Request $request)
    {
        $request->validate([
            'GRN'               => 'required|string',
            'required_stock_id' => 'required',
            'sell_id'           => 'required',
            'required_qty'      => 'required|numeric|min:1',
            'return_by'         => 'required|string',
            'SRJV'              => 'required|integer',
        ]);

        PPurchStock::where('stock_id', $request->required_stock_id)
            ->increment('remain_qty', $request->required_qty);

        PSalePart::where('sell_id', $request->sell_id)->update([
            'SRJV_return' => $request->SRJV,
            'remain_qty'  => DB::raw("remain_qty - " . (int)$request->required_qty),
        ]);

        PSaleReturn::create([
            'SRJV'       => $request->SRJV,
            'invoice_no' => $request->GRN,
            'stock_id'   => $request->required_stock_id,
            'sell_id'    => $request->sell_id,
            'unit_price' => $request->unit_price,
            'return_qty' => $request->required_qty,
            'return_by'  => $request->return_by,
            'reason'     => $request->reason,
            'user'       => session('login_id'),
        ]);

        return redirect()->route('parts.print.sale-return', ['invoice_no' => $request->SRJV])
            ->with('success', 'Sale return recorded.');
    }

    // ==================== VENDOR PAYMENTS ====================

    public function jobberPayment()
    {
        $jobbers = PJobber::orderBy('jbr_name')->get();
        return view('parts.entry.jobberpayment', compact('jobbers'));
    }

    public function jobberPaymentStore(Request $request)
    {
        $request->validate([
            'jobber'         => 'required',
            'trans_type'     => 'required|in:Paid,Received',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'required|string',
            'rec_paid_by'    => 'required|string',
        ]);

        $payment = PJobberPayment::create([
            'jobber'         => $request->jobber,
            'trans_type'     => $request->trans_type,
            'amount'         => $request->amount,
            'payment_method' => $request->payment_method,
            'rec_paid_by'    => $request->rec_paid_by,
            'remarks'        => $request->remarks,
            'user'           => session('login_id'),
        ]);

        if ($request->trans_type === 'Paid') {
            PJobber::where('jobber_id', $request->jobber)->update([
                'Balance_status' => DB::raw("Balance_status + " . (float)$request->amount),
                'latest_update'  => "Payment_Paid:{$request->amount}",
                'last_update'    => now(),
            ]);
        } else {
            PJobber::where('jobber_id', $request->jobber)->update([
                'Balance_status' => DB::raw("Balance_status - " . (float)$request->amount),
                'latest_update'  => "Payment_Received:{$request->amount}",
                'last_update'    => now(),
            ]);
        }

        return redirect()->route('parts.print.payment', $payment->payment_id)
            ->with('success', 'Payment recorded.');
    }

    // ==================== NEW PART ====================

    public function newPart()
    {
        return view('parts.entry.new_part');
    }

    public function newPartStore(Request $request)
    {
        $allowedUsers = ['Mashkoor', 'Swahid'];
        if (!in_array(session('login_id'), $allowedUsers)) {
            return back()->with('error', 'You are not authorized to add parts.');
        }

        $request->validate([
            'partnumber'  => 'required|string|unique:p_parts,Part_no',
            'description' => 'required|string',
            'catetype'    => 'required|string',
        ]);

        PPart::create([
            'Part_no'     => $request->partnumber,
            'Description' => $request->description,
            'catetype'    => $request->catetype,
            'part_type'   => $request->parttype,
            'Model'       => $request->modelcode,
            'ReOrder'     => $request->reorder,
            'Location'    => $request->Location,
            'user'        => session('login_id'),
        ]);

        return back()->with('success', 'Part number added successfully.');
    }

    // ==================== NEW JOBBER ====================

    public function newJobber()
    {
        $jobbers = PJobber::orderBy('jbr_name')->get();
        return view('parts.entry.new_jobber', compact('jobbers'));
    }

    public function newJobberStore(Request $request)
    {
        $request->validate([
            'jobber' => 'required|string|unique:p_jobber,jbr_name',
        ]);

        PJobber::create([
            'jbr_name' => $request->jobber,
            'Job_cust' => $request->cust_jobber,
            'person'   => $request->contactperson,
            'contact'  => $request->contact,
            'address'  => $request->address,
            'email'    => $request->email,
            'cnic'     => $request->CNIC,
            'user'     => session('login_id'),
        ]);

        return back()->with('success', 'Jobber/Vendor added successfully.');
    }

    // ==================== NEW CATEGORY PART ====================

    public function newCatePart()
    {
        $categories = DB::table('p_parts_subcat')
            ->select('category', 'subcategory', 'partnumber', 'id')
            ->orderByDesc('id')
            ->get();

        return view('parts.entry.new_cate_part', compact('categories'));
    }

    public function newCatePartStore(Request $request)
    {
        $request->validate([
            'typeahead'   => 'required|string',
            'subcategory' => 'required|string',
            'category'    => 'required|string',
        ]);

        if (PPartsSubcat::where('partnumber', $request->typeahead)->exists()) {
            return back()->with('error', 'The submitted Part# is already added.');
        }

        PPartsSubcat::create([
            'category'    => $request->category,
            'subcategory' => $request->subcategory,
            'partnumber'  => $request->typeahead,
        ]);

        return back()->with('success', 'Category part added successfully.');
    }

    public function newCatePartDelete(Request $request)
    {
        PPartsSubcat::destroy($request->id);
        return back()->with('success', 'Record deleted.');
    }

    // ==================== EDIT LOCATION ====================

    public function locationChange()
    {
        return view('parts.entry.location_change');
    }

    public function locationChangeUpdate(Request $request)
    {
        $request->validate([
            'typeahead' => 'required|string',
            'location'  => 'required|string',
        ]);

        PPart::where('Part_no', $request->typeahead)
            ->update(['Location' => $request->location]);

        return back()->with('success', 'Location updated successfully.');
    }

    // ==================== INCENTIVES ====================

    public function incentives()
    {
        $incentives = DB::table('p_tech_incentive')
            ->orderByDesc('id')
            ->get();

        return view('parts.entry.incentives', compact('incentives'));
    }

    // ==================== APPOINTMENTS ====================

    public function appointments()
    {
        $appointments = DB::table('cr_appointments')
            ->select(
                'app_id',
                'veh_id',
                'CustomerName',
                'Mobile',
                'job_nature',
                'cust_id',
                'source',
                'veh_rec',
                'veh_details',
                'VOC',
                'parts',
                'Parts_cost',
                'appt_datetime',
                'CRO',
                'Variant',
                'parts_status'
            )
            ->whereIn('parts_status', ['0', '1'])
            ->orderByDesc('app_id')
            ->get();

        return view('parts.entry.appointments', compact('appointments'));
    }

    public function appointmentUpdateStatus(Request $request)
    {
        $user = session('login_id');

        if ($request->has('availble')) {
            DB::table('cr_appointments')
                ->where('app_id', $request->availble)
                ->update(['parts_status' => '2', 'parts_user' => $user, 'parts_datatime' => now()]);
        } elseif ($request->has('notavailable')) {
            DB::table('cr_appointments')
                ->where('app_id', $request->notavailable)
                ->update(['parts_status' => '3', 'parts_user' => $user, 'parts_datatime' => now()]);
        }

        return back()->with('success', 'Appointment status updated.');
    }

    // ==================== SEARCH ====================

    public function search()
    {
        return view('parts.entry.search');
    }

    public function searchRedirect(Request $request)
    {
        $field  = $request->field;
        $search = $request->search;

        $routes = [
            'counter-sale'  => route('parts.print.sale-invoice', ['inv_no' => $search]),
            'purch-inv'     => route('parts.print.purchase', ['invoice_no' => $search]),
            'purch-inv-tax' => route('parts.print.purchase', ['invoice_no' => $search, 'tax' => 1]),
            'PRJV'          => route('parts.print.purchase-return', ['invoice_no' => $search]),
            'SRJV'          => route('parts.print.sale-return', ['invoice_no' => $search]),
            'WPR'           => route('parts.print.wp-return', ['WPR' => $search]),
            'Payment'       => route('parts.print.payment', $search),
        ];

        if (isset($routes[$field])) {
            return redirect($routes[$field]);
        }

        return back()->with('error', 'Invalid search type.');
    }

    // ==================== PRINT / REQUISITION ====================

    public function printRequisition()
    {
        $jobcards = DB::table('jobcard')
            ->where('status', 'InProgress')
            ->orderByDesc('job_id')
            ->get();

        return view('parts.entry.print_requision', compact('jobcards'));
    }

    public function printRequisitionRedirect(Request $request)
    {
        $inv = $request->model;
        if ($request->has('consumble')) {
            return redirect()->route('parts.print.issue-cons', ['inv_id' => $inv]);
        }
        return redirect()->route('parts.print.issue-part', ['inv_id' => $inv]);
    }

    // ==================== REPORTS ====================

    public function reports()
    {
        return view('parts.entry.reports');
    }

    public function kpiReport()
    {
        return view('parts.entry.KPI_report');
    }

    public function dpokReport()
    {
        return view('parts.entry.report_Dpok');
    }

    // ==================== AJAX SEARCH ENDPOINTS ====================

    public function searchPart(Request $request)
    {
        $key   = $request->key ?? '';
        $parts = PPart::where('Part_no', 'LIKE', "%{$key}%")
            ->orWhere('Description', 'LIKE', "%{$key}%")
            ->select('Part_no', 'Description', 'Location')
            ->limit(15)
            ->get();

        return response()->json($parts->map(fn($p) => [
            'value' => $p->Part_no,
            'label' => "{$p->Part_no} - {$p->Description}",
            'desc'  => $p->Description,
        ]));
    }

    public function searchStock(Request $request)
    {
        $key    = $request->key ?? '';
        $stocks = DB::table('p_purch_stock as ps')
            ->join('p_parts as pp', 'ps.part_no', '=', 'pp.Part_no')
            ->where(function ($q) use ($key) {
                $q->where('ps.part_no', 'LIKE', "%{$key}%")
                    ->orWhere('ps.Description', 'LIKE', "%{$key}%");
            })
            ->where('ps.remain_qty', '>', 0)
            ->select('ps.stock_id', 'ps.part_no', 'ps.Description', 'ps.remain_qty', 'ps.Price', 'pp.Location')
            ->orderByDesc('ps.stock_id')
            ->limit(15)
            ->get();

        return response()->json($stocks->map(fn($s) => [
            'value'      => $s->stock_id,
            'label'      => "{$s->part_no} - {$s->Description} (Qty: {$s->remain_qty})",
            'part_no'    => $s->part_no,
            'desc'       => $s->Description,
            'remain_qty' => $s->remain_qty,
            'price'      => $s->Price,
        ]));
    }

    public function checkInvoice(Request $request)
    {
        $exists = PPurchInv::where('Invoice_number', $request->NIC)
            ->where('jobber', $request->jobber)
            ->exists();

        echo $exists
            ? "<span style='color:red'>Invoice# already exists for this Jobber!</span>"
            : 'OK';
    }

    public function searchSaleInvoice(Request $request)
    {
        $invoice = PSaleInv::with('parts')->find($request->inv_no);

        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }

        return view('parts.entry.sale.files.print_sale_inv', compact('invoice'));
    }

    public function searchPurchaseInvoice(Request $request)
    {
        $invoice = PPurchInv::with('stockItems', 'jobber')->find($request->invoice_no);

        if (!$invoice) {
            abort(404);
        }

        return view('parts.entry.sale.files.print_purch', compact('invoice'));
    }

    // ==================== PRINT PAGES ====================

    public function printSaleInvoice($inv_no)
    {
        $invoice = PSaleInv::with('parts')->findOrFail($inv_no);
        return view('parts.entry.sale.files.print_sale_inv', compact('invoice'));
    }

    public function printPurchase($invoice_no)
    {
        $invoice = PPurchInv::with('stockItems', 'jobber')->findOrFail($invoice_no);
        $tax     = request('tax', 0);
        return view('parts.entry.sale.files.print_purch', compact('invoice', 'tax'));
    }

    public function printPurchaseReturn($invoice_no)
    {
        $return = PPurchReturn::where('PRJV', $invoice_no)->with('stockItem')->get();
        return view('parts.entry.sale.files.print_purch_return', compact('return', 'invoice_no'));
    }

    public function printSaleReturn($invoice_no)
    {
        $return = PSaleReturn::where('SRJV', $invoice_no)->get();
        return view('parts.entry.sale.files.print_sale_return', compact('return', 'invoice_no'));
    }

    public function printWpReturn($WPR = null)
    {
        $parts = collect();
        if ($WPR) {
            $parts = DB::table('jobc_parts')->where('parts_sale_id', $WPR)->get();
        }
        return view('parts.entry.sale.files.print_wp_return', compact('parts', 'WPR'));
    }

    public function printPayment($payment_id)
    {
        $payment = PJobberPayment::with('jobber')->findOrFail($payment_id);
        return view('parts.entry.sale.files.print_payment', compact('payment'));
    }

    public function printIssuePart($inv_id)
    {
        // inv_id = RO_no (Jobc_id) — filter by RO_no, not job_id
        $parts = DB::table('jobc_parts as jp')
            ->join('jobcard as jc', 'jp.RO_no', '=', 'jc.Jobc_id')
            ->join('p_parts as pp', 'jp.part_number', '=', 'pp.Part_no')
            ->where('jp.RO_no', $inv_id)
            ->select('jp.*', 'jc.RO_no as RO_display', 'jc.Customer_name', 'pp.Description', 'pp.Location')
            ->get();

        return view('parts.entry.sale.files.print_issue_part', compact('parts', 'inv_id'));
    }

    public function printIssueCons($inv_id)
    {
        // Same — filter by RO_no
        $consumbles = DB::table('jobc_consumble as jc2')
            ->join('jobcard as jc', 'jc2.RO_no', '=', 'jc.Jobc_id')
            ->where('jc2.RO_no', $inv_id)
            ->select('jc2.*', 'jc.RO_no as RO_display', 'jc.Customer_name')
            ->get();

        return view('parts.entry.sale.files.issue_cons_print', compact('consumbles', 'inv_id'));
    }
}
