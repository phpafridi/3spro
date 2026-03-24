<div class="no-print" style="background:#fff;border:1px solid #e5e7eb;border-radius:6px;padding:14px;margin-bottom:16px;">
    <form method="GET" style="display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;">
        @if(!isset($hideDates) || !$hideDates)
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6b7280;margin-bottom:4px;">From</label>
            <input type="date" name="from" value="{{ $from ?? now()->startOfMonth()->toDateString() }}"
                   style="border:1px solid #d1d5db;border-radius:4px;padding:6px 10px;font-size:13px;">
        </div>
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6b7280;margin-bottom:4px;">To</label>
            <input type="date" name="to" value="{{ $to ?? now()->toDateString() }}"
                   style="border:1px solid #d1d5db;border-radius:4px;padding:6px 10px;font-size:13px;">
        </div>
        @endif
        @if(isset($showYear) && $showYear)
        <div>
            <label style="display:block;font-size:11px;font-weight:600;color:#6b7280;margin-bottom:4px;">Year</label>
            <select name="year" style="border:1px solid #d1d5db;border-radius:4px;padding:6px 10px;font-size:13px;">
                @for($y = now()->year; $y >= 2018; $y--)
                <option value="{{ $y }}" {{ ($year??now()->year)==$y ? 'selected':'' }}>{{ $y }}</option>
                @endfor
            </select>
        </div>
        @endif
        <button type="submit"
                style="padding:7px 18px;background:#dc2626;color:#fff;border:none;border-radius:4px;font-size:13px;font-weight:600;cursor:pointer;"
                onmouseover="this.style.background='#b91c1c'" onmouseout="this.style.background='#dc2626'">
            Generate
        </button>
        <a href="{{ route('sm.reports') }}"
           style="padding:7px 14px;background:#991b1b;color:#fff;text-decoration:none;border-radius:4px;font-size:13px;font-weight:600;">
            ← Reports
        </a>
        <button type="button" onclick="window.print()"
                style="padding:7px 14px;background:#374151;color:#fff;border:none;border-radius:4px;font-size:13px;cursor:pointer;">
            Print
        </button>
    </form>
</div>
@push('styles')
<style>@media print { .no-print { display: none !important; } body { font-size: 11px; } }</style>
@endpush
