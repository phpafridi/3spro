<?php
namespace App\Http\Controllers\ITManager;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user       = Auth::user();
        $login_time = session('login_waqat');
        if (!$login_time || !is_numeric($login_time)) $login_time = time();

        return view('it-manager.dashboard', [
            'user_name'  => $user->Name,
            'position'   => $user->position,
            'dept'       => $user->dept,
            'login_time' => $login_time,
            'menu_items' => $this->getMenuItems(),
        ]);
    }

    private function getMenuItems()
    {
        return [
            ['title'=>'Service Manager',   'url'=>route('sm.index'),       'icon'=>'services', 'description'=>'Manage service operations, team, and workflow'],
            ['title'=>'Sales / CRM',        'url'=>route('sales.index'),    'icon'=>'sales',    'description'=>'Sales dashboard, customer relations, VIN check, campaigns'],
            ['title'=>'Finance Manager',    'url'=>route('accountant.index'),  'icon'=>'finance',  'description'=>'Financial reports, invoices, and accounting'],
            ['title'=>'Job Controller',     'url'=>route('jc.dashboard'),   'icon'=>'job',      'description'=>'Monitor and control job cards'],
            ['title'=>'Service Advisor',    'url'=>route('jobcard.index'),  'icon'=>'advisor',  'description'=>'Customer service and job card management'],
            ['title'=>'Parts Manager',      'url'=>route('parts.index'),    'icon'=>'parts',    'description'=>'Inventory, parts management and ordering'],
            ['title'=>'Finance Ledger',     'url'=>route('accounts.index'), 'icon'=>'book',       'description'=>'Finance Ledger'],
            ['title'=>'Recovery Executive', 'url'=>route('recovery.index'),'icon'=>'recovery','description'=>'Debt collection and recovery'],
            ['title'=>'Cashier',            'url'=>route('cashier.index'),  'icon'=>'cashier',  'description'=>'Cashier operations and payments'],
            ['title'=>'Sales Vehicle',      'url'=>route('sv.index'),       'icon'=>'car',      'description'=>'New car inventory, delivery orders & sold vehicle search'],
        ];
    }
}
