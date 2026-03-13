<?php

namespace App\Http\Controllers\Service\Jobcard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JobcardController extends Controller
{
    // ─────────────────────────────────────────────
    //  DASHBOARD / UNCLOSED JC LIST
    //  Original: Jobcard/index.php / Unclosed_JC.php
    // ─────────────────────────────────────────────
    public function index()
    {
        $unclosedJobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->whereIn('jc.status', ['0', '1'])
            ->select(
                'jc.Jobc_id', 'jc.Open_date_time', 'jc.SA', 'jc.RO_type',
                'jc.status', 'jc.Mileage', 'jc.comp_appointed',
                'v.Registration', 'v.Variant', 'v.Frame_no',
                'c.Customer_name', 'c.mobile'
            )
            ->orderBy('jc.Open_date_time', 'desc')
            ->get();

        return view('service.jobcard.index', compact('unclosedJobs'));
    }

    // ─────────────────────────────────────────────
    //  SEARCH VEHICLE  (add_veh.php / add_vehicle.php)
    //  Step 1: search vehicle by registration / frame
    // ─────────────────────────────────────────────
    public function searchVehicle()
    {
        return view('service.jobcard.add-vehicle');
    }

    public function searchVehicleResult(Request $request)
    {
        $column = $request->input('column', 'Registration');
        $value  = $request->input('value', '');

        $vehicles = DB::table('vehicles_data as v')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where("v.$column", 'LIKE', "%$value%")
            ->select('v.*', 'c.Customer_name', 'c.mobile', 'c.Customer_id')
            ->limit(20)
            ->get();

        return view('service.jobcard.add-vehicle', compact('vehicles', 'column', 'value'));
    }

    // ─────────────────────────────────────────────
    //  ADD NEW CUSTOMER + VEHICLE  (add_vehicle.php POST)
    // ─────────────────────────────────────────────
    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
        ]);

        $customerId = DB::table('customer_data')->insertGetId([
            'cust_type'     => $request->cust_type,
            'contact_type'  => $request->contact_type,
            'Customer_name' => strtoupper($request->name),
            'off_phone'     => $request->off_phone,
            'mobile'        => $request->mobile,
            'Address'       => $request->address,
            'CNIC'          => $request->cnic,
            'Update_date'   => now(),
        ]);

        return redirect()->route('jobcard.add-vehicle.vehicle', ['customer_id' => $customerId])
            ->with('success', 'Customer created. Now add vehicle details.');
    }

    public function storeVehicle(Request $request)
    {
        $request->validate([
            'registration' => 'required|string|max:20',
            'varaint'      => 'required|string|max:100',
            'customer_id'  => 'required|integer',
        ]);

        $intosell  = $request->has('intosell') ? 'on' : '';
        $modelYear = $intosell ? $request->model_year : '';
        $demandPri = $intosell ? $request->demandprice : '';

        DB::table('vehicles_data')->insert([
            'Customer_id'  => $request->customer_id,
            'Registration' => $request->registration,
            'Frame_no'     => $request->fram,
            'Engine_no'    => $request->engine,
            'Variant'      => $request->varaint,
            'Model'        => $request->model,
            'Colour'       => $request->color,
            'Make'         => $request->make,
            'into_sell'    => $intosell,
            'model_year'   => $modelYear,
            'demand_price' => $demandPri,
            'Update_date'  => now()->toDateString(),
        ]);

        $vehicleId = DB::table('vehicles_data')
            ->where('Registration', $request->registration)
            ->orderByDesc('Vehicle_id')
            ->value('Vehicle_id');

        return redirect()->route('jobcard.create', ['vehicle_id' => $vehicleId])
            ->with('success', 'Vehicle added. Now create the job card.');
    }

    // ─────────────────────────────────────────────
    //  EDIT CUSTOMER  (files/cust_edit.php)
    // ─────────────────────────────────────────────
    public function editCustomer(Request $request, $customerId)
    {
        $customer = DB::table('customer_data')->where('Customer_id', $customerId)->first();
        if (!$customer) abort(404);

        $roNo    = $request->query('ro_no');
        $vehicleId = $request->query('vehicle_id');

        return view('service.jobcard.customer-edit', compact('customer', 'roNo', 'vehicleId'));
    }

    public function updateCustomer(Request $request)
    {
        $request->validate([
            'cust_id' => 'required|integer',
            'mobile'  => 'required|string|max:20',
            'name'    => 'required|string|max:255',
        ]);

        DB::table('customer_data')
            ->where('Customer_id', $request->cust_id)
            ->update([
                'cust_type'     => $request->cust_type,
                'Customer_name' => strtoupper($request->name),
                'DOB'           => $request->dob,
                'City'          => $request->city,
                'Region'        => $request->region,
                'off_phone'     => $request->off_phone,
                'mobile'        => $request->mobile,
                'Address'       => $request->address,
                'email'         => $request->email,
                'NTN'           => $request->ntn,
                'STRN'          => $request->strn,
                'Supplier'      => $request->supplier,
                'CNIC'          => $request->cnic,
                'updated_by'    => Auth::user()->login_id,
                'Update_date'   => now(),
            ]);

        if ($request->ro_no) {
            return redirect()->route('cashier.tax-invoice-get', $request->ro_no)
                ->with('success', 'Customer updated.');
        }

        return redirect()->route('jobcard.show', $request->veh_idd)
            ->with('success', 'Customer updated.');
    }

    // ─────────────────────────────────────────────
    //  MILEAGE CHECK AJAX  (files/check.php)
    // ─────────────────────────────────────────────
    public function checkMileage(Request $request)
    {
        $nic    = $request->input('NIC');
        $vehId  = $request->input('veh_id');

        $last = DB::table('jobcard')
            ->where('Vehicle_id', $vehId)
            ->orderByDesc('Open_date_time')
            ->value('Mileage');

        if ($last && $last > $nic) {
            return response('<img src="/images/cross.gif"/>');
        }

        return response('OK');
    }

    // ─────────────────────────────────────────────
    //  ESTIMATE  (estimate.php)
    //  Create a new estimate (s_estimates)
    // ─────────────────────────────────────────────
    public function createEstimate()
    {
        $customers      = DB::table('customer_data')->orderBy('Customer_name')->get();
        $insurCompanies = DB::table('s_insurance_companies')->get();
        return view('service.jobcard.estimate', compact('customers', 'insurCompanies'));
    }

    public function storeEstimate(Request $request)
    {
        $request->validate([
            'estimate_type' => 'required|string',
            'cust_id'       => 'required|integer',
            'veh_id'        => 'required|integer',
        ]);

        DB::table('s_estimates')->insert([
            'sur_cont'      => $request->sur_cont,
            'estimate_type' => $request->estimate_type,
            'cust_id'       => $request->cust_id,
            'veh_id'        => $request->veh_id,
            'payment_mode'  => $request->payment_mode,
            'cust_type'     => $request->cust_type,
            'insur_company' => $request->insur_company,
            'surv_name'     => $request->surv_name,
            'surv_type'     => $request->surv_type,
            'est_delivery'  => $request->est_delivery,
            'user'          => Auth::user()->login_id,
            'entry_datetime'=> now(),
        ]);

        return redirect()->route('jobcard.unclosed-estimates')
            ->with('success', 'Estimate created.');
    }

    // ─────────────────────────────────────────────
    //  ESTIMATE RO  (estimate_ro.php)
    //  View RO details from estimate
    // ─────────────────────────────────────────────
    public function estimateRO($estimateId)
    {
        $estimate = DB::table('s_estimates as e')
            ->join('customer_data as c', 'e.cust_id', '=', 'c.Customer_id')
            ->join('vehicles_data as v', 'e.veh_id', '=', 'v.Vehicle_id')
            ->where('e.est_id', $estimateId)
            ->select('e.*', 'c.Customer_name', 'c.mobile', 'v.Registration', 'v.Variant')
            ->first();

        if (!$estimate) abort(404);

        $labors     = DB::table('s_est_labor')->where('estm_id', $estimateId)->get();
        $parts      = DB::table('s_est_parts')->where('estm_id', $estimateId)->get();
        $consumbles = DB::table('s_est_consumble')->where('estm_id', $estimateId)->get();
        $sublets    = DB::table('s_est_sublet')->where('estm_id', $estimateId)->get();

        return view('service.jobcard.estimate-ro', compact('estimate', 'labors', 'parts', 'consumbles', 'sublets'));
    }

    // ─────────────────────────────────────────────
    //  ESTIMATE 1 (estimate_1.php) - Estimate list / manage
    // ─────────────────────────────────────────────
    public function unclosedEstimates()
    {
        $estimates = DB::table('s_estimates as e')
            ->join('customer_data as c', 'e.cust_id', '=', 'c.Customer_id')
            ->join('vehicles_data as v', 'e.veh_id', '=', 'v.Vehicle_id')
            ->where('e.est_status', '0')
            ->select('e.*', 'c.Customer_name', 'c.mobile', 'v.Registration', 'v.Variant')
            ->orderByDesc('e.entry_datetime')
            ->get();

        return view('service.jobcard.unclosed-estimates', compact('estimates'));
    }

    // ─────────────────────────────────────────────
    //  ESTIMATE LABOR  (estm_labor.php)
    // ─────────────────────────────────────────────
    public function estimateLabor($estmId)
    {
        $estimate  = DB::table('s_estimates')->where('est_id', $estmId)->first();
        if (!$estimate) abort(404);
        $labors    = DB::table('s_est_labor')->where('estm_id', $estmId)->get();
        $laborList = DB::table('labor_list')->get();
        return view('service.jobcard.estm-labor', compact('estimate', 'labors', 'laborList', 'estmId'));
    }

    public function storeEstimateLabor(Request $request)
    {
        if ($request->jobrequest) {
            DB::table('s_est_labor')->insert([
                'estm_id' => $request->job_id,
                'Labor'   => $request->jobrequest,
                'cost'    => $request->price,
            ]);
        }
        return redirect()->route('jobcard.estimate.labor', $request->job_id)
            ->with('success', 'Labor added.');
    }

    // ─────────────────────────────────────────────
    //  ESTIMATE PART  (estm_part.php)
    // ─────────────────────────────────────────────
    public function estimatePart($estmId)
    {
        $estimate = DB::table('s_estimates')->where('est_id', $estmId)->first();
        if (!$estimate) abort(404);
        $parts    = DB::table('s_est_parts')->where('estm_id', $estmId)->get();
        return view('service.jobcard.estm-part', compact('estimate', 'parts', 'estmId'));
    }

    public function storeEstimatePart(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            DB::table('s_est_parts')->insert([
                'estm_id'          => $request->job_id,
                'part_description' => $request->part_description,
                'qty'              => $request->qty,
                'unitprice'        => $request->unitprice,
                'total'            => $request->totalprice,
            ]);
        }
        return redirect()->route('jobcard.estimate.part', $request->job_id)
            ->with('success', 'Part added.');
    }

    // ─────────────────────────────────────────────
    //  ESTIMATE CONSUMABLE  (estm_consumble.php)
    // ─────────────────────────────────────────────
    public function estimateConsumable($estmId)
    {
        $estimate   = DB::table('s_estimates')->where('est_id', $estmId)->first();
        if (!$estimate) abort(404);
        $consumbles = DB::table('s_est_consumble')->where('estm_id', $estmId)->get();
        return view('service.jobcard.estm-consumable', compact('estimate', 'consumbles', 'estmId'));
    }

    public function storeEstimateConsumable(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            DB::table('s_est_consumble')->insert([
                'estm_id'          => $request->job_id,
                'part_description' => $request->part_description,
                'qty'              => $request->qty,
                'unitprice'        => $request->unitprice,
                'total'            => $request->totalprice,
            ]);
        }
        return redirect()->route('jobcard.estimate.consumable', $request->job_id)
            ->with('success', 'Consumable added.');
    }

    // ─────────────────────────────────────────────
    //  ESTIMATE SUBLET  (estm_sublet.php)
    // ─────────────────────────────────────────────
    public function estimateSublet($estmId)
    {
        $estimate = DB::table('s_estimates')->where('est_id', $estmId)->first();
        if (!$estimate) abort(404);
        $sublets  = DB::table('s_est_sublet')->where('estm_id', $estmId)->get();
        return view('service.jobcard.estm-sublet', compact('estimate', 'sublets', 'estmId'));
    }

    public function storeEstimateSublet(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            DB::table('s_est_sublet')->insert([
                'estm_id'  => $request->job_id,
                'Sublet'   => $request->sublet,
                'type'     => $request->type,
                'qty'      => $request->qty,
                'unitprice'=> $request->unitprice,
                'total'    => $request->totalprice,
            ]);
        }
        return redirect()->route('jobcard.estimate.sublet', $request->job_id)
            ->with('success', 'Sublet added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL JOB REQUEST  (Additional_Jobrequest.php)
    //  Adds an additional labor item to an open jobcard
    // ─────────────────────────────────────────────
    public function additionalJobrequest($jobId)
    {
        $jobcard   = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard) abort(404);
        $labors    = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
        $laborList = DB::table('labor_list')->get();
        return view('service.jobcard.additional-jobrequest', compact('jobcard', 'labors', 'laborList', 'jobId'));
    }

    public function storeAdditionalJobrequest(Request $request)
    {
        if ($request->jobrequest) {
            $type  = $request->type;
            $price = ($type === 'Workshop') ? $request->price : 0;

            DB::table('jobc_labor')->insert([
                'RO_no'      => $request->job_id,
                'Labor'      => $request->jobrequest,
                'type'       => $type,
                'cost'       => $price,
                'reason'     => $request->reason ?? '',
                'Additional' => 1,
                'entry_time' => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.jobrequest', $request->job_id)
            ->with('success', 'Job request added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL PART  (Additional_part_add.php)
    // ─────────────────────────────────────────────
    public function additionalPart($jobId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard) abort(404);
        $parts   = DB::table('jobc_parts')->where('RO_no', $jobId)->get();
        return view('service.jobcard.additional-part', compact('jobcard', 'parts', 'jobId'));
    }

    public function storeAdditionalPart(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            DB::table('jobc_parts')->insert([
                'RO_no'            => $request->job_id,
                'part_description' => $request->part_description,
                'qty'              => $request->qty,
                'req_qty'          => $request->qty,
                'unitprice'        => $request->unitprice,
                'total'            => $request->totalprice,
                'Additional'       => 1,
                'entry_datetime'   => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.part', $request->job_id)
            ->with('success', 'Part added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL CONSUMABLE  (Additional_consumble.php)
    // ─────────────────────────────────────────────
    public function additionalConsumable($jobId)
    {
        $jobcard    = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard) abort(404);
        $consumbles = DB::table('jobc_consumble')->where('RO_no', $jobId)->get();
        return view('service.jobcard.additional-consumable', compact('jobcard', 'consumbles', 'jobId'));
    }

    public function storeAdditionalConsumable(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            DB::table('jobc_consumble')->insert([
                'RO_no'            => $request->job_id,
                'cons_description' => $request->part_description,
                'qty'              => $request->qty,
                'req_qty'          => $request->qty,
                'unitprice'        => $request->unitprice,
                'total'            => $request->totalprice,
                'Additional'       => 1,
                'entry_datetime'   => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.consumable', $request->job_id)
            ->with('success', 'Consumable added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL SUBLET  (Additional_sublet.php)
    // ─────────────────────────────────────────────
    public function additionalSublet($jobId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard) abort(404);
        $sublets = DB::table('jobc_sublet')->where('RO_no', $jobId)->get();
        return view('service.jobcard.additional-sublet', compact('jobcard', 'sublets', 'jobId'));
    }

    public function storeAdditionalSublet(Request $request)
    {
        if ($request->unitprice && !empty($request->unitprice)) {
            $type       = $request->type;
            $unitprice  = ($type === 'Workshop') ? $request->unitprice : 0;
            $totalprice = ($type === 'Workshop') ? $request->totalprice : 0;

            DB::table('jobc_sublet')->insert([
                'RO_no'          => $request->job_id,
                'Sublet'         => $request->sublet,
                'type'           => $type,
                'qty'            => $request->qty,
                'unitprice'      => $unitprice,
                'additional'     => 1,
                'total'          => $totalprice,
                'entry_datetime' => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.sublet', $request->job_id)
            ->with('success', 'Sublet added.');
    }

    // ─────────────────────────────────────────────
    //  DELETE LABOR / PART / SUBLET / CONSUMABLE  (delete_labor.php)
    // ─────────────────────────────────────────────
    public function deleteItem(Request $request)
    {
        if ($request->id) {
            // Delete labor (only if not yet assigned)
            DB::table('jobc_labor')
                ->where('Labor_id', $request->id)
                ->where('status', '')
                ->delete();
        } elseif ($request->Pid) {
            // Delete part (only if not in parts_sale)
            $exists = DB::table('jobc_parts_p')
                ->where('parts_sale_id', $request->Pid)
                ->exists();
            if (!$exists) {
                DB::table('jobc_parts')
                    ->where('parts_sale_id', $request->Pid)
                    ->where('status', '0')
                    ->delete();
            }
        } elseif ($request->sid) {
            // Delete sublet (only if not assigned)
            DB::table('jobc_sublet')
                ->where('sublet_id', $request->sid)
                ->where('status', '')
                ->delete();
        } elseif ($request->cnid) {
            // Delete consumable (only if not in consumble_p)
            $exists = DB::table('jobc_consumble_p')
                ->where('parts_sale_id', $request->cnid)
                ->exists();
            if (!$exists) {
                DB::table('jobc_consumble')
                    ->where('cons_sale_id', $request->cnid)
                    ->where('status', '0')
                    ->delete();
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL VIEW  (Additional.php - dashboard of open jobcard)
    // ─────────────────────────────────────────────
    public function additional($jobId)
    {
        $jobcard = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jc.Jobc_id', $jobId)
            ->select('jc.*', 'v.Registration', 'v.Variant', 'v.Frame_no',
                     'c.Customer_name', 'c.mobile', 'c.Customer_id')
            ->first();

        if (!$jobcard) abort(404);

        $labors     = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
        $parts      = DB::table('jobc_parts')->where('RO_no', $jobId)->get();
        $consumbles = DB::table('jobc_consumble')->where('RO_no', $jobId)->get();
        $sublets    = DB::table('jobc_sublet')->where('RO_no', $jobId)->get();

        return view('service.jobcard.additional', compact(
            'jobcard', 'labors', 'parts', 'consumbles', 'sublets', 'jobId'
        ));
    }

    // ─────────────────────────────────────────────
    //  AJAX: VARIANT AUTOCOMPLETE  (ajax.php)
    // ─────────────────────────────────────────────
    public function ajaxVariant(Request $request)
    {
        $name    = $request->input('name_startsWith', '');
        $rowNum  = $request->input('row_num', 0);

        $variants = DB::table('variant_codes')
            ->whereRaw("UPPER(Model) LIKE ?", [strtoupper($name) . '%'])
            ->select('Model', 'Variant', 'Make', 'Fram', 'Engine')
            ->get();

        $data = $variants->map(function ($row) use ($rowNum) {
            return $row->Model . '|' . $row->Variant . '|' . $row->Make . '|' . $row->Engine . '|' . $rowNum;
        })->values()->all();

        return response()->json($data);
    }

    // Additional jobs list (all status=1 jobs for SA)
    public function additionalList()
    {
        $jobs = \DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jc.status', '1')
            ->where('jc.SA', session('login_id'))
            ->orderBy('jc.Jobc_id', 'desc')
            ->select('jc.Jobc_id', 'jc.Open_date_time', 'jc.MSI_cat', 'jc.SA', 'jc.comp_appointed',
                     'v.Registration', 'v.Variant', 'c.Customer_name', 'c.mobile')
            ->get();

        return view('service.jobcard.additional', compact('jobs'));
    }

    public function complete()
    {
        $jobs = \DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '1')
            ->where('jc.SA', session('login_id'))
            ->select('jc.Jobc_id', 'jc.Veh_reg_no', 'jc.SA', 'jc.status')
            ->get();

        return view('service.jobcard.complete', compact('jobs'));
    }

    public function completeProcess(\Illuminate\Http\Request $request)
    {
        \DB::table('jobcard')
            ->where('Jobc_id', $request->job_id)
            ->update(['status' => '2', 'closing_time' => now()]);

        return redirect()->route('jobcard.complete');
    }

    public function statusLabor()      { return view('service.jobcard.status-labor'); }
    public function statusParts()      { return view('service.jobcard.status-parts'); }
    public function statusSublet()     { return view('service.jobcard.status-sublet'); }
    public function statusConsumable() { return view('service.jobcard.status-consumable'); }

    public function newLabor()         { return view('service.jobcard.new-labor'); }
    public function newPart()          { return view('service.jobcard.new-part'); }
    public function newConsumable()    { return view('service.jobcard.new-consumable'); }

    public function search(\Illuminate\Http\Request $request)
    {
        return view('service.jobcard.search', ['results' => collect()]);
    }
}
