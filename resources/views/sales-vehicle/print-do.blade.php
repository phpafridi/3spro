<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NVD — {{ $do->do_no }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            width: 21cm;
            margin: 0 auto;
            padding: 0.6cm 1cm;
            color: #000;
        }

        /* ── No-print toolbar ── */
        .no-print {
            text-align: center;
            margin-bottom: 14px;
        }
        .no-print button {
            padding: 8px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 13px;
            margin: 0 4px;
        }
        .btn-print { background: #22c55e; color: #fff; }
        .btn-close  { background: #ef4444; color: #fff; }

        /* ── Company header ── */
        .company-header {
            text-align: center;
            margin-bottom: 6px;
        }
        .company-header img {
            height: 60px;
            display: block;
            margin: 0 auto 4px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            letter-spacing: 2px;
        }
        .company-sub {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        /* ── Document title ── */
        .doc-title {
            display: block;
            background: #000;
            color: #fff;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            letter-spacing: 2px;
            padding: 5px 0;
            margin-bottom: 8px;
        }

        /* ── Generic row helpers ── */
        .row { display: flex; gap: 6px; margin-bottom: 5px; align-items: center; }
        .label { font-weight: bold; white-space: nowrap; }
        .val {
            border-bottom: 1px solid #000;
            flex: 1;
            min-width: 40px;
            padding-bottom: 1px;
        }
        .val-sm { width: 80px; flex: none; }
        .val-md { width: 130px; flex: none; }

        /* ── Section heading ── */
        .section-head {
            background: #000;
            color: #fff;
            font-weight: bold;
            font-size: 11px;
            padding: 3px 6px;
            margin: 8px 0 6px;
            letter-spacing: 1px;
        }

        /* ── Deferred payment note ── */
        .deferred {
            color: red;
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
        }

        /* ── Two-column checklist layout ── */
        .checklist-wrap {
            display: flex;
            gap: 14px;
            margin-top: 8px;
        }
        .checklist-box {
            flex: 1;
            border: 1px solid #888;
        }
        .checklist-box .ch-title {
            background: #000;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            padding: 3px 0;
            letter-spacing: 1px;
        }
        .checklist-box .ch-sub {
            background: #000;
            color: #fff;
            font-weight: bold;
            font-size: 10px;
            padding: 2px 5px;
        }
        .checklist-box .ch-item {
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 3px 6px;
            border-top: 1px solid #ddd;
            font-size: 10.5px;
        }
        .checklist-box .ch-item input[type=checkbox] {
            width: 12px;
            height: 12px;
            accent-color: #000;
        }

        /* ── Remarks ── */
        .remarks-box {
            border: 1px solid #999;
            min-height: 36px;
            margin-top: 10px;
            padding: 4px 6px;
            font-size: 10.5px;
        }
        .remarks-label {
            font-weight: bold;
            margin-top: 8px;
            margin-bottom: 2px;
        }

        /* ── Footer notice ── */
        .footer-note {
            font-size: 10px;
            text-align: center;
            margin-top: 8px;
            color: #333;
        }

        /* ── Signature row ── */
        .sig-row {
            display: flex;
            justify-content: space-between;
            margin-top: 22px;
        }
        .sig-item {
            text-align: center;
            width: 18%;
        }
        .sig-line {
            border-top: 1px solid #000;
            margin-bottom: 3px;
        }
        .sig-label { font-size: 10px; }

        @media print {
            body { margin: 0; padding: 0.3cm 0.8cm; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body onload="window.print()">

    {{-- ── Toolbar (screen only) ──────────────────────────────── --}}
    <div class="no-print">
        <button class="btn-print" onclick="window.print()">🖨 Print</button>
        <button class="btn-close"  onclick="window.close()">✕ Close</button>
    </div>

    {{-- ── Company Header ──────────────────────────────────────── --}}
    <div class="company-header">
        @php $logo = public_path(config('company.logo_path')); @endphp
        @if(file_exists($logo))
            <img src="{{ asset(config('company.logo_path')) }}" alt="{{ config('company.name') }}">
        @else
            <div class="company-name">{{ config('company.name') }}</div>
        @endif
        <div class="company-sub">
            {{ config('company.location') }} &nbsp;|&nbsp; {{ config('company.phone') }}
        </div>
    </div>

    <span class="doc-title">VEHICLE DELIVERY &amp; ACCEPTANCE NOTE</span>

    {{-- ── PBO / Type / Sale Price / Date ──────────────────────── --}}
    <div class="row">
        <span class="label">PBO No. :</span>
        <span class="val val-md">{{ $do->pbo_no }}</span>

        <span class="label">Type :</span>
        <span class="val val-md">{{ $do->customer_type }}</span>

        <span class="label">Sale Price :</span>
        <span class="val val-md">{{ $do->sale_price ? 'PKR ' . number_format($do->sale_price) : '' }}</span>

        <span class="label">Date :</span>
        <span class="val val-md">{{ $do->do_date?->format('d/m/Y') }}</span>
    </div>

    {{-- ── Customer Name / Address ─────────────────────────────── --}}
    <div class="row">
        <span class="label">Customer Name :</span>
        <span class="val">{{ $do->customer_name }}</span>
        <span class="label">Address :</span>
        <span class="val">{{ $do->customer_address }}</span>
    </div>

    {{-- ── NIC / Mobile / S/o W/o ──────────────────────────────── --}}
    <div class="row">
        <span class="label">NIC No. :</span>
        <span class="val val-md">{{ $do->customer_cnic }}</span>

        <span class="label">Mobile :</span>
        <span class="val val-md">{{ $do->customer_phone }}</span>

        <span class="label">S/o  W/o :</span>
        <span class="val">{{ $do->customer_son_wife_of }}</span>
    </div>

    {{-- ── Deferred payment note (show if finance) ─────────────── --}}
    @if(in_array($do->payment_type, ['Installment', 'Direct']))
    <div class="deferred">
        DEFERRED PAYMENT?
        <input type="checkbox" checked disabled style="accent-color:#c00; width:12px; height:12px;">
        Yes
        @if($do->payment_type === 'Installment')
            &nbsp; Bank: {{ $do->bank_name }}
            &nbsp; Down: PKR {{ number_format($do->down_payment) }}
            &nbsp; EMI: PKR {{ number_format($do->monthly_installment) }} × {{ $do->tenure_months }} mo
        @else
            &nbsp; Down: PKR {{ number_format($do->down_payment) }}
            &nbsp; Balance: PKR {{ number_format($do->loan_amount) }}
            &nbsp; {{ $do->tenure_months }} instalments
        @endif
    </div>
    @endif

    {{-- ═══ VEHICLE DETAILS ═══════════════════════════════════ --}}
    <div class="section-head">VEHICLE DETAILS</div>

    <div class="row">
        <span class="label">Variant :</span>
        <span class="val">{{ $do->vehicle?->variant }}</span>

        <span class="label">Model :</span>
        <span class="val val-sm">{{ $do->vehicle?->model_year ?? $do->vehicle?->model }}</span>

        <span class="label">Color :</span>
        <span class="val">{{ $do->vehicle?->color }}</span>
    </div>

    <div class="row">
        <span class="label">Engine # :</span>
        <span class="val">{{ $do->vehicle?->engine_no }}</span>

        <span class="label">Chassis # :</span>
        <span class="val">{{ $do->vehicle?->vin }}</span>

        <span class="label">Registration # :</span>
        <span class="val">{{ $do->vehicle?->registration_no ?? 'NEW' }}</span>
    </div>

    <div class="row">
        <span class="label">Invoice # :</span>
        <span class="val val-md">{{ $do->do_no }}</span>
    </div>

    {{-- ═══ VEHICLE RECEIVER ══════════════════════════════════ --}}
    <div class="section-head">VEHICLE RECEIVER Information</div>

    <div class="row">
        <span class="label">Receiver Name :</span>
        <span class="val">{{ $do->receiver_name ?? $do->customer_name }}</span>

        <span class="label">Father Name :</span>
        <span class="val">{{ $do->receiver_father_name }}</span>

        <span class="label">NIC # :</span>
        <span class="val val-md">{{ $do->receiver_cnic ?? $do->customer_cnic }}</span>
    </div>

    <div class="row">
        <span class="label">Mobile :</span>
        <span class="val val-md">{{ $do->receiver_phone ?? $do->customer_phone }}</span>

        <span class="label">Address :</span>
        <span class="val">{{ $do->receiver_address ?? $do->customer_address }}</span>
    </div>

    {{-- ═══ ACCESSORIES + NVD CHECKLIST (side by side) ═════ --}}
    <div class="checklist-wrap">

        {{-- Left: Accessories + Documents --}}
        <div class="checklist-box" style="max-width: 48%;">
            <div class="ch-title">ACCESSORIES</div>

            <div class="ch-item">
                <input type="checkbox" {{ $do->acc_remote_control ? 'checked' : '' }} disabled>
                Keys {{ $do->acc_keys_qty }} / Remote Control
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->acc_toolkit_jack ? 'checked' : '' }} disabled>
                ToolKit/Jack with Handle
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->acc_spare_wheel ? 'checked' : '' }} disabled>
                Spare Wheel/Rear TrunkMat
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->acc_battery_warranty ? 'checked' : '' }} disabled>
                Battery/Warranty Card/Cassette/Player
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->acc_service_warranty ? 'checked' : '' }} disabled>
                Service Warranty/Floor Mat
            </div>

            <div class="ch-sub">DOCUMENTS</div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->doc_sales_invoice ? 'checked' : '' }} disabled>
                Sales Invoice
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->doc_sales_certificate ? 'checked' : '' }} disabled>
                Sales Certificate
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->doc_sales_cert_verification ? 'checked' : '' }} disabled>
                Sales Certificate Verification Copy
            </div>
        </div>

        {{-- Right: NVD Checklist --}}
        <div class="checklist-box" style="flex: 1;">
            <div class="ch-title">NVD Checklist Sheet</div>

            <div class="ch-item">
                <input type="checkbox" {{ $do->nvd_warranty_terms ? 'checked' : '' }} disabled>
                Explained Warranty Terms &amp; Conditions
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->nvd_owners_manual ? 'checked' : '' }} disabled>
                Explained Owner's Manual
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->nvd_ffs_pm_schedule ? 'checked' : '' }} disabled>
                Explained FFS &amp; PM Schedule
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->nvd_3s_visit ? 'checked' : '' }} disabled>
                Conducted 3S Visit
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->nvd_ew_ppm ? 'checked' : '' }} disabled>
                Explained EW &amp; PPM
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->nvd_safety_features ? 'checked' : '' }} disabled>
                Explained Safety Features
            </div>
            <div class="ch-item">
                <input type="checkbox" {{ $do->nvd_demonstrated_ops ? 'checked' : '' }} disabled>
                Demonstrated Operations of Features
            </div>
        </div>
    </div>

    {{-- ── Remarks ──────────────────────────────────────────────── --}}
    <div class="remarks-label">Remarks:</div>
    <div class="remarks-box">{{ $do->remarks }}</div>

    {{-- ── Footer notice ───────────────────────────────────────── --}}
    <div class="footer-note">
        From today, {{ config('company.name') }} will not be responsible for any loss/damage to the vehicle
        because of theft, accident, misuse and natural climate etc.
    </div>

    {{-- ── Satisfaction statement ─────────────────────────────── --}}
    <p style="font-size: 10.5px; margin-top: 10px;">
        I have seen, inspected, approved and then taken delivery of MG vehicle, from
        <strong>{{ config('company.name') }}</strong> today in good condition
        and in working order with standard tools and equipment, <strong>to my satisfaction.</strong>
    </p>

    <div style="margin-top: 4px; font-size: 10.5px;">
        Customer Comments: <span style="display:inline-block; border-bottom:1px solid #000; width: 280px;">&nbsp;</span>
    </div>

    {{-- ── Signature row ──────────────────────────────────────── --}}
    <div class="sig-row">
        <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Sale Manager</div>
        </div>
        <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Service Manager</div>
        </div>
        <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Parts Manager</div>
        </div>
        <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">CR Manager</div>
        </div>
        <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Sales Executive Signature</div>
        </div>
        <div class="sig-item">
            <div class="sig-line"></div>
            <div class="sig-label">Customer Signature</div>
        </div>
    </div>

</body>
</html>
