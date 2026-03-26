<?php
// routes/web.php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController as MainDashboardController;
use App\Http\Controllers\ITManager\DashboardController as ITDashboardController;
use App\Http\Controllers\Service\JC\JobController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Finance\CashierController;
use App\Http\Controllers\Finance\Cashier\ReportsController;

use App\Http\Controllers\Finance\AccountantController;
use App\Http\Controllers\Finance\RecoveryController;
use App\Http\Controllers\Finance\AccountsController;

use App\Http\Controllers\Parts\PartsController;

use App\Http\Controllers\Service\Jobcard\JobcardController;
use App\Http\Controllers\Service\BPJC\BPJobController;
use App\Http\Controllers\Service\SM\SMController;
use App\Http\Controllers\Sales\SalesController;

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

        Route::get('/', [CashierController::class, 'index'])->name('index');
        Route::get('/search', [CashierController::class, 'search'])->name('search');
        Route::post('/search', [CashierController::class, 'searchRedirect'])->name('search.redirect');
        Route::get('/search-jobs', [CashierController::class, 'searchJobs'])->name('search-jobs');
        Route::get('/history', [CashierController::class, 'history'])->name('history');
        Route::get('/reports', [CashierController::class, 'reports'])->name('reports');

        Route::get('/parts-return', [CashierController::class, 'partsReturn'])->name('parts-return');
        Route::post('/process-return', [CashierController::class, 'processReturn'])->name('process-return');

        Route::match(['get', 'post'], '/invoice', [CashierController::class, 'invoice'])->name('invoice');
        Route::post('/save-invoice', [CashierController::class, 'saveInvoice'])->name('save-invoice');
        Route::get('/print-invoice/{id}', [CashierController::class, 'printInvoice'])->name('print-invoice');

        Route::post('/report-download', [ReportsController::class, 'typeReport'])->name('report-download');
        Route::post('/business-summary', [ReportsController::class, 'summary'])->name('business-summary');
        Route::post('/all-report', [ReportsController::class, 'allReport'])->name('all-report');
        Route::post('/msi-report', [ReportsController::class, 'msiReport'])->name('msi-report');
        Route::post('/pm-export', [ReportsController::class, 'pmExport'])->name('pm-export');

        Route::get('/print-initial', [CashierController::class, 'printInitialRO'])->name('print-initial');
        Route::get('/print-close', [CashierController::class, 'printCloseRO'])->name('print-close');

        Route::match(['get', 'post'], '/print-initial-ro', [ReportsController::class, 'printInitialRO'])->name('print-initial-ro');
        Route::match(['get', 'post'], '/print-close-ro', [ReportsController::class, 'printCloseRO'])->name('print-close-ro');

        Route::post('/tax-invoice', [ReportsController::class, 'taxInvoice'])->name('tax-invoice');
        Route::get('/tax-invoice/{ro_no}', [ReportsController::class, 'taxInvoice'])->name('tax-invoice-get');
    });


// ==================== PARTS MODULE ====================
Route::middleware(['auth', 'role:PManager,DataOperator,IT Manager'])
    ->prefix('parts/entry')
    ->name('parts.')
    ->group(function () {

        // Dashboard / Workshop Requisitions
        Route::delete('/purchase/detail/{invoice_no}/{id}', [PartsController::class, 'destroy'])->name('purchase.detail.delete');
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

        // ==================== SALE ====================
        // Step 1 - GET: blank form with Jobber dropdown
        // Step 1 - POST: creates invoice header, redirects to /sale/{id}/add
        Route::get('/sale',  [PartsController::class, 'sale'])->name('sale');
        Route::post('/sale', [PartsController::class, 'saleStore'])->name('sale.store');

        // Step 2 - GET: show add-part form (invoice ID known)
        // Step 2 - POST: add a part row to the invoice
        Route::get('/sale/{sale_inv}/add',  [PartsController::class, 'saleAdd'])->name('sale.add');
        Route::post('/sale/{sale_inv}/add', [PartsController::class, 'saleAddPart'])->name('sale.add.part');

        // Step 3 - GET: review invoice (all parts + totals + close button)
        Route::get('/sale/{sale_inv}/invoice', [PartsController::class, 'saleInvoice'])->name('sale.invoice');

        // Step 3 - POST: close invoice + print (sale_inv in URL so controller gets it)
        Route::post('/sale/{sale_inv}/close', [PartsController::class, 'printAndClose'])->name('sale.close');

        // Delete a part line from the invoice review page
        Route::post('/sale/invoice/delete', [PartsController::class, 'saleInvoiceDelete'])->name('sale.invoice.delete');

        // Edit a part line
        Route::get('/sale/invoice/edit/{sell_id}', [PartsController::class, 'saleInvoiceEdit'])->name('sale.invoice.edit');

        // Sale Return
        Route::get('/sale-return',  [PartsController::class, 'saleReturn'])->name('sale-return');
        Route::post('/sale-return', [PartsController::class, 'saleReturnStore'])->name('sale-return.store');

        // Vendor Payments
        Route::get('/jobber-payment',  [PartsController::class, 'jobberPayment'])->name('jobber-payment');
        Route::post('/jobber-payment', [PartsController::class, 'jobberPaymentStore'])->name('jobber-payment.store');

        // New entries
        Route::get('/new-part',  [PartsController::class, 'newPart'])->name('new-part');
        Route::post('/new-part', [PartsController::class, 'newPartStore'])->name('new-part.store');

        Route::get('/new-jobber',  [PartsController::class, 'newJobber'])->name('new-jobber');
        Route::post('/new-jobber', [PartsController::class, 'newJobberStore'])->name('new-jobber.store');

        Route::get('/new-cate-part',  [PartsController::class, 'newCatePart'])->name('new-cate-part');
        Route::post('/new-cate-part', [PartsController::class, 'newCatePartStore'])->name('new-cate-part.store');
        Route::post('/new-cate-part/delete', [PartsController::class, 'newCatePartDelete'])->name('new-cate-part.delete');

        // Location change
        Route::get('/location-change',  [PartsController::class, 'locationChange'])->name('location-change');
        Route::post('/location-change', [PartsController::class, 'locationChangeUpdate'])->name('location-change.update');

        // Others
        Route::get('/incentives',   [PartsController::class, 'incentives'])->name('incentives');
        Route::get('/appointments', [PartsController::class, 'appointments'])->name('appointments');
        Route::post('/appointments',[PartsController::class, 'appointmentUpdateStatus'])->name('appointments.update');

        // Search
        Route::get('/search',  [PartsController::class, 'search'])->name('search');
        Route::post('/search', [PartsController::class, 'searchRedirect'])->name('search.redirect');

        // Print / Requisition
        Route::get('/print-requisition',  [PartsController::class, 'printRequisition'])->name('print-requisition');
        Route::post('/print-requisition', [PartsController::class, 'printRequisitionRedirect'])->name('print-requisition.redirect');

        // Reports
        Route::get('/reports',                [PartsController::class, 'reports'])->name('reports');
        Route::get('/reports/daily-sale',     [PartsController::class, 'reportDailySale'])->name('reports.daily-sale');
        Route::get('/reports/stock',          [PartsController::class, 'reportStock'])->name('reports.stock');
        Route::get('/reports/purchase',       [PartsController::class, 'reportPurchase'])->name('reports.purchase');
        Route::get('/reports/sale',           [PartsController::class, 'reportSale'])->name('reports.sale');
        Route::get('/reports/sale-history',   [PartsController::class, 'reportSaleHistory'])->name('reports.sale-history');
        Route::get('/reports/dead-stock',     [PartsController::class, 'reportDeadStock'])->name('reports.dead-stock');
        Route::get('/reports/non-moving',     [PartsController::class, 'reportNonMoving'])->name('reports.non-moving');
        Route::get('/reports/fill-rate',      [PartsController::class, 'reportFillRate'])->name('reports.fill-rate');
        Route::get('/reports/lost-sale',      [PartsController::class, 'reportLostSale'])->name('reports.lost-sale');
        Route::get('/reports/revenue',        [PartsController::class, 'reportRevenue'])->name('reports.revenue');
        Route::get('/reports/reorder',        [PartsController::class, 'reportReorder'])->name('reports.reorder');
        Route::get('/reports/part-wise',      [PartsController::class, 'reportPartWise'])->name('reports.part-wise');
        Route::get('/kpi-report',             [PartsController::class, 'kpiReport'])->name('kpi-report');
        Route::get('/dpok-report',            [PartsController::class, 'dpokReport'])->name('dpok-report');

        // ==================== AJAX Endpoints ====================
        Route::get('/ajax/search-part',           [PartsController::class, 'searchPart'])->name('ajax.search-part');
        Route::get('/ajax/search-part-desc',      [PartsController::class, 'searchPartDesc'])->name('ajax.search-part-desc');
        Route::get('/ajax/search-stock',          [PartsController::class, 'searchStock'])->name('ajax.search-stock');
        Route::post('/ajax/check-invoice',        [PartsController::class, 'checkInvoice'])->name('ajax.check-invoice');
        Route::post('/ajax/search-stock-by-part', [PartsController::class, 'searchStockByPart'])->name('ajax.search-stock-by-part');
        Route::post('/ajax/search-stock-by-grn',  [PartsController::class, 'searchStockByGrn'])->name('ajax.search-stock-by-grn');
        Route::post('/ajax/search-sale-inv',      [PartsController::class, 'searchSaleInvoiceParts'])->name('ajax.search-sale-inv');
        Route::get('/ajax/jobber-balance',        [PartsController::class, 'getJobberBalance'])->name('ajax.jobber-balance');
        Route::get('/ajax/search-sale-invoice',   [PartsController::class, 'searchSaleInvoice'])->name('ajax.search-sale-invoice');
        Route::get('/ajax/search-purchase-invoice',[PartsController::class,'searchPurchaseInvoice'])->name('ajax.search-purchase-invoice');

        // ==================== Print Pages ====================
        Route::get('/print/sale-invoice/{inv_no}',      [PartsController::class, 'printSaleInvoice'])->name('print.sale-invoice');
        Route::get('/print/purchase/{invoice_no}',      [PartsController::class, 'printPurchase'])->name('print.purchase');
        Route::get('/print/purchase-return/{invoice_no}',[PartsController::class,'printPurchaseReturn'])->name('print.purchase-return');
        Route::get('/print/sale-return/{invoice_no}',   [PartsController::class, 'printSaleReturn'])->name('print.sale-return');
        Route::get('/print/wp-return',                  [PartsController::class, 'printWpReturn'])->name('print.wp-return');
        Route::get('/print/payment/{payment_id}',       [PartsController::class, 'printPayment'])->name('print.payment');
        Route::get('/print/issue-part/{inv_id}',        [PartsController::class, 'printIssuePart'])->name('print.issue-part');
        Route::get('/print/issue-cons/{inv_id}',        [PartsController::class, 'printIssueCons'])->name('print.issue-cons');

        // Issue part / consumable
        Route::post('/issue-part-form',         [PartsController::class, 'issuePartForm'])->name('issue-part-form');
        Route::post('/issue-part-submit',       [PartsController::class, 'issuePartSubmit'])->name('issue-part-submit');
        Route::post('/issue-cons-form',         [PartsController::class, 'issueConsForm'])->name('issue-cons-form');
        Route::post('/issue-cons-submit',       [PartsController::class, 'issueConsSubmit'])->name('issue-cons-submit');
        Route::post('/issue-part',              [PartsController::class, 'issuePart'])->name('issue-part');
        Route::post('/issue-consumable',        [PartsController::class, 'issueConsumable'])->name('issue-consumable');
        Route::post('/part-not-available',      [PartsController::class, 'partNotAvailable'])->name('part-not-available');
        Route::post('/consumable-not-available',[PartsController::class, 'consumableNotAvailable'])->name('consumable-not-available');
    });


// ==================== SERVICE MODULE ROUTES ====================

// JOBCARD MODULE
Route::middleware(['auth', 'role:SerAdvisor,IT Manager'])
    ->prefix('service/jobcard')
    ->name('jobcard.')
    ->group(function () {

        Route::get('/', [JobcardController::class, 'index'])->name('index');
        Route::get('/add-vehicle', [JobcardController::class, 'searchVehicle'])->name('add-vehicle');
        Route::post('/add-vehicle/search', [JobcardController::class, 'searchVehicleResult'])->name('add-vehicle.search');
        Route::get('/add-vehicle/new', [JobcardController::class, 'newVehicleForm'])->name('add-vehicle.new');
        Route::post('/add-vehicle/new/store', [JobcardController::class, 'storeNewVehicle'])->name('add-vehicle.new.store');
        Route::get('/vehicle-detail', [JobcardController::class, 'vehicleDetail'])->name('vehicle-detail');
        Route::get('/add-customer', [JobcardController::class, 'addCustomerForm'])->name('add-customer');
        Route::post('/add-customer/store', [JobcardController::class, 'storeCustomer'])->name('add-customer.store');
        Route::get('/customer/edit/{id}', [JobcardController::class, 'editCustomer'])->name('customer.edit');
        Route::post('/customer/update', [JobcardController::class, 'updateCustomer'])->name('customer.update');
        Route::get('/create', [JobcardController::class, 'createJobcard'])->name('create');
        Route::post('/store', [JobcardController::class, 'storeJobcard'])->name('store');
        Route::get('/{jobcId}/checklist', [JobcardController::class, 'checklist'])->name('checklist');
        Route::post('/checklist/store', [JobcardController::class, 'storeChecklist'])->name('checklist.store');
        Route::get('/unclosed-list', [JobcardController::class, 'unclosedList'])->name('unclosed-list');
        Route::post('/start-working', [JobcardController::class, 'startWorking'])->name('start-working');
        Route::post('/check-mileage', [JobcardController::class, 'checkMileage'])->name('check-mileage');
        Route::get('/estimate/create', [JobcardController::class, 'createEstimate'])->name('estimate.create');
        Route::post('/estimate/store', [JobcardController::class, 'storeEstimate'])->name('estimate.store');
        Route::get('/estimate/{id}/ro', [JobcardController::class, 'estimateRO'])->name('estimate.ro');
        Route::get('/unclosed-estimates', [JobcardController::class, 'unclosedEstimates'])->name('unclosed-estimates');
        Route::get('/estimate/{id}/labor', [JobcardController::class, 'estimateLabor'])->name('estimate.labor');
        Route::post('/estimate/labor/store', [JobcardController::class, 'estimateLaborStore'])->name('estimate.labor.store');
        Route::get('/estimate/{id}/part', [JobcardController::class, 'estimatePart'])->name('estimate.part');
        Route::post('/estimate/part/store', [JobcardController::class, 'estimatePartStore'])->name('estimate.part.store');
        Route::get('/estimate/{id}/consumable', [JobcardController::class, 'estimateConsumable'])->name('estimate.consumable');
        Route::post('/estimate/consumable/store', [JobcardController::class, 'estimateConsumableStore'])->name('estimate.consumable.store');
        Route::get('/estimate/{id}/sublet', [JobcardController::class, 'estimateSublet'])->name('estimate.sublet');
        Route::post('/estimate/sublet/store', [JobcardController::class, 'estimateSubletStore'])->name('estimate.sublet.store');
        Route::get('/{jobId}/additional', [JobcardController::class, 'additional'])->name('additional');
        Route::get('/{jobId}/additional/jobrequest', [JobcardController::class, 'additionalJobrequest'])->name('additional.jobrequest');
        Route::post('/additional/jobrequest/store', [JobcardController::class, 'additionalJobrequestStore'])->name('additional.jobrequest.store');
        Route::get('/{jobId}/additional/part', [JobcardController::class, 'additionalPart'])->name('additional.part');
        Route::post('/additional/part/store', [JobcardController::class, 'additionalPartStore'])->name('additional.part.store');
        Route::get('/{jobId}/additional/consumable', [JobcardController::class, 'additionalConsumable'])->name('additional.consumable');
        Route::post('/additional/consumable/store', [JobcardController::class, 'additionalConsumableStore'])->name('additional.consumable.store');
        Route::get('/{jobId}/additional/sublet', [JobcardController::class, 'additionalSublet'])->name('additional.sublet');
        Route::post('/additional/sublet/store', [JobcardController::class, 'additionalSubletStore'])->name('additional.sublet.store');
        // Post-work Additional routes — always set Additional=1
        Route::post('/additional/jobrequest/post-store', [JobcardController::class, 'postWorkJobrequestStore'])->name('additional.jobrequest.post-store');
        Route::post('/additional/part/post-store', [JobcardController::class, 'postWorkPartStore'])->name('additional.part.post-store');
        Route::post('/additional/consumable/post-store', [JobcardController::class, 'postWorkConsumableStore'])->name('additional.consumable.post-store');
        Route::post('/additional/sublet/post-store', [JobcardController::class, 'postWorkSubletStore'])->name('additional.sublet.post-store');
        Route::post('/delete-item', [JobcardController::class, 'deleteItem'])->name('delete-item');
        Route::match(['get', 'post'], '/ajax/variant', [JobcardController::class, 'ajaxVariant'])->name('ajax.variant');
        Route::get('/additional-list', [JobcardController::class, 'additionalList'])->name('additional-list');
        Route::get('/complete', [JobcardController::class, 'complete'])->name('complete');
        Route::post('/complete', [JobcardController::class, 'completeProcess'])->name('complete.process');
        Route::get('/status/labor', [JobcardController::class, 'statusLabor'])->name('status.labor');
        Route::get('/status/parts', [JobcardController::class, 'statusParts'])->name('status.parts');
        Route::get('/status/sublet', [JobcardController::class, 'statusSublet'])->name('status.sublet');
        Route::get('/status/consumable', [JobcardController::class, 'statusConsumable'])->name('status.consumable');
        Route::get('/new-labor', [JobcardController::class, 'newLabor'])->name('new.labor');
        Route::post('/new-labor/store', [JobcardController::class, 'newLaborStore'])->name('new-labor.store');
        Route::get('/new-part', [JobcardController::class, 'newPart'])->name('new.part');
        Route::post('/new-part/store', [JobcardController::class, 'newPartStore'])->name('new-part.store');
        Route::get('/new-consumable', [JobcardController::class, 'newConsumable'])->name('new.consumable');
        Route::post('/new-consumable/store', [JobcardController::class, 'newConsumableStore'])->name('new-consumable.store');
        Route::match(['get', 'post'], '/search', [JobcardController::class, 'search'])->name('search');
        Route::get('/vehicle/edit', [JobcardController::class, 'editVehicle'])->name('vehicle.edit');
        Route::post('/vehicle/update', [JobcardController::class, 'updateVehicle'])->name('vehicle.update');
        Route::match(['get', 'post'], '/history', [JobcardController::class, 'vehicleHistory'])->name('history');
        Route::match(['get', 'post'], '/invoice', [JobcardController::class, 'invoiceView'])->name('invoice');
        Route::match(['get', 'post'], '/vin-check', [JobcardController::class, 'vinCheck'])->name('vin-check');
        Route::match(['get', 'post'], '/warranty', [JobcardController::class, 'warranty'])->name('warranty');
        Route::match(['get', 'post'], '/loyalty-services', [JobcardController::class, 'loyaltyServices'])->name('loyalty-services');
        Route::get('/multi-customer', [JobcardController::class, 'multiCustomerForm'])->name('multi-customer');
        Route::post('/multi-customer/store', [JobcardController::class, 'multiCustomerStore'])->name('multi-customer.store');
        Route::post('/ajax/msi-details', [JobcardController::class, 'ajaxMsiDetails'])->name('ajax.msi-details');
        Route::post('/ajax/labor-cost', [JobcardController::class, 'ajaxLaborCost'])->name('ajax.labor-cost');
        Route::post('/delete-estimate-item', [JobcardController::class, 'deleteEstimateItem'])->name('delete-estimate-item');
    });


// SERVICE MANAGER MODULE
Route::middleware(['auth', 'role:SManager,IMCc,IT Manager'])
    ->prefix('service/sm')
    ->name('sm.')
    ->group(function () {

        Route::get('/', [SMController::class, 'index'])->name('index');
        Route::get('/unclosed-ros', [SMController::class, 'unclosedROs'])->name('unclosed-ros');
        Route::match(['get', 'post'], '/search', [SMController::class, 'search'])->name('search');
        Route::get('/status/labor', [SMController::class, 'statusLabor'])->name('status-labor');
        Route::get('/status/parts', [SMController::class, 'statusParts'])->name('status-parts');
        Route::get('/status/sublet', [SMController::class, 'statusSublet'])->name('status-sublet');
        Route::get('/status/consumable', [SMController::class, 'statusConsumable'])->name('status-consumable');
        Route::get('/history', [SMController::class, 'history'])->name('history');
        Route::get('/jc-changes', [SMController::class, 'jcChanges'])->name('jc-changes');
        Route::get('/labor-change', [SMController::class, 'laborChange'])->name('labor-change');
        Route::post('/labor-change/update', [SMController::class, 'laborChangeUpdate'])->name('labor-change.update');
        Route::get('/hidden-labor-change', [SMController::class, 'hiddenLaborChange'])->name('hidden-labor-change');
        Route::post('/hidden-labor-change/update', [SMController::class, 'hiddenLaborUpdate'])->name('hidden-labor-change.update');
        Route::get('/unclose', [SMController::class, 'unclose'])->name('unclose');
        Route::post('/unclose', [SMController::class, 'uncloseProcess'])->name('unclose.process');
        Route::get('/ac', [SMController::class, 'activeCustomers'])->name('ac');
        Route::post('/ac/update-type', [SMController::class, 'updateCustomerType'])->name('ac.update-type');
        Route::get('/uio', [SMController::class, 'uio'])->name('uio');
        Route::post('/uio/update', [SMController::class, 'uioUpdate'])->name('uio.update');
        Route::get('/vin', [SMController::class, 'vin'])->name('vin');
        Route::get('/vin-check', [SMController::class, 'vinCheck'])->name('vin-check');
        Route::get('/campaigns', [SMController::class, 'campaigns'])->name('campaigns');
        Route::post('/campaigns/store', [SMController::class, 'campaignStore'])->name('campaigns.store');
        Route::post('/campaigns/toggle', [SMController::class, 'campaignToggle'])->name('campaigns.toggle');
        Route::get('/campaigns/{id}/labour', [SMController::class, 'campaignLabour'])->name('campaign-labour');
        Route::post('/campaigns/{id}/labour', [SMController::class, 'campaignLabourStore'])->name('campaign-labour.store');
        Route::post('/campaigns/labour/delete', [SMController::class, 'campaignLabourDelete'])->name('campaign-labour.delete');
        Route::get('/sms', [SMController::class, 'smsManage'])->name('sms');
        Route::post('/sms/update', [SMController::class, 'smsUpdate'])->name('sms.update');
        Route::get('/new-labor', [SMController::class, 'newLabor'])->name('new-labor');
        Route::post('/new-labor/store', [SMController::class, 'newLaborStore'])->name('new-labor.store');
        Route::get('/vendors', [SMController::class, 'vendors'])->name('vendors');
        Route::post('/vendors/store', [SMController::class, 'vendorStore'])->name('vendors.store');
        Route::post('/vendors/toggle', [SMController::class, 'vendorToggle'])->name('vendors.toggle');
        Route::get('/insurance', [SMController::class, 'insuranceCompanies'])->name('insurance');
        Route::post('/insurance/store', [SMController::class, 'insuranceStore'])->name('insurance.store');
        Route::post('/insurance/toggle', [SMController::class, 'insuranceToggle'])->name('insurance.toggle');
        Route::get('/new-user', [SMController::class, 'newUser'])->name('new-user');
        Route::post('/new-user/store', [SMController::class, 'newUserStore'])->name('new-user.store');
        Route::match(['get', 'post'], '/problem-box', [SMController::class, 'problemBox'])->name('problem-box');
        Route::get('/upload-frame', [SMController::class, 'uploadFrame'])->name('upload-frame');
        Route::post('/upload-frame/store', [SMController::class, 'uploadFrameStore'])->name('upload-frame.store');
        Route::get('/reports', [SMController::class, 'reports'])->name('reports');
        Route::get('/reports/summary', [SMController::class, 'reportSummary'])->name('reports.summary');
        Route::get('/reports/invoice', [SMController::class, 'reportInvoice'])->name('reports.invoice');
        Route::get('/reports/sa', [SMController::class, 'reportSA'])->name('reports.sa');
        Route::get('/reports/ffs-rate', [SMController::class, 'reportFfsRate'])->name('reports.ffs-rate');
        Route::get('/reports/ratings', [SMController::class, 'reportRatings'])->name('reports.ratings');
        Route::get('/reports/team', [SMController::class, 'reportTeam'])->name('reports.team');
        Route::get('/reports/labor-detail', [SMController::class, 'reportLaborDetail'])->name('reports.labor-detail');
        Route::get('/reports/dept', [SMController::class, 'reportDept'])->name('reports.dept');
        Route::get('/reports/bays', [SMController::class, 'reportBays'])->name('reports.bays');
        Route::get('/reports/cpus', [SMController::class, 'reportCpus'])->name('reports.cpus');
        Route::get('/reports/otd', [SMController::class, 'reportOtd'])->name('reports.otd');
        Route::get('/reports/wyw', [SMController::class, 'reportWyw'])->name('reports.wyw');
        Route::get('/reports/app-rate', [SMController::class, 'reportAppRate'])->name('reports.app-rate');
        Route::get('/reports/psfu', [SMController::class, 'reportPsfu'])->name('reports.psfu');
        Route::get('/reports/tus', [SMController::class, 'reportTus'])->name('reports.tus');
        Route::get('/reports/ffs-units', [SMController::class, 'reportFfsUnits'])->name('reports.ffs-units');
        Route::get('/reports/nvs', [SMController::class, 'reportNvs'])->name('reports.nvs');
        Route::get('/reports/top-labor', [SMController::class, 'reportTopLabor'])->name('reports.top-labor');
        Route::get('/reports/zero-invoices', [SMController::class, 'reportZeroInvoices'])->name('reports.zero-invoices');
        Route::get('/reports/warranty', [SMController::class, 'reportWarranty'])->name('reports.warranty');
        Route::get('/reports/sales-tax', [SMController::class, 'reportSalesTax'])->name('reports.sales-tax');
        Route::get('/reports/sublet-profit', [SMController::class, 'reportSubletProfit'])->name('reports.sublet-profit');
        Route::get('/reports/parts-timings', [SMController::class, 'reportPartsTimings'])->name('reports.parts-timings');
        Route::get('/reports/sa-parts', [SMController::class, 'reportSaParts'])->name('reports.sa-parts');
        Route::get('/reports/campaign', [SMController::class, 'reportCampaign'])->name('reports.campaign');
        Route::get('/reports/visits', [SMController::class, 'reportVisits'])->name('reports.visits');

        Route::prefix('master')->name('master.')->group(function () {
            Route::get('/bays', [SMController::class, 'masterBays'])->name('bays');
            Route::post('/bays/store', [SMController::class, 'masterBaysStore'])->name('bays.store');
            Route::post('/bays/update', [SMController::class, 'masterBaysUpdate'])->name('bays.update');
            Route::post('/bays/delete', [SMController::class, 'masterBaysDelete'])->name('bays.delete');
            Route::get('/labor', [SMController::class, 'masterLabor'])->name('labor');
            Route::post('/labor/store', [SMController::class, 'masterLaborStore'])->name('labor.store');
            Route::post('/labor/update', [SMController::class, 'masterLaborUpdate'])->name('labor.update');
            Route::post('/labor/delete', [SMController::class, 'masterLaborDelete'])->name('labor.delete');
            Route::get('/teams', [SMController::class, 'masterTeams'])->name('teams');
            Route::post('/teams/store', [SMController::class, 'masterTeamsStore'])->name('teams.store');
            Route::post('/teams/update', [SMController::class, 'masterTeamsUpdate'])->name('teams.update');
            Route::post('/teams/delete', [SMController::class, 'masterTeamsDelete'])->name('teams.delete');
            Route::get('/variants', [SMController::class, 'masterVariants'])->name('variants');
            Route::post('/variants/store', [SMController::class, 'masterVariantsStore'])->name('variants.store');
            Route::post('/variants/update', [SMController::class, 'masterVariantsUpdate'])->name('variants.update');
            Route::post('/variants/delete', [SMController::class, 'masterVariantsDelete'])->name('variants.delete');
        });
    });


// SALES / CRM MODULE
Route::middleware(['auth', 'role:SManager,IMCc,IT Manager'])
    ->prefix('sales')
    ->name('sales.')
    ->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/search', [SalesController::class, 'search'])->name('search');
        Route::get('/active-customers', [SalesController::class, 'activeCustomers'])->name('ac');
        Route::post('/active-customers/update-type', [SalesController::class, 'updateCustomerType'])->name('ac.update-type');
        Route::get('/vin', [SalesController::class, 'vin'])->name('vin');
        Route::match(['get', 'post'], '/vin-check', [SalesController::class, 'vinCheck'])->name('vin-check');
        Route::get('/uio', [SalesController::class, 'uio'])->name('uio');
        Route::post('/uio/update', [SalesController::class, 'uioUpdate'])->name('uio.update');
        Route::get('/campaigns', [SalesController::class, 'campaigns'])->name('campaigns');
        Route::post('/campaigns/store', [SalesController::class, 'campaignStore'])->name('campaigns.store');
        Route::post('/campaigns/toggle', [SalesController::class, 'campaignToggle'])->name('campaigns.toggle');
        Route::get('/problem-tray', [SalesController::class, 'problemTray'])->name('problem-tray');
        Route::post('/problem-tray/action', [SalesController::class, 'problemTrayAction'])->name('problem-tray.action');
        Route::get('/upload-vin', [SalesController::class, 'uploadVin'])->name('upload-vin');
        Route::post('/upload-vin/store', [SalesController::class, 'uploadVinStore'])->name('upload-vin.store');
        Route::get('/reports', [SalesController::class, 'reports'])->name('reports');
        Route::get('/reports-new', [SalesController::class, 'reportsNew'])->name('reports-new');
        Route::get('/jobcards', [SalesController::class, 'jobcards'])->name('jobcards');
        Route::get('/jc-changes', [SalesController::class, 'jcChanges'])->name('jc-changes');
        Route::get('/status/labor', [SalesController::class, 'statusLabor'])->name('status.labor');
        Route::get('/status/parts', [SalesController::class, 'statusParts'])->name('status.parts');
        Route::get('/status/sublet', [SalesController::class, 'statusSublet'])->name('status.sublet');
        Route::get('/status/consumable', [SalesController::class, 'statusConsumable'])->name('status.consumable');
        Route::get('/crm-reminder', [SalesController::class, 'followUpReminder'])->name('crm-reminder');
        Route::get('/parts-filter', [SalesController::class, 'partsFilter'])->name('parts-filter');
    });


// ACCOUNTANT MODULE
Route::middleware(['auth', 'role:FManager,Accountant,IT Manager'])
    ->prefix('finance/accountant')
    ->name('accountant.')
    ->group(function () {

        Route::get('/', [AccountantController::class, 'index'])->name('index');
        Route::get('/jobcard-status', [AccountantController::class, 'jobcardStatus'])->name('jobcard-status');
        Route::get('/reopen-jc', [AccountantController::class, 'reopenJc'])->name('reopen-jc');
        Route::post('/reopen-jc', [AccountantController::class, 'reopenJcProcess'])->name('reopen-jc.process');
        Route::get('/labor-request', [AccountantController::class, 'laborRequest'])->name('labor-request');
        Route::post('/labor-request', [AccountantController::class, 'laborRequestProcess'])->name('labor-request.process');
        Route::get('/labor-manual', [AccountantController::class, 'laborManual'])->name('labor-manual');
        Route::get('/labor-auto', [AccountantController::class, 'laborAuto'])->name('labor-auto');
        Route::post('/labor-auto', [AccountantController::class, 'laborAutoUpdate'])->name('labor-auto.update');
        Route::get('/new-user', [AccountantController::class, 'newUser'])->name('new-user');
        Route::post('/new-user', [AccountantController::class, 'newUserStore'])->name('new-user.store');
        Route::get('/new-part', [AccountantController::class, 'newPart'])->name('new-part');
        Route::post('/new-part', [AccountantController::class, 'newPartStore'])->name('new-part.store');
        Route::get('/service-search', [AccountantController::class, 'serviceSearch'])->name('service-search');
        Route::post('/service-search', [AccountantController::class, 'serviceSearchRedirect'])->name('service-search.redirect');
        Route::get('/parts-search', [AccountantController::class, 'partsSearch'])->name('parts-search');
        Route::post('/parts-search', [AccountantController::class, 'partsSearchRedirect'])->name('parts-search.redirect');
        Route::post('/cancel-part', [AccountantController::class, 'cancelPart'])->name('cancel-part');
        Route::get('/history', [AccountantController::class, 'history'])->name('history');
        Route::get('/finance-reports', [AccountantController::class, 'financeReports'])->name('finance-reports');
        Route::get('/parts-reports', [AccountantController::class, 'partsReports'])->name('parts-reports');
    });


// RECOVERY MODULE
Route::middleware(['auth', 'role:RecoveryExec,FManager,IT Manager'])
    ->prefix('finance/recovery')
    ->name('recovery.')
    ->group(function () {

        Route::get('/', [RecoveryController::class, 'index'])->name('index');
        Route::get('/add-debt', [RecoveryController::class, 'addDebt'])->name('add-debt');
        Route::post('/add-debt', [RecoveryController::class, 'addDebtStore'])->name('add-debt.store');
        Route::get('/add-credit', [RecoveryController::class, 'addCredit'])->name('add-credit');
        Route::post('/add-credit', [RecoveryController::class, 'addCreditStore'])->name('add-credit.store');
        Route::post('/add-credit/update', [RecoveryController::class, 'addCreditUpdate'])->name('add-credit.update');
        Route::match(['get', 'post'], '/search', [RecoveryController::class, 'search'])->name('search');
        Route::get('/search-adv', [RecoveryController::class, 'searchAdvanced'])->name('search-adv');
        Route::get('/search-name', [RecoveryController::class, 'searchName'])->name('search-name');
        Route::get('/customer-ledger', [RecoveryController::class, 'customerLedger'])->name('customer-ledger');
        Route::get('/clearance', [RecoveryController::class, 'clearance'])->name('clearance');
        Route::get('/history', [RecoveryController::class, 'history'])->name('history');
        Route::match(['get', 'post'], '/followup', [RecoveryController::class, 'followup'])->name('followup');
        Route::get('/not-contacted', [RecoveryController::class, 'notContacted'])->name('not-contacted');
        Route::get('/recovered', [RecoveryController::class, 'recovered'])->name('recovered');
        Route::get('/stats', [RecoveryController::class, 'stats'])->name('stats');
        Route::get('/dm-bills', [RecoveryController::class, 'dmBills'])->name('dm-bills');
        Route::get('/add-account', [RecoveryController::class, 'addAccount'])->name('add-account');
        Route::post('/add-account', [RecoveryController::class, 'addAccountStore'])->name('add-account.store');
        Route::get('/check-invoice', [RecoveryController::class, 'checkInvoice'])->name('check-invoice');
        Route::get('/email-status', [RecoveryController::class, 'emailStatus'])->name('email-status');
    });


// ACCOUNTS MODULE
Route::middleware(['auth', 'role:FManager,Accountant,IT Manager'])
    ->prefix('finance/accounts')
    ->name('accounts.')
    ->group(function () {

        Route::get('/', [AccountsController::class, 'index'])->name('index');
        Route::get('/cpv', [AccountsController::class, 'cpv'])->name('cpv');
        Route::post('/cpv', [AccountsController::class, 'cpv'])->name('cpv.post');
        Route::get('/cpv/items', [AccountsController::class, 'cpvItems'])->name('cpv.items');
        Route::post('/cpv/items', [AccountsController::class, 'cpvItems'])->name('cpv.items.post');
        Route::get('/crv', [AccountsController::class, 'crv'])->name('crv');
        Route::post('/crv', [AccountsController::class, 'crv'])->name('crv.post');
        Route::get('/crv/items', [AccountsController::class, 'crvItems'])->name('crv.items');
        Route::post('/crv/items', [AccountsController::class, 'crvItems'])->name('crv.items.post');
        Route::get('/bpv', [AccountsController::class, 'bpv'])->name('bpv');
        Route::post('/bpv', [AccountsController::class, 'bpv'])->name('bpv.post');
        Route::get('/bpv/items', [AccountsController::class, 'bpvItems'])->name('bpv.items');
        Route::post('/bpv/items', [AccountsController::class, 'bpvItems'])->name('bpv.items.post');
        Route::get('/brv', [AccountsController::class, 'brv'])->name('brv');
        Route::post('/brv', [AccountsController::class, 'brv'])->name('brv.post');
        Route::get('/brv/items', [AccountsController::class, 'brvItems'])->name('brv.items');
        Route::post('/brv/items', [AccountsController::class, 'brvItems'])->name('brv.items.post');
        Route::get('/jv', [AccountsController::class, 'jv'])->name('jv');
        Route::post('/jv', [AccountsController::class, 'jv'])->name('jv.post');
        Route::get('/jv/items', [AccountsController::class, 'jvItems'])->name('jv.items');
        Route::post('/jv/items', [AccountsController::class, 'jvItems'])->name('jv.items.post');
        Route::match(['get', 'post'], '/pending-vouchers', [AccountsController::class, 'pendingVouchers'])->name('pending-vouchers');
        Route::match(['get', 'post'], '/authenticate', [AccountsController::class, 'authenticate'])->name('authenticate');
        Route::match(['get', 'post'], '/reopened-vouchers', [AccountsController::class, 'reopenedVouchers'])->name('reopened-vouchers');
        Route::match(['get', 'post'], '/search', [AccountsController::class, 'search'])->name('search');
        Route::match(['get', 'post'], '/coa', [AccountsController::class, 'coa'])->name('coa');
        Route::match(['get', 'post'], '/add-gl', [AccountsController::class, 'addGL'])->name('add-gl');
        Route::match(['get', 'post'], '/add-gsl', [AccountsController::class, 'addGSL'])->name('add-gsl');
        Route::match(['get', 'post'], '/add-sh', [AccountsController::class, 'addSH'])->name('add-sh');
        Route::post('/report/trial-balances', [AccountsController::class, 'reportTrialBalances'])->name('report.trial-balances');
        Route::post('/report/trial-bal-gl', [AccountsController::class, 'reportTrialBalGL'])->name('report.trial-bal-gl');
        Route::post('/report/gsl-report', [AccountsController::class, 'reportGslReport'])->name('report.gsl-report');
        Route::post('/report/voucher-type', [AccountsController::class, 'reportVoucherType'])->name('report.voucher-type');
        Route::post('/report/profit-loss', [AccountsController::class, 'reportProfitLoss'])->name('report.profit-loss');
        Route::post('/report/profit-loss-dept', [AccountsController::class, 'reportProfitLossDept'])->name('report.profit-loss-dept');
        Route::post('/report/profit-loss-overall', [AccountsController::class, 'reportProfitLossOverall'])->name('report.profit-loss-overall');
        Route::post('/report/cash-flow', [AccountsController::class, 'reportCashFlow'])->name('report.cash-flow');
        Route::post('/report/cash-flow-gsl', [AccountsController::class, 'reportCashFlowGsl'])->name('report.cash-flow-gsl');
    });
