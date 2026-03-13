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
        }
        td {
            border: 1px solid #000;
            padding: 2px 4px;
        }
        #header {
            height: 100px;
            width: 790px;
            margin: 0px;
            position: relative;
        }
        #heading-labels {
            border-top: 1px solid #dbabb9;
            background: red;
            padding: 7.5px 15px;
            -webkit-border-radius: 12px;
            -moz-border-radius: 12px;
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
            height: 12px;
            padding: 2px;
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
        .w-100 { width: 100%; }
        .mt-2 { margin-top: 10px; }
    </style>
</head>
<body>
    <div id="header">
        <div style="float:left; height:90px;">
            <br/>
            <label id="heading-labels">Repair Order # <strong>{{ $jobcard->Jobc_id }}</strong></label>
        </div>
        <div style="float:left;">
            <a href="{{ route('cashier.print.tax-invoice', ['id' => $jobcard->Jobc_id]) }}">
                <img src="{{ asset('images/header.png') }}" width="420px" height="100px"/>
            </a>
        </div>
        <div style="float:right; height:90px;">
            <br/>
            <label id="heading-labels">Type: <strong>{{ $jobcard->RO_type }}</strong></label>
        </div>
    </div>

    <div class="clearfix"></div>

    <!-- Job Requests -->
    <div style="width:390px; float:left;">
        <table border="1" style="width:390px;">
            <tr><th colspan="5">Job Requests</th></tr>
            <colgroup>
                <col style="width:175px" />
                <col style="width:30px" />
                <col style="width:35px" />
                <col style="width:40px" />
                <col style="width:30px" />
            </colgroup>
            <tr>
                <td id="ques">Description</td>
                <td id="ques">Time</td>
                <td id="ques">Bay</td>
                <td id="ques">Team</td>
                <td id="ques">Cost</td>
            </tr>

            @php
                $totalLabor = 0;
                $rowCount = 0;
            @endphp

            @foreach($laborItems as $labor)
                @php
                    $totalLabor += $labor->cost;
                    $rowCount++;
                    $timeElapsed = $labor->timeelapsed ?? 0;
                    if($timeElapsed > 60) {
                        $timeDisplay = number_format($timeElapsed/60, 2) . " Hrs";
                    } else {
                        $timeDisplay = $timeElapsed . " Min";
                    }
                @endphp
                <tr>
                    <td id="anss">{{ $labor->Labor }}</td>
                    @if($labor->status == 'Job Not Done')
                        <td align="center" id="anss" colspan="3">Job Not Done(JC)</td>
                    @else
                        <td id="anss">{{ $timeDisplay }}</td>
                        <td id="anss">{{ $labor->bay }}</td>
                        <td id="anss">{{ $labor->team }}</td>
                    @endif
                    <td id="ques">{{ number_format($labor->cost) }}</td>
                </tr>
            @endforeach

            <tr><th colspan="5">Additional Jobs</th></tr>

            @foreach($additionalLabor as $addLabor)
                @php
                    $totalLabor += $addLabor->cost;
                    $rowCount++;
                    $timeElapsed = $addLabor->timeelapsed ?? 0;
                    if($timeElapsed > 60) {
                        $timeDisplay = number_format($timeElapsed/60, 2) . " Hrs";
                    } else {
                        $timeDisplay = $timeElapsed . " Min";
                    }
                @endphp
                <tr>
                    <td id="anss">{{ $addLabor->Labor }}</td>
                    @if($addLabor->type == 'Workshop')
                        @if($addLabor->status == 'Jobclose')
                            <td id="anss">{{ $timeDisplay }}</td>
                            <td id="anss">{{ $addLabor->bay }}</td>
                            <td id="anss">{{ $addLabor->team }}</td>
                        @else
                            <td align="center" id="anss" colspan="3">Job Not Done(JC)</td>
                        @endif
                    @else
                        <td align="center" id="anss" colspan="4">{{ $addLabor->type }}</td>
                    @endif
                    <td id="ques">{{ number_format($addLabor->cost) }}</td>
                </tr>
            @endforeach

            @for($i = 0; $i < 56 - count($laborItems) - count($additionalLabor); $i++)
                <tr>
                    <td id="anss"></td>
                    <td id="anss"></td>
                    <td id="anss"></td>
                    <td id="anss"></td>
                    <td id="ques"></td>
                </tr>
            @endfor

            <tr>
                <td id="ques" colspan="3" align="right">Total:</td>
                <td id="ques" colspan="2" align="center">Rs {{ number_format($totalLabor) }}</td>
            </tr>
        </table>
    </div>

    <!-- Sublet & Additional Parts -->
    <div style="width:390px; float:right;">
        <table border="1" style="width:390px;">
            <tr><th colspan="4">SUBLET</th></tr>
            <tr>
                <td id="ques">Description</td>
                <td id="ques">Qty</td>
                <td id="ques">UnitPrice</td>
                <td id="ques">Total</td>
            </tr>

            @php $totalSublet = 0; @endphp
            @foreach($subletItems as $sublet)
                @php $totalSublet += $sublet->total; @endphp
                <tr>
                    <td id="anss">{{ $sublet->Sublet }}</td>
                    <td id="anss">{{ $sublet->qty }}</td>
                    <td id="anss">{{ number_format($sublet->unitprice) }}</td>
                    <td id="ques">{{ number_format($sublet->total) }}</td>
                </tr>
            @endforeach

            <tr>
                <td id="ques" colspan="3" align="right">Total:</td>
                <td id="ques">Rs {{ number_format($totalSublet) }}</td>
            </tr>

            <tr><th colspan="4">Additional Parts</th></tr>

            @php $totalParts = 0; @endphp
            @foreach($additionalParts as $part)
                @php $totalParts += $part->total; @endphp
                <tr>
                    <td id="anss">{{ $part->part_description }}</td>
                    <td id="anss">{{ $part->qty }}</td>
                    <td id="anss">{{ number_format($part->unitprice) }}</td>
                    <td id="ques">{{ number_format($part->total) }}</td>
                </tr>
            @endforeach

            <tr><th colspan="4">Additional Lubricants</th></tr>

            @foreach($additionalConsumables as $cons)
                @php $totalParts += $cons->total; @endphp
                <tr>
                    <td id="anss">{{ $cons->cons_description }}</td>
                    <td id="anss">{{ $cons->qty }}</td>
                    <td id="anss">{{ number_format($cons->unitprice) }}</td>
                    <td id="ques">{{ number_format($cons->total) }}</td>
                </tr>
            @endforeach

            @for($i = 0; $i < 53 - count($subletItems) - count($additionalParts) - count($additionalConsumables); $i++)
                <tr>
                    <td id="anss"></td>
                    <td id="anss"></td>
                    <td id="anss"></td>
                    <td id="ques"></td>
                </tr>
            @endfor

            <tr>
                <td id="ques" colspan="3" align="right">Total:</td>
                <td id="ques">Rs {{ number_format($totalParts) }}</td>
            </tr>
        </table>
    </div>

    <div class="clearfix"></div>

    <!-- Time Consumed -->
    <div id="customer" class="mt-2">
        <table width="100%" border="1">
            <tr><th colspan="5">Time Consumed</th></tr>
            <tr>
                <td id="ques">Clock ON</td>
                <td id="ques">Clock OFF</td>
                <td id="ques">Total Time</td>
                <td id="ques">Estimated Time</td>
            </tr>
            <tr>
                <td id="anss">{{ \Carbon\Carbon::parse($jobcard->Open_date_time)->format('d M Y h:i A') }}</td>
                <td id="anss">{{ \Carbon\Carbon::parse($jobcard->closing_time)->format('d M Y h:i A') }}</td>
                <td id="anss">
                    @php
                        $toTime = strtotime($jobcard->closing_time);
                        $fromTime = strtotime($jobcard->Open_date_time);
                        $hours = round(abs($toTime - $fromTime) / 60);
                    @endphp
                    {{ $hours }} Hours
                </td>
                <td id="anss">3:00 Hours</td>
            </tr>
        </table>
    </div>

    <!-- PSFU -->
    <div style="float:left;" class="mt-2">
        <table border="1" style="width:390px;">
            <tr><th colspan="4">Post Service Follow Up</th></tr>
            <tr><td id="ques">Plan PSFU Date & time</td><td id="ans"></td></tr>
            <tr><td id="ques">Actual PSFU Date & time</td><td id="ans"></td></tr>
            <tr><td id="ques">Contacted with</td><td id="ans"></td></tr>
            <tr><td id="ques">PSFU Result</td><td id="ans"></td></tr>
            <tr><td id="ques">CRO name</td><td id="ans"></td></tr>
            <tr>
                <td colspan="6">
                    Received the car along with all tools and accessories. The Repair have been performed to my satisfaction.<br/>
                    ____________________________________
                </td>
            </tr>
        </table>
    </div>

    <!-- Total Invoice -->
    <div align="right" class="mt-2">
        @if($invoice)
        <table border="1" style="width:390px;">
            <tr><th colspan="4">Total</th></tr>
            <tr>
                <td id="ques">Bill No.</td>
                <td id="ans">{{ $invoice->type }}-{{ $invoice->Invoice_id }}</td>
                <td id="ques">Labor</td>
                <td id="ques">Rs {{ number_format($invoice->Labor) }}</td>
            </tr>
            <tr>
                <td id="ques">Estmd Cost</td>
                <td id="ans">Rs</td>
                <td id="ques">SUBLET</td>
                <td id="ques">Rs {{ number_format($invoice->Sublet) }}</td>
            </tr>
            <tr>
                <td id="ques">Gate Pass</td>
                <td id="ans"></td>
                <td id="ques">LUB OIL</td>
                <td id="ques">Rs {{ number_format($invoice->Consumble) }}</td>
            </tr>
            <tr>
                <td id="ans"></td>
                <td id="ans"></td>
                <td id="ques">PARTS</td>
                <td id="ques">Rs {{ number_format($invoice->Parts) }}</td>
            </tr>
            <tr>
                <td width="70" rowspan="5" valign="bottom">
                    <p align="center">________<br/><span id="anss">Service Manager</span></p>
                </td>
                <td width="70" rowspan="5" valign="bottom">
                    <p align="center">_______<br/><span id="anss">Billing Clerk</span></p>
                </td>
                <td id="ans">TOTAL</td>
                <td id="ans"><strong>Rs {{ number_format($invoice->Labor + $invoice->Parts + $invoice->Sublet + $invoice->Consumble) }}</strong></td>
            </tr>
            <tr>
                <td id="ques">TAX</td>
                <td id="ques">{{ number_format($invoice->Ltax + $invoice->Ptax + $invoice->Stax + $invoice->Ctax) }}</td>
            </tr>
            <tr>
                <td id="ques">Discount</td>
                <td id="ques">{{ number_format($invoice->Ldiscount + $invoice->Pdiscount + $invoice->Sdiscount + $invoice->Cdiscount) }}</td>
            </tr>
            <tr>
                <td id="ans">Grand TOTAL</td>
                <td id="ans"><strong>Rs {{ number_format($invoice->Total) }}</strong></td>
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
