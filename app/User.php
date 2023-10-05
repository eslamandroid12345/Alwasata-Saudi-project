<?php

namespace App;

use App\Models\Classification;
use App\Models\RequestHistory;
use App\Models\RequestSource;
use App\Models\QualityRequestNeedTurned;
use App\Traits\HasMany\HasManyWaitingRequestNotification;
use App\Traits\HasPushTokensTrait;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use MyHelpers;
use NotificationChannels\WebPush\HasPushSubscriptions;

//to take date

class User extends Authenticatable
{
    use Notifiable, HasPushSubscriptions, HasApiTokens;
    use HasPushTokensTrait;
    use HasManyWaitingRequestNotification;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'code',
        'locale',
        'username',
        'password',
        'role',
        'manager_id',
        'funding_manager_id',
        'mortgage_manager_id',
        'bank_id',
        'subdomain',
        'req_count',
        'pen_count',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['roleName'];
   /* protected $with = ['performances'];*/

    public function scopePerformanceKey(Builder $builder,$startData,$endDate,$key){
        $this->performances()->whereBetween('today_date', [$startData, $endDate])->sum($key);
    }
    public function performances()
    {
        return $this->hasMany(DailyPerformances::class, 'user_id');
    }
    public function servays()
    {
        return $this->hasMany(servay::class, 'user_id');
    }

    public function logs()
    {
        return $this->hasMany(DailyLogs::class, 'user_id');
    }

    public function repeated()
    {
        return $this->hasMany(CollaboratorRequest::class, 'user_id')
            ->where("type","repeated");
    }
    //get all sales agents for (auth user::Sales Manager role)

    public static function lastMessage($sender)
    {
        //        $query = Message::where('to', Auth::id())->where('from',$sender)->latest('created_at')->first();
        //
        //        if ($query)
        //        return $query;
        //        else
        //        return '';

        //        $serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/firebaseKey.json');
        //        $firebase = (new Factory)
        //            ->withServiceAccount($serviceAccount)
        //            ->withDatabaseUri('https://flash-chat-55470.firebaseio.com/')
        //            ->create();
        //        $database = $firebase->getDatabase();
        //        $ref = $database->getReference('messages');
        //        $chats = $ref->equalTo('sender_id',$sender)
        //            ->equalTo('receiver_id',Auth::id())
        //            ->getValue();
        //        dd($chats);
        //        if($chats == null){
        //            $messages[] = [];
        //            return view('Customer.chatBodyPage', compact('messages','model_type'));
        //        }else{
        //            foreach ($chats as $chat)
        //            {
        //                $messages[] = $chat;
        //            }
        //            return view('Customer.chatBodyPage', compact('messages','model_type'));
        //        }
    }

    //get all General Managers for (auth user :: funding Manager role)

    public static function username($id)
    {
        $user = DB::table('users')->where('id', $id)->first();
        if ($user) {
            return $user->name;
        }
        return '';
    }

    //get all funding Managers for (auth user :: funding Manager role)

    public function salesAgents()
    {
        return $this->belongsTo('App\User', 'manager_id');
    }

    public function generalManagers()
    {
        return $this->belongsTo('App\User', 'funding_mnager_id');
    }

    public function fundingManagers()
    {
        return $this->belongsTo('App\User', 'funding_mnager_id');
    }

    public function customers()
    {
        return $this->hasMany(customer::class);
    }

    public function task_from()
    {
        return $this->hasMany(task::class, 'user_id');
    }

    public function reqRecords()
    {
        return $this->hasMany(reqRecord::class);
    }

    public function quality_request_need_turneds()
    {
        return $this->hasMany(QualityRequestNeedTurned::class, 'quality_id');
    }
    public function quality_request_need_turneds_count($status = null)
    {
        $quality_request_need_turneds = $this->quality_request_need_turneds();
        if ($status != null && in_array([0,1,2],$status))
            $quality_request_need_turneds =$quality_request_need_turneds->where('status',$status);
        return $quality_request_need_turneds->count();
    }


    public function complete($startDate, $endDate)
    {
        $allReqs = $this->requests()->whereNotIn('statusReq', [0, 1, 2, 4]);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function requests()
    {
        return $this->hasMany(request::class);
    }

    public function received(){
        return $this->hasMany(request::class)->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->leftjoin('quality_reqs', 'quality_reqs.req_id', '=', 'requests.id')->where(function ($query) {

                $query->where(function ($query) {
                    $query->whereIn('statusReq', [0, 1, 4, 31]);
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 19);
                    $query->where('requests.type', 'رهن-شراء');
                    $query->where('requests.isSentSalesAgent', 1);
                });

                $query->orWhere(function ($query) {
                    $query->whereIn('prepayments.payStatus', [4, 3]);
                    $query->where('requests.type', 'شراء-دفعة');
                    $query->where('prepayments.isSentSalesAgent', 1);
                });
            })->where('requests.is_canceled', 0)->where('requests.is_followed', 0)->where('requests.is_stared', 0)->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id', '=', 'requests.real_id')->join('fundings', 'fundings.id', '=',
                'requests.fun_id')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'customers.mobile', 'customers.app_downloaded',
                'requests.class_id_quality as is_quality_recived')
            ->dateForAgentFilter(request('startdate'),request('enddate'));
    }
    public function completes(){
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))
            ->whereNotIn('statusReq', [0, 1, 2, 4]);
    }
    public function star(){
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))
            ->where('is_stared', 1)->whereIn('statusReq', [0, 1, 4]);
    }
    public function following(){
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))
            ->where('is_followed', 1)->whereIn('statusReq', [0, 1, 4]);
    }
    public function archived(){
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))
            ->where('statusReq', 2);
    }

    public function new_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 0);
    }
    public function open_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 1);
    }
    public function archive_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 2);
    }
    public function waiting_sm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 3);
    }
    public function rejected_sm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 4);
    }
    public function archive_sm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 5);
    }
    public function waiting_fm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 6);
    }
    public function rejected_fm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 7);
    }
    public function archive_fm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 8);
    }
    public function waiting_mm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 9);
    }
    public function rejected_mm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 10);
    }
    public function archive_mm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 11);
    }
    public function waiting_gm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 12);
    }
    public function rejected_gm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 13);
    }
    public function archive_gm_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 14);
    }
    public function canceled_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 15);
    }
    public function completed_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('statusReq', 16);
    }
    public function funding_report_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('isUnderProcFund', 1);
    }
    public function mortgage_report_status() {
        return $this->hasMany(request::class)->dateForAgentFilter(request('startdate'),request('enddate'))->where('isUnderProcMor', 1);
    }


    public function classifications()
    {
        return $this->belongsToMany(
            Classification::class,
            'requests',
            'user_id',
            'class_id_agent'
        );
    }

    public function collaborates()
    {
        return $this->belongsToMany(
            __CLASS__,
            'requests',
            'user_id',
            'collaborator_id'
        );
    }

    public function sources()
    {
        return $this->belongsToMany(
            RequestSource::class,
            'requests',
            'user_id',
            'source'
        );
    }


    public function cancel()
    {
        return $this->requests()->where('statusReq', 15)->count();
    }

    public function noRequest($startDate, $endDate)
    {

        $allReqs = $this->requests();

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();

    }

    public function newRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 0);

        //if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
        if ($startDate) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        //if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
        if ($endDate) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();

    }

    public function openRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 1);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function archivedInSalesAgentRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 2);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function watingForSalesManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 3);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function rejectedFromSalesManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 4);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function archivedInSalesManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 5);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function watingForFundingManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 6);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function rejectedFromFundingManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 7);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function archivedInFundingManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 8);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function watingForMortgageManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 9);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function rejectedFromMortgageManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 10);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function archivedInMortgageManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 11);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function watingForGeneralManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 12);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function rejectedFromGeneralManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 13);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function archivedInGeneralManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 14);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function canceledFromGeneralManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 15);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function completedInGeneralManagerRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('statusReq', 16);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function fundingReportRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('isUnderProcFund', 1);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function mortgageReportRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('isUnderProcMor', 1);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function followingRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('is_followed', 1)->whereIn('statusReq', [0, 1, 4]);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function starRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('is_stared', 1)->whereIn('statusReq', [0, 1, 4]);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();

    }

    public function receivedRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('is_followed', 0)->where('is_stared', 0)->whereIn('statusReq', [0, 1, 4]);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function otaredRequest($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('collaborator_id', 17);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();

    }

    public function classRequest($classID, $startDate, $endDate)
    {

        $allReqs = $this->requests()->where('requests.class_id_agent', $classID);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function frindReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 3);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function telphonReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 5);

        //if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
        if ($startDate) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        //if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
        if ($endDate) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function missedCallReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 1);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function adminReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 4);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function webAskFundingReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 7);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function webAskConsReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 8);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function webCalculaterReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 9);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function collobratorWithoutReqSource($startDate, $endDate) //All colls without tmweel and otared
    {

        $allReqs = $this->requests()->where('source', 2)->where('collaborator_id', '!=', 17)->where('collaborator_id', '!=', 77);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function otaredReqSource($startDate, $endDate) //All colls without tmweel and otared
    {

        $allReqs = $this->requests()->where('source', 2)->where('collaborator_id', 17);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function tamweelkReqSource($startDate, $endDate) //All colls without tmweel and otared
    {

        $allReqs = $this->requests()->where('source', 2)->where('collaborator_id', 77);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function callNotRecordReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 10);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function app_askconsReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 11);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function app_calcReqSource($startDate, $endDate)
    {

        $allReqs = $this->requests()->where('source', 12);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function hasbahNetReqSource($startDate, $endDate)
    {
        //$allReqs = $this->requests()->where('collaborator_id', 269);
        $allReqs = $this->requests()->where('source', \App\Models\Request::HASBAH_SOURCE);
        //if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
        if ($startDate) {
            $allReqs = $allReqs->whereDate('created_at', '>=', \Illuminate\Support\Carbon::make($startDate));
        }
        //if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
        if ($endDate) {
            $allReqs = $allReqs->whereDate('created_at', '<=', \Illuminate\Support\Carbon::make($endDate));
        }
        return $allReqs->count();
    }

    public function hasbahNetNotReqSource($startDate, $endDate)
    {

        //$allReqs = $this->requests()->where('collaborator_id', 288);
        $allReqs = $this->requests()->where('source', \App\Models\Request::HASBAH_SOURCE_NOT_COMPLETE);

        //if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
        //    $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        //}
        //if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
        //    $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        //}
        if ($startDate) {
            $allReqs = $allReqs->whereDate('created_at', '>=', \Illuminate\Support\Carbon::make($startDate));
        }
        //if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
        if ($endDate) {
            $allReqs = $allReqs->whereDate('created_at', '<=', \Illuminate\Support\Carbon::make($endDate));
        }
        return $allReqs->count();
    }

    public function movedRequestsFrom($startDate, $endDate)
    {

        $allReqs = $this->request_histories_from()->where('title', 'نقل الطلب');

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function request_histories_from()
    {
        return $this->hasMany(requestHistory::class, 'user_id');
    }

    public function movedRequestsTo($startDate, $endDate)
    {

        $allReqs = $this->request_histories_to()->where('title', 'نقل الطلب');

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function request_histories_to()
    {
        return $this->hasMany(requestHistory::class, 'recive_id');
    }

    public function movedRequestsSent($startDate, $endDate)
    {

        $allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where('statusReq', '>', 2)->whereNotIn('statusReq', [16, 26])->where('requests.class_id_agent', '!=', 58);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function movedRequestsCompleted($startDate, $endDate)
    {

        $allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where(function ($query) {
            $query->whereIn('statusReq', [16, 26])->orWhere('requests.class_id_agent', 58);
        });

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function movedRequests_AskReq($startDate = null, $endDate = null)
    {
        //$allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where('content', 'اطلب عملاء')->where(function ($query) {
        //    $query->whereIn('statusReq', [16, 26])->orWhere('requests.class_id_agent', 58)->orWhere('statusReq', '>', 2);
        //});
        //$requests = \App\Models\Request::whereHas('requestHistories', function (Builder $builder) use ($startDate, $endDate) {
        //    $builder
        //        ->where([
        //            'user_id' => $this->id,
        //            'title'   => \App\Models\RequestHistory::TITLE_MOVE_REQUEST,
        //            'content' => \App\Models\RequestHistory::CONTENT_AGENT_ASK_REQUEST,
        //        ]);
        //
        //    $startDate && $builder->whereDate('history_date', '>=', $startDate);
        //    $endDate && $builder->whereDate('history_date', '<=', $endDate);
        //    return $builder;
        //});

        $allReqs = $this->request_histories_from()
            ->where('title', \App\Models\RequestHistory::TITLE_MOVE_REQUEST)
            ->where('content', \App\Models\RequestHistory::CONTENT_AGENT_ASK_REQUEST);

        if ($startDate) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        //return $requests->count();
        return $allReqs->count();
    }

    public function movedRequestsAskRequestTransferBasket($startDate = null, $endDate = null)
    {
        $allReqs = $this->request_histories_from()
            ->where('title', \App\Models\RequestHistory::TITLE_MOVE_REQUEST)
            ->where('content', \App\Models\RequestHistory::CONTENT_ASK_REQUEST_TRANSFER_BASKET);

        if ($startDate) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function movedRequests_Admin($startDate, $endDate)
    {

        $allReqs = $this->request_histories_from()
            ->where('title', \App\Models\RequestHistory::TITLE_MOVE_REQUEST);

        //$allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where('content', 'مدير النظام')->where(function ($query) {
        //    $query->whereIn('statusReq', [16, 26])->orWhere('requests.class_id_agent', 58)->orWhere('statusReq', '>', 2);
        //});

        if ($startDate) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function movedRequests_NeedActionTable($startDate, $endDate)
    {

        //$allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where('content', 'سلة طلبات بحاجة للتحويل')->where(function ($query) {
        //    $query->whereIn('statusReq', [16, 26])->orWhere('requests.class_id_agent', 58)->orWhere('statusReq', '>', 2);
        //});
        $allReqs = $this->request_histories_from()
            ->where('title', \App\Models\RequestHistory::TITLE_MOVE_REQUEST)
            ->where('content', \App\Models\RequestHistory::CONTENT_EXISTED_IN_NEED_ACTION);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function movedRequests_ArchiveBacket($startDate, $endDate)
    {

        //$allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where('content', 'سلة الأرشيف')->where(function ($query) {
        //    $query->whereIn('statusReq', [16, 26])->orWhere('requests.class_id_agent', 58)->orWhere('statusReq', '>', 2);
        //});
        $allReqs = $this->request_histories_from()
            ->where('title', \App\Models\RequestHistory::TITLE_MOVE_REQUEST)
            ->where('content', \App\Models\RequestHistory::CONTENT_ARCHIVED_BASKET);

        if ($startDate) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function movedRequests_ArchiveAgent($startDate, $endDate)
    {

        $allReqs = $this->request_histories_from()
            ->where('title', \App\Models\RequestHistory::TITLE_MOVE_REQUEST)
            ->where('content', \App\Models\RequestHistory::CONTENT_ARCHIVED_AGENT);
        //$allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where('content', 'استشاري مؤرشف')->where(function ($query) {
        //    $query->whereIn('statusReq', [16, 26])->orWhere('requests.class_id_agent', 58)->orWhere('statusReq', '>', 2);
        //});

        if ($startDate) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function movedRequests_Pending($startDate, $endDate)
    {

        $allReqs = $this->request_histories_from()
            ->where('title', \App\Models\RequestHistory::TITLE_MOVE_REQUEST)
            ->where('content', \App\Models\RequestHistory::CONTENT_ARCHIVED_AGENT);
        $allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where('content', 'الطلبات المعلقة')->where(function ($query) {
            $query->whereIn('statusReq', [16, 26])->orWhere('requests.class_id_agent', 58)->orWhere('statusReq', '>', 2);
        });

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function movedRequests_Undefined($startDate, $endDate)
    {

        $allReqs = $this->request_histories_from()->join('requests', 'requests.id', 'request_histories.req_id')->where('title', 'نقل الطلب')->where('content', null)->where(function ($query) {
            $query->whereIn('statusReq', [16, 26])->orWhere('requests.class_id_agent', 58)->orWhere('statusReq', '>', 2);
        });

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('history_date', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function taskTo($startDate, $endDate)
    {

        $allReqs = $this->task_to()->leftjoin('users as user', 'user.id', 'tasks.user_id')->where('user.role', 5);
        // ->where('status',3);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function task_to()
    {
        return $this->hasMany(task::class, 'recive_id');
    }

    //============================unreceived tasks =================================
    public function agent_tasks(){
        //status of unreceived task should be [0 or 1 or 2 or 3]  or what ???
        //and in task table or task_content table ???
        return $this->task_to()->whereIn('tasks.status',[0,1,2]);

    }
    //================================================================================

    public function completedTaskTo($startDate, $endDate)
    {

        $allReqs = $this->task_to()->leftjoin('users as user', 'user.id', 'tasks.user_id')->where('user.role', 5)->where('tasks.status', 3);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function notCompletedTask($startDate, $endDate)
    {

        $allReqs = $this->task_to()->leftjoin('users as user', 'user.id', 'tasks.user_id')->where('user.role', 5)->where('tasks.status', 4);

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('tasks.created_at', '<=', $endDate);
        }

        return $allReqs->count();
    }

    public function updateRequest($startDate, $endDate)
    {

        $allReqs = $this->requests();

        if ($startDate != null && ($endDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '>=', $startDate);
        }
        if ($endDate != null && ($startDate == null || $startDate <= $endDate)) {
            $allReqs = $allReqs->whereDate('created_at', '<=', $endDate);
        }

        $allReqs = $allReqs->get();

        $avgOfEachRequest[] = [];
        $i = 0;

        foreach ($allReqs as $request) {

            $reqRecord = DB::table('req_records')
                //->join('requests', 'requests.id', 'req_records.req_id')
                ->where('req_id', $request->id)->select(DB::raw('AVG(TIME_TO_SEC(updateValue_at)) as avgValue'))->pluck('avgValue');
            // ->sum('avgValue');

            $reqInfo = DB::table('requests')->where('id', $request->id)->first();

            $removetime = $reqInfo->remove_from_archive;

            if ($removetime == null && $reqInfo->add_to_archive != null) {
                $removetime = Carbon::now('Asia/Riyadh');
            }

            if ($reqInfo->add_to_archive == null && $reqInfo->remove_from_archive == null) {
                $archTime[0] = 0;
            }
            else {
                $archTime = DB::table('requests')->where('id', $request->id)->select(DB::raw("(TIME_TO_SEC(TIMEDIFF('$removetime', add_to_archive))) AS timediff"))->pluck('timediff');
            }

            $diff = 0;
            if ($archTime[0] > $reqRecord[0]) {
                $diff = $archTime[0] - $reqRecord[0];
            }
            else {
                $diff = $reqRecord[0] - $archTime[0];
            }

            if ($reqRecord != null) {
                $avgOfEachRequest[$i++] = (float) $diff;
            }
            else {
                $avgOfEachRequest[$i++] = 0;
            }

        }

        if ($allReqs->count() != 0) {
            $sumValue = array_sum($avgOfEachRequest);
            $avgValue = $sumValue / $allReqs->count();
        }
        else {
            $avgValue = 0;
        }

        $avg = gmdate("H:i:s", $avgValue);

        return $avg;
    }

    public function to_messages()
    {
        return $this->morphMany(Message::class, 'to', 'to_type', 'to');
    }

    public function getUnreadAttribute()
    {
        $user_type = auth('customer')->user() ? 'App\customer' : 'App\User';
        return $this->from_messages()->where('to', auth()->id())->where('to_type', $user_type)->where('is_read', 0)->count();
    }

    public function from_messages()
    {
        return $this->morphMany(Message::class, 'from', 'from_type', 'from');
    }

    public function reminders()
    {
        return $this->hasMany(notification::class, 'recived_id')->where('receiver_type', '=', 'web')->where('request_type', '=', 1)->whereDate('reminder_date', '>', Carbon::now()->subDays(30))->whereNotNull('reminder_date');
    }


    public function getRoleNameAttribute()
    {
        switch ($this->role) {
            case 0:
                $role = 'Sales Agent';
                break;
            case 1:
                $role = 'Sales Manager';
                break;
            case 2:
                $role = 'Funding Manager';
                break;
            case 3:
                $role = 'Mortgage Manager';
                break;
            case 4:
                $role = 'General Manager';
                break;
            case 5:
                $role = 'Quality User';
                break;
            case 9:
                $role = 'Quality Manager';
                break;
            case 6:
                $role = 'Collaborator';
                break;
            case 7:
                $role = 'Admin';
                break;
            case 8:
                $role = 'Accountant';
                break;
            case 11:
                $role = 'Training';
                break;
            case 12:
                $role = 'Hr';
                break;  case 20:
            $role = $this->subdomain ?? '-';
            break;
            default:
                $role = 'Undefined';
                break;
        }
        $data = $this->role != 20 ? MyHelpers::admin_trans(auth()->user()->id, $role) : $role;
        return $data;
    }

    public function profile()
    {
        return $this->hasOne('App\Employee');
    }

    public function phones()
    {
        return $this->hasMany('App\CustomersPhone', 'user_id', 'id');
    }

    public function agents()
    {
        return $this->hasMany('App\user_collaborator', 'collaborato_id', 'id');
    }

    public function files()
    {
        return $this->belongsTo('App\EmployeeFile');
    }

    /**
     * @return HasMany
     */
    public function receivedRequestHistories(): HasMany
    {
        return $this->hasMany(RequestHistory::class, 'recive_id');
    }
    public function trainings(){
        return $this->hasMany(TrainingAndAgent::class, 'training_id');
    }

    public function friend_source() {
        return $this->hasMany(request::class)
            ->dateFilter(request('startdate'),request('enddate'))
            ->where('source', 3);
    }
    public function quality_reqs()
    {
        return $this->hasMany("App\quality_req","user_id")
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id');
    }


    public function quality_reqs_followed()
    {
        return $this->hasMany("App\quality_req","user_id")
            ->where('allow_recive', 1)
            ->whereIn('status', [0, 1, 2])
            ->where('is_followed', 1)
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ;
    }


    public function quality_reqs_completed()
    {
        return $this->hasMany("App\quality_req","user_id")
            ->where('allow_recive', 1)
            ->where('status', 3)
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ;
    }


    public function quality_reqs_recevied(){
        return $this->hasMany("App\quality_req","user_id")
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('quality_reqs.allow_recive', 1)
            ->whereIn('quality_reqs.status', [0, 1, 2])
            ->where('quality_reqs.is_followed', 0)

            ;
    }
    public function quality_reqs_arch(){
        return $this->hasMany("App\quality_req","user_id")
            ->where('allow_recive', 1)
            ->where('status', 5)
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ;
    }

    public function areas()
    {
        return $this->hasMany("App\CollaboratorProfile")
            ->where("key","area_id");
    }

    public function cities()
    {
        return $this->hasMany("App\CollaboratorProfile")
            ->where("key","city_id");
    }

    public function districts()
    {
        return $this->hasMany("App\CollaboratorProfile")
            ->where("key","district_id");
    }

    public function direction()
    {
        return $this->hasOne("App\CollaboratorProfile")
            ->where("key","direction");
    }
}
