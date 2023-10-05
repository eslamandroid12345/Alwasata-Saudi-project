<?php

namespace App\Composers;

use App\Announcement;
use App\BankPercentage;
use App\FundingYear;
use App\Message;
use App\Model\PendingRequest;
use App\RequestNeedAction;
use App\SuggestionUser;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;
use MyHelpers;

class HomeComposer
{

    public function compose($view)
    {

        $supported_image = ['gif', 'jpg', 'jpeg', 'png'];
        $otaredfemale = DB::table('requests')->where('collaborator_id', 17)
            ->where('users.name', 'like', '%...%')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name')
            ->orderBy('req_date', 'DESC')
            ->count();

        $otaredmale = DB::table('requests')->where('collaborator_id', 17)
            ->where('users.name', 'like', '%،،،%')
            ->join('users', 'users.id', '=', 'requests.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.*', 'customers.name', 'users.name')
            ->orderBy('req_date', 'DESC')
            ->count();

        $checkFollow = DB::table('notifications')->where('recived_id', (auth()->user()->id))
            ->where('reminder_date', "<=", Carbon::now('Asia/Riyadh')->format("Y-m-d H:i:s"))
            ->where('status', 2) //Not Active (for following)
            ->first();
        if (!empty($checkFollow)) {
            DB::table('notifications')->where('id', $checkFollow->id)
                ->update([
                    'status'     => 0,
                    'created_at' => Carbon::now('Asia/Riyadh'),
                ]);

            //email notification
            !config('app.debug') &&
            MyHelpers::sendEmailNotifiaction('follow_req', auth()->user()->id, 'طلب يحتاج متابعة', 'لديك طلب يحتاج لمتابعتك');

            //$reqType = DB::table('requests')->where('id', $checkFollow->req_id)->first();
            //$reqType = !empty($reqType) ? $reqType->type : '';

            //$pwaPush = MyHelpers::pushPWA(auth()->user()->id, ' يومك سعيد  ' . auth()->user()->name, 'لديك طلب يحتاج متابعة', 'فتح الطلب', $userType, $typeReq, $checkFollow->req_id);
        }

        $notifyWithHelpdesk = DB::table('notifications')->where('recived_id', (auth()->user()->id))
            ->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('notifications.status', 0) // new
            ->whereIn('notifications.type', [8]) // new help desk
            ->orderBy('notifications.id', 'DESC')
            ->select('notifications.*', 'customers.name')
            ->get();

        $notifyWithoutReminders = DB::table('notifications')->where('recived_id', (auth()->user()->id))
            ->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('notifications.status', 0) // new
            ->whereNotIn('notifications.type', [1, 8, 9]) // reminder & dublicate customer
            ->orderBy('notifications.id', 'DESC')
            ->select('notifications.*', 'customers.name')
            ->get();
            // dd($notifyWithoutReminders);

        $mytime = Carbon::now();
        $reminders = DB::table('notifications')
            ->where('recived_id', (auth()->user()->id))
            ->where('notifications.status', 0) // new
            ->where('notifications.type', 9) // reminder & dublicate customer
            ->where(DB::raw("DATE(notifications.reminder_date)"), $mytime->toDateString())
            ->orderBy('notifications.id', 'DESC')
            ->get();
        $notifyWithOnlyReminders = DB::table('notifications')->where('recived_id', (auth()->user()->id))
            ->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('notifications.status', 0) // new
            ->whereIn('notifications.type', [1]) // reminder & dublicate customer
            ->orderBy('notifications.id', 'DESC')
            ->select('notifications.*', 'customers.name')
            ->get();

        $user_type = auth('customer')->user() ? 'App\customer' : 'App\User';

        if ($user_type == 'App\customer') {
            $unread_conversions = Message::where('to', auth()->id())
                ->where('to_type', $user_type)
                ->where('is_read', 0)->count();
        }
        else {

            $unread_conversions_users = Message::where('to', auth()->id())
                ->where('to_type', $user_type)
                ->where('from_type', 'App\User')
                ->where('is_read', 0)
                ->count();

            $unread_conversions_customers = Message::where('to', auth()->id())
                ->where('to_type', $user_type)
                ->where('from_type', 'App\customer')
                ->where('is_read', 0)
                ->join('requests', 'requests.customer_id', DB::raw('messages.from'))
                ->where('requests.user_id', auth()->id())
                ->count();

            $unread_conversions = $unread_conversions_customers + $unread_conversions_users;
        }

        if ($user_type == 'App\customer') {
            $unread_messages = Message::where('to', auth()->id())
                ->where('to_type', $user_type)
                ->where('is_read', 0) // new
                ->latest()->orderBy('from', 'DESC')->get();
        }
        else {
            $unread_messages_users = Message::where('to', auth()->id())
                ->where('from_type', 'App\User')
                ->where('to_type', $user_type)
                ->where('is_read', 0) // new
                ->select('messages.*')
                ->latest()->orderBy('from', 'DESC')->get();

            $unread_messages_customers = Message::where('to', auth()->id())
                ->where('from_type', 'App\customer')
                ->where('to_type', $user_type)
                ->where('is_read', 0) // new
                ->join('requests', 'requests.customer_id', DB::raw('messages.from'))
                ->where('requests.user_id', auth()->id())
                ->select('messages.*')
                ->latest()->orderBy('from', 'DESC')->get();

            $unread_messages = $unread_messages_users->merge($unread_messages_customers);
        }
        $users = User::where("role",5)->where("subdomain","<>",null)->pluck("id")->toArray();

        //dd( $unread_messages);

        //--------------- ALL Reqs Counter----------------------

        if (auth()->user()->role == 0) {
            $all_reqs_count = DB::table('requests')->where('requests.user_id', auth()->user()->id)->count();
        }

        elseif (auth()->user()->role == 1) {
            $all_reqs_count = MyHelpers::allReqCountSalesManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 2) {
            $all_reqs_count = MyHelpers::allReqCountFundingManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 3) {
            $all_reqs_count = MyHelpers::allReqCountMortgageManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 4) {
            $all_reqs_count = DB::table('requests')
                ->where(function ($query) {
                    $query->whereIn('statusReq', [12, 32]) //wating for generall manager approval
                    ->orWhere('isSentGeneralManager', 1); //yes sent
                })
                ->count();
        }

        elseif (auth()->user()->role == 5|| auth()->user()->role ==9 ) {

            $all_reqs_count = DB::table('quality_reqs')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users', 'users.id', 'requests.user_id')
                ->join('users as others', 'others.id', 'quality_reqs.user_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                    $q->whereIn('quality_reqs.user_id', $users);
                })->when(auth()->user()->role != 9 ,function($q,$v) {
                    $q->where('quality_reqs.user_id', auth()->id());
                })
                ->where('quality_reqs.allow_recive', 1)
                ->count();
        }
        elseif (auth()->user()->role == 6) {

            $all_reqs_count =  \App\Models\Request::where('collaborator_id', auth()->user()->id)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->count();
        }
        elseif (auth()->user()->role == 13) {

            $all_reqs_count =  \App\Models\Request::where('collaborator_id', auth()->user()->id)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->count();
        }
        elseif (auth()->user()->role == 7) {
            $all_reqs_count = DB::table('requests')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('joints', 'joints.id', '=', 'requests.joint_id')
                ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
                ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
                ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->count();
        }

        else {
            $all_reqs_count = 0;
        }
        $all_reqs_count = MyHelpers::numberFormatter($all_reqs_count);

        //--------------- End ALL Reqs Counter----------------------

        //--------------- Recive Reqs Counter----------------------

        if (auth()->user()->role == 0) {
            $received_reqs_count = DB::table('requests')->where('requests.user_id', auth()->user()->id)
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 0)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->count();
            //dd($received_reqs_count);
        }

        elseif (auth()->user()->role == 1) {
            $received_reqs_count = MyHelpers::reciveReqCountSalesManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 2) {
            $received_reqs_count = MyHelpers::reciveReqCountFundingManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 3) {
            $received_reqs_count = MyHelpers::reciveReqCountMortgageManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 4) {
            $received_reqs_count = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [12, 32]);
                        $query->where('requests.isSentGeneralManager', 1);
                        $query->whereIn('type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 23);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentGeneralManager', 1);
                    });
                })
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 0)
                ->count();
        }

        elseif (auth()->user()->role == 5 || auth()->user()->role ==9 ) {
            $received_reqs_count = DB::table('quality_reqs')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users', 'users.id', 'requests.user_id')
                ->join('users as others', 'others.id', 'quality_reqs.user_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                    $q->whereIn('quality_reqs.user_id', $users);
                })->when(auth()->user()->role != 9 ,function($q,$v) {
                    $q->where('quality_reqs.user_id', auth()->id());
                })
                ->where('quality_reqs.allow_recive', 1)
                ->whereIn('quality_reqs.status', [0, 1, 2])
                ->where('quality_reqs.is_followed', 0)
                ->select('quality_reqs.id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')
                ->count();
        }
        elseif (auth()->user()->role == 6) {
            $received_reqs_count =  \App\Models\Request::where('collaborator_id', auth()->user()->id)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where('statusReq','<>',2)
                ->count();
        }

        elseif (auth()->user()->role == 13) {
            $received_reqs_count =  \App\Models\Request::where('collaborator_id', auth()->user()->id)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where('statusReq','<>',2)
                ->count();
        }
        else {

            $received_reqs_count = 0;
        }

        //--------------- END Receive Reqs Counter----------------------

        //--------------- Follow Reqs Counter----------------------

        if (auth()->user()->role == 0) {
            $follow_reqs_count = DB::table('requests')->where('requests.user_id', auth()->user()->id)
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->where('is_canceled', 0)
                ->where('is_followed', 1)
                ->where('is_stared', 0)
                ->count();
        }
        elseif (auth()->user()->role == 1) {
            $managerID = (auth()->user()->id);

            $follow_reqs_count = DB::table('requests')
                //->whereIn('requests.user_id', $fn)
                // ->where('requests.req_date','>','2019-12-31')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where('requests.is_followed', 1)
                ->where('is_canceled', 0)
                ->where('is_stared', 0)
                ->where('requests.sales_manager_id', $managerID)
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
                ->orderBy('req_date', 'DESC')
                ->count();
        }
        elseif (auth()->user()->role == 7) {

            $follow_reqs_count = DB::table('requests')
                ->whereIn('statusReq', [0, 1, 4, 31])
                ->where('is_followed', 1)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('joints', 'joints.id', '=', 'requests.joint_id')
                ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
                ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
                ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->count();
        }
        elseif (auth()->user()->role == 5 || auth()->user()->role ==9 ) {

            $follow_reqs_count = DB::table('quality_reqs')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users', 'users.id', 'requests.user_id')
                ->join('users as others', 'others.id', 'quality_reqs.user_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                    $q->whereIn('quality_reqs.user_id', $users);
                })->when(auth()->user()->role != 9 ,function($q,$v) {
                    $q->where('quality_reqs.user_id', auth()->id());
                })
                ->where('quality_reqs.allow_recive', 1)
                ->whereIn('quality_reqs.status', [0, 1, 2])
                ->where('quality_reqs.is_followed', 1)
                ->select('quality_reqs.id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')
                ->count();
        }
        else {
            $follow_reqs_count = 0;
        }

        $follow_reqs_count = MyHelpers::numberFormatter($follow_reqs_count);

        //--------------- Follow Reqs Counter----------------------

        //--------------- Star Reqs Counter----------------------

        if (auth()->user()->role == 0) {
            $star_reqs_count = DB::table('requests')->where('requests.user_id', auth()->user()->id)
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 1)
                ->count();
        }

        elseif (auth()->user()->role == 1) {
            $managerID = (auth()->user()->id);

            $star_reqs_count = DB::table('requests')
                //->whereIn('requests.user_id', $fn)
                // ->where('requests.req_date','>','2019-12-31')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where('requests.is_stared', 1)
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('requests.sales_manager_id', $managerID)
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
                ->orderBy('req_date', 'DESC')
                ->count();
        }
        elseif (auth()->user()->role == 7) {

            $star_reqs_count = DB::table('requests')
                ->whereIn('statusReq', [0, 1, 4, 31])
                ->where('is_stared', 1)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('joints', 'joints.id', '=', 'requests.joint_id')
                ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
                ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
                ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->count();
        }
        else {
            $star_reqs_count = 0;
        }

        $star_reqs_count = MyHelpers::numberFormatter($star_reqs_count);

        //--------------- Star Reqs Counter----------------------

        //--------------- Mortgage Reqs Counter----------------------

        if (auth()->user()->role == 1) {
            $mor_reqs_count = MyHelpers::morReqCountSalesManager(auth()->user()->id);
        }
        else {
            $mor_reqs_count = 0;
        }

        //--------------- Mortgage Reqs Counter----------------------

        //--------------- Mor-Pur Reqs Counter----------------------

        if (auth()->user()->role == 0) {
            $mor_pur_reqs_count = DB::table('requests')->where('requests.user_id', auth()->user()->id)
                ->where('type', 'رهن-شراء')
                //  ->where('statusReq', 19) //WAITING FOR SALES AGENT
                ->where('isSentSalesAgent', 1)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->count();
        }

        elseif (auth()->user()->role == 1) {
            $mor_pur_reqs_count = MyHelpers::morPurReqCountSalesManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 2) {
            $mor_pur_reqs_count = MyHelpers::morPurReqCountFundingManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 3) {
            $mor_pur_reqs_count = MyHelpers::morPurReqCountMortgageManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 4) {
            $mor_pur_reqs_count = DB::table('requests')
                ->where('type', 'رهن-شراء')
                ->where(function ($query) {
                    $query->where('statusReq', 23) //approved and wating general manager
                    ->orWhere('isSentGeneralManager', 1); //yes sent
                })
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->select('requests.*', 'customers.name')
                ->count();
        }

        else {
            $mor_pur_reqs_count = 0;
        }

        //--------------- Mor-Pur Reqs Counter----------------------

        //--------------- cancel Reqs Counter----------------------

        $cancel_reqs_count = DB::table('requests')
            ->where(function ($query) {

                $query->where(function ($query) {
                    $query->where('statusReq', 15);
                    $query->where('requests.isSentGeneralManager', 1);
                    $query->whereIn('type', ['شراء', 'شراء-دفعة', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
                });

                $query->orWhere(function ($query) {
                    $query->where('statusReq', 27);
                    $query->where('type', 'رهن-شراء');
                    $query->where('requests.isSentGeneralManager', 1);
                });
            })->count();

        //--------------- cancel Reqs Counter----------------------

        //--------------- under Reqs Counter----------------------

        if (auth()->user()->role == 2) {
            $under_reqs_count = MyHelpers::underReqCountFundingManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 3) {
            $under_reqs_count = MyHelpers::underReqCountMortgageManager(auth()->user()->id);
        }

        else {
            $under_reqs_count = 0;
        }

        //--------------- under Reqs Counter----------------------

        //--------------- prepayment Reqs Counter----------------------
        if (auth()->user()->role == 0) {
            $prepay_reqs_count = DB::table('requests')->where('requests.user_id', auth()->user()->id)
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->count();
        }

        elseif (auth()->user()->role == 1) {
            $prepay_reqs_count = MyHelpers::prepayReqCountSalesManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 2) {
            $prepay_reqs_count = MyHelpers::prepayReqCountFundingManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 3) {
            $prepay_reqs_count = MyHelpers::prepayReqCountMortgageManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 4) {
            $prepay_reqs_count = DB::table('requests')
                ->where('type', 'شراء-دفعة')
                ->where(function ($query) {
                    $query->where('requests.isSentGeneralManager', 1); //yes sent
                })
                ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->select('requests.*', 'customers.name', 'prepayments.payStatus')
                ->orderBy('req_date', 'DESC')
                ->count();
        }

        else {
            $prepay_reqs_count = 0;
        }

        //--------------- prepayment Reqs Counter----------------------

        //--------------- Daily Reqs Counter----------------------

        $daily_reqs_count = MyHelpers::dailyReqCountSalesManager(auth()->user()->id);

        //--------------- Daily Reqs Counter----------------------

        //--------------- Purchase Reqs Counter----------------------

        if (auth()->user()->role == 1) {
            $pur_reqs_count = MyHelpers::purReqCountSalesManager(auth()->user()->id);
        }
        else {
            $pur_reqs_count = 0;
        }

        //--------------- Purchase Reqs Counter----------------------

        //--------------- Rejected Reqs Counter----------------------

        if (auth()->user()->role == 1) {
            $rej_reqs_count = MyHelpers::rejReqCountSalesManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 2) {
            $rej_reqs_count = MyHelpers::rejReqCountFundingManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 3) {
            $rej_reqs_count = MyHelpers::rejReqCountMortgageManager(auth()->user()->id);
        }

        else {
            $rej_reqs_count = 0;
        }

        //--------------- Rejected Reqs Counter----------------------

        //--------------- Arch Reqs Counter----------------------
        if (auth()->user()->role == 0) {
            $arch_reqs_count = DB::table('requests')->where('requests.user_id', auth()->user()->id)
                ->where('statusReq', 2) //archived in sales agent
                ->count();
        }

        elseif (auth()->user()->role == 1) {
            $arch_reqs_count = MyHelpers::archReqCountSalesManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 2) {
            $arch_reqs_count = MyHelpers::archReqCountFundingManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 3) {
            $arch_reqs_count = MyHelpers::archReqCountMortgageManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 4) {
            $arch_reqs_count = DB::table('requests')
                ->where('statusReq', 14) //archived in general manager
                ->where('isSentGeneralManager', 1)
                ->count();
        }

        elseif (auth()->user()->role == 5|| auth()->user()->role ==9 ) {
            $arch_reqs_count = DB::table('quality_reqs')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                    $q->whereIn('quality_reqs.user_id', $users);
                })->when(auth()->user()->role != 9 ,function($q,$v) {
                    $q->where('quality_reqs.user_id', auth()->id());
                })
                ->where('quality_reqs.allow_recive', 1)
                ->where('quality_reqs.status', 5)
                ->select('quality_reqs.id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')
                ->count();
        }
        elseif (auth()->user()->role == 6) {
            $arch_reqs_count = \App\Models\Request::where('collaborator_id', auth()->user()->id)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where('statusReq',2)
                ->count();
        }
        elseif (auth()->user()->role == 13) {
            $arch_reqs_count = \App\Models\Request::where('collaborator_id', auth()->user()->id)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where('statusReq',2)
                ->count();
        }

        elseif (auth()->user()->role == 7) {
            $arch_reqs_count = DB::table('requests')
                ->whereIn('statusReq', [2])
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->join('joints', 'joints.id', '=', 'requests.joint_id')
                ->join('real_estats', 'real_estats.id', '=', 'requests.real_id')
                ->join('fundings', 'fundings.id', '=', 'requests.fun_id')
                ->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
                ->count();
        }

        else {
            $arch_reqs_count = 0;
        }

        $arch_reqs_count = MyHelpers::numberFormatter($arch_reqs_count);

        //--------------- Arch Reqs Counter----------------------

        //--------------- Completed Reqs Counter----------------------

        if (auth()->user()->role == 0) {
            $com_reqs_count = MyHelpers::compReqCountSalesAgent(auth()->user()->id);
        }

        elseif (auth()->user()->role == 1) {
            $com_reqs_count = MyHelpers::comReqCountSalesManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 2) {
            $com_reqs_count = MyHelpers::comReqCountFundingManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 3) {
            $com_reqs_count = MyHelpers::comReqCountMortgageManager(auth()->user()->id);
        }

        elseif (auth()->user()->role == 4) {
            $com_reqs_count = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereNotIn('statusReq', [12, 14, 15, 32]);
                        $query->where('requests.isSentGeneralManager', 1);
                        $query->whereIn('type', ['شراء', 'شراء-دفعة', 'رهن', 'تساهيل']); // because the mortgage type ll never recived and recived mor=pur insted
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', '!=', 23);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentGeneralManager', 1);
                    });
                })
                ->count();
        }
        elseif (auth()->user()->role == 5|| auth()->user()->role ==9 ) {
            $com_reqs_count = DB::table('quality_reqs')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users', 'users.id', 'requests.user_id')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->where('quality_reqs.allow_recive', 1)
                ->where('quality_reqs.status', 3)
                ->select('quality_reqs.id', 'requests.id as reqID', 'users.name as agentName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent', 'requests.class_id_quality', 'quality_reqs.status',
                    'requests.type', 'requests.collaborator_id', 'requests.source', 'quality_reqs.created_at')
                ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                    $q->whereIn('quality_reqs.user_id', $users);
                })->when(auth()->user()->role != 9 ,function($q,$v) {
                    $q->where('quality_reqs.user_id', auth()->id());
                })
                ->count();
        }

        else {
            $com_reqs_count = 0;
        }

        //--------------- Completed Reqs Counter----------------------

        //-------------------------Agent completed---------------------------
        if (auth()->user()->role == 1) {
            $managerID = (auth()->user()->id);

            $agent_com_reqs_count = DB::table('requests')
                //->whereIn('requests.user_id', $fn)
                // ->where('requests.req_date','>','2019-12-31')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                        $query->whereIn('type', ['شراء-دفعة']);
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('statusReq', [0, 1, 2, 4]);
                        $query->whereIn('type', ['رهن', 'شراء']);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereNotIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('prepayments.isSentSalesAgent', 1);
                        $query->where('requests.type', 'شراء-دفعة');
                    });
                })
                ->where('requests.sales_manager_id', $managerID)
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
                ->orderBy('req_date', 'DESC')
                ->count();

            //------------------------------------------------------------

            //-------------------------Agent Archevied---------------------------
            $managerID = (auth()->user()->id);
            $users = DB::table('users')->where('manager_id', $managerID)
                ->get()->toArray();
            //  $requests = array();
            $fn = [];
            foreach ($users as $user) {
                $fn[] = $user->id;
            }

            $agent_arch_reqs_count = DB::table('requests')->whereIn('requests.user_id', $fn)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where('statusReq', 2)
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
                ->orderBy('req_date', 'DESC')
                ->count();

            $count = DB::table('requests')->whereIn('requests.user_id', $fn)
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('requests.type', 'شراء-دفعة');
                        $query->where('prepayments.isSentSalesAgent', 1);
                    });
                })
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 0)
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
                ->orderBy('req_date', 'DESC')
                ->count();
        }
        elseif (auth()->user()->role == 7) {

            $count = DB::table('requests')
                // ->where('requests.req_date','>','2019-12-31')
                ->join('customers', 'customers.id', '=', 'requests.customer_id')
                ->join('users', 'users.id', '=', 'requests.user_id')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->whereIn('statusReq', [0, 1, 4, 31]);
                    });

                    $query->orWhere(function ($query) {
                        $query->where('statusReq', 19);
                        $query->where('type', 'رهن-شراء');
                        $query->where('requests.isSentSalesAgent', 1);
                    });

                    $query->orWhere(function ($query) {
                        $query->whereIn('prepayments.payStatus', [4, 3]);
                        $query->whereIn('statusReq', [6, 13]);
                        $query->where('requests.type', 'شراء-دفعة');
                        $query->where('prepayments.isSentSalesAgent', 1);
                    });
                })
                ->where('is_canceled', 0)
                ->where('is_followed', 0)
                ->where('is_stared', 0)
                ->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')
                ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name', 'prepayments.payStatus')
                ->orderBy('req_date', 'DESC')
                ->count();

            $agent_arch_reqs_count = 0;
            $agent_com_reqs_count = 0;
        }
        else {
            $agent_arch_reqs_count = 0;
            $agent_com_reqs_count = 0;
            $count = 0;
        }

        $count = MyHelpers::numberFormatter($count);
        $agent_com_reqs_count = MyHelpers::numberFormatter($agent_com_reqs_count);
        $agent_arch_reqs_count = MyHelpers::numberFormatter($agent_arch_reqs_count);

        if (auth()->user()->role == 5) {
            /*$requests = DB::table('quality_reqs')
                ->where('quality_reqs.user_id', auth()->user()->id)
                ->pluck('quality_reqs.id');

            $received_task_count = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users', 'users.id', 'requests.user_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->whereIn('tasks.status', [2])
                ->whereIn('tasks.req_id', $requests)
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id);
                    });
                })
                ->where('task_contents.task_contents_status', 1)
                ->get();
            $received_task_count = $received_task_count->unique('id')->count();*/

            $quality_req = \Illuminate\Support\Facades\DB::table('quality_reqs')
                ->where('quality_reqs.user_id', auth()->id())
                ->pluck('quality_reqs.id');

            $requests = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users', 'users.id', 'requests.user_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->id());
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->id());
                    });
                })
                ->where('task_contents.task_contents_status', 1)
                ->whereIn('tasks.status', [2])
                ->whereIn('tasks.req_id', $quality_req)
                ->select('tasks.*', 'requests.comment', 'users.name as user_name', 'customers.mobile', 'customers.name', 'customers.salary', 'requests.collaborator_id', 'requests.source', 'requests.type', 'requests.quacomment', 'quality_reqs.status as qustatus')
                ->get();
            $received_task_count = $requests->unique('id')->count();
            //if( \auth()->id() == 278) {
            //    dd(auth()->id(), $received_task_count);
            //}

        }
        elseif (auth()->user()->role == 0) {
            $received_task_count = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('requests', 'requests.id', 'tasks.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')

                ->whereIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 1);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 0);
                    });
                })
                ->where('user.role', '!=', 5)
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();

            $received_task_count2 = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->join('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')

                ->whereIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 1);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 0);
                    });
                })
                ->where('user.role', 5)
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();

            $received_task_count = $received_task_count->merge($received_task_count2 ?: collect());

            $received_task_count = $received_task_count->unique('id')->count();

        }
        else {

            $received_task_count = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('requests', 'requests.id', 'tasks.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->whereIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 1);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 0);
                    });
                })
                ->where('user.role', '!=', 5)
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();

            $received_task_count2 = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->join('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->whereIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 1);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 0);
                    });
                })
                ->where('user.role', 5)
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();

            $received_task_count = $received_task_count->merge($received_task_count2 ?: collect());

            $received_task_count = $received_task_count->unique('id')->count();
        }

        if (auth()->user()->role == 5) {
            $requests = DB::table('quality_reqs')
                ->where('quality_reqs.user_id', auth()->user()->id)
                ->pluck('quality_reqs.id');

            $sent_task_count = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users', 'users.id', 'requests.user_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->whereIn('tasks.status', [0, 1, 2])
                ->whereIn('tasks.req_id', $requests)
                ->where('task_contents.task_contents_status', 0)
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id);
                    });
                })
                ->get();

            $sent_task_count = $sent_task_count->unique('id')->count();

        }
        elseif (auth()->user()->role == 0) {

            $tasks1 = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('requests', 'requests.id', 'tasks.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')

                ->whereIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 0);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 1);
                    });
                })
                ->where('user.role', '!=', 5)
                ->get();

            $tasks2 = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')

                ->whereIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 0);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 1);
                    });
                })
                ->where('user.role', 5)
                ->get();

            $sent_task_count = $tasks1->merge($tasks2 ?: collect());

            $sent_task_count = $sent_task_count->unique('id')->count();

        }
        else {

            $sent_task_count = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('requests', 'requests.id', 'tasks.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->whereIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 0);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id)
                            ->where('task_contents.task_contents_status', 1);
                    });
                })
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();

            $sent_task_count = $sent_task_count->unique('id')->count();
        }

        //-----

        if (auth()->user()->role == 5) {
            $requests = DB::table('quality_reqs')
                ->where('quality_reqs.user_id', auth()->user()->id)
                ->pluck('quality_reqs.id');

            $completed_task_count = DB::table('tasks')
                ->whereNotIn('tasks.status', [0, 1, 2])
                ->whereIn('tasks.req_id', $requests)->count();
        }
        elseif (auth()->user()->role == 0) {

            $tasks1 = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('requests', 'requests.id', 'tasks.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->whereNotIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id);
                    });
                })
                ->where('user.role', '!=', 5)
                ->get();

            $tasks2 = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')
                ->join('requests', 'requests.id', 'quality_reqs.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->whereNotIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id);
                    });
                })
                ->where('user.role', 5)
                ->get();

            $tasks = $tasks1->merge($tasks2 ?: collect());
            $completed_task_count = $tasks->unique('id')->count();
        }
        else {
            $completed_task_count = DB::table('tasks')
                ->join('task_contents', 'task_contents.task_id', 'tasks.id')
                ->join('requests', 'requests.id', 'tasks.req_id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->whereNotIn('tasks.status', [0, 1, 2])
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id);
                    });
                })
                ->get();

            $completed_task_count = $completed_task_count->unique('id')->count();
        }

        //------------------------------------------------------------

        $task = DB::table('tasks')
            ->join('task_contents', 'task_contents.task_id', 'tasks.id')
            ->where('recive_id', auth()->user()->id)
            ->where('status', 0)
            ->select('tasks.*', 'task_contents.date_of_content', 'task_contents.content')
            ->get();

        //If I created the task
        $task_contents1 = DB::table('tasks')
            ->join('task_contents', 'task_contents.task_id', 'tasks.id')
            ->where('user_id', auth()->user()->id)
            ->where('tasks.status', 2)
            ->where('task_contents_status', 1)
            ->select('tasks.*', 'task_contents.date_of_content', 'task_contents.id as task_id', 'task_contents.content', 'task_contents.user_note', 'task_contents.task_id')
            ->distinct('task_id')
            ->get();
        //If I received the task
        $task_contents2 = DB::table('tasks')
            ->join('task_contents', 'task_contents.task_id', 'tasks.id')
            ->where('recive_id', auth()->user()->id)
            ->whereIn('status', [2])
            ->where('task_contents_status', 0)
            ->select('tasks.*', 'task_contents.date_of_content', 'task_contents.content')
            ->distinct('task_id')
            ->get();

        $task_contents = $task_contents1->merge($task_contents2);

        $pending_request_count = PendingRequest::count();

        $need_action_request_count = RequestNeedAction::where('status', 0)->count();

        //ANNOUNCEMENTS
        $announces = Announcement::where('status', 1)->get();

        //CALCULATOR SUGGESTIONS:::::::::::::::::::
        if (\App\EditCalculationFormulaUser::where('user_id', auth()->user()->id)->count() > 0 || auth()->user()->role == 7) {
            if (auth()->user()->role == 7) {
                $banks = BankPercentage::where('status', 0)->count();
                $years = FundingYear::where('status', 0)->count();
            }
            else {
                $done_banks = SuggestionUser::where([
                    'suggestable_type' => BankPercentage::class,
                    'user_id'          => auth()->id(),
                ])
                    ->pluck('suggestable_id')
                    ->toArray();
                $done_years = SuggestionUser::where([
                    'suggestable_type' => FundingYear::class,
                    'user_id'          => auth()->id(),
                ])
                    ->pluck('suggestable_id')
                    ->toArray();
                $banks = BankPercentage::whereNotIn('id', $done_banks)
                    ->where('user_id', '<>', auth()->user()->id)
                    ->where(['status' => 0])
                    ->count();
                $years = FundingYear::whereNotIn('id', $done_years)
                    ->where('user_id', '<>', auth()->user()->id)
                    ->where(['status' => 0])
                    ->count();
            }

            $calculator_suggests = $years + $banks;
        }
        else {
            $calculator_suggests = 0;
        }
        $customer_id = Auth::guard('customer')->id();
        $agent_id_check_auth = Auth::id();
        if ($customer_id) {
            /*
            $requestTable = \Illuminate\Support\Facades\DB::table('requests')->where('customer_id', $customer_id)->first();
            $agent_id = $requestTable->user_id;
            $chatFireStore = new FireStore('messages');
            $getAllMessages = $chatFireStore->countUnreadMessagesCustomer($customer_id, $agent_id);
//                dd($getAllMessages);
            if ($getAllMessages == null){
                $messages = [];
                $view->with([
                    'count' => null,
                    'messages' => $messages,
                ]);
            }
            else{
                foreach ($getAllMessages as $key => $value){$messages[] = $value;}
                $view->with([
                    'count' => count($getAllMessages),
                    'messages' => $messages,
                ]);
            }
            */
        }
        if ($agent_id_check_auth) {
            /*
            $chatFireStore = new FireStore('messages');
            $count_message = $chatFireStore->countAllUnreadMessageFromClient($agent_id_check_auth);
            $get_all_message = $chatFireStore->getAllUnreadMessageFromClient($agent_id_check_auth);
            if ($get_all_message == null){
                $messages = [];
                $view->with([
                    'count_unread_message_from_client' => $count_message,
                    'get_all_unread_messages' => $messages
                ]);
            }
            else{
                foreach ($get_all_message as $key => $value){$messages[] = $value;}
                $view->with([
                    'count_unread_message_from_client' => $count_message,
                    'get_all_unread_messages' => $messages,
                ]);
            }
            */
        }

        /////////////////////////////////////
        //Add your variables
        $view->with([
            'count'                     => 0,
            'messages'                  => null,
            'supported_image'           => $supported_image,
            'notifyWithHelpdesk'        => $notifyWithHelpdesk,
            'notifyWithoutReminders'    => $notifyWithoutReminders,
            'notifyWithOnlyReminders'   => $notifyWithOnlyReminders,
            'reminders'                 => $reminders,
            'unread_conversions'        => $unread_conversions,
            'unread_messages'           => $unread_messages,
            'daily_reqs_count'          => $daily_reqs_count,
            'all_reqs_count'            => $all_reqs_count,
            'received_reqs_count'       => $received_reqs_count,
            'follow_reqs_count'         => $follow_reqs_count,
            'star_reqs_count'           => $star_reqs_count,
            'arch_reqs_count'           => $arch_reqs_count,
            'com_reqs_count'            => $com_reqs_count,
            'pur_reqs_count'            => $pur_reqs_count,
            'agent_com_reqs_count'      => $agent_com_reqs_count,
            'sent_task_count'           => $sent_task_count,
            'mor_reqs_count'            => $mor_reqs_count,
            'mor_pur_reqs_count'        => $mor_pur_reqs_count,
            'prepay_reqs_count'         => $prepay_reqs_count,
            'rej_reqs_count'            => $rej_reqs_count,
            'completed_task_count'      => $completed_task_count,
            'under_reqs_count'          => $under_reqs_count,
            'cancel_reqs_count'         => $cancel_reqs_count,
            'otaredfemale'              => $otaredfemale,
            'otaredmale'                => $otaredmale,
            'received_task_count'       => $received_task_count,
            'agent_arch_reqs_count'     => $agent_arch_reqs_count,
            'agent_received_reqs_count' => $count,
            'task'                      => $task,
            'task_contents'             => $task_contents,
            'pending_request_count'     => $pending_request_count,
            'announces'                 => $announces,
            'need_action_request_count' => $need_action_request_count,
            'calculator_suggests'       => $calculator_suggests,
        ]);
    }

}
