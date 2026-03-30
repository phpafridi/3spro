<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $invoice->Invoice_id }}</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            width: 21cm;
            margin: 0 auto;
            padding: 1cm;
        }
        .header {
            width: 100%;
            height: 3cm;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        .invoice-title {
            text-align: center;
            text-decoration: underline;
            font-size: 24px;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        td, th {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .thead {
            background-color: #808080;
            color: white;
            font-weight: bold;
        }
        .subhead {
            background-color: #ccc;
            font-weight: bold;
        }
        .total-row {
            background-color: #808080;
            color: white;
            font-weight: bold;
        }
        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid black;
            margin-top: 5px;
        }
        @media print {
            body { margin: 0; padding: 0.5cm; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;">
            <i class="fas fa-print"></i> Print
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            <i class="fas fa-times"></i> Close
        </button>
    </div>

    <div class="header">
        @include('partials.company-header')
    </div>

    <h2 class="invoice-title">Invoice</h2>

    <table>
        <tr>
            <td colspan="5"></td>
            <td class="subhead" colspan="2">Date & Time:</td>
            <td colspan="2">{{ \Carbon\Carbon::parse($invoice->datetime)->format('d-M-y g:i A') }}</td>
        </tr>
        <tr>
            <td colspan="5"></td>
            <td class="subhead" colspan="2">Invoice No:</td>
            <td colspan="2">{{ $invoice->type }}-{{ $invoice->Invoice_id }}</td>
        </tr>
        <tr>
            <td class="subhead" colspan="2">Customer Name:</td>
            <td class="subhead" colspan="2">{{ $customer->Customer_name }}</td>
            <td width="126"></td>
            <td class="subhead" colspan="2">Reg Number:</td>
            <td class="subhead" colspan="2">{{ $vehicle->Registration }} (Mileage: {{ $job->Mileage }})</td>
        </tr>
        <tr>
            <td class="subhead" colspan="2">Frame Number:</td>
            <td class="subhead" colspan="2">{{ $vehicle->Frame_no }}</td>
            <td width="126"></td>
            <td class="subhead" colspan="2">SA:</td>
            <td class="subhead" colspan="2">{{ $job->SA }}</td>
        </tr>
        <tr>
            <td class="subhead" colspan="2">Jobcard Number:</td>
            <td class="subhead" colspan="2">{{ $job->Jobc_id }}</td>
        </tr>
    </table>

    <!-- Labor -->
    <table>
        <tr>
            <td colspan="6" class="thead">Labor</td>
        </tr>
        @foreach($laborItems as $item)
        <tr>
            <td colspan="4">{{ $item->Labor }}</td>
            <td colspan="2">{{ number_format($item->cost) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4"></td>
            <td colspan="2" class="total-row">{{ number_format($invoice->Labor) }}</td>
        </tr>
    </table>

    <!-- Parts -->
    <table>
        <tr>
            <td colspan="6" class="thead">Parts</td>
        </tr>
        @foreach($partsItems as $item)
        <tr>
            <td colspan="3">{{ $item->part_description }}</td>
            <td>{{ $item->unitprice }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ number_format($item->total) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5"></td>
            <td class="total-row">{{ number_format($invoice->Parts) }}</td>
        </tr>
    </table>

    <!-- Sublet -->
    <table>
        <tr>
            <td colspan="6" class="thead">Sublet</td>
        </tr>
        @foreach($subletItems as $item)
        <tr>
            <td colspan="3">{{ $item->Sublet }}</td>
            <td>{{ $item->unitprice }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ number_format($item->total) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5"></td>
            <td class="total-row">{{ number_format($invoice->Sublet) }}</td>
        </tr>
    </table>

    <!-- Consumable -->
    <table>
        <tr>
            <td colspan="6" class="thead">Consumable</td>
        </tr>
        @foreach($consumableItems as $item)
        <tr>
            <td colspan="3">{{ $item->cons_description }}</td>
            <td>{{ $item->unitprice }}</td>
            <td>{{ $item->qty }}</td>
            <td>{{ number_format($item->total) }}</td>
        </tr>
        @endforeach
        <tr>
            <td colspan="5"></td>
            <td class="total-row">{{ number_format($invoice->Consumble) }}</td>
        </tr>
    </table>

    <!-- Totals -->
    <table>
        <tr>
            <td colspan="4"></td>
            <td class="subhead">Discount</td>
            <td class="subhead">{{ number_format($discount) }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="subhead">TAX</td>
            <td class="subhead">{{ number_format($tax) }}</td>
        </tr>
        <tr>
            <td colspan="4"></td>
            <td class="total-row">Total</td>
            <td class="total-row">Rs {{ number_format($invoice->Total) }}</td>
        </tr>
    </table>

    <p style="font-size: 13px; margin-top: 30px;">
        &#10033; I received the car along with all tools and accessories. The Repair have been performed to my satisfaction.
    </p>

    <div class="signature">
        <div>
            <div>________________________________</div>
            <div>Customer signature</div>
        </div>
        @if($invoice->careof)
        <div>
            <div>________________________________</div>
            <div>Careof : ({{ $invoice->careof }})</div>
        </div>
        @endif
        <div>
            <div>________________________________</div>
            <div>Cashier: {{ $invoice->cashier }}</div>
        </div>
    </div>
</body>
</html>
