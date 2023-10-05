<?php

namespace App\Http\Controllers\V2\Request;

use App\Helpers\MyHelpers;
use App\Models\Customer;
use App\Models\ClassificationAlertSchedule;
use App\Models\User;
use App\Traits\General;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Request as Model;
use Illuminate\Support\Facades\DB;
use function React\Promise\reduce;

class RequestClassificationController extends Controller
{
    use General;
    public function index($requestId)
    {
        $customerRequest = Model::findOrFail($requestId);
        if(!$customerRequest || !in_array($customerRequest->class_id_agent, [62, 33, 72]))
        {
            return view('V2.RequestClassification.link_expired');
        }
        return view('V2.RequestClassification.postponed_communication',compact('requestId'));
    }
    public function store(Request $request)
    {
        $customerRequest = Model::findOrFail($request->request_id);
        $user = User::findOrFail($customerRequest->user_id);
        $customer = Customer::findOrFail($customerRequest->customer_id);
        // send notification to agent
        DB::table('notifications')->insert([
            'value'         => MyHelpers::admin_trans(auth()->id(), 'The request need following') . " (قام العميل تحديد موعد)",
            'recived_id'    => $customerRequest->user_id,
            // 'status'        => 2,
            // 'type'          => 1,
            'status'        => 0,
            'type'          => 0,
            'reminder_date' => null,
            'req_id'        => $request->request_id,
            'created_at'    => (Carbon::now('Asia/Riyadh')),
        ]);
        // set reminder for agent
        DB::table('notifications')->insert([
            'value'         => MyHelpers::admin_trans(auth()->id(), 'The request need following') . " (قام العميل تحديد موعد)",
            'recived_id'    => $customerRequest->user_id,
            'receiver_type'        => 'web',
            'request_type'          => 1,
            'status'        => 0,
            'type'          => 0,
            'reminder_date' => Carbon::parse($request->new_date . ' ' . $request->new_time),
            'req_id'        => $request->request_id,
            'created_at'    => (Carbon::now('Asia/Riyadh')),
        ]);
        // add new row in request history
        DB::table('request_histories')->insert([
            'title'        => "قام العميل بتحديد موعد للتواصل بتاريخ ".$request->new_date . ' ' . $request->new_time,
            'user_id'      => null,
            'recive_id'    => $customerRequest->user_id,
            'class_id_agent'    => $customerRequest->class_id_agent,
            'history_date' => (Carbon::now('Asia/Riyadh')),
            'req_id'       => $request->request_id,
            'content'      => null,
            'class_id_agent'      => $customerRequest->class_id_agent,
        ]);
        if ($customerRequest->is_freeze == '1') {
            $customerRequest->update([
                'customer_want_to_contact_date'     => $request->new_date . ' ' . $request->new_time,
                'postponed_communication_status'    => 1,
                'class_id_agent'                    => null,
                'statusReq'                         => 0,
                'is_freeze'                         => 0,
                'add_to_archive'          => null,
                'remove_from_archive'     => null,
                'user_id'                           => MyHelpers::getNextAgentForRequest()
             ]);

        } else {
            $customerRequest->update([
                'customer_want_to_contact_date'    => $request->new_date . ' ' . $request->new_time,
                'postponed_communication_status'   => 1,
                'class_id_agent'                   => 1
             ]);

        }
        ClassificationAlertSchedule::where('request_id', $request->request_id)->delete();
        $this->fcm_send($user->getPushTokens(), 'يحتاج متابعة'. $customer->name.'العميل : ',$customer->mobile . 'يرجي التواصل معه على رقم جوال : ' . $request->new_time .'الوقت : ' . $request->new_date . 'العميل : يحتاج متابعة, حدد موعد للتواصل بتاريخ :  ');
        return redirect()->route('thank-you');
    }
    public function postponedStatus($requestId)
    {
        $customerRequest = Model::findOrFail($requestId);
        DB::table('notifications')->insert([
            'value'         => "العميل لم يطلب تاجيل التواصل",
            'recived_id'    => $customerRequest->user_id,
            'status'        => 0,
            'type'          => 0,
            'reminder_date' => null,
            'req_id'        => $requestId,
            'created_at'    => (Carbon::now('Asia/Riyadh')),
        ]);
        // add new row in request history
        DB::table('request_histories')->insert([
            'title'         => "العميل لم يطلب تاجيل التواصل",
            'user_id'      => null,
            'recive_id'    => $customerRequest->user_id,
            'history_date' => (Carbon::now('Asia/Riyadh')),
            'req_id'       => $requestId,
            'content'      => null,
            'class_id_agent'      => $customerRequest->class_id_agent,
        ]);
        $customerRequest->update([
            'postponed_communication_status'   => 0,
            'class_id_agent'                   => 1
        ]);
        $admins_and_gm = MyHelpers::getAllActiveGMAndAdmins();
        foreach ($admins_and_gm as $item) {
            DB::table('notifications')->insert([
                'value'         => "العميل لم يطلب تاجيل التواصل",
                'recived_id'    => $item->id,
                'status'        => 0,
                'type'          => 0,
                'reminder_date' => null,
                'req_id'        => $requestId,
                'created_at'    => (Carbon::now('Asia/Riyadh')),
            ]);
        }
        ClassificationAlertSchedule::where('request_id', $requestId)->delete();
        return redirect()->route('thank-you')->with('message', 'نعتذر عن الخطأ وسيتم التواصل معك في أقرب وقت ممكن');
    }
    public function thankPage()
    {
        return view('V2.RequestClassification.thank_you');
    }
}
