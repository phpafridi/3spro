<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Payment Receipt #{{ $payment->payment_id }}</title>
<style>body{font-family:Arial,sans-serif;font-size:13px;margin:20px}.box{border:1px solid #ccc;padding:20px;max-width:500px;margin:0 auto}.row{display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #eee}.bold{font-weight:bold}@media print{button{display:none}}</style>
</head><body>
<div class="box">
@include('partials.company-header')
<h2 style="text-align:center">Payment Receipt</h2>
<p style="text-align:center">#{{ $payment->payment_id }}</p>
<div class="row"><span>Jobber:</span><span class="bold">{{ $payment->jobber->jbr_name ?? $payment->jobber }}</span></div>
<div class="row"><span>Transaction Type:</span><span class="bold">{{ $payment->trans_type }}</span></div>
<div class="row"><span>Amount:</span><span class="bold">{{ number_format($payment->amount, 2) }}</span></div>
<div class="row"><span>Payment Method:</span><span>{{ $payment->payment_method }}</span></div>
<div class="row"><span>Received/Paid By:</span><span>{{ $payment->rec_paid_by }}</span></div>
<div class="row"><span>Remarks:</span><span>{{ $payment->remarks ?? '-' }}</span></div>
<div class="row"><span>Date:</span><span>{{ $payment->created_at ? \Carbon\Carbon::parse($payment->created_at)->format('d M Y h:i A') : '-' }}</span></div>
<div class="row"><span>User:</span><span>{{ $payment->user }}</span></div>
</div>
<br><button onclick="window.print()">Print</button>
</body></html>
