<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController as MainDashboardController;
use App\Http\Controllers\ITManager\DashboardController as ITDashboardController;
use App\Http\Controllers\Service\JC\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Finance\CashierController;
use App\Http\Controllers\Finance\Cashier\ReportsController;
use App\Http\Controllers\Parts\PartsController;

use App\Http\Controllers\Service\Jobcard\JobcardController;
use App\Http\Controllers\Service\BPJC\BPJobController;
use App\Http\Controllers\Service\SM\SMController;

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


// Body & Paint Job Controller
Route::middleware(['auth', 'role:body_PaintJC'])->group(function () {
    Route::get('/service/bp-jc', [MainDashboardController::class, 'serviceBPJC'])->name('service.bp-jc');
});

// Job Controller
Route::middleware(['auth', 'role:JobController'])->group(function () {
    Route::get('/service/jc', [JobController::class, 'index'])->name('service.jc');
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
    Route::get('/dashboard', [JobController::class, 'index'])->name('dashboard');
    Route::get('/sublet', [JobController::class, 'sublet'])->name('sublet');
    Route::get('/inprogress', [JobController::class, 'inprogress'])->name('inprogress');
    Route::get('/parts-status', [JobController::class, 'partsStatus'])->name('parts-status');

    Route::get('/assign/{laborId}', [JobController::class, 'showAssignForm'])->name('assign');
    Route::post('/assign', [JobController::class, 'assignJob'])->name('assign.process');
    Route::post('/job-done', [JobController::class, 'jobDone'])->name('job-done');

    Route::get('/sublet-assign/{subletId}', [JobController::class, 'showSubletAssignForm'])->name('sublet-assign');
    Route::post('/sublet-assign', [JobController::class, 'assignSublet'])->name('sublet-assign.process');
    Route::get('/sublet-done/{subletId}', [JobController::class, 'showSubletDoneForm'])->name('sublet-done');
    Route::post('/sublet-done', [JobController::class, 'subletDone'])->name('sublet-done.process');

    Route::post('/team-members', [JobController::class, 'getTeamMembers'])->name('team-members');
});

// ==================== CASHIER ROUTES ====================
Route::middleware(['auth', 'role:Cashier,FManager,Accountant,IT Manager'])
    ->prefix('finance/cashier')
    ->name('cashier.')
    ->group(function () {

    // Main pages
    Route::get('/', [CashierController::class, 'index'])->name('index');
    Route::get('/search', [CashierController::class, 'search'])->name('search');
    Route::get('/search-jobs', [CashierController::class, 'searchJobs'])->name('search-jobs');
    Route::get('/history', [CashierController::class, 'history'])->name('history');
    Route::get('/reports', [CashierController::class, 'reports'])->name('reports');

    // Parts return
    Route::get('/parts-return', [CashierController::class, 'partsReturn'])->name('parts-return');
    Route::post('/process-return', [CashierController::class, 'processReturn'])->name('process-return');

    // Invoice
    Route::post('/invoice', [CashierController::class, 'invoice'])->name('invoice');
    Route::post('/save-invoice', [CashierController::class, 'saveInvoice'])->name('save-invoice');
    Route::get('/print-invoice/{id}', [CashierController::class, 'printInvoice'])->name('print-invoice');

    // Reports (ReportsController)
    Route::post('/report-download', [ReportsController::class, 'typeReport'])->name('report-download');
    Route::post('/business-summary', [ReportsController::class, 'summary'])->name('business-summary');
    Route::post('/all-report', [ReportsController::class, 'allReport'])->name('all-report');
    Route::post('/msi-report', [ReportsController::class, 'msiReport'])->name('msi-report');
    Route::post('/pm-export', [ReportsController::class, 'pmExport'])->name('pm-export');

    // Print list pages - GET (CashierController - just shows the list)
    Route::get('/print-initial', [CashierController::class, 'printInitialRO'])->name('print-initial');
    Route::get('/print-close', [CashierController::class, 'printCloseRO'])->name('print-close');

    // Actual print pages - POST (ReportsController - does the actual printing)
    Route::post('/print-initial-ro', [ReportsController::class, 'printInitialRO'])->name('print-initial-ro');
    Route::post('/print-close-ro', [ReportsController::class, 'printCloseRO'])->name('print-close-ro');

    // Tax invoice
    Route::post('/tax-invoice', [ReportsController::class, 'taxInvoice'])->name('tax-invoice');
    Route::get('/tax-invoice/{ro_no}', [ReportsController::class, 'taxInvoice'])->name('tax-invoice-get');
});



Route::middleware(['auth', 'role:PManager,DataOperator,IT Manager'])
    ->prefix('parts/entry')
    ->name('parts.')
    ->group(function () {

    // Dashboard / Workshop Requisitions
    Route::get('/', [PartsController::class, 'index'])->name('index');

    // Workshop Return
    Route::get('/workshop-return', [PartsController::class, 'workshopReturn'])->name('workshop-return');
    Route::post('/workshop-return', [PartsController::class, 'workshopReturnUpdate'])->name('workshop-return.update');

    // Estimates
    Route::get('/estimates', [PartsController::class, 'estimates'])->name('estimates');

    // Unclosed Requisitions
    Route::get('/unclosed-req', [PartsController::class, 'unclosedRequisitions'])->name('unclosed-req');
    Route::post('/unclosed-req/close', [PartsController::class, 'closeRequisition'])->name('unclosed-req.close');

    // Purchase
    Route::get('/purchase', [PartsController::class, 'purchase'])->name('purchase');
    Route::post('/purchase', [PartsController::class, 'purchaseStore'])->name('purchase.store');
    Route::get('/purchase/{invoice_no}/detail', [PartsController::class, 'purchaseDetail'])->name('purchase.detail');
    Route::post('/purchase/{invoice_no}/detail', [PartsController::class, 'purchaseDetailStore'])->name('purchase.detail.store');
    Route::get('/purchase/{invoice_no}/view', [PartsController::class, 'purchaseDetailView'])->name('purchase.detail.view');
    Route::post('/purchase/edit', [PartsController::class, 'purchaseEdit'])->name('purchase.edit');

    // Purchase Return
    Route::get('/purchase-return', [PartsController::class, 'purchaseReturn'])->name('purchase-return');
    Route::post('/purchase-return', [PartsController::class, 'purchaseReturnStore'])->name('purchase-return.store');

    // Sale
    Route::get('/sale', [PartsController::class, 'sale'])->name('sale');
    Route::post('/sale', [PartsController::class, 'saleStore'])->name('sale.store');
    Route::get('/sale/{sale_inv}/invoice', [PartsController::class, 'saleInvoice'])->name('sale.invoice');
    Route::post('/sale/part-store', [PartsController::class, 'salePartStore'])->name('sale.part.store');

    // Sale Return
    Route::get('/sale-return', [PartsController::class, 'saleReturn'])->name('sale-return');
    Route::post('/sale-return', [PartsController::class, 'saleReturnStore'])->name('sale-return.store');

    // Vendor Payments
    Route::get('/jobber-payment', [PartsController::class, 'jobberPayment'])->name('jobber-payment');
    Route::post('/jobber-payment', [PartsController::class, 'jobberPaymentStore'])->name('jobber-payment.store');

    // New entries
    Route::get('/new-part', [PartsController::class, 'newPart'])->name('new-part');
    Route::post('/new-part', [PartsController::class, 'newPartStore'])->name('new-part.store');

    Route::get('/new-jobber', [PartsController::class, 'newJobber'])->name('new-jobber');
    Route::post('/new-jobber', [PartsController::class, 'newJobberStore'])->name('new-jobber.store');

    Route::get('/new-cate-part', [PartsController::class, 'newCatePart'])->name('new-cate-part');
    Route::post('/new-cate-part', [PartsController::class, 'newCatePartStore'])->name('new-cate-part.store');
    Route::post('/new-cate-part/delete', [PartsController::class, 'newCatePartDelete'])->name('new-cate-part.delete');

    // Edit
    Route::get('/location-change', [PartsController::class, 'locationChange'])->name('location-change');
    Route::post('/location-change', [PartsController::class, 'locationChangeUpdate'])->name('location-change.update');

    // Others
    Route::get('/incentives', [PartsController::class, 'incentives'])->name('incentives');
    Route::get('/appointments', [PartsController::class, 'appointments'])->name('appointments');
    Route::post('/appointments', [PartsController::class, 'appointmentUpdateStatus'])->name('appointments.update');

    // Search
    Route::get('/search', [PartsController::class, 'search'])->name('search');
    Route::post('/search', [PartsController::class, 'searchRedirect'])->name('search.redirect');

    // Print / Requisition
    Route::get('/print-requisition', [PartsController::class, 'printRequisition'])->name('print-requisition');
    Route::post('/print-requisition', [PartsController::class, 'printRequisitionRedirect'])->name('print-requisition.redirect');

    // Reports
    Route::get('/reports', [PartsController::class, 'reports'])->name('reports');
    Route::get('/kpi-report', [PartsController::class, 'kpiReport'])->name('kpi-report');
    Route::get('/dpok-report', [PartsController::class, 'dpokReport'])->name('dpok-report');

    // AJAX Endpoints
    Route::get('/ajax/search-part', [PartsController::class, 'searchPart'])->name('ajax.search-part');
    Route::get('/ajax/search-stock', [PartsController::class, 'searchStock'])->name('ajax.search-stock');
    Route::post('/ajax/check-invoice', [PartsController::class, 'checkInvoice'])->name('ajax.check-invoice');
    Route::get('/ajax/search-sale-invoice', [PartsController::class, 'searchSaleInvoice'])->name('ajax.search-sale-invoice');
    Route::get('/ajax/search-purchase-invoice', [PartsController::class, 'searchPurchaseInvoice'])->name('ajax.search-purchase-invoice');

    // Print pages
    Route::get('/print/sale-invoice/{inv_no}', [PartsController::class, 'printSaleInvoice'])->name('print.sale-invoice');
    Route::get('/print/purchase/{invoice_no}', [PartsController::class, 'printPurchase'])->name('print.purchase');
    Route::get('/print/purchase-return/{invoice_no}', [PartsController::class, 'printPurchaseReturn'])->name('print.purchase-return');
    Route::get('/print/sale-return/{invoice_no}', [PartsController::class, 'printSaleReturn'])->name('print.sale-return');
    Route::get('/print/wp-return', [PartsController::class, 'printWpReturn'])->name('print.wp-return');
    Route::get('/print/payment/{payment_id}', [PartsController::class, 'printPayment'])->name('print.payment');
    Route::get('/print/issue-part/{inv_id}', [PartsController::class, 'printIssuePart'])->name('print.issue-part');
    Route::get('/print/issue-cons/{inv_id}', [PartsController::class, 'printIssueCons'])->name('print.issue-cons');
});


// ============================================================
//  SERVICE MODULE ROUTES  – paste inside your web.php
// ============================================================

// ─────────────────────────────────────────────────────────────
//  JOBCARD MODULE  (role: SerAdvisor, IT Manager)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:SerAdvisor,IT Manager'])
    ->prefix('service/jobcard')
    ->name('jobcard.')
    ->group(function () {

        Route::get('/',                         [JobcardController::class, 'index'])->name('index');

        // Add Vehicle / Search
        Route::get('/add-vehicle',              [JobcardController::class, 'searchVehicle'])->name('add-vehicle');
        Route::post('/add-vehicle/search',      [JobcardController::class, 'searchVehicleResult'])->name('add-vehicle.search');
        Route::post('/add-vehicle/customer',    [JobcardController::class, 'storeCustomer'])->name('add-vehicle.customer');
        Route::post('/add-vehicle/vehicle',     [JobcardController::class, 'storeVehicle'])->name('add-vehicle.vehicle.store');

        // Customer Edit
        Route::get('/customer/edit/{id}',       [JobcardController::class, 'editCustomer'])->name('customer.edit');
        Route::post('/customer/update',         [JobcardController::class, 'updateCustomer'])->name('customer.update');

        // Mileage check (AJAX)
        Route::post('/check-mileage',           [JobcardController::class, 'checkMileage'])->name('check-mileage');

        // Estimate
        Route::get('/estimate/create',          [JobcardController::class, 'createEstimate'])->name('estimate.create');
        Route::post('/estimate/store',          [JobcardController::class, 'storeEstimate'])->name('estimate.store');
        Route::get('/estimate/{id}/ro',         [JobcardController::class, 'estimateRO'])->name('estimate.ro');
        Route::get('/unclosed-estimates',       [JobcardController::class, 'unclosedEstimates'])->name('unclosed-estimates');
        Route::get('/estimate/{id}/labor',      [JobcardController::class, 'estimateLabor'])->name('estimate.labor');
        Route::post('/estimate/labor/store',    [JobcardController::class, 'estimateLaborStore'])->name('estimate.labor.store');
        Route::get('/estimate/{id}/part',       [JobcardController::class, 'estimatePart'])->name('estimate.part');
        Route::post('/estimate/part/store',     [JobcardController::class, 'estimatePartStore'])->name('estimate.part.store');
        Route::get('/estimate/{id}/consumable', [JobcardController::class, 'estimateConsumable'])->name('estimate.consumable');
        Route::post('/estimate/consumable/store',[JobcardController::class,'estimateConsumableStore'])->name('estimate.consumable.store');
        Route::get('/estimate/{id}/sublet',     [JobcardController::class, 'estimateSublet'])->name('estimate.sublet');
        Route::post('/estimate/sublet/store',   [JobcardController::class, 'estimateSubletStore'])->name('estimate.sublet.store');

        // Jobcard management
        Route::get('/{jobId}/additional',                  [JobcardController::class, 'additional'])->name('additional');
        Route::get('/{jobId}/additional/jobrequest',       [JobcardController::class, 'additionalJobrequest'])->name('additional.jobrequest');
        Route::post('/additional/jobrequest/store',        [JobcardController::class, 'additionalJobrequestStore'])->name('additional.jobrequest.store');
        Route::get('/{jobId}/additional/part',             [JobcardController::class, 'additionalPart'])->name('additional.part');
        Route::post('/additional/part/store',              [JobcardController::class, 'additionalPartStore'])->name('additional.part.store');
        Route::get('/{jobId}/additional/consumable',       [JobcardController::class, 'additionalConsumable'])->name('additional.consumable');
        Route::post('/additional/consumable/store',        [JobcardController::class, 'additionalConsumableStore'])->name('additional.consumable.store');
        Route::get('/{jobId}/additional/sublet',           [JobcardController::class, 'additionalSublet'])->name('additional.sublet');
        Route::post('/additional/sublet/store',            [JobcardController::class, 'additionalSubletStore'])->name('additional.sublet.store');

        // Delete item
        Route::post('/delete-item',             [JobcardController::class, 'deleteItem'])->name('delete-item');

        // Ajax variant
        Route::get('/ajax/variant',             [JobcardController::class, 'ajaxVariant'])->name('ajax.variant');

        // Additional jobs list (all in-progress jobs for SA)
        Route::get('/additional-list',          [JobcardController::class, 'additionalList'])->name('additional-list');

        // Job complete page
        Route::get('/complete',                 [JobcardController::class, 'complete'])->name('complete');
        Route::post('/complete',                [JobcardController::class, 'completeProcess'])->name('complete.process');

        // Status pages
        Route::get('/status/labor',             [JobcardController::class, 'statusLabor'])->name('status.labor');
        Route::get('/status/parts',             [JobcardController::class, 'statusParts'])->name('status.parts');
        Route::get('/status/sublet',            [JobcardController::class, 'statusSublet'])->name('status.sublet');
        Route::get('/status/consumable',        [JobcardController::class, 'statusConsumable'])->name('status.consumable');

        // Add New pages
        Route::get('/new-labor',                [JobcardController::class, 'newLabor'])->name('new.labor');
        Route::get('/new-part',                 [JobcardController::class, 'newPart'])->name('new.part');
        Route::get('/new-consumable',           [JobcardController::class, 'newConsumable'])->name('new.consumable');

        // Search
        Route::match(['get','post'], '/search', [JobcardController::class, 'search'])->name('search');
    });

// ─────────────────────────────────────────────────────────────
//  BODY & PAINT JC MODULE  (role: body_PaintJC, IT Manager)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:body_PaintJC,IT Manager'])
    ->prefix('service/bp-jc')
    ->name('bp-jc.')
    ->group(function () {

        Route::get('/',                             [BPJobController::class, 'index'])->name('index');
        Route::post('/job-done',                    [BPJobController::class, 'jobDone'])->name('job-done');
        Route::get('/inprogress',                   [BPJobController::class, 'inprogress'])->name('inprogress');
        Route::get('/customers',                    [BPJobController::class, 'customers'])->name('customers');
        Route::get('/sublet',                       [BPJobController::class, 'sublet'])->name('sublet');
        Route::get('/search',                       [BPJobController::class, 'search'])->name('search');
        Route::get('/unclosed',                     [BPJobController::class, 'unclosedJC'])->name('unclosed');
        Route::get('/part-add',                     [BPJobController::class, 'partAdd'])->name('part-add');

        // Job assign
        Route::get('/assign/{laborId}',             [BPJobController::class, 'showAssignForm'])->name('assign');
        Route::post('/assign',                      [BPJobController::class, 'assignJob'])->name('assign.process');
        Route::post('/team-members',                [BPJobController::class, 'getTeamMembers'])->name('team-members');

        // Additional management
        Route::get('/{jobId}/additional',                  [BPJobController::class, 'additional'])->name('additional');
        Route::get('/{jobId}/additional/jobrequest',       [BPJobController::class, 'additionalJobrequest'])->name('additional.jobrequest');
        Route::post('/additional/jobrequest/store',        [BPJobController::class, 'additionalJobrequestStore'])->name('additional.jobrequest.store');
        Route::get('/{jobId}/additional/part',             [BPJobController::class, 'additionalPart'])->name('additional.part');
        Route::post('/additional/part/store',              [BPJobController::class, 'additionalPartStore'])->name('additional.part.store');
        Route::get('/{jobId}/additional/consumable',       [BPJobController::class, 'additionalConsumable'])->name('additional.consumable');
        Route::post('/additional/consumable/store',        [BPJobController::class, 'additionalConsumableStore'])->name('additional.consumable.store');
        Route::get('/{jobId}/additional/sublet',           [BPJobController::class, 'additionalSublet'])->name('additional.sublet');
        Route::post('/additional/sublet/store',            [BPJobController::class, 'additionalSubletStore'])->name('additional.sublet.store');
    });

// ─────────────────────────────────────────────────────────────
//  SERVICE MANAGER (SM) MODULE  (role: SManager, IMCc)
// ─────────────────────────────────────────────────────────────
Route::middleware(['auth', 'role:SManager,IMCc'])
    ->prefix('service/sm')
    ->name('sm.')
    ->group(function () {

        // Dashboard
        Route::get('/',                             [SMController::class, 'index'])->name('index');
        Route::get('/unclosed-ros',                 [SMController::class, 'unclosedROs'])->name('unclosed-ros');

        // Search & Print
        Route::match(['get','post'], '/search',     [SMController::class, 'search'])->name('search');

        // Status pages
        Route::get('/status/labor',                 [SMController::class, 'statusLabor'])->name('status-labor');
        Route::get('/status/parts',                 [SMController::class, 'statusParts'])->name('status-parts');
        Route::get('/status/sublet',                [SMController::class, 'statusSublet'])->name('status-sublet');
        Route::get('/status/consumable',            [SMController::class, 'statusConsumable'])->name('status-consumable');

        // History & JC Changes
        Route::get('/history',                      [SMController::class, 'history'])->name('history');
        Route::get('/jc-changes',                   [SMController::class, 'jcChanges'])->name('jc-changes');

        // Labor changes
        Route::get('/labor-change',                 [SMController::class, 'laborChange'])->name('labor-change');
        Route::post('/labor-change/update',         [SMController::class, 'laborChangeUpdate'])->name('labor-change.update');
        Route::get('/hidden-labor-change',          [SMController::class, 'hiddenLaborChange'])->name('hidden-labor-change');
        Route::post('/hidden-labor-change/update',  [SMController::class, 'hiddenLaborUpdate'])->name('hidden-labor-change.update');

        // Unclose jobcard
        Route::get('/unclose',                      [SMController::class, 'unclose'])->name('unclose');
        Route::post('/unclose',                     [SMController::class, 'uncloseProcess'])->name('unclose.process');

        // Active Customers
        Route::get('/ac',                           [SMController::class, 'activeCustomers'])->name('ac');
        Route::post('/ac/update-type',              [SMController::class, 'updateCustomerType'])->name('ac.update-type');

        // UIO
        Route::get('/uio',                          [SMController::class, 'uio'])->name('uio');
        Route::post('/uio/update',                  [SMController::class, 'uioUpdate'])->name('uio.update');

        // VIN
        Route::get('/vin',                          [SMController::class, 'vin'])->name('vin');
        Route::get('/vin-check',                    [SMController::class, 'vinCheck'])->name('vin-check');

        // Campaigns
        Route::get('/campaigns',                    [SMController::class, 'campaigns'])->name('campaigns');
        Route::post('/campaigns/store',             [SMController::class, 'campaignStore'])->name('campaigns.store');
        Route::post('/campaigns/toggle',            [SMController::class, 'campaignToggle'])->name('campaigns.toggle');
        Route::get('/campaigns/{id}/labour',        [SMController::class, 'campaignLabour'])->name('campaign-labour');
        Route::post('/campaigns/{id}/labour',       [SMController::class, 'campaignLabourStore'])->name('campaign-labour.store');
        Route::post('/campaigns/labour/delete',     [SMController::class, 'campaignLabourDelete'])->name('campaign-labour.delete');

        // SMS Templates
        Route::get('/sms',                          [SMController::class, 'smsManage'])->name('sms');
        Route::post('/sms/update',                  [SMController::class, 'smsUpdate'])->name('sms.update');

        // New Labor Request
        Route::get('/new-labor',                    [SMController::class, 'newLabor'])->name('new-labor');
        Route::post('/new-labor/store',             [SMController::class, 'newLaborStore'])->name('new-labor.store');

        // Vendors
        Route::get('/vendors',                      [SMController::class, 'vendors'])->name('vendors');
        Route::post('/vendors/store',               [SMController::class, 'vendorStore'])->name('vendors.store');
        Route::post('/vendors/toggle',              [SMController::class, 'vendorToggle'])->name('vendors.toggle');

        // Insurance Companies
        Route::get('/insurance',                    [SMController::class, 'insuranceCompanies'])->name('insurance');
        Route::post('/insurance/store',             [SMController::class, 'insuranceStore'])->name('insurance.store');
        Route::post('/insurance/toggle',            [SMController::class, 'insuranceToggle'])->name('insurance.toggle');

        // New User
        Route::get('/new-user',                     [SMController::class, 'newUser'])->name('new-user');
        Route::post('/new-user/store',              [SMController::class, 'newUserStore'])->name('new-user.store');

        // Problem Box
        Route::match(['get','post'], '/problem-box',[SMController::class, 'problemBox'])->name('problem-box');

        // Upload Frame
        Route::get('/upload-frame',                 [SMController::class, 'uploadFrame'])->name('upload-frame');
        Route::post('/upload-frame/store',          [SMController::class, 'uploadFrameStore'])->name('upload-frame.store');

        // Reports
        Route::get('/reports',                      [SMController::class, 'reports'])->name('reports');

        // ── Master Data ──────────────────────────────────
        Route::prefix('master')->name('master.')->group(function () {

            // Bays
            Route::get('/bays',             [SMController::class, 'masterBays'])->name('bays');
            Route::post('/bays/store',      [SMController::class, 'masterBaysStore'])->name('bays.store');
            Route::post('/bays/update',     [SMController::class, 'masterBaysUpdate'])->name('bays.update');
            Route::post('/bays/delete',     [SMController::class, 'masterBaysDelete'])->name('bays.delete');

            // Labor List
            Route::get('/labor',            [SMController::class, 'masterLabor'])->name('labor');
            Route::post('/labor/store',     [SMController::class, 'masterLaborStore'])->name('labor.store');
            Route::post('/labor/update',    [SMController::class, 'masterLaborUpdate'])->name('labor.update');
            Route::post('/labor/delete',    [SMController::class, 'masterLaborDelete'])->name('labor.delete');

            // Tech Teams
            Route::get('/teams',            [SMController::class, 'masterTeams'])->name('teams');
            Route::post('/teams/store',     [SMController::class, 'masterTeamsStore'])->name('teams.store');
            Route::post('/teams/update',    [SMController::class, 'masterTeamsUpdate'])->name('teams.update');
            Route::post('/teams/delete',    [SMController::class, 'masterTeamsDelete'])->name('teams.delete');

            // Variant Codes
            Route::get('/variants',         [SMController::class, 'masterVariants'])->name('variants');
            Route::post('/variants/store',  [SMController::class, 'masterVariantsStore'])->name('variants.store');
            Route::post('/variants/update', [SMController::class, 'masterVariantsUpdate'])->name('variants.update');
            Route::post('/variants/delete', [SMController::class, 'masterVariantsDelete'])->name('variants.delete');
        });
    });
