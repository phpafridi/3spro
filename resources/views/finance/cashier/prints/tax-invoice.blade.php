<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Sales Tax Invoice #{{ $invoice->Invoice_id }}</title>
    <style>
        .pagebreak { page-break-before: always; }
        body {
            height: 14cm;
            width: 21cm;
            margin: 0 auto;
            font-family: 'Courier New', monospace;
        }
        .tblehds {
            background-color: #808080;
            color: #FFF;
            font-size: 16px;
            padding: 5px;
        }
        .theads {
            background-color: #CCC;
            color: #000;
            font-size: 14px;
            padding: 5px;
        }
        .tlist {
            font-size: 12px;
        }
        .theads_ans {
            background-color: #CCC;
            color: #000;
            font-size: 14px;
            font-family: "Courier New", Courier, monospace;
        }
        table {
            width: 800px;
            border-collapse: collapse;
        }
        td, th {
            border: 1px solid #000;
            padding: 5px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .float-left { float: left; }
        .float-right { float: right; }
        .clearfix { clear: both; }
        .mt-2 { margin-top: 20px; }
        .mb-2 { margin-bottom: 20px; }
        .header-img {
            width: 21cm;
            height: 2cm;
        }
        .span_class {
            text-decoration: underline;
        }
        .pagebreak {
            position: relative;
            overflow: hidden;
            border: dashed 1px #ccc;
            margin-top: 20px;
            padding: 20px;
        }
        .pagebreak img {
            position: absolute;
            left: 0;
            top: 0;
            width: 90%;
            height: 100%;
            opacity: 0.25;
        }
        .gate-pass {
            border: double 3px #000;
            height: 50px;
            width: 90%;
            margin: 10px auto;
        }
    </style>
</head>
<body onload="window.print()">
    <div id="header" style="width:21cm; padding-bottom:4px;">
        @include('partials.company-header')
    </div>

    <h2 align="center" style="text-decoration:underline; margin:0;">Sales Tax Invoice</h2>

    <table border="0" width="800px" cellpadding="0">
        <tr>
            <td colspan="5"></td>
            <td class="theads" colspan="2">Customer Name:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->Customer_name }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="theads" colspan="2">Invoice No:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->type }}-{{ $invoice->Invoice_id }}</td>
        </tr>
        <tr>
            <td class="theads" colspan="2">Customer NTN:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->NTN ?? 'N/A' }}</td>
            <td width="126"></td>
            <td class="theads" colspan="2">Jobcard No:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->Jobc_id }}</td>
        </tr>
        <tr>
            <td class="theads" colspan="2">Customer STRN:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->STRN ?? 'N/A' }}</td>
            <td width="126"></td>
            <td class="theads" colspan="2">Registration:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->Veh_reg_no }}</td>
        </tr>
        <tr>
            <td class="theads" colspan="2">Supplier Number:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->Supplier ?? 'N/A' }}</td>
            <td width="126"></td>
            <td class="theads" colspan="2">Frame Number:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->Frame_no }}</td>
        </tr>
        <tr>
            <td class="theads" colspan="2">Company NTN:</td>
            <td class="theads_ans" colspan="2">4453609</td>
            <td width="126"></td>
            <td class="theads" colspan="2">Mileage:</td>
            <td class="theads_ans" colspan="2">{{ $invoice->Mileage }}</td>
        </tr>
        <tr>
            <td class="theads" colspan="2">Company K-STRN:</td>
            <td class="theads_ans" colspan="2">K-4453609-0</td>
            <td width="126"></td>
            <td class="theads" colspan="2">Date & time:</td>
            <td class="theads_ans" colspan="2">{{ \Carbon\Carbon::parse($invoice->datetime)->format('d-M-y g:i A') }}</td>
        </tr>
        <tr>
            <td class="theads" colspan="2">Company P-STRN:</td>
            <td class="theads_ans" colspan="2">P-4453609-0</td>
        </tr>
    </table>

    <!-- Labor -->
    <table width="800px">
        <tr>
            <td colspan="7" bgcolor="black" style="color:white" align="center">Invoice Details</td>
        </tr>
        <tr>
            <td class="theads_ans" colspan="6">Labor</td>
        </tr>

        @php
            $totalLabor = 0;
            $taxTotal = 0;
        @endphp

        @foreach($laborItems as $item)
            @php $totalLabor += $item->cost; @endphp
            <tr>
                <td class="tlist">{{ $item->Labor }}</td>
                <td class="tlist" colspan="4"></td>
                <td class="tlist">{{ number_format($item->cost) }}</td>
                <td></td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3"></td>
            <td colspan="3" align="right">Total Labor</td>
            <td class="theads_ans">{{ number_format($invoice->Labor) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" align="right">GST on Labor (10%)</td>
            <td class="theads_ans">{{ number_format($invoice->Ltax) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" class="tblehds" align="right">Total Labor Including Sales Tax</td>
            <td class="tblehds">{{ number_format($invoice->Lnet) }}</td>
        </tr>

        <!-- Parts -->
        <tr>
            <td class="theads_ans" colspan="6">Parts</td>
        </tr>

        @foreach($partsItems as $item)
            <tr>
                <td class="tlist">{{ $item->part_description }}</td>
                <td class="tlist" colspan="2"></td>
                <td class="tlist">{{ number_format($item->unitprice) }}</td>
                <td class="tlist">{{ $item->qty }}</td>
                <td class="tlist">{{ number_format($item->total) }}</td>
                <td></td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3"></td>
            <td colspan="3" align="right">Total Parts</td>
            <td class="theads_ans">{{ number_format($invoice->Parts) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" align="right">GST on Parts (18%)</td>
            <td class="theads_ans">{{ number_format($invoice->Ptax) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" class="tblehds" align="right">Total Parts Including Sales Tax</td>
            <td class="tblehds">{{ number_format($invoice->Pnet) }}</td>
        </tr>

        <!-- Sublet -->
        <tr>
            <td class="theads_ans" colspan="6">Sublet</td>
        </tr>

        @foreach($subletItems as $item)
            <tr>
                <td class="tlist">{{ $item->Sublet }}</td>
                <td class="tlist" colspan="2"></td>
                <td class="tlist">{{ number_format($item->unitprice) }}</td>
                <td class="tlist">{{ $item->qty }}</td>
                <td class="tlist">{{ number_format($item->total) }}</td>
                <td></td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3"></td>
            <td colspan="3" align="right">Total Sublet</td>
            <td class="theads_ans">{{ number_format($invoice->Sublet) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" align="right">GST on Sublet (10%)</td>
            <td class="theads_ans">{{ number_format($invoice->Stax) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" class="tblehds" align="right">Total Sublet Including Sales Tax</td>
            <td class="tblehds">{{ number_format($invoice->Snet) }}</td>
        </tr>

        <!-- Consumable -->
        <tr>
            <td class="theads_ans" colspan="6">Consumable</td>
        </tr>

        @foreach($consumableItems as $item)
            <tr>
                <td class="tlist">{{ $item->cons_description }}</td>
                <td class="tlist" colspan="2"></td>
                <td class="tlist">{{ number_format($item->unitprice) }}</td>
                <td class="tlist">{{ $item->qty }}</td>
                <td class="tlist">{{ number_format($item->total) }}</td>
                <td></td>
            </tr>
        @endforeach

        <tr>
            <td colspan="3"></td>
            <td colspan="3" align="right">Total Consumables</td>
            <td class="theads_ans">{{ number_format($invoice->Consumble) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" align="right">GST on Consumables (18%)</td>
            <td class="theads_ans">{{ number_format($invoice->Ctax) }}</td>
        </tr>
        <tr>
            <td colspan="3"></td>
            <td colspan="3" class="tblehds" align="right">Total Consumable Including Sales Tax</td>
            <td class="tblehds">{{ number_format($invoice->Cnet) }}</td>
        </tr>

        <tr bgcolor="black">
            <td class="tblehds" colspan="7" align="center"></td>
        </tr>

        <tr>
            <td colspan="5"></td>
            <td class="theads" align="center">Discount</td>
            <td style="font-size:22px;" class="tblehds">
                {{ number_format($invoice->Ldiscount + $invoice->Pdiscount + $invoice->Sdiscount + $invoice->Cdiscount) }}
            </td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="theads" align="center">Total TAX</td>
            <td style="font-size:22px;" class="tblehds">
                {{ number_format($invoice->Ltax + $invoice->Ptax + $invoice->Stax + $invoice->Ctax) }}
            </td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="tblehds" align="center">Total Amount</td>
            <td style="font-size:25px;" class="tblehds">
                Rs {{ number_format($invoice->Total) }}
            </td>
        </tr>
    </table>

    <p style="font-size:13px;">
        &#10033; I received the car along with all tools and accessories. The Repair have been performed to my satisfaction.
    </p>

    <table width="800px">
        <tr>
            <td>________________________________<br/>Cashier: {{ $invoice->cashier }}</td>
            @if($invoice->careof)
            <td>________________________________<br/>Careof : ({{ $invoice->careof }})</td>
            @endif
            <td>________________________________<br/>Customer signature</td>
        </tr>
    </table>

    <footer>
        <p style="font-size:14px; font-family:'Courier New', Courier, monospace;">
            NOTE: No Sales Tax is charged as per SRO No. 896(1)2003 dated 04-10-2013 read with chapter XIII Sales Tax procedure rules 2007 therefore withholding of Sales Tax is no required on supplier made by us.
        </p>
    </footer>

    <!-- Gate Pass -->
    <div class="pagebreak">
        <h1 style="text-align:center; text-decoration:underline;">GATE PASS</h1>

        <div class="gate-pass">
            <div style="width:45%; float:left; font-size:22px;">
                Gate Pass # <span class="span_class">{{ $invoice->Invoice_id }}</span>
            </div>
            <div style="width:45%; float:right; font-size:22px;">
                Veh REG # <span class="span_class">{{ $invoice->Veh_reg_no }}</span>
            </div>
        </div>

        <table width="90%" align="center">
            <tr>
                <td>Customer Name: <span class="span_class">{{ $invoice->Customer_name }}</span></td>
            </tr>
            <tr>
                <td>Jobcard Number: <span class="span_class">{{ $invoice->Jobc_id }}</span></td>
                <td>Type of RO: <span class="span_class">{{ $invoice->RO_type }}</span></td>
            </tr>
            <tr>
                <td>Model: <span class="span_class">{{ $invoice->Variant }}</span></td>
                <td>Frame Number: <span class="span_class">{{ $invoice->Frame_no }}</span></td>
            </tr>
        </table>

        @php
            $openDateTime = \Carbon\Carbon::parse($invoice->Open_date_time)->format('d-M g:i A');
            $closeDateTime = \Carbon\Carbon::parse($invoice->closing_time)->format('d-M g:i A');
        @endphp

        <div style="height:40px;">
            <div style="width:45%; border:double; float:left; font-size:15px; height:40px;">
                <br/>Booking Date&Time : <span class="span_class">{{ $openDateTime }}</span>
            </div>
            <div style="width:45%; border:double; float:right; font-size:15px; height:40px;">
                <br/>Delivery Date&Time : <span class="span_class">{{ $closeDateTime }}</span>
            </div>
        </div>

        <img src="{{ asset('images/' . $invoice->RO_type . '.png') }}" class="imagebg" style="opacity:0.25; width:100%;">

        <table width="100%">
            <tr>
                <td align="center"><br/>Service Advisor</td>
                <td align="center"><br/></td>
                <td align="center"><br/>Issued By({{ $invoice->cashier }})</td>
            </tr>
            <tr>
                <td align="center"><br/><br/><span class="span_class">{{ $invoice->SA }}</span></td>
                <td align="center"><br/><br/></td>
                <td align="center"><br/><br/>_____________________</td>
            </tr>
        </table>
    </div>
</body>
</html>
