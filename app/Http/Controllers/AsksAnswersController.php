<?php

namespace App\Http\Controllers;

use App\Ask;
use App\AskAnswer;
use App\Models\Customer;
use App\Models\User;
use App\Traits\General;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use View;

class AsksAnswersController extends Controller
{
    use General;

    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content', 'layouts.Customermaster', 'Customer.customerIndexPage', 'Customer.fundingReq.customerReqLayout'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.Customermaster', 'Customer.fundingReq.customerReqLayout'],
        ]);
    }

    public function index($requestId)
    {
        if (AskAnswer::where(['batch' => 0, 'request_id' => $requestId, 'customer_id' => auth('customer')->user()->id])->count() == 0) {
            $questions = Ask::where('active', 1)->get();
            return view('Customer.survey.index', [
                'questions' => $questions,
                'count'     => $questions->count(),
                'requestId' => $requestId,
            ]);
        }
        else {
            return redirect()->back();
        }
    }

    public function store(Request $request)
    {
        $requests = \App\request::find($request->request_id);
        $userModel = User::findOrFail($requests->user_id);
        //$customer = DB::table('customers')->where('id',$requests->customer_id)->first();
        $customer = Customer::findOrFail($requests->customer_id);
        $questions = Ask::where('active', 1)->get();

        if ($request->has('customer_reason_for_cancel')) {
            $updateReason = DB::table('requests')->where('id', $request->request_id)->update(['customer_reason_for_cancel' => $request->customer_reason_for_cancel]);
        }

        if ($request->has('empty')) {
            foreach ($questions as $key => $question) {
                AskAnswer::create([
                    'answer'      => 2,
                    'ask_id'      => $question->id,
                    'user_id'     => $requests->user_id,
                    'surveyQC'    => $request->count,
                    'customer_id' => $request->user_id,
                    'request_id'  => $request->request_id,
                ]);
            }
        }
        else {

            if ($request->has('answer')) {
                foreach ($request->answer as $key => $item) {
                    AskAnswer::create([
                        'answer'      => $item,
                        'ask_id'      => $key,
                        'user_id'     => $requests->user_id,
                        'surveyQC'    => $request->count,
                        'customer_id' => $request->user_id,
                        'request_id'  => $request->request_id,
                    ]);
                }
            }
        }

        if ($requests->class_id_agent != 13) {
            DB::table('notifications')->insert([
                'value'         => 'طلب العميل إلغاء الطلب ',
                'recived_id'    => $requests->user_id,
                'receiver_type' => 'web',
                'created_at'    => (Carbon::now('Asia/Riyadh')),
                'type'          => 5,
                'reminder_date' => null,
                'req_id'        => $requests->id,
            ]);
            $this->fcm_send($userModel->getPushTokens(), $customer->name."  إشعار من عميلك   ", "طلب العميل إلغاء الطلب");
        }
        $requests->update([
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
                                                 'req_id'         => $requests->id,
                                                 'user_switch_id' => null,
        ]);
        DB::table('req_records')->insert([
            'colum'          => 'class_agent',
            'user_id'        => null,
            //'value'          => 'ملغى من قبل العميل',
            'value'          => 65,
            'updateValue_at' => Carbon::now('Asia/Riyadh'),
            'req_id'         => $requests->id,
            'user_switch_id' => null,
            'comment'        => 'تلقائي - عن طريق النظام',
        ]);
        session()->flash('message', 'تم إرسال إلغاء الطلب بنجاح');
        return redirect(route('customer.account'));
    }

    public function reopen($id)
    {
        $requests = \App\request::find($id);
        //$customer = DB::table('customers')->where('id', $requests->customer_id)->first();
        $userModel = User::findOrFail($requests->user_id);
        $customer = Customer::findOrFail($requests->customer_id);
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
        $this->fcm_send($userModel->getPushTokens(), $customer->name."  إشعار من عميلك   ", "طلب العميل إعادة فتح الطلب من جديد");
        session()->flash('message', 'تم إرسال إعادة فتح الطلب');
        return redirect(route('customer.account'));
    }
}
