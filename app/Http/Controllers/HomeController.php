<?php

namespace App\Http\Controllers;

use App\Announcement;
use App\customer;
use App\notification;
use App\quality_req;
use App\request as Req;
use App\User;
use Calendar;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MyHelpers;
use View;

//to take date

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return Renderable
     */

    public function index()
    {

        $fn = User::find(auth()->user()->id);

        return view('home', compact('fn'));
    }

    function update_customers_date($start_id, $end_id)
    {

        $customers = DB::table('customers')->where('id', '>=', $start_id)->where('id', '<', $end_id)->get();
        foreach ($customers as $customer) {
            $txt = str_random(8);
            DB::table('customers')->where('id', '=', $customer->id)->update([
                'username'  => 'customer_'.rand(10000000, 99999999),
                'password'  => Hash::make($txt),
                'pass_text' => $txt,
            ]);
        }
        return 'Data Updated';
    }

    /*  For Reminder & Calendar */
    public function calendar($type = null, $id = null)
    {

        $auth = auth()->user();
        $role = $auth->role;

        $events = [];
        if ($role == '7') {
            $reminders = notification::where('request_type', '=', 1)
                ->whereNotNull('reminder_date')
                ->whereDate('reminder_date', '>', Carbon::now()->subDays(30))
                ->get();
        }
        elseif ($role == '11') {

            $agents = $this->getTrainingAgents($auth->id);

            if ($agents == -1) {
                $reminders = notification::where('request_type', '=', 1)
                    ->whereNotNull('reminder_date')
                    ->whereDate('reminder_date', '>', Carbon::now()->subDays(30))
                    ->get();
            }
            else {
                $reminders = notification::where('request_type', '=', 1)
                    ->whereNotNull('reminder_date')
                    ->whereIn('recived_id', $agents)
                    ->whereDate('reminder_date', '>', Carbon::now()->subDays(30))
                    ->get();
            }
        }
        else {
            $reminders = $auth->reminders;
        }

        if ($type == 'byRequest' && $id) {
            $reminders = $reminders->where('req_id', $id);
        }

        if ($type == 'byUser' && $id) {
            $reminders = $reminders->where('recived_id', $id);
        }

        if ($role == 6){
            return view('Calendar.calender', compact('reminders'));
        }

        foreach ($reminders as $key => $value) {
            $url = route('getReminder', $value->id);
            $title = (auth()->user()->role == '7' || auth()->user()->role == '11') ? '#'.$value->req_id.'/'.User::username($value->recived_id) : 'Req ID : #'.$value->req_id;
            if (auth()->user()->role != '11')
                switch ($value->status) {
                    case '0':
                        $url = '#';
                        $color = '#ff0000';//red
                        break;
                    case '1':
                        $url = '#';
                        $color = '#47d147';//green
                        break;
                    case '2':
                        $url = ($value->reminder_date >= date('Y-m-d')) ? $url : '#';
                        $color = '#3399ff';//blue
                        break;
                    default:
                        $url = ($value->reminder_date >= date('Y-m-d')) ? $url : '#';
                        $color = '#e0e0d1';//gray
                }
            else
                switch ($value->status) {
                    case '0':
                        $url = '#';
                        $color = '#ff0000';
                        break;
                    case '1':
                        $url = '#';
                        $color = '#47d147';
                        break;
                    case '2':
                        $url = '#';
                        $color = '#3399ff';
                        break;
                    default:
                        $url = '#';
                        $color = '#e0e0d1';
                }

            $request = $value->request;
            $customer = customer::getCustomerByID(@$request->customer_id);
            $events[] = Calendar::event(
                $title,
                true,
                $value->reminder_date, //new \DateTime($value->date . ' ' . $value->start_time),
                $value->reminder_date, // new \DateTime($value->date . ' ' . $value->finish_time),
                null,
                [
                    'url'         => $url,
                    'color'       => $color,
                    'textColor'   => '#fff',
                    'description' => nl2br($customer['name']." ".$customer['mobile']." ( ".$value->value." ) "), //  nl2br :: I tried to print new line but function does not work
                ]
            );
        }

        $calendar = Calendar::addEvents($events)
            ->setOptions(['firstDay' => 0, 'lang' => 'ar'])
            ->setCallbacks([
                'eventRender' => 'function (event,jqEvent,view) {
                                     jqEvent.tooltip({placement: "top", title: event.description});
                                 }',
            ]);

        return view('Calendar.reminders', compact('calendar'));
    }

    public function getTrainingAgents($trainID)
    {

        $agents = DB::table('training_and_agent')->where('training_id', $trainID);

        if ($agents->count() == 0) {
            return -1;
        }

        $agent_array = $agents->pluck('agent_id')->toArray();

        return $agent_array;
    }

    public function deleteUndefindCustomerRequests()
    {
        // In Request Table
        Req::whereNull('customer_id')->delete();   /* step 1 delete all request where customer_id NULL */
        /* Step 2 :: get all customers IDs and then delete all requets that customer_id whereNotIn  customers IDs array*/
        $customers = customer::pluck('id')->toArray();
        Req::whereNotIn('customer_id', $customers)->delete();

        // In Notification Table
        $reqs = Req::pluck('id')->toArray();
        return notification::where('request_type', 1)->whereNotIn('req_id', $reqs)->delete();
    }

    public function jsonRequests()
    {
        return response()->json($this->myRequests());
    }

    public function myRequests()
    {
        $role = auth()->user()->role;
        switch ($role) {
            case '0': // sale agent
                $requests = Req::where('user_id', auth()->id())->get();
                break;
            case '1': // sale manager
                $myUsers = User::where('manager_id', auth()->user()->id)->pluck('id')->toArray(); // My salesAgents
                $requests = Req::whereIn('user_id', $myUsers)->where('isSentSalesManager', 1)->get();
                break;
            case '2': // funding manager
                $myUsers = MyHelpers::extractUsersFunding(auth()->id());
                $myUsers = $myUsers[0]->pluck('id')->toArray();
                $requests = Req::whereIn('user_id', $myUsers)->where('isSentFundingManager', 1)->get();
                break;
            case '3': // mortgage manager
                $myUsers = MyHelpers::extractUsersMortgage(auth()->id());
                $myUsers = $myUsers[0]->pluck('id')->toArray();
                $requests = Req::whereIn('user_id', $myUsers)->where('isSentMortgageManager', 1)
                    ->whereIn('type', ['رهن', 'رهن-شراء', 'شراء-دفعة', 'تساهيل'])
                    ->get();
                break;
            case '4': // general manager
                $requests = Req::whereIn('statusReq', [12, 32])->where('isSentGeneralManager', 1)->get();
                break;
            case '5': // quality manager
                $quality_reqs = quality_req::where('user_id', auth()->id())->pluck('req_id')->toArray();
                $requests = Req::whereIn('id', $quality_reqs)->get();
                break;
            case '6': // collaborator
                $requests = Req::where('collaborator_id', auth()->id())->get();
                break;
            default:
                $requests = Req::all();
                break;
        }
        //        dd($requests[0]);
        return $requests;
    }

    public function jsonUsers()
    {
        return response()->json(User::where('status', 1)->get());
    }

    public function checkCustomerMobile($mobile)
    {
        $requests = $this->myRequests();
        $customers_id = $requests->pluck('customer_id')->toArray();
        $customer = customer::whereIn('id', $customers_id)->where('mobile', $mobile)->first();
        if (!$customer) {
            if (request()->ajax()) {
                return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'The mobile number is not registered for any one of my customers'), 'status' => 'error']);
            }
            return -1;
        }
        else {
            $request = Req::where('customer_id', $customer->id)->first();
            if (request()->ajax()) {
                return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'customer mobile found'), 'status' => 'success', 'req_id' => $request->id]);
            }
            return $customer;
        }
    }

    public function getReminder($id)
    {
        // return response()->json(notification::findOrFail($id));
        if (auth()->user()->role == 0) {
            return redirect()->route("agent.fundingRequest",$id);
        }
        if (auth()->user()->role == 1) {
            return redirect()->route("sales.manager.fundingRequest",$id);
        }
        if (auth()->user()->role == 2) {
            return redirect()->route("funding.manager.fundingRequest",$id);
        }
        if (auth()->user()->role == 3) {
            return redirect()->route("mortgage.manager.fundingRequest",$id);
        }
        if (auth()->user()->role == 4) {
            return redirect()->route("general.manager.fundingRequest",$id);
        }
        if (auth()->user()->role == 5) {
            return redirect()->route("'quality.manager.fundingRequest",$id);
        }
        if (auth()->user()->role == 7) {
            return redirect()->route("admin.fundingRequest",$id);
        }
        if (auth()->user()->role == 6) {
            return redirect()->route("proper.request.show",$id);
        }
        if (auth()->user()->role == 8) {
            return redirect()->route("accountant.fundingRequest",$id);
        }
        if (auth()->user()->role == 13) {
            return response()->json(notification::findOrFail($id));
            // return redirect()->route("V2.ExternalCustomer.request.show",$id);
        }

        return redirect()->back();
    }


    public function createReminder(Request $request)
    {
        $rules = [
            'date'   => 'required',
            'notify' => 'required',
        ];
        $customMessages = [
            'date.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'notify.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];
        $this->validate($request, $rules, $customMessages);

        $req_id = $this->requestByMobile($request->mobile);
        $reminder = MyHelpers::sendNotify(auth()->id(), 'web', $request->notify, $req_id, 1, null, null, $request->date);
        if ($reminder) {
            return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'), 'type' => 'success']);
        }
        return response()->json(['msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'type' => 'danger']);
    }

    public function requestByMobile($mobile)
    {
        $customer = customer::where('mobile', $mobile)->first();
        $request = Req::where('customer_id', $customer->id)->first();
        return $request->id;
    }

    public function updateReminder(Request $request)
    {
        $rules = [
            'date'   => 'required',
            'notify' => 'required',
        ];
        $customMessages = [
            'date.required'   => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
            'notify.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];
        $this->validate($request, $rules, $customMessages);
        $reminder_object = notification::where('id', $request->id)->first();
        $reminder = $reminder_object->update([
            'value'         => $request->notify,
            'reminder_date' => $request->date,
        ]);
        if ($reminder) {
            return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Edited successfully'), 'type' => 'success']);
        }
        return response()->json(['msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'type' => 'danger']);
    }

    public function deleteReminder($id)
    {
        $reminder = notification::where('id', $id)->delete();
        if ($reminder) {
            return response()->json(['msg' => MyHelpers::admin_trans(auth()->user()->id, 'Deleted successfully'), 'type' => 'warning']);
        }
        return response()->json(['msg', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'type' => 'danger']);
    }

    public function getReqType($id)
    {
        $reqInfo = Req::where('id', $id)->first();

        if (!empty($reqInfo)) {
            return response()->json(['type' => $reqInfo->type]);
        }
        return response()->json(['type' => null]);
    }

    public function openAnnounceFile($id)
    {

        $request = Announcement::findOrFail($id);
        try {
            $filename = $request->attachment;
            return response()->file(storage_path('app/public/'.$filename));
        }catch (\Exception $e){
            return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');
        }
    }


    public function seenAnnounce(Request $request)
    {

        $announceSeen = DB::table('announce_seen')
            ->insert([
                'announce_id' => $request->id,
                'user_id'     => auth()->user()->id,
                'created_at'  => Carbon::now('Asia/Riyadh'),
            ]);

        if ($announceSeen) {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'), 'status' => 1]);
        }
        else {
            return response()->json(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
        }
    }

}
