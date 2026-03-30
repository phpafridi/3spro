<?php

/**
 * Company / Dealership Identity
 *
 * All values are driven from .env so you can change them without
 * touching code.  Access anywhere in PHP via  config('company.name')
 * and in Blade via  {{ config('company.name') }}
 */

return [

    'name'      => env('COMPANY_NAME', '3SPRO'),
    'phone'     => env('COMPANY_PHONE', '+92-91-1234567'),
    'location'  => env('COMPANY_LOCATION', 'Peshawar, KPK'),
    'logo_path' => env('COMPANY_LOGO_PATH', 'images/logo.png'),

];
