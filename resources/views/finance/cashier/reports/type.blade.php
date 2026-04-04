<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $type }} Report</title>
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
            text-transform: uppercase;
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
            padding: 4px;
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
            background-color: #E9E9E8;
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
        .text-left {
            text-align: left;
        }
    </style>
</head>
<body onload="window.print()">
<div id="company-header" style="text-align:center;margin-bottom:12px;font-family:Arial,sans-serif;border-bottom:2px solid #555;padding-bottom:8px;">
    @php $logoPath = public_path(config('company.logo_path')); @endphp
    @if(file_exists($logoPath))
        <img src="{{ asset(config('company.logo_path')) }}" alt="{{ config('company.name') }}" style="height:52px;display:block;margin:0 auto 4px;" onerror="this.style.display='none'">
    @endif
    <div style="font-size:18px;font-weight:bold;letter-spacing:2px;text-transform:uppercase;">{{ config('company.name') }}</div>
    <div style="font-size:10px;color:#444;margin-top:2px;">{{ config('company.location') }} &nbsp;|&nbsp; {{ config('company.phone') }}</div>
</div>

    <div class="header">
        <img src="{{ asset('images/header1.png') }}" alt="Header">
    </div>

    <h2>{{ $type }} Report</h2>

    <div class="date-range">
        {{ $fromFormatted }} To {{ $toFormatted }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Sr#</th>
                <th>Invoice#</th>
                <th>Date</th>
                <th>RO#</th>
                <th>Customer</th>
                <th>Labor</th>
                <th>Parts</th>
                <th>Consumble</th>
                <th>Sublet</th>
                <th>Discount</th>
                <th>Tax</th>
                <th>Balance</th>
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
            @endphp

            @foreach($reports as $report)
                @php
                    $serial++;
                    $totalLabor += $report->Lnet;
                    $totalParts += $report->Pnet;
                    $totalSublet += $report->Snet;
                    $totalConsumble += $report->Cnet;
                    $totalDiscount += $report->discount;
                    $totalTax += $report->tax;
                    $totalGrand += $report->Total;
                @endphp
                <tr>
                    <td>{{ $serial }}</td>
                    <td style="font-size:10px;">{{ $report->type }}-{{ $report->Invoice_id }}</td>
                    <td style="font-size:10px;">{{ $report->DATE }}</td>
                    <td>{{ $report->Jobc_id }}</td>
                    <td style="font-size:10px;" class="text-left">{{ $report->Customer_name }}</td>
                    <td>{{ number_format($report->Lnet) }}</td>
                    <td>{{ number_format($report->Pnet) }}</td>
                    <td>{{ number_format($report->Cnet) }}</td>
                    <td>{{ number_format($report->Snet) }}</td>
                    <td>{{ number_format($report->discount) }}</td>
                    <td>{{ number_format($report->tax) }}</td>
                    <td>{{ number_format($report->Total) }}</td>
                </tr>
            @endforeach

            <tr style="height: 20px;">
                <td colspan="12" style="border-top: 2px solid #000;"></td>
            </tr>

            <tr class="total-row">
                <td colspan="5"></td>
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

    <div class="page-counter" style="text-align: center; margin-top: 20px;">
        Page <span class="page"></span> of <span class="topage"></span>
    </div>
</body>
</html>
