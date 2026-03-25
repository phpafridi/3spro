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
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PartsController extends Controller
{
    // ==================== DASHBOARD / INDEX ====================

    public function index()
    {
        $workshopParts = DB::table('jobc_parts as jp')
            ->join('jobcard as jc', 'jp.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->leftJoin('p_parts as pp', 'jp.part_number', '=', 'pp.Part_no')
            ->select(
                'jp.*',
                'jp.parts_sale_id',
                'jp.RO_no',
                'jp.part_description',
                'jp.issued_qty',
                'jp.req_qty',
                'jp.qty',
                'jp.unitprice',
                'jp.total',
                'jp.entry_datetime',
                'jc.Customer_name',
                'jc.Veh_reg_no',
                'jc.SA',
                'jc.comp_appointed',
                'jc.cust_source',
                'v.Variant',
                'v.Model',
                DB::raw('COALESCE(pp.Description, jp.part_description) as Description'),
                DB::raw('COALESCE(pp.Location, "N/A") as Location'),
                DB::raw("TIME_FORMAT(TIMEDIFF(NOW(), jp.entry_datetime), '%H:%i:%s') as time_elapsed"),
                DB::raw("DATE_FORMAT(jp.entry_datetime, '%d %b %h:%i %p') as booking_time")
            )
            ->where('jp.status', 0)
            ->where('jc.status', 1)
            ->orderByDesc('jp.parts_sale_id')
            ->get();

        $workshopConsumbles = DB::table('jobc_consumble as jc2')
            ->join('jobcard as jc', 'jc2.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->select(
                'jc2.*',
                'jc2.cons_sale_id',
                'jc2.RO_no',
                'jc2.cons_description',
                'jc2.issued_qty',
                'jc2.req_qty',
                'jc2.qty',
                'jc2.unitprice',
                'jc2.total',
                'jc2.entry_datetime',
                'jc.Customer_name',
                'jc.Veh_reg_no',
                'jc.SA',
                'jc.comp_appointed',
                'jc.cust_source',
                'v.Variant',
                'v.Model',
                DB::raw("TIME_FORMAT(TIMEDIFF(NOW(), jc2.entry_datetime), '%H:%i:%s') as time_elapsed"),
                DB::raw("DATE_FORMAT(jc2.entry_datetime, '%d %b %h:%i %p') as booking_time")
            )
            ->where('jc2.status', 0)
            ->where('jc.status', 1)
            ->orderByDesc('jc2.cons_sale_id')
            ->get();

        $invoiceNumbers = DB::table('jobc_parts')
            ->where('part_invoice_no', '!=', 0)
            ->where('part_invoice_no', '!=', '')
            ->where('issue_by', '')
            ->select('part_invoice_no', 'RO_no')
            ->distinct()
            ->get()
            ->groupBy('RO_no');

        $reqNumbers = DB::table('jobc_consumble')
            ->where('cons_req_no', '!=', 0)
            ->where('cons_req_no', '!=', '')
            ->where('issue_by', '')
            ->select('cons_req_no', 'RO_no')
            ->distinct()
            ->get()
            ->groupBy('RO_no');

        \Log::info('Workshop Requisitions', [
            'parts_found'       => $workshopParts->count(),
            'consumables_found' => $workshopConsumbles->count(),
            'jobcards_status_1' => DB::table('jobcard')->where('status', 1)->count(),
            'parts_status_0'    => DB::table('jobc_parts')->where('status', 0)->count(),
        ]);

        return view('parts.entry.index', compact('workshopParts', 'workshopConsumbles', 'invoiceNumbers', 'reqNumbers'));
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
            'Invoice_number'   => $request->invo,
            'jobber'           => $request->required_jobber,
            'payment_method'   => $request->required_payment_method,
            'Purchase_Requis'  => $request->required_preq,
            'deleverynote'     => $request->deleverynote,
            'consignmentnote'  => $request->consignmentnote,
            'Receivername'     => $request->Receivername,
            'mdate'            => $request->mdate ?? now()->toDateString(),
            'Total_amount'     => 0,
            'status'           => 1,
            'user'             => session('login_id'),
            'date'             => now(),
            'created_at'       => now(),
            'updated_at'       => now(),
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
            'typeahead'        => 'required|string',
            'required_qty'     => 'required|numeric|min:0.01',
            'required_uprice'  => 'required|numeric|min:0',
        ]);

        $qty       = $request->required_qty;
        $uprice    = $request->required_uprice;
        $netamount = $qty * $uprice;

        $partInfo = DB::table('p_parts')->where('Part_no', $request->typeahead)->first();

        PPurchStock::create([
            'Invoice_no'  => $invoice_no,
            'part_no'     => $request->typeahead,
            'Description' => $request->desc ?? ($partInfo->Description ?? ''),
            'unit'        => $request->unit ?? 'Pcs',
            'quantity'    => $qty,
            'remain_qty'  => $qty,
            'Price'       => $uprice,
            'Netamount'   => $netamount,
            'cate_type'   => $request->category ?? ($partInfo->catetype ?? ''),
            'discount'    => 0,
            'tax'         => 0,
            'purch_return'=> 0,
            'Model'       => $partInfo->Model ?? '',
            'location'    => $partInfo->Location ?? '',
            'user'        => session('login_id'),
            'date'        => now(),
            'created_at'  => now(),
            'updated_at'  => now(),
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
            'edit_invoice_no'  => 'required',
            'edit_stock_id'    => 'required',
            'required_qty'     => 'required|numeric',
            'required_uprice'  => 'required|numeric',
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

    /**
     * GET /sale — Show blank "create invoice" form (Step 1)
     */
    public function sale()
    {
        $jobbers = PJobber::orderBy('jbr_name')->get();
        return view('parts.entry.sale_part', compact('jobbers'));
    }

    /**
     * POST /sale — Create a new sale invoice header, then redirect to add-parts page
     * FIX: Only creates header — no part logic here. Uses insertGetId to get new PK reliably.
     */
    public function saleStore(Request $request)
    {
        $request->validate([
            'required_jobber'  => 'required|string',
            'payment_method'   => 'required|string',
        ]);

        $inv = DB::table('p_sale_inv')->insertGetId([
            'Jobber'         => $request->required_jobber,
            'payment_method' => $request->payment_method,
            'user'           => session('login_id'),
            'datetime'       => now(),
            'Total_amount'   => 0,
            'discount'       => 0,
            'tax'            => 0,
            'status'         => 0,
            'remarks'        => '',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        // Redirect to the add-parts page — prevents duplicate invoice on browser refresh
        return redirect()->route('parts.sale.add', $inv)
            ->with('success', 'Invoice #' . $inv . ' created. Now add parts.');
    }

    /**
     * GET /sale/{sale_inv}/add — Show add-part form for an existing invoice (Step 2)
     */
    public function saleAdd($sale_inv)
    {
        $invoice = PSaleInv::findOrFail($sale_inv);
        $jobbers = PJobber::orderBy('jbr_name')->get();
        $parts   = DB::table('p_sale_part')->where('sale_inv', $sale_inv)->get();
        return view('parts.entry.sale_part', compact('jobbers', 'sale_inv', 'invoice', 'parts'));
    }

    /**
     * POST /sale/{sale_inv}/add — Add a part to an existing invoice
     *
     * FIX 1: netamount formula corrected.
     *   Original PHP:  $required_netprice = $_POST["required_netprice"] - $tax
     *   i.e. the form sends (qty*price - discount + tax) as required_netprice,
     *   then the insert does netamount = required_netprice - tax
     *   = (qty*price - discount + tax) - tax = qty*price - discount
     *   So: netamount = gross - discount  (tax is stored separately, NOT deducted from netamount)
     *
     * FIX 2: Redirect after POST — prevents duplicate part row on refresh.
     */
    public function saleAddPart(Request $request, $sale_inv)
    {
        $request->validate([
            'required_stock_id' => 'required|integer',
            'typeahead'         => 'required|string',
            'required_qty'      => 'required|numeric|min:1',
            'required_uprice'   => 'required|numeric|min:0',
        ]);

        $stockId   = (int)   $request->required_stock_id;
        $qty       = (float) $request->required_qty;
        $unitPrice = (float) $request->required_uprice;
        $discount  = (float) ($request->discount  ?? 0);
        $tax       = (float) ($request->tax       ?? 0);

        // FIX: correct formula — netamount = gross - discount  (matches original PHP exactly)
        $gross     = $qty * $unitPrice;
        $netamount = $gross - $discount;

        // Check stock availability
        $stock = DB::table('p_purch_stock')->where('stock_id', $stockId)->first();
        if (!$stock || $stock->remain_qty < $qty) {
            return back()->withInput()->with('error', 'Not enough stock! Available: ' . ($stock->remain_qty ?? 0));
        }

        DB::table('p_sale_part')->insert([
            'sale_inv'    => (int) $sale_inv,
            'stock_id'    => $stockId,
            'part_no'     => $request->typeahead,
            'Description' => $request->descript ?? ($stock->Description ?? ''),
            'quantity'    => $qty,
            'sale_price'  => $unitPrice,
            'discount'    => $discount,
            'tax'         => $tax,
            'netamount'   => $netamount,
            'remain_qty'  => $qty,
            'SRJV_return' => 0,
            
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        // Decrement stock
        DB::table('p_purch_stock')->where('stock_id', $stockId)->decrement('remain_qty', $qty);

        // Recalculate invoice total (sum of netamount + tax)
        $total = DB::table('p_sale_part')->where('sale_inv', $sale_inv)
            ->selectRaw('SUM(netamount + tax) as total')
            ->value('total') ?? 0;

        DB::table('p_sale_inv')->where('sale_inv', $sale_inv)
            ->update(['Total_amount' => $total, 'updated_at' => now()]);

        // Redirect back to same page — prevents duplicate on refresh
        return redirect()->route('parts.sale.add', $sale_inv)->with('success', 'Part added successfully.');
    }

    /**
     * GET /sale/{sale_inv}/invoice — Show invoice review page with all parts
     */
    public function saleInvoice($sale_inv)
{
    $invoice = PSaleInv::findOrFail($sale_inv);
    $parts = PSalePart::where('sale_inv', $sale_inv)->get();

    $grossSale = $parts->sum(fn($p) => $p->sale_price * $p->quantity);
    $totalDiscount = $parts->sum('discount');
    $totalTax = $parts->sum('tax');
    $netAmount = $grossSale - $totalDiscount + $totalTax;

    return view('parts.entry.sale.sale_inv', compact(
        'invoice', 'parts', 'grossSale', 'totalDiscount', 'totalTax', 'netAmount'
    ));
}

    /**
     * POST /sale/invoice/part-store — Add a part from the invoice REVIEW page
     * (same logic as saleAddPart — kept separate for route clarity)
     */
    public function saleInvoicePartStore(Request $request)
    {
        $request->validate([
            'sale_inv'   => 'required|integer',
            'stock_id'   => 'required|integer',
            'quantity'   => 'required|numeric|min:1',
            'sale_price' => 'required|numeric|min:0',
        ]);

        $saleInv   = (int)   $request->sale_inv;
        $stockId   = (int)   $request->stock_id;
        $qty       = (float) $request->quantity;
        $unitPrice = (float) $request->sale_price;
        $discount  = (float) ($request->discount ?? 0);
        $tax       = (float) ($request->tax      ?? 0);

        // FIX: correct formula — netamount = gross - discount
        $gross     = $qty * $unitPrice;
        $netamount = $gross - $discount;

        $stock = DB::table('p_purch_stock')->where('stock_id', $stockId)->first();
        if (!$stock || $stock->remain_qty < $qty) {
            return back()->withInput()->with('error', 'Not enough stock! Available: ' . ($stock->remain_qty ?? 0));
        }

        PSalePart::create([
            'sale_inv'    => $saleInv,
            'stock_id'    => $stockId,
            'part_no'     => $request->part_no ?? ($stock->part_no ?? ''),
            'Description' => $request->descript ?? ($stock->Description ?? ''),
            'quantity'    => $qty,
            'sale_price'  => $unitPrice,
            'discount'    => $discount,
            'tax'         => $tax,
            'netamount'   => $netamount,
            'remain_qty'  => $qty,
            'SRJV_return' => 0,
            'user'        => Auth::user()->login_id ?? 'unkown',
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);

        PPurchStock::where('stock_id', $stockId)->decrement('remain_qty', $qty);

        // Recalculate invoice total
        $total = DB::table('p_sale_part')->where('sale_inv', $saleInv)
            ->selectRaw('SUM(netamount + tax) as total')
            ->value('total') ?? 0;
        PSaleInv::where('sale_inv', $saleInv)->update(['Total_amount' => $total, 'updated_at' => now()]);

        // REDIRECT prevents duplicate on refresh
        return redirect()->route('parts.sale.invoice', $saleInv)->with('success', 'Part added.');
    }

    /**
     * POST /sale/invoice/delete — Delete a part from an invoice and restore stock
     */
    public function saleInvoiceDelete(Request $request)
    {
        $sellId  = $request->sell_id;
        $qty     = (float) $request->quantity;
        $stockId = (int)   $request->stock_id;
        $invNo   = $request->inv_no;

        DB::table('p_sale_part')->where('sell_id', $sellId)->delete();

        if ($stockId && $qty > 0) {
            DB::table('p_purch_stock')->where('stock_id', $stockId)->increment('remain_qty', $qty);
        }

        $total = DB::table('p_sale_part')->where('sale_inv', $invNo)
            ->selectRaw('SUM(netamount + tax) as total')
            ->value('total') ?? 0;
        DB::table('p_sale_inv')->where('sale_inv', $invNo)
            ->update(['Total_amount' => $total, 'updated_at' => now()]);

        return redirect()->route('parts.sale.invoice', $invNo)->with('success', 'Part deleted and stock restored.');
    }

    /**
     * POST /sale/close — Close the invoice, update totals, update jobber balance if credit
     *
     * FIX: Recalculate totals server-side instead of trusting hidden form fields.
     */
public function printAndClose(Request $request, $sale_inv)
{
    $remarks = $request->remarks ?? '';

    $invoice = PSaleInv::findOrFail($sale_inv);
    $parts = DB::table('p_sale_part')->where('sale_inv', $sale_inv)->get();
    
    $grossSale = $parts->sum(fn($p) => $p->sale_price * $p->quantity);
    $totalDiscount = $parts->sum('discount');
    $totalTax = $parts->sum('tax');
    $netAmount = $grossSale - $totalDiscount + $totalTax;

    DB::table('p_sale_inv')->where('sale_inv', $sale_inv)->update([
        'Total_amount' => $netAmount,
        'discount' => $totalDiscount,
        'tax' => $totalTax,
        'remarks' => $remarks,
        'status' => 1,
        'updated_at' => now(),
    ]);

    if ($invoice->payment_method === 'Credit') {
        DB::table('p_jobber')
            ->where('jbr_name', $invoice->Jobber)
            ->update([
                'Balance_status' => DB::raw("Balance_status + $netAmount"),
                'latest_update' => "Cred_Sale SJV#{$sale_inv} ({$netAmount})",
                'last_update' => now(),
            ]);
    }

    $invoice = PSaleInv::findOrFail($sale_inv);
    $parts = DB::table('p_sale_part')->where('sale_inv', $sale_inv)->get();
    
    // Make sure this path is correct
    return view('parts.entry.sale.files.print_sale_inv', compact('invoice', 'parts'));
}
    public function saleInvoiceEdit($sell_id)
    {
        $part    = DB::table('p_sale_part')->where('sell_id', $sell_id)->first();
        if (!$part) return redirect()->back()->with('error', 'Part not found.');
        $invoice  = PSaleInv::findOrFail($part->sale_inv);
        $sale_inv = $part->sale_inv;
        $jobbers  = PJobber::orderBy('jbr_name')->get();
        return view('parts.entry.sale_part', compact('jobbers', 'sale_inv', 'invoice', 'part'));
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
            'remain_qty'  => DB::raw("remain_qty - " . (int) $request->required_qty),
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
            'user'       => Auth::user()->login_id ?? 'unkown',
            'datetime'        => now(),
            'created_at'      => now(),
            'updated_at'      => now(),
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
            'jobber'          => 'required',
            'trans_type'      => 'required|in:Paid,Received',
            'amount'          => 'required|numeric|min:0.01',
            'payment_method'  => 'required|string',
            'rec_paid_by'     => 'required|string',
        ]);

        $payment = PJobberPayment::create([
            'jobber'          => $request->jobber,
            'trans_type'      => $request->trans_type,
            'amount'          => $request->amount,
            'payment_method'  => $request->payment_method,
            'rec_paid_by'     => $request->rec_paid_by,
            'remarks'         => $request->remarks,
            'user'            => session('login_id'),
            'datetime'        => now(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        if ($request->trans_type === 'Paid') {
            PJobber::where('jobber_id', $request->jobber)->update([
                'Balance_status' => DB::raw("Balance_status + " . (float) $request->amount),
                'latest_update'  => "Payment_Paid:{$request->amount}",
                'last_update'    => now(),
            ]);
        } else {
            PJobber::where('jobber_id', $request->jobber)->update([
                'Balance_status' => DB::raw("Balance_status - " . (float) $request->amount),
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
            'datetime'    => now(),
            'created_at'  => now(),
            'updated_at'  => now(),
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
            'jbr_name'       => $request->jobber,
            'Job_cust'       => $request->cust_jobber,
            'person'         => $request->contactperson,
            'contact'        => $request->contact,
            'address'        => $request->address,
            'email'          => $request->email,
            'cnic'           => $request->CNIC,
            'Balance_status' => 0,
            'latest_update'  => now(),
            'last_update'    => now(),
            'datetime'       => now(),
            'created_at'     => now(),
            'updated_at'     => now(),
            'user'           => session('login_id'),
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
                'app_id', 'veh_id', 'CustomerName', 'Mobile', 'job_nature',
                'cust_id', 'source', 'veh_rec', 'veh_details', 'VOC', 'parts',
                'Parts_cost', 'appt_datetime', 'CRO', 'Variant', 'parts_status'
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
            ->where('status', '1')
            ->orderByDesc('Jobc_id')
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
        $jobbers = DB::table('p_jobber')->orderBy('jbr_name')->pluck('jbr_name');
        return view('parts.entry.reports', compact('jobbers'));
    }

    public function kpiReport()
    {
        return view('parts.entry.KPI_report');
    }

    public function dpokReport()
    {
        return view('parts.entry.report_Dpok');
    }

    // ==================== INDIVIDUAL REPORTS ====================

public function reportDailySale(Request $request)
{
    $from = $request->from ?? today()->toDateString();
    $to   = $request->to   ?? today()->toDateString();

    // Workshop parts with correct price from jobc_parts
    $workshopParts = DB::table('jobc_parts_p as jp')
        ->join('p_parts as pp', 'jp.part_no', '=', 'pp.Part_no')
        ->leftJoin('jobc_parts as jc', function($join) {
            $join->on('jp.parts_sale_id', '=', 'jc.parts_sale_id')
                 ->on('jp.part_no', '=', 'jc.part_number');
        })
        ->select('jp.part_no', 'pp.Description', 'pp.catetype',
            DB::raw('SUM(jp.issued_qty) as sale_qty'),
            DB::raw('SUM(jp.issued_qty * jc.unitprice) as total_amount'))
        ->whereBetween(DB::raw("DATE_FORMAT(jp.date_time,'%Y-%m-%d')"), [$from, $to])
        ->groupBy('jp.part_no', 'pp.Description', 'pp.catetype')
        ->get();

    // Workshop consumables - get description from jobc_consumble
    $workshopCons = DB::table('jobc_consumble_p as cp')
        ->leftJoin('jobc_consumble as jc', 'cp.parts_sale_id', '=', 'jc.cons_sale_id')
        ->select('cp.part_no', 
            DB::raw('COALESCE(jc.cons_description, cp.part_no) as Description'),
            DB::raw('SUM(cp.issued_qty) as sale_qty'),
            DB::raw('SUM(cp.issued_qty * jc.unitprice) as total_amount'))
        ->whereBetween(DB::raw("DATE_FORMAT(cp.date_time,'%Y-%m-%d')"), [$from, $to])
        ->groupBy('cp.part_no', 'jc.cons_description')
        ->get();

    // Counter sale
    $counterSale = DB::table('p_sale_part as sp')
        ->join('p_sale_inv as si', 'sp.sale_inv', '=', 'si.sale_inv')
        ->select('sp.part_no', 'sp.Description',
            DB::raw('SUM(sp.quantity) as sale_qty'),
            DB::raw('SUM(sp.netamount + sp.tax) as total_amount'),
            'si.Jobber')
        ->whereBetween(DB::raw("DATE_FORMAT(si.datetime,'%Y-%m-%d')"), [$from, $to])
        ->groupBy('sp.part_no', 'sp.Description', 'si.Jobber')
        ->get();

    $totalWorkshop = $workshopParts->sum('total_amount') + $workshopCons->sum('total_amount');
    $totalCounter  = $counterSale->sum('total_amount');

    return view('parts.entry.reports.daily_sale', compact(
        'workshopParts', 'workshopCons', 'counterSale',
        'totalWorkshop', 'totalCounter', 'from', 'to'
    ));
}
    public function reportStock(Request $request)
    {
        $stockType = $request->stock_type ?? 'all';
        $category  = $request->category ?? '';

        $query = DB::table('p_purch_stock as ps')
            ->join('p_parts as pp', 'ps.part_no', '=', 'pp.Part_no')
            ->where('ps.purch_return', 0)
            ->where('ps.remain_qty', '>', 0)
            ->select('ps.stock_id', 'ps.part_no', 'ps.Description', 'ps.unit',
                     'ps.quantity', 'ps.remain_qty', 'ps.Price',
                     DB::raw('ps.remain_qty * ps.Price as stock_value'),
                     'ps.cate_type', 'pp.Location', 'ps.date');


        if ($stockType === 'local') $query->where('ps.cate_type', 'NOT LIKE', '%IMC%');
        if ($category)              $query->where('ps.cate_type', $category);

        $stocks     = $query->orderByDesc('ps.stock_id')->get();
        $totalValue = $stocks->sum('stock_value');

        return view('parts.entry.reports.stock', compact('stocks', 'totalValue', 'stockType', 'category'));
    }

    public function reportPurchase(Request $request)
    {
        $from   = $request->from   ?? today()->subMonth()->toDateString();
        $to     = $request->to     ?? today()->toDateString();
        $vendor = $request->vendor ?? '';

        $query = DB::table('p_purch_inv as pi')
            ->where('pi.status', 1)
            ->whereBetween(DB::raw("DATE_FORMAT(pi.date,'%Y-%m-%d')"), [$from, $to]);

        if ($vendor) $query->where('pi.jobber', $vendor);

        $invoices = $query->orderByDesc('pi.Invoice_no')->get();

        $invoices = $invoices->map(function ($inv) {
            $inv->items   = DB::table('p_purch_stock')->where('Invoice_no', $inv->Invoice_no)->get();
            $inv->returns = DB::table('p_purch_return as pr')
                ->join('p_purch_stock as ps', 'pr.stock_id', '=', 'ps.stock_id')
                ->where('ps.Invoice_no', $inv->Invoice_no)
                ->select(DB::raw('SUM(pr.unit_price * pr.return_qty) as return_amount'))
                ->first();
            return $inv;
        });

        $totalPurchase = $invoices->sum(fn($i) => $i->items->sum('Netamount'));
        $totalReturn   = $invoices->sum(fn($i) => $i->returns->return_amount ?? 0);

        return view('parts.entry.reports.purchase', compact(
            'invoices', 'totalPurchase', 'totalReturn', 'from', 'to', 'vendor'
        ));
    }

    public function reportSale(Request $request)
    {
        $from   = $request->from   ?? today()->subMonth()->toDateString();
        $to     = $request->to     ?? today()->toDateString();
        $vendor = $request->vendor ?? '';

        $query = DB::table('p_sale_inv as si')
            ->whereBetween(DB::raw("DATE_FORMAT(si.datetime,'%Y-%m-%d')"), [$from, $to]);

        if ($vendor) $query->where('si.Jobber', $vendor);

        $invoices = $query->orderByDesc('si.sale_inv')->get();

        $invoices = $invoices->map(function ($inv) {
            $inv->items = DB::table('p_sale_part')->where('sale_inv', $inv->sale_inv)->get();
            return $inv;
        });

        $grandTotal = $invoices->sum(fn($i) => $i->items->sum(fn($p) => $p->netamount + $p->tax));

        return view('parts.entry.reports.sale', compact('invoices', 'grandTotal', 'from', 'to', 'vendor'));
    }

    public function reportSaleHistory(Request $request)
    {
        $from = $request->from ?? today()->subMonth()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $parts = DB::select("
            SELECT part_no, SUM(t_sale) AS t_sale FROM (
                SELECT part_no, SUM(issued_qty) AS t_sale
                FROM jobc_parts_p
                WHERE DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN ? AND ?
                GROUP BY part_no
                UNION ALL
                SELECT part_no, SUM(issued_qty) AS t_sale
                FROM jobc_consumble_p
                WHERE DATE_FORMAT(date_time,'%Y-%m-%d') BETWEEN ? AND ?
                GROUP BY part_no
                UNION ALL
                SELECT sp.part_no, SUM(sp.quantity) AS t_sale
                FROM p_sale_part sp
                JOIN p_sale_inv si ON sp.sale_inv = si.sale_inv
                WHERE DATE_FORMAT(si.datetime,'%Y-%m-%d') BETWEEN ? AND ?
                GROUP BY sp.part_no
            ) x GROUP BY part_no
            ORDER BY t_sale DESC
        ", [$from, $to, $from, $to, $from, $to]);

        return view('parts.entry.reports.sale_history', compact('parts', 'from', 'to'));
    }

    public function reportDeadStock(Request $request)
    {
        $months = $request->months ?? 3;
        $type   = $request->type ?? 'all';

        $sold = DB::table('jobc_parts_p')
            ->where('date_time', '>=', DB::raw("DATE(NOW() - INTERVAL $months MONTH)"))
            ->pluck('stock_id')->merge(
                DB::table('jobc_consumble_p')
                    ->where('date_time', '>=', DB::raw("DATE(NOW() - INTERVAL $months MONTH)"))
                    ->pluck('stock_id')
            )->merge(
                DB::table('p_sale_part as sp')
                    ->join('p_sale_inv as si', 'sp.sale_inv', '=', 'si.sale_inv')
                    ->where('si.datetime', '>=', DB::raw("DATE(NOW() - INTERVAL $months MONTH)"))
                    ->pluck('sp.stock_id')
            )->unique();

        $query = DB::table('p_purch_stock as ps')
            ->join('p_parts as pp', 'ps.part_no', '=', 'pp.Part_no')
            ->where('ps.purch_return', 0)
            ->where('ps.remain_qty', '>', 0)
            ->where('ps.date', '<', DB::raw("DATE(NOW() - INTERVAL $months MONTH)"))
            ->whereNotIn('ps.stock_id', $sold->all())
            ->select('ps.stock_id', 'ps.part_no', 'ps.Description', 'ps.unit',
                     'ps.quantity', 'ps.remain_qty', 'ps.Price', 'pp.Location',
                     DB::raw("DATEDIFF(CURDATE(), ps.date) as StockDays"));

        if ($type === 'imc')   $query->where('ps.cate_type', 'LIKE', '%IMC%');
        if ($type === 'local') $query->where('ps.cate_type', 'NOT LIKE', '%IMC%');

        $stocks     = $query->orderByDesc('StockDays')->get();
        $totalValue = $stocks->sum(fn($s) => $s->remain_qty * $s->Price);

        return view('parts.entry.reports.dead_stock', compact('stocks', 'totalValue', 'months', 'type'));
    }

    public function reportNonMoving(Request $request)
    {
        $months = $request->months ?? 6;
        $type   = $request->type ?? 'all';

        $sold = DB::table('jobc_parts_p')
            ->where('date_time', '>=', DB::raw("DATE(NOW() - INTERVAL $months MONTH)"))
            ->pluck('stock_id')->merge(
                DB::table('jobc_consumble_p')
                    ->where('date_time', '>=', DB::raw("DATE(NOW() - INTERVAL $months MONTH)"))
                    ->pluck('stock_id')
            )->merge(
                DB::table('p_sale_part as sp')
                    ->join('p_sale_inv as si', 'sp.sale_inv', '=', 'si.sale_inv')
                    ->where('si.datetime', '>=', DB::raw("DATE(NOW() - INTERVAL $months MONTH)"))
                    ->pluck('sp.stock_id')
            )->unique();

        $query = DB::table('p_purch_stock as ps')
            ->join('p_parts as pp', 'ps.part_no', '=', 'pp.Part_no')
            ->where('ps.purch_return', 0)
            ->where('ps.remain_qty', '>', 0)
            ->whereNotIn('ps.stock_id', $sold->all())
            ->select('ps.stock_id', 'ps.part_no', 'ps.Description', 'ps.unit',
                     'ps.remain_qty', 'ps.Price', 'pp.Location',
                     DB::raw("DATEDIFF(CURDATE(), ps.date) as StockDays"));

        if ($type === 'imc')   $query->where('ps.cate_type', 'LIKE', '%IMC%');
        if ($type === 'local') $query->where('ps.cate_type', 'NOT LIKE', '%IMC%');

        $stocks     = $query->orderByDesc('StockDays')->get();
        $totalValue = $stocks->sum(fn($s) => $s->remain_qty * $s->Price);

        return view('parts.entry.reports.non_moving', compact('stocks', 'totalValue', 'months', 'type'));
    }

    public function reportReorder(Request $request)
    {
        $parts = DB::table('p_parts as pp')
            ->leftJoin('p_purch_stock as ps', function ($j) {
                $j->on('pp.Part_no', '=', 'ps.part_no')->where('ps.purch_return', 0);
            })
            ->select('pp.Part_no', 'pp.Description', 'pp.catetype', 'pp.ReOrder', 'pp.Location',
                     DB::raw('COALESCE(SUM(ps.remain_qty),0) as current_stock'))
            ->groupBy('pp.Part_no', 'pp.Description', 'pp.catetype', 'pp.ReOrder', 'pp.Location')
            ->havingRaw('pp.ReOrder > COALESCE(SUM(ps.remain_qty),0)')
            ->orderByDesc('pp.ReOrder')
            ->get();

        return view('parts.entry.reports.reorder', compact('parts'));
    }

    public function reportPartWise(Request $request)
    {
        $from    = $request->from ?? today()->subMonth()->toDateString();
        $to      = $request->to   ?? today()->toDateString();
        $imcOnly = $request->has('imc_only');

        $query = DB::table('p_sale_part as sp')
            ->join('p_sale_inv as si', 'sp.sale_inv', '=', 'si.sale_inv')
            ->join('p_purch_stock as ps', 'sp.stock_id', '=', 'ps.stock_id')
            ->select('sp.part_no', 'sp.Description',
                DB::raw('SUM(sp.quantity) as total_qty'),
                DB::raw('AVG(sp.sale_price) as avg_price'),
                DB::raw('SUM(sp.netamount + sp.tax) as total_sale'),
                DB::raw('SUM(ps.Price * sp.quantity) as total_cost'),
                DB::raw('SUM(sp.netamount + sp.tax) - SUM(ps.Price * sp.quantity) as profit'))
            ->whereBetween(DB::raw("DATE_FORMAT(si.datetime,'%Y-%m-%d')"), [$from, $to])
            ->groupBy('sp.part_no', 'sp.Description')
            ->orderByDesc('total_sale');

        if ($imcOnly) $query->where('ps.cate_type', 'LIKE', '%IMC%');

        $parts     = $query->get();
        $totalSale = $parts->sum('total_sale');
        $totalCost = $parts->sum('total_cost');

        return view('parts.entry.reports.part_wise', compact('parts', 'totalSale', 'totalCost', 'from', 'to', 'imcOnly'));
    }

    public function reportLostSale(Request $request)
    {
        $from = $request->from ?? today()->subMonth()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $lost = DB::table('cr_appointments')
            ->select('parts', 'CRO', 'Parts_cost', 'parts_status', 'CustomerName',
                     'appt_datetime', 'Variant', 'VOC')
            ->whereBetween(DB::raw("DATE_FORMAT(appt_datetime,'%Y-%m-%d')"), [$from, $to])
            ->where('parts_status', 3)
            ->orderByDesc('app_id')
            ->get();

        $totalLost = $lost->sum('Parts_cost');

        return view('parts.entry.reports.lost_sale', compact('lost', 'totalLost', 'from', 'to'));
    }

 public function destroy($invoice_no, $id)
{
    // Use the correct model - PPurchStock instead of PartStock
    $stock = PPurchStock::where('Invoice_no', $invoice_no)
                        ->where('stock_id', $id)  // Using stock_id instead of id
                        ->firstOrFail();
    
    $stock->delete();
    
    // Update the invoice total after deletion
    $total = PPurchStock::where('Invoice_no', $invoice_no)->sum('Netamount');
    PPurchInv::where('Invoice_no', $invoice_no)->update(['Total_amount' => $total]);
    
    return redirect()->back()->with('success', 'Item removed successfully');
}
public function reportRevenue(Request $request)
{
    $from = $request->from ?? today()->subMonth()->toDateString();
    $to   = $request->to   ?? today()->toDateString();

    // Workshop parts revenue
    $wpRevenue = DB::table('jobc_parts_p as jp')
        ->leftJoin('jobc_parts as jc', 'jp.parts_sale_id', '=', 'jc.parts_sale_id')
        ->whereBetween(DB::raw("DATE_FORMAT(jp.date_time,'%Y-%m-%d')"), [$from, $to])
        ->select(DB::raw('SUM(jp.issued_qty * jc.unitprice) as revenue'))
        ->first();

    // Workshop consumables revenue
    $wpConsRevenue = DB::table('jobc_consumble_p as cp')
        ->leftJoin('jobc_consumble as jc', 'cp.parts_sale_id', '=', 'jc.cons_sale_id')
        ->whereBetween(DB::raw("DATE_FORMAT(cp.date_time,'%Y-%m-%d')"), [$from, $to])
        ->select(DB::raw('SUM(cp.issued_qty * jc.unitprice) as revenue'))
        ->first();

    // Counter sale revenue
    $counterRevenue = DB::table('p_sale_part as sp')
        ->join('p_sale_inv as si', 'sp.sale_inv', '=', 'si.sale_inv')
        ->whereBetween(DB::raw("DATE_FORMAT(si.datetime,'%Y-%m-%d')"), [$from, $to])
        ->select(DB::raw('SUM(sp.netamount + sp.tax) as revenue'))
        ->first();

    return view('parts.entry.reports.revenue', compact(
        'wpRevenue', 'wpConsRevenue', 'counterRevenue', 'from', 'to'
    ));
}
    public function reportFillRate(Request $request)
    {
        $from = $request->from ?? today()->subMonth()->toDateString();
        $to   = $request->to   ?? today()->toDateString();

        $stats = DB::table('cr_appointments')
            ->whereBetween(DB::raw("DATE_FORMAT(appt_datetime,'%Y-%m-%d')"), [$from, $to])
            ->whereIn('parts_status', [2, 3])
            ->select(
                DB::raw("SUM(IF(parts_status=2,1,0)) as available"),
                DB::raw("SUM(IF(parts_status=3,1,0)) as not_available"),
                DB::raw("COUNT(*) as total")
            )->first();

        $fillRate = $stats->total > 0
            ? round(($stats->available / $stats->total) * 100, 2) : 0;

        $details = DB::table('cr_appointments')
            ->whereBetween(DB::raw("DATE_FORMAT(appt_datetime,'%Y-%m-%d')"), [$from, $to])
            ->whereIn('parts_status', [2, 3])
            ->select('app_id', 'CustomerName', 'parts', 'Parts_cost', 'parts_status',
                     'CRO', 'appt_datetime', 'Variant')
            ->orderByDesc('app_id')
            ->get();

        return view('parts.entry.reports.fill_rate', compact('stats', 'fillRate', 'details', 'from', 'to'));
    }

    // ==================== AJAX SEARCH ENDPOINTS ====================

    /**
     * GET /ajax/search-part-desc?partn=XXX
     * Returns JSON description for a given part number
     */
    public function searchPartDesc(Request $request)
    {
        $part = DB::table('p_parts')->where('Part_no', $request->partn)->first();
        return response()->json(['desc' => $part ? $part->Description : '']);
    }

    /**
     * GET /ajax/search-part?key=XXX  — typeahead for part numbers
     */
public function searchPart(Request $request)
{
    $key = $request->key ?? $request->input('partn', '');
    
    $parts = DB::table('p_parts')
        ->where('Part_no', 'LIKE', "%{$key}%")
        ->limit(15)
        ->select(
            'Part_no as value',      // Maps to p.value
            'Description as desc',   // Maps to p.desc
            'catetype as category'   // Maps to p.category
        )
        ->get();
    
    return response()->json($parts);
}

    /**
     * GET /ajax/search-stock?key=XXX
     * Exact match on part_no — matches original search_stock.php
     * Joins p_purch_inv for GRN, Invoice#, date, jobber columns
     */
    public function searchStock(Request $request)
{
    $partNo = $request->key ?? '';

    $stocks = DB::table('p_purch_stock as ps')
        ->join('p_parts as pp', 'ps.part_no', '=', 'pp.Part_no')
        ->where('pp.Part_no', $partNo)
        ->orderByDesc('ps.remain_qty')
        ->orderByDesc('ps.stock_id')
        ->select(
            'ps.stock_id', 'ps.part_no', 'ps.Description',
            'ps.quantity', 'ps.remain_qty', 'ps.Price',
            'ps.Model', 'ps.cate_type', 'ps.Invoice_no',
            DB::raw('COALESCE(pp.Location, ps.location, "") as location')
        )
        ->get();

    $result = $stocks->map(function ($s) {
        $purch = DB::table('p_purch_inv')
            ->where('Invoice_no', $s->Invoice_no)
            ->select('Invoice_no', 'Invoice_number', 'jobber',
                     DB::raw("DATE_FORMAT(`date`, ' %d %b %y') AS purchasedate"), 'status')
            ->first();

        // Original PHP logic: Show if invoice doesn't exist OR invoice status = 1
        $showRow = (!$purch) || ($purch && $purch->status == 1);

        return [
            'show' => $showRow,
            'stock_id' => $s->stock_id,
            'part_no' => $s->part_no,
            'desc' => $s->Description ?? '',
            'quantity' => $s->quantity,
            'remain_qty' => $s->remain_qty,
            'price' => $s->Price,
            'model' => $s->Model ?? '',
            'location' => $s->location ?? '',
            'cate_type' => $s->cate_type ?? '',
            'grn' => $purch ? $purch->Invoice_no : '',
            'inv_number' => $purch ? $purch->Invoice_number : '',
            'purch_date' => $purch ? $purch->purchasedate : '',
            'jobber' => $purch ? ($purch->jobber ?: $s->cate_type) : $s->cate_type,
        ];
    })->filter(fn($s) => $s['show'])->values();

    return response()->json($result);
}

    /**
     * POST /ajax/search-stock-by-part
     * Returns stock batches for a given part number — used by purchase return / sale return pages
     */
    public function searchStockByPart(Request $request)
    {
        $partNo = $request->input('partn', '');
        $stocks = DB::table('p_purch_stock as ps')
            ->leftJoin('p_purch_inv as pi', 'ps.Invoice_no', '=', 'pi.Invoice_no')
            ->where('ps.part_no', $partNo)
            ->select(
                'ps.stock_id', 'ps.part_no', 'ps.Description', 'ps.unit',
                'ps.remain_qty', 'ps.quantity', 'ps.Price', 'ps.cate_type',
                'ps.Model', 'ps.location', 'ps.Invoice_no',
                'pi.Invoice_number', 'pi.jobber',
                DB::raw("DATE_FORMAT(pi.date, '%d %b %y') as purch_date")
            )
            ->orderByDesc('ps.remain_qty')
            ->orderByDesc('ps.stock_id')
            ->get();

        return response()->json($stocks->map(fn($s) => [
            'stock_id'   => $s->stock_id,
            'value'      => $s->stock_id,
            'part_no'    => $s->part_no,
            'desc'       => $s->Description,
            'unit'       => $s->unit,
            'remain_qty' => $s->remain_qty,
            'quantity'   => $s->quantity,
            'price'      => $s->Price,
            'category'   => $s->cate_type,
            'model'      => $s->Model,
            'location'   => $s->location,
            'grn'        => $s->Invoice_no,
            'bill_no'    => $s->Invoice_number ?? '',
            'jobber'     => $s->jobber ?? '',
            'purch_date' => $s->purch_date ?? '',
        ]));
    }

    /**
     * POST /ajax/search-stock-by-grn
     * Returns stock items for a given GRN/Invoice_no — used by purchase return page
     */
    public function searchStockByGrn(Request $request)
    {
        $grn    = $request->input('partn', '');
        $stocks = DB::table('p_purch_stock as ps')
            ->leftJoin('p_purch_inv as pi', 'ps.Invoice_no', '=', 'pi.Invoice_no')
            ->where('ps.Invoice_no', $grn)
            ->select(
                'ps.stock_id', 'ps.part_no', 'ps.Description',
                'ps.remain_qty', 'ps.quantity', 'ps.Price', 'ps.cate_type',
                'pi.Invoice_number', 'pi.jobber'
            )
            ->get();

        return response()->json($stocks->map(fn($s) => [
            'stock_id'   => $s->stock_id,
            'value'      => $s->stock_id,
            'part_no'    => $s->part_no,
            'desc'       => $s->Description,
            'remain_qty' => $s->remain_qty,
            'price'      => $s->Price,
            'bill_no'    => $s->Invoice_number ?? '',
            'jobber'     => $s->jobber ?? '',
        ]));
    }

    /**
     * POST /ajax/search-sale-inv
     * Returns parts for a given sale invoice number — used by sale return page
     */
    public function searchSaleInvoiceParts(Request $request)
    {
        $saleInv = $request->input('partn', '');
        $invoice = DB::table('p_sale_inv')->where('sale_inv', $saleInv)->first();

        $parts = DB::table('p_sale_part')
            ->where('sale_inv', $saleInv)
            ->where('remain_qty', '>', 0)
            ->select('sell_id', 'stock_id', 'part_no', 'Description', 'remain_qty', 'sale_price')
            ->get();

        return response()->json([
            'jobber' => $invoice->Jobber ?? '',
            'date'   => $invoice->datetime ?? '',
            'parts'  => $parts,
        ]);
    }

    /**
     * GET /ajax/search-sale-invoice?inv_no=XXX
     * Used by search page to load a sale invoice print view
     */
    public function searchSaleInvoice(Request $request)
    {
        $invoice = PSaleInv::with('parts')->find($request->inv_no);
        if (!$invoice) {
            return response()->json(['error' => 'Invoice not found'], 404);
        }
        return view('parts.entry.sale.files.print_sale_inv', compact('invoice'));
    }

    /**
     * GET /ajax/search-purchase-invoice?invoice_no=XXX
     */
    public function searchPurchaseInvoice(Request $request)
    {
        $invoice = PPurchInv::with('stockItems', 'jobber')->find($request->invoice_no);
        if (!$invoice) abort(404);
        return view('parts.entry.sale.files.print_purch', compact('invoice'));
    }

    /**
     * AJAX: get jobber balance
     */
    public function getJobberBalance(Request $request)
    {
        $jobber = DB::table('p_jobber')->where('jobber_id', $request->jobber_id)->first();
        if (!$jobber) return response()->json(['balance' => 0, 'name' => '']);
        return response()->json([
            'balance' => $jobber->Balance_status,
            'name'    => $jobber->jbr_name,
            'updated' => $jobber->latest_update,
        ]);
    }

    /**
     * AJAX: check if purchase invoice number already exists for a jobber
     */
    public function checkInvoice(Request $request)
    {
        $exists = PPurchInv::where('Invoice_number', $request->NIC)
            ->where('jobber', $request->jobber)
            ->exists();

        echo $exists
            ? "<span style='color:red'>Invoice# already exists for this Jobber!</span>"
            : 'OK';
    }

    // ==================== PRINT PAGES ====================

    public function printSaleInvoice($inv_no)
    {
        $invoice = PSaleInv::findOrFail($inv_no);
        $parts   = DB::table('p_sale_part')->where('sale_inv', $inv_no)->get();
        return view('parts.entry.sale.files.print_sale_inv', compact('invoice', 'parts'));
    }

    public function printPurchase($invoice_no)
    {
        $invoice = PPurchInv::with('stockItems', 'jobber')->findOrFail($invoice_no);
        $tax = request('tax', 0);
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
        $consumbles = DB::table('jobc_consumble as jc2')
            ->join('jobcard as jc', 'jc2.RO_no', '=', 'jc.Jobc_id')
            ->where('jc2.RO_no', $inv_id)
            ->select('jc2.*', 'jc.RO_no as RO_display', 'jc.Customer_name')
            ->get();

        return view('parts.entry.sale.files.issue_cons_print', compact('consumbles', 'inv_id'));
    }

    // ==================== ISSUE PARTS / CONSUMABLES ====================

    public function issuePart(Request $request)
    {
        $request->validate(['part_id' => 'required|integer']);

        DB::table('jobc_parts')
            ->where('parts_sale_id', $request->part_id)
            ->update([
                'issue_time'     => now(),
                'status'         => 1,
                'issue_by'       => session('login_id'),
                'part_invoice_no'=> 0,
            ]);

        return redirect()->route('parts.index')->with('success', 'Part issued successfully.');
    }

    public function issueConsumable(Request $request)
    {
        $request->validate(['part_id' => 'required|integer']);

        DB::table('jobc_consumble')
            ->where('cons_sale_id', $request->part_id)
            ->update([
                'issue_time' => now(),
                'status'     => 1,
                'issue_by'   => session('login_id'),
                'cons_req_no'=> 0,
            ]);

        return redirect()->route('parts.index')->with('success', 'Consumable issued successfully.');
    }

    public function partNotAvailable(Request $request)
    {
        $request->validate(['not_available_id' => 'required|integer']);

        DB::table('jobc_parts')
            ->where('parts_sale_id', $request->not_available_id)
            ->update([
                'issue_time' => now(),
                'status'     => 2,
                'issue_by'   => session('login_id'),
            ]);

        return redirect()->route('parts.index')->with('warning', 'Part marked as Not Available.');
    }

    public function consumableNotAvailable(Request $request)
    {
        $request->validate(['not_available_cons' => 'required|integer']);

        DB::table('jobc_consumble')
            ->where('cons_sale_id', $request->not_available_cons)
            ->update([
                'issue_time' => now(),
                'status'     => 2,
                'issue_by'   => session('login_id'),
            ]);

        return redirect()->route('parts.index')->with('warning', 'Consumable marked as Not Available.');
    }

    // ==================== ISSUE PART FORM (issue_part.php) ====================

    public function issuePartForm(Request $request)
    {
        $invoiceNo = $request->invoice_no_field;
        if ($invoiceNo === 'New' || !$invoiceNo) {
            $invoiceNo = DB::table('jobc_parts')->max('part_invoice_no') + 1;
        }

        $data = [
            'part_id'          => $request->part_id,
            'RO_no'            => $request->RO_no,
            'part_description' => $request->part_description,
            'qty'              => $request->qty,
            'req_qty'          => $request->req_qty,
            'issued_qty'       => $request->issued_qty,
            'unitprice'        => $request->unitprice,
            'total'            => $request->total,
            'invoice_no'       => $invoiceNo,
        ];

        return view('parts.entry.issue_part', compact('data'));
    }

    public function issuePartSubmit(Request $request)
    {
        $request->validate([
            'required_stock_id' => 'required|integer',
            'typeahead'         => 'required|string',
            'parts_issued'      => 'required|integer|min:1',
            'require_orignal'   => 'required|integer|min:1',
            'issueto'           => 'required|string',
        ]);

        $partId      = $request->part_id;
        $typeahead   = $request->typeahead;
        $stockId     = $request->required_stock_id;
        $issueTo     = $request->issueto;
        $inv         = $request->inv;
        $issuedQty   = (int) $request->issued_qty;
        $reqOrignal  = (int) $request->require_orignal;
        $partsIssued = (int) $request->parts_issued;
        $totalIssued = $issuedQty + $partsIssued;
        $user        = session('login_id');

        $status = ($reqOrignal == $totalIssued) ? 1 : 0;

        DB::table('jobc_parts')
            ->where('parts_sale_id', $partId)
            ->update([
                'part_number'     => $typeahead,
                'issue_to'        => $issueTo,
                'Stock_id'        => $stockId,
                'req_qty'         => $reqOrignal,
                'part_invoice_no' => $inv,
                'issued_qty'      => $totalIssued,
                'status'          => $status,
                'issue_time'      => now(),
            ]);

        DB::table('jobc_parts_p')->insert([
            'parts_sale_id' => $partId,
            'part_no'       => $typeahead,
            'stock_id'      => $stockId,
            'req_qty'       => $reqOrignal,
            'qty_issued'    => $totalIssued,
            'issued_qty'    => $partsIssued,
            'user'          => $user,
            'date_time'     => now(),
        ]);

        DB::table('p_purch_stock')
            ->where('stock_id', $stockId)
            ->decrement('remain_qty', $partsIssued);

        if ($request->first_btn) {
            return redirect()->route('parts.index')->with('success', 'Part issued successfully.');
        }

        return redirect()->route('parts.print.issue-part', ['inv_id' => $inv]);
    }

    // ==================== ISSUE CONSUMABLE FORM (issue_cons.php) ====================

    public function issueConsForm(Request $request)
    {
        $invoiceNo = $request->invoice_no_field;
        if ($invoiceNo === 'New' || !$invoiceNo) {
            $invoiceNo = DB::table('jobc_consumble')->max('cons_req_no') + 1;
        }

        $data = [
            'part_id'          => $request->part_id,
            'RO_no'            => $request->RO_no,
            'part_description' => $request->part_description,
            'qty'              => $request->qty,
            'req_qty'          => $request->req_qty,
            'issued_qty'       => $request->issued_qty,
            'unitprice'        => $request->unitprice,
            'total'            => $request->total,
            'invoice_no'       => $invoiceNo,
        ];

        return view('parts.entry.issue_cons', compact('data'));
    }

    public function issueConsSubmit(Request $request)
    {
        $request->validate([
            'required_stock_id' => 'required|integer',
            'typeahead'         => 'required|string',
            'parts_issued'      => 'required|integer|min:1',
            'require_orignal'   => 'required|integer|min:1',
            'issueto'           => 'required|string',
        ]);

        $partId      = $request->part_id;
        $typeahead   = $request->typeahead;
        $stockId     = $request->required_stock_id;
        $issueTo     = $request->issueto;
        $inv         = $request->inv;
        $issuedQty   = (int) $request->issued_qty;
        $reqOrignal  = (int) $request->require_orignal;
        $partsIssued = (int) $request->parts_issued;
        $totalIssued = $issuedQty + $partsIssued;
        $user        = session('login_id');

        $status = ($reqOrignal == $totalIssued) ? 1 : 0;

        DB::table('jobc_consumble')
            ->where('cons_sale_id', $partId)
            ->update([
                'cons_number' => $typeahead,
                'issue_to'    => $issueTo,
                'Stock_id'    => $stockId,
                'req_qty'     => $reqOrignal,
                'cons_req_no' => $inv,
                'issued_qty'  => $totalIssued,
                'status'      => $status,
                'issue_time'  => now(),
            ]);

        DB::table('jobc_consumble_p')->insert([
            'parts_sale_id' => $partId,
            'part_no'       => $typeahead,
            'stock_id'      => $stockId,
            'req_qty'       => $reqOrignal,
            'qty_issued'    => $totalIssued,
            'issued_qty'    => $partsIssued,
            'user'          => $user,
            'date_time'     => now(),
        ]);

        DB::table('p_purch_stock')
            ->where('stock_id', $stockId)
            ->decrement('remain_qty', $partsIssued);

        if ($request->first_btn) {
            return redirect()->route('parts.index')->with('success', 'Consumable issued successfully.');
        }

        return redirect()->route('parts.print.issue-cons', ['inv_id' => $inv]);
    }
}
