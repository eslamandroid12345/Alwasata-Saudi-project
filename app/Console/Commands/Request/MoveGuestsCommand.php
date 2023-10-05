<?php

namespace App\Console\Commands\Request;

use App\CustomersPhone;
use App\GuestCustomer;
use App\Helpers\MyHelpers;
use App\Model\PendingRequest;
use App\Model\RequestSearching;
use App\Models\Customer;
use App\Models\RequestHistory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MoveGuestsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'request:move-guests';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move hasbah requests';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return void
     * @throws \Exception
     */
    public function handle(): void
    {

        $moveCondition = DB::table('settings')->where('option_name', 'hasbah_net_movment')->first();
        $moveHoursCondition = DB::table('settings')->where('option_name', 'hasbah_net_movment_hours')->first();
        if ($moveCondition->option_value != 1 || !$moveHoursCondition->option_value) {
            return;
        }
        # enable movement from hasbah.net automatically
        //if ($moveCondition->option_value == 1 && $moveHoursCondition->option_value != null) {
        $hours = $moveHoursCondition->option_value;
        //$now = Carbon::now()->subHours($hours);
        //$guest_users_reqs = GuestCustomer::whereDate('created_at', '>=', $now)->get();
        $guest_users_reqs = GuestCustomer::oldest('created_at')->get();
        //dd([$hours,$now->toDateTimeString(),now()->toDateTimeString(),$guest_users_reqs->count()]);
        //$req_source = 2;
        $collaborator_id = null;
        foreach ($guest_users_reqs as $reqInfo) {
            //dd($reqInfo->created_at->addHours($hours),$guest_users_reqs->count());
            if ($reqInfo->created_at->addHours($hours) > now()) {
                continue;
            }
            //dd($reqInfo->created_at, now()->subHours($hours),$guest_users_reqs->count());
            //dd([$reqInfo->created_at]);
            if ($reqInfo->status == 1) {
                $req_source = \App\Models\Request::HASBAH_SOURCE;
                //$collaborator_id = null;
            }
            else {
                //$collaborator_id = 288;
                $req_source = \App\Models\Request::HASBAH_SOURCE_NOT_COMPLETE;
            }
            //$collaborator_id = $reqInfo->status == 1 ? 269 : 288;
            $customer = null;
            $customerID = DB::table('customers')->where('mobile', $reqInfo->mobile)->first();
            if (!$customerID) {
                $customer = DB::table('customers_phones')->where('mobile', $reqInfo->mobile)->first();
            }

            if (!$customer && !$customerID) {
                $input['birth_hijri'] = $reqInfo->birth_date;
                $input['salary'] = $reqInfo->salary;
                $input['work'] = $reqInfo->work;
                $input['birth_date'] = null;
                $is_approved = MyHelpers::check_is_request_acheive_condition($input);
                $user_id = getLastAgentOfDistribution();
                $customer_email_check = \App\Models\Customer::query()->where('email', $reqInfo->email)->first();
                $customer_email = !$customer_email_check ? $reqInfo->email : $customer_email_check->email;
                $work = null;
                $getworkValue = DB::table('work_sources')->where('id', $reqInfo->work)->first();
                if ($getworkValue) {
                    $work = $getworkValue->value;
                }

                //add it once use insertGetId
                $customer = Customer::updateOrCreate([
                    'mobile'           => $reqInfo->mobile,
                ],[
                    'user_id'          => $user_id,
                    'name'             => $reqInfo->name,
                    'mobile'           => $reqInfo->mobile,
                    'email'            => $customer_email,
                    'welcome_message'        => 2,
                    'birth_date_higri' => $reqInfo->birth_date,
                    'work'             => $work,
                    'salary'           => $reqInfo->salary,
                    'created_at'       => now('Asia/Riyadh'),
                ]);
                //insertGetId : insertGetId method to insert a record and then retrieve the ID
                //add it once use insertGetId

                if ($customer->wasRecentlyCreated === true) {
                    $joinID = DB::table('joints')->insertGetId([
                        // 'customer_id' => $customer->id,
                        'created_at' => now('Asia/Riyadh'),
                    ]);

                    $realID = DB::table('real_estats')->insertGetId([
                        //'customer_id' => $customer->id,
                        'created_at' => now('Asia/Riyadh'),
                    ]);

                    $funID = DB::table('fundings')->insertGetId([
                        // 'customer_id' => $customer->id,
                        'created_at' => now('Asia/Riyadh'),
                    ]);

                    $reqDate = now('Asia/Riyadh');
                    $searching_id = RequestSearching::create()->id;
                    if ($isApproved) {
                        $reqID = DB::table('requests')->insertGetId([
                            'req_date'        => $reqDate,
                            'created_at'      => $reqInfo->created_at,
                            'searching_id'    => $searching_id,
                            'user_id'         => $user_id,
                            'customer_id'     => $customer->id,
                            'collaborator_id' => $collaborator_id,
                            'source'          => $req_source,
                            'joint_id'        => $joinID,
                            'real_id'         => $realID,
                            'fun_id'          => $funID,
                            'statusReq'       => 0,
                            'agent_date'      => carbon::now(),
                        ]);
                        DB::table('notifications')->insert([
                            'value'      => MyHelpers::guest_trans('New Request Added'),
                            'recived_id' => $user_id,
                            'created_at' => now('Asia/Riyadh'),
                            'type'       => 0,
                            'req_id'     => $reqID,
                        ]);
                        // Request history
                        // add to request history
                        DB::table('request_histories')->insert([
                            'title'        => RequestHistory::TITLE_MOVE_REQUEST,
                            'recive_id'    => $user_id,
                            'history_date' => now('Asia/Riyadh'),
                            'req_id'       => $reqID,
                            'content'      => MyHelpers::guest_trans('hasbah_net'),
                        ]);
                        //***********UPDATE DAILY PREFERENCE */
                        $agent_id = $user_id;
                        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                            MyHelpers::addDailyPerformanceRecord($agent_id);
                        }
                        MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$reqID);
                       // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$reqID);
                        //***********END - UPDATE DAILY PREFERENCE */
                    }
                    else {
                        #pending
                        PendingRequest::create([
                            'statusReq'       => 0,
                            'customer_id'     => $customer->id,
                            'user_id'         => null,
                            'source'          => $req_source,
                            'req_date'        => $reqDate,
                            'created_at'      => $reqInfo->created_at,
                            'joint_id'        => $joinID,
                            'real_id'         => $realID,
                            'searching_id'    => $searching_id,
                            'fun_id'          => $funID,
                            'collaborator_id' => $collaborator_id,
                        ]);
                    }
                    setLastAgentOfDistribution($user_id, !$isApproved);
                }
            }
            else {
                $mobile = $reqInfo->mobile;
                # Duplicate
                try {
                    $customer = Customer::where('mobile', $mobile)->first();
                    $phones = CustomersPhone::where('mobile', $mobile)->first();
                    if ($phones) {
                        $customer = Customer::find($phones->customer_id)->first();
                    }
                    if (!($checkRequest = \App\Models\Request::where('customer_id', $customer->id)->first())) {
                        $checkRequest = PendingRequest::where('customer_id', $customer->id)->first();
                    }

                    if ($checkRequest && $checkRequest->class_id_agent != 16 && $checkRequest->class_id_agent != 13) {
                        //we will not notify the REJECTED & CUSTOMER NOT WANT TO COMPLETE classifications
                        if (MyHelpers::resubmitCustomerReqTime($checkRequest->agent_date)) {
                            // If The Difference Between Days is Greater Than Specified
                            $gms = MyHelpers::getAllActiveGM();
                            #send notify to admin
                            foreach ($gms as $gm) {
                                $value = MyHelpers::guest_trans('The customer tried to submit a new request');
                                if (MyHelpers::checkDublicateNotification($gm->id, $value, $checkRequest->id)) {
                                    DB::table('notifications')->insert([
                                        'value'      => $value,
                                        'recived_id' => $gm->id,
                                        'created_at' => (Carbon::now('Asia/Riyadh')),
                                        'type'       => 5,
                                        'req_id'     => $checkRequest->id,
                                    ]);
                                    MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $gm->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                                }
                            }
                        }
                        else {
                            // If The Difference Between Days is Less Than Specified
                            $value = MyHelpers::guest_trans('Your customer tried to submit a new request');
                            $user = DB::table('users')->where('id', $checkRequest->user_id)->first();
                            if (MyHelpers::checkDublicateNotification($user->id, $value, $checkRequest->id)) {
                                DB::table('notifications')->insert([
                                    'value'      => $value,
                                    'recived_id' => $user->id,
                                    'created_at' => (Carbon::now('Asia/Riyadh')),
                                    'type'       => 5,
                                    'req_id'     => $checkRequest->id,
                                ]);
                                MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $user->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                            }
                        }
                    }
                }
                catch (\Exception $exception) {

                }
            }
            GuestCustomer::where('id', $reqInfo->id)->delete();
        }
    }
}
