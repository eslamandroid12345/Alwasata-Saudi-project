<?php

namespace App\Http\Controllers;

use App\ChatFiles;
use App\customer;
use App\customerActivity;
use App\Helpers\MyHelpers;
use App\Message;
use App\User;
use Carbon\Carbon;
use Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use View;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('chatComposerView');
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'             => ['layouts.Customermaster', 'layouts.content', 'Customer.chatPage', 'ClientsChatting.chat'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content', 'Chatting.chat', 'Chatting.new-chat', 'layouts.Customermaster', 'ClientsChatting.chat'],
            'App\Composers\ActivityComposer'         => ['layouts.content', 'Chatting.chat', 'Chatting.new-chat', 'ClientsChatting.chat'],
        ]);
    }

    // == this method will git count all unread message and get lateset unread message from agent related to other clients

    public static function getAllMessagesWhereCustomer($customer_id)
    {
        $user_id = Auth::user()->id;
        $chatFireStore = new FireStore('messages');
        $getMessages = $chatFireStore->getAllMessagesFromClients($customer_id, $user_id);
        return $getMessages[0];
    }
    // === end method ====
    // ------------------------------ //

    public function indexWithClients()
    {
        $user_id = Auth::user()->id;
        $getRequests = DB::table('requests')->where('user_id', $user_id)->first();
        $chatFireStore = new FireStore('messages');
        $getMessages = $chatFireStore->getLastMessageFromClient($user_id);
        $count_message = $chatFireStore->countAllUnreadMessageFromClient($user_id);
        //        dd($count_message);
        if ($getMessages == null) {
            $messages = 0;
            return view('ClientsChatting.chat', compact('count_message', 'messages',));
        }
        else {
            foreach ($getMessages as $key => $value) {
                $messages = $value;
            }
            return view('ClientsChatting.chat', compact('count_message', 'messages'));
        }
    }

    public function getAllCustomersRelatedToSalesAgent()
    {
        $correspondents = DB::table('requests')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('requests.user_id', Auth::user()->id)
            ->select('customers.id', 'customers.name')
            ->get();
        return view('ClientsChatting.chat-sub', compact('correspondents'));
    }

    public function ajaxReceiversFirebase()
    {
        $users = DB::table('requests')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('requests.user_id', Auth::user()->id)
            ->where('customers.message_status', '=', 0)
            ->select('customers.id', 'customers.name')
            ->get();
        if (\request()->ajax()) {
            return response()->json($users);
        }
    }

    public function ajax_receivers()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $users = [];

        if (\auth()->user()) {
            switch (auth()->user()->role) {
                case 0 :
                    $users = User::where('status', true)
                        ->where('id', auth()->user()->manager_id)
                        ->orWhere('role', 4)
                        ->where('id', "<>", auth()->id())->get();
                    break;
                case 1 : // Sales manager
                    $users = User::where('status', true)
                        ->where('manager_id', auth()->id())
                        ->orWhere('id', auth()->user()->funding_mnager_id)
                        ->orWhere('id', auth()->user()->mortgage_mnager_id)
                        ->orWhere('role', 4)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 2 : // funding manager
                    $users = User::where('status', true)
                        ->where('funding_mnager_id', auth()->id())
                        ->orWhere('role', 4)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 3 : // mortgage manager
                    $users = User::where('status', true)
                        ->where('mortgage_mnager_id', auth()->id())
                        ->orWhere('role', 4)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 4 : // genral manager
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 5 : // quality
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 6 :  // collaborator
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();

                case 7 : // admin
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 8 : // accountant
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    break;
                case 9 : // property agent
                    $users = User::whereHas('to_messages', function ($query) {
                        $query->where('from', \auth()->id())->where('from_type', 'App\User');
                    })
                        ->orWhereHas('from_messages', function ($query) {
                            $query->where('to', \auth()->id())->where('to_type', 'App\User');
                        })
                        ->where('status', true)
                        ->where('role', 9)
                        ->latest()
                        ->get();
                    break;
                default:
                    //

            }
        }
        if (\auth('customer')->user()) { // customer

            $users = User::whereHas('to_messages', function ($query) {
                $query->where('from', \auth()->id())->where('from_type', 'App\customer');
            })
                ->orWhereHas('from_messages', function ($query) {
                    $query->where('to', \auth()->id())->where('to_type', 'App\customer');
                })
                ->latest()
                ->get();
        }

        if (\request()->ajax()) {
            return response()->json($users);
        }
    }

    public function ajax()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $users = $customers = $correspondents = [];

        if (\auth()->user()) {
            switch (auth()->user()->role) {
                case 0 :
                    $users = User::where('status', true)
                        ->where('id', auth()->user()->manager_id)
                        ->orWhere('role', 4)
                        ->where('id', "<>", auth()->id())->get();

                    $ids = [];
                    $from_ids = Message::where(function ($query) {
                        $query->where('to', \auth()->id())
                            ->whereIn('from_type', ['App\customer']);
                    })->pluck('from')->toArray();
                    $to_ids = Message::where(function ($query) {
                        $query->where('from', \auth()->id())
                            ->whereIn('to_type', ['App\customer']);
                    })->pluck('to')->toArray();
                    if (count($from_ids) > 0) {
                        foreach ($from_ids as $id) {
                            array_push($ids, $id);
                        }
                    }
                    if (count($to_ids) > 0) {
                        foreach ($to_ids as $id) {
                            array_push($ids, $id);
                        }
                    }
                    //dd(array_unique($ids));
                    $customers = customer::whereIn('id', $ids)->latest()->get();

                    /*
                     * $customers = customer::whereHas('to_messages', function ($query) {
                        $query->where('from', \auth()->id())->whereIn('from_type', ['App\User','App\customer']);
                    })
                        ->orWhereHas('from_messages', function ($query) {
                            $query->where('to', \auth()->id())->whereIn('from_type', ['App\User','App\customer']);
                        })
                        ->join('requests','requests.customer_id','customers.id')
                        ->where('requests.user_id',auth()->user()->id)
                        ->select('customers.*')
                        ->latest()->get();
                    */
                    break;

                case 1 : // Sales manager
                    $users = User::where('status', true)
                        ->where('manager_id', auth()->id())
                        ->orWhere('id', auth()->user()->funding_mnager_id)
                        ->orWhere('id', auth()->user()->mortgage_mnager_id)
                        ->orWhere('role', 4)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 2 : // funding manager
                    $users = User::where('status', true)
                        ->where('funding_mnager_id', auth()->id())
                        ->orWhere('role', 4)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 3 : // mortgage manager
                    $users = User::where('status', true)
                        ->where('mortgage_mnager_id', auth()->id())
                        ->orWhere('role', 4)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 4 : // genral manager
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 5 : // quality
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 6 :  // collaborator
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    $ids = [];
                    $from_ids = Message::where(function ($query) {
                        $query->where('to', \auth()->id())
                            ->whereIn('from_type', ['App\customer']);
                    })->pluck('from')->toArray();
                    $to_ids = Message::where(function ($query) {
                        $query->where('from', \auth()->id())
                            ->whereIn('to_type', ['App\customer']);
                    })->pluck('to')->toArray();
                    if (count($from_ids) > 0) {
                        foreach ($from_ids as $id) {
                            array_push($ids, $id);
                        }
                    }
                    if (count($to_ids) > 0) {
                        foreach ($to_ids as $id) {
                            array_push($ids, $id);
                        }
                    }
                    //dd(array_unique($ids));
                    $customers = customer::whereIn('id', $ids)->latest()->get();
                    //                    $customers = customer::whereHas('to_messages', function ($query) {
                    //                        $query->where('from', \auth()->id())->where('from_type', 'App\User');
                    //                    })
                    //                        ->orWhereHas('from_messages', function ($query) {
                    //                            $query->where('to', \auth()->id())->where('to_type', 'App\User');
                    //                        })->latest()->get();
                    break;

                case 7 : // admin
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    break;

                case 8 : // accountant
                    $users = User::where('status', true)
                        ->where('id', "<>", auth()->id())->get();
                    break;
                case 9 : // property agent
                    $users = User::whereHas('to_messages', function ($query) {
                        $query->where('from', \auth()->id())->where('from_type', 'App\User');
                    })
                        ->orWhereHas('from_messages', function ($query) {
                            $query->where('to', \auth()->id())->where('to_type', 'App\User');
                        })
                        ->where('status', true)
                        ->where('role', 9)
                        ->latest()
                        ->get();
                    break;
                default:
                    //

            }
        }
        if (\auth('customer')->user()) { // customer

            $users = User::whereHas('to_messages', function ($query) {
                $query->where('from', \auth()->id())->where('from_type', 'App\customer');
            })
                ->orWhereHas('from_messages', function ($query) {
                    $query->where('to', \auth()->id())->where('to_type', 'App\customer');
                })
                ->latest()
                ->get();
        }

        if ($users->count() > 0) {
            foreach ($users as $user) {
                array_push($correspondents, $user);
            }
        }
        if (count($customers) > 0) {
            foreach ($customers as $customer) {
                array_push($correspondents, $customer);
            }
        }

        return view('Chatting.chat-sub', compact('correspondents'));
    }

    public function ajaxClientchat()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        $user_id = Auth::user()->id;
        $chatFireStore = new FireStore('messages');
        $getMessages = $chatFireStore->getAllUsersWhereSaleAgent($user_id);
        foreach ($getMessages as $key => $value) {
            $users[] = $value['room_date']['users'];
        }
        return view('ClientsChatting.chat-sub', compact('users'));
    }

    public function index()
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');
        return view('Chatting.chat');
    }

    ////// this function will get all messages when open from customer interface ////
    public function newMessageCustomer(Request $request)
    {
        $redirect = $request->redirect;
        if ($redirect == 1) {
            Session::put('redirect', $redirect);
        }
        else {
            Session::put('redirect', $redirect);
        }
        $receivers = $request->receivers[0];
        $receiver_model_type = 'App\User';
        $model_type = 'App\customer';
        $chatFireStore = new FireStore('messages');
        $user = User::whereIn('id', $request->receivers)->first();
        $customerId = \auth()->user()->id;
        $user_id = DB::table('requests')->where('customer_id', $customerId)->first();
        $markIsRead = $chatFireStore->markAllMessageAsReadCustomer($customerId, $user_id->user_id);
        $getMessages = $chatFireStore->getMessagesWhereUserAndCustomer($customerId, $user_id->user_id);
        if ($getMessages == null) {
            $messages = [];
            return view('Customer.chatPage',
                compact('user', 'messages', 'receivers', 'model_type', 'receiver_model_type'));
        }
        else {
            foreach ($getMessages as $key => $value) {
                $messages[] = $value;
            }
            return view('Customer.chatPage',
                compact('user', 'messages', 'receivers', 'model_type', 'receiver_model_type'));
        }
    }
    ///////// end function /////////////////////

    /////// this function send new message from customer interface to related sales agent user and also from sales agent interface to specific customer///
    public function sendMessageFirebase(Request $request)
    {
        $receivers = $request->receivers;
        $message = $request->message;
        $customerId = Auth::user()->id;
        $userId = intval($receivers);
        if ($request->hasFile('message') && in_array($request->message_type, ['video', 'image', 'file'])) {
            $file = $request->file('message');
            $time = microtime('.') * 10000;
            $message = $time.'.'.strtolower($file->getClientOriginalExtension());
            $destination = 'storage/chat/';
            $file->move($destination, $message);
            $data = new ChatFiles();
            $data->file_name = $message;
            $data->message_id = '1';
            $data->user_id = $userId;
            $data->customer_id = $customerId;
            $data->save();
        }
        if (Auth::guard('customer')->user()) {
            $senderId = Auth::guard('customer')->id();
            $model_type = 'App\customer';
            $receiver_model_type = 'App\User';
            $firestore = new FireStore('messages');
            $document = (int) date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(1000, 999));
            $document2 = (int) date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(1000, 999));
            $save = $firestore->newDocument2($customerId, $userId, $document, $document2,
                [
                    'room_date' => [
                        'users' => [
                            '0' => $customerId,
                            '1' => $userId,
                        ],
                    ],
                ],
                [
                    'created_at'   => (Carbon::now('Asia/Riyadh')),
                    'senderId'     => Auth::user()->id,
                    'senderName'   => Auth::user()->name,
                    'receiverId'   => intval($receivers),
                    'text'         => ($request->message_type == "text") ? $message : "",
                    'file'         => (($request->message_type == "image") || ($request->message_type == "video") || ($request->message_type == "file")) ? $message : "",
                    'message_type' => $request->message_type,
                    'from_type'    => $model_type,
                    'to_type'      => $receiver_model_type,
                    'is_read'      => 0,
                ]
            );
            if ($save) {
                // from customer to agent
                $agentInfo = MyHelpers::getAgentInfo($userId);
                if ($agentInfo->status == 0) {
                    $requestInfo = DB::table('requests')->where('customer_id', '=', $customerId)->first();
                    $checkDublicateOfNeedActionReq = MyHelpers::checkDublicateOfNeedActionReq('رسالة جديدة من العميل', $userId, $requestInfo->id);
                    if ($checkDublicateOfNeedActionReq) {
                        $addNeedActionReq = MyHelpers::addNeedActionReq('رسالة جديدة من العميل', $userId, $requestInfo->id);
                    }
                }
                $emailNotify = MyHelpers::sendEmailNotifiaction('new_msg_customer', $userId, ' رسالة جديدة ', 'العميل قام بإرسال رسالة جديدة إليك');
                $updateCustomerMessageStatus = DB::table('customers')->where('id', $customerId)
                    ->update(['message_status' => 1]);
                $chatFireStore = new FireStore('messages');
                $getMessages = $chatFireStore->getMessagesWhereUserAndCustomer($customerId, $userId);
                foreach ($getMessages as $key => $value) {
                    $messages[] = $value;
                }
                $response = [
                    'status'    => 'success',
                    'msg'       => 'Data successful saved',
                    'data'      => $messages,
                    'receivers' => $receivers,
                    'redirect'  => '/chat-clients',
                ];
                return response()->json($response);
            }
        }
        else {
            $senderId = Auth::id();
            $model_type = 'App\User';
            $receiver_model_type = 'App\customer';
            $firestore = new FireStore('messages');
            $document = (int) date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(1000, 999));
            $document2 = (int) date('Ymd').substr(time(), -5).substr(microtime(), 2, 5).sprintf('%02d', rand(1000, 999));
            $save = $firestore->newDocument2($userId, $customerId, $document, $document2,
                [
                    'room_date' => [
                        'users' => [
                            '0' => $userId,
                            '1' => $customerId,
                        ],
                    ],
                ],
                [
                    'created_at'   => (Carbon::now('Asia/Riyadh')),
                    'senderId'     => Auth::user()->id,
                    'senderName'   => Auth::user()->name,
                    'receiverId'   => intval($receivers),
                    'text'         => ($request->message_type == "text") ? $message : "",
                    'file'         => (($request->message_type == "image") || ($request->message_type == "video") || ($request->message_type == "file")) ? $message : "",
                    'message_type' => $request->message_type,
                    'from_type'    => $model_type,
                    'to_type'      => $receiver_model_type,
                    'is_read'      => 0,
                ]
            );
            if ($save) {
                $emailNotify = MyHelpers::sendEmailNotifiactionCustomer($customerId, ' عزيزي العميل ، لديك رسالة جديدة فيما يخص طلب التمويل  ', ' رسالة جديدة بإنتظارك - شركة الوساطة العقارية');
                $chatFireStore = new FireStore('messages');
                $getMessages = $chatFireStore->getMessagesWhereUserAndCustomerSalesInterface($userId, $customerId);
                foreach ($getMessages as $key => $value) {
                    $messages[] = $value;
                }
                $response = [
                    'status'    => 'success',
                    'msg'       => 'Data successful saved',
                    'data'      => $messages,
                    'receivers' => $receivers,
                    'redirect'  => '/chat-clients',
                ];
                $updateCustomerMessageStatus = DB::table('customers')->where('id', intval($receivers))->update(['message_status' => 1]);
                return response()->json($response);
            }
        }
        if ($receiver_model_type == 'App\customer') { //from agent to customer
            $emailNotify = MyHelpers::sendEmailNotifiactionCustomer($userId, ' عزيزي العميل ، لديك رسالة جديدة فيما يخص طلب التمويل  ', ' رسالة جديدة بإنتظارك - شركة الوساطة العقارية');
        }

    }

    ////// end function //////////////////////////////////

    public function sendMessage(Request $request)
    {
        if (Auth::guard('customer')->user()) {
            $from = Auth::guard('customer')->id();
            $model_type = 'App\customer';
        }
        else {
            $from = Auth::id();
            $model_type = 'App\User';
        }
        $receiver_model_type = $request->receiver_model_type ?? 'App\User';
        $request->has('is_default_msg') ? $receivers = ($request->receivers)[0] : $receivers = $request->receivers; // returns true
        //        $receivers = $request->receivers;
        $message = $request->message;
        if ($request->hasFile('message') && in_array($request->message_type, ['video', 'image', 'file'])) {
            $message = $message->store('chat');
        }
        $data = new Message();
        $data->from = $from;
        $data->to = intval($receivers);
        $data->to_type = $receiver_model_type; //$request->receiver_model_type ;
        $data->message_type = $request->message_type;
        $data->from_type = $model_type;
        $data->message = $message;
        $data->is_read = 0; // message will be unread when sending message
        $data->save();
        if ($model_type == 'App\customer') { // from customer to agent
            #add to need to action requests
            $agentInfo = MyHelpers::getAgentInfo($receivers);
            if ($agentInfo->status == 0) {
                $requestInfo = DB::table('requests')->where('customer_id', '=', $from)->first();
                $checkDublicateOfNeedActionReq = MyHelpers::checkDublicateOfNeedActionReq('رسالة جديدة من العميل', $receivers, $requestInfo->id);
                if ($checkDublicateOfNeedActionReq) {
                    $addNeedActionReq = MyHelpers::addNeedActionReq('رسالة جديدة من العميل', $receivers, $requestInfo->id);
                }
            }
            $emailNotify = MyHelpers::sendEmailNotifiaction('new_msg_customer', $receivers, ' رسالة جديدة ', 'العميل قام بإرسال رسالة جديدة إليك');
        }

        if ($receiver_model_type == 'App\customer') { //from agent to customer
            $emailNotify = MyHelpers::sendEmailNotifiactionCustomer($receivers, ' عزيزي العميل ، لديك رسالة جديدة فيما يخص طلب التمويل  ', ' رسالة جديدة بإنتظارك - شركة الوساطة العقارية');
        }

        $response = [
            'status'    => 'success',
            'msg'       => 'Data successful saved',
            'data'      => $data,
            'receivers' => $receivers,
            'redirect'  => '/chat',
        ];
        // Return JSON Response
        return response()->json($response);
    }

    public function newMessage(Request $request)
    {
        $receivers = $request->receivers;
        $receiver_model_type = $request->receiver_model_type ?? 'App\User';
        if (Auth::guard('customer')->user()) {
            $my_id = Auth::guard('customer')->id();
            $model_type = 'App\customer';
        }
        else {
            $my_id = Auth::id();
            $model_type = 'App\User';
        }
        $count =0;
        if (count($receivers) == 1) {
            $user_id = $receivers[0];
            if ($receiver_model_type == 'App\User') {
                $users = User::whereIn('id', $request->receivers)->get();
            }
            else {
                $users = customer::whereIn('id', $request->receivers)->get();
            }
            Message::where(['from' => $user_id, 'to' => $my_id, 'to_type' => $model_type, 'from_type' => $receiver_model_type])->update(['is_read' => 1]);
            $messages = Message::where(function ($query) use ($user_id, $my_id, $model_type, $receiver_model_type) {
                $query->where('from', $user_id)->where('to', $my_id)->where('to_type', $model_type)->where('from_type', $receiver_model_type);
            })->orWhere(function ($query) use ($user_id, $my_id, $model_type, $receiver_model_type) {
                $query->where('from', $my_id)->where('to', $user_id)->where('from_type', $model_type)->where('to_type', $receiver_model_type);
            })->get();
            $count = count($messages);
        }
        else {
            $users = User::whereIn('id', $request->receivers)->get();
            $messages = [];
        }

        return view('Chatting.new-chat', compact('users','count', 'messages', 'receivers', 'model_type', 'receiver_model_type'));
    }

    public function getMessageUser($user, $receiver_model)
    {
        $receivers[] = $user;
        $receiver_model_type = $receiver_model ?? 'App\User';

        if (Auth::guard('customer')->user()) {
            $my_id = Auth::guard('customer')->id();
            $model_type = 'App\customer';
        }
        else {
            $my_id = Auth::id();
            $model_type = 'App\User';
        }

        //        if (\auth()->guard())
        if (count($receivers) == 1) {
            $user_id = $receivers[0];

            if ($receiver_model_type == 'App\User') {
                $users = User::whereIn('id', $receivers)->get();
            }
            else {
                $users = customer::whereIn('id', $receivers)->get();
            }

            // Make read all unread message
            Message::where(['from' => $user_id, 'to' => $my_id, 'to_type' => $model_type, 'from_type' => $receiver_model_type])->update(['is_read' => 1]);

            // Get all message from selected user
            $messages = Message::where(function ($query) use ($user_id, $my_id, $model_type, $receiver_model_type) {
                $query->where('from', $user_id)->where('to', $my_id)->where('to_type', $model_type)->where('from_type', $receiver_model_type);
            })->orWhere(function ($query) use ($user_id, $my_id, $model_type, $receiver_model_type) {
                $query->where('from', $my_id)->where('to', $user_id)->where('from_type', $model_type)->where('to_type', $receiver_model_type);
            })->get();
        }
        else {
            $users = User::whereIn('id', $receivers)->get();
            $messages = [];
        }

        return view('Chatting.new-chatBodyPage', compact('messages', 'model_type'));
    }

    ///// This function will get all messages depend on customer in case open chat from sales agent interface
    public function getAllMessagesWithSalesAgentAndCustomer(Request $request)
    {
        $receivers = $request->receivers;
        if (Auth::guard('customer')->user()) {
            $my_id = Auth::guard('customer')->id();
            $model_type = 'App\customer';
            $receiver_model_type = 'App\User';
        }
        else {
            $my_id = Auth::id();
            $model_type = 'App\User';
            $receiver_model_type = 'App\customer';
        }
        if (count($receivers) == 1) {
            $user_id = (int) $receivers[0]; // customer ID
            if ($receiver_model_type == 'App\User') {
                $users = User::whereIn('id', $request->receivers)->get();
            }
            else {
                $users = customer::whereIn('id', $request->receivers)->get();
            }
            // update is read = 1 for all related customer messages
            $firestore = new FireStore('messages');
            $markIsRead = $firestore->markAllMessageAsRead($user_id, $my_id);
            // Get all message from selected user
            $getAllMessages = $firestore->getAllMessageFromSelectedCustomer($user_id, $my_id);
            if ($getAllMessages == null) {
                $messages = [];
            }
            else {
                foreach ($getAllMessages as $key => $value) {
                    $messages[] = $value;
                }
            }

        }
        else {
            $users = User::whereIn('id', $request->receivers)->get();
            $messages = [];
        }
        $customersOnline = [];
        $onlineCustomers = customerActivity::select('last_activity', 'customer_id')->get();
        foreach ($onlineCustomers as $onlineCustomer) {
            if ((($onlineCustomer->last_activity + (Config::get('session.lifetime') * 60) - time()) / 60) > 0) {
                $customersOnline [] = $onlineCustomer->customer_id;
            }
        }
        $getAget = DB::table('users')->where('id', $my_id)->first();
        return view('ClientsChatting.new-chat', compact('users', 'customersOnline', 'getAget', 'messages', 'receivers', 'model_type', 'receiver_model_type'));
    }

    ///////// End Function ////////////

    public function getMessageCustomer($agent)
    {
        $receivers[] = $agent;
        $model_type = 'App\customer';
        $customerId = \auth()->user()->id;
        $user_id = DB::table('requests')->where('customer_id', $customerId)->first();
        $chatFireStore = new FireStore('messages');
        //        $markIsRead = $chatFireStore->markAllMessageAsRead($customerId,$user_id->user_id);
        $getMessages = $chatFireStore->getMessagesWhereUserAndCustomer($customerId, $user_id->user_id);
        if ($getMessages == null) {
            $messages = [];
            return view('Customer.chatBodyPage', compact('messages', 'model_type'));
        }
        else {
            foreach ($getMessages as $key => $value) {
                $messages[] = $value;
            }
            return view('Customer.chatBodyPage', compact('messages', 'model_type'));
        }
    }

    public function delete($id)
    {
        $user = Auth::id(); //7
        $message = Message::find($id); //210

        if ($message->from == $user) {
            $x = 1;
            $message->from_is_show = 0;
            $message->save();
        }
        else {
            $x = 2;
            $message->to_is_show = 0;
            $message->save();
        }

        $response = [
            'status' => 'success',
            'msg'    => 'Message successful deleted',
            'x'      => $x,
        ];
        // Return JSON Response
        return response()->json($response);
    }

    public function downloadFile($id)
    {
        $file = DB::table('messages')->where('id', '=', $id)
            ->first();

        if ($file) {
            $filename = $file->message;
            return response()->download(storage_path('app/public/'.$filename)); // download
        }
        else {
            return back();
        }
    }

    public function downloadFileFirebase($id)
    {
        $file = ChatFiles::where('file_name', '=', $id)
            ->first();
        if ($file) {
            $filename = $file->file_name;
            return response()->download(public_path('storage/chat/'.$filename));
        }
        else {
            return back();
        }
    }

    public function openFileFirebase($id)
    {
        $file = ChatFiles::where('file_name', '=', $id)
            ->first();
        if ($file) {
            $filename = $file->file_name;
            return response()->file(public_path('storage/chat/'.$filename));
        }
        else {
            return back();
        }
    }

    public function openFile($id)
    {
        $file = DB::table('messages')->where('id', '=', $id)
            ->first();

        if ($file) {
            $filename = $file->message;
            return response()->file(storage_path('app/public/'.$filename));
        }
        else {
            return back();
        }
    }

    public function addFile(Request $request)
    {

        $filename = $request->nameFile;
        $msgInfo = DB::table('messages')->where('id', $request->messageID)
            ->first();
        $reqInfo = DB::table('requests')->where('customer_id', $request->customerID)
            ->first();
        if (empty($reqInfo)) {
            return response()->json(['status' => 2]);
        }

        $upload_date = Carbon::today('Asia/Riyadh')->format('Y-m-d');

        $docID = DB::table('documents')->insertGetId(
            [
                'filename'    => $filename,
                'location'    => $msgInfo->message,
                'upload_date' => $upload_date,
                'req_id'      => $reqInfo->id,
                'user_id'     => auth()->user()->id,
            ]
        );

        if ($docID) {
            return response()->json(['status' => $docID]);
        }
        else {
            return response()->json(['status' => 0]);
        }
    }

    public function addFileFirebase(Request $request)
    {
        $filename = $request->nameFile;
        $msgInfo = $file = ChatFiles::where('file_name', '=', $request->messageID)
            ->first();
        $reqInfo = DB::table('requests')->where('customer_id', $request->customerID)
            ->first();
        if (empty($reqInfo)) {
            return response()->json(['status' => 2]);
        }

        $upload_date = Carbon::today('Asia/Riyadh')->format('Y-m-d');

        $docID = DB::table('documents')->insertGetId(
            [
                'filename'    => $filename,
                'location'    => 'storage/chat/'.$msgInfo->file_name,
                'upload_date' => $upload_date,
                'req_id'      => $reqInfo->id,
                'user_id'     => auth()->user()->id,
            ]
        );

        if ($docID) {
            return response()->json(['status' => $docID]);
        }
        else {
            return response()->json(['status' => 0]);
        }
    }
}
