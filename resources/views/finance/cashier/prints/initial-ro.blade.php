<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Print RO #{{ $jobcard->Jobc_id }}</title>
    <style>
        body {
            size: 7in 9.25in;
            margin: 27mm 16mm 27mm 16mm;
            font-family: Arial, sans-serif;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        #header {
            height: 100px;
            width: 100%;
            margin: 0px;
            position: relative;
        }
        #customer {
            width: 100%;
            margin: 0px;
            float: left;
        }
        #heading-labels {
            border-top: 1px solid #dbabb9;
            background: black;
            padding: 7.5px 15px;
            -webkit-border-radius: 12px;
            -moz-border-radius: 12px;
            border-radius: 12px;
            -webkit-box-shadow: rgba(0,0,0,1) 0 1px 0;
            -moz-box-shadow: rgba(0,0,0,1) 0 1px 0;
            box-shadow: rgba(0,0,0,1) 0 1px 0;
            text-shadow: rgba(0,0,0,.4) 0 1px 0;
            color: white;
            font-size: 15px;
            font-family: Impact, Charcoal, sans-serif;
            text-decoration: none;
            vertical-align: middle;
        }
        th {
            background-color: red;
            color: #FFF;
            font-size: 14px;
            padding: 5px;
        }
        #ques {
            background-color: #D4D4D4;
            color: #000;
            font-size: 11px;
            height: 15px;
            padding: 3px;
        }
        #quess {
            background-color: #BFBCBC;
            color: #000;
            font-size: 10px;
            height: 14px;
            text-align: center;
            padding: 2px;
        }
        #ans {
            color: #000;
            font-size: 12px;
            font-family: Arial, Helvetica, sans-serif;
            padding: 3px;
        }
        #anss {
            color: #000;
            font-size: 11px;
            font-family: "Times New Roman", Times, serif;
            padding: 3px;
        }
        .featureList, .featureList ul {
            margin-top: 0;
            padding-left: 2em;
            list-style-type: none;
            font-size: 9px;
            font-family: Arial, Helvetica, sans-serif;
        }
        .featureList li:before {
            position: absolute;
            margin-left: -1.3em;
            font-weight: bold;
        }
        .featureList li.tick:before {
            content: "\2713";
            color: darkgreen;
        }
        .featureList li.cross:before {
            content: "\2717";
            color: crimson;
        }
        .float-left { float: left; }
        .float-right { float: right; }
        .clearfix { clear: both; }
        .border-dotted { border-style: dotted; }
        .text-center { text-align: center; }
        .mt-2 { margin-top: 10px; }
        .mb-2 { margin-bottom: 10px; }
        .w-100 { width: 100%; }
        .w-50 { width: 50%; }
    </style>
</head>
<body style="width:780px;">
    <div id="header">
        <div style="float:left; align:center; height:90px;">
            <br/>
            <div id="heading-labels"> MG Khyber </div>
            <br/>
            <label id="heading-labels">RO# {{ $jobcard->Jobc_id }}</label>
        </div>
        <div style="float:left;">
            <img src="{{ asset('images/header.png') }}" width="405px" height="100px"/>
        </div>
        <div style="float:right; height:90px;">
            <br/>
            <div id="heading-labels">RO Type : {{ $jobcard->RO_type }}</div>
            <br/>
            <label id="heading-labels">{{ \Carbon\Carbon::parse($jobcard->Open_date_time)->format('d M Y h:i A') }}</label>
        </div>
    </div>

    <div id="customer" class="clearfix">
        <table width="100%" border="1">
            <tr>
                <th colspan="3">Customer Information</th>
                <th>Estimations</th>
            </tr>
            <tr>
                <td colspan="2" id="ques">Name: <span id="ans">{{ $jobcard->Customer_name }}</span></td>
                <td id="ques">Phone: <span id="ans">{{ $jobcard->mobile }}</span></td>
                <td id="ques">Time: <span id="anss">{{ \Carbon\Carbon::parse($jobcard->Estim_time)->format('d M Y h:i A') }}</span></td>
            </tr>
            <tr>
                <td colspan="3" id="ques">Address: <span id="ans">{{ $jobcard->Address }}</span></td>
                <td id="ques">Cost: <span id="ans">{{ $jobcard->Estim_cost }}</span></td>
            </tr>
        </table>
    </div>

    <!-- Vehicle Details -->
    <div id="customer" class="mt-2">
        <table width="100%" border="1">
            <tr>
                <th colspan="7">Car Information</th>
            </tr>
            <tr>
                <td id="ques">Make</td>
                <td id="ques">Model</td>
                <td id="ques">Reg#</td>
                <td id="ques">Frame#</td>
                <td id="ques">Engine</td>
                <td id="ques">Mileage</td>
                <td id="ques">Fuel</td>
            </tr>
            <tr>
                <td id="ans">{{ $jobcard->Make }}</td>
                <td id="ans">{{ $jobcard->Variant }}</td>
                <td id="ans">{{ $jobcard->Registration }}</td>
                <td id="ans">{{ $jobcard->Frame_no }}</td>
                <td id="ans">{{ $jobcard->Engine_Code }}</td>
                <td id="ans">{{ $jobcard->Mileage }}</td>
                <td id="ans">{{ $jobcard->Fuel }}</td>
            </tr>
        </table>
    </div>

    <!-- Vehicle History -->
    <div style="float:right;" class="mt-2">
        <table width="385px" border="1">
            <tr>
                <th colspan="6">Vehicle History</th>
            </tr>
            <tr>
                <td id="ques">Visits</td>
                <td id="ques">Prior Visit</td>
                <td id="ques">RO#</td>
                <td id="ques">Odometer</td>
                <td id="ques">MSI_Cate</td>
                <td id="ques">SA</td>
            </tr>
            <tr>
                <td align="center">{{ $vehicleHistory ? '1' : '0' }}</td>
                <td>{{ $vehicleHistory ? \Carbon\Carbon::parse($vehicleHistory->Open_date_time)->format('d M Y') : '-' }}</td>
                <td>{{ $vehicleHistory->Jobc_id ?? '-' }}</td>
                <td>{{ $vehicleHistory->Mileage ?? '-' }}</td>
                <td>{{ $vehicleHistory->MSI_cat ?? '-' }}</td>
                <td>{{ $vehicleHistory->SA ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <!-- Roundabout -->
    <div style="width:130px; margin:0px; float:left; border-style:dotted;">
        <img src="{{ asset('images/condition.jpg') }}" width="130" height="161"/>
    </div>

    <!-- Check List -->
    <div style="border-radius:12px; width:255px; float:left; height:165px;">
        <table border="1" style="width:255px; height:165px;">
            <tr>
                <th colspan="2">Check List</th>
            </tr>
            <tr>
                <td>
                    <ul class="featureList">
                        <li class="{{ $checklist->usb == 1 ? 'tick' : 'cross' }}">USB Drives</li>
                        <li class="{{ $checklist->ashtray == 1 ? 'tick' : 'cross' }}">Ashtray</li>
                        <li class="{{ $checklist->wiperblader == 1 ? 'tick' : 'cross' }}">Wiper Blade</li>
                        <li class="{{ $checklist->dickymat == 1 ? 'tick' : 'cross' }}">Dicky Mat</li>
                        <li class="{{ $checklist->jackhandle == 1 ? 'tick' : 'cross' }}">Jack Handle</li>
                        <li class="{{ $checklist->perfume == 1 ? 'tick' : 'cross' }}">Perfume</li>
                        <li class="{{ $checklist->floormate == 1 ? 'tick' : 'cross' }}">Floor Mats</li>
                        <li class="{{ $checklist->cassete == 1 ? 'tick' : 'cross' }}">Cassette</li>
                        <li class="{{ $checklist->wheelcaps == 1 ? 'tick' : 'cross' }}">Wheel Caps</li>
                        <li class="{{ $checklist->extrakeys == 1 ? 'tick' : 'cross' }}">Extra Keys</li>
                        <li class="{{ $checklist->clock == 1 ? 'tick' : 'cross' }}">Clock</li>
                    </ul>
                </td>
                <td>
                    <ul class="featureList">
                        <li class="{{ $checklist->cardreader == 1 ? 'tick' : 'cross' }}">Card Reader</li>
                        <li class="{{ $checklist->lighter == 1 ? 'tick' : 'cross' }}">Lighter</li>
                        <li class="{{ $checklist->seatcover == 1 ? 'tick' : 'cross' }}">Seat Cover</li>
                        <li class="{{ $checklist->sparewheel == 1 ? 'tick' : 'cross' }}">Spare Wheel</li>
                        <li class="{{ $checklist->tools == 1 ? 'tick' : 'cross' }}">Tools</li>
                        <li class="{{ $checklist->remote == 1 ? 'tick' : 'cross' }}">Remote</li>
                        <li class="{{ $checklist->mirror == 1 ? 'tick' : 'cross' }}">Mirror</li>
                        <li class="{{ $checklist->hubcaps == 1 ? 'tick' : 'cross' }}">Hub Caps</li>
                        <li class="{{ $checklist->monogram == 1 ? 'tick' : 'cross' }}">Monogram</li>
                        <li class="{{ $checklist->anttena == 1 ? 'tick' : 'cross' }}">Antenna</li>
                        <li class="{{ $checklist->Navigation == 1 ? 'tick' : 'cross' }}">Navigation</li>
                    </ul>
                </td>
            </tr>
        </table>
    </div>

    <!-- Voice of Customer -->
    <div style="float:right;" class="mt-2">
        <table width="385px" border="1">
            <tr>
                <th>Voice of Customer</th>
            </tr>
            <tr>
                <td height="62px" style="vertical-align:top; font-size:14px; text-decoration:underline;">
                    {{ $jobcard->VOC }}
                </td>
            </tr>
        </table>
    </div>

    <div class="clearfix"></div>

    <!-- Job Requests -->
    <div style="width:390px; float:left; margin-top:3px;">
        <table border="1" style="width:390px;">
            <colgroup>
                <col style="width:235px" />
                <col style="width:40px" />
                <col style="width:65px" />
            </colgroup>
            <tr>
                <th colspan="3">Job Input Results</th>
            </tr>
            <tr>
                <td id="ques">Recommended Jobs</td>
                <td id="ques">Cost</td>
                <td id="ques">Status</td>
            </tr>

            @php $totalLabor = 0; @endphp
            @foreach($laborItems as $labor)
                @php $totalLabor += $labor->cost; @endphp
                <tr>
                    <td id="anss">{{ $labor->Labor }}</td>
                    <td id="ques">{{ number_format($labor->cost) }}</td>
                    <td id="ques">{{ $labor->type }}</td>
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

            @for($i = 0; $i < 19 - count($laborItems) - count($subletItems); $i++)
                <tr>
                    <td id="anss"></td>
                    <td id="ques"></td>
                    <td id="ques"></td>
                </tr>
            @endfor

            <tr>
                <td id="ques" colspan="2" align="right">Total:</td>
                <td id="ques">Rs {{ number_format($totalLabor) }}</td>
            </tr>
            <tr>
                <td id="quess" colspan="3">Diagnose By : {{ $jobcard->Diagnose_by }}</td>
            </tr>
        </table>
    </div>

    <!-- Parts & Lubricants -->
    <div style="width:387px; float:right;">
        <table border="1" style="width:387px;">
            <colgroup>
                <col style="width:235px" />
                <col style="width:25px" />
                <col style="width:35px" />
                <col style="width:40px" />
            </colgroup>
            <tr>
                <th colspan="4">Parts & Lubricants Required</th>
            </tr>
            <tr>
                <td id="ques">Description</td>
                <td id="ques">Qty</td>
                <td id="ques">UPrice</td>
                <td id="ques">Total</td>
            </tr>

            @php $totalParts = 0; @endphp
            @foreach($partsItems as $part)
                @php $totalParts += $part->total; @endphp
                <tr>
                    <td id="anss">{{ $part->part_description }}</td>
                    <td id="ques">{{ $part->qty }}</td>
                    <td id="ques">{{ number_format($part->unitprice) }}</td>
                    <td id="ques">{{ number_format($part->total) }}</td>
                </tr>
            @endforeach

            <tr><th colspan="4">Lubricants</th></tr>

            @foreach($consumableItems as $cons)
                @php $totalParts += $cons->total; @endphp
                <tr>
                    <td id="anss">{{ $cons->cons_description }}</td>
                    <td id="ques">{{ $cons->qty }}</td>
                    <td id="ques">{{ number_format($cons->unitprice) }}</td>
                    <td id="ques">{{ number_format($cons->total) }}</td>
                </tr>
            @endforeach

            @for($i = 0; $i < 20 - count($partsItems) - count($consumableItems); $i++)
                <tr>
                    <td id="anss"></td>
                    <td id="ques"></td>
                    <td id="ques"></td>
                    <td id="ques"></td>
                </tr>
            @endfor

            <tr>
                <td id="ques" colspan="2" align="right">Total:</td>
                <td id="ques" colspan="2">Rs {{ number_format($totalParts) }}</td>
            </tr>
        </table>
    </div>

    <div class="clearfix"></div>

    <!-- Terms and Conditions -->
    <div class="mt-2">
        <img src="{{ asset('images/terms_conditions.JPG') }}" width="780px">
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
