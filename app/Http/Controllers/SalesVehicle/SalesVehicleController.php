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
            'payment_type'         => 'required|in:Cash,Installment',
            'onroad_price'         => 'required|numeric|min:1',
            'discount'             => 'nullable|numeric|min:0',
            'do_date'              => 'required|date',
        ]);

        if ($request->payment_type === 'Cash') {
            $request->validate([
                'cash_received' => 'required|numeric|min:0',
            ]);
        } else {
            $request->validate([
                'bank_name'           => 'required',
                'down_payment'        => 'required|numeric|min:0',
                'tenure_months'       => 'required|integer|min:1',
                'monthly_installment' => 'required|numeric|min:0',
            ]);
        }

        $onroadPrice        = (float) $request->onroad_price;
        $discount           = (float) ($request->discount ?? 0);
        $customerPaidAmount = $onroadPrice - $discount;
        $downPayment        = (float) ($request->down_payment ?? 0);
        $loanAmount         = $request->payment_type === 'Installment'
                                ? max(0, $customerPaidAmount - $downPayment)
                                : 0;

        DB::transaction(function () use ($request, $onroadPrice, $discount, $customerPaidAmount, $downPayment, $loanAmount) {
            SvDeliveryOrder::create([
                'do_no'                => SvDeliveryOrder::generateDoNo(),
                'vehicle_id'           => $request->vehicle_id,
                'customer_name'        => strtoupper($request->customer_name),
                'customer_cnic'        => $request->customer_cnic,
                'customer_phone'       => $request->customer_phone,
                'customer_address'     => $request->customer_address,
                'payment_type'         => $request->payment_type,
                'onroad_price'         => $onroadPrice,
                'discount'             => $discount,
                'customer_paid_amount' => $customerPaidAmount,
                'cash_received'        => $request->payment_type === 'Cash' ? $request->cash_received : 0,
                'bank_name'            => $request->bank_name,
                'finance_scheme'       => $request->finance_scheme,
                'down_payment'         => $downPayment,
                'loan_amount'          => $loanAmount,
                'tenure_months'        => $request->tenure_months,
                'monthly_installment'  => $request->monthly_installment ?? 0,
                'do_date'              => $request->do_date,
                'delivery_date'        => $request->delivery_date,
                'status'               => 'Pending',
                'remarks'              => $request->remarks,
                'created_by'           => Auth::user()->login_id,
            ]);

            SvVehicle::where('id', $request->vehicle_id)->update(['status' => 'Reserved']);
        });

        return redirect()->route('sv.do-list')->with('success', 'Delivery Order created successfully.');
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
