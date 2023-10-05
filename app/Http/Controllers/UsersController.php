<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\AnnounceSeen;
use App\document;
use App\helpDesk;
use App\HelperFunctions\Helper;
use App\Image;
use App\Models\Classification;
use App\Models\Customer;
use App\Models\Request as RequestModel;
use App\Models\RequestSource;
use App\task;
use App\task_content;
use App\User;
use Carbon\Carbon;
use Datatables;
use GeniusTS\HijriDate\Hijri;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use MyHelpers;
use PDF;
use View;

//to take date

class UsersController extends Controller
{

    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
    }

    /////////////////////NOTIFCATIONES////////////////////////

    public function fetchNotify()
    { // to get notificationes of users
        return DB::table('notifications')->where('recived_id', (auth()->user()->id))->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('notifications.status', 0) // new
        ->orderBy('notifications.id', 'DESC')->select('notifications.*', 'customers.name')->get();
    }

    public function reqHistory(Request $request, $id)
    {
        if (auth()->user()->role == 6) {
            die('صلاحيتك لا تسمح بالوصول الى الصفحة المطلوبة');
        }
        $model = \App\Models\Request::findOrFail($id);
        if (auth()->user()->role == 7 ||auth()->user()->role==0) {
            return view('All.request_records', ['records' => $model->getHistoryWithNotes(auth()->user()->role)]);
        }
        if (auth()->user()->role != 0) {
            $check_aget = DB::table('requests')->where('id', $id)->where('user_id', auth()->user()->id)->first();
            if ($check_aget) {
                $customer=Customer::find($check_aget->customer_id);
            }else{
                $customer = null;
            }

            $req_histories = DB::table('request_histories')->where('request_histories.req_id', $id)->leftjoin('users as user', 'user.id', '=', 'request_histories.user_id') // name join if u will join same table twic
            ->leftjoin('users as rec', 'rec.id', '=', 'request_histories.recive_id')->leftjoin('users as switch', 'switch.id', '=', 'request_histories.user_switch_id')->select('request_histories.*', 'user.name as sentname', 'rec.name as recname',
                'switch.name as swname')->orderBy('request_histories.id', 'ASC')->get();
        }
        else {
            $check_aget = DB::table('requests')->where('id', $id)->where('user_id', auth()->user()->id)->first();
            if (empty($check_aget)) {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }
            $customer=Customer::find($check_aget->customer_id);
            $req_histories = DB::table('request_histories')->where('request_histories.req_id', $id)->leftjoin('users as user', 'user.id', '=', 'request_histories.user_id') // name join if u will join same table twic
            ->leftjoin('users as rec', 'rec.id', '=', 'request_histories.recive_id')->leftjoin('users as switch', 'switch.id', '=', 'request_histories.user_switch_id')->select('request_histories.*', 'user.name as sentname', 'rec.name as recname',
                'switch.name as swname')->orderBy('request_histories.id', 'ASC')->get();

        }
        return view('All.reqTimeLine', compact('req_histories','model','customer'));


        //dd($req_histories);

    }

    public function notificationes()
    {
        /*
        if (auth()->user()->role == 7)
            $updateNotify = DB::table('notifications')->where('recived_id', auth()->user()->id)
                ->update(['status' => 1]);
        */

        $notifiys = DB::table('notifications')->where('recived_id', (auth()->user()->id))->whereIn('notifications.status', [0, 1])->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->select('notifications.*',
            'customers.name')->orderBy('notifications.created_at', 'DESC');

        if (auth()->user()->role == 7) {
            $notifiys = $notifiys->where('is_done', 0);
        }

        $notifiys = $notifiys->count();

        return view('All.notification', compact('notifiys'));
    }

    public function notificationes_datatable()
    {


        $notifiys = DB::table('notifications')->where('recived_id', (auth()->user()->id))
            ->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')
            ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')

            // ->whereIn('notifications.status', [0, 1]) // new
            // ->whereIn('notifications.type', [1]) // reminder & dublicate customer

            ->where('notifications.status', 0) // new
            ->whereNotIn('notifications.type', [1, 8, 9]) // reminder & dublicate customer

            ->orderBy('notifications.id', 'DESC')
            ->select('notifications.*', 'customers.name');


        if (auth()->user()->role == 7) {
            $notifiys = $notifiys->where('is_done', 0);
        }

        return Datatables::of($notifiys)->setRowId(function ($notifiys) {
            return $notifiys->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            if ($row->name != null && $row->req_id) {

                $data = $data.'<a href="'.route('all.openNotify', ['id' => $row->req_id, 'notify' => $row->id]).'">
          <span class="item pointer" id="Open" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'"> <i class="fas fa-eye"></i></span></a>';
            }

            //    else
            //       $data =  $data . ' <span class="item" id="Open" data-toggle="tooltip" data-placement="top" title="الطلب موجود في الطلبات المعلقة"> <i class="fas fa-ban"></i></span>';

            if ($row->status != 0 || $row->name == null) {
                $data = $data.'<span type="button"  id="Delete" data-id="'.($row->id).'" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Delete').'">
                <i class="fas fa-trash-alt"></i></span>';
            }
            // only for admins
            if (auth()->user()->role == 7 && $row->is_done == 0) {
                $data = $data.'<span type="button"  id="Done" data-id="'.($row->id).'" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'notification done').'">
                <i class="fas fa-check"></i> </span>';
            }

            if (auth()->user()->role == 7) {
                $json = json_encode($row);
                $title = __('language.request records');
                $data = $data."<span type='button' data-id='{$row->id}' class='item pointer notify-modal-btn' data-toggle='tooltip' data-placement='top' title='$title' data-notify='$json'><i class='fa fa-history i-20'></i> </span>";
            }

            $data = $data.'</div>';

            return $data;
        })->addColumn('status', function ($row) {
            if ($row->status == 0) {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'New Notify');
            }
            else {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'Open Notify');
            }
            return $data;
        })->addColumn('name', function ($row) {
            if ($row->name != null) {
                $data = $row->name;
            }
            else {

                $notifiy = DB::table('notifications')->where('recived_id', (auth()->user()->id))->where('notifications.id', $row->id)->leftjoin('pending_requests', 'pending_requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=',
                    'pending_requests.customer_id')->select('customers.name')->first();

                $data = $notifiy->name;
            }
            return $data;
        })->make(true);
    }

    public function notificationes_Done()
    {

        if (auth()->user()->role == 7) {

            $notifiys = DB::table('notifications')->where('recived_id', (auth()->user()->id))->whereIn('notifications.status', [0, 1])->where('is_done', 1)->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=',
                'requests.customer_id')->select('notifications.*', 'customers.name')->orderBy('notifications.created_at', 'DESC')->count();

            return view('All.notification_done', compact('notifiys'));
        }

        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function notificationes_datatable_Done()
    {
        $notifiys = DB::table('notifications')->where('is_done', 1)->where('recived_id', (auth()->user()->id))->whereIn('notifications.status', [0, 1])->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=',
            'requests.customer_id')->select('notifications.*', 'customers.name')->orderBy('notifications.created_at', 'DESC');

        return Datatables::of($notifiys)->setRowId(function ($notifiys) {
            return $notifiys->id;
        })->addColumn('action', function ($row) {
            $data = '<div  class="tableAdminOption">';

            if ($row->name != null) {
                $data = $data.'<a href="'.route('all.openNotify', ['id' => $row->req_id, 'notify' => $row->id]).'">
          <span class="item pointer" id="Open" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'"> <i class="fas fa-eye"></i></span></a>';
            }

            else {
                $data = $data.' <span class="item" id="Open" data-toggle="tooltip" data-placement="top" title="الطلب موجود في الطلبات المعلقة"> <i class="fas fa-ban"></i></span>';
            }

            if ($row->status != 0 || $row->name == null) {
                $data = $data.'<span type="button"  id="Delete" data-id="'.($row->id).'" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Delete').'">
                <i class="fas fa-trash-alt"></i> </span>';
            }

            if (auth()->user()->role == 7 && $row->is_done == 0) // only for admins
            {
                $data = $data.'<span type="button"  id="Done" data-id="'.($row->id).'" class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'notification done').'">
                <i class="fas fa-check"></i> </span>';
            }

            $data = $data.'</div>';

            return $data;
        })->addColumn('status', function ($row) {
            if ($row->status == 0) {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'New Notify');
            }
            else {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'Open Notify');
            }
            return $data;
        })->addColumn('name', function ($row) {
            if ($row->name != null) {
                $data = $row->name;
            }
            else {

                $notifiy = DB::table('notifications')->where('recived_id', (auth()->user()->id))->where('notifications.id', $row->id)->leftjoin('pending_requests', 'pending_requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=',
                    'pending_requests.customer_id')->select('customers.name')->first();

                $data = $notifiy->name;
            }
            return $data;
        })->make(true);
    }

    public function openReq($id, $notify)
    {
        DB::table('notifications')->where('id', $notify)->update(['status' => 1]); // open

        $requestInfo = DB::table('requests')->where('requests.id', '=', $id)->first();

        if (auth()->user()->role == 5) {
            $requestInfo = DB::table('quality_reqs')->join('requests', 'requests.id', '=', 'quality_reqs.req_id')->where('quality_reqs.id', '=', $id)->select('requests.*')->first();
        }

        //dd($id);

        if (auth()->user()->role == 0 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('agent.fundingRequest', $id);
        }
        elseif (auth()->user()->role == 0 && $requestInfo->type == 'رهن-شراء') {
            return redirect()->route('agent.morPurRequest', $id);
        }
        elseif (auth()->user()->role == 1 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('sales.manager.fundingRequest', $id);
        }
        elseif (auth()->user()->role == 1 && $requestInfo->type == 'رهن-شراء') {
            return redirect()->route('sales.manager.morPurRequest', $id);
        }
        elseif (auth()->user()->role == 2 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('funding.manager.fundingRequest', $id);
        }
        elseif (auth()->user()->role == 2 && $requestInfo->type == 'رهن-شراء') {
            return redirect()->route('funding.manager.morPurRequest', $id);
        }
        elseif (auth()->user()->role == 3 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('mortgage.manager.fundingRequest', $id);
        }
        elseif (auth()->user()->role == 3 && $requestInfo->type == 'رهن-شراء') {
            return redirect()->route('mortgage.manager.morPurRequest', $id);
        }
        elseif (auth()->user()->role == 4 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('general.manager.fundingRequest', $id);
        }
        elseif (auth()->user()->role == 4 && $requestInfo->type == 'رهن-شراء') {
            return redirect()->route('general.manager.morPurRequest', $id);
        }
        elseif (auth()->user()->role == 5 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('quality.manager.fundingRequest', $id);
        }
        elseif (auth()->user()->role == 5 && $requestInfo->type == 'رهن-شراء') {
            return redirect()->route('quality.manager.morPurRequest', $id);
        }
        elseif (auth()->user()->role == 7 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('admin.fundingRequest', $id);
        }
        elseif (auth()->user()->role == 13 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('V2.BankDelegate.request.show', $id);
        }elseif (auth()->user()->role == 6 && $requestInfo->type != 'رهن-شراء') {
            return redirect()->route('proper.request.show', $id);
        }
        elseif (auth()->user()->role == 7 && $requestInfo->type == 'رهن-شراء') {
            return redirect()->route('admin.morPurRequest', $id);
        } // test

    }

    public function reqRecords(Request $request)
    {
        if ($request->has('userID')) {
            $histories = DB::table('req_records')
                ->where('req_records.comment', '=', $request->userID)
                ->where('req_records.colum', '=', 'profile_'.$request->coloum)
                ->leftjoin('users as user', 'user.id', '=', 'req_records.user_id')
                ->leftjoin('users as switch', 'switch.id', '=', 'req_records.user_switch_id')
                ->select('user.name_for_admin', 'user.name as name', 'switch.name as switch', 'req_records.*')->get();
        }
        else {
            $histories = DB::table('req_records')
                ->where('req_records.req_id', '=', $request->reqID)
                ->where('req_records.colum', '=', $request->coloum)
                ->leftjoin('users as user', 'user.id', '=', 'req_records.user_id')
                ->leftjoin('users as switch', 'switch.id', '=', 'req_records.user_switch_id')
                ->select('user.name_for_admin', 'user.name as name', 'switch.name as switch', 'req_records.*')->get();
        }

        if (!empty($histories[0])) {
            //dd($histories);
            $histories = $histories->map(function ($i) {
                $i->name = $i->name ?: '';
                $i->switch = $i->switch ?: '';
                $i->name .= ($i->name ? " - " : '').($i->name_for_admin ?: null);
                $i->name = trim($i->name, ' -');
                //$i->name = $i->name?:null;
                if ($i->value && is_numeric($i->value) && Str::contains($i->colum, 'class_')) {
                    $class = Classification::find($i->value);
                    $i->value = $class ? $class->value : $i->value;
                }
                if ($i->value && is_numeric($i->value) && Str::contains($i->colum, 'reqSource')) {
                    $class = RequestSource::find($i->value);
                    $i->value = $class ? $class->value : $i->value;
                }
                return $i;
            })->values();
            return response(['status' => 1, 'histories' => $histories]);
        }
        else {
            return response(['status' => 0, 'message' => "لايوجد تحديثات"]);
        }
    }

    // Task-17
    //*********************************************
    public function checkMobile(Request $request)
    {
        $mobile = $request->mobile;
        $validator = Validator::make($request->all(), [
            //'mobile' => ['required', 'numeric', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
            'mobile' => ['required', 'numeric', 'mobile'],
        ], [
            'mobile.required' => ' رقم الجوال مطلوب *',
            'mobile.numeric'  => 'رقم الجوال لابد ان يكون ارقام *',
            'mobile.unique'   => 'رقم الجوال موجود بالفعل  *',
            'mobile.regex'    => 'رقم الجوال غير صحيح *',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $checkmobile = DB::table('customers')->where('mobile', $mobile)->first();
        $checkmobiles = DB::table('customers_phones')->where('mobile', $mobile)->first();

        if ($checkmobile == null && $checkmobiles == null) {
            return response('no'); // not existed
        }
        else {

            $typeOfReq = MyHelpers::typeOfRequest($mobile);
            if ($typeOfReq == 'pending') {

                $request = MyHelpers::getPendingRequestByMobile($mobile);

                return response()->json(['result' => 'pending', 'request' => $request]); //in pending table
            }
            elseif ($typeOfReq == 'request') {

                //dd($requestData);
                if (!($requestData = MyHelpers::getActiveRequestByMobile($mobile))) {
                    return response('no'); // not existed
                }

                if ($requestData->user_id == auth()->user()->id) // the request existed in same agent
                {
                    return response()->json(['result' => 'existed_in_agent', 'request' => $requestData]);
                }

                if ($requestData->is_freeze) {
                    return response()->json(['result' => 'freeze', 'request' => $requestData]);
                }

                if ($requestData->statusReq == 2) {
                    return response()->json(['result' => 'archivedReq', 'request' => $requestData]);
                }

                //if (($requestData->statusReq == 0 || $requestData->statusReq == 1 || $requestData->statusReq == 2 || $requestData->statusReq == 4)) {
                if (in_array($requestData->statusReq, [0, 1, 2, 4])) {
                    /**
                     * 2022-01-27
                     * From need actions
                     */
                    if (MyHelpers::checkIfNeedActionReqExisted($requestData->id)) {
                        return response()->json(['result' => 'needAction', 'request' => $requestData]);
                    } //in need Action table

                    $getAgent = DB::table('users')->where('id', $requestData->user_id)->where('status', 0)->first(); //Archive Agent

                    if ($getAgent) {
                        return response()->json(['result' => 'previous', 'request' => $requestData]);
                    }
                    //else {
                    //    if ($requestData->statusReq == 2) {
                    //        return response()->json(['result' => 'archivedReq', 'request' => $requestData]);
                    //    }
                    //}
                }
                //dd($requestData);
            }
            else {
                $requestData = MyHelpers::getRequestByMobile($mobile);
            }

            if ($requestData) {
                if ($requestData->class_id_agent != 16 && $requestData->class_id_agent != 13) {
                    //we will not notify the REJECTED & CUSTOMER NOT WANT TO COMPLETE clssifications
                    if (MyHelpers::resubmitCustomerReqTime($requestData->agent_date)) {
                        $value = auth()->user()->name.'  حاول إضافة العميل';
                        #send notifiy to admin
                        $admins = MyHelpers::getAllActiveAdmin();
                        foreach ($admins as $admin) {
                            if (MyHelpers::checkDublicateNotification($admin->id, $value, $requestData->id)) {
                                DB::table('notifications')->insert([ // add notification to send user
                                                                     'value'      => auth()->user()->name.'  حاول إضافة العميل',
                                                                     'recived_id' => $admin->id,
                                                                     'created_at' => (Carbon::now()),
                                                                     'type'       => 5,
                                                                     'req_id'     => $requestData->id,
                                ]);

                                $emailNotify = MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $admin->id, ' محاولة إضافة عميل ', auth()->user()->name.'  حاول إضافة العميل');
                            }
                        }

                        $gms = MyHelpers::getAllActiveGM();
                        foreach ($gms as $gm) {
                            if (MyHelpers::checkDublicateNotification($gm->id, $value, $requestData->id)) {
                                DB::table('notifications')->insert([ // add notification to send user
                                                                     'value'      => $value,
                                                                     'recived_id' => $gm->id,
                                                                     'created_at' => (Carbon::now('Asia/Riyadh')),
                                                                     'type'       => 5,
                                                                     'req_id'     => $requestData->id,
                                ]);

                                $emailNotify = MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $gm->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                            }
                        }
                    }
                    else {
                        // If The Difference Between Days is Less Than Specified
                        $value = 'يوجد محاولة لإضافة عميل لديك';
                        $user = User::find($requestData->user_id);
                        if (MyHelpers::checkDublicateNotification($user->id, $value, $requestData->id)) {
                            DB::table('notifications')->insert([
                                'value'      => $value,
                                'recived_id' => $user->id,
                                'created_at' => (Carbon::now('Asia/Riyadh')),
                                'type'       => 5,
                                'req_id'     => $requestData->id,
                            ]);
                            MyHelpers::sendEmailNotifiaction('customer_tried_to_submit_req', $user->id, ' عميل مكرر ', 'العميل حاول تسجيل طلب جديد من الموقع الإلكتروني');
                        }
                    }
                }
                // return response('no'); //  existed
            }

            return response('yes'); //  existed
            //return response()->json(['result'=>'yes','typeOfReq'=>$typeOfReq,'reqID'=>$reqID]);
        }
    }

    public function homePage()
    {
        return view('All.home');
    }

    public function printReport($id)
    {

        $check = false;

        $requestInfo = DB::table('requests')->where('requests.id', '=', $id)->first();

        // dd($requestInfo);

        if (!empty($requestInfo)) {
            $agentRequest = DB::table('users')->where('id', '=', $requestInfo->user_id)->first();

            if ($agentRequest->isTsaheel == 1) {
                $check = true;
            }
            else {
                if (auth()->user()->role == 0 && $requestInfo->user_id == auth()->user()->id) {
                    $check = true;
                }
                elseif (auth()->user()->role == 1 && $agentRequest->manager_id == auth()->user()->id) {
                    $check = true;
                }
                elseif (auth()->user()->role == 2 && MyHelpers::extractFunding($id) == auth()->user()->id) {
                    $check = true;
                }
                elseif (auth()->user()->role == 3 && MyHelpers::extractMortgage($id) == auth()->user()->id) {
                    $check = true;
                }
                elseif (auth()->user()->role == 4 || (auth()->user()->role == 8 && auth()->user()->accountant_type == 1) || auth()->user()->role == 7) {
                    $check = true;
                }
                else {
                    $check = false;
                }
            }

            if ($requestInfo->type == 'شراء' || $requestInfo->type == null) {
                $check = false;
            }
        }

        if ($check) {

            $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->leftjoin('work_sources', 'work_sources.id', '=', 'customers.work')->leftjoin('users', 'users.id', '=', 'customers.user_id')->where('requests.id', '=',
                $id)->select('customers.*', 'users.name as user_name', 'work_sources.value')->first();

            $salaryBank = DB::table('salary_sources')->where('id', $purchaseCustomer->salary_id)->first();

            if ($purchaseCustomer->birth_date_higri != null) {
                $purchaseCustomer->age = $this->calculateAge($this->convertToGregorianWithoutRequest($purchaseCustomer->birth_date_higri));
            }

            elseif ($purchaseCustomer->birth_date != null) {
                $purchaseCustomer->age = $this->calculateAge($purchaseCustomer->birth_date);
            }

            $salesagent = DB::table('requests')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('requests.id', '=', $id)->first();

            $salesmaanger = null;
            $salesmaanger_id = $salesagent->sales_manager_id;
            if ($salesmaanger_id != null) {
                $salesmaanger = DB::table('users')->where('id', '=', $salesmaanger_id)->first();
            }

            $fundingmaanger = null;
            $fundingmaanger_id = $salesagent->funding_manager_id;
            if ($fundingmaanger_id != null) {
                $fundingmaanger = DB::table('users')->where('id', '=', $fundingmaanger_id)->first();
            }

            $mortgagemaanger = null;
            $mortgagemaanger_id = $salesagent->mortgage_manager_id;
            if ($mortgagemaanger_id != null) {
                $mortgagemaanger = DB::table('users')->where('id', '=', $mortgagemaanger_id)->first();
            }

            $colloberator = DB::table('users')->where('id', '=', $requestInfo->collaborator_id)->first();

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->leftjoin('salary_sources', 'salary_sources.id', '=', 'joints.salary_id')->where('requests.id', '=', $id)->first();

            if ($purchaseJoint->birth_date_higri != null) {
                $purchaseJoint->age = $this->calculateAge($this->convertToGregorianWithoutRequest($purchaseJoint->birth_date_higri));
            }

            elseif ($purchaseJoint->birth_date != null) {
                $purchaseJoint->age = $this->calculateAge($purchaseJoint->birth_date);
            }

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $city = DB::table('cities')->where('id', $purchaseReal->city)->first();

            $realType = DB::table('real_types')->where('id', $purchaseReal->type)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->leftjoin('funding_sources', 'funding_sources.id', '=', 'fundings.funding_source')->where('requests.id', '=', $id)->first();

            $fundingSource = DB::table('funding_sources')->where('id', '=', $purchaseFun->funding_source)->first();

            $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();

            //dd($payment);

            $todaydate = (Carbon::today('Asia/Riyadh')->format('Y-m-d'));

            // dd($id);

            $pdf = PDF::loadView('All.tsaheelReport',
                compact('requestInfo', 'fundingmaanger', 'mortgagemaanger', 'colloberator', 'salesmaanger', 'todaydate', 'city', 'realType', 'salaryBank', 'salesagent', 'fundingSource', 'purchaseCustomer', 'payment', 'purchaseFun', 'purchaseReal', 'purchaseJoint'));
            return $pdf->stream('TsaheelReport.ReqNo.'.$id.'.pdf');
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function calculateAge($input)
    {

        $date = Carbon::parse($input);

        //dd($date);

        $dateString = (((int) $date->format('m') > 8) ? ((int) $date->format('m')) : ('0' + ((int) $date->format('m') + 1)));
        $dateString = (string) $dateString;
        $dateString = $dateString.'/';
        $dateString = $dateString.(((int) $date->format('d') > 9) ? $date->format('d') : ('0' + (int) $date->format('d')));
        $dateString = $dateString.'/'.$date->format('Y');

        $now = Carbon::now();

        $yearNow = $now->format('Y');
        $monthNow = $now->format('m');
        $dateNow = $now->format('d');

        $dob = Carbon::parse($dateString.substr(6, 10), (int) ($dateString.substr(0, 2)) - 1, $dateString.substr(3, 5));

        $yearDob = $dob->format('Y');
        $monthDob = $dob->format('m');
        $dateDob = $dob->format('d');
        $ageString = "";
        $yearString = "";
        $monthString = "";
        $dayString = "";

        $yearAge = $yearNow - $yearDob;

        if ($monthNow >= $monthDob) {
            $monthAge = $monthNow - $monthDob;
        }
        else {
            $yearAge--;
            $monthAge = 12 + $monthNow - $monthDob;
        }

        if ($dateNow >= $dateDob) {
            $dateAge = $dateNow - $dateDob;
        }
        else {
            $monthAge--;
            $dateAge = 31 + $dateNow - $dateDob;

            if ($monthAge < 0) {
                $monthAge = 11;
                $yearAge--;
            }
        }

        $age = [
            "years"  => $yearAge,
            "months" => $monthAge,
            "days"   => $dateAge,
        ];

        if ($age["years"] > 1 && $age["years"] < 11) {
            $yearString = MyHelpers::admin_trans(auth()->user()->id, 'years');
        }
        else {
            $yearString = MyHelpers::admin_trans(auth()->user()->id, 'year');
        }
        if ($age["months"] > 1) {
            $monthString = MyHelpers::admin_trans(auth()->user()->id, 'months');
        }
        else {
            $monthString = MyHelpers::admin_trans(auth()->user()->id, 'month');
        }
        if ($age["days"] > 1) {
            $dayString = MyHelpers::admin_trans(auth()->user()->id, 'days');
        }
        else {
            $dayString = MyHelpers::admin_trans(auth()->user()->id, 'day');
        }

        if (($age["years"] > 0) && ($age["months"] > 0) && ($age["days"] > 0)) {
            $ageString = $age["years"].$yearString.", ".$age["months"].$monthString.",".MyHelpers::admin_trans(auth()->user()->id, 'and').$age["days"].$dayString.MyHelpers::admin_trans(auth()->user()->id, 'old');
        }
        elseif (($age["years"] == 0) && ($age["months"] == 0) && ($age["days"] > 0)) {
            $ageString = MyHelpers::admin_trans(auth()->user()->id, 'Only').$age["days"].$dayString.MyHelpers::admin_trans(auth()->user()->id, 'old');
        }
        elseif (($age["years"] > 0) && ($age["months"] == 0) && ($age["days"] == 0)) {
            $ageString = $age["years"].$yearString.' '.MyHelpers::admin_trans(auth()->user()->id, 'old');
        }
        elseif (($age["years"] > 0) && ($age["months"] > 0) && ($age["days"] == 0)) {
            $ageString = $age["years"].$yearString.' '.MyHelpers::admin_trans(auth()->user()->id, 'and').$age["months"].$monthString.MyHelpers::admin_trans(auth()->user()->id, 'old');
        }
        elseif (($age["years"] == 0) && ($age["months"] > 0) && ($age["days"] > 0)) {
            $ageString = $age["months"].$monthString.MyHelpers::admin_trans(auth()->user()->id, 'and').$age["days"].$dayString.MyHelpers::admin_trans(auth()->user()->id, 'old');
        }
        elseif (($age["years"] > 0) && ($age["months"] == 0) && ($age["days"] > 0)) {
            $ageString = $age["years"].$yearString.' '.MyHelpers::admin_trans(auth()->user()->id, 'and').$age["days"].$dayString.MyHelpers::admin_trans(auth()->user()->id, 'old');
        }
        elseif (($age["years"] == 0) && ($age["months"] > 0) && ($age["days"] == 0)) {
            $ageString = $age["months"].$monthString.MyHelpers::admin_trans(auth()->user()->id, 'old').".";
        }
        else {
            $ageString = MyHelpers::admin_trans(auth()->user()->id, 'Could not calculate age')."!";
        }

        return $ageString;
    }

    //-------------Hijiri Date-----------------------------------

    public function convertToGregorianWithoutRequest($hijri)
    {
        //        return($request->hijri);
        $date = $hijri;
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $output = Hijri::convertToGregorian((int) $day, (int) $month, (int) $year);

        $year2 = substr($output, 0, 4);
        $month2 = substr($output, 5, 2);
        $day2 = substr($output, 8, 2);

        return $year2.'-'.$month2.'-'.$day2;
    }

    public function aqarReport($id)
    {
        $check = false;
        $requestInfo = DB::table('requests')->leftjoin('request_source', 'request_source.id', '=', 'requests.source')->where('requests.id', '=', $id)->first();
        if (!empty($requestInfo)) {

            $agentRequest = DB::table('users')->where('id', '=', $requestInfo->user_id)->first();

            if (auth()->user()->role == 0 && $requestInfo->user_id == auth()->user()->id) {
                $check = true;
            }
            elseif (auth()->user()->role == 1 && $agentRequest->manager_id == auth()->user()->id) {
                $check = true;
            }
            elseif (auth()->user()->role == 2 && MyHelpers::extractFunding($id) == auth()->user()->id) {
                $check = true;
            }
            elseif (auth()->user()->role == 3 && MyHelpers::extractMortgage($id) == auth()->user()->id) {
                $check = true;
            }
            elseif (auth()->user()->role == 4 || (auth()->user()->role == 8 && auth()->user()->accountant_type == 1) || auth()->user()->role == 7) {
                $check = true;
            }
            else {
                $check = false;
            }
        }

        if ($check) {
            $date = Carbon::parse($requestInfo->recived_date_report);
            $now = Carbon::now();
            $counter = $date->diffInDays($now);

            if ($requestInfo->counter_report != $counter) {
                DB::table('requests')->where('id', $requestInfo->id)->update(['counter_report' => $counter,]);
                $requestInfo = DB::table('requests')->where('requests.id', '=', $id)->first();
            }

            $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->leftjoin('users', 'users.id', '=', 'customers.user_id')->where('requests.id', '=', $id)->select('customers.*', 'users.name as user_name')->first();

            $salaryBank = DB::table('salary_sources')->where('id', $purchaseCustomer->salary_id)->first();

            //dd($salaryBank);

            //  dd($purchaseCustomer->birth_date_higri);
            // dd($this->convertToGregorianWithoutRequest($purchaseCustomer->birth_date_higri));

            if ($purchaseCustomer->birth_date_higri != null) {
                $purchaseCustomer->age = $this->calculateAge($this->convertToGregorianWithoutRequest($purchaseCustomer->birth_date_higri));
            }

            elseif ($purchaseCustomer->birth_date != null) {
                $purchaseCustomer->age = $this->calculateAge($purchaseCustomer->birth_date);
            }

            $salesagent = DB::table('requests')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('requests.id', '=', $id)->first();

            $salesmaanger = null;
            $salesmaanger_id = $salesagent->sales_manager_id;
            if ($salesmaanger_id != null) {
                $salesmaanger = DB::table('users')->where('id', '=', $salesmaanger_id)->first();
            }

            $fundingmaanger = null;
            $fundingmaanger_id = $salesagent->funding_manager_id;
            if ($fundingmaanger_id != null) {
                $fundingmaanger = DB::table('users')->where('id', '=', $fundingmaanger_id)->first();
            }

            $mortgagemaanger = null;
            $mortgagemaanger_id = $salesagent->mortgage_manager_id;
            if ($mortgagemaanger_id != null) {
                $mortgagemaanger = DB::table('users')->where('id', '=', $mortgagemaanger_id)->first();
            }

            $colloberator = DB::table('users')->where('id', '=', $requestInfo->collaborator_id)->first();

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->leftjoin('salary_sources', 'salary_sources.id', '=', 'joints.salary_id')->where('requests.id', '=', $id)->first();

            if ($purchaseJoint->birth_date_higri != null) {
                $purchaseJoint->age = $this->calculateAge($this->convertToGregorianWithoutRequest($purchaseJoint->birth_date_higri));
            }

            elseif ($purchaseJoint->birth_date != null) {
                $purchaseJoint->age = $this->calculateAge($purchaseJoint->birth_date);
            }

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $city = DB::table('cities')->where('id', $purchaseReal->city)->first();

            $realType = DB::table('real_types')->where('id', $purchaseReal->type)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->leftjoin('funding_sources', 'funding_sources.id', '=', 'fundings.funding_source')->where('requests.id', '=', $id)->first();

            $fundingSource = DB::table('funding_sources')->where('id', '=', $purchaseFun->funding_source)->first();

            $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();

            //dd($payment);

            $todaydate = (Carbon::today('Asia/Riyadh')->format('Y-m-d'));

            // dd($id);

            $pdf = PDF::loadView('All.aqarCompletedReport',
                compact('requestInfo', 'fundingmaanger', 'mortgagemaanger', 'colloberator', 'salesmaanger', 'todaydate', 'city', 'realType', 'salaryBank', 'salesagent', 'fundingSource', 'purchaseCustomer', 'payment', 'purchaseFun', 'purchaseReal', 'purchaseJoint'));
            return $pdf->stream('aqarCompletedReport.ReqNo.'.$id.'.pdf');
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }
    }

    public function convertToGregorian(Request $request)
    {
        //        return($request->hijri);
        $date = $request->hijri;
        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        $output = Hijri::convertToGregorian((int) $day, (int) $month, (int) $year);

        $year2 = substr($output, 0, 4);
        $month2 = substr($output, 5, 2);
        $day2 = substr($output, 8, 2);

        return $year2.'-'.$month2.'-'.$day2;
    }

    public function convertToHijri(Request $request)
    {
        //        return($request->gregorian);
        $date = Hijri::convertToHijri($request->gregorian);

        $year = substr($date, 0, 4);
        $month = substr($date, 5, 2);
        $day = substr($date, 8, 2);

        return $year.'-'.$month.'-'.$day;
    }

    //-------------END Hijiri Date-----------------------------------

    public function delNotify(Request $request)
    {

        $notify = DB::table('notifications')->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('notifications.id', $request->id)->select('notifications.*', 'customers.name')->first();
        //->where('status', 1)
        //->delete();

        if ($notify->name == null) // Notification from pending request
        {
            $result = DB::table('notifications')->where('id', $request->id)->delete();
        }
        else {
            $result = DB::table('notifications')->where('id', $request->id)->where('status', 1)->delete();
        }

        return response($result); // if 1: update succesfally

    }

    ///////////////////////////////////

    public function delNotifys(Request $request)
    {

        $result = DB::table('notifications')->where('status', 1)->whereIn('id', $request->array)->delete();

        return response($result); // if 1: update succesfally

    }

    public function updateNotificationToDone(Request $request)
    {

        $updateNotifty = DB::table('notifications')->where('id', $request->id)->update(['is_done' => 1, 'status' => 1]);

        return response($updateNotifty); // if 1: update succesfally

    }

    public function updateNotificationToDoneArray(Request $request)
    {

        $updateNotiftys = DB::table('notifications')->whereIn('id', $request->array)->update(['is_done' => 1, 'status' => 1]);

        return response($updateNotiftys); // if 1: update succesfally

    }

    public function show_q_task(Request $request, $id)
    {

        $tasks = DB::table('tasks')->where('tasks.id', $id)->join('users as recived', 'recived.id', 'tasks.recive_id')->join('users as sent', 'sent.id', 'tasks.user_id')->select('recived.name as recname', 'recived.role as recrole', 'recived.id as recid', 'sent.id as sentid', 'sent.name as sentname',
            'sent.role as sentrole', 'tasks.id', 'tasks.req_id', 'tasks.status')->first();

        /*
        if ($tasks->status == 0 && $tasks->recid == auth()->user()->id)
            $updateTask =  task::whereId($id)
                ->update([
                    'status' => 1
                ]);
                */

        //dd($tasks);

        $task_contents = DB::table('task_contents')->where('task_contents.task_id', $id)->orderBy('created_at', 'asc')->get();

        $task_content_last = DB::table('task_contents')->where('task_contents.task_id', $id)->get()->last();

        $reqInfo = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('customers', 'customers.id', 'requests.customer_id')->where('quality_reqs.id', $tasks->req_id)->select('quality_reqs.req_id', 'customers.name', 'customers.mobile')->first();

        //dd($reqInfo);

        return view('QualityManager.showtask', compact('id', 'tasks', 'task_contents', 'task_content_last', 'reqInfo'));
    }

    public function update_task_content(Request $request)
    {

        $rules = [
            'content' => 'required',
        ];

        $customMessages = [
            'content.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $maxConent = task_content::where('task_id', $request->id)->max('id');

        $updateContent = task_content::whereId($maxConent)->update([
            'task_contents_status' => 2,
        ]);

        $newContent = task_content::create([
            'content'         => $request->get('content'),
            'date_of_content' => Carbon::now('Asia/Riyadh'),
            'task_id'         => $request->id,
        ]);

        if (!$newContent) {
            return back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'com'));
        }

        else {
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord(auth()->id())) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord(auth()->id());
            }
            MyHelpers::incrementDailyPerformanceColumn(auth()->id(), 'replayed_task',$request->taskId);
            return redirect()->route('all.show_q_task', $request->id)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function update_task_note(Request $request)
    {

        $rules = [
            'user_note' => 'required',
        ];

        $customMessages = [
            'user_note.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateTask = task::whereId($request->taskId)->update([
            'status' => 2,
        ]);

        $updateContent = task_content::whereId($request->id)->update([
            'date_of_note'         => Carbon::now('Asia/Riyadh'),
            'user_note'            => $request->user_note,
            'task_contents_status' => 1,
        ]);

        return redirect()->route('all.show_q_task', $request->taskId)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function updatePersonalFundingData(Request $request)
    {
        $reqID = $request->reqID;
        $fundingPersonal = $request->personalFunding;
        $fundingReq = DB::table('requests')->where('id', $reqID)->first();
        $fundId = $fundingReq->fun_id;

        $updatePersonalFunding = DB::table('fundings')->where('id', $fundId)->update([
            'personalFun_cost' => $fundingPersonal,
        ]);

        $this->records($reqID, 'fundPers', $fundingPersonal, 'حاسبة الرهن العقاري');

        return response(['personalFunding' => $fundingPersonal, 'status' => $updatePersonalFunding, 'id' => $reqID]);

    }

    public function records($reqID, $coloum, $value, $comment = null)
    {
        //LAST UPDATE RECORD OF THIS REQ
        $lastUpdate = DB::table('req_records')->where('req_id', '=', $reqID)->where('colum', '=', $coloum)->max('id'); //to retrive id of last record update of comment

        if ($lastUpdate != null) {
            $rowOfLastUpdate = DB::table('req_records')->where('id', '=', $lastUpdate)->first();
        } //we get here the row of this id
        //

        if (session('existing_user_id')) {
            $userSwitch = session('existing_user_id');
        }
        else {
            $userSwitch = null;
        }

        if ($lastUpdate == null && ($value != null)) {
            DB::table('req_records')->insert([
                'colum'          => $coloum,
                'user_id'        => (auth()->user()->id),
                'value'          => $value,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $reqID,
                'user_switch_id' => $userSwitch,
                'comment'        => $comment,
            ]);
        }

        if ($lastUpdate != null) {
            if (($rowOfLastUpdate->value) != $value) {

                DB::table('req_records')->insert([
                    'colum'          => $coloum,
                    'user_id'        => (auth()->user()->id),
                    'value'          => $value,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $reqID,
                    'user_switch_id' => $userSwitch,
                    'comment'        => $comment,
                ]);
            }
        }

        //  dd($rowOfLastUpdate);
    }

    public function sendMortgageData(Request $request)
    {
        $reqID = $request->reqID;
        $visa = $request->visa_mortgage;
        $realCost = $request->real_property_cost;
        $fundingPersonal = $request->personal_funding;
        $mortgagedValue = $request->mortgaged_value;
        $realDisposition = $request->Real_estate_disposition_value;
        $purchaseTax = $request->purchase_tax_value;
        $firstBatch = $request->first_batch_value;
        $personalMortgage = $request->personal_mortgage;
        $carMortgage = $request->car_mortgage;
        $otherMortgageVal = $request->beside_value;
        $otherFeesMortgage = $request->other_fees;
        $fundingReq = DB::table('requests')->where('id', $reqID)->first();
        $payId = $fundingReq->payment_id;
        $realId = $fundingReq->real_id;
        $fundId = $fundingReq->fun_id;

        $updatePersonalFunding = DB::table('fundings')->where('id', $fundId)->update([
            'personalFun_cost' => $fundingPersonal,
        ]);

        $updateCost = DB::table('real_estats')->where('id', $realId)->update([
            'cost' => $realCost,
        ]);
        $updateMortgageInfo = DB::table('prepayments')->where('id', $payId)->update([
            'realLo'                             => $mortgagedValue,
            'mortgaged_percentage'               => $request->mortgaged_percentage,
            'Real_estate_disposition_value'      => $realDisposition,
            'Real_estate_disposition_percentage' => $request->Real_estate_disposition_percentage,
            'purchase_tax_value'                 => $purchaseTax,
            'purchase_tax_percentage'            => $request->purchase_tax_percentage,
            'prepaymentVal'                      => $firstBatch,
            'first_batch_percentage'             => $request->first_batch_percentage,
            'first_batch_from_realValue'         => $request->first_batch_from_realValue,
            'personalLo'                         => $personalMortgage,
            'perlo_percentage'                   => $request->perlo_percentage,
            'carLo'                              => $carMortgage,
            'car_percentage'                     => $request->car_percentage,
            'visa'                               => $visa,
            'visa_percentage'                    => $request->visa_percentage,
            'other'                              => $otherMortgageVal,
            'beside_percentage'                  => $request->beside_percentage,
            'other_fees'                         => $otherFeesMortgage,
            'mortgage_debt'                      => $request->mortgage_debt,
            'mortgage_debt_with_tax'             => $request->mortgage_debt_with_tax,
            'debt'                               => $request->mortgage_debt,
            //'total_taxes_mortgage' => $request->total_taxes_mortgage,
            'net_to_customer'                    => $request->net_to_customer,
            'netCustomer'                        => $request->net_to_customer,
        ]);
        if ($visa != 0) {
            $this->records($reqID, 'preVisa', $visa, 'حاسبة الرهن العقاري');
        }
        $this->records($reqID, 'realCost', $realCost, 'حاسبة الرهن العقاري');
        if ($mortgagedValue != 0) {
            $this->records($reqID, 'realLo', $mortgagedValue, 'حاسبة الرهن العقاري');
        }
        $this->records($reqID, 'realDisposition', $realDisposition, 'حاسبة الرهن العقاري');
        $this->records($reqID, 'purchaseTax', $purchaseTax, 'حاسبة الرهن العقاري');
        $this->records($reqID, 'preValue', $firstBatch, 'حاسبة الرهن العقاري');
        $this->records($reqID, 'personalLo', $personalMortgage, 'حاسبة الرهن العقاري');
        $this->records($reqID, 'carLo', $carMortgage, 'حاسبة الرهن العقاري');
        $this->records($reqID, 'otherLo', $otherMortgageVal, 'حاسبة الرهن العقاري');
        $this->records($reqID, 'otherFees', $otherFeesMortgage, 'حاسبة الرهن العقاري');
        $this->records($reqID, 'fundPers', $fundingPersonal, 'حاسبة الرهن العقاري');
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Sending successfully'), 'status' => $updateMortgageInfo, 'id' => $reqID]);
    }

    public function getNegativeCommentWithAgent(Request $request)
    {

        $get_agent_and_status_of_show = MyHelpers::hideCommentOfNegativeClassificationOfAddCustomer($request->req_id, $request->comment, $request->user_id, $request->class_id_agent);

        return response($get_agent_and_status_of_show);

    }


    //********************************************************************
    // Task-45 Announcements Task Start
    //********************************************************************
    public function announcements()
    {
        $announcementsSeen = AnnounceSeen::where('user_id', auth()->id())->pluck('announce_id')->toArray();
        $announcements = Announcement::whereIn('id', $announcementsSeen)->get();

        return view('All.allAnnouncements', compact('announcements'));
    }

    public function singleAnnouncement($id)
    {
        $announce = Announcement::where('id', $id)->first();

        $data = explode(".",$announce->attachment);
        $extention =end($data);
        $exists = file_exists(storage_path('app/public/'.$announce->attachment));

        return view('All.singleAnnouncements', compact('announce','extention',"exists"));
    }
    public function openFile($id)
    {
        $request = Announcement::findOrFail($id);
        try {
            $filename = $request->attachment;
            return response()->file(storage_path('app/public/'.$filename));
        }catch (\Exception $e){
            return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');
        }
    }
    public function downloadFile($id)
    {
        $request = Announcement::findOrFail($id);

        try {
            $filename = $request->attachment;
            return response()->download(storage_path('app/public/'.$filename));
        }catch (\Exception $e){
            return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');

        }
    }
    public function allAnnouncements_datatable()
    {
        $announcementsSeen = AnnounceSeen::where('user_id', auth()->id())->pluck('announce_id')->toArray();
        $announcements = Announcement::whereIn('id', $announcementsSeen)->get();
        return \Yajra\DataTables\DataTables::of($announcements)->setRowId(function ($announcements) {
            return $announcements->id;
        })->addColumn('idn', function ($row) {
            static $var = 1;
            return $var++;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

            if ($row->status == 1) {
                $data = $data.'<span class="item pointer Green" id="active" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'not active').'">
                                    <i class="fas fa-exclamation-circle"></i></a>
                                </span>';
            }

            else {
                $data = $data.'<span class="item pointer Red" id="active" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'active').'">
                                     <i class="fas fa-exclamation-circle"></i></a>
                                </span>';
            }

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                                <a href="'.route('admin.openAnnouncePage', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="edit" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                <a href="'.route('admin.editAnnouncePage', $row->id).'"><i class="fas fa-edit"></i></a></span>';

            $data = $data.'<span class="item pointer" id="archive" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Delete').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';

            $data = $data.'</div>';
            return $data;
        })->editColumn('status', function ($row) {
            if ($row->status == 0) {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'not active');
            }
            else {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'active');
            }
            return $data;
        })->editColumn('end_at', function ($row) {
            return $row->end_at ?? '-';
        })->editColumn('action', function ($row) {
            $data = '<div id="tableAdminOption" class="tableAdminOption">';
            $data .= '<span class="item pointer" id="open" data-id="54" data-toggle="tooltip" data-placement="top" title="" data-original-title=" عرض">
                                <a href="'.route('all.announcement', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            $data .= $row->attachment != null ? '<span class="item pointer" id="open" data-id="54" data-toggle="tooltip" data-placement="top" title="" data-original-title="تحميل الملف">
                                <a href="'.$row->attachment.'"><i class="fas fa-download"></i></a></span>' : '<span class="item pointer" style="background: transparent" id="open" data-id="54" data-toggle="tooltip" data-placement="top" title="" data-original-title="تحميل الملف">
                       </span>';
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }
    //********************************************************************
    //  Task-45 Announcements Task End
    //********************************************************************
    //********************************************************************
    //  Techniqal Support Start
    //********************************************************************
    public function SendTechnicalSupport(Request $request)
    {
        // return $request->all();
        $validator = \Validator::make($request->all(), ['descrebtion' => 'required']);
        if($validator->fails())
        {
            return ["message" => implode(' , ', $validator->errors()->all()), "code" => 402];
        }
        try {
            $user=auth()->user();
            if(isset($user) && in_array($user->role,  [0,1,2,3,5,6,13]) ){
                $helpDesk = helpDesk::create([
                    'name'        => $user->name,
                    'email'       => $user->email,
                    'descrebtion' => $request->descrebtion,
                    'msg_type' => $request->msg_type,
                    'technical_owner_id' => auth()->id(),
                ]);
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $name = 'helpdesk_'.\Str::random(32).'.'.$file->getClientOriginalExtension();
                        $path = asset('uploads/'.$name);
                        $file->move(public_path('uploads/'), $name);
                        $imageModel = new Image();
                        $imageModel->image_path = $path;
                        $imageModel->imageable_id = $helpDesk->id;
                        $imageModel->imageable_type = 'App\helpDesk';
                        $imageModel->save();
                    }
                }

                if(isset($helpDesk)){
                    $admins = Helper::getAllActiveAdmin();
                    foreach ($admins as $admin) {
                        DB::table('notifications')->insert([
                            'value'      => 'الدعم الفني: '. $user->name,
                            'recived_id' => $admin->id,
                            'created_at' => now('Asia/Riyadh'),
                            'type'       => 8,
                            'req_id'     => $helpDesk->id,
                        ]);
                        Helper::sendEmailNotifiaction('new_help_desk', $admin->id, 'لديك طلب دعم فني جديد  ', 'طلب دعم فني ');
                    }
                }

                return ["message" => "تم تسجيل رسالتكم بنجاح", "code" => 200];

            }else{
                return ["message" => "ليس لديك صلاحية للدخول", "code" => 422];
            }

        } catch (\Exception $e) {
            return ["message" => "حدث خطأ ما , من فضلك حاول بوقت لاحق!", "code" => 422];
        }
    }
    //********************************************************************
    //  Techniqal Support End
    //********************************************************************
    //********************************************************************
    //  Help Desks Start
    //********************************************************************
    public function openHelpDeskPage($id)
    {
        $helpDesk = helpDesk::where('help_desks.id', $id)->leftJoin('users', 'users.id', 'help_desks.user_id')->select('help_desks.*', 'users.name as username')->first();
        // open req
        if ($helpDesk->status == 0) {
            helpDesk::where('id', $id)->update(['status' => 1]);
        }

        DB::table('notifications')->where('status', 0)->where('type', 8)->where('req_id', $id)->update(['status' => 1]);
        $reqInfo = null;

        if($helpDesk->parent_id)
        $helpDesk = helpDesk::find($helpDesk->parent_id);
        return view('All.helpDesk.openHelpDeskPage', compact('helpDesk', 'reqInfo'));
    }

    public function postReplayHelpDesk(Request $request)
    {
        $rules = ['replay' => ['required']];

        $customMessages = ['replay.required' => MyHelpers::guest_trans('Mobile filed is required'),];

        $validator = Validator::make($request->all(), $rules, $customMessages);
        $validator->validate();

        // $updateReq = helpDesk::where('id', $request->reqID)->update(['replay' => null, 'status' => 2, 'user_id' => auth()->user()->id, 'date_replay' => Carbon::now('Asia/Riyadh')]);
        $row = helpDesk::where('id', $request->reqID)->first();
        // new chat
        if($row->technical_owner_id)
        {
            $new_row = new helpDesk;
            $new_row->name = auth()->user()->name;
            $new_row->parent_id = $request->reqID;
            $new_row->descrebtion = $request->replay;
            $new_row->technical_owner_id = $row->technical_owner_id;
            $new_row->user_id = $row->user_id;
            $new_row->save();
            // send notification to employee
            DB::table('notifications')->insert([
                'value'      => 'الدعم الفني: رد من ' . auth()->user()->name,
                'recived_id' => $row->user_id,
                'created_at' => now('Asia/Riyadh'),
                'type'       => 8,
                'req_id'     => $new_row->id,
            ]);
        }
        try{
            MyHelpers::sendEmailNotifiactionByEmailOnly($request->email, $request->replay, ' تم الرد على طلب الدعم الفني - شركة الوساطة العقارية');

        }catch(\Exception $e){}

        // if ($updateReq) {
            return redirect()->back()->with('message', 'تم التحديث بنجاح');
        // }
        // else {
        //     return redirect()->back()->with('message2', 'حدث خطأ ، حاول مجددا');
        // }
    }

    //********************************************************************
    //  Help Desks End
    //********************************************************************
}
