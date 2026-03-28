<?php

namespace App\Http\Controllers\Service\Jobcard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class JobcardController extends Controller
{
    // ─────────────────────────────────────────────
    //  DASHBOARD / ALL UNCLOSED JC LIST
    //  Original: Jobcard/index.php
    //  Shows status 0 AND 1 for all SAs (overview)
    // ─────────────────────────────────────────────
    public function index()
    {
        $unclosedJobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', 0)
            ->where('jc.SA', session('login_id'))
            ->select(
                'jc.Jobc_id',
                'jc.Open_date_time',
                'jc.SA',
                'jc.RO_type',
                'jc.status',
                'jc.Mileage',
                'jc.comp_appointed',
                'jc.MSI_cat',
                'jc.Customer_name',
                'jc.Customer_id',
                'v.Registration',
                'v.Variant',
                'v.Frame_no'
            )
            ->orderBy('jc.Open_date_time', 'desc')
            ->get();

        return view('service.jobcard.index', compact('unclosedJobs'));
    }

    // ─────────────────────────────────────────────
    //  STEP 1: SEARCH VEHICLE PAGE  (index.php form)
    // ─────────────────────────────────────────────
    public function searchVehicle()
    {
        return view('service.jobcard.add-vehicle');
    }

    public function searchVehicleResult(Request $request)
    {
        $reg = strtoupper(trim($request->input('Registration', '')));
        $fram = strtoupper(trim($request->input('fram', '')));
        $reserved = ['NEW', 'AFR', 'APL'];

        if ($reg && in_array($reg, $reserved)) {
            return back()->withErrors(['Registration' => 'Wrong Registration Number entered!']);
        }
        if ($fram && in_array($fram, $reserved)) {
            return back()->withErrors(['fram' => 'Wrong Frame Number entered!']);
        }

        $field = $reg ? 'Registration' : 'Frame_no';
        $value = $reg ?: $fram;

        if (empty($value)) {
            return back()->withErrors(['search' => 'Please enter a Registration or Frame number.']);
        }

        $vehicleId = DB::table('vehicles_data')
            ->where($field, $value)
            ->orderByDesc('Vehicle_id')
            ->value('Vehicle_id');

        if (!$vehicleId) {
            return redirect()->route('jobcard.add-vehicle.new', ['field' => $field, 'reg_fam' => $value]);
        }

        return redirect()->route('jobcard.vehicle-detail', ['vehicle_id' => $vehicleId]);
    }

    // ─────────────────────────────────────────────
    //  STEP 2a: NEW VEHICLE FORM  (add_veh.php)
    // ─────────────────────────────────────────────
    public function newVehicleForm(Request $request)
    {
        $field = $request->query('field', 'Registration');
        $regFam = $request->query('reg_fam', '');
        return view('service.jobcard.new-vehicle', compact('field', 'regFam'));
    }

    public function storeNewVehicle(Request $request)
    {
        $request->validate([
            'registration' => 'required|string|max:20',
            'varaint' => 'required|string|max:100',
        ]);

        $intosell = $request->has('intosell') ? 'on' : '';

        DB::table('vehicles_data')->insert([
            'Customer_id' => 0,
            'Registration' => strtoupper($request->registration),
            'Frame_no' => strtoupper($request->fram ?? ''),
            'Engine_code' => $request->engine ?? '',
            'Engine_number' => $request->engine_no ?? '',
            'cust_id' => 0,
            
            'Wrnty_book_no' => 'nil',
            'Insurance' => 'nil',
            'user' => Auth::user()->login_id ?? 'unkown',
            'updated_by' => Auth::user()->login_id ?? 'unkown',
            'own_vehicle' => 'nil',
            'v_status' => 'nil',
            'Variant' => $request->varaint,
            'Model' => $request->model ?? '',
            'Colour' => $request->color ?? '',
            'Make' => $request->make ?? '',
            'into_sell' => $intosell,
            'model_year' => $request->model_year ?? '',
            'demand_price' => $intosell ? ($request->demandprice ?? '') : '',
            'Update_date' => now()->toDateString(),
        ]);

        $vehicleId = DB::table('vehicles_data')
            ->where('Registration', strtoupper($request->registration))
            ->orderByDesc('Vehicle_id')
            ->value('Vehicle_id');

        return redirect()->route('jobcard.vehicle-detail', ['vehicle_id' => $vehicleId])
            ->with('success', 'Vehicle added. Please add a customer.');
    }

    // ─────────────────────────────────────────────
    //  STEP 2b: VEHICLE DETAIL PAGE  (jobcard_2.php)
    //  Shows vehicle + all linked customers
    // ─────────────────────────────────────────────
    public function vehicleDetail(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        if (!$vehicleId)
            abort(404);

        $vehicle = DB::table('vehicles_data')->where('Vehicle_id', $vehicleId)->first();
        if (!$vehicle)
            abort(404);

        // Original jobcard_2.php query:
        // SELECT ... FROM s_cust_veh
        // INNER JOIN customer_data ON s_cust_veh.cust_id=customer_data.Customer_id
        // INNER JOIN vehicles_data ON s_cust_veh.veh_id=vehicles_data.Vehicle_id
        // WHERE s_cust_veh.veh_id='X' OR vehicles_data.Vehicle_id='X'
        // ORDER BY s_cust_veh.veh_id DESC
        $customers = DB::table('s_cust_veh as scv')
            ->join('customer_data as c', 'scv.cust_id', '=', 'c.Customer_id')
            ->join('vehicles_data as v', 'scv.veh_id', '=', 'v.Vehicle_id')
            ->where(function ($q) use ($vehicleId) {
                $q->where('scv.veh_id', $vehicleId)
                    ->orWhere('v.Vehicle_id', $vehicleId);
            })
            ->select(
                'c.Customer_id',
                'c.Customer_name',
                'c.mobile',
                'c.cust_type',
                'v.Vehicle_id',
                'v.Registration',
                'v.Frame_no',
                'v.Variant',
                'v.Model',
                'v.model_year'
            )
            ->orderByDesc('scv.veh_id')
            ->get();

        // Validate model code exists in variant_codes
        $modelValid = $vehicle->Model
            ? DB::table('variant_codes')->where('Model', $vehicle->Model)->exists()
            : false;

        return view('service.jobcard.vehicle-detail', compact('customers', 'vehicle', 'vehicleId', 'modelValid'));
    }

    // ─────────────────────────────────────────────
    //  ADD NEW CUSTOMER  (Forsale/add_customer.php)
    // ─────────────────────────────────────────────
    public function addCustomerForm(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        if (!$vehicleId)
            abort(404);
        return view('service.jobcard.add-customer', compact('vehicleId'));
    }

    public function storeCustomer(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:20',
            'vehicle_id' => 'required|integer',
        ]);

        $vehicleId = $request->vehicle_id;
        $name = strtoupper($request->name);

        if (strpos($name, '----') !== false) {
            // Existing customer — just link
            DB::table('s_cust_veh')->insert(['cust_id' => $request->email, 'veh_id' => $vehicleId]);
        } else {
            $customerId = DB::table('customer_data')->insertGetId([
                'Vehicle_id' => $vehicleId,
                'old_id' => 'nill',
                'NTN' => 'nill',
                'STRN' => 'nill',
                'Supplier' => 'nill',
                'c_status' => 'nill',
                'updated_by' => Auth::user()->login_id ?? 'unkown',
                'cust_type' => $request->cust_type ?? '',
                'contact_type' => $request->contact_type ?? '',
                'Customer_name' => $name,
                'off_phone' => $request->off_phone ?? '',
                'mobile' => $request->mobile,
                'DOB' => $request->dob ?? null,
                'City' => $request->city ?? '',
                'Region' => $request->region ?? '',
                'Address' => $request->address ?? '',
                'email' => $request->email ?? '',
                'CNIC' => $request->cnic ?? '',

                'user' => Auth::user()->login_id,
                'update_date' => now()->toDateTimeString(),
                'created_at' => now(),
                'updated_at' => now(),

            ]);
            DB::table('s_cust_veh')->insert(['cust_id' => $customerId, 'veh_id' => $vehicleId]);
        }

        return redirect()->route('jobcard.vehicle-detail', ['vehicle_id' => $vehicleId])
            ->with('success', 'Customer added successfully.');
    }

    // ─────────────────────────────────────────────
    //  EDIT CUSTOMER  (files/cust_edit.php)
    // ─────────────────────────────────────────────
    public function editCustomer(Request $request, $customerId)
    {
        $customer = DB::table('customer_data')->where('Customer_id', $customerId)->first();
        if (!$customer)
            abort(404);

        $roNo = $request->query('ro_no');
        $vehicleId = $request->query('vehicle_id');

        return view('service.jobcard.customer-edit', compact('customer', 'roNo', 'vehicleId'));
    }

    public function updateCustomer(Request $request)
    {
        $request->validate([
            'cust_id' => 'required|integer',
            'mobile' => 'required|string|max:20',
            'name' => 'required|string|max:255',
        ]);

        DB::table('customer_data')
            ->where('Customer_id', $request->cust_id)
            ->update([
                'cust_type' => $request->cust_type,
                'Customer_name' => strtoupper($request->name),
                'DOB' => $request->dob,
                'City' => $request->city,
                'Region' => $request->region,
                'off_phone' => $request->off_phone,
                'mobile' => $request->mobile,
                'Address' => $request->address,
                'email' => $request->email,
                'NTN' => $request->ntn,
                'STRN' => $request->strn,
                'Supplier' => $request->supplier,
                'CNIC' => $request->cnic,
                'updated_by' => Auth::user()->login_id,
                'Update_date' => now(),
            ]);

        if ($request->ro_no) {
            return redirect()->route('cashier.tax-invoice-get', $request->ro_no)
                ->with('success', 'Customer updated.');
        }

        return redirect()->route('jobcard.vehicle-detail', ['vehicle_id' => $request->veh_id])
            ->with('success', 'Customer updated.');
    }

    // ─────────────────────────────────────────────
    //  STEP 3: OPEN RO FORM  (Jobcard_3.php)
    //  Full jobcard details form
    // ─────────────────────────────────────────────
    public function createJobcard(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        $customerId = $request->query('customer_id');

        if (!$vehicleId || !$customerId)
            abort(404);

        $vehicle = DB::table('vehicles_data')->where('Vehicle_id', $vehicleId)->first();
        $customer = DB::table('customer_data')->where('Customer_id', $customerId)->first();

        if (!$vehicle || !$customer)
            abort(404);

        $campaigns = DB::table('s_campaigns')
            ->whereDate('c_to', '>=', now()->toDateString())
            ->whereDate('c_from', '<=', now()->toDateString())
            ->where('status', 'Active')
            
            ->orderByDesc('campaign_id')
            ->pluck('campaign_name');


        return view('service.jobcard.create-jobcard', compact(
            'vehicle',
            'customer',
            'vehicleId',
            'customerId',
            'campaigns',

        ));
    }

    // ─────────────────────────────────────────────
    //  STEP 3 POST: STORE JOBCARD  (Jobcard_3.php POST)
    // ─────────────────────────────────────────────
    public function storeJobcard(Request $request)
    {
        $request->validate([
            'veh_id' => 'required|integer',
            'cust_id' => 'required|integer',
            'ro_type' => 'required|string',
            'milage' => 'required|integer|min:0',
            'VOC' => 'required|string|min:5',
        ]);

        $vehId = $request->veh_id;
        $custId = $request->cust_id;
        $vehReg = strtoupper($request->veh_reg ?? '');
        $custName = strtoupper($request->cust_name ?? '');
        $frameNo = $request->Frame_no ?? '';
        $SA = Auth::user()->login_id;

        // Check appointment ±3 days
        $custSource = $request->cust_source ?? 'None';
        $apptRow = DB::table('cr_appointments')
            ->where(function ($q) use ($vehId, $vehReg) {
                $q->where('veh_id', $vehId)->orWhere('veh_details', $vehReg);
            })
            ->whereBetween(DB::raw("DATE(appt_datetime)"), [
                now()->subDays(3)->toDateString(),
                now()->addDays(3)->toDateString(),
            ])
            ->first();

        if ($apptRow) {
            $custSource = 'Appointed';
        }

        // Prevent duplicate on same day
        $existing = DB::table('jobcard')
            ->where('Vehicle_id', $vehId)
            ->where('SA', $SA)
            ->whereDate('Open_date_time', now()->toDateString())
            ->where('status', '0')
            ->first();

        if ($existing) {
            return redirect()->route('jobcard.checklist', $existing->Jobc_id);
        }

        $jobcId = DB::table('jobcard')->insertGetId([
            'Vehicle_id' => $vehId,
            'Customer_id' => $custId,
            'Customer_name' => $custName,
            'Veh_reg_no' => $vehReg,
            'comp_appointed' => $request->compaign ?? 'None',
            'cust_source' => $custSource,
            'MSI_cat' => $request->msi_category ?? '',
            'RO_type' => $request->ro_type,
            'serv_nature' => $request->serv_nature ?? '',
            'Fuel' => $request->fuel ?? 'Half',
            'Mileage' => $request->milage,
            'cust_waiting' => 0,
            'VOC' => $request->VOC,
            'Estim_time' => $request->estimat_time ?: null,
            'closing_time' => $request->estimat_time ?: null,
            'Estim_cost' => $request->estimatedcost ?? '',
            'Diagnose_by' => $request->Diagnozer ?? '',
            'SA' => $SA,
            'Open_date_time' => now(),
            'PSFU' => 0,
            'status' => 0,
            'rating_done' => 0,
            'PM_status' => 0,
            'RO_no' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Update appointment
        if ($apptRow) {
            DB::table('cr_appointments')
                ->where('app_id', $apptRow->app_id)
                ->update(['mature_datetime' => now(), 'mature' => '1', 'ro_no' => $jobcId]);
        }

        // VIN check
        if ($frameNo) {
            $lastDigits = substr($frameNo, -7);
            $vinRow = DB::table('s_uploaded_frams')
                ->where('VIN', $lastDigits)
                ->whereNotIn('uploaded_id', [35, 36, 37])
                ->first();

            if ($vinRow) {
                $listRow = DB::table('s_upload_listname')->where('list_id', $vinRow->uploaded_id)->first();
                $listName = $listRow ? $listRow->list_name : 'a recall list';

                DB::table('s_vin_check')->insert([
                    'jobcard' => $jobcId,
                    'frameno' => $vinRow->VIN,
                    'listid' => $vinRow->uploaded_id,
                    'full_vin' => $vinRow->full_VIN,
                    'veh_id' => $vehId,
                    'cust_name' => $custName,
                    'cust_id' => $custId,
                    'veh_reg' => $vehReg,
                ]);

                return redirect()->route('jobcard.checklist', $jobcId)
                    ->with('vin_warning', "This Vehicle Frame No is in {$listName}. Please open an RO for that as well.");
            }
        }

        return redirect()->route('jobcard.checklist', $jobcId);
    }

    // ─────────────────────────────────────────────
    //  STEP 4: CHECKLIST  (jobcard_4.php)
    // ─────────────────────────────────────────────
    public function checklist($jobcId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobcId)->first();
        if (!$jobcard)
            abort(404);
        return view('service.jobcard.checklist', compact('jobcard', 'jobcId'));
    }

    public function storeChecklist(Request $request)
    {
        $jobcId = $request->jobc_id;

        DB::table('jobc_checklist')->insert([
            'RO_id' => $jobcId,
            'usb' => $request->has('USB') ? 1 : 0,
            'cardreader' => $request->has('Reader') ? 1 : 0,
            'ashtray' => $request->has('AshTray') ? 1 : 0,
            'lighter' => $request->has('Lighter') ? 1 : 0,
            'wiperblader' => $request->has('WiperBlades') ? 1 : 0,
            'seatcover' => $request->has('SeatCovers') ? 1 : 0,
            'dickymat' => $request->has('DickeyMat') ? 1 : 0,
            'sparewheel' => $request->has('SpareWheel') ? 1 : 0,
            'jackhandle' => $request->has('JackHandle') ? 1 : 0,
            'tools' => $request->has('Tools') ? 1 : 0,
            'perfume' => $request->has('Perfume') ? 1 : 0,
            'remote' => $request->has('Remote') ? 1 : 0,
            'floormate' => $request->has('FloorMats') ? 1 : 0,
            'mirror' => $request->has('RearViewMirrors') ? 1 : 0,
            'cassete' => $request->has('Cassettes') ? 1 : 0,
            'hubcaps' => $request->has('Hubcaps') ? 1 : 0,
            'wheelcaps' => $request->has('Wheelcaps') ? 1 : 0,
            'monogram' => $request->has('Monograms') ? 1 : 0,
            'extrakeys' => $request->has('Noofkeys') ? 1 : 0,
            'anttena' => $request->has('RadioAntenna') ? 1 : 0,
            'clock' => $request->has('Clock') ? 1 : 0,
            'Navigation' => $request->has('Nav_sys') ? 1 : 0,
        ]);

        return redirect()->route('jobcard.index')
            ->with('success', "RO #{$jobcId} opened successfully.");
    }

    // ─────────────────────────────────────────────
    //  MILEAGE CHECK AJAX  (files/check.php)
    // ─────────────────────────────────────────────
    public function checkMileage(Request $request)
    {
        $nic = $request->input('NIC');
        $vehId = $request->input('veh_id');

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
    //  UNCLOSED JC LIST  (Unclosed_JC.php)
    //  Status=0 for current SA — with Start Working
    // ─────────────────────────────────────────────
    public function unclosedList()
    {
        $SA = Auth::user()->login_id;

        $jobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jc.status', '0')
            ->where('jc.SA', $SA)
            ->select(
                'jc.Jobc_id',
                'jc.Open_date_time',
                'jc.comp_appointed',
                'jc.MSI_cat',
                'v.Registration',
                'v.Variant',
                'c.Customer_name',
                'c.mobile'
            )
            ->orderByDesc('jc.Jobc_id')
            ->get();

        return view('service.jobcard.unclosed-jc', compact('jobs'));
    }

    // POST: Start Working
    public function startWorking(Request $request)
    {
        $jobId = $request->job_id;
        $compAppointed = $request->comp_appointed;

        // Insert campaign labors
        $campaignLabors = DB::table('s_compaingh_labour as cl')
            ->join('s_campaigns as sc', 'cl.compaingh_id', '=', 'sc.campaign_id')
            ->where('sc.campaign_name', $compAppointed)
            ->select('cl.labour_des', 'cl.labour_cost')
            ->get();

        foreach ($campaignLabors as $labor) {
            DB::table('jobc_labor')->insert([
                'RO_no' => $jobId,
                'Labor' => $labor->labour_des,
                'Additional' => 0,
                'reason' => 'nul',
                'type' => 'Workshop',
                'cost' => $labor->labour_cost,
                'entry_time' => now(),
                'estimated_time' => now(),
                'Assign_time' => now(),
                'end_time' => now(),
                'team' => 'nul',
                'bay' => 'nul',
                'remarks' => 'nul',
                'resumetime' => now(),
                'jc' => 'nil',
                'status' => 0,
            ]);
        }

        $laborCount = DB::table('jobc_labor')->where('RO_no', $jobId)->count();

        if ($laborCount === 0) {
            return back()->with('error', "Please first assign a Labor on RO #{$jobId}!");
        }

        DB::table('jobcard')
            ->where('Jobc_id', $jobId)
            ->update(['status' => '1', 'Open_date_time' => now()]);

        return redirect()->route('jobcard.index')
            ->with('success', "RO #{$jobId} sent to workshop.");
    }

    // ─────────────────────────────────────────────
    //  ESTIMATE  (estimate.php)
    // ─────────────────────────────────────────────
    public function createEstimate()
    {
        $customers = DB::table('customer_data')->orderBy('Customer_name')->get();
        $insurCompanies = DB::table('s_insurance_companies')->get();
        return view('service.jobcard.estimate', compact('customers', 'insurCompanies'));
    }

    public function storeEstimate(Request $request)
    {
        $request->validate([
            'estimate_type' => 'required|string',
            'cust_id' => 'required|integer',
            'veh_id' => 'required|integer',
        ]);

        DB::table('s_estimates')->insert([
            'sur_cont' => $request->sur_cont,
            'estimate_type' => $request->estimate_type,
            'cust_id' => $request->cust_id,
            'veh_id' => $request->veh_id,
            'payment_mode' => $request->payment_mode,
            'cust_type' => $request->cust_type,
            'insur_company' => $request->insur_company,
            'surv_name' => $request->surv_name,
            'surv_type' => $request->surv_type,
            'est_delivery' => $request->est_delivery,
            'user' => Auth::user()->login_id,
            'entry_datetime' => now(),
        ]);

        return redirect()->route('jobcard.unclosed-estimates')
            ->with('success', 'Estimate created.');
    }

    public function estimateRO($estimateId)
    {
        $estimate = DB::table('s_estimates as e')
            ->join('customer_data as c', 'e.cust_id', '=', 'c.Customer_id')
            ->join('vehicles_data as v', 'e.veh_id', '=', 'v.Vehicle_id')
            ->where('e.est_id', $estimateId)
            ->select('e.*', 'c.Customer_name', 'c.mobile', 'v.Registration', 'v.Variant')
            ->first();

        if (!$estimate)
            abort(404);

        $labors = DB::table('s_est_labor')->where('estm_id', $estimateId)->get();
        $parts = DB::table('s_est_parts')->where('estm_id', $estimateId)->get();
        $consumbles = DB::table('s_est_consumble')->where('estm_id', $estimateId)->get();
        $sublets = DB::table('s_est_sublet')->where('estm_id', $estimateId)->get();

        return view('service.jobcard.estimate-ro', compact('estimate', 'labors', 'parts', 'consumbles', 'sublets'));
    }

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

    public function estimateLabor($estmId)
    {
        $estimate = DB::table('s_estimates')->where('est_id', $estmId)->first();
        if (!$estimate)
            abort(404);
        $labors = DB::table('s_est_labor')->where('estm_id', $estmId)->get();
        $laborList = DB::table('labor_list')->get();
        return view('service.jobcard.estm-labor', compact('estimate', 'labors', 'laborList', 'estmId'));
    }

    public function estimateLaborStore(Request $request)
    {
        if ($request->jobrequest) {
            DB::table('s_est_labor')->insert([
                'estm_id' => $request->job_id,
                'Labor' => $request->jobrequest,
                'cost' => $request->price,
            ]);
        }
        return redirect()->route('jobcard.estimate.labor', $request->job_id)->with('success', 'Labor added.');
    }

    public function estimatePart($estmId)
    {
        $estimate = DB::table('s_estimates')->where('est_id', $estmId)->first();
        if (!$estimate)
            abort(404);
        $parts = DB::table('s_est_parts')->where('estm_id', $estmId)->get();
        return view('service.jobcard.estm-part', compact('estimate', 'parts', 'estmId'));
    }

    public function estimatePartStore(Request $request)
    {
        if ($request->unitprice) {
            DB::table('s_est_parts')->insert([
                'estm_id' => $request->job_id,
                'part_description' => $request->part_description,
                'qty' => $request->qty,
                'unitprice' => $request->unitprice,
                'total' => $request->totalprice,
            ]);
        }
        return redirect()->route('jobcard.estimate.part', $request->job_id)->with('success', 'Part added.');
    }

    public function estimateConsumable($estmId)
    {
        $estimate = DB::table('s_estimates')->where('est_id', $estmId)->first();
        if (!$estimate)
            abort(404);
        $consumbles = DB::table('s_est_consumble')->where('estm_id', $estmId)->get();
        return view('service.jobcard.estm-consumable', compact('estimate', 'consumbles', 'estmId'));
    }

    public function estimateConsumableStore(Request $request)
    {
        if ($request->unitprice) {
            DB::table('s_est_consumble')->insert([
                'estm_id' => $request->job_id,
                'part_description' => $request->part_description,
                'qty' => $request->qty,
                'unitprice' => $request->unitprice,
                'total' => $request->totalprice,
            ]);
        }
        return redirect()->route('jobcard.estimate.consumable', $request->job_id)->with('success', 'Consumable added.');
    }

    public function estimateSublet($estmId)
    {
        $estimate = DB::table('s_estimates')->where('est_id', $estmId)->first();
        if (!$estimate)
            abort(404);
        $sublets = DB::table('s_est_sublet')->where('estm_id', $estmId)->get();
        return view('service.jobcard.estm-sublet', compact('estimate', 'sublets', 'estmId'));
    }

    public function estimateSubletStore(Request $request)
    {
        if ($request->unitprice) {
            DB::table('s_est_sublet')->insert([
                'estm_id' => $request->job_id,
                'Sublet' => $request->sublet,
                'type' => $request->type,
                'qty' => $request->qty,
                'unitprice' => $request->unitprice,
                'total' => $request->totalprice,
            ]);
        }
        return redirect()->route('jobcard.estimate.sublet', $request->job_id)->with('success', 'Sublet added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL VIEW  (Additional.php single RO)
    // ─────────────────────────────────────────────
    public function additional($jobId)
    {
        $jobcard = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jc.Jobc_id', $jobId)
            ->select(
                'jc.*',
                'v.Registration',
                'v.Variant',
                'v.Frame_no',
                'c.Customer_name',
                'c.mobile',
                'c.Customer_id'
            )
            ->first();

        if (!$jobcard)
            abort(404);

        $labors = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
        $parts = DB::table('jobc_parts')->where('RO_no', $jobId)->get();
        $consumbles = DB::table('jobc_consumble')->where('RO_no', $jobId)->get();
        $sublets = DB::table('jobc_sublet')->where('RO_no', $jobId)->get();

        return view('service.jobcard.additional', compact(
            'jobcard',
            'labors',
            'parts',
            'consumbles',
            'sublets',
            'jobId'
        ));
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL OVERVIEW  (JSON for modal)
    // ─────────────────────────────────────────────
    public function additionalOverviewJson($jobId)
    {
        $jobcard = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jc.Jobc_id', $jobId)
            ->select('jc.*', 'v.Registration', 'v.Variant', 'c.Customer_name', 'c.mobile')
            ->first();

        if (!$jobcard) return response()->json(['error' => 'Not found'], 404);

        $labors    = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
        $parts     = DB::table('jobc_parts')->where('RO_no', $jobId)->get();
        $consumbles= DB::table('jobc_consumble')->where('RO_no', $jobId)->get();
        $sublets   = DB::table('jobc_sublet')->where('RO_no', $jobId)->get();

        return response()->json([
            'jobcard'   => $jobcard,
            'labors'    => $labors,
            'parts'     => $parts,
            'consumbles'=> $consumbles,
            'sublets'   => $sublets,
            'totals' => [
                'labor'     => $labors->where('type', 'Workshop')->sum('cost'),
                'parts'     => $parts->sum('total'),
                'consumble' => $consumbles->sum('total'),
                'sublet'    => $sublets->where('type', 'Workshop')->sum('total'),
                'grand'     => $labors->where('type','Workshop')->sum('cost')
                             + $parts->sum('total')
                             + $consumbles->sum('total')
                             + $sublets->where('type','Workshop')->sum('total'),
            ],
        ]);
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL JOBREQUEST  (Additional_Jobrequest.php)
    // ─────────────────────────────────────────────
    public function additionalJobrequest($jobId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard)
            abort(404);
        $labors = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
        $laborList = DB::table('labor_list')->get();
        return view('service.jobcard.additional-jobrequest', compact('jobcard', 'labors', 'laborList', 'jobId'));
    }

    public function additionalJobrequestStore(Request $request)
    {
        if ($request->jobrequest) {
            $type = $request->type;
            $price = ($type === 'Workshop') ? $request->price : 0;
            DB::table('jobc_labor')->insert([
                'RO_no' => $request->job_id,
                'Labor' => $request->jobrequest,
                'type' => $type,
                'cost' => $price,
                'reason' => $request->reason ?? '',
                'Additional' => 0,
                'status' => 0,
                'team' => 'null',
                'bay' => "null",
                'remarks' => "null",
                'resumetime' => "null",
                'jc' => "null",
                'end_time' => now(),
                'Assign_time' => now(),
                'estimated_time' => now(),
                'entry_time' => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.jobrequest', $request->job_id)->with('success', 'Labor added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL PART  (Additional_part_add.php)
    // ─────────────────────────────────────────────
    public function additionalPart($jobId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard)
            abort(404);
        $parts = DB::table('jobc_parts')->where('RO_no', $jobId)->get();
        $partsList = DB::table('s_new_parts')->orderBy('Description')->pluck('Description');
        return view('service.jobcard.additional-part', compact('jobcard', 'parts', 'jobId', 'partsList'));
    }

    public function additionalPartStore(Request $request)
    {
        if ($request->unitprice) {
            DB::table('jobc_parts')->insert([
                'RO_no' => $request->job_id,
                'part_description' => $request->part_description,
                'qty' => $request->qty,
                'req_qty' => $request->qty,
                'unitprice' => $request->unitprice,
                'total' => $request->totalprice,
                'Additional' => 0,
                'part_invoice_no' => 0,
                'issued_qty' => 0,
                'issue_to' => 'null',
                'issue_time' => now(),
                'Stock_id' => 0,
                'status' => 0,
                'issue_by' => 'null',
                'p_return' => 0,
                'incentive_status' => 0,
                'part_number' => 'null',


                'entry_datetime' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.part', $request->job_id)->with('success', 'Part added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL CONSUMABLE  (Additional_consumble.php)
    // ─────────────────────────────────────────────
    public function additionalConsumable($jobId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard)
            abort(404);
        $consumbles = DB::table('jobc_consumble')->where('RO_no', $jobId)->get();
        $consumableList = DB::table('s_list_consumble')->orderBy('consumble')->pluck('consumble');
        return view('service.jobcard.additional-consumable', compact('jobcard', 'consumbles', 'jobId', 'consumableList'));
    }

    public function additionalConsumableStore(Request $request)
    {
        if ($request->unitprice) {
            DB::table('jobc_consumble')->insert([
                'RO_no' => $request->job_id,
                'cons_description' => $request->part_description,
                'qty' => $request->qty,
                'req_qty' => $request->qty,
                'unitprice' => $request->unitprice,
                'total' => $request->totalprice,
                'Additional' => 0,
                'cons_req_no' => 0,
                'cons_number' => 'null',
                'issue_to' => 'null',
                'issue_time' => now(),
                'status' => 0,
                'issue_by' => 'null',
                'p_return' => 0,
                'incentive_status' => 0,
                'issued_qty' => 0,
                'Stock_id' => 0,
                'entry_datetime' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.consumable', $request->job_id)->with('success', 'Consumable added.');
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL SUBLET  (Additional_sublet.php)
    // ─────────────────────────────────────────────
    public function additionalSublet($jobId)
    {
        $jobcard = DB::table('jobcard')->where('Jobc_id', $jobId)->first();
        if (!$jobcard)
            abort(404);
        $sublets = DB::table('jobc_sublet')->where('RO_no', $jobId)->get();
        return view('service.jobcard.additional-sublet', compact('jobcard', 'sublets', 'jobId'));
    }

    public function additionalSubletStore(Request $request)
    {
        if ($request->unitprice) {
            $type = $request->type;
            DB::table('jobc_sublet')->insert([
                'RO_no' => $request->job_id,
                'Sublet' => $request->sublet,
                'type' => $type,
                'qty' => $request->qty,
                'unitprice' => ($type === 'Workshop') ? $request->unitprice : 0,
                'additional' => 0,
                'status' => 0,
                'jc' => 0,
                'end_time' => now(),
                'Asign_time' => now(),
                'parts_details' => 'null',
                'Vendor' => 'null',
                'who_taking' => 'null',
                'Vendor_price' => 0,
                'logistics' => 0,
                'total' => ($type === 'Workshop') ? $request->totalprice : 0,
                'entry_datetime' => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.sublet', $request->job_id)->with('success', 'Sublet added.');
    }


    // ─────────────────────────────────────────────
    //  POST-WORK ADDITIONAL STORES
    //  Called ONLY from Additional screens after Start Working
    //  Always sets Additional = 1
    // ─────────────────────────────────────────────

    public function postWorkJobrequestStore(Request $request)
    {
        if ($request->jobrequest) {
            $type  = $request->type;
            $price = ($type === 'Workshop') ? $request->price : 0;
            DB::table('jobc_labor')->insert([
                'RO_no'          => $request->job_id,
                'Labor'          => $request->jobrequest,
                'type'           => $type,
                'cost'           => $price,
                'reason'         => $request->reason ?? '',
                'Additional'     => 1,
                'status'         => 0,
                'team'           => 'null',
                'bay'            => 'null',
                'remarks'        => 'null',
                'resumetime'     => 'null',
                'jc'             => 'null',
                'end_time'       => now(),
                'Assign_time'    => now(),
                'estimated_time' => now(),
                'entry_time'     => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.jobrequest', $request->job_id)->with('success', 'Labor added.');
    }

    public function postWorkPartStore(Request $request)
    {
        if ($request->unitprice) {
            DB::table('jobc_parts')->insert([
                'RO_no'            => $request->job_id,
                'part_description' => $request->part_description,
                'qty'              => $request->qty,
                'req_qty'          => $request->qty,
                'unitprice'        => $request->unitprice,
                'total'            => $request->totalprice,
                'Additional'       => 1,
                'part_invoice_no'  => 0,
                'issued_qty'       => 0,
                'issue_to'         => 'null',
                'issue_time'       => now(),
                'Stock_id'         => 0,
                'status'           => 0,
                'issue_by'         => 'null',
                'p_return'         => 0,
                'incentive_status' => 0,
                'part_number'      => 'null',
                'entry_datetime'   => now(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.part', $request->job_id)->with('success', 'Part added.');
    }

    public function postWorkConsumableStore(Request $request)
    {
        if ($request->unitprice) {
            DB::table('jobc_consumble')->insert([
                'RO_no'            => $request->job_id,
                'cons_description' => $request->part_description,
                'qty'              => $request->qty,
                'req_qty'          => $request->qty,
                'unitprice'        => $request->unitprice,
                'total'            => $request->totalprice,
                'Additional'       => 1,
                'cons_req_no'      => 0,
                'cons_number'      => 'null',
                'issue_to'         => 'null',
                'issue_time'       => now(),
                'status'           => 0,
                'issue_by'         => 'null',
                'p_return'         => 0,
                'incentive_status' => 0,
                'issued_qty'       => 0,
                'Stock_id'         => 0,
                'entry_datetime'   => now(),
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.consumable', $request->job_id)->with('success', 'Consumable added.');
    }

    public function postWorkSubletStore(Request $request)
    {
        if ($request->unitprice) {
            $type = $request->type;
            DB::table('jobc_sublet')->insert([
                'RO_no'         => $request->job_id,
                'Sublet'        => $request->sublet,
                'type'          => $type,
                'qty'           => $request->qty,
                'unitprice'     => ($type === 'Workshop') ? $request->unitprice : 0,
                'additional'    => 1,
                'status'        => 0,
                'jc'            => 0,
                'end_time'      => now(),
                'Asign_time'    => now(),
                'parts_details' => 'null',
                'Vendor'        => 'null',
                'who_taking'    => 'null',
                'Vendor_price'  => 0,
                'logistics'     => 0,
                'total'         => ($type === 'Workshop') ? $request->totalprice : 0,
                'entry_datetime' => now(),
            ]);
        }
        return redirect()->route('jobcard.additional.sublet', $request->job_id)->with('success', 'Sublet added.');
    }

    // ─────────────────────────────────────────────
    //  DELETE ITEM  (delete_labor.php)
    // ─────────────────────────────────────────────
    public function deleteItem(Request $request)
    {
        if ($request->id) {
            // Labor: delete if not yet started (status empty/null)
            DB::table('jobc_labor')
                ->where('Labor_id', $request->id)
                ->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', '')->orWhere('status', '0');
                })
                ->delete();

        } elseif ($request->Pid) {
            // Parts: only delete if not yet issued to parts dept
            $alreadyIssued = DB::table('jobc_parts_p')
                ->where('parts_sale_id', $request->Pid)
                ->exists();
            if (!$alreadyIssued) {
                DB::table('jobc_parts')
                    ->where('parts_sale_id', $request->Pid)
                    ->where('status', 0)
                    ->where('issued_qty', 0)
                    ->delete();
            }

        } elseif ($request->sid) {
            // Sublet: delete if status empty/null
            DB::table('jobc_sublet')
                ->where('sublet_id', $request->sid)
                ->where(function ($q) {
                    $q->whereNull('status')->orWhere('status', '')->orWhere('status', '0');
                })
                ->delete();

        } elseif ($request->cnid) {
            // Consumable: only delete if not yet issued
            $alreadyIssued = DB::table('jobc_consumble_p')
                ->where('parts_sale_id', $request->cnid)
                ->exists();
            if (!$alreadyIssued) {
                DB::table('jobc_consumble')
                    ->where('cons_sale_id', $request->cnid)
                    ->where('status', 0)
                    ->where('issued_qty', 0)
                    ->delete();
            }
        }

        return response()->json(['status' => 'ok']);
    }

    // ─────────────────────────────────────────────
    //  AJAX: VARIANT AUTOCOMPLETE  (ajax.php)
    // ─────────────────────────────────────────────
    public function ajaxVariant(Request $request)
    {
        // Original: files/ajax.php
        // POST: type=country_table, name_startsWith, row_num
        // search_field: 'Model' (default, original behaviour) or 'Variant' (extended)

        if ($request->isMethod('post') && $request->input('type') !== 'country_table') {
            return response()->json([]);
        }

        $name = $request->input('name_startsWith', '');
        $rowNum = $request->input('row_num', 0);
        $searchField = $request->input('search_field', 'Model'); // Model or Variant

        // Only allow safe column names
        $column = in_array($searchField, ['Model', 'Variant']) ? $searchField : 'Model';

        $variants = DB::table('variant_codes')
            ->whereRaw("UPPER({$column}) LIKE ?", [strtoupper($name) . '%'])
            ->select('Model', 'Variant', 'Make', 'Fram', 'Engine')
            ->limit(20)
            ->get();

        $data = $variants->map(
            fn($row) =>
            $row->Model . '|' . $row->Variant . '|' . $row->Make . '|' . $row->Engine . '|' . $rowNum
        )->values()->all();

        return response()->json($data);
    }

    // ─────────────────────────────────────────────
    //  ADDITIONAL LIST  (Additional.php list)
    //  Status=1 for current SA
    // ─────────────────────────────────────────────
    public function additionalList()
    {
        $SA = Auth::user()->login_id;

        $jobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.status', '1')  // Note: Use integer, not string
            ->where('jc.SA', $SA)
            ->select(
                'jc.Jobc_id',
                'jc.Open_date_time',
                'jc.MSI_cat',
                'jc.comp_appointed',
                'jc.Customer_name',  // From jobcard table
                'v.Registration',
                'v.Variant'
            )
            ->orderByDesc('jc.Jobc_id')
            ->get();

        return view('service.jobcard.additional-list', compact('jobs'));
    }

    // ─────────────────────────────────────────────
    //  COMPLETE  (jobcomplete.php)
    // ─────────────────────────────────────────────
    public function complete()
    {
        $SA = Auth::user()->login_id;

        $jobs = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->leftJoin('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')  // LEFT JOIN directly from jobcard
            ->where('jc.status', 1)  // Use integer, not string
            ->where('jc.SA', $SA)
            ->select(
                'jc.Jobc_id',
                'jc.Open_date_time',
                'jc.MSI_cat',
                'jc.comp_appointed',
                'jc.Customer_name',  // From jobcard table (if exists)
                DB::raw('COALESCE(jc.Veh_reg_no, v.Registration) as Registration'),  // Use whichever exists
                'v.Variant',
                DB::raw('COALESCE(c.Customer_name, jc.Customer_name) as Customer_name'),
                DB::raw('COALESCE(c.mobile, "N/A") as mobile')
            )
            ->orderByDesc('jc.Jobc_id')
            ->get();

        return view('service.jobcard.complete', compact('jobs'));
    }

    public function completeProcess(Request $request)
    {
        DB::table('jobcard')
            ->where('Jobc_id', $request->job_id)
            ->update(['status' => '2', 'closing_time' => now()]);

        return redirect()->route('jobcard.complete')->with('success', "RO #{$request->job_id} closed.");
    }

    // ─────────────────────────────────────────────
    //  STATUS PAGES — now show real data
    // ─────────────────────────────────────────────
    public function statusLabor()
    {
        $SA = Auth::user()->login_id;
        $labors = DB::table('jobc_labor as jl')
            ->join('jobcard as jc', 'jl.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.SA', $SA)->whereIn('jc.status', ['0', '1'])
            ->select('jl.*', 'jc.Jobc_id', 'v.Registration', 'v.Variant')
            ->orderByDesc('jl.Labor_id')->get();
        return view('service.jobcard.status-labor', compact('labors'));
    }

    public function statusParts()
    {
        $SA = Auth::user()->login_id;
        $parts = DB::table('jobc_parts as jp')
            ->join('jobcard as jc', 'jp.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.SA', $SA)->whereIn('jc.status', ['0', '1'])
            ->select('jp.*', 'jc.Jobc_id', 'v.Registration', 'v.Variant')
            ->orderByDesc('jp.parts_sale_id')->get();
        return view('service.jobcard.status-parts', compact('parts'));
    }

    public function statusSublet()
    {
        $SA = Auth::user()->login_id;
        $sublets = DB::table('jobc_sublet as js')
            ->join('jobcard as jc', 'js.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.SA', $SA)->whereIn('jc.status', ['0', '1'])
            ->select('js.*', 'jc.Jobc_id', 'v.Registration', 'v.Variant')
            ->orderByDesc('js.sublet_id')->get();
        return view('service.jobcard.status-sublet', compact('sublets'));
    }

    public function statusConsumable()
    {
        $SA = Auth::user()->login_id;
        $consumbles = DB::table('jobc_consumble as jcon')
            ->join('jobcard as jc', 'jcon.RO_no', '=', 'jc.Jobc_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->where('jc.SA', $SA)->whereIn('jc.status', ['0', '1'])
            ->select('jcon.*', 'jc.Jobc_id', 'v.Registration', 'v.Variant')
            ->orderByDesc('jcon.cons_sale_id')->get();
        return view('service.jobcard.status-consumable', compact('consumbles'));
    }

    // ─────────────────────────────────────────────
    //  NEW ITEMS — now pass jobs list
    // ─────────────────────────────────────────────
    public function newLabor()
    {
        $SA = Auth::user()->login_id;
        $jobs = DB::table('jobcard')->where('status', '1')->where('SA', $SA)->orderByDesc('Jobc_id')->get();
        return view('service.jobcard.new-labor', compact('jobs'));
    }

    public function newPart()
    {
        $SA = Auth::user()->login_id;
        $jobs = DB::table('jobcard')->where('status', '1')->where('SA', $SA)->orderByDesc('Jobc_id')->get();
        return view('service.jobcard.new-part', compact('jobs'));
    }

    public function newConsumable()
    {
        $SA = Auth::user()->login_id;
        $jobs = DB::table('jobcard')->where('status', '1')->where('SA', $SA)->orderByDesc('Jobc_id')->get();
        return view('service.jobcard.new-consumable', compact('jobs'));
    }

    // ─────────────────────────────────────────────
    //  NEW ITEM STORES  (new_part_add.php, new_cons_add.php)
    //  SA requests a new description not in system
    // ─────────────────────────────────────────────
    public function newLaborStore(Request $request)
    {
        if ($request->new_labor) {
            DB::table('labor_list')->insert([
                'Labor' => strtoupper($request->new_labor),
              
            ]);
        }
        return back()->with('success', 'Labor description added.');
    }

    public function newPartStore(Request $request)
    {
        if ($request->new_part) {
            DB::table('s_new_parts')->insert([
                'Description' => strtoupper($request->new_part),
                'User' => Auth::user()->login_id,
            ]);
        }
        return back()->with('success', 'Part description added.');
    }

    public function newConsumableStore(Request $request)
    {
        if ($request->new_part) {
            DB::table('s_list_consumble')->insert([
                'consumble' => strtoupper($request->new_part),
                'user' => Auth::user()->login_id,
            ]);
        }
        return back()->with('success', 'Consumable added.');
    }

    // ─────────────────────────────────────────────
    //  SEARCH  (search.php)
    // ─────────────────────────────────────────────
    public function search(Request $request)
    {
        $search = $request->input('search', '');
        $field = $request->input('field', '');
        $results = collect();
        $headers = [];
        $tableType = '';
        $total = 0;

        if ($field && $search !== '') {
            if ($field === 'jobcard-instail') {
                return redirect()->route('cashier.print', ['job_id' => $search]);
            }
            if ($field === 'jobcard-closed') {
                return redirect()->route('cashier.print2', ['job_id' => $search]);
            }
            if ($field === 'Invoice') {
                return redirect()->route('jobcard.invoice.print', ['id' => $search]);
            }

            if (strpos($field, '-') !== false) {
                [$table, $col] = explode('-', $field, 2);

                if ($table === 'customer_data') {
                    $tableType = 'customer';
                    $rows = DB::table('customer_data')
                        ->where($col, 'LIKE', "%{$search}%")
                        ->select(
                            'Customer_id',
                            'cust_type',
                            'Customer_name',
                            'mobile',
                            'Address',
                            DB::raw("DATE_FORMAT(Update_date,'%d %b %Y') as lastvisit")
                        )
                        ->orderByDesc('Customer_id')->limit(10)->get();

                    $results = $rows->map(function ($r) {
                        $r->regs = DB::table('jobcard')->where('Customer_id', $r->Customer_id)
                            ->groupBy('Customer_id')
                            ->selectRaw("GROUP_CONCAT(DISTINCT Veh_reg_no SEPARATOR ', ') as regs")
                            ->value('regs');
                        return $r;
                    });
                } elseif ($table === 'vehicles_data') {
                    $tableType = 'vehicle';
                    $rows = DB::table('vehicles_data')
                        ->where($col, 'LIKE', "%{$search}%")
                        ->select(
                            'Vehicle_id',
                            'Registration',
                            'Frame_no',
                            'Model',
                            'Variant',
                            'Colour',
                            'Make',
                            'Engine_Code',
                            'into_sell',
                            DB::raw("DATE_FORMAT(Update_date,'%d %b %Y') as lastvisit")
                        )
                        ->orderByDesc('Vehicle_id')->limit(10)->get();

                    $results = $rows->map(function ($r) {
                        $r->customers = DB::table('jobcard')->where('Vehicle_id', $r->Vehicle_id)
                            ->groupBy('Vehicle_id')
                            ->selectRaw("GROUP_CONCAT(DISTINCT Customer_name SEPARATOR ', ') as customers")
                            ->value('customers');
                        return $r;
                    });
                }
            } elseif ($field === 'jobc_parts') {
                $tableType = 'parts';
                $results = DB::table('jobc_parts as jp')
                    ->join('jobcard as jc', 'jp.RO_no', '=', 'jc.Jobc_id')
                    ->join('p_purch_stock as ps', 'jp.Stock_id', '=', 'ps.stock_id')
                    ->where('jp.RO_no', $search)->where('jp.status', '1')
                    ->select(
                        'jp.*',
                        'ps.Invoice_no',
                        DB::raw("DATE_FORMAT(jp.issue_time,'%d %b %h:%i %p') as bookingtime")
                    )
                    ->get();
                $total = $results->sum('total');
            } elseif ($field === 'jobc_consumble') {
                $tableType = 'consumble';
                $results = DB::table('jobc_consumble as jcon')
                    ->join('jobcard as jc', 'jcon.RO_no', '=', 'jc.Jobc_id')
                    ->join('p_purch_stock as ps', 'jcon.Stock_id', '=', 'ps.stock_id')
                    ->where('jcon.RO_no', $search)->where('jcon.status', '1')
                    ->select(
                        'jcon.*',
                        'ps.Invoice_no',
                        DB::raw("DATE_FORMAT(jcon.issue_time,'%d %b %h:%i %p') as bookingtime")
                    )
                    ->get();
                $total = $results->sum('total');
            }
        }

        return view('service.jobcard.search', compact('results', 'search', 'field', 'tableType', 'total'));
    }
    // ─────────────────────────────────────────────
    //  EDIT VEHICLE  (files/veh_edit.php)
    // ─────────────────────────────────────────────
    public function editVehicle(Request $request)
    {
        $vehicleId = $request->query('vehicle_id', $request->input('vehicle_id'));
        if (!$vehicleId)
            abort(404);
        $vehicle = DB::table('vehicles_data')->where('Vehicle_id', $vehicleId)->first();
        if (!$vehicle)
            abort(404);
        return view('service.jobcard.vehicle-edit', compact('vehicle', 'vehicleId'));
    }

    public function updateVehicle(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|integer',
            'registration' => 'required|string|max:20',
            'varaint' => 'required|string|max:100',
        ]);

        $vehicleId = $request->vehicle_id;
        $intosell = $request->has('intosell') ? 'on' : 'off';

        DB::table('vehicles_data')->where('Vehicle_id', $vehicleId)->update([
            'Registration' => strtoupper($request->registration),
            'Frame_no' => strtoupper($request->fram ?? ''),
            'Model' => $request->model ?? '',
            'Variant' => $request->varaint,
            'Colour' => $request->color ?? '',
            'Make' => $request->make ?? '',
            'Engine_Code' => $request->engine ?? '',
            'Engine_number' => $request->engine_no ?? '',
            'into_sell' => $intosell,
            'model_year' => $request->model_year ?? '',
            'demand_price' => ($intosell === 'on') ? ($request->demandprice ?? '') : '',
            'updated_by' => Auth::user()->login_id,
            'Update_date' => now(),
        ]);

        return redirect()->route('jobcard.vehicle-detail', ['vehicle_id' => $vehicleId])
            ->with('success', 'Vehicle updated successfully.');
    }

    // ─────────────────────────────────────────────
    //  VEHICLE HISTORY  (history.php / histories.php / histry.php)
    //  history.php  — POST veh_id (from vehicle-detail)
    //  histry.php   — GET  veh_id or Cust_id (from search links)
    // ─────────────────────────────────────────────
    public function vehicleHistory(Request $request)
    {
        $vehId = $request->input('veh_id') ?? $request->query('veh_id');
        $custId = $request->input('cust_id') ?? $request->query('Cust_id');

        if ($vehId) {
            $where = ['Vehicle_id' => $vehId];
            $heading = 'Vehicle History';
        } elseif ($custId) {
            $where = ['Customer_id' => $custId];
            $heading = 'Customer History';
        } else {
            abort(404);
        }

        $jobcards = DB::table('jobcard')
            ->where($where)
            ->select(
                'Jobc_id',
                'Customer_name',
                'Veh_reg_no',
                'Vehicle_id',
                'Customer_id',
                'VOC',
                'MSI_cat',
                'Mileage',
                'SA',
                'status',
                DB::raw("DATE_FORMAT(closing_time,' %d %b %Y %h:%i %p') as bookingtime")
            )
            ->orderByDesc('Jobc_id')
            ->get();

        // Attach labor/parts/consumable/sublet summary per jobcard
        $jobcards = $jobcards->map(function ($jc) {
            $jc->labors = DB::table('jobc_labor')
                ->where('RO_no', $jc->Jobc_id)
                ->selectRaw("GROUP_CONCAT(Labor SEPARATOR '.<br>') as labor_list, SUM(cost) as total_labor")
                ->first();
            $jc->parts = DB::table('jobc_parts')
                ->where('RO_no', $jc->Jobc_id)
                ->selectRaw("GROUP_CONCAT(part_description SEPARATOR '.<br>') as parts_list, SUM(total) as total_parts")
                ->first();
            $jc->consumbles = DB::table('jobc_consumble')
                ->where('RO_no', $jc->Jobc_id)
                ->selectRaw("GROUP_CONCAT(cons_description SEPARATOR '.<br>') as cons_list, SUM(total) as total_cons")
                ->first();
            $jc->sublets = DB::table('jobc_sublet')
                ->where('RO_no', $jc->Jobc_id)
                ->selectRaw("GROUP_CONCAT(Sublet SEPARATOR '.<br>') as sub_list, SUM(total) as total_sub")
                ->first();
            return $jc;
        });

        return view('service.jobcard.vehicle-history', compact('jobcards', 'heading', 'vehId', 'custId'));
    }

    // ─────────────────────────────────────────────
    //  INVOICE VIEW  (invoice.php)
    //  Shows full jobcard invoice with labor/parts/sublet/consumable totals
    // ─────────────────────────────────────────────
    public function invoiceView(Request $request)
    {
        $jobId = $request->input('job_id') ?? $request->query('job_id');
        if (!$jobId)
            abort(404);

        $jobcard = DB::table('jobcard as jc')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->join('customer_data as c', 'v.Customer_id', '=', 'c.Customer_id')
            ->where('jc.Jobc_id', $jobId)
            ->select('jc.*', 'v.Variant', 'v.Registration', 'c.Customer_name', 'c.mobile')
            ->first();

        if (!$jobcard)
            abort(404);

        $totalLabor = DB::table('jobc_labor')
            ->where('RO_no', $jobId)->where('type', 'Workshop')
            ->sum('cost') ?? 0;
        $totalParts = DB::table('jobc_parts')
            ->where('RO_no', $jobId)->sum('total') ?? 0;
        $totalSublet = DB::table('jobc_sublet')
            ->where('RO_no', $jobId)->where('type', 'Workshop')
            ->sum('total') ?? 0;
        $totalConsumble = DB::table('jobc_consumble')
            ->where('RO_no', $jobId)->sum('total') ?? 0;

        $labors = DB::table('jobc_labor')->where('RO_no', $jobId)->get();
        $parts = DB::table('jobc_parts')->where('RO_no', $jobId)->get();
        $sublets = DB::table('jobc_sublet')->where('RO_no', $jobId)->get();
        $consumbles = DB::table('jobc_consumble')->where('RO_no', $jobId)->get();

        return view('service.jobcard.invoice-view', compact(
            'jobcard',
            'labors',
            'parts',
            'sublets',
            'consumbles',
            'totalLabor',
            'totalParts',
            'totalSublet',
            'totalConsumble'
        ));
    }

    // ─────────────────────────────────────────────
    //  VIN CHECK LIST  (vin_check.php)
    //  Shows pending VIN check records, allows mark done or Open RO
    // ─────────────────────────────────────────────
    public function vinCheck(Request $request)
    {
        // Mark as done
        if ($request->isMethod('post') && $request->has('done')) {
            DB::table('s_vin_check')
                ->where('frameno', $request->framno)
                ->update(['ActionTaken' => 1, 'ActionDate' => now()]);
            return back()->with('success', 'Marked as done.');
        }

        $records = DB::table('s_vin_check as vc')
            ->join('s_upload_listname as ul', 'ul.list_id', '=', 'vc.listid')
            ->where('vc.ActionTaken', 0)
            ->select('vc.*', 'ul.list_name')
            ->get();

        // Attach vehicle data
        $records = $records->map(function ($r) {
            $r->vehicle = DB::table('vehicles_data')
                ->whereRaw("SUBSTR(Frame_no,-7) = ?", [$r->frameno])
                ->first();
            return $r;
        });

        return view('service.jobcard.vin-check', compact('records'));
    }

    // ─────────────────────────────────────────────
    //  WARRANTY  (warranty.php)
    //  Claim, approve, deny warranty records
    // ─────────────────────────────────────────────
    public function warranty(Request $request)
    {
        $user = Auth::user()->login_id;

        if ($request->has('Labor_id')) {
            DB::table('s_warranty')->insert([
                'jobc_id' => $request->Labor_id,
                'wc_no' => $request->warrantyclaim,
                'status' => 'Claimed',
                'claim_date' => now(),
                'user' => $user,
            ]);
        }

        if ($request->has('reason')) {
            DB::table('s_warranty')
                ->where('w_id', $request->w_id)
                ->update([
                    'status' => 'Denied',
                    'user' => $user,
                    'remarks' => $request->reason,
                    'approve_date' => now()
                ]);
        }

        if ($request->has('approved')) {
            DB::table('s_warranty')
                ->where('w_id', $request->approved)
                ->update(['status' => 'Approved', 'user' => $user, 'approve_date' => now()]);
        }

        // Warranty claims list — WC invoices not yet in s_warranty, after 2020
        $pendingJobs = DB::table('jobcard as jc')
            ->leftJoin('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->leftJoin('jobc_invoice as ji', 'jc.Jobc_id', '=', 'ji.Jobc_id')
            ->whereNotIn('jc.Jobc_id', DB::table('s_warranty')->pluck('jobc_id'))
            ->where('jc.Open_date_time', '>', '2020-07-01')
            ->where('ji.type', 'WC')
            ->where('jc.status', '>', 2)
            ->select(
                'jc.Jobc_id',
                'jc.Customer_name',
                'v.Frame_no',
                'ji.Total',
                DB::raw("DATE_FORMAT(jc.Open_date_time,' %d %b %Y %h:%i %p') as bookingtime")
            )
            ->orderBy('jc.Jobc_id')
            ->get();

        $claimedWarranties = DB::table('s_warranty')->orderByDesc('w_id')->get();

        return view('service.jobcard.warranty', compact('pendingJobs', 'claimedWarranties'));
    }

    // ─────────────────────────────────────────────
    //  LOYALTY SERVICES  (loyalty_services.php)
    //  Show and update loyalty card services for a vehicle
    // ─────────────────────────────────────────────
    public function loyaltyServices(Request $request)
    {
        $vehId = $request->input('veh_id') ?? $request->query('veh_id');
        $jobId = $request->input('job_id');

        if ($request->has('labor')) {
            $labor = $request->labor;
            $cost = $request->cost;
            DB::table('s_loyal_services')
                ->where('veh_id', $vehId)
                ->update([$labor => 'Done']);
            DB::table('jobc_labor')->insert([
                'RO_no' => $jobId,
                'Labor' => $labor,
                'type' => 'Workshop',
                'cost' => $cost,
                'entry_time' => now(),
            ]);
        }

        $loyalty = DB::table('s_loyal_services')->where('veh_id', $vehId)->first();

        return view('service.jobcard.loyalty-services', compact('loyalty', 'vehId', 'jobId'));
    }

    // ─────────────────────────────────────────────
    //  MULTI-CUSTOMER LINK  (Forsale/multi_customer.php)
    //  Link an existing customer to a vehicle
    // ─────────────────────────────────────────────
    public function multiCustomerForm(Request $request)
    {
        $vehicleId = $request->query('vehicle_id');
        if (!$vehicleId)
            abort(404);
        $customers = DB::table('customer_data')->orderBy('Customer_name')->get();
        return view('service.jobcard.multi-customer', compact('vehicleId', 'customers'));
    }

    public function multiCustomerStore(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|integer',
            'customer_id' => 'required|integer',
        ]);
        // Check not already linked
        $exists = DB::table('s_cust_veh')
            ->where('cust_id', $request->customer_id)
            ->where('veh_id', $request->vehicle_id)
            ->exists();
        if (!$exists) {
            DB::table('s_cust_veh')->insert([
                'cust_id' => $request->customer_id,
                'veh_id' => $request->vehicle_id,
            ]);
        }
        return redirect()->route('jobcard.vehicle-detail', ['vehicle_id' => $request->vehicle_id])
            ->with('success', 'Customer linked to vehicle.');
    }

    // ─────────────────────────────────────────────
    //  AJAX: GET MSI DETAILS  (files/getmsiDetails.php)
    //  Returns ro_type + service_nature for an MSI category
    // ─────────────────────────────────────────────
    public function ajaxMsiDetails(Request $request)
    {
        $msiId = $request->input('msi_id');
        $row = DB::table('s_msi_categories')->where('MSI', $msiId)->first();
        if (!$row)
            return response()->json([]);
        return response()->json([
            [
                'ro_type' => $row->ro_type,
                'service_nature' => $row->service_nature,
            ]
        ]);
    }

    // ─────────────────────────────────────────────
    //  AJAX: LABOR COST  (files/Labor_cost.php)
    //  Returns labor price based on variant category
    // ─────────────────────────────────────────────
    public function ajaxLaborCost(Request $request)
    {
        $laborTitle = $request->input('partn');
        $variant = $request->input('variant');
        $type = $request->input('type');

        $varRow = DB::table('variant_codes')->where('Variant', $variant)->first();
        $cate = $varRow ? $varRow->Category : null;

        $price = 0;
        if ($cate) {
            $laborRow = DB::table('labor_list')->where('Labor', $laborTitle)->first();
            if ($laborRow) {
                $price = $laborRow->$cate ?? 0;
                if ($type === 'New Contracts') {
                    $price = (18 * $price / 100) + $price;
                }
            }
        }
        return response((string) $price);
    }

    // ─────────────────────────────────────────────
    //  AJAX: DELETE ESTIMATE ITEM  (files/del_est_labor.php)
    //  Deletes items from s_est_* tables
    // ─────────────────────────────────────────────
    public function deleteEstimateItem(Request $request)
    {
        if ($request->id) {
            DB::table('s_est_labor')->where('est_lab_id', $request->id)->delete();
        } elseif ($request->Pid) {
            DB::table('s_est_parts')
                ->where('estm_part_id', $request->Pid)
                ->where('status', '0')
                ->delete();
        } elseif ($request->sid) {
            DB::table('s_est_sublet')->where('est_sub_id', $request->sid)->delete();
        } elseif ($request->cnid) {
            DB::table('s_est_consumble')
                ->where('estm_part_id', $request->cnid)
                ->where('status', '0')
                ->delete();
        }
        return response()->json(['status' => 'ok']);
    }
}