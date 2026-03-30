<?php

namespace App\Http\Controllers\SalesVehicle;

use App\Http\Controllers\Controller;
use App\Models\SvVehicle;
use App\Models\SvDeliveryOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SalesVehicleController extends Controller
{
    // ─── DASHBOARD ────────────────────────────────────────────────────────────
    public function index()
    {
        $stats = [
            'in_stock'   => SvVehicle::where('status', 'In Stock')->count(),
            'reserved'   => SvVehicle::where('status', 'Reserved')->count(),
            'sold_month' => SvVehicle::where('status', 'Sold')
                                ->whereMonth('updated_at', now()->month)
                                ->whereYear('updated_at', now()->year)
                                ->count(),
            'pending_do' => SvDeliveryOrder::where('status', 'Pending')->count(),
        ];

        $recentDOs = SvDeliveryOrder::with('vehicle')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('sales-vehicle.index', compact('stats', 'recentDOs'));
    }

    // ─── INVENTORY LIST ───────────────────────────────────────────────────────
    public function inventory(Request $request)
    {
        $q      = $request->input('q', '');
        $status = $request->input('status', '');
        $model  = $request->input('model', '');

        $vehicles = SvVehicle::query()
            ->when($q, fn($qb) => $qb->where(function ($qb) use ($q) {
                $qb->where('vin',   'like', "%$q%")
                   ->orWhere('model',  'like', "%$q%")
                   ->orWhere('variant','like', "%$q%")
                   ->orWhere('color',  'like', "%$q%")
                   ->orWhere('engine_no','like', "%$q%");
            }))
            ->when($status, fn($qb) => $qb->where('status', $status))
            ->when($model,  fn($qb) => $qb->where('model', $model))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $models = SvVehicle::select('model')->distinct()->orderBy('model')->pluck('model');

        return view('sales-vehicle.inventory', compact('vehicles', 'models', 'q', 'status', 'model'));
    }

    // ─── ADD VEHICLE ──────────────────────────────────────────────────────────
    public function addVehicle()
    {
        return view('sales-vehicle.add-vehicle');
    }

    public function storeVehicle(Request $request)
    {
        $request->validate([
            'vin'        => 'required|unique:sv_vehicles,vin',
            'model'      => 'required',
            'list_price' => 'required|numeric|min:0',
        ]);

        SvVehicle::create([
            'vin'          => strtoupper($request->vin),
            'model'        => strtoupper($request->model),
            'variant'      => strtoupper($request->variant),
            'color'        => $request->color,
            'model_year'   => $request->model_year,
            'engine_no'    => strtoupper($request->engine_no),
            'transmission' => $request->transmission,
            'list_price'   => $request->list_price,
            'status'       => $request->status ?? 'In Stock',
            'arrival_date' => $request->arrival_date,
            'location'     => $request->location,
            'remarks'      => $request->remarks,
            'added_by'     => Auth::user()->login_id,
        ]);

        return redirect()->route('sv.inventory')->with('success', 'Vehicle added to inventory successfully.');
    }

    // ─── EDIT VEHICLE ─────────────────────────────────────────────────────────
    public function editVehicle($id)
    {
        $vehicle = SvVehicle::findOrFail($id);
        return view('sales-vehicle.edit-vehicle', compact('vehicle'));
    }

    public function updateVehicle(Request $request, $id)
    {
        $vehicle = SvVehicle::findOrFail($id);

        $request->validate([
            'vin'        => 'required|unique:sv_vehicles,vin,' . $id,
            'model'      => 'required',
            'list_price' => 'required|numeric|min:0',
        ]);

        $vehicle->update([
            'vin'          => strtoupper($request->vin),
            'model'        => strtoupper($request->model),
            'variant'      => strtoupper($request->variant),
            'color'        => $request->color,
            'model_year'   => $request->model_year,
            'engine_no'    => strtoupper($request->engine_no),
            'transmission' => $request->transmission,
            'list_price'   => $request->list_price,
            'status'       => $request->status,
            'arrival_date' => $request->arrival_date,
            'location'     => $request->location,
            'remarks'      => $request->remarks,
        ]);

        return redirect()->route('sv.inventory')->with('success', 'Vehicle updated successfully.');
    }

    // ─── DELIVERY ORDER FORM ──────────────────────────────────────────────────
    public function doForm(Request $request)
    {
        $vehicleId = $request->input('vehicle_id');
        $vehicle   = $vehicleId ? SvVehicle::findOrFail($vehicleId) : null;

        // Available vehicles for dropdown
        $availableVehicles = SvVehicle::whereIn('status', ['In Stock', 'Reserved'])
            ->orderBy('model')
            ->get();

        return view('sales-vehicle.do-form', compact('vehicle', 'availableVehicles'));
    }

    public function storeDO(Request $request)
    {
        $request->validate([
            'vehicle_id'           => 'required|exists:sv_vehicles,id',
            'customer_name'        => 'required',
            'customer_phone'       => 'required',
            'payment_type'         => 'required|in:Cash,Installment,Direct',
            'onroad_price'         => 'required|numeric|min:1',
            'discount'             => 'nullable|numeric|min:0',

        ]);

        if ($request->payment_type === 'Cash') {
            $request->validate([
                'cash_received' => 'required|numeric|min:0',
            ]);
        } elseif ($request->payment_type === 'Installment') {
            $request->validate([
                'bank_name'           => 'required',
                'down_payment'        => 'required|numeric|min:0',
                'tenure_months'       => 'required|integer|min:1',
            ]);
        } else {
            // Direct
            $request->validate([
                'direct_down_payment'        => 'required|numeric|min:0',
                'direct_tenure_months'       => 'required|integer|min:1',
            ]);
        }

        $onroadPrice        = (float) $request->onroad_price;
        $discount           = (float) ($request->discount ?? 0);
        $customerPaidAmount = $onroadPrice - $discount;

        // Determine down payment / loan / installment based on payment type
        if ($request->payment_type === 'Cash') {
            $downPayment         = 0;
            $loanAmount          = 0;
            $tenureMonths        = null;
            $monthlyInstallment  = 0;
            $bankName            = null;
            $financeScheme       = null;
        } elseif ($request->payment_type === 'Installment') {
            $downPayment        = (float) ($request->down_payment ?? 0);
            $loanAmount         = max(0, $customerPaidAmount - $downPayment);
            $tenureMonths       = (int) $request->tenure_months;
            $monthlyInstallment = $tenureMonths > 0 ? ceil($loanAmount / $tenureMonths) : 0;
            $bankName           = $request->bank_name;
            $financeScheme      = $request->finance_scheme;
        } else {
            // Direct
            $downPayment        = (float) ($request->direct_down_payment ?? 0);
            $loanAmount         = max(0, $customerPaidAmount - $downPayment);
            $tenureMonths       = (int) $request->direct_tenure_months;
            $monthlyInstallment = $tenureMonths > 0 ? ceil($loanAmount / $tenureMonths) : 0;
            $bankName           = 'DIRECT — ' . ($request->guarantor_name ? 'Guarantor: ' . strtoupper($request->guarantor_name) : 'No Bank');
            $financeScheme      = $request->guarantor_phone ? 'Guarantor Phone: ' . $request->guarantor_phone : null;
        }

        $deliveryDate = $request->delivery_date ?? $request->direct_delivery_date ?? null;

        DB::transaction(function () use ($request, $onroadPrice, $discount, $customerPaidAmount, $downPayment, $loanAmount, $tenureMonths, $monthlyInstallment, $bankName, $financeScheme, $deliveryDate) {
            SvDeliveryOrder::create([
                'do_no'                => SvDeliveryOrder::generateDoNo(),
                // NVD header
                'pbo_no'               => $request->pbo_no,
                'customer_type'        => $request->customer_type ?? 'Individual',
                'sale_price'           => $request->sale_price ?? $onroadPrice - $discount,
                // Vehicle
                'vehicle_id'           => $request->vehicle_id,
                // Customer
                'customer_name'        => strtoupper($request->customer_name),
                'customer_cnic'        => $request->customer_cnic,
                'customer_phone'       => $request->customer_phone,
                'customer_address'     => $request->customer_address,
                'customer_son_wife_of' => $request->customer_son_wife_of,
                // Payment
                'payment_type'         => $request->payment_type,
                'onroad_price'         => $onroadPrice,
                'discount'             => $discount,
                'customer_paid_amount' => $customerPaidAmount,
                'cash_received'        => $request->payment_type === 'Cash' ? $request->cash_received : 0,
                'bank_name'            => $bankName,
                'finance_scheme'       => $financeScheme,
                'down_payment'         => $downPayment,
                'loan_amount'          => $loanAmount,
                'tenure_months'        => $tenureMonths,
                'monthly_installment'  => $monthlyInstallment,
                // DO meta
                'do_date'              => $request->do_date,
                'delivery_date'        => $deliveryDate,
                'status'               => 'Pending',
                'remarks'              => $request->remarks,
                'created_by'           => Auth::user()->login_id,
                // Receiver
                'receiver_name'        => $request->receiver_name ? strtoupper($request->receiver_name) : strtoupper($request->customer_name),
                'receiver_father_name' => $request->receiver_father_name,
                'receiver_cnic'        => $request->receiver_cnic ?? $request->customer_cnic,
                'receiver_phone'       => $request->receiver_phone ?? $request->customer_phone,
                'receiver_address'     => $request->receiver_address ?? $request->customer_address,
                // Accessories
                'acc_keys_qty'         => (int) ($request->acc_keys_qty ?? 1),
                'acc_remote_control'   => $request->boolean('acc_remote_control', true),
                'acc_toolkit_jack'     => $request->boolean('acc_toolkit_jack', true),
                'acc_spare_wheel'      => $request->boolean('acc_spare_wheel'),
                'acc_battery_warranty' => $request->boolean('acc_battery_warranty'),
                'acc_service_warranty' => $request->boolean('acc_service_warranty', true),
                // Documents
                'doc_sales_invoice'           => $request->boolean('doc_sales_invoice'),
                'doc_sales_certificate'       => $request->boolean('doc_sales_certificate'),
                'doc_sales_cert_verification' => $request->boolean('doc_sales_cert_verification'),
                // NVD Checklist
                'nvd_warranty_terms'   => $request->boolean('nvd_warranty_terms'),
                'nvd_owners_manual'    => $request->boolean('nvd_owners_manual'),
                'nvd_ffs_pm_schedule'  => $request->boolean('nvd_ffs_pm_schedule'),
                'nvd_3s_visit'         => $request->boolean('nvd_3s_visit'),
                'nvd_ew_ppm'           => $request->boolean('nvd_ew_ppm'),
                'nvd_safety_features'  => $request->boolean('nvd_safety_features'),
                'nvd_demonstrated_ops' => $request->boolean('nvd_demonstrated_ops'),
            ]);

            SvVehicle::where('id', $request->vehicle_id)->update(['status' => 'Reserved']);
        });

        return redirect()->route('sv.do-list')->with('success', 'Delivery Order created successfully.');
    }

    // ─── PRINT DO (NVD) ───────────────────────────────────────────────────────
    public function printDO($id)
    {
        $do = SvDeliveryOrder::with('vehicle')->findOrFail($id);
        return view('sales-vehicle.print-do', compact('do'));
    }

    // ─── DO LIST ──────────────────────────────────────────────────────────────
    public function doList(Request $request)
    {
        $q      = $request->input('q', '');
        $status = $request->input('status', '');

        $orders = SvDeliveryOrder::with('vehicle')
            ->when($q, fn($qb) => $qb->where(function ($qb) use ($q) {
                $qb->where('do_no',         'like', "%$q%")
                   ->orWhere('customer_name','like', "%$q%")
                   ->orWhere('customer_cnic','like', "%$q%")
                   ->orWhere('customer_phone','like',"%$q%");
            }))
            ->when($status, fn($qb) => $qb->where('status', $status))
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('sales-vehicle.do-list', compact('orders', 'q', 'status'));
    }

    // ─── APPROVE / DELIVER DO ─────────────────────────────────────────────────
    public function doUpdateStatus(Request $request)
    {
        $request->validate([
            'do_id'  => 'required|exists:sv_delivery_orders,id',
            'action' => 'required|in:Approved,Delivered,Cancelled',
        ]);

        DB::transaction(function () use ($request) {
            $do = SvDeliveryOrder::findOrFail($request->do_id);
            $do->update(['status' => $request->action]);

            if ($request->action === 'Delivered') {
                SvVehicle::where('id', $do->vehicle_id)->update(['status' => 'Sold']);
            } elseif ($request->action === 'Cancelled') {
                SvVehicle::where('id', $do->vehicle_id)->update(['status' => 'In Stock']);
            }
        });

        return back()->with('success', 'DO status updated.');
    }

    // ─── SEARCH SOLD VEHICLES ─────────────────────────────────────────────────
    public function searchSold(Request $request)
    {
        $q       = $request->input('q', '');
        $results = collect();

        if ($q) {
            $results = SvVehicle::with('latestDO')
                ->where('status', 'Sold')
                ->where(function ($qb) use ($q) {
                    $qb->where('vin',      'like', "%$q%")
                       ->orWhere('model',   'like', "%$q%")
                       ->orWhere('variant', 'like', "%$q%")
                       ->orWhere('color',   'like', "%$q%")
                       ->orWhere('engine_no','like',"%$q%");
                })
                ->orWhereHas('deliveryOrders', function ($qb) use ($q) {
                    $qb->where('customer_name', 'like', "%$q%")
                       ->orWhere('customer_cnic','like', "%$q%")
                       ->orWhere('customer_phone','like',"%$q%")
                       ->orWhere('do_no',        'like', "%$q%");
                })
                ->orderByDesc('updated_at')
                ->limit(30)
                ->get();
        }

        return view('sales-vehicle.search-sold', compact('q', 'results'));
    }
}
