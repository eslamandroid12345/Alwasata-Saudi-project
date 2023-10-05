<?php

namespace App\Http\Controllers\API;

use App\Ask;
use App\AskAnswer;
use App\Http\Controllers\Controller;
use App\Model\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SurveyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function checkRequestOpenOrNot()
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomerAuth = Customer::where('id', $customerId)->first();
        $getRequestInfo = \App\request::where('customer_id', $customerId)->first();
        if ($checkCustomerAuth) {
            $checkCancelStatus = AskAnswer::where(['batch' => 0, 'request_id' => $getRequestInfo->id, 'customer_id' => $customerId])
                ->get();
            $checkOrderStatus = DB::table('notifications')
                ->where(['type' => 6, 'req_id' => $getRequestInfo->id, 'request_type' => 22])
                ->get();
            if ($checkCancelStatus->count() == 0 && $checkOrderStatus->count() > 0) {
                $status = 1; // show cancel Button
                return self::successResponse(200, true, null, $status);
            }
            elseif ($checkCancelStatus->count() > 0 && $checkOrderStatus->count() == 0) {
                $status = 0; // show reopen button
                return self::successResponse(200, true, null, $status);
            }
            else {
                $status = null; // hide both button
                return self::successResponse(200, true, null, $status);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للدخول لهذه الصفحة", null);
        }
    }

    public function getAllAsksQuestions()
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomerAuth = Customer::where('id', $customerId)->first();
        $getRequestID = \App\request::where('customer_id', $customerId)->where('class_id_agent', '!=', 13)
            ->orWhere('class_id_agent', '=', null)
            ->first();
        if ($checkCustomerAuth) {
            if ($getRequestID) {
                if (AskAnswer::where(['batch' => 0, 'request_id' => $getRequestID->user_id, 'customer_id' => $customerId])->count() == 0) {
                    $questions = Ask::where('active', 1)->get();
                    if ($questions->count() <= 0) {
                        return self::errorResponse(422, false, "لا توجد اي نتائج", null);
                    }
                    else {
                        return self::successResponse(200, true, null, $questions);
                    }
                }
            }
            else {
                return self::errorResponse(422, false, "الطلب مصنف بأنه لا يرغب من قبل الاستشاري", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للدخول لهذه الصفحة", null);
        }
    }

    public function cancelRequestWithSurvey(Request $request)
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomerAuth = Customer::where('id', $customerId)->first();
        $questions = Ask::where('active', 1)->get();
        $getRequestID = \App\request::where('customer_id', $customerId)->where('class_id_agent', '!=', 13)
            ->orWhere('class_id_agent', '=', null)
            ->first();
        if ($checkCustomerAuth) {
            if ($getRequestID) {
                if (AskAnswer::where(['batch' => 0, 'request_id' => $getRequestID->id, 'customer_id' => $customerId])->count() == 0) {
                    $requestData = ['data' => $request->json()->all()];
                    foreach ($requestData['data'] as $data) {
                        if ($data['answers'] == null) {
                            foreach ($questions as $key => $question) {
                                AskAnswer::create([
                                    'answer'      => 2,
                                    'ask_id'      => $question->id,
                                    'user_id'     => $getRequestID->user_id,
                                    'surveyQC'    => $questions->count(),
                                    'request_id'  => $getRequestID->id,
                                    'customer_id' => $customerId,
                                ]);
                            }
                            if ($getRequestID->class_id_agent != 13) {
                                DB::table('notifications')->insert([
                                    'value'         => 'طلب العميل إلغاء الطلب ',
                                    'recived_id'    => $getRequestID->user_id,
                                    'receiver_type' => 'web',
                                    'created_at'    => (Carbon::now('Asia/Riyadh')),
                                    'type'          => 5,
                                    'reminder_date' => null,
                                    'req_id'        => $getRequestID->id,
                                ]);
                            }
                            $getRequestID->update([
                                'class_id_agent' => 65,
                                'statusReq'      => 2,
                                'add_to_archive' => Carbon::now('Asia/Riyadh'),
                            ]);
                            DB::table('request_histories')->insert([
                                'title'          => 'تم إلغاء الطلب',
                                'user_id'        => null,
                                'recive_id'      => null,
                                'history_date'   => (Carbon::now('Asia/Riyadh')),
                                'content'        => 'العميل ألغى الطلب',
                                'req_id'         => $getRequestID->id,
                                'user_switch_id' => null,
                            ]);
                            DB::table('req_records')->insert([
                                'colum'          => 'class_agent',
                                'user_id'        => null,
                                //'value'          => 'ملغى من قبل العميل',
                                'value'          => 65,
                                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                                'req_id'         => $getRequestID->id,
                                'user_switch_id' => null,
                                'comment'        => 'تلقائي - عن طريق النظام',
                            ]);
                            return self::successResponse(200, true, "تم إرسال إلغاء الطلب بنجاح", null);
                        }
                        else {
                            foreach ($data['answers'] as $key => $value) {
                                $surveyData = [
                                    'ask_id'      => $value['ask_id'],
                                    'answer'      => $value['answer'],
                                    'request_id'  => $getRequestID->id,
                                    'customer_id' => $customerId,
                                    'user_id'     => $getRequestID->user_id,
                                    'surveyQC'    => $questions->count(),
                                    'created_at'  => (Carbon::now('Asia/Riyadh')),
                                    'updated_at'  => (Carbon::now('Asia/Riyadh')),
                                ];
                                AskAnswer::insert($surveyData);
                            }
                            if ($getRequestID->class_id_agent != 13) {
                                DB::table('notifications')->insert([
                                    'value'         => 'طلب العميل إلغاء الطلب ',
                                    'recived_id'    => $getRequestID->user_id,
                                    'receiver_type' => 'web',
                                    'created_at'    => (Carbon::now('Asia/Riyadh')),
                                    'type'          => 5,
                                    'reminder_date' => null,
                                    'req_id'        => $getRequestID->id,
                                ]);
                            }
                            $getRequestID->update([
                                'class_id_agent' => 65,
                                'statusReq'      => 2,
                                'add_to_archive' => Carbon::now('Asia/Riyadh'),
                            ]);
                            DB::table('request_histories')->insert([ // add to request history
                                                                     'title'          => 'تم إلغاء الطلب',
                                                                     'user_id'        => null,
                                                                     'recive_id'      => null,
                                                                     'history_date'   => (Carbon::now('Asia/Riyadh')),
                                                                     'content'        => 'العميل ألغى الطلب',
                                                                     'req_id'         => $getRequestID->id,
                                                                     'user_switch_id' => null,
                            ]);
                            DB::table('req_records')->insert([
                                'colum'          => 'class_agent',
                                'user_id'        => null,
                                //'value'          => 'ملغى من قبل العميل',
                                'value'          => 65,
                                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                                'req_id'         => $getRequestID->id,
                                'user_switch_id' => null,
                                'comment'        => 'تلقائي - عن طريق النظام',
                            ]);
                            return self::successResponse(200, true, "تم إرسال إلغاء الطلب بنجاح", null);
                        }
                    }
                }
                else {
                    return self::errorResponse(422, false, "الطلب ملغي بالفعل", null);
                }
            }
            else {
                return self::errorResponse(422, false, "الطلب مصنف بأنه لا يرغب من قبل الاستشاري", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للدخول لهذه الصفحة", null);
        }
    }

    public function reopenRequest()
    {
        $customerId = Auth::guard('api')->id();
        $checkCustomerAuth = Customer::where('id', $customerId)->first();
        $getRequestInfo = \App\request::where('customer_id', $customerId)->first();
        $checkOrderStatus = DB::table('notifications')
            ->where(['type' => 6, 'req_id' => $getRequestInfo->id, 'request_type' => 22])
            ->get();

        if ($checkCustomerAuth) {
            if ($checkOrderStatus->count() == 0) {
                $requests = \App\request::find($getRequestInfo->id);
                $requests->update([
                    'class_id_agent'      => null,
                    'statusReq'           => 1,
                    'remove_from_archive' => Carbon::now('Asia/Riyadh'),
                ]);
                DB::table('request_histories')->insert([ // add to request history
                                                         'title'          => 'فتح الطلب',
                                                         'user_id'        => null,
                                                         'recive_id'      => null,
                                                         'history_date'   => (Carbon::now('Asia/Riyadh')),
                                                         'content'        => 'العميل أعاد فتح الطلب',
                                                         'req_id'         => $requests->id,
                                                         'user_switch_id' => null,
                ]);
                AskAnswer::where('request_id', $requests->id)->update(['batch' => DB::raw('batch+1')]);
                DB::table('notifications')->insert([
                    'value'         => 'طلب العميل إعادة فتح الطلب من جديد ',
                    'recived_id'    => $requests->user_id,
                    'request_type'  => 22,
                    'receiver_type' => 'web',
                    'created_at'    => (Carbon::now('Asia/Riyadh')),
                    'type'          => 5,
                    'reminder_date' => null,
                    'req_id'        => $requests->id,
                ]);
                return self::successResponse(200, true, "تم ارسال اعادة فتح الطلب بنجاح", null);
            }
            else {
                return self::errorResponse(401, false, "طلبك مفتوح بالفعل, وجاري مراجعته من قبل الاستشاري", null);
            }
        }
        else {
            return self::errorResponse(401, false, "ليس لديك صلاحية للدخول لهذه الصفحة", null);
        }
    }
}
