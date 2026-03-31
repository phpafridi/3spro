<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>JC Close #{{ $jobcard->Jobc_id }}</title>
    <style>
        body {
            size: 7in 9.25in;
            margin: 27mm 16mm 27mm 16mm;
            width: 780px;
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        td, th {
            border: 1px solid #000;
            padding: 4px;
        }
        #header {
            height: 100px;
            width: 100%;
            margin: 0px;
            position: relative;
        }
        #heading-labels {
            border-top: 1px solid #dbabb9;
            background: red;
            padding: 7.5px 15px;
            border-radius: 12px;
            color: white;
            font-size: 15px;
            font-family: Impact, Charcoal, sans-serif;
        }
        th {
            background-color: red;
            color: #FFF;
            font-size: 12px;
            padding: 5px;
        }
        #ques {
            background-color: #CCC;
            color: #000;
            font-size: 9px;
            padding: 3px;
            font-weight: bold;
        }
        #ans {
            color: #000;
            font-size: 9px;
            font-family: Arial, Helvetica, sans-serif;
        }
        #anss {
            color: #000;
            font-size: 8px;
            font-family: "Times New Roman", Times, serif;
        }
        .float-left { float: left; }
        .float-right { float: right; }
        .clearfix { clear: both; }
        .text-center { text-align: center; }
        .mt-2 { margin-top: 10px; }
        .additional-badge {
            background-color: #ff9800;
            color: white;
            font-size: 7px;
            padding: 2px 4px;
            border-radius: 3px;
            margin-left: 5px;
            font-weight: bold;
        }
        .standard-badge {
            background-color: #4caf50;
            color: white;
            font-size: 7px;
            padding: 2px 4px;
            border-radius: 3px;
            margin-left: 5px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div id="header">
        <div style="float:left; height:90px;">
            <br/>
            <label id="heading-labels">Repair Order # <strong>{{ $jobcard->Jobc_id }}</strong></label>
        </div>
        <div style="float:left; text-align:center; padding:6px 10px;">
            @php $logoPath = public_path(config('company.logo_path')); @endphp
            @if(file_exists($logoPath))
                <img src="{{ asset(config('company.logo_path')) }}" height="80px"
                     onerror="this.style.display='none'"/>
            @else
                <div style="font-size:12px; font-weight:bold; padding:10px;">{{ config('company.name') }}</div>
            @endif
        </div>
        <div style="float:right; height:90px;">
            <br/>
            <label id="heading-labels">Type: <strong>{{ $jobcard->RO_type }}</strong></label>
        </div>
    </div>

    <div class="clearfix"></div>

    <!-- Customer & Vehicle Info (SAME as initial-ro) -->
    <div style="width:100%; margin-top:10px;">
        <table width="100%" border="1">
            <tr><th colspan="4">Customer & Vehicle Information</th></tr>
            <tr>
                <td id="ques">Customer Name</td>
                <td id="ans">{{ $jobcard->Customer_name }}</td>
                <td id="ques">Phone</td>
                <td id="ans">{{ $jobcard->mobile ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td id="ques">Registration</td>
                <td id="ans">{{ $jobcard->Registration ?? 'N/A' }}</td>
                <td id="ques">Variant</td>
                <td id="ans">{{ $jobcard->Variant ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td id="ques">Make</td>
                <td id="ans">{{ $jobcard->Make ?? 'N/A' }}</td>
                <td id="ques">Frame No</td>
                <td id="ans">{{ $jobcard->Frame_no ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td id="ques">Mileage</td>
                <td id="ans">{{ $jobcard->Mileage ?? 'N/A' }}</td>
                <td id="ques">Fuel</td>
                <td id="ans">{{ $jobcard->Fuel ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- ALL JOB REQUESTS (Standard + Additional) - SAME as initial-ro -->
    <div style="width:390px; float:left; margin-top:10px;">
        <table border="1" style="width:390px;">
            <colgroup>
                <col style="width:235px" />
                <col style="width:40px" />
                <col style="width:65px" />
            </colgroup>
            <tr><th colspan="3">Job Input Results</th></tr>
            <tr>
                <td id="ques">Recommended Jobs</td>
                <td id="ques">Cost</td>
                <td id="ques">Status</td>
            </tr>

            @php $totalLabor = 0; @endphp

            <!-- STANDARD LABOR ITEMS -->
            @foreach($laborItems as $labor)
                @php $totalLabor += $labor->cost; @endphp
                <tr>
                    <td id="anss">
                        {{ $labor->Labor }}
                        <span class="standard-badge">STANDARD</span>
                    </td>
                    <td id="ques">{{ number_format($labor->cost) }}</td>
                    <td id="ques">{{ $labor->type }}</td>
                </tr>
            @endforeach

            <!-- ADDITIONAL LABOR ITEMS -->
            @foreach($additionalLabor as $addLabor)
                @php $totalLabor += $addLabor->cost; @endphp
                <tr style="background-color:#fff3cd;">
                    <td id="anss">
                        {{ $addLabor->Labor }}
                        <span class="additional-badge">ADDITIONAL</span>
                    </td>
                    <td id="ques">{{ number_format($addLabor->cost) }}</td>
                    <td id="ques">{{ $addLabor->type }}</td>
                </tr>
            @endforeach

            <tr><th colspan="3">Sublet</th></tr>

            @foreach($subletItems as $sublet)
                @php $totalLabor += $sublet->total; @endphp
                <tr>
                    <td id="anss">{{ $sublet->Sublet }}</td>
                    <td id="ques">{{ number_format($sublet->total) }}</td>
                    <td id="ques">{{ $sublet->type }}</td>
                </tr>
            @endforeach

            <tr>
                <td id="ques" colspan="2" align="right">Total Labor:</td>
                <td id="ques">Rs {{ number_format($totalLabor) }}</td>
            </tr>
            <tr>
                <td id="quess" colspan="3">Diagnose By : {{ $jobcard->Diagnose_by ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <!-- ALL PARTS (Standard + Additional) - SAME as initial-ro -->
    <div style="width:387px; float:right; margin-top:10px;">
        <table border="1" style="width:387px;">
            <colgroup>
                <col style="width:200px" />
                <col style="width:35px" />
                <col style="width:50px" />
                <col style="width:50px" />
            </colgroup>
            <tr><th colspan="4">Parts & Lubricants Required</th></tr>
            <tr>
                <td id="ques">Description</td>
                <td id="ques">Qty</td>
                <td id="ques">UPrice</td>
                <td id="ques">Total</td>
            </tr>

            @php $totalParts = 0; @endphp

            <!-- STANDARD PARTS -->
            @foreach($partsItems as $part)
                @php $totalParts += $part->total; @endphp
                <tr>
                    <td id="anss">
                        {{ $part->part_description }}
                        <span class="standard-badge">STANDARD</span>
                    </td>
                    <td id="ques">{{ $part->qty }}</td>
                    <td id="ques">{{ number_format($part->unitprice) }}</td>
                    <td id="ques">{{ number_format($part->total) }}</td>
                </tr>
            @endforeach

            <!-- ADDITIONAL PARTS -->
            @foreach($additionalParts as $part)
                @php $totalParts += $part->total; @endphp
                <tr style="background-color:#fff3cd;">
                    <td id="anss">
                        {{ $part->part_description }}
                        <span class="additional-badge">ADDITIONAL</span>
                    </td>
                    <td id="ques">{{ $part->qty }}</td>
                    <td id="ques">{{ number_format($part->unitprice) }}</td>
                    <td id="ques">{{ number_format($part->total) }}</td>
                </tr>
            @endforeach

            <tr><th colspan="4">Lubricants</th></tr>

            <!-- STANDARD CONSUMABLES -->
            @foreach($consumableItems as $cons)
                @php $totalParts += $cons->total; @endphp
                <tr>
                    <td id="anss">
                        {{ $cons->cons_description }}
                        <span class="standard-badge">STANDARD</span>
                    </td>
                    <td id="ques">{{ $cons->qty }}</td>
                    <td id="ques">{{ number_format($cons->unitprice) }}</td>
                    <td id="ques">{{ number_format($cons->total) }}</td>
                </tr>
            @endforeach

            <!-- ADDITIONAL CONSUMABLES -->
            @foreach($additionalConsumables as $cons)
                @php $totalParts += $cons->total; @endphp
                <tr style="background-color:#fff3cd;">
                    <td id="anss">
                        {{ $cons->cons_description }}
                        <span class="additional-badge">ADDITIONAL</span>
                    </td>
                    <td id="ques">{{ $cons->qty }}</td>
                    <td id="ques">{{ number_format($cons->unitprice) }}</td>
                    <td id="ques">{{ number_format($cons->total) }}</td>
                </tr>
            @endforeach

            <tr>
                <td id="ques" colspan="2" align="right">Total Parts:</td>
                <td id="ques" colspan="2">Rs {{ number_format($totalParts) }}</td>
            </tr>
        </table>
    </div>

    <div class="clearfix"></div>

    <!-- Time Consumed -->
    <div class="mt-2">
        <table width="100%" border="1">
            <tr><th colspan="4">Time Consumed</th></tr>
            <tr>
                <td id="ques">Clock ON</td>
                <td id="ques">Clock OFF</td>
                <td id="ques">Total Time</td>
                <td id="ques">Estimated Time</td>
            </tr>
            <tr>
                <td id="anss">{{ \Carbon\Carbon::parse($jobcard->Open_date_time)->format('d M Y h:i A') }}</td>
                <td id="anss">{{ $jobcard->closing_time ? \Carbon\Carbon::parse($jobcard->closing_time)->format('d M Y h:i A') : 'Not closed' }}</td>
                <td id="anss">
                    @php
                        if($jobcard->closing_time) {
                            $toTime = strtotime($jobcard->closing_time);
                            $fromTime = strtotime($jobcard->Open_date_time);
                            $hours = round(abs($toTime - $fromTime) / 3600, 1);
                            echo $hours . " Hours";
                        } else {
                            echo "In Progress";
                        }
                    @endphp
                </td>
                <td id="anss">3:00 Hours</td>
            </tr>
        </table>
    </div>

    <!-- PSFU -->
    <div style="float:left;" class="mt-2">
        <table border="1" style="width:390px;">
            <tr><th colspan="4">Post Service Follow Up</th></tr>
            <tr><td id="ques">Plan PSFU Date & time</td><td id="ans" colspan="3">&nbsp;</td></tr>
            <tr><td id="ques">Actual PSFU Date & time</td><td id="ans" colspan="3">&nbsp;</td></tr>
            <tr><td id="ques">Contacted with</td><td id="ans" colspan="3">&nbsp;</td></tr>
            <tr><td id="ques">PSFU Result</td><td id="ans" colspan="3">&nbsp;</td></tr>
            <tr><td id="ques">CRO name</td><td id="ans" colspan="3">{{ $jobcard->CRO ?? $jobcard->SA ?? 'N/A' }}</td></tr>
            <tr>
                <td colspan="4">
                    Received the car along with all tools and accessories. The Repair have been performed to my satisfaction.<br/><br/>
                    Customer Signature: _________________________
                </td>
            </tr>
        </table>
    </div>

    <!-- Total Invoice -->
    <div align="right" class="mt-2">
        @if(isset($invoice) && $invoice)
        <table border="1" style="width:390px;">
            <tr><th colspan="4">Invoice Summary</th></tr>
            <tr>
                <td id="ques">Bill No.</td>
                <td id="ans">{{ $invoice->type ?? 'INV' }}-{{ $invoice->Invoice_id ?? 'N/A' }}</td>
                <td id="ques">Labor</td>
                <td id="ques">Rs {{ number_format($invoice->Labor ?? 0) }}</td>
            </tr>
            <tr>
                <td id="ques">Estmd Cost</td>
                <td id="ans">Rs {{ number_format($jobcard->Estim_cost ?? 0) }}</td>
                <td id="ques">SUBLET</td>
                <td id="ques">Rs {{ number_format($invoice->Sublet ?? 0) }}</td>
            </tr>
            <tr>
                <td id="ques">Gate Pass</td>
                <td id="ans">-</td>
                <td id="ques">LUB OIL</td>
                <td id="ques">Rs {{ number_format($invoice->Consumble ?? 0) }}</td>
            </tr>
            <tr>
                <td id="ans"></td>
                <td id="ans"></td>
                <td id="ques">PARTS</td>
                <td id="ques">Rs {{ number_format($invoice->Parts ?? 0) }}</td>
            </tr>
            <tr>
                <td width="70" rowspan="4" valign="bottom">
                    <p align="center">________<br/><span id="anss">Service Manager</span></p>
                </td>
                <td width="70" rowspan="4" valign="bottom">
                    <p align="center">_______<br/><span id="anss">Billing Clerk</span></p>
                </td>
                <td id="ans">TOTAL</td>
                <td id="ans"><strong>Rs {{ number_format(($invoice->Labor ?? 0) + ($invoice->Parts ?? 0) + ($invoice->Sublet ?? 0) + ($invoice->Consumble ?? 0)) }}</strong></td>
            </tr>
            <tr>
                <td id="ques">TAX</td>
                <td id="ques">{{ number_format(($invoice->Ltax ?? 0) + ($invoice->Ptax ?? 0) + ($invoice->Stax ?? 0) + ($invoice->Ctax ?? 0)) }}</td>
            </tr>
            <tr>
                <td id="ques">Discount</td>
                <td id="ques">{{ number_format(($invoice->Ldiscount ?? 0) + ($invoice->Pdiscount ?? 0) + ($invoice->Sdiscount ?? 0) + ($invoice->Cdiscount ?? 0)) }}</td>
            </tr>
            <tr>
                <td id="ans">Grand TOTAL</td>
                <td id="ans"><strong>Rs {{ number_format($invoice->Total ?? 0) }}</strong></td>
            </tr>
        </table>
        @else
        <table border="1" style="width:390px;">
            <tr><th colspan="4">Invoice Summary</th></tr>
            <tr>
                <td id="ques" colspan="4" align="center">No invoice generated yet</td>
            </tr>
            <tr>
                <td id="ques">Total Labor</td>
                <td id="ques" colspan="3">Rs {{ number_format($totalLabor) }}</td>
            </tr>
            <tr>
                <td id="ques">Total Sublet</td>
                <td id="ques" colspan="3">Rs {{ number_format($totalSublet ?? 0) }}</td>
            </tr>
            <tr>
                <td id="ques">Total Parts</td>
                <td id="ques" colspan="3">Rs {{ number_format($totalParts) }}</td>
            </tr>
            <tr>
                <td id="ans">GRAND TOTAL</td>
                <td id="ans" colspan="3"><strong>Rs {{ number_format($totalLabor + ($totalSublet ?? 0) + $totalParts) }}</strong></td>
            </tr>
        </table>
        @endif
    </div>

    <div class="clearfix"></div>

    <script>
        window.print();
    </script>
</body>
</html>
