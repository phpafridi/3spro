<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function serviceJobcard()
    {
        return view('service.jobcard.dashboard');
    }

    public function serviceSM()
    {
        return view('service.sm.dashboard');
    }

    public function partsEntry()
    {
        return view('parts.entry.index');
    }

    public function serviceBPJC()
    {
        return view('service.bp-jc.dashboard');
    }

    public function serviceJC()
    {
        return view('service.jc.index');
    }

    public function financeCashier()
    {
        return view('cashier.index');
    }

    public function financeAccounts()
    {
        return view('finance.accounts.dashboard');
    }

    public function financeRecovery()
    {
        return view('finance.recovery.dashboard');
    }

    public function crCRO()
    {
        return view('cr.cro.dashboard');
    }

    public function tsureAdmin()
    {
        return view('t-sure.admin.dashboard');
    }

    public function itManager()
    {
        return view('it-manager.dashboard');
    }
}
