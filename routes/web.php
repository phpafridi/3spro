<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController as MainDashboardController;
use App\Http\Controllers\ITManager\DashboardController as ITDashboardController;
use App\Http\Controllers\Service\JC\JobController;  // ADD THIS
use Illuminate\Support\Facades\Route;

// ==================== LOGIN ROUTES ====================
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('p_login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ==================== DEPARTMENT DASHBOARDS ====================

// Service Advisor
Route::middleware(['auth', 'role:SerAdvisor'])->group(function () {
    Route::get('/service/jobcard', [MainDashboardController::class, 'serviceJobcard'])->name('service.jobcard');
});

// Service Manager
Route::middleware(['auth', 'role:SManager,IMCc'])->group(function () {
    Route::get('/service/sm', [MainDashboardController::class, 'serviceSM'])->name('service.sm');
});

// Parts Manager / Data Operator
Route::middleware(['auth', 'role:PManager,DataOperator'])->group(function () {
    Route::get('/parts/entry', [MainDashboardController::class, 'partsEntry'])->name('parts.entry');
});

// Body & Paint Job Controller
Route::middleware(['auth', 'role:body_PaintJC'])->group(function () {
    Route::get('/service/bp-jc', [MainDashboardController::class, 'serviceBPJC'])->name('service.bp-jc');
});

// Job Controller - MAIN DASHBOARD (keep this for backward compatibility)
Route::middleware(['auth', 'role:JobController'])->group(function () {
   Route::get('/service/jc', [JobController::class, 'index'])->name('service.jc');
});

// Cashier
Route::middleware(['auth', 'role:Cashier'])->group(function () {
    Route::get('/finance/cashier', [MainDashboardController::class, 'financeCashier'])->name('finance.cashier');
});

// Finance Manager / Accountant
Route::middleware(['auth', 'role:FManager,Accountant'])->group(function () {
    Route::get('/finance/accounts', [MainDashboardController::class, 'financeAccounts'])->name('finance.accounts');
});

// Recovery Executive
Route::middleware(['auth', 'role:RecoveryExec'])->group(function () {
    Route::get('/finance/recovery', [MainDashboardController::class, 'financeRecovery'])->name('finance.recovery');
});

// CRO / CR Manager
Route::middleware(['auth', 'role:CRO,CRManager'])->group(function () {
    Route::get('/cr/cro', [MainDashboardController::class, 'crCRO'])->name('cr.cro');
});

// T-Sure Admin
Route::middleware(['auth', 'role:Tsure'])->group(function () {
    Route::get('/t-sure/admin', [MainDashboardController::class, 'tsureAdmin'])->name('tsure.admin');
});

// IT Manager
Route::middleware(['auth', 'role:IT Manager'])->group(function () {
    Route::get('/it-manager', [ITDashboardController::class, 'index'])->name('it.manager');
});

// ==================== JOB CONTROLLER DETAIL ROUTES ====================
Route::middleware(['auth', 'role:JobController'])->prefix('service/jc')->name('jc.')->group(function () {
    // Main pages
    Route::get('/dashboard', [JobController::class, 'index'])->name('dashboard');
    Route::get('/sublet', [JobController::class, 'sublet'])->name('sublet');
    Route::get('/inprogress', [JobController::class, 'inprogress'])->name('inprogress');
    Route::get('/parts-status', [JobController::class, 'partsStatus'])->name('parts-status');

    // Job assignment
    Route::get('/assign/{laborId}', [JobController::class, 'showAssignForm'])->name('assign');
    Route::post('/assign', [JobController::class, 'assignJob'])->name('assign.process');
    Route::post('/job-done', [JobController::class, 'jobDone'])->name('job-done');

    // Sublet assignment
    Route::get('/sublet-assign/{subletId}', [JobController::class, 'showSubletAssignForm'])->name('sublet-assign');
    Route::post('/sublet-assign', [JobController::class, 'assignSublet'])->name('sublet-assign.process');
    Route::get('/sublet-done/{subletId}', [JobController::class, 'showSubletDoneForm'])->name('sublet-done');
    Route::post('/sublet-done', [JobController::class, 'subletDone'])->name('sublet-done.process');

    // AJAX
    Route::post('/team-members', [JobController::class, 'getTeamMembers'])->name('team-members');
});
