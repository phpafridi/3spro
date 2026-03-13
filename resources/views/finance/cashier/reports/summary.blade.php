<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Service Business Summary</title>
    <style>
        @page {
            size: A4 landscape;
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
        p {
            text-align: center;
            font-size: 16px;
            margin: 10px 0;
        }
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
        }
        th {
            background-color: gray;
            color: white;
            font-size: 18px;
            padding: 10px;
            border: 1px solid #000;
        }
        td {
            padding: 8px;
            border: 1px solid #000;
            text-align: center;
            font-size: 20px;
        }
        tr:nth-child(even) {
            background-color: #E3DEDE;
        }
        tr:nth-child(odd) {
            background-color: #FFF;
        }
        .total-row {
            background-color: gray;
            color: white;
            font-size: 23px;
            font-weight: bold;
        }
        .total-row td {
            color: white;
        }
        .bn {
            border-style: outset;
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <img src="{{ asset('images/header1.png') }}" alt="Header">
    </div>

    <h2>Service Business Summary</h2>

    <p>{{ $fromFormatted }} To {{ $toFormatted }}</p>

    <table>
        <thead>
            <tr>
                <th>RO Type</th>
                <th>Closed ROs</th>
                <th>Labor</th>
                <th>Parts</th>
                <th>Sublet</th>
                <th>Consumble</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalROs = 0;
                $totalLabor = 0;
                $totalParts = 0;
                $totalSublet = 0;
                $totalConsumble = 0;
                $totalAll = 0;
            @endphp

            @foreach($summary as $item)
                @php
                    $rowTotal = $item->Labor + $item->PARTS + $item->SUBLET + $item->CONSUMBLE;
                    $totalROs += $item->ROs;
                    $totalLabor += $item->Labor;
                    $totalParts += $item->PARTS;
                    $totalSublet += $item->SUBLET;
                    $totalConsumble += $item->CONSUMBLE;
                    $totalAll += $rowTotal;
                @endphp
                <tr>
                    <td>{{ $item->RO_type }}</td>
                    <td>{{ number_format($item->ROs) }}</td>
                    <td>{{ number_format($item->Labor) }}</td>
                    <td>{{ number_format($item->PARTS) }}</td>
                    <td>{{ number_format($item->SUBLET) }}</td>
                    <td>{{ number_format($item->CONSUMBLE) }}</td>
                    <td>{{ number_format($rowTotal) }}</td>
                </tr>
            @endforeach

            <tr class="total-row">
                <td>Total</td>
                <td class="bn">{{ number_format($totalROs) }}</td>
                <td class="bn">{{ number_format($totalLabor) }}</td>
                <td class="bn">{{ number_format($totalParts) }}</td>
                <td class="bn">{{ number_format($totalSublet) }}</td>
                <td class="bn">{{ number_format($totalConsumble) }}</td>
                <td class="bn">{{ number_format($totalAll) }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 30px; text-align: right;">
        <p>Generated on: {{ date('d-M-Y H:i:s') }}</p>
    </div>
</body>
</html>
