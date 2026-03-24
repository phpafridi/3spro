<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function serviceJobcard()
    {
        return view('service.jobcard.dashboard');
    }

    public function serviceSM()
    {
        return redirect()->route('sm.index');
    }

    public function partsEntry()
    {
        return redirect()->route('parts.index');
    }



    public function serviceJC()
    {
        return view('service.jc.index');
    }

    public function financeCashier()
    {
        return redirect()->route('cashier.index');
    }

    public function financeAccounts()
    {
        return redirect()->route('accounts.index');
    }

    public function financeRecovery()
    {
        return redirect()->route('recovery.index');
    }

    public function crCRO()
    {
        return view('placeholders.coming-soon', ['module' => 'CR / CRO']);
    }

    public function tsureAdmin()
    {
        return view('placeholders.coming-soon', ['module' => 'T-Sure Admin']);
    }

    public function itManager()
    {
        return view('it-manager.dashboard');
    }
}
