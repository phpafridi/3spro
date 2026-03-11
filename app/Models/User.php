<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'login_id',
        'Name',
        'password',
        'email',
        'last_login',
        'mobile',
        'dept',
        'position',
        'last_logout'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'last_logout' => 'datetime'
    ];

    // Relationships - Appointments
    public function createdAppointments()
    {
        return $this->hasMany(CrAppointment::class, 'CRO', 'login_id');
    }

    public function servicedAppointments()
    {
        return $this->hasMany(CrAppointment::class, 'SA', 'login_id');
    }

    // Relationships - Followup Calls
    public function pmCalls()
    {
        return $this->hasMany(CrPmcall1::class, 'username', 'login_id');
    }

    public function ffsCalls1()
    {
        return $this->hasMany(CrFfscall1::class, 'username', 'login_id');
    }

    public function ffsCalls2()
    {
        return $this->hasMany(CrFfscall2::class, 'username', 'login_id');
    }

    public function dormantFollowups()
    {
        return $this->hasMany(CrDormantfollowup::class, 'username', 'login_id');
    }

    // Relationships - CR Tables
    public function nvdRecords()
    {
        return $this->hasMany(CrNvd::class, 'cro', 'login_id');
    }

    public function problemTrays()
    {
        return $this->hasMany(CrProblemTray::class, 'cro', 'login_id');
    }

    public function psfuRecords()
    {
        return $this->hasMany(CrPsfu::class, 'CRO', 'login_id');
    }

    public function smsRecords()
    {
        return $this->hasMany(CrSms::class, 'cro', 'login_id');
    }

    // Relationships - Job Cards
    public function jobCards()
    {
        return $this->hasMany(Jobcard::class, 'SA', 'login_id');
    }

    public function diagnosedJobs()
    {
        return $this->hasMany(Jobcard::class, 'Diagnose_by', 'login_id');
    }

    // Relationships - Finance
    public function glAccounts()
    {
        return $this->hasMany(FinGl::class, 'user', 'login_id');
    }

    public function gslAccounts()
    {
        return $this->hasMany(FinGsl::class, 'gsl_user', 'login_id');
    }

    public function voucherMasters()
    {
        return $this->hasMany(FinVchMas::class, 'UserName', 'login_id');
    }

    public function voucherChildren()
    {
        return $this->hasMany(FinVchChld::class, 'user', 'login_id');
    }

    // Relationships - Parts
    public function parts()
    {
        return $this->hasMany(PPart::class, 'user', 'login_id');
    }

    public function purchaseInvoices()
    {
        return $this->hasMany(PPurchInv::class, 'user', 'login_id');
    }

    public function saleInvoices()
    {
        return $this->hasMany(PSaleInv::class, 'user', 'login_id');
    }

    public function purchaseReturns()
    {
        return $this->hasMany(PPurchReturn::class, 'user', 'login_id');
    }

    public function saleReturns()
    {
        return $this->hasMany(PSaleReturn::class, 'user', 'login_id');
    }

    public function jobberPayments()
    {
        return $this->hasMany(PJobberPayment::class, 'user', 'login_id');
    }

    public function techIncentives()
    {
        return $this->hasMany(PTechIncentive::class, 'user', 'login_id');
    }

    // Relationships - Recovery
    public function recoveryAccounts()
    {
        return $this->hasMany(RecovAccount::class, 'r_officer', 'login_id');
    }

    public function recoveryCredits()
    {
        return $this->hasMany(RecovCred::class, 'user', 'login_id');
    }

    public function recoveryDebts()
    {
        return $this->hasMany(RecovDebt::class, 'user', 'login_id');
    }

    // Relationships - Service
    public function campaigns()
    {
        return $this->hasMany(SCampaign::class, 'user', 'login_id');
    }

    public function estimates()
    {
        return $this->hasMany(SEstimate::class, 'user', 'login_id');
    }

    public function laborRequests()
    {
        return $this->hasMany(SLaborRequest::class, 'who_req', 'login_id');
    }

    public function acceptedLaborRequests()
    {
        return $this->hasMany(SLaborRequest::class, 'who_acept', 'login_id');
    }

    public function consumableList()
    {
        return $this->hasMany(SListConsumble::class, 'user', 'login_id');
    }

    public function newParts()
    {
        return $this->hasMany(SNewPart::class, 'User', 'login_id');
    }

    public function unclosedJobsSM()
    {
        return $this->hasMany(SUnclosedJc::class, 'SM', 'login_id');
    }

    public function unclosedJobsFinance()
    {
        return $this->hasMany(SUnclosedJc::class, 'fin_guy', 'login_id');
    }

    public function uploadedFrames()
    {
        return $this->hasMany(SUploadedFram::class, 'Assign_to', 'login_id');
    }

    public function uploadLists()
    {
        return $this->hasMany(SUploadListname::class, 'user', 'login_id');
    }

    public function vendorLists()
    {
        return $this->hasMany(SVendorList::class, 'addedby', 'login_id');
    }

    public function warranties()
    {
        return $this->hasMany(SWarranty::class, 'user', 'login_id');
    }

    public function tsureServices()
    {
        return $this->hasMany(TsureService::class, 'SA', 'login_id');
    }

    public function tsureServicesCreated()
    {
        return $this->hasMany(TsureService::class, 'user', 'login_id');
    }

    public function tsFollowups()
    {
        return $this->hasMany(TsFollowup::class, 'user', 'login_id');
    }

    public function uioRecords()
    {
        return $this->hasMany(Uio::class, 'user', 'login_id');
    }

    public function insuranceCompanies()
    {
        return $this->hasMany(SInsuranceCompany::class, 'addedby', 'login_id');
    }
}
