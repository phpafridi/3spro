<?php

namespace App\Http\Controllers\Finance\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ReportsController extends Controller
{
    /**
     * Parse daterange safely - handles "MM/DD/YYYY - MM/DD/YYYY" format
     */
    private function parseDateRange($daterange)
    {
        $parts = explode(' - ', $daterange);
        $from  = date('Y-m-d', strtotime(trim($parts[0])));
        $to    = date('Y-m-d', strtotime(trim($parts[1])));
        return [$from, $to];
    }

    /**
     * All Types Invoice Report
     */
    public function allReport(Request $request)
    {
        [$from, $to] = $this->parseDateRange($request->daterange);

        $reports = DB::table('jobc_invoice as ji')
            ->join('jobcard as jc', 'ji.Jobc_id', '=', 'jc.Jobc_id')
            ->select(
                'ji.type',
                DB::raw('COUNT(ji.type) as total_type'),
                DB::raw('SUM(ji.Lnet) as total_L'),
                DB::raw('SUM(ji.Pnet) as total_P'),
                DB::raw('SUM(ji.Snet) as total_S'),
                DB::raw('SUM(ji.Cnet) as total_C'),
                DB::raw('SUM(ji.Ltax + ji.Ptax + ji.Stax + ji.Ctax) as tax'),
                DB::raw('SUM(ji.Ldiscount + ji.Pdiscount + ji.Sdiscount + ji.Cdiscount) as discount'),
                DB::raw('SUM(ji.Total) as total_total')
            )
            ->whereBetween(DB::raw('DATE(ji.datetime)'), [$from, $to])
            ->groupBy('ji.type')
            ->orderBy('total_type', 'desc')
            ->get();

        $fromFormatted = date('d-M-y', strtotime($from));
        $toFormatted   = date('d-M-y', strtotime($to));

        return view('finance.cashier.reports.all', compact('reports', 'fromFormatted', 'toFormatted'));
    }

    /**
     * MSI Excel Report
     */
    public function msiReport(Request $request)
    {
        [$from, $to] = $this->parseDateRange($request->daterange);

        $data = $this->calculateMSI($from, $to);
        $xml  = $this->generateMSIExcelXML($data);

        return Response::make($xml, 200, [
            'Content-Type'        => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="MSI.xml"',
        ]);
    }

    /**
     * PM Excel Export
     */
    public function pmExport(Request $request)
    {
        [$from, $to] = $this->parseDateRange($request->daterange);

        $isPMGRBP = $request->has('PMGRBP');

        $query = DB::table('jobcard as jc')
            ->leftJoin('jobc_invoice as ji', 'jc.Jobc_id', '=', 'ji.Jobc_id')
            ->leftJoin('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->leftJoin('variant_codes as vc', 'v.Model', '=', 'vc.Model')
            ->leftJoin('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->whereIn('jc.status', ['3', '4']);

        if ($isPMGRBP) {
            $query->whereBetween(DB::raw('DATE(jc.Open_date_time)'), [$from, $to]);
        } else {
            $query->where('jc.serv_nature', 'PM')
                ->whereBetween(DB::raw('DATE(jc.Open_date_time)'), [$from, $to]);
        }

        $results = $query->select(
            DB::raw('DATE_FORMAT(jc.Open_date_time, "%d-%b-%y") as datee'),
            DB::raw("CONCAT(v.Model, '-', v.Frame_no) as Frami"),
            'jc.Jobc_id',
            'jc.serv_nature',
            'jc.Customer_name',
            'ji.Lnet',
            'ji.Pnet',
            'ji.Snet',
            'jc.Mileage',
            'vc.Fram',
            DB::raw("IF(c.cust_type = '', 'Individuals', c.cust_type) as cust_type"),
            'v.model_year',
            DB::raw("IF(jc.Veh_reg_no = 'NEW', 'APL', jc.Veh_reg_no) as Regt"),
            DB::raw("IF(jc.comp_appointed LIKE '%IMC%', jc.comp_appointed, 'Dlr Campaign') as campaign"),
            'c.mobile',
            DB::raw("IF(vc.Make IS NULL, 'Others', vc.Make) as make")
        )
            ->groupBy('jc.Jobc_id')
            ->orderBy('jc.Jobc_id', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Add headers
        $headers = array_keys((array)$results->first());
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col++ . '1', $header);
        }

        // Add data
        $row = 2;
        foreach ($results as $result) {
            $col = 'A';
            foreach ((array)$result as $value) {
                $sheet->setCellValue($col++ . $row, $value);
            }
            $row++;
        }

        $writer = new Xlsx($spreadsheet);

        return Response::stream(function () use ($writer) {
            $writer->save('php://output');
        }, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="PM.xlsx"',
        ]);
    }

    /**
     * Type-wise Report (CM, DM, etc.)
     */
    public function typeReport(Request $request)
    {
        [$from, $to] = $this->parseDateRange($request->daterange);
        $type = $request->type;

        $reports = DB::table('jobc_invoice as ji')
            ->join('jobcard as jc', 'ji.Jobc_id', '=', 'jc.Jobc_id')
            ->select(
                'ji.Invoice_id',
                'ji.Jobc_id',
                'ji.type',
                'ji.Lnet',
                'ji.Pnet',
                'ji.Snet',
                'ji.Cnet',
                DB::raw('DATE_FORMAT(ji.datetime, "%d %b %y") as DATE'),
                DB::raw('ji.Ltax + ji.Ptax + ji.Stax + ji.Ctax as tax'),
                DB::raw('ji.Ldiscount + ji.Pdiscount + ji.Sdiscount + ji.Cdiscount as discount'),
                'ji.Total',
                'jc.Customer_name'
            )
            ->where('ji.type', $type)
            ->whereBetween(DB::raw('DATE(ji.datetime)'), [$from, $to])
            ->orderBy('ji.Jobc_id', 'desc')
            ->get();

        $fromFormatted = date('d-M-y', strtotime($from));
        $toFormatted   = date('d-M-y', strtotime($to));

        return view('finance.cashier.reports.type', compact('reports', 'type', 'fromFormatted', 'toFormatted'));
    }

    /**
     * Business Summary Report
     */
    public function summary(Request $request)
    {
        [$from, $to] = $this->parseDateRange($request->daterange);

        $summary = DB::table('jobc_invoice as ji')
            ->join('jobcard as jc', 'ji.Jobc_id', '=', 'jc.Jobc_id')
            ->select(
                'jc.RO_type',
                DB::raw('COUNT(ji.Jobc_id) as ROs'),
                DB::raw('SUM(ji.Lnet) as Labor'),
                DB::raw('SUM(ji.Pnet) as PARTS'),
                DB::raw('SUM(ji.Snet) as SUBLET'),
                DB::raw('SUM(ji.Cnet) as CONSUMBLE')
            )
            ->whereBetween(DB::raw('DATE(ji.datetime)'), [$from, $to])
            ->where('jc.status', '>', 1)
            ->groupBy('jc.RO_type')
            ->get();

        $fromFormatted = date('d-M-y', strtotime($from));
        $toFormatted   = date('d-M-y', strtotime($to));

        return view('finance.cashier.reports.summary', compact('summary', 'fromFormatted', 'toFormatted'));
    }

    /**
     * Print Initial RO
     */
    public function printInitialRO(Request $request)
    {
        $roNumber = $request->job_id ?? $request->id;

        $jobcard = DB::table('jobcard as jc')
            ->leftJoin('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->leftJoin('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('jc.Jobc_id', $roNumber)
            ->select(
                'jc.*',
                'v.Registration',
                'v.Frame_no',
                'v.Engine_Code',
                'v.Variant',
                'v.Make',
                'c.mobile',
                'c.Address',
                'c.email',
                'c.CNIC'
            )
            ->first();

        if (!$jobcard) {
            abort(404, 'Job card not found');
        }

        $vehicleHistory = DB::table('jobcard')
            ->where('Vehicle_id', $jobcard->Vehicle_id)
            ->where('Jobc_id', '!=', $roNumber)
            ->orderBy('Open_date_time', 'desc')
            ->first();

        // GET ALL LABOR ITEMS - BOTH Additional = 0 AND 1
        $laborItems = DB::table('jobc_labor')
            ->where('RO_no', $roNumber)
            ->orderBy('Additional', 'asc')
            ->get();

        // GET ALL SUBLET ITEMS
        $subletItems = DB::table('jobc_sublet')
            ->where('RO_no', $roNumber)
            ->get();

        // GET ALL PARTS ITEMS - BOTH Additional = 0 AND 1
        $partsItems = DB::table('jobc_parts')
            ->where('RO_no', $roNumber)
            ->orderBy('Additional', 'asc')
            ->get();

        // GET ALL CONSUMABLE ITEMS - BOTH Additional = 0 AND 1
        $consumableItems = DB::table('jobc_consumble')
            ->where('RO_no', $roNumber)
            ->orderBy('Additional', 'asc')
            ->get();

        $checklist = DB::table('jobc_checklist')
            ->where('RO_id', $roNumber)
            ->first();

        return view('finance.cashier.prints.initial-ro', compact(
            'jobcard',
            'vehicleHistory',
            'laborItems',
            'subletItems',
            'partsItems',
            'consumableItems',
            'checklist'
        ));
    }

    /**
     * Print Close RO
     */
    public function printCloseRO(Request $request)
    {
        $jobId = $request->job_id;

        $jobcard = DB::table('jobcard as jc')
            ->leftJoin('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->leftJoin('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->where('jc.Jobc_id', $jobId)
            ->select(
                'jc.*',
                'v.Registration',
                'v.Frame_no',
                'v.Engine_Code',
                'v.Variant',
                'v.Make',
                'c.mobile',
                'c.Address',
                'c.email',
                'c.CNIC'
            )
            ->first();

        // STANDARD labor (Additional = 0)
        $laborItems = DB::table('jobc_labor')
            ->where('RO_no', $jobId)
            ->where('Additional', '0')
            ->get();

        // ADDITIONAL labor (Additional = 1)
        $additionalLabor = DB::table('jobc_labor')
            ->where('RO_no', $jobId)
            ->where('Additional', '1')
            ->get();

        $subletItems = DB::table('jobc_sublet')
            ->where('RO_no', $jobId)
            ->get();

        // STANDARD parts (Additional = 0)
        $partsItems = DB::table('jobc_parts')
            ->where('RO_no', $jobId)
            ->where('Additional', '0')
            ->get();

        // ADDITIONAL parts (Additional = 1)
        $additionalParts = DB::table('jobc_parts')
            ->where('RO_no', $jobId)
            ->where('Additional', '1')
            ->get();

        // STANDARD consumables (Additional = 0)
        $consumableItems = DB::table('jobc_consumble')
            ->where('RO_no', $jobId)
            ->where('Additional', '0')
            ->get();

        // ADDITIONAL consumables (Additional = 1)
        $additionalConsumables = DB::table('jobc_consumble')
            ->where('RO_no', $jobId)
            ->where('Additional', '1')
            ->get();

        $invoice = DB::table('jobc_invoice')
            ->where('Jobc_id', $jobId)
            ->first();

        return view('finance.cashier.prints.close-ro', compact(
            'jobcard',
            'laborItems',
            'additionalLabor',
            'subletItems',
            'partsItems',
            'additionalParts',
            'consumableItems',
            'additionalConsumables',
            'invoice'
        ));
    }

    /**
     * Sales Tax Invoice
     */
    public function taxInvoice(Request $request)
    {
        $roNo = $request->inv_tax ?? $request->ro_no;

        $invoice = DB::table('jobc_invoice as ji')
            ->join('jobcard as jc', 'ji.Jobc_id', '=', 'jc.Jobc_id')
            ->join('customer_data as c', 'jc.Customer_id', '=', 'c.Customer_id')
            ->join('vehicles_data as v', 'jc.Vehicle_id', '=', 'v.Vehicle_id')
            ->select(
                'ji.*',
                'jc.Veh_reg_no',
                'jc.SA',
                'jc.Open_date_time',
                'jc.RO_type',
                'jc.Customer_name',
                'jc.Mileage',
                'jc.closing_time',
                'v.Variant',
                'v.Frame_no',
                'c.NTN',
                'c.STRN',
                'c.Supplier'
            )
            ->where('ji.Jobc_id', $roNo)
            ->orderBy('ji.Invoice_id', 'desc')
            ->first();

        $laborItems = DB::table('jobc_labor')
            ->where('RO_no', $roNo)
            ->where('status', 'Jobclose')
            ->get();

        $partsItems = DB::table('jobc_parts')
            ->where('RO_no', $roNo)
            ->whereIn('status', ['1', '3'])
            ->get();

        $subletItems = DB::table('jobc_sublet')
            ->where('RO_no', $roNo)
            ->where('status', 'JobDone')
            ->get();

        $consumableItems = DB::table('jobc_consumble')
            ->where('RO_no', $roNo)
            ->whereIn('status', ['1', '3'])
            ->get();

        return view('finance.cashier.prints.tax-invoice', compact(
            'invoice',
            'laborItems',
            'partsItems',
            'subletItems',
            'consumableItems'
        ));
    }

    private function calculateMSI($from, $to)
    {
        return [];
    }

    private function generateMSIExcelXML($data)
    {
        return '';
    }
}
