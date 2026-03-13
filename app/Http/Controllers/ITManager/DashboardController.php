<?php
// app/Http/Controllers/ITManager/DashboardController.php
namespace App\Http\Controllers\ITManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get login time from session or use current time as fallback
        $login_time = session('login_waqat');

        // If login_time is not set or not numeric, use a default
        if (!$login_time || !is_numeric($login_time)) {
            $login_time = time(); // Fallback to current time
        }

        $data = [
            'user_name' => $user->Name,
            'position' => $user->position,
            'dept' => $user->dept,
            'login_time' => $login_time,
            'menu_items' => $this->getMenuItems()
        ];

        return view('it-manager.dashboard', $data);
    }

    private function getMenuItems()
    {
        return [
            [
                'title' => 'Service Manager',
                'url' => route('sm.index'),
                'icon' => 'services',
                'description' => 'Manage service operations, team, and workflow'
            ],
            [
                'title' => 'Finance Manager',
                'url' => route('cashier.index'),
                'icon' => 'finance',
                'description' => 'Financial reports, invoices, and accounting'
            ],
            [
                'title' => 'Job Controller',
                'url' => route('jc.dashboard'),
                'icon' => 'job',
                'description' => 'Monitor and control job cards'
            ],
            [
                'title' => 'Service Advisor',
                'url' => route('jobcard.index'),
                'icon' => 'advisor',
                'description' => 'Customer service and job card management'
            ],
            [
                'title' => 'Parts Manager',
                'url' => route('parts.index'),
                'icon' => 'parts',
                'description' => 'Inventory, parts management and ordering'
            ],
            [
                'title' => 'CR Manager',
                'url' => route('cr.cro'),
                'icon' => 'cr',
                'description' => 'Customer relations and followups'
            ],
            [
                'title' => 'Recovery Executive',
                'url' => route('finance.recovery'),
                'icon' => 'recovery',
                'description' => 'Debt collection and recovery'
            ],
            [
                'title' => 'Cashier',
                'url' => route('cashier.index'),
                'icon' => 'cashier',
                'description' => 'Cashier operations and payments'
            ],
            [
                'title' => 'Body & Paint JC',
                'url' => route('bp-jc.index'),
                'icon' => 'job',
                'description' => 'Body and paint job controller'
            ],
        ];
    }
}
