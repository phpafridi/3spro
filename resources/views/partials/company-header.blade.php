{{--
    Reusable company header for ALL print / report views.
    Usage:  @include('partials.company-header')

    Renders:
      - Logo (from config('company.logo_path'), falls back to text)
      - Company name
      - Location and phone from .env / config('company.*')
--}}
<div style="text-align:center; margin-bottom:8px; font-family:Arial,sans-serif;">
    @php
        $logoPath = public_path(config('company.logo_path'));
        $logoUrl  = asset(config('company.logo_path'));
    @endphp

    @if(file_exists($logoPath))
        <img src="{{ $logoUrl }}"
             alt="{{ config('company.name') }}"
             style="height:56px; display:block; margin:0 auto 4px;"
             onerror="this.style.display='none'">
    @endif

    <div style="font-size:18px; font-weight:bold; letter-spacing:2px; text-transform:uppercase;">
        {{ config('company.name') }}
    </div>
    <div style="font-size:10px; color:#444; margin-top:2px;">
        {{ config('company.location') }}
        &nbsp;|&nbsp;
        {{ config('company.phone') }}
    </div>
</div>
