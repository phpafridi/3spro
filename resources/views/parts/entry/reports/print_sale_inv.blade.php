<!DOCTYPE html>
<html>
<head>
    <title>Sale Invoice SJV-{{ $invoice->sale_inv }}</title>
    <style>
        @media print {
            body { margin: 0; padding: 0; }
            .pagebreak { page-break-before: always; }
        }
        body { font-family: Arial, sans-serif; }
        .tblehds { background-color: #8E8E8E; color: #FFF; font-size: 16px; padding: 5px; }
        .theads { background-color: #CCC; color: #000; font-size: 15px; padding: 5px; }
        .theads_ans { background-color: #CCC; color: #000; font-size: 14px; padding: 5px; }
        table { border-collapse: collapse; width: 100%; margin: 0 auto; }
        td, th { padding: 5px; border: 1px solid #ddd; }
        .header { text-align: center; margin-bottom: 20px; }
    </style>
</head>
<body onLoad="window.print();">
    <div align="center">
        @include('partials.company-header')
        <h2>Parts Sale Invoice</h2>
    </div>

    <table width="100%">
        <tr>
            <td class="theads">Sale Date:</td>
            <td class="theads_ans">{{ date('d M y', strtotime($invoice->datetime)) }}</td>
            <td class="theads">Invoice Number:</td>
            <td class="theads_ans">SJV-{{ $invoice->sale_inv }}</td>
        </tr>
        <tr>
            <td class="theads">Jobber:</td>
            <td class="theads_ans">{{ $invoice->Jobber }}</td>
            <td class="theads">Payment Method:</td>
            <td class="theads_ans">{{ $invoice->payment_method }}</td>
        </tr>
    </table>

    <br/>

    <table>
        <thead>
             <tr>
                <th>S.no</th>
                <th>Part No</th>
                <th colspan="2">Description</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Total</th>
             </tr>
        </thead>
        <tbody>
            @php $serial = 0; @endphp
            @foreach($parts as $part)
                @php $serial++; @endphp
                <tr>
                    <td align="center">{{ $serial }}</td>
                    <td>{{ $part->part_no }}</td>
                    <td colspan="2" style="font-size:11px;">{{ $part->Description }}</td>
                    <td align="center">{{ $part->quantity }}</td>
                    <td align="center">{{ number_format($part->sale_price, 2) }}</td>
                    <td align="center">{{ number_format($part->discount, 2) }}</td>
                    <td align="center">{{ number_format($part->tax, 2) }}</td>
                    <td align="center">{{ number_format($part->netamount + $part->tax, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <br/>

    <table width="100%">
        <tr>
            <td colspan="5"></td>
            <td class="tblehds">Gross Sale:</td>
            <td class="tblehds" align="center">{{ number_format($invoice->Total_amount + $invoice->discount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="theads">Discount:</td>
            <td class="theads" align="center">{{ number_format($invoice->discount, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="theads">GST:</td>
            <td class="theads" align="center">{{ number_format($invoice->tax, 2) }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="tblehds">Net Amount:</td>
            <td class="tblehds" align="center">Rs {{ number_format($invoice->Total_amount + $invoice->tax, 2) }}</td>
        </tr>
    </table>

    <div>
        <p><strong>Remarks:</strong><br/>{{ $invoice->remarks }}</p>
    </div>

    <br/><br/>
    <table width="100%">
        <tr>
            <td align="center">____________________<br/>Sold By: {{ $invoice->user }}</td>
            <td align="center">____________________<br/>Jobber: {{ $invoice->Jobber }}</td>
            <td align="center">____________________<br/>Warehouse Incharge</td>
        </tr>
    </table>

    <!-- Gate Pass -->
    <div class="pagebreak">
        <h2 align="center">GATE PASS</h2>
        <table width="100%">
            <tr>
                <td class="theads">Sale Date:</td>
                <td class="theads_ans">{{ date('d M y', strtotime($invoice->datetime)) }}</td>
                <td class="theads">Invoice Number:</td>
                <td class="theads_ans">SJV-{{ $invoice->sale_inv }}</td>
            </tr>
            <tr>
                <td class="theads">Jobber:</td>
                <td class="theads_ans">{{ $invoice->Jobber }}</td>
                <td class="theads">Payment Method:</td>
                <td class="theads_ans">{{ $invoice->payment_method }}</td>
            </tr>
        </table>
        <br/>
        <table>
            <thead>
                <tr>
                    <th>S.no</th>
                    <th colspan="2">Description</th>
                    <th>Qty</th>
                </tr>
            </thead>
            <tbody>
                @php $serial = 0; @endphp
                @foreach($parts as $part)
                    @php $serial++; @endphp
                    <tr>
                        <td align="center">{{ $serial }}</td>
                        <td colspan="2">{{ $part->Description }}</td>
                        <td align="center">{{ $part->quantity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br/>
        <table width="100%">
            <tr>
                <td align="center"><br/>Parts Manager</td>
                <td align="center"><br/></td>
                <td align="center"><br/>Issued By({{ $invoice->user }})</td>
            </tr>
            <tr>
                <td align="center">_____________________</td>
                <td align="center"></td>
                <td align="center">_____________________</td>
            </tr>
        </table>
    </div>
</body>
</html>