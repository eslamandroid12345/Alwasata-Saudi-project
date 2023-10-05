<?php

namespace App\Http\Controllers;

use App\Model\Complain;
use App\Model\ComplainChat;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class SuggestionsController extends Controller
{

    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer' => ['layouts.content', 'layouts.Customermaster'],
            // 'App\Composers\ActivityComposer'  => ['layouts.customer_app'],
        ]);
    }

    public function index()
    {
        $complains = Complain::where('customer_id', auth()->id())->get();
        return view('Customer.complianPage', compact('complains'));
    }

    public function store(Request $request)
    {
        $rules = [
            'type'        => 'required',
            'title'       => 'required',
            'description' => 'required',
        ];

        $customMessages = [
            'type.required'        => 'لابد من اختيار إحدى الخيارات المتاحة',
            'title.required'       => MyHelpers::guest_trans('The filed is required'),
            'description.required' => MyHelpers::guest_trans('The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        if ($request->type == 'complain') {

            $countOfComplain = Complain::
            where('customer_id', auth()->user()->id)
                ->whereIn('status', [0, 1, 2])
                ->where('type', $request->type)
                ->get()
                ->count();

            if ($countOfComplain != 0) {
                return redirect()->back()->with('errorSugg', 'لديك شكوى سابقة تحت المعالجة');

            }

        }

        $complain = Complain::create([
            'type'        => $request->type,
            'title'       => $request->title,
            'description' => $request->description,
            'user_id'     => auth()->user()->user_id,
            'customer_id' => auth()->user()->id,
        ]);

        ComplainChat::create([
            'user_id'     => auth()->user()->user_id,
            'customer_id' => auth()->user()->id,
            'complain_id' => $complain->id,
            'type'        => 'send',
            'message'     => $request->description,
        ]);

        /*
        $complain = Complain::create([
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'user_id' => auth()->user()->user_id,
            'customer_id' => auth()->user()->id,
        ]);
        */

        /*
        $complainId = DB::table('complains')->insertGetId(
            array( //add it once use insertGetId
                'type' => $request->type,
                'title' => $request->title,
                'description' => $request->description,
                'user_id' => auth()->user()->user_id,
                'customer_id' => auth()->user()->id,
            )
        );
        */

        /*
        ComplainChat::create([
            'user_id'     => auth()->user()->user_id,
            'customer_id'     => auth()->user()->id,
            'complain_id'     => $complainId,
            'type'              => 'send',
            'message'     => $request->description,
        ]);
        */

        /*
        $complainChat = DB::table('complain_chats')->insertGetId(
            array( //add it once use insertGetId
                'user_id'     => auth()->user()->user_id,
                'customer_id'     => auth()->user()->id,
                'complain_id'     => $complainId,
                'type'              => 'send',
                'message'     => $request->description,
            )
        );
        */

        if ($request->type == 'suggestion') {
            return redirect()->back()->with('status', 'تم إضافة الإقتراح , شكرا لك');
        }
        else {
            return redirect()->back()->with('status', 'تم إضافة الشكوى يمكنك الدخول فى محادثة الشكوى ومتابعتها');
        }

    }

    public function chat($id)
    {
        $complain = Complain::find($id);
        $messages = ComplainChat::where('complain_id', $id)->get();

        return view('Customer.complain-chat', compact('complain', 'messages'));
    }

    public function chatStore(Request $request)
    {
        ComplainChat::create([
            'user_id'     => $request->user_id,
            'customer_id' => $request->customer_id,
            'complain_id' => $request->complain_id,
            'type'        => $request->type,
            'message'     => $request->message,
        ]);

        return redirect()->back();
    }
}
