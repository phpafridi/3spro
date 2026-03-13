<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>All Types Invoice Report</title>
    <style>
        @page {
            size: A4;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 2cm;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header img {
            width: 100%;
            height: 3cm;
        }
        h2 {
            text-align: center;
            margin: 20px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background-color: dimgray;
            color: white;
            font-size: 17px;
            padding: 8px;
            border: 1px solid #000;
        }
        td {
            padding: 6px;
            border: 1px solid #000;
            text-align: center;
        }
        .date-range {
            text-align: center;
            font-size: 15px;
            text-decoration: underline;
            margin: 10px 0;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .total-row {
            background-color: dimgray;
            color: white;
            font-size: 19px;
            font-weight: bold;
        }
        .total-row td {
            color: white;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <img src="{{ asset('images/header1.png') }}" alt="Header">
    </div>

    <h2>All Types Invoices Report</h2>

    <div class="date-range">
        {{ $fromFormatted }} To {{ $toFormatted }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Invoice Type</th>
                <th>Counted</th>
                <th>Labor</th>
                <th>Parts</th>
                <th>Consumble</th>
                <th>Sublet</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $serial = 0;
                $totalLabor = 0;
                $totalParts = 0;
                $totalSublet = 0;
                $totalConsumble = 0;
                $totalDiscount = 0;
                $totalTax = 0;
                $totalGrand = 0;
                $totalCount = 0;
            @endphp

            @foreach($reports as $report)
                @php
                    $serial++;
                    $totalLabor += $report->total_L;
                    $totalParts += $report->total_P;
                    $totalSublet += $report->total_S;
                    $totalConsumble += $report->total_C;
                    $totalDiscount += $report->discount;
                    $totalTax += $report->tax;
                    $totalGrand += $report->total_total;
                    $totalCount += $report->total_type;
                @endphp
                <tr>
                    <td>{{ $serial }}</td>
                    <td style="font-size:15px;">{{ $report->type }}</td>
                    <td>{{ number_format($report->total_type) }}</td>
                    <td>{{ number_format($report->total_L) }}</td>
                    <td>{{ number_format($report->total_P) }}</td>
                    <td>{{ number_format($report->total_C) }}</td>
                    <td>{{ number_format($report->total_S) }}</td>
                    <td>{{ number_format($report->discount) }}</td>
                    <td>{{ number_format($report->tax) }}</td>
                    <td>{{ number_format($report->total_total) }}</td>
                </tr>
            @endforeach

            <tr style="height: 20px;">
                <td colspan="10" style="border-top: 2px solid #000;"></td>
            </tr>

            <tr class="total-row">
                <td colspan="2"></td>
                <td>{{ number_format($totalCount) }}</td>
                <td>{{ number_format($totalLabor) }}</td>
                <td>{{ number_format($totalParts) }}</td>
                <td>{{ number_format($totalConsumble) }}</td>
                <td>{{ number_format($totalSublet) }}</td>
                <td>{{ number_format($totalDiscount) }}</td>
                <td>{{ number_format($totalTax) }}</td>
                <td>{{ number_format($totalGrand) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right;">
        <p>Generated on: {{ date('d-M-Y H:i:s') }}</p>
    </div>
</body>
</html>
