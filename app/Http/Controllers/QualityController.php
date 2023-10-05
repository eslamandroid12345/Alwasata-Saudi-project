<?php

namespace App\Http\Controllers;

use App\classifcation;
use App\Models\RequestHistory;
use App\quality_req;
use App\servay;
use App\task;
use App\task_content;
use App\User;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;


use App\Models\RequestCondition;
use App\Models\Request as request_model;
use App\Models\RequestRecord;
use App\Models\QualityRequest;

use App\Models\QualityRequestNeedTurned;


//to take date

class QualityController extends Controller
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

    public function myReqs()
    {
        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();
        $collaborators = DB::table('users')->where('role', 6)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        $check = 0; // check if this user is belong for at lest one user
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $users = User::where("role",5)->where("subdomain","<>",null)->get();
        return view('QualityManager.Request.myReqs', compact('check','users', 'classifcations_sa', 'classifcations_qu', 'all_status', 'collaborators', 'task_status', 'worke_sources', 'request_sources'));

        $qualityID = auth()->id();
        $requests = DB::table('quality_reqs')
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->where('quality_reqs.user_id', $qualityID)
            ->where('quality_reqs.allow_recive', 1)
            ->whereNotIn('quality_reqs.status', [3, 4])
            ->select('quality_reqs.id', 'quality_reqs.user_id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')
            ->get();
        if ($requests->count() > 0) {
            foreach ($requests as $request) {
                $check = MyHelpers::checkConditionMatch($request->id);
                if ($check == false) {
                    $update_req = DB::table('quality_reqs')->where('id', $request->id)->where('con_id', '!=', null)->update(['status' => 3]);
                    if ($update_req == 1) {
                        DB::table('tasks')->where('req_id', $request->id)->where('user_id', $request->user_id)->update([
                            'status' => 3 //completed
                        ]);
                    }
                }
            }
        }
        $requests = DB::table('quality_reqs')
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->where('quality_reqs.user_id', $qualityID)
            ->where('quality_reqs.allow_recive', 1)
            ->select('quality_reqs.id', 'quality_reqs.user_id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')
            ->get();

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();
        $collaborators = DB::table('users')->where('role', 6)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        $check = 0; // check if this user is belong for at lest one user
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();


        return view('QualityManager.Request.myReqs', compact('requests','users','val', 'check', 'classifcations_sa', 'classifcations_qu', 'all_status', 'collaborators', 'task_status', 'worke_sources', 'request_sources'));
    }

    public function statusQuality($getBy = 'empty')
    {
        $s = [
            0 => MyHelpers::admin_trans(auth()->id(), 'new req'),
            1 => MyHelpers::admin_trans(auth()->id(), 'open req'),
            2 => MyHelpers::admin_trans(auth()->id(), 'Under Processing'),
            3 => MyHelpers::admin_trans(auth()->id(), 'Completed'),
            4 => MyHelpers::admin_trans(auth()->id(), 'not completed'),
            5 => MyHelpers::admin_trans(auth()->id(), 'Archive in quality'),
        ];

        return $getBy == 'empty' ? $s : ($s[$getBy] ?? $s[4]);
    }

    public function statusTask($getBy = 'empty')
    {
        $s = [
            0 => MyHelpers::admin_trans(auth()->id(), 'new task'),
            1 => MyHelpers::admin_trans(auth()->id(), 'open task'),
            2 => MyHelpers::admin_trans(auth()->id(), 'Under Processing'),
            3 => MyHelpers::admin_trans(auth()->id(), 'Completed'),
            4 => MyHelpers::admin_trans(auth()->id(), 'not completed'),
            5 => MyHelpers::admin_trans(auth()->id(), 'Canceled'),
        ];

        return $getBy == 'empty' ? $s : ($s[$getBy] ?? $s[5]);
    }

    /**
     * @throws \Exception
     */
    public function myReqs_datatable(Request $request)
    {
        $users = User::where("role",5)->where("subdomain","<>",null)->pluck("id")->toArray();
        $requests =quality_req::with("user_data")->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('quality_reqs.allow_recive', 1)
            ->select('quality_reqs.id',"others.name_for_admin as  quality", 'requests.id as reqID', 'users.name as agentName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent', 'requests.class_id_quality',
                'quality_reqs.status',
                'requests.type', 'requests.collaborator_id', 'requests.source', 'quality_reqs.created_at', 'requests.created_at as req_created_at')

            ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                $q->whereIn('quality_reqs.user_id', $users);
            })->when(auth()->user()->role != 9 ,function($q,$v) {
                $q->where('quality_reqs.user_id', auth()->id());
            });

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '>=', $request->get('req_date_from'));
        }
        if ($request->get('users')&& is_array($request->get('users'))) {
            $requests = $requests->whereIn('quality_reqs.user_id',  $request->get('users'));
        }
        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '<=', $request->get('req_date_to'));
        }

        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        /*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            if (auth()->user()->role != 9){
                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'tasks').'">
            <i class="fa fa-comments"></i></span>';
            $data = $data.'<span class="item pointer" id="need_turned_req" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'add to need to be turned request basket').'">
            <a href="'.route('quality.manager.add_needToBeTurnedReq', $row->id).'"><i class="fa fa-retweet"></i></a></span>';

            }

            $data = $data.'<span class="item pointer" id="questions" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'quality questions').'">
           <i class="fa fa-question"></i></span>';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
            <a href="'.route('quality.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';

            if (auth()->user()->role != 9) {
                if ($row->status == 3 || $row->status == 4) {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Restore Request').'">
                 <a href="'.route('quality.manager.restoreReq', $row->id).'"><i class="fa fa-redo"></i></a></span>';
                }
            }

            /*
            $data = $data . '<span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="' . MyHelpers::admin_trans(auth()->id(), 'Add to need action req') . '">
                 <a onclick="transalteData(' . $row->id . ')"><i class="fa fa-random"></i></a></span>';
                 */

            return $data.'</div>';
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return @$this->statusQuality()[$row->status] ?? $this->statusQuality()[4];
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[30];
        })->editColumn('class_id_agent', function ($row) {

            $classValue = classifcation::find($row->class_id_agent);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_agent;
        })->editColumn('class_id_quality', function ($row) {

            $check = true;
            $lastClassUser = DB::table('req_records')->where('colum', 'class_quality')->where('req_records.req_id', $row->reqID)->latest('req_records.updateValue_at')->first();

            if ($lastClassUser) {
                if ($lastClassUser->user_id != auth()->id()) {
                    $check = false;
                }
            }

            $classifcations_qu = classifcation::where('user_role', 5)->get();

            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {
                $val = auth()->user()->role == 9 ? "disabled" : " ";
                $data = '<select '.$val.' id="reqClass'.$row->reqID.'" style="width:150px;"  name="reqClass" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); saveclass(this,'.$row->reqID.')" >';

                $data = $data.'<option value="" selected>---</option>';

                foreach ($classifcations_qu as $classifcations) {

                    if (($classifcations->id == $row->class_id_quality) && $check) {
                        $data = $data.'<option value="'.$classifcations->id.'" selected>'.$classifcations->value.'</option>';
                    }
                    else {
                        $data = $data.'<option value="'.$classifcations->id.'">'.$classifcations->value.'</option>';
                    }
                }

                $data = $data.'</select>';

                return $data;
            }
            else {
                $classifcations_qu = classifcation::where('id', $row->class_id_quality)->first();

                if ($classifcations_qu != null) {
                    return $classifcations_qu->value;
                }
                else {
                    return $row->class_id_quality;
                }
            }
        })->editColumn('quacomment', function ($row) {

            $check = true;
            $lastCommentUser = DB::table('req_records')->join('users', 'users.id', 'req_records.user_id')->where('colum', 'comment')->where('users.role', 5)->where('req_records.req_id', $row->reqID)->latest('req_records.updateValue_at')->first();
            $val = auth()->user()->role == 9 ? "disabled" : " ";
            if ($lastCommentUser) {
                if ($lastCommentUser->user_id != auth()->id()) {
                    $check = false;
                }
            }

            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {

                if ($check) {
                    $data = '<textarea '.$val.' title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" >'.$row->quacomment.' </textarea>';
                }
                else {
                    $data = '<textarea '.$val.' title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" > </textarea>';
                }
            }
            else {
                $data = '<textarea '.$val.'  title="'.$row->quacomment.'" disabled id="reqComment'.$row->reqID.'" class="textarea"  >'.$row->quacomment.' </textarea>';
            }
            return $data;
        })->rawColumns(['quacomment', 'action', 'class_id_quality'])->make(true);
    }

    public function status($getBy = 'empty')
    {
        $s = [
            0  => MyHelpers::admin_trans(auth()->id(), 'new req'),
            1  => MyHelpers::admin_trans(auth()->id(), 'open req'),
            2  => MyHelpers::admin_trans(auth()->id(), 'archive in sales agent req'),
            3  => MyHelpers::admin_trans(auth()->id(), 'wating sales manager req'),
            4  => MyHelpers::admin_trans(auth()->id(), 'rejected sales manager req'),
            //5 => MyHelpers::admin_trans(auth()->id(), 'archive in sales manager req'),
            5  => MyHelpers::admin_trans(auth()->id(), 'wating sales manager req'),
            6  => MyHelpers::admin_trans(auth()->id(), 'wating funding manager req'),
            7  => MyHelpers::admin_trans(auth()->id(), 'rejected funding manager req'),
            // 8 => MyHelpers::admin_trans(auth()->id(), 'archive in funding manager req'),
            8  => MyHelpers::admin_trans(auth()->id(), 'wating funding manager req'),
            9  => MyHelpers::admin_trans(auth()->id(), 'wating mortgage manager req'),
            10 => MyHelpers::admin_trans(auth()->id(), 'rejected mortgage manager req'),
            // 11 => MyHelpers::admin_trans(auth()->id(), 'archive in mortgage manager req'),
            11 => MyHelpers::admin_trans(auth()->id(), 'wating mortgage manager req'),
            12 => MyHelpers::admin_trans(auth()->id(), 'wating general manager req'),
            13 => MyHelpers::admin_trans(auth()->id(), 'rejected general manager req'),
            //14 => MyHelpers::admin_trans(auth()->id(), 'archive in general manager req'),
            14 => MyHelpers::admin_trans(auth()->id(), 'wating general manager req'),
            15 => MyHelpers::admin_trans(auth()->id(), 'Canceled'),
            16 => MyHelpers::admin_trans(auth()->id(), 'Completed'),
            17 => MyHelpers::admin_trans(auth()->id(), 'draft in mortgage maanger'),
            18 => MyHelpers::admin_trans(auth()->id(), 'wating sales manager req'),
            19 => MyHelpers::admin_trans(auth()->id(), 'wating sales agent req'),
            20 => MyHelpers::admin_trans(auth()->id(), 'rejected sales manager req'),
            21 => MyHelpers::admin_trans(auth()->id(), 'wating funding manager req'),
            22 => MyHelpers::admin_trans(auth()->id(), 'rejected funding manager req'),
            23 => MyHelpers::admin_trans(auth()->id(), 'wating general manager req'),
            24 => MyHelpers::admin_trans(auth()->id(), 'cancel mortgage manager req'),
            25 => MyHelpers::admin_trans(auth()->id(), 'rejected general manager req'),
            26 => MyHelpers::admin_trans(auth()->id(), 'Completed'),
            27 => MyHelpers::admin_trans(auth()->id(), 'Canceled'),
            28 => MyHelpers::admin_trans(auth()->id(), 'Undefined'),
            29 => MyHelpers::admin_trans(auth()->id(), 'Rejected and archived'),
            30 => "Undefined",
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[28]);
    }

    public function recivedReqs()
    {

        /*    $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');

    */

        $qualityID = auth()->id();

        //$this->qualityReqsNotRecived();
        //dd($qualityID);
        //$r = QualityRequest::query()
        //    ->where('user_id', $qualityID)
        //    ->where('allow_recive', '=',1)
        //    ->whereIn('status', [0, 1, 2])
        //    ->where('is_followed', '=',0)
        //    ->get();
        //dd($r);
        //$requests = DB::table('quality_reqs')
        //    ->join('requests', 'requests.id', 'quality_reqs.req_id')
        //    ->where('quality_reqs.user_id', $qualityID)
        //    ->where('quality_reqs.allow_recive', '=', 1)
        //    ->whereIn('quality_reqs.status', [0, 1, 2])
        //    ->where('quality_reqs.is_followed', '=', 0)
        //    ->select('quality_reqs.is_followed', 'quality_reqs.id', 'quality_reqs.user_id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')
        //    ->get();
        ////dd($requests);
        //if ($requests->count() > 0) {
        //    foreach ($requests as $request) {
        //        $check = MyHelpers::checkConditionMatch($request->id);
        //        if ($check == false) {
        //            $update_req = DB::table('quality_reqs')->where('id', $request->id)->where('con_id', '!=', null)->update(['status' => 3]);
        //            if ($update_req == 1) {
        //                DB::table('tasks')->where('req_id', $request->id)->where('user_id', $request->user_id)->update([
        //                    'status' => 3 //completed
        //                ]);
        //            }
        //        }
        //    }
        //}

        //$requests = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->where('quality_reqs.user_id', $qualityID)->where('quality_reqs.allow_recive', 1)->whereIn('quality_reqs.status', [0, 1, 2])->where('quality_reqs.is_followed', 0)->select('quality_reqs.id',
        //    'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')->get();

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();

        $check = 0; // check if this user is belong for at lest one user
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $requests = collect();
        $users = User::where("role",5)->where("subdomain","<>",null)->get();
        return view('QualityManager.Request.recivedReqs', compact('users','requests', 'check', 'classifcations_sa', 'classifcations_qu', 'all_status', 'collaborators', 'task_status', 'worke_sources', 'request_sources'));
    }

    public function recivedReqs_datatable(Request $request)
    {

        /* $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');
        */
        //dd(123);
        $users = User::where("role",5)->where("subdomain","<>",null)->pluck("id")->toArray();
        $requests = DB::table('quality_reqs')
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('quality_reqs.allow_recive', 1)
            ->whereIn('quality_reqs.status', [0, 1, 2])
            ->where('quality_reqs.is_followed', 0)
            ->whereNotNull('quality_reqs.updated_at')
            ->select('quality_reqs.id',"others.name as  quality", 'requests.id as reqID', 'users.name as agentName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent', 'requests.class_id_quality', 'quality_reqs.status',
                'requests.type', 'requests.collaborator_id', 'requests.source', 'quality_reqs.created_at', 'requests.created_at as req_created_at')
            ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                $q->whereIn('quality_reqs.user_id', $users);
            })->when(auth()->user()->role != 9 ,function($q,$v) {

                $q->where('quality_reqs.user_id', auth()->id());
            });

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '<=', $request->get('req_date_to'));
        }
        if ($request->get('users')&& is_array($request->get('users'))) {
            $requests = $requests->whereIn('quality_reqs.user_id',  $request->get('users'));
        }
        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }
        /*
                if ($request->has('search')) {
                    if (array_key_exists('value', $request->search)) {
                        if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                            $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                            if ($mobile->count() == 0) {
                                $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                                if ($mobiles != null) {
                                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                                }
                            }
                            else {
                                $requests = $requests->where('customers.mobile', $request->search['value']);
                            }
                        }
                        $search = $request->search;
                        $search['value'] = null;
                        $request->merge([
                            'search' => $search,
                        ]);
                    }

                }*/
        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            if(auth()->user()->role != 9) {
                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'tasks').'">
                <i class="fa fa-comments"></i></span>';

                $data = $data.'<span class="item pointer" id="need_turned_req" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'add to need to be turned request basket').'">
                <a href="'.route('quality.manager.add_needToBeTurnedReq', $row->id).'"><i class="fa fa-retweet"></i></a></span>';
            }
            $data = $data.'<span class="item pointer" id="questions" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'quality questions').'">
           <i class="fa fa-question"></i></span>';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
            <a href="'.route('quality.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';

            if(auth()->user()->role != 9) {
                if ($row->status == 3 || $row->status == 4) {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Restore Request').'">
                    <a href="'.route('quality.manager.restoreReq', $row->id).'"><i class="fa fa-redo"></i></a></span>';
                }
            }

            /*
            $data = $data . '<span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="' . MyHelpers::admin_trans(auth()->id(), 'Add to need action req') . '">
       <a onclick="transalteData(' . $row->id . ')"><i class="fa fa-random"></i></a></span>';
       */

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return @$this->statusQuality()[$row->status] ?? $this->statusQuality()[4];
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[30];
        })->editColumn('class_id_agent', function ($row) {

            $classValue = classifcation::find($row->class_id_agent);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_agent;
        })->editColumn('class_id_quality', function ($row) {

            $check = true;
            $lastClassUser = DB::table('req_records')->where('colum', 'class_quality')->where('req_records.req_id', $row->reqID)->latest('req_records.updateValue_at')->first();

            if ($lastClassUser) {
                if ($lastClassUser->user_id != auth()->id()) {
                    $check = false;
                }
            }

            $classifcations_qu = classifcation::where('user_role', 5)->get();
            $val = auth()->user()->role == 9 ? "disabled" : " ";
            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {

                $data = '<select '.$val.'  id="reqClass'.$row->reqID.'" style="width:150px;"  name="reqClass" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); saveclass(this,'.$row->reqID.')" >';

                $data = $data.'<option value="" selected>---</option>';

                foreach ($classifcations_qu as $classifcations) {

                    if (($classifcations->id == $row->class_id_quality) && $check) {
                        $data = $data.'<option value="'.$classifcations->id.'" selected>'.$classifcations->value.'</option>';
                    }
                    else {
                        $data = $data.'<option value="'.$classifcations->id.'">'.$classifcations->value.'</option>';
                    }
                }

                $data = $data.'</select>';

                return $data;
            }
            else {
                $classifcations_qu = classifcation::where('id', $row->class_id_quality)->first();

                if ($classifcations_qu != null) {
                    return $classifcations_qu->value;
                }
                else {
                    return $row->class_id_quality;
                }
            }
        })->editColumn('quacomment', function ($row) {

            $check = true;
            $lastCommentUser = DB::table('req_records')->join('users', 'users.id', 'req_records.user_id')->where('colum', 'comment')->where('users.role', 5)->where('req_records.req_id', $row->reqID)->latest('req_records.updateValue_at')->first();

            if ($lastCommentUser) {
                if ($lastCommentUser->user_id != auth()->id()) {
                    $check = false;
                }
            }
            $val = auth()->user()->role == 9 ? "disabled" : " ";
            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {

                if ($check) {
                    $data = '<textarea '.$val.' title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" >'.$row->quacomment.' </textarea>';
                }
                else {
                    $data = '<textarea '.$val.' title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" > </textarea>';
                }
            }
            else {
                $data = '<textarea  title="'.$row->quacomment.'" disabled id="reqComment'.$row->reqID.'" class="textarea"  >'.$row->quacomment.' </textarea>';
            }
            return $data;
        })->rawColumns(['quacomment', 'action', 'class_id_quality'])->make(true);
    }

    public function followReqs()
    {
        $qualityID = auth()->id();
        //$this->qualityReqsNotRecived();
        //$requests = DB::table('quality_reqs')
        //    ->join('requests', 'requests.id', 'quality_reqs.req_id')
        //    ->where('quality_reqs.user_id', $qualityID)
        //    ->where('quality_reqs.allow_recive', 1)
        //    ->whereIn('quality_reqs.status', [0, 1, 2])
        //    ->where('quality_reqs.is_followed', 1)
        //    ->select('quality_reqs.id', 'quality_reqs.user_id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')
        //    ->get();
        //if ($requests->count() > 0) {
        //    foreach ($requests as $request) {
        //        $check = MyHelpers::checkConditionMatch($request->id);
        //        if ($check == false) {
        //            $update_req = DB::table('quality_reqs')->where('id', $request->id)->where('con_id', '!=', null)->update(['status' => 3]);
        //            if ($update_req == 1) {
        //                DB::table('tasks')->where('req_id', $request->id)->where('user_id', $request->user_id)
        //                    ->update([
        //                        'status' => 3 //completed
        //                    ]);
        //            }
        //        }
        //    }
        //}

        //$requests = DB::table('quality_reqs')
        //    ->join('requests', 'requests.id', 'quality_reqs.req_id')
        //    ->where('quality_reqs.user_id', $qualityID)
        //    ->where('quality_reqs.allow_recive', 1)
        //    ->whereIn('quality_reqs.status', [0, 1, 2])
        //    ->where('quality_reqs.is_followed', 1)
        //    ->select('quality_reqs.id', 'requests.comment', 'requests.type', 'requests.source', 'quality_reqs.created_at')
        //    ->get();

        /*
                \App\Models\QualityRequest::query()
                    ->has('request')
                    ->where('quality_reqs.user_id', $qualityID)
                    ->where('quality_reqs.allow_recive', 1)
                    ->whereIn('quality_reqs.status', [0, 1, 2])
                    ->where('quality_reqs.is_followed', 1)
                    ->chunk(500, function ($requests) {
                        //dd(2);
                        foreach ($requests as $request) {
                            //dd($request);
                            //$check = MyHelpers::checkConditionMatch($request->id);
                            $check = true;
                            if ($check == false) {
                                $update_req = DB::table('quality_reqs')
                                    ->where('id', $request->id)
                                    ->where('con_id', '!=', null)
                                    ->update(['status' => 3]);
                                if ($update_req == 1) {
                                    DB::table('tasks')
                                        ->where('req_id', $request->id)
                                        ->where('user_id', $request->user_id)
                                        ->update(['status' => 3]);
                                }
                            }
                        }

                    });
                */

        //dd(1);
        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();

        $check = 0; // check if this user is belong for at lest one user
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $requests = collect();
        $users = User::where("role",5)->where("subdomain","<>",null)->get();
        return view('QualityManager.Request.followReqs', compact('users','requests', 'check', 'all_status', 'classifcations_sa', 'classifcations_qu', 'collaborators', 'task_status', 'worke_sources', 'request_sources'));
    }

    public function followReqs_datatable(Request $request)
    {
        $users = User::where("role",5)->where("subdomain","<>",null)->pluck("id")->toArray();
        $requests = DB::table('quality_reqs')
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')

            ->join('users', 'users.id', 'requests.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('quality_reqs.allow_recive', 1)
            ->whereIn('quality_reqs.status', [0, 1, 2])
            ->where('quality_reqs.is_followed', 1)
            //->whereDate('quality_reqs.created_at','>=','2022-02-27')
            ->select('quality_reqs.is_followed',"others.name as  quality", 'quality_reqs.id', 'requests.id as reqID', 'users.name as agentName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent',
                'requests.class_id_quality', 'quality_reqs.status', 'requests.type', 'requests.collaborator_id', 'requests.source', 'quality_reqs.created_at', 'requests.created_at as req_created_at')
            ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                $q->whereIn('quality_reqs.user_id', $users);
            })
            ->when(auth()->user()->role != 9 ,function($q,$v) {

                $q->where('quality_reqs.user_id', auth()->id());
            });

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '<=', $request->get('req_date_to'));
        }
        if ($request->get('users')&& is_array($request->get('users'))) {
            $requests = $requests->whereIn('quality_reqs.user_id',  $request->get('users'));
        }
        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }
        /*
                if ($request->has('search')) {
                    if (array_key_exists('value', $request->search)) {
                        if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                            $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                            if ($mobile->count() == 0) {
                                $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                                if ($mobiles != null) {
                                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                                }
                            }
                            else {
                                $requests = $requests->where('customers.mobile', $request->search['value']);
                            }
                        }
                        $search = $request->search;
                        $search['value'] = null;
                        $request->merge([
                            'search' => $search,
                        ]);
                    }

                }*/

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            if(auth()->user()->role != 9) {
                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'tasks').'">
            <i class="fa fa-comments"></i></span>';

            $data = $data.'<span class="item pointer" id="need_turned_req" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'add to need to be turned request basket').'">
            <a href="'.route('quality.manager.add_needToBeTurnedReq', $row->id).'"><i class="fa fa-retweet"></i></a></span>';
            }


            $data = $data.'<span class="item pointer" id="questions" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'quality questions').'">
           <i class="fa fa-question"></i></span>';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
            <a href="'.route('quality.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';

            if(auth()->user()->role != 9) {
                $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Restore').'">
            <a href="'.route('quality.manager.restoreRequest', $row->id).'"> <i class="fa fa-reply"></i></a></span>';

            }

            /*
            $data = $data . '<span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="' . MyHelpers::admin_trans(auth()->id(), 'Add to need action req') . '">
            <a onclick="transalteData('. $row->id.')"><i class="fa fa-random"></i></a></span>';
            */

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return @$this->statusQuality()[$row->status] ?? $this->statusQuality()[4];
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[30];
        })->editColumn('class_id_agent', function ($row) {

            $classValue = classifcation::find($row->class_id_agent);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_agent;
        })->editColumn('class_id_quality', function ($row) {

            $check = true;
            $name = classifcation::query()->find($row->class_id_quality);
            $name = $name ? $name->value : null;
            $lastClassUser = DB::table('req_records')
                ->where('colum', 'class_quality')
                ->where(fn($b) => $b->where('value', $row->class_id_quality)->orWhere('value', $name))
                ->where('req_records.req_id', $row->reqID)
                ->latest('req_records.updateValue_at')
                ->first();
            //dd($lastClassUser);
            if ($lastClassUser) {
                if ($lastClassUser->user_id != auth()->id()) {
                    $check = false;
                }
            }
            //if (!$check) {
            //dd($row);
            //if ($row->is_followed) {
            //QualityRequest::query()->find($row->id)->update(['is_followed' => 0]);
            //dd($row);
            //}
            //}

            $classifcations_qu = classifcation::where('user_role', 5)->get();
            $val = auth()->user()->role == 9 ? "disabled" : "";
            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {

                $data = '<select '.$val.'  id="reqClass'.$row->reqID.'" style="width:150px;"  name="reqClass" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); saveclass(this,'.$row->reqID.')" >';

                $data = $data.'<option value="" selected>---</option>';

                foreach ($classifcations_qu as $classifcations) {

                    if (($classifcations->id == $row->class_id_quality) && $check) {
                        $data = $data.'<option value="'.$classifcations->id.'" selected>'.$classifcations->value.'</option>';
                    }
                    else {
                        //QualityRequest::query()->find($row->id)->update([
                        //    'is_followed' => 0,
                        //    'status'      => 0,
                        //]);
                        //dd($row);
                        $data = $data.'<option value="'.$classifcations->id.'">'.$classifcations->value.'</option>';
                    }
                }

                $data = $data.'</select>';

                return $data;
            }
            else {
                $classifcations_qu = classifcation::where('id', $row->class_id_quality)->first();

                if ($classifcations_qu != null) {
                    return $classifcations_qu->value;
                }
                else {
                    return $row->class_id_quality;
                }
            }
        })->editColumn('quacomment', function ($row) {

            $check = true;
            $lastCommentUser = DB::table('req_records')->join('users', 'users.id', 'req_records.user_id')->where('colum', 'comment')->where('users.role', 5)->where('req_records.req_id', $row->reqID)->latest('req_records.updateValue_at')->first();

            if ($lastCommentUser) {
                if ($lastCommentUser->user_id != auth()->id()) {
                    $check = false;
                }
            }
            $val = auth()->user()->role == 9 ? "disabled" : "";
            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {

                if ($check) {
                    $data = '<textarea '.$val.' title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" >'.$row->quacomment.' </textarea>';
                }
                else {
                    $data = '<textarea '.$val.' title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" > </textarea>';
                }
            }
            else {
                $data = '<textarea  '.$val.' title="'.$row->quacomment.'" disabled id="reqComment'.$row->reqID.'" class="textarea"  >'.$row->quacomment.' </textarea>';
            }
            return $data;
        })->rawColumns(['quacomment', 'action', 'class_id_quality'])->make(true);
    }

    public function archReqs()
    {

        /*    $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');

    */

        //$this->qualityReqsNotRecived();

        $qualityID = auth()->id();

        //$requests = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->where('quality_reqs.user_id', $qualityID)->where('quality_reqs.allow_recive', 1)->where('quality_reqs.status', 5)->select('quality_reqs.id', 'requests.comment', 'requests.type', 'requests.source',
        //    'quality_reqs.created_at')->get();

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();

        $check = 0; // check if this user is belong for at lest one user
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $requests = collect();
        $users = User::where("role",5)->where("subdomain","<>",null)->get();
        return view('QualityManager.Request.archReqs', compact('users','requests', 'check', 'all_status', 'classifcations_sa', 'classifcations_qu', 'collaborators', 'task_status', 'worke_sources', 'request_sources'));
    }

    public function archReqs_datatable(Request $request)
    {

        /* $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');
        */
        $users = User::where("role",5)->where("subdomain","<>",null)->pluck("id")->toArray();
        $requests = DB::table('quality_reqs')
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('quality_reqs.allow_recive', 1)
            ->where('quality_reqs.status', 5)
            ->select('quality_reqs.id',"others.name as  quality", 'requests.id as reqID', 'users.name as agentName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent', 'requests.class_id_quality', 'quality_reqs.status',
                'requests.type', 'requests.collaborator_id', 'requests.source', 'quality_reqs.created_at', 'requests.created_at as req_created_at')
            ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                $q->whereIn('quality_reqs.user_id', $users);
            })
            ->when(auth()->user()->role != 9 ,function($q,$v) {
                $q->where('quality_reqs.user_id', auth()->id());
            });

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '<=', $request->get('req_date_to'));
        }
        if ($request->get('users')&& is_array($request->get('users'))) {
            $requests = $requests->whereIn('quality_reqs.user_id',  $request->get('users'));
        }
        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            if(auth()->user()->role == 9){
                $data = $data.'<span class="item pointer" id="questions" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'quality questions').'">
           <i class="fa fa-question"></i></span>';

            }else{
                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'tasks').'">
            <i class="fa fa-comments"></i></span>';


            $data = $data.'<span class="item pointer" id="questions" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'quality questions').'">
            <i class="fa fa-question"></i></span>';

                $data = $data.'<span  class="item pointer" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Restore').'">
            <a href="'.route('quality.manager.restoreRequest', $row->id).'"> <i class="fa fa-reply"></i></a></span>';

                /*
                $data = $data . '<span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="' . MyHelpers::admin_trans(auth()->id(), 'Add to need action req') . '">
                <a onclick="transalteData('. $row->id.')"><i class="fa fa-random"></i></a></span>';
                */
            }


            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
                <a href="'.route('quality.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';


            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return @$this->statusQuality()[$row->status] ?? $this->statusQuality()[4];
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[30];
        })->editColumn('class_id_agent', function ($row) {

            $classValue = classifcation::find($row->class_id_agent);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_agent;
        })->editColumn('class_id_quality', function ($row) {

            $check = true;
            $lastClassUser = DB::table('req_records')->where('colum', 'class_quality')->where('req_records.req_id', $row->reqID)->latest('req_records.updateValue_at')->first();

            if ($lastClassUser) {
                if ($lastClassUser->user_id != auth()->id()) {
                    $check = false;
                }
            }

            $classifcations_qu = classifcation::where('user_role', 5)->get();
            $val = auth()->user()->role == 9 ? "disabled" : " ";
            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {

                $data = '<select '.$val.'   id="reqClass'.$row->reqID.'" style="width:150px;"  name="reqClass" onfocus="this.size=3;" onblur="this.size=1;"  onchange="this.size=1; this.blur(); saveclass(this,'.$row->reqID.')" >';

                $data = $data.'<option value="" selected>---</option>';

                foreach ($classifcations_qu as $classifcations) {

                    if (($classifcations->id == $row->class_id_quality) && $check) {
                        $data = $data.'<option value="'.$classifcations->id.'" selected>'.$classifcations->value.'</option>';
                    }
                    else {
                        $data = $data.'<option value="'.$classifcations->id.'">'.$classifcations->value.'</option>';
                    }
                }

                $data = $data.'</select>';

                return $data;
            }
            else {
                $classifcations_qu = classifcation::where('id', $row->class_id_quality)->first();

                if ($classifcations_qu != null) {
                    return $classifcations_qu->value;
                }
                else {
                    return $row->class_id_quality;
                }
            }
        })->editColumn('quacomment', function ($row) {

            $check = true;
            $lastCommentUser = DB::table('req_records')->join('users', 'users.id', 'req_records.user_id')->where('colum', 'comment')->where('users.role', 5)->where('req_records.req_id', $row->reqID)->latest('req_records.updateValue_at')->first();

            if ($lastCommentUser) {
                if ($lastCommentUser->user_id != auth()->id()) {
                    $check = false;
                }
            }
            $val = auth()->user()->role == 9 ? "disabled" : " ";
            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {

                if ($check) {
                    $data = '<textarea '.$val.' title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" >'.$row->quacomment.' </textarea>';
                }
                else {
                    $data = '<textarea '.$val.' title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" > </textarea>';
                }
            }
            else {
                $data = '<textarea  '.$val.' title="'.$row->quacomment.'" disabled id="reqComment'.$row->reqID.'" class="textarea"  >'.$row->quacomment.' </textarea>';
            }
            return $data;
        })->rawColumns(['quacomment', 'action', 'class_id_quality'])->make(true);
    }

    public function completedReqs()
    {

        /*    $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');

    */

        //$this->qualityReqsNotRecived();

        $qualityID = auth()->id();

        if (MyHelpers::checkActiveQualityReqs()) {

            $requestsNotRecived = DB::table('quality_reqs')
                ->join('request_conditions', 'request_conditions.id', 'quality_reqs.con_id')
                ->when(auth()->user()->role == 5,function($q,$v) use ($qualityID){
                    $q->where('quality_reqs.user_id', $qualityID);
                })
                ->where('quality_reqs.allow_recive', 0)->where('quality_reqs.status', 3)->select('quality_reqs.id', 'quality_reqs.req_id',
                    'quality_reqs.allow_recive', 'request_conditions.timeDays', 'quality_reqs.created_at')->get();

            if ($requestsNotRecived->count() > 0) {

                $now = Carbon::now();

                foreach ($requestsNotRecived as $request) {

                    $date = Carbon::parse($request->created_at);
                    $checkValue = $date->diffInDays($now);
                    $timedaysWithActiveCounter = $request->timeDays + MyHelpers::getQualityCounter();

                    if ($timedaysWithActiveCounter <= $checkValue && auth()->user()->role == 5) {

                        $check = MyHelpers::checkConditionMatch($request->id);
                        if ($check != false) {

                            $quality_id = MyHelpers::findNextQuality();

                            DB::table('quality_reqs')->where('id', $request->id)->update(['con_id' => $check, 'allow_recive' => 1, 'user_id' => $quality_id, 'created_at' => Carbon::now('Asia/Riyadh')->format("Y-m-d H:i:s")]);

                            DB::table('notifications')->insert([ // add notification to send user
                                                                 'value'      => MyHelpers::admin_trans(auth()->id(), 'New Request Added'),
                                                                 'recived_id' => $request->user_id,
                                                                 'created_at' => (Carbon::now('Asia/Riyadh')),
                                                                 'type'       => 0,
                                                                 'req_id'     => $request->id,
                            ]);

                            DB::table('request_histories')->insert([
                                'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                                'user_id'      => null,
                                'recive_id'    => $quality_id,
                                'history_date' => (Carbon::now('Asia/Riyadh')),
                                'req_id'       => $request->req_id,
                                'content'      => null,
                            ]);
                        }
                    }
                }
            }
        }

        $requests = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->when(auth()->user()->role == 5,function($q,$v) use ($qualityID){
                $q->where('quality_reqs.user_id', $qualityID);
            })
            ->where('quality_reqs.allow_recive', 1)->where('quality_reqs.status', 3)->select('quality_reqs.id', 'requests.comment', 'requests.type', 'requests.source',
                'quality_reqs.created_at')->get();

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();

        $check = 0; // check if this user is belong for at lest one user
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $users = User::where("role",5)->where("subdomain","<>",null)->get();
        return view('QualityManager.Request.completedReqs', compact('users','requests', 'check', 'all_status', 'classifcations_sa', 'classifcations_qu', 'collaborators', 'task_status', 'worke_sources', 'request_sources'));
    }

    public function completedReqs_datatable(Request $request)
    {

        /* $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');
        */
        //$c = QualityRequest::query()->ArchivedBasket()->where('quality_reqs.user_id',auth()->id());
        //dd($c->count());
        $users = User::where("role",5)->where("subdomain","<>",null)->pluck("id")->toArray();
        $requests = DB::table('quality_reqs')
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->where('quality_reqs.allow_recive', 1)
            ->where('quality_reqs.status', 3)
            ->select('quality_reqs.id',"others.name as  quality", 'requests.id as reqID', 'users.name as agentName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent', 'requests.class_id_quality', 'quality_reqs.status',
                'requests.type', 'requests.collaborator_id', 'requests.source', 'quality_reqs.created_at', 'requests.created_at as req_created_at')
            ->when(auth()->user()->role == 9 ,function($q,$v) use ($users){
                $q->whereIn('quality_reqs.user_id', $users);
            })
            ->when(auth()->user()->role != 9 ,function($q,$v) {
                $q->where('quality_reqs.user_id', auth()->id());
            });

        //dd($requests->count());
        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '<=', $request->get('req_date_to'));
        }
        if ($request->get('users')&& is_array($request->get('users'))) {
            $requests = $requests->whereIn('quality_reqs.user_id',  $request->get('users'));
        }
        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }
        /*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            if(auth()->user()->role == 9){
                $data = $data.'<span class="item pointer" id="questions" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'quality questions').'">
           <i class="fa fa-question"></i></span>';


            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
            <a href="'.route('quality.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';

            }else{
                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'tasks').'">
            <i class="fa fa-comments"></i></span>';

                $data = $data.'<span class="item pointer" id="questions" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'quality questions').'">
           <i class="fa fa-question"></i></span>';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
                    <a href="'.route('quality.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';

                if ($row->status == 3 || $row->status == 4) {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Restore Request').'">
       <a href="'.route('quality.manager.restoreReq', $row->id).'"><i class="fa fa-reply"></i></a></span>';
                }

            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return @$this->statusQuality()[$row->status] ?? $this->statusQuality()[4];
        })->editColumn('statusReq', function ($row) {
            return @$this->status()[$row->statusReq] ?? $this->status()[30];
        })->editColumn('class_id_agent', function ($row) {

            $classValue = classifcation::find($row->class_id_agent);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_agent;
        })->editColumn('class_id_quality', function ($row) {

            $classValue = classifcation::find($row->class_id_quality);
            if ($classValue) {
                return $classValue->value;
            }
            return $row->class_id_quality;
        })->editColumn('quacomment', function ($row) {

            $check = true;
            $lastCommentUser = DB::table('req_records')->join('users', 'users.id', 'req_records.user_id')->where('colum', 'comment')->where('users.role', 5)->where('req_records.req_id', $row->reqID)->latest('req_records.updateValue_at')->first();

            if ($lastCommentUser) {
                if ($lastCommentUser->user_id != auth()->id()) {
                    $check = false;
                }
            }

            if ($row->status == 0 || $row->status == 1 || $row->status == 2 || $row->status == 5) {

                if ($check) {
                    $data = '<textarea title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" >'.$row->quacomment.' </textarea>';
                }
                else {
                    $data = '<textarea title="'.$row->quacomment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" > </textarea>';
                }
            }
            else {
                $data = '<textarea  title="'.$row->quacomment.'" disabled id="reqComment'.$row->reqID.'" class="textarea"  >'.$row->quacomment.' </textarea>';
            }
            return $data;
        })->rawColumns(['quacomment', 'action'])->make(true);
    }


    public function add_needToBeTurnedReq($id){

        # check if already added before
        $quality_already_added = QualityRequestNeedTurned::query()->where('quality_req_id',$id)->first();
        if(!empty($quality_already_added))
            return redirect()->back()->with('message3', MyHelpers::admin_trans(auth()->user()->id, "You already add this request"));
        
        # add 
        $get_quality_req_info = DB::table('quality_reqs')->where('id',$id)->first();
        $get_request_info = DB::table('requests')->where('id',$get_quality_req_info->req_id)->first();
        $newRequest = QualityRequestNeedTurned::create([
            'quality_id'         => auth()->id(),
            'quality_req_id' => $get_quality_req_info->id,
            'agent_req_id'         =>  $get_quality_req_info->req_id,
            'previous_agent_id' =>  $get_request_info->user_id,
        ]);

        $value = ' موظف الجودة: '. auth()->user()->name.' أضاف طلب جديد لسلة التحويل ' ;
        #send notifiy to admin
        $admins = MyHelpers::getAllActiveAdmin();
        foreach ($admins as $admin) {
            if (MyHelpers::checkDublicateNotification($admin->id, $value, $get_quality_req_info->req_id)) {
                DB::table('notifications')->insert([ // add notification to send user
                                                     'value'      => $value,
                                                     'recived_id' => $admin->id,
                                                     'created_at' => (Carbon::now()),
                                                     'type'       => 5,
                                                     'req_id'     => $get_quality_req_info->req_id,
                ]);
            }
        }
                    
        return redirect()->back()->with('message4', MyHelpers::admin_trans(auth()->user()->id, "Request added sucessfully"));
        
    }

    public function remove_needToBeTurnedReq($id){

        # check if already added before
        $quality_already_added = QualityRequestNeedTurned::query()->where('id',$id)->first();
        if(empty($quality_already_added))
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, "No Requests"));
        #check if it's not new
        if ($quality_already_added->status != 0)
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, "The request was finished, you cannot remove it"));
        # remove 
        #remove notification
        DB::table('notifications')->where('req_id',$quality_already_added->agent_req_id)->where('is_done',0)->delete();
        $quality_already_added->delete();
        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, "Request removed sucessfully"));
        
    }
    public function needToBeTurnedReq()
    {
        $qualityID = auth()->id();

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $collaborators = DB::table('users')->where('role', 6)->get();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();

        $check = 0; // check if this user is belong for at lest one user
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $requests = collect();
        $users = User::where("role",5)->where("subdomain","<>",null)->get();
        return view('QualityManager.Request.qualityNeedTurnedReqs', compact('users','requests', 'check', 'all_status', 'classifcations_sa', 'classifcations_qu', 'collaborators', 'task_status', 'worke_sources', 'request_sources'));
    }

    public function needToBeTurnedReq_datatable(Request $request)
    {
        $users = User::where("role",5)->where("subdomain","<>",null)->pluck("id")->toArray();
        $requests = DB::table('quality_request_need_turneds')
            ->join('quality_reqs', 'quality_reqs.id', 'quality_request_need_turneds.quality_req_id')
            ->join('users as others', 'others.id', 'quality_reqs.user_id')
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'requests.user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select("others.name as  quality", 'quality_reqs.id', 'requests.id as reqID', 'users.name as agentName', 'customers.name',  'requests.quacomment', 'requests.type'
                ,'quality_request_need_turneds.status','quality_request_need_turneds.created_at' , 'reject_reason','quality_request_need_turneds.id as turned_id')
            ->when(auth()->user()->role != 9 ,function($q,$v) {
                $q->where('quality_request_need_turneds.quality_id', auth()->id());
            });

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_request_need_turneds.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_request_need_turneds.created_at', '<=', $request->get('req_date_to'));
        }
        if ($request->get('users')&& is_array($request->get('users'))) {
            $requests = $requests->whereIn('quality_reqs.user_id',  $request->get('users'));
        }
        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('quality_reqs.comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('quality_reqs.comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }
       
        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            if(auth()->user()->role != 9) {
                $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'tasks').'">
            <i class="fa fa-comments"></i></span>';


            if ($row->status == 0){
                $data = $data.'<span class="item pointer" id="remove_from_turned" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'remove from need turned request').'">
                <a href="'.route('quality.manager.remove_needToBeTurnedReq', $row->turned_id).'"><i class="fa fa-times"></i></a></span>';
            }

            }


            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
                    <a href="'.route('quality.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';

            
            $data = $data.'<span class="item pointer" id="questions" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'quality questions').'">
                    <i class="fa fa-question"></i></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            $value = 'جديد';
            if ($row->status == 1)
                $value = 'تم تحويل الطلب';
            else if ($row->status == 2)
                $value = 'تم رفض الطلب';
            return  $value;
        })->editColumn('quacomment', function ($row) {

            $check = true;

            $data = '<textarea  title="'.$row->quacomment.'" disabled id="reqComment'.$row->reqID.'" class="textarea"  >'.$row->quacomment.' </textarea>';
            return $data;
        })->rawColumns(['quacomment', 'action'])->make(true);
    }

    public function manageReq($id)
    {

        $userID = (auth()->id());

        $reqInfo = DB::table('quality_reqs')->where('id', $id)->first();
        $orginalReq = DB::table('requests')->where('id', $reqInfo->req_id)->first();

        $checkFollow = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', (auth()->id()))->where('type', '=', 1)->first();

        if ($orginalReq->class_id_quality != null) {
            if ($orginalReq->quacomment != null) {
                if (!empty($checkFollow)) {

                    # check class type
                    $getclassValue = DB::table('classifcations')->where('id', $orginalReq->class_id_quality)->first();
                    if ($getclassValue->type == 0){
                        if ($reqInfo->status == 5) {
                            return redirect()->back()->with('message3', 'الطلب مؤرشف ، ولا يمكن تغيير حالته إلى أن يتم تغيير التصنيف إلى تصنيف إيجابي');
                        }
                        else {
                            # set request to archive in agent & quality
                            DB::table('quality_reqs')->where('id', $reqInfo->id)->update([
                                'status' => 5, 'is_followed' => 0
                            ]);
                            DB::table('requests')->where('id', $orginalReq->id)->update([
                                'statusReq' => 2, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'add_to_archive' => Carbon::now('Asia/Riyadh'), 'add_to_stared' => null, 'add_to_followed' => null, 'updated_at' =>  Carbon::now('Asia/Riyadh')
                            ]);

                            # add request history 
                            DB::table('request_histories')->insert([
                                'title'        => RequestHistory::TITLE_MOVE_REQUEST_TO_ARCHIVED_BASKET,
                                'user_id'      => auth()->id(),
                                'recive_id'    => null,
                                'history_date' => (Carbon::now('Asia/Riyadh')),
                                'req_id'       =>  $orginalReq->id,
                                'content'      => RequestHistory::NEGATIVE_CLASS_QUALITY,
                            ]);

                            return redirect()->back()->with('message3', 'الطلب مؤرشف ، ولا يمكن تغيير حالته إلى أن يتم تغيير التصنيف إلى تصنيف إيجابي');
                        }
                    }
                    else{
                        if ($reqInfo->status == 5) {
                            $restRequest = DB::table('quality_reqs')->where('id', $id)->where('user_id', $userID)->update(['is_followed' => 0, 'status' => 1]);
                        } //set request
    
                        else {
                            $restRequest = DB::table('quality_reqs')->where('id', $id)->where('user_id', $userID)->update(['is_followed' => 0]);
                        } //set request
                    }

                    $restRequest = DB::table('quality_reqs')->where('id', $id)->where('user_id', $userID)->update(['is_followed' => 1, 'updated_at' => Carbon::now('Asia/Riyadh')]);
                    DB::table('request_histories')->insert([
                        'title'        => "الجوده: تم نقل الطلب الي المتابعة",
                        'user_id'      => auth()->id(),
                        'recive_id'    => null,
                        'history_date' => (Carbon::now('Asia/Riyadh')),
                        'req_id'       => $orginalReq->id,
                        'content'      => null,
                    ]);

                    if ($restRequest == 1) {
                        $agent_id =auth()->id();
                        if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                            MyHelpers::addDailyPerformanceRecord($agent_id);
                        }
                        MyHelpers::incrementDailyPerformanceColumn($agent_id, 'followed_basket',$id);
                        return redirect()->route('quality.manager.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Successfully'));
                    }
                    else {
                        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
                    }
                }
                else {
                    return redirect()->back()->with('message3', MyHelpers::admin_trans(auth()->id(), 'The request reminder is required'));
                }
            }
            else {
                return redirect()->back()->with('message3', MyHelpers::admin_trans(auth()->id(), 'The request comment is required'));
            }
        }
        else {
            return redirect()->back()->with('message4', MyHelpers::admin_trans(auth()->id(), 'The request class is required'));
        }
    }

    public function reqArchive($id)
    {

        return redirect()->back()->with('message4', 'الخاصية غير متاحة حاليا');

        $reqInfo = DB::table('quality_reqs')->where('id', $id)->first();
        $orginalReq = DB::table('requests')->where('id', $reqInfo->req_id)->first();

        if ($orginalReq->class_id_quality != null) {
            if ($orginalReq->quacomment != null) {

                $archRequest = DB::table('quality_reqs')->where('id', $id)->whereIn('status', [0, 1, 2])->update(['status' => 5, 'is_followed' => 0]); //archive request in sales agent

                if ($archRequest == 0) // not updated
                {
                    return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'you do not have a premation to do that'));
                }

                if ($archRequest == 1) { // updated sucessfull
                    $agent_id =auth()->id();
                    if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                        MyHelpers::addDailyPerformanceRecord($agent_id);
                    }
                    MyHelpers::incrementDailyPerformanceColumn($agent_id, 'archived_basket',$id);
                    return redirect()->route('quality.manager.recivedRequests')->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Successfully'));
                }
            }
            else {
                return redirect()->back()->with('message3', MyHelpers::admin_trans(auth()->id(), 'The request comment is required'));
            }
        }
        else {
            return redirect()->back()->with('message4', MyHelpers::admin_trans(auth()->id(), 'The request class is required'));
        }
        return redirect('/');
    }

    public function restReq(Request $request, $id)
    {

        return redirect()->back()->with('message2', 'لاسترجاع الطلب ، ينبغي تصنيفه بتصنيف إيجابي');

        $userID = (auth()->id());

        $restRequest = DB::table('quality_reqs')->where('id', $id)->where('user_id', $userID)
            // ->where('status', 5);
            ->where('is_followed', 1)->update(['is_followed' => 0]);

        $restRequest2 = DB::table('quality_reqs')->where('id', $id)->where('user_id', $userID)->where('status', 5)
            // ->where('is_followed', 1)
            ->update(['status' => 1]);

        if ($restRequest == 1) {
            return redirect()->route('quality.manager.followRequests')->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Successfully'));
        }
        elseif ($restRequest2 == 1) {
            return redirect()->route('quality.manager.archRequests')->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Successfully'));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
        }
    }

    public function questions($id)
    {

        $reqInfo = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('customers', 'customers.id', 'requests.customer_id')->where([
            'quality_reqs.id' => $id,
        ])->select('requests.*', 'quality_reqs.status as quStatus', 'customers.name', 'customers.mobile')->first();

        $status = $reqInfo->quStatus;
        $customerName = $reqInfo->name;
        $customerMobile = $reqInfo->mobile;

        $servay = DB::table('servays')->where([
            'req_id' => $id,
        ])->first();

        if (!$servay) {
            $servayId = DB::table('servays')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                [ //add it once use insertGetId
                  'user_id'    => $reqInfo->user_id,
                  'req_id'     => $id,
                  'created_at' => Carbon::now('Asia/Riyadh'),
                ]);

            $servay = DB::table('servays')->where([
                'id' => $servayId,
            ])->first();
        }

        //dd($servay);

        $serv_ques = DB::table('serv_ques')->where('serv_id', $servay->id)->get();

        $serv_ques_answered = DB::table('serv_ques')->where([
            'serv_id' => $servay->id,
        ])->pluck('ques_id');

        $questions = DB::table('questions')->where('status', 0)->ORwhereIn('id', $serv_ques_answered)->get();

        $total = $serv_ques;
        $que_true = $total->where('answer', 2)->count();

        $total = $serv_ques;
        $que_false = $total->where('answer', 1)->count();

        $total = $serv_ques;
        $que_not_answer = ($questions->count()) - ($que_true + $que_false);

        if ($questions->count() != 0) {
            $result = ($que_true * 100 / $questions->count());
        }
        else {
            $result = 0;
        }

        $result = number_format((float) $result, 2, '.', '');

        $id = $servay->id;

        return view('QualityManager.questions', compact('id', 'status', 'result', 'questions', 'serv_ques', 'que_true', 'que_false', 'que_not_answer', 'customerName', 'customerMobile'));
    }

    public function questions_post(Request $request, $servID)
    {

        $getAnswers = $request->except('status', '_token');
        if ($request->status == 0 || $request->status == 1 || $request->status == 2 || $request->status == 5) {
            $check = false;
            foreach ($getAnswers as $key => $getAnswer) {

                $ques = explode("#", $key); //[0]=check ,[1]= question id
                $quesID = $ques[1];

                $serv_que = DB::table('serv_ques')->where('ques_id', $quesID)->where('serv_id', $servID)->first();

                if (!$serv_que) {
                    $resultId = DB::table('serv_ques')->insertGetId( //insertGetId : insertGetId method to insert a record and then retrieve the ID
                        [ //add it once use insertGetId
                          'ques_id'    => $quesID,
                          'serv_id'    => $servID,
                          'created_at' => Carbon::now('Asia/Riyadh'),
                        ]);

                    $serv_que = DB::table('serv_ques')->where('id', $resultId)->first();
                }

                $update_serv_ques = DB::table('serv_ques')->where('id', $serv_que->id)->update([
                    'answer' => $getAnswer,
                ]);

                if ($update_serv_ques == 1) {
                    $check = true;
                }
            }

            if (!$check) {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'Try Again'));
            }
            else {
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord(auth()->id())) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord(auth()->id());
                }
                MyHelpers::incrementDailyPerformanceColumn(auth()->id(), 'star_basket',servay::find($serv_que->serv_id)->req_id);
                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->id(), 'Save Successfully'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
        }
    }

    public function mytask()
    {

        /* $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');

        */

        $this->delayedTask();

        $requests = DB::table('quality_reqs')->where('quality_reqs.user_id', auth()->id())->pluck('quality_reqs.id');

        $userID = auth()->id();

        $tasks = DB::table('tasks')->join('task_contents', 'task_contents.task_id', 'tasks.id')->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'requests.user_id')->join('customers', 'customers.id',
            'requests.customer_id')->where(function ($query) {

            $query->where(function ($query) {
                $query->where('tasks.user_id', auth()->id());
            });
            $query->orWhere(function ($query) {
                $query->where('tasks.recive_id', auth()->id());
            });
        })->where('task_contents.task_contents_status', 1)->whereIn('tasks.status', [2])->whereIn('tasks.req_id', $requests)->get();

        $tasks = $tasks->unique('id')->count();

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('QualityManager.Task.mytask', compact('tasks', 'classifcations_sa', 'classifcations_qu', 'all_status', 'task_status', 'worke_sources', 'request_sources'

        ));
    }

    public function delayedTask()
    {

        $tasks = DB::table('tasks')
            //->where('tasks.user_id', auth()->id())
            ->join('task_contents', 'task_contents.task_id', 'tasks.id')->join('users', 'users.id', 'tasks.user_id')->whereIn('tasks.status', [0, 1, 2])->where('task_contents.task_contents_status', 0)->where('users.role', 5)->select('tasks.id', 'task_contents.id as contentId',
                'task_contents.date_of_content')->get();

        //dd($tasks);

        foreach ($tasks as $task) {
            if (Carbon::parse($task->date_of_content)->diffInDays(Carbon::now()) >= 3) {

                $maxConent = task_content::where('task_id', $task->id)->max('id');

                //1# ADD 3 DAYS TO THIS TASK , TO INCREASE BAD ASSMMENT TO AGENT'S TASK
                $updateContent = task_content::whereId($maxConent)->update([
                    'task_contents_status' => 2,
                    'date_of_note'         => Carbon::parse($task->date_of_content)->addDays(3),
                ]);

                //2# GET THE LAST CONTENT OF TASK AND TASK INFO AND ALL TASK CONTENTS
                $taskContent = task_content::whereId($maxConent)->first();
                $taskInfo = task::whereId($task->id)->first();
                $allTaskContent = task_content::where('task_id', $task->id)->get();
                $contentCount = $allTaskContent->count();

                //3# SET TASK AS NOT COMPLETED
                $updateTask = DB::table('tasks')->where('id', $task->id)->update([
                    'status' => 4,
                ]);

                $reqInfo = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->where([
                    'quality_reqs.id' => $taskInfo->req_id,
                ])->select('requests.*')->first();

                //4# CREATE A NEW TASK FOR SAME REQUEST
                $newTask = task::create([
                    'req_id'    => $taskInfo->req_id,
                    'recive_id' => $taskInfo->recive_id,
                    'user_id'   => $taskInfo->user_id,
                ]);

                #add to need to action requests
                if (!empty($reqInfo)) {
                    $agentInfo = MyHelpers::getAgentInfo($reqInfo->user_id);
                    if ($agentInfo->status == 0) {
                        MyHelpers::addNeedActionReqWithoutConditions('مهمة جديدة', $reqInfo->user_id, $reqInfo->id);
                    }
                }

                //5# CREATE A NEW TASK CONTENT FOR SAME CONTENT
                if ($contentCount == 1) {
                    $newContent = task_content::create([
                        'content'         => $taskContent->content,
                        'date_of_content' => Carbon::now('Asia/Riyadh'),
                        'task_id'         => $newTask->id,
                    ]);
                }

                else {
                    for ($i = 0; $i < $contentCount - 1; $i++) {

                        $newContent = task_content::create([
                            'content'              => $allTaskContent[$i]->content,
                            'date_of_content'      => $allTaskContent[$i]->date_of_content,
                            'date_of_note'         => null,
                            'user_note'            => $allTaskContent[$i]->user_note,
                            'task_contents_status' => 2,
                            'task_id'              => $newTask->id,
                        ]);
                    }

                    //LAST CONTENT
                    $newContent = task_content::create([
                        'content'         => $taskContent->content,
                        'date_of_content' => Carbon::now('Asia/Riyadh'),
                        'task_id'         => $newTask->id,
                    ]);
                }
            }
        }
    }

    public function mytask_datatable(Request $request)
    {
        $quality_req = DB::table('quality_reqs')->where('quality_reqs.user_id', auth()->id())->pluck('quality_reqs.id');

        $requests = DB::table('tasks')->join('task_contents', 'task_contents.task_id', 'tasks.id')->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'requests.user_id')->join('customers', 'customers.id',
            'requests.customer_id')->where(function ($query) {
            $query->where(function ($query) {
                $query->where('tasks.user_id', auth()->id());
            });
            $query->orWhere(function ($query) {
                $query->where('tasks.recive_id', auth()->id());
            });
        })->where('task_contents.task_contents_status', 1)->whereIn('tasks.status', [2])->whereIn('tasks.req_id', $quality_req)->select('tasks.*', 'requests.comment', 'users.name as user_name', 'customers.mobile', 'customers.name', 'customers.salary', 'requests.collaborator_id', 'requests.source',
            'requests.type', 'requests.quacomment', 'quality_reqs.status as qustatus')->get();

        $requests = $requests->unique('id');
        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '<=', $request->get('req_date_to'));
        }

        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('task_status') && is_array($request->get('task_status'))) {

            $requests = $requests->whereIn('tasks.status', $request->get('task_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        // dd($all);
        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').' '.MyHelpers::admin_trans(auth()->id(), 'The Request').'">
            <a href="'.route('quality.manager.fundingRequest', $row->req_id).'"><i class="fa fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').' '.MyHelpers::admin_trans(auth()->id(), 'the task').'">
            <a href="'.route('all.show_q_task', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            if ($row->status == 0 || $row->status == 1) {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Edit').'">
            <a href="'.route('quality.manager.edittask', $row->id).'"><i class="fa fa-edit"></i></a></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            if ($data) {
                return $data->content;
            }
            return null;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            if ($data) {
                return $data->user_note;
            }
            return null;
        })->make(true);
    }

    public function sentTask()
    {

        //$this->delayedTask();

        $requests = DB::table('quality_reqs')->where('quality_reqs.user_id', auth()->id())->pluck('quality_reqs.id');

        $userID = auth()->id();

        $tasks = DB::table('tasks')->join('task_contents', 'task_contents.task_id', 'tasks.id')->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'requests.user_id')->join('customers', 'customers.id',
            'requests.customer_id')->where(function ($query) {

            $query->where(function ($query) {
                $query->where('tasks.user_id', auth()->id());
            });
            $query->orWhere(function ($query) {
                $query->where('tasks.recive_id', auth()->id());
            });
        })->where('task_contents.task_contents_status', 0)->whereIn('tasks.status', [0, 1, 2])->whereIn('tasks.req_id', $requests)->select('tasks.*', 'users.name as user_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')->get();

        $tasks = $tasks->unique('id')->count();

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('QualityManager.Task.senttask', compact('tasks', 'classifcations_sa', 'classifcations_qu', 'all_status', 'task_status', 'worke_sources', 'request_sources'

        ));
    }

    public function sentTask_datatable(Request $request)
    {

        /* $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');

        */

        $quality_req = DB::table('quality_reqs')->where('quality_reqs.user_id', auth()->id())->pluck('quality_reqs.id');

        $userID = auth()->id();

        $requests = DB::table('tasks')->join('task_contents', 'task_contents.task_id', 'tasks.id')->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'requests.user_id')->join('customers', 'customers.id',
            'requests.customer_id')->where(function ($query) {

            $query->where(function ($query) {
                $query->where('tasks.user_id', auth()->id());
            });
            $query->orWhere(function ($query) {
                $query->where('tasks.recive_id', auth()->id());
            });
        })->where('task_contents.task_contents_status', 0)->whereIn('tasks.status', [0, 1, 2])->whereIn('tasks.req_id', $quality_req)->select('tasks.*', 'users.name as user_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')->get();

        $requests = $requests->unique('id');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '<=', $request->get('req_date_to'));
        }

        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('task_status') && is_array($request->get('task_status'))) {

            $requests = $requests->whereIn('tasks.status', $request->get('task_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/

        // dd($all);
        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').' '.MyHelpers::admin_trans(auth()->id(), 'The Request').'">
            <a href="'.route('quality.manager.fundingRequest', $row->req_id).'"><i class="fa fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').' '.MyHelpers::admin_trans(auth()->id(), 'the task').'">
            <a href="'.route('all.show_q_task', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            if ($row->status == 0 || $row->status == 1) {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Edit').'">
            <a href="'.route('quality.manager.edittask', $row->id).'"><i class="fa fa-edit"></i></a></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->make(true);
    }

    public function completedtask()
    {

        //$this->delayedTask();

        $requests = DB::table('quality_reqs')->where('quality_reqs.user_id', auth()->id())->pluck('quality_reqs.id');

        $tasks = DB::table('tasks')->whereNotIn('tasks.status', [0, 1, 2])->whereIn('tasks.req_id', $requests)->count();

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('QualityManager.Task.completedtask', compact('tasks', 'classifcations_sa', 'classifcations_qu', 'all_status', 'task_status', 'worke_sources', 'request_sources'

        ));
    }

    public function completetask_datatable(Request $request)
    {

        /* $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');

        */

        $quality_req = DB::table('quality_reqs')->where('quality_reqs.user_id', auth()->id())->pluck('quality_reqs.id');

        $requests = DB::table('tasks')->join('task_contents', 'task_contents.task_id', 'tasks.id')->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')->join('requests', 'requests.id', 'quality_reqs.req_id')->join('users', 'users.id', 'tasks.recive_id')->join('customers', 'customers.id',
            'requests.customer_id')->whereNotIn('tasks.status', [0, 1, 2])->whereIn('tasks.req_id', $quality_req)->select('tasks.*', 'task_contents.content', 'task_contents.user_note', 'requests.comment', 'users.name as user_name', 'customers.mobile', 'customers.name', 'customers.salary',
            'requests.collaborator_id', 'requests.source', 'requests.type', 'requests.quacomment', 'quality_reqs.status as qustatus');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('quality_reqs.created_at', '<=', $request->get('req_date_to'));
        }

        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('quality_reqs.status', $request->get('req_status'));
        }

        if ($request->get('task_status') && is_array($request->get('task_status'))) {

            $requests = $requests->whereIn('tasks.status', $request->get('task_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        // dd($all);
        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').' '.MyHelpers::admin_trans(auth()->id(), 'The Request').'">
            <a href="'.route('quality.manager.fundingRequest', $row->req_id).'"><i class="fa fa-eye"></i></a></span>';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').' '.MyHelpers::admin_trans(auth()->id(), 'the task').'">
            <a href="'.route('all.show_q_task', $row->id).'"><i class="fa fa-comments"></i></a></span>';

            if ($row->status == 0 || $row->status == 1) {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Edit').'">
            <a href="'.route('quality.manager.edittask', $row->id).'"><i class="fa fa-edit"></i></a></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            return $data->content;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            return $data->user_note;
        })->make(true);
    }

    public function alltask($id)
    {

        //$this->delayedTask();

        /*$myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');
        */

        $requests = DB::table('quality_reqs')
            // ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->where('quality_reqs.user_id', auth()->id())->pluck('quality_reqs.id');

        $request = DB::table('quality_reqs')->where('id', $id)->first();

        $tasks = DB::table('tasks')->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')->join('requests', 'requests.id', 'quality_reqs.req_id')->whereIn('tasks.req_id', $requests)->where('tasks.req_id', $id)->select('tasks.*', 'requests.comment', 'requests.collaborator_id', 'requests.source',
            'requests.type', 'requests.quacomment', 'quality_reqs.status as qustatus')->count();

        //dd($tasks);

        $all_status = $this->statusQuality();
        $task_status = $this->statusTask();

        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('QualityManager.alltask', compact('id', 'tasks', 'request', 'all_status', 'classifcations_sa', 'classifcations_qu', 'task_status', 'request_sources', 'worke_sources'

        ));
    }

    public function task_datatable(Request $request)
    {
        /* $myUsers = DB::table('agent_qualities')
            ->where('Quality_id', auth()->id())
            ->pluck('Agent_id');
            */

        $requests = DB::table('tasks')->join('quality_reqs', 'quality_reqs.id', 'tasks.req_id')
            ->join('requests', 'requests.id', 'quality_reqs.req_id')
            ->join('users', 'users.id', 'tasks.recive_id')
            ->join('customers', 'customers.id', 'requests.customer_id')
            ->where('tasks.req_id', $request->get('id'))
            ->select('tasks.*', 'requests.comment', 'users.name as user_name', 'customers.mobile', 'customers.name', 'customers.salary', 'requests.collaborator_id', 'requests.source', 'requests.type', 'requests.quacomment', 'quality_reqs.status as qustatus');

        if ($request->get('req_date_from')) {
            $requests = $requests->whereDate('tasks.created_at', '>=', $request->get('req_date_from'));
        }

        if ($request->get('req_date_to')) {
            $requests = $requests->whereDate('tasks.created_at', '<=', $request->get('req_date_to'));
        }

        if ($request->get('notes_status_agent')) {
            if ($request->get('notes_status_agent') == 1) // choose contain only
            {
                $requests = $requests->where('requests.comment', '!=', null);
            }

            if ($request->get('notes_status_agent') == 2) // choose empty only
            {
                $requests = $requests->where('requests.comment', null);
            }
        }

        if ($request->get('notes_status_quality')) {
            if ($request->get('notes_status_quality') == 1) // choose contain only
            {
                $requests = $requests->where('requests.quacomment', '!=', null);
            }

            if ($request->get('notes_status_quality') == 2) // choose empty only
            {
                $requests = $requests->where('requests.quacomment', null);
            }
        }

        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
        }

        if ($request->get('req_status') && is_array($request->get('req_status'))) {

            $requests = $requests->whereIn('qustatus', $request->get('req_status'));
        }

        if ($request->get('task_status') && is_array($request->get('task_status'))) {

            $requests = $requests->whereIn('tasks.status', $request->get('task_status'));
        }

        if ($request->get('customer_salary')) {
            $requests = $requests->where('customers.salary', $request->get('customer_salary'));
        }

        if ($request->get('source') && is_array($request->get('source'))) {
            $requests = $requests->whereIn('requests.source', $request->get('source'));
        }

        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $requests = $requests->whereIn('requests.collaborator_id', $request->get('collaborator'));
        }

        if ($request->get('customer_phone')) {
            $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
        }

        if ($request->get('customer_birth')) {
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }

        $xses = [
            'sa' => 'class_id_agent',
            'qu' => 'class_id_quality',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }/*
        if ($request->has('search')) {
            if (array_key_exists('value', $request->search)) {
                if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9) {
                    $mobile = DB::table('customers')->where('mobile', $request->search['value']);
                    if ($mobile->count() == 0) {
                        $mobiles = CustomersPhone::where('mobile', $request->search['value'])->first();
                        if ($mobiles != null) {
                            $requests = $requests->where('customer_id', $mobiles->customer_id);
                        }
                    }
                    else {
                        $requests = $requests->where('customers.mobile', $request->search['value']);
                    }
                }
                $search = $request->search;
                $search['value'] = null;
                $request->merge([
                    'search' => $search,
                ]);
            }

        }*/
        // dd($all);
        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
            <a href="'.route('all.show_q_task', $row->id).'"><i class="fa fa-eye"></i></a></span>';

            if ($row->status == 0 || $row->status == 1) {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Edit').'">
            <a href="'.route('quality.manager.edittask', $row->id).'"><i class="fa fa-edit"></i></a></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            return $data->content;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')->where('task_contents.task_id', $row->id)->get()->last();

            return $data->user_note;
        })->make(true);
    }

    public function task(Request $request, $id)
    {

        $reqInfo = DB::table('quality_reqs')->where('id', $id)->first();
        //dd($reqInfo);
        $requestRoot = DB::table('requests')->where('id', $reqInfo->req_id)->first();

        $taskID = DB::table('tasks')->where('req_id', $id)->max('id');
        $taskInfo = DB::table('tasks')->where('id', $taskID)->first();

        if (!empty($taskInfo)) {
            if (($reqInfo->status == 0 || $reqInfo->status == 1 || $reqInfo->status == 2 || $reqInfo->status == 5) && ($taskInfo->status == 3 || $taskInfo->status == 4 || $taskInfo->status == 5)) {
                return view('QualityManager.task', compact('id', 'requestRoot'));
            }
        }
        if (empty($taskInfo)) {
            if (($reqInfo->status == 0 || $reqInfo->status == 1 || $reqInfo->status == 2 || $reqInfo->status == 5)) {
                return view('QualityManager.task', compact('id', 'requestRoot'));
            }
        }

        return redirect()->route('quality.manager.alltask', $id)->with('message2', MyHelpers::admin_trans(auth()->id(), 'you have task under process'));
    }

    public function show_q_task(Request $request, $id)
    {

        $tasks = DB::table('tasks')->where('tasks.id', $id)->join('users as recived', 'recived.id', 'tasks.recive_id')->join('users as sent', 'sent.id', 'tasks.user_id')->select('recived.name as recname', 'recived.role as recrole', 'recived.id as recid', 'sent.id as sentid', 'sent.name as sentname',
            'sent.role as sentrole', 'tasks.id', 'tasks.req_id', 'tasks.status')->first();

        //dd($tasks);

        $task_contents = DB::table('task_contents')->where('task_contents.task_id', $id)->orderBy('created_at', 'asc')->get();

        $task_content_last = DB::table('task_contents')->where('task_contents.task_id', $id)->get()->last();

        $reqInfo = DB::table('quality_reqs')->where('id', $tasks->req_id)->first();

        //dd($reqInfo);

        return view('QualityManager.showtask', compact('id', 'tasks', 'task_contents', 'task_content_last', 'reqInfo'));
    }

    public function task_post(Request $request)
    {

        $rules = [
            'content' => 'required',
        ];

        $customMessages = [
            'content.required' => MyHelpers::admin_trans(auth()->id(), 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $reqInfo = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->where([
            'quality_reqs.id' => $request->id,
        ])->select('requests.*')->first();

        $newTask = task::create([
            'req_id'    => $request->id,
            'recive_id' => $reqInfo->user_id,
            'user_id'   => auth()->id(),
        ]);

        $newContent = task_content::create([
            'content'         => $request->get('content'),
            'date_of_content' => Carbon::now('Asia/Riyadh'),
            'task_id'         => $newTask->id,
        ]);

        #add to need to action requests
        $agentInfo = MyHelpers::getAgentInfo($reqInfo->user_id);
        if ($agentInfo->status == 0) {
            MyHelpers::addNeedActionReqWithoutConditions('مهمة جديدة', $reqInfo->user_id, $reqInfo->id);
        }

        if (!$newContent) {
            return back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'com'));
        }

        else {
            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $reqInfo->user_id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_task', $reqInfo->id);

            if (!MyHelpers::checkIfThereDailyPrefromenceRecord(auth()->id())) {
                MyHelpers::addDailyPerformanceRecord(auth()->id());
            }
            MyHelpers::incrementDailyPerformanceColumn(auth()->id(), 'received_task', $reqInfo->id);

            //***********END - UPDATE DAILY PREFROMENCE */
            return redirect()->route('quality.manager.alltask', $request->id)->with('message', MyHelpers::admin_trans(auth()->id(), 'Add Succesffuly'));
        }
    }

    public function edittask(Request $request, $id)
    {
        //dd($id);
        $tasks = DB::table('tasks')->where('id', $id)->whereIn('status', [0, 1])->first();

        $task_content_last = DB::table('task_contents')->where('task_contents.task_id', $id)->get()->last();

        // dd($task);
        if ($tasks) {
            return view('QualityManager.edittask', compact('id', 'tasks', 'task_content_last'));
        }
        else {
            return redirect()->route('quality.manager.mytask')->with('message2', MyHelpers::admin_trans(auth()->id(), 'you do not have a premation to do that'));
        }
    }

    public function edit_task_post(Request $request)
    {
        $rules = [
            'content' => 'required',
            // 'class' => 'required',

        ];

        $customMessages = [
            'content.required' => MyHelpers::admin_trans(auth()->id(), 'The filed is required'),
            // 'class.required' => MyHelpers::admin_trans(auth()->id(), 'The filed is required'),

        ];

        $this->validate($request, $rules, $customMessages);

        $updateContent = task_content::whereId($request->id)->update([
            'content'         => $request->get('content'),
            'date_of_content' => Carbon::now('Asia/Riyadh'),
        ]);

        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Succesffuly'));
    }

    public function completeTask($id)
    {

        $tasks = DB::table('tasks')->where('id', $id)->first();
        $reqInfo = DB::table('quality_reqs')->where('id', $tasks->req_id)->first();

        //dd($reqInfo);

        if (($reqInfo->status == 0 || $reqInfo->status == 1 || $reqInfo->status == 2 || $reqInfo->status == 3 || $reqInfo->status == 5)) {

            $updateTask = DB::table('tasks')->where('id', $id)->where('status', 2)->update([
                'status' => 3,
            ]);

            if ($updateTask == 0) {
                return redirect()->route('all.show_q_task', $id)->with('message', MyHelpers::admin_trans(auth()->id(), 'Try Again'));
            }

            else {
                $updateRequest = DB::table('quality_reqs')->where('id', $reqInfo->id) // request ll be complete too if the task is complete
                ->update([
                    'status' => 3,
                ]);

                return redirect()->route('all.show_q_task', $id)->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Succesffuly'));
            }
        }
        else {
            return redirect()->route('all.show_q_task', $id)->with('message2', MyHelpers::admin_trans(auth()->id(), 'you do not have a premation to do that'));
        }
    }

    public function notcompleteTask($id)
    {

        $tasks = DB::table('tasks')->where('id', $id)->first();
        $reqInfo = DB::table('quality_reqs')->where('id', $tasks->req_id)->first();

        if (($reqInfo->status == 0 || $reqInfo->status == 1 || $reqInfo->status == 2 || $reqInfo->status == 4 || $reqInfo->status == 5)) {
            $updateTask = DB::table('tasks')->where('id', $id)->where('status', 2)->update([
                'status' => 4,
            ]);

            if ($updateTask == 0) {
                return redirect()->route('all.show_q_task', $id)->with('message', MyHelpers::admin_trans(auth()->id(), 'Try Again'));
            }

            else {
                $updateRequest = DB::table('quality_reqs')->where('id', $reqInfo->id) // request ll be complete too if the task is complete
                ->update([
                    'status' => 4,
                ]);

                return redirect()->route('all.show_q_task', $id)->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Succesffuly'));
            }
        }
        else {
            return redirect()->route('all.show_q_task', $id)->with('message2', MyHelpers::admin_trans(auth()->id(), 'you do not have a premation to do that'));
        }
    }

    public function canceleTask($id)
    {

        $tasks = DB::table('tasks')->where('id', $id)->first();
        $reqInfo = DB::table('quality_reqs')->where('id', $tasks->req_id)->first();

        //if (($reqInfo->status == 0 || $reqInfo->status == 1 || $reqInfo->status == 2 || $reqInfo->status == 5)) {

        $updateTask = DB::table('tasks')->where('id', $id)->whereIn('status', [0, 1])->update([
            'status' => 5,
        ]);

        if ($updateTask == 0) {
            return redirect()->route('all.show_q_task', $id)->with('message', MyHelpers::admin_trans(auth()->id(), 'Try Again'));
        }

        else {
            return redirect()->route('all.show_q_task', $id)->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Succesffuly'));
        }
        // } else
        //     return redirect()->route('all.show_q_task', $id)->with('message2', MyHelpers::admin_trans(auth()->id(), 'you do not have a premation to do that'));
    }

    public function completeReq(Request $request)
    {

        $request1 = DB::table('quality_reqs')->join('requests', 'requests.id', '=', 'quality_reqs.req_id')->where('quality_reqs.id', '=', $request->id)->where('quality_reqs.user_id', auth()->id())->select('requests.*', 'quality_reqs.status', 'quality_reqs.id as quID')->first();

        if ($request1) { // check if it's correct funding or not
            $updateRequest = DB::table('quality_reqs')->where('id', $request->id)->whereIn('status', [0, 1, 2, 5])->update(['status' => 3, 'is_followed' => 0]);

            if ($updateRequest == 0) //nothing send
            {
                return response(['message' => MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'), 'status' => $updateRequest, 'id' => $request->id]);
            }
            else {
                $updateTask = DB::table('tasks')->where('req_id', $request->id)->whereIn('status', [0, 1, 2])->update([
                    'status' => 3,
                ]);
                MyHelpers::incrementDailyPerformanceColumn(auth()->id(), 'completed_request',$request->id);
                return response(['message' => MyHelpers::admin_trans(auth()->id(), 'Update successfully'), 'status' => $updateRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function notcompleteReq(Request $request)
    {

        $request1 = DB::table('quality_reqs')->join('requests', 'requests.id', '=', 'quality_reqs.req_id')->where('quality_reqs.id', '=', $request->id)->where('quality_reqs.user_id', auth()->id())->select('requests.*', 'quality_reqs.status', 'quality_reqs.id as quID')->first();

        //$checkQuality = MyHelpers::checkQualityMatchWithAgent($request1->user_id);

        if ($request1) { // check if it's correct funding or not

            $updateRequest = DB::table('quality_reqs')->where('id', $request->id)->whereIn('status', [0, 1, 2, 5])->update(['status' => 4, 'is_followed' => 0]);

            if ($updateRequest == 0) //nothing send

            {
                return response(['message' => MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'), 'status' => $updateRequest, 'id' => $request->id]);
            }

            else {

                $updateTask = DB::table('tasks')->where('req_id', $request->id)->whereIn('status', [0, 1, 2])->update([
                    'status' => 4,
                ]);
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord(auth()->id())) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord(auth()->id());
                }
                MyHelpers::incrementDailyPerformanceColumn(auth()->id(), 'sent_basket',$request->id);
                return response(['message' => MyHelpers::admin_trans(auth()->id(), 'Update successfully'), 'status' => $updateRequest, 'id' => $request->id]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'), 'status' => 0, 'id' => $request->id]);
        }
    }

    public function fundingreqpage($id)
    {
        $qualityRequest = $id;
        $request = DB::table('quality_reqs')->join('requests', 'requests.id', '=', 'quality_reqs.req_id')
            ->where('quality_reqs.id', '=', $id)
            ->when(auth()->user()->role != 9,function($q,$v) {
                $q->where('quality_reqs.user_id', auth()->id());
            })
            ->select('requests.*', 'quality_reqs.status', 'quality_reqs.is_followed as qualityFollowed',
                'quality_reqs.id as quID')->first();

        if ($request) { // check if it's correct funding or not
            $id = $request->id;
            $quID = $request->quID;
            $is_follow = $request->qualityFollowed;

            $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

            $reqStatus = $request->statusReq;
            $status = $request->status;

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

            $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();

            $purchaseClass2 = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_quality')->where('requests.id', '=', $id)->first();

            if ($request->type == 'رهن-شراء') {
                $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();
            }
            else {
                $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();
            }

            // dd(  $payment);

            $purchaseTsa = DB::table('requests')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.id', '=', $id)->first();

            $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $cities = DB::table('cities')->select('id', 'value')->get();
            $ranks = DB::table('military_ranks')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();

            $classifcations = DB::table('classifcations')->get();

            // dd($classifcations);

            /* $histories  = DB::table('req_records')->where('req_id', '=', $id)
                ->leftjoin('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                ->get();*/

            $documents = DB::table('documents')->where('req_id', '=', $id)->leftjoin('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')->get();

            #---------------- FOLLOW DATE OF CURRENT USER & AGENT--------------------------------
            $followdate = DB::table('notifications')->where('req_id', '=', $quID)->where('recived_id', '=', (auth()->id()))->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->orderBy('id', 'desc')->first(); //to get last reminder

            //dd($followdate);

            $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

            if (!empty($followdate)) {
                $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
            }

            $followdate_agent = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', $request->user_id)->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

            $followtime_agent = ($followdate_agent != null ? Carbon::parse($followdate_agent->reminder_date)->format('H:i') : null);

            if (!empty($followdate_agent)) {
                $followdate_agent->reminder_date = (Carbon::parse($followdate_agent->reminder_date)->format('Y-m-d'));
            }
            #----------------END FOLLOW DATE OF CURRENT USER & AGENT--------------------------------

            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
            MyHelpers::openReqWillOpenNotify($id);
            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

            $product_types = null;
            $getTypes = MyHelpers::getProductType();
            if ($getTypes != null) {
                $product_types = $getTypes;
            }

            //***dispaly funding bank */
            $show_funding_source = false;
            $show_funding_source = MyHelpers::canShowBankName(auth()->id());
            //***dispaly funding bank */

            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();
            $survey = DB::table('servays')->where('req_id',(int)$qualityRequest)->first();
            $servQuestion = null;
            if($survey)
            {
                $servQuestion = DB::table('serv_ques')->where('serv_id',$survey->id)->where('ques_id',4)->first();
            }
            $agent_id = auth()->user()->id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'opened_request',$id);
            return view('QualityManager.fundingReq.fundingreqpage', compact('purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseClass', 'purchaseTsa', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'id', //Request ID
                'quID', //quality req id
                // 'histories',
                'documents', 'reqStatus', 'payment', 'followdate', 'followdate_agent', 'collaborator', 'status', 'ranks', 'cities', 'followtime', 'followtime_agent', 'purchaseClass2', 'is_follow', 'realTypes', 'product_types', 'show_funding_source', 'worke_sources', 'request_sources',
                'servQuestion'

            ));
        }
        else {

            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
        }
    }

    public function morPurpage($id)
    {

        $qualityRequest = $id;
        $request = DB::table('quality_reqs')->join('requests', 'requests.id', '=', 'quality_reqs.req_id')->where('quality_reqs.id', '=', $id)->where('quality_reqs.user_id', auth()->id())->select('requests.*', 'quality_reqs.status', 'quality_reqs.is_followed as qualityFollowed',
            'quality_reqs.id as quID')->first();

        // $checkQuality = MyHelpers::checkQualityMatchWithAgent($request->user_id);

        if (!empty($request)) { // check if it's correct funding or not

            $id = $request->id;
            $quID = $request->quID;
            $is_follow = $request->qualityFollowed;

            $purchaseCustomer = DB::table('requests')->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')->where('requests.id', '=', $id)->first();

            $reqStatus = $request->statusReq;
            $status = $request->status;

            $purchaseJoint = DB::table('requests')->leftjoin('joints', 'joints.id', '=', 'requests.joint_id')->where('requests.id', '=', $id)->first();

            $purchaseReal = DB::table('requests')->leftjoin('real_estats', 'real_estats.id', '=', 'requests.real_id')->where('requests.id', '=', $id)->first();

            $purchaseFun = DB::table('requests')->leftjoin('fundings', 'fundings.id', '=', 'requests.fun_id')->where('requests.id', '=', $id)->first();

            $purchaseClass = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->where('requests.id', '=', $id)->first();

            $purchaseClass2 = DB::table('requests')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_quality')->where('requests.id', '=', $id)->first();

            if ($request->type == 'رهن-شراء') {
                $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();
            }
            else {
                $payment = DB::table('prepayments')->where('req_id', '=', $id)->first();
            }

            // dd(  $payment);

            $purchaseTsa = DB::table('requests')->leftjoin('prepayments', 'prepayments.id', '=', 'requests.payment_id')->where('requests.id', '=', $id)->first();

            $collaborator = DB::table('requests')->join('users', 'users.id', '=', 'requests.collaborator_id')->where('requests.id', '=', $id)->first();

            $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
            $funding_sources = DB::table('funding_sources')->select('id', 'value')->get();
            $askary_works = DB::table('askary_works')->select('id', 'value')->get();
            $madany_works = DB::table('madany_works')->select('id', 'value')->get();
            $cities = DB::table('cities')->select('id', 'value')->get();
            $ranks = DB::table('military_ranks')->select('id', 'value')->get();
            $realTypes = DB::table('real_types')->select('id', 'value')->get();

            $classifcations = DB::table('classifcations')->get();

            // dd($classifcations);

            /* $histories  = DB::table('req_records')->where('req_id', '=', $id)
                ->leftjoin('users', 'users.id', '=', 'req_records.user_id') // to retrive user information
                ->get();*/

            $documents = DB::table('documents')->where('req_id', '=', $id)->leftjoin('users', 'users.id', '=', 'documents.user_id') // to retrive user information
            ->select('documents.*', 'users.name')->get();

            #---------------- FOLLOW DATE OF CURRENT USER & AGENT--------------------------------
            $followdate = DB::table('notifications')->where('req_id', '=', $quID)->where('recived_id', '=', (auth()->id()))->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->orderBy('id', 'desc')->first(); //to get last reminder

            //dd($followdate);

            $followtime = ($followdate != null ? Carbon::parse($followdate->reminder_date)->format('H:i') : null);

            if (!empty($followdate)) {
                $followdate->reminder_date = (Carbon::parse($followdate->reminder_date)->format('Y-m-d'));
            }

            $followdate_agent = DB::table('notifications')->where('req_id', '=', $id)->where('recived_id', '=', $request->user_id)->where('type', '=', 1)
                //  ->select(DB::row('DATE_FORMAT(reminder_date,"%Y-%m-%dT%H:%i") as cust_date'))
                ->get()->last(); //to get last reminder

            $followtime_agent = ($followdate_agent != null ? Carbon::parse($followdate_agent->reminder_date)->format('H:i') : null);

            if (!empty($followdate_agent)) {
                $followdate_agent->reminder_date = (Carbon::parse($followdate_agent->reminder_date)->format('Y-m-d'));
            }
            #----------------END FOLLOW DATE OF CURRENT USER & AGENT--------------------------------


            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */
            MyHelpers::openReqWillOpenNotify($id);
            //*******************UPDATE NEW NOTIFICATIONS THAT RELATED TO THIS REQUES */

            $product_types = null;
            $getTypes = MyHelpers::getProductType();
            if ($getTypes != null) {
                $product_types = $getTypes;
            }

            //***dispaly funding bank */
            $show_funding_source = false;
            $show_funding_source = MyHelpers::canShowBankName(auth()->id());
            //***dispaly funding bank */

            $worke_sources = WorkSource::all();
            $request_sources = DB::table('request_source')->get();
            $survey = DB::table('servays')->where('req_id',(int)$qualityRequest)->first();
            $servQuestion = null;
            if($survey)
            {
                $servQuestion = DB::table('serv_ques')->where('serv_id',$survey->id)->where('ques_id',4)->first();
            }
            return view('QualityManager.morPurReq.fundingreqpage', compact('purchaseCustomer', 'purchaseJoint', 'purchaseReal', 'purchaseFun', 'purchaseClass', 'purchaseTsa', 'salary_sources', 'funding_sources', 'askary_works', 'madany_works', 'classifcations', 'id', //Request ID
                'quID', //quality req id
                // 'histories',
                'documents', 'reqStatus', 'payment', 'followdate','followdate_agent', 'collaborator', 'status', 'ranks', 'cities', 'followtime','followtime_agent', 'purchaseClass2', 'is_follow', 'realTypes', 'product_types', 'show_funding_source', 'worke_sources', 'request_sources'
                ,'servQuestion'
            ));
        }
        else {

            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
        }
    }

    public function updatefunding(Request $request)
    {
        $request1 = DB::table('quality_reqs')->join('requests', 'requests.id', '=', 'quality_reqs.req_id')->where('quality_reqs.id', '=', $request->quID)->where('quality_reqs.user_id', auth()->id())->select('requests.*', 'quality_reqs.status', 'quality_reqs.id as quID')->first();
        if ($request1) {
            $fundingReq = DB::table('quality_reqs')->where('id', $request->quID)->whereIn('status', [0, 1, 2, 5])->first();
            $orginialReq = DB::table('requests')->where('id', $request1->id)->first();
            if (!empty($fundingReq)) {
                //JOINT
                $jointId = $orginialReq->joint_id;
                //CUSTOMER
                $customerId = $orginialReq->customer_id;
                $customerInfo = DB::table('customers')->where('id', '=', $customerId)->first();
                //FUNDING INFO
                $fundingId = $orginialReq->fun_id;
                //REAL ESTAT
                $realId = $orginialReq->real_id;
                $reqID = $request1->id;
                if ($request->name == null) {
                    $request->name = 'بدون اسم';
                }
                $this->records($reqID, 'customerName', $request->name);
                //$this->records($reqID, 'mobile', $request->mobile);
                $this->records($reqID, 'sex', $request->sex);
                $this->records($reqID, 'birth_date', $request->birth);
                $this->records($reqID, 'birth_hijri', $request->birth_hijri);
                $this->records($reqID, 'hiring_date', $request->hiring_date);
                $this->records($reqID, 'salary', $request->salary);
                $this->records($reqID, 'age_years', $request->age_years);
                $this->records($reqID, 'add_support_installment_to_salary', $request->add_support_installment_to_salary == 0 ? 'لا' : 'نعم');
                $this->records($reqID, 'without_transfer_salary', $request->without_transfer_salary == 0 ? 'لا' : 'نعم');
                $this->records($reqID, 'guarantees', $request->guarantees == 0 ? 'لا' : 'نعم');
                $this->records($reqID, 'basic_salary', $request->basic_salary);
                if ($request->is_support != null) {
                    if ($request->is_support == 'no') {
                        $this->records($reqID, 'support', 'لا');
                    }
                    if ($request->is_support == 'yes') {
                        $this->records($reqID, 'support', 'نعم');
                    }
                }
                if ($request->has_obligations != null) {
                    if ($request->has_obligations == 'no') {
                        $this->records($reqID, 'obligations', 'لا');
                    }
                    if ($request->has_obligations == 'yes') {
                        $this->records($reqID, 'obligations', 'نعم');
                    }
                }
                if ($request->has_financial_distress != null) {
                    if ($request->has_financial_distress == 'no') {
                        $this->records($reqID, 'distress', 'لا');
                    }
                    if ($request->has_financial_distress == 'yes') {
                        $this->records($reqID, 'distress', 'نعم');
                    }
                }
                $this->records($reqID, 'obligations_value', $request->obligations_value);
                $this->records($reqID, 'financial_distress_value', $request->financial_distress_value);
                $this->records($reqID, 'jobTitle', $request->job_title);
                $getsalaryValue = DB::table('salary_sources')->where('id', $request->salary_source)->first();
                if (!empty($getsalaryValue)) {
                    $this->records($reqID, 'salary_source', $getsalaryValue->value);
                }
                $getaskaryValue = DB::table('askary_works')->where('id', $request->askary_work)->first();
                if (!empty($getaskaryValue)) {
                    $this->records($reqID, 'askaryWork', $getaskaryValue->value);
                }
                $getmadanyValue = DB::table('madany_works')->where('id', $request->madany_work)->first();
                if (!empty($getmadanyValue)) {
                    $this->records($reqID, 'madanyWork', $getmadanyValue->value);
                }
                $getrankValue = DB::table('military_ranks')->where('id', $request->rank)->first();
                if (!empty($getrankValue)) {
                    $this->records($reqID, 'rank', $getrankValue->value);
                }
                $getworkValue = DB::table('work_sources')->where('id', $request->work)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'work', $getworkValue->value);
                }
                if (($request->jointname != null) || ($request->jointmobile != null)) {
                    $has_joint = 'yes';
                }
                else {
                    $has_joint = 'no';
                }
                $updateResult = DB::table('customers')->where([
                    ['id', '=', $customerId],
                ])->update([
                    'name'                              => $request->name,
                    //'mobile' => $request->mobile,
                    'sex'                               => $request->sex,
                    'birth_date'                        => $request->birth,
                    'birth_date_higri'                  => $request->birth_hijri,
                    'age'                               => $request->age,
                    'work'                              => $request->work,
                    'madany_id'                         => $request->madany_work,
                    'job_title'                         => $request->job_title,
                    'askary_id'                         => $request->askary_work,
                    'military_rank'                     => $request->rank,
                    'salary_id'                         => $request->salary_source,
                    'salary'                            => $request->salary,
                    'is_supported'                      => $request->is_support,
                    'has_obligations'                   => $request->has_obligations,
                    'obligations_value'                 => $request->obligations_value,
                    'has_financial_distress'            => $request->has_financial_distress,
                    'financial_distress_value'          => $request->financial_distress_value,
                    'has_joint'                         => $has_joint,
                    'age_years'                         => $request->age_years,
                    'without_transfer_salary'           => $request->without_transfer_salary,
                    'add_support_installment_to_salary' => $request->add_support_installment_to_salary,
                    'basic_salary'                      => $request->basic_salary,
                    'guarantees'                        => $request->guarantees,
                    'hiring_date'                       => $request->hiring_date,
                ]);
                $name = $request->jointname;
                $mobile = $request->jointmobile;
                $birth = $request->jointbirth;
                $birth_higri = $request->jointbirth_hijri;
                $age = $request->jointage;
                $joint_hiring_date = $request->joint_hiring_date;
                $work = $request->jointwork;
                $salary = $request->jointsalary;
                $salary_source = $request->jointsalary_source;
                $rank = $request->jointrank;
                $madany = $request->jointmadany_work;
                $job_title = $request->jointjob_title;
                $askary_work = $request->jointaskary_work;
                $jointfunding_source = $request->jointfunding_source;
                $jointis_support = $request->joint_is_support;
                $joint_add_support = $request->joint_add_support_installment_to_salary;
                $joint_obligation = $request->joint_has_obligations;
                $joint_obligationvalue = $request->jointobligations_value;
                $this->records($reqID, 'jointName', $request->jointname);
                $this->records($reqID, 'jointMobile', $request->jointmobile);
                $this->records($reqID, 'jointSalary', $request->jointsalary);
                $this->records($reqID, 'jointBirth', $request->jointbirth);
                $this->records($reqID, 'jointBirth_higri', $request->jointbirth_hijri);
                $this->records($reqID, 'joint_hiring_date', $joint_hiring_date);
                $this->records($reqID, 'jointJobTitle', $job_title);
                $this->records($reqID, 'jointobligations_value', $joint_obligationvalue);
                $this->records($reqID, 'joint_add_support_installment_to_salary', $request->joint_add_support_installment_to_salary == 0 ? 'لا' : 'نعم');
                $this->records($reqID, 'jointSupport', $jointis_support == 'yes' ? 'نعم' : 'لا');
                $this->records($reqID, 'jointObligations', $joint_obligation == 'yes' ? 'نعم' : 'لا');
                $getjointfundingValue = DB::table('funding_sources')->where('id', $request->jointfunding_source)->first();
                if (!empty($getjointfundingValue)) {
                    $this->records($reqID, 'jointfunding_source', $getjointfundingValue->value);
                }
                $getjointsalaryValue = DB::table('salary_sources')->where('id', $request->jointsalary_source)->first();
                if (!empty($getjointsalaryValue)) {
                    $this->records($reqID, 'jointsalary_source', $getjointsalaryValue->value);
                }
                $getjointrankValue = DB::table('military_ranks')->where('id', $request->jointrank)->first();
                if (!empty($getjointrankValue)) {
                    $this->records($reqID, 'jointRank', $getjointrankValue->value);
                }
                $getworkValue = DB::table('work_sources')->where('id', $request->jointwork)->first();
                if (!empty($getworkValue)) {
                    $this->records($reqID, 'jointWork', $getworkValue->value);
                }
                $getjointaskaryValue = DB::table('askary_works')->where('id', $request->jointaskary_work)->first();
                if (!empty($getjointaskaryValue)) {
                    $this->records($reqID, 'jointaskaryWork', $getjointaskaryValue->value);
                }
                $getjointmadanyValue = DB::table('madany_works')->where('id', $request->jointmadany_work)->first();
                if (!empty($getjointmadanyValue)) {
                    $this->records($reqID, 'jointmadanyWork', $getjointmadanyValue->value);
                }
                DB::table('joints')->where('id', $jointId)->update([
                    'name'                              => $name,
                    'mobile'                            => $mobile,
                    'salary'                            => $salary,
                    'birth_date'                        => $birth,
                    'birth_date_higri'                  => $birth_higri,
                    'age'                               => $age,
                    'work'                              => $work,
                    'salary_id'                         => $salary_source,
                    'military_rank'                     => $rank,
                    'madany_id'                         => $madany,
                    'job_title'                         => $job_title,
                    'funding_id'                        => $jointfunding_source,
                    'askary_id'                         => $askary_work,
                    'is_supported'                      => $jointis_support,
                    'add_support_installment_to_salary' => $joint_add_support,
                    'has_obligations'                   => $joint_obligation,
                    'obligations_value'                 => $joint_obligationvalue,
                    'hiring_date'                       => $joint_hiring_date,
                ]);
                $realname = $request->realname;
                $realmobile = $request->realmobile;
                $realcity = $request->realcity;
                $region = $request->realregion;
                $realpursuit = $request->realpursuit;
                $realstatus = $request->realstatus;
                $realage = $request->realage;
                $realcost = $request->realcost;
                $realhas = $request->realhasprop;
                $realtype = $request->realtype;
                $othervalue = $request->othervalue;
                $realeva = $request->realeva;
                $realten = $request->realten;
                $realmor = $request->realmor;
                $owning_property = $request->owning_property;
                $this->records($reqID, 'realName', $request->realname);
                $this->records($reqID, 'realMobile', $request->realmobile);
                $getcityValue = DB::table('cities')->where('id', $request->realcity)->first();
                if (!empty($getcityValue)) {
                    $this->records($reqID, 'realCity', $getcityValue->value);
                }
                $this->records($reqID, 'realRegion', $request->realregion);
                $this->records($reqID, 'realPursuit', $request->realpursuit);
                $this->records($reqID, 'realAge', $request->realage);
                $this->records($reqID, 'realStatus', $request->realstatus);
                $this->records($reqID, 'realCost', $request->realcost);
                if ($request->owning_property == 'no') {
                    $this->records($reqID, 'owning_property', 'لا');
                }
                if ($request->owning_property == 'yes') {
                    $this->records($reqID, 'owning_property', 'نعم');
                }
                $gettypeValue = DB::table('real_types')->where('id', $request->realtype)->first();
                if (!empty($gettypeValue)) {
                    $this->records($reqID, 'realType', $gettypeValue->value);
                }
                $this->records($reqID, 'residence_type', $request->residence_type);
                DB::table('real_estats')->where('id', $realId)->update([
                    'name'            => $realname,
                    'mobile'          => $realmobile,
                    'city'            => $realcity,
                    'region'          => $region,
                    'pursuit'         => $realpursuit,
                    'age'             => $realage,
                    'status'          => $realstatus,
                    'cost'            => $realcost,
                    'type'            => $realtype,
                    'other_value'     => $othervalue,
                    'evaluated'       => $realeva,
                    'tenant'          => $realten,
                    'mortgage'        => $realmor,
                    'has_property'    => $realhas,
                    'owning_property' => $owning_property,
                    'residence_type'  => $request->residence_type,
                ]);
                $funding_source = $request->funding_source;
                $fundingdur = $request->fundingdur;
                $fundingpersonal = $request->fundingpersonal;
                $fundingpersonalp = $request->fundingpersonalp;
                $fundingreal = $request->fundingreal;
                $fundingrealp = $request->fundingrealp;
                $dedp = $request->dedp;
                $monthIn = $request->monthIn;

                $flexFund = $request->flexiableFun_cost;
                $extenFund = $request->extendFund_cost;
                $personalDed = $request->personal_salary_deduction;
                $personalMonthIn = $request->personal_monthly_installment;
                $product_code = $request->product_code;
                $monthInAfterSupport = $request->monthly_installment_after_support;

                $this->records($reqID, 'fundDur', $fundingdur);
                $this->records($reqID, 'fundPers', $fundingpersonal);
                $this->records($reqID, 'fundPersPre', $fundingpersonalp);
                $this->records($reqID, 'fundReal', $fundingreal);
                $this->records($reqID, 'fundRealPre', $fundingrealp);
                $this->records($reqID, 'fundDed', $dedp);
                $this->records($reqID, 'fundMonth', $monthIn);
                $this->records($reqID, 'fundFlex', $flexFund);
                $this->records($reqID, 'fundExten', $extenFund);
                $this->records($reqID, 'personal_salary_deduction', $personalDed);
                $this->records($reqID, 'installment_after_support', $monthInAfterSupport);
                $getfundingValue = DB::table('funding_sources')->where('id', $request->funding_source)->first();
                if (!empty($getfundingValue)) {
                    $this->records($reqID, 'funding_source', $getfundingValue->value);
                }

                if ($product_code != null) {
                    $matchCode = MyHelpers::getSpasficProductType($product_code);
                    if ($matchCode != null) {
                        $this->records($reqID, 'product_type', $matchCode['name_ar']);
                    }
                }
                DB::table('fundings')->where('id', $fundingId)->update([
                    'funding_source'                    => $funding_source,
                    'funding_duration'                  => $fundingdur,
                    'personalFun_cost'                  => $fundingpersonal,
                    'personalFun_pre'                   => $fundingpersonalp,
                    'realFun_cost'                      => $fundingreal,
                    'realFun_pre'                       => $fundingrealp,
                    'ded_pre'                           => $dedp,
                    'monthly_in'                        => $monthIn,
                    'flexiableFun_cost'                 => $flexFund,
                    'personal_salary_deduction'         => $personalDed,
                    'personal_monthly_installment'      => $personalMonthIn,
                    'monthly_installment_after_support' => $monthInAfterSupport,
                    'extendFund_cost'                   => $extenFund,
                    'product_code'                      => $product_code,

                ]);
                $followdate = DB::table('notifications')->where('req_id', '=', $request->quID)->where('recived_id', '=', (auth()->id()))->where('type', '=', 1)->orderBy('id', 'desc')->first();
                if ($request->follow == null && (!empty($followdate))) {
                    $fn = DB::table('notifications')->where('id', '=', $followdate->id)->delete();
                    //dd($fn);
                }
                if ($request->follow != null) {
                    $date = $request->follow;
                    $time = $request->follow1;
                    if ($time == null) {
                        $time = "00:00";
                    }
                    $newValue = $date."T".$time;
                    $checkFollow = DB::table('notifications')->where('req_id', '=', $request->quID)->where('recived_id', '=', (auth()->id()))->where('type', '=', 1)->where('status', '=', 2)->orderBy('id', 'desc')->first();

                    if (empty($checkFollow)) { // not dublicate
                        DB::table('notifications')->insert([ // add following notification
                                                             'value'         => MyHelpers::admin_trans(auth()->id(), 'The request need following'),
                                                             'recived_id'    => (auth()->id()),
                                                             'status'        => 2,
                                                             'type'          => 1,
                                                             'reminder_date' => $newValue,
                                                             'req_id'        => $request->quID,
                                                             'created_at'    => Carbon::now('Asia/Riyadh'),
                        ]);
                    }
                    else {
                        $overWriteReminder = DB::table('notifications')->where('id', $checkFollow->id)->update(['reminder_date' => $newValue, 'created_at' => (Carbon::now('Asia/Riyadh'))]); //set new notifiy
                    }
                }
                $quacomm = $request->quacomm;
                $reqClass = $request->reqclass;
                $this->records($request1->id, 'comment', $quacomm);
                $getclassValue = DB::table('classifcations')->where('id', $reqClass)->first();
                if (!empty($getclassValue)) {
                    $this->records($request1->id, 'class_quality', $getclassValue->value);

                    # check if the class is negative :: 
                    if ($getclassValue->type == 0 && $fundingReq->status != 5){
                        # set request to archive in agent & quality
                        DB::table('quality_reqs')->where('id', $fundingReq->id)->update([
                            'status' => 5, 'is_followed' => 0
                        ]);
                        DB::table('requests')->where('id', $orginialReq->id)->update([
                            'statusReq' => 2, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'add_to_archive' => Carbon::now('Asia/Riyadh'), 'add_to_stared' => null, 'add_to_followed' => null, 'updated_at' =>  Carbon::now('Asia/Riyadh')
                        ]);

                        # add request history 
                        DB::table('request_histories')->insert([
                            'title'        => RequestHistory::TITLE_MOVE_REQUEST_TO_ARCHIVED_BASKET,
                            'user_id'      => auth()->id(),
                            'recive_id'    => null,
                            'history_date' => (Carbon::now('Asia/Riyadh')),
                            'req_id'       =>  $orginialReq->id,
                            'content'      => RequestHistory::NEGATIVE_CLASS_QUALITY,
                        ]);
                    }
                    else if ($getclassValue->type != 0 &&  $fundingReq->status == 5){
                        DB::table('quality_reqs')->where('id',  $fundingReq->id)->update([
                            'status' => 1, 'is_followed' => 0
                        ]);
                    }
                    #######################################

                }

                //
                DB::table('requests')->where('id', $request1->id)->update([
                    'quacomment'       => $quacomm,
                    'class_id_quality' => $reqClass,
                ]);
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord(auth()->id())) {
                    MyHelpers::addDailyPerformanceRecord(auth()->id());
                }
                MyHelpers::incrementDailyPerformanceColumn(auth()->id(), 'updated_request',$request1->id);
                //***********END - UPDATE DAILY PREFROMENCE */
                if($request->survey_id != null){
                    DB::table('serv_ques')->where('serv_id',$request->survey_id)->update([
                        'answer' => $request->agent_note_status
                    ]);
                }

                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Succesffuly'));
            }
            else {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
        }
    }

    public function records($reqID, $coloum, $value)
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
                'user_id'        => (auth()->id()),
                'value'          => $value,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'req_id'         => $reqID,
                'user_switch_id' => $userSwitch,
            ]);
        }

        if ($lastUpdate != null) {
            if (($rowOfLastUpdate->value) != $value) {

                DB::table('req_records')->insert([
                    'colum'          => $coloum,
                    'user_id'        => (auth()->id()),
                    'value'          => $value,
                    'updateValue_at' => Carbon::now('Asia/Riyadh'),
                    'req_id'         => $reqID,
                    'user_switch_id' => $userSwitch,
                ]);
            }
        }

        //  dd($rowOfLastUpdate);
    }

    //-----------------------Customer----------------------------------------

    public function uploadFile(Request $request)
    {

        $rules = [
            'name' => 'required',
            'file' => 'required|file|max:10240',
        ];

        $customMessages = [
            'file.max'      => MyHelpers::admin_trans(auth()->id(), 'Should not exceed 10 MB'),
            'name.required' => MyHelpers::admin_trans(auth()->id(), 'The filed is required'),
            'file.required' => MyHelpers::admin_trans(auth()->id(), 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $name = $request->name;
        $reqID = $request->id;
        $userID = auth()->id();
        $upload_date = Carbon::today('Asia/Riyadh')->format('Y-m-d');
        $file = $request->file('file');

        // generate a new filename. getClientOriginalExtension() for the file extension
        $filename = $name.time().'.'.$file->getClientOriginalExtension();

        // save to storage/app/photos as the new $filename
        $path = $file->storeAs('documents', $filename);

        $docID = DB::table('documents')->insertGetId([
            'filename'    => $name,
            'location'    => $path,
            'upload_date' => $upload_date,
            'req_id'      => $reqID,
            'user_id'     => $userID,
        ]);

        //$docRow = DB::table('documents')->where('id', $docID)->first();

        $documents = DB::table('documents')->where('req_id', '=', $reqID)->join('users', 'users.id', '=', 'documents.user_id') // to retrive user information
        ->select('documents.*', 'users.name')->get();

        return response()->json($documents);
    }

    //This new function to show dataTabel in view(Agent.Customer.mycustomers)

    public function allCustomer()
    {

        return view('QualityManager.Customer.allCustomers');
    }

    public function searchCustomer(Request $request)
    {

        $customer = DB::table('customers')->leftjoin('requests', 'requests.customer_id', '=', 'customers.id')->leftjoin('classifcations', 'classifcations.id', '=', 'requests.class_id_agent')->leftjoin('users', 'users.id', '=', 'requests.user_id')->where('customers.mobile',
            $request->mobile)->select('customers.name', 'customers.mobile', 'requests.*', 'classifcations.value', 'users.name as user_name')->first();

        if (!empty($customer)) {
            if ($customer->statusReq !== null) {
                $statusReq = $this->status()[$customer->statusReq];
                $customer->statusReq = $statusReq;
            }
        }

        if (!empty($customer)) {
            return response(['customer' => $customer]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->id(), 'Not Found'), 'status' => 0]);
    }

    public function addReqToQuality(Request $request)
    {

        $checkAddingStatus = false;

        if (MyHelpers::checkQualityReq($request->id)) {

            $newReq = quality_req::create([
                'req_id'     => $request->id,
                'created_at' => (Carbon::now('Asia/Riyadh')),
                'user_id'    => auth()->id(),
            ]);
            $checkAddingStatus = true;
        }
        else {
            $quality_req_id = MyHelpers::checkQualityReqWithArchivedUser($request->id);
            if ($quality_req_id != false) {

                //Update Current quality req in archived quality to complete before creating another one
                MyHelpers::updateQualityReqToComplete($quality_req_id);

                $newReq = quality_req::create([
                    'req_id'     => $request->id,
                    'created_at' => (Carbon::now('Asia/Riyadh')),
                    'user_id'    => auth()->id(),
                ]);

                $checkAddingStatus = true;
            }
        }

        if ($checkAddingStatus) {
            #------remove need action req if existed(will nt allowed to recived same request with admin & quality)
            $needReq = MyHelpers::checkDublicateOfNeedActionReqWithStatusOnly($request->id);
            if ($needReq != 'false') {
                MyHelpers::removeNeedActionReq($needReq->id);
            }
            #----------------------------

            DB::table('request_histories')->insert([
                'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                'user_id'      => null,
                'recive_id'    => auth()->id(),
                'history_date' => (Carbon::now('Asia/Riyadh')),
                'req_id'       => $request->id,
                'content'      => null,
            ]);

            DB::table('notifications')->insert([ // add notification to send user
                                                 'value'      => MyHelpers::admin_trans(auth()->id(), 'New Request Added'),
                                                 'recived_id' => $newReq->user_id,
                                                 'created_at' => (Carbon::now('Asia/Riyadh')),
                                                 'type'       => 0,
                                                 'req_id'     => $newReq->id,
            ]);

            if ($newReq) {
                return response(['message' => MyHelpers::admin_trans(auth()->id(), 'Add Succesffuly'), 'status' => 1]);
            }
            else {
                return response(['message' => MyHelpers::admin_trans(auth()->id(), 'Try Again'), 'status' => 0]);
            }
        }
        else {
            return response(['message' => MyHelpers::admin_trans(auth()->id(), 'There is request Under Processing'), 'status' => 0]);
        }
    }

    public function updateNewReq(Request $request)
    {
        $updateResult = DB::table('quality_reqs')->where([
            ['id', '=', $request->id],
            ['status', '=', 0],
        ])->update([
            'status' => 1, //open
        ]);

        return response($updateResult); // if 1: update succesfally

    }

    public function restoreReq($id)
    {
        $request = DB::table('quality_reqs')->join('requests', 'requests.id', '=', 'quality_reqs.req_id')->where('quality_reqs.id', '=', $id)->where('quality_reqs.user_id', auth()->id())->select('requests.*', 'quality_reqs.status', 'quality_reqs.is_followed as qualityFollowed',
            'quality_reqs.id as quID')->first();

        if (!empty($request)) {

            $updateTask = DB::table('quality_reqs')->where('quality_reqs.id', '=', $id)->whereIn('quality_reqs.status', [3, 4])->update(['status' => 1, 'is_followed' => 0]);

            if ($updateTask == 0) {
                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->id(), 'Try Again'));
            }
            else {
                return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->id(), 'Update Succesffuly'));
            }
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
        }
    }

    public function updateComm(Request $request)
    {

        if ($request->reqComm != null) {
            $this->records($request->id, 'comment', $request->reqComm);
        }

        $qualityInfo = DB::table('quality_reqs')->where('req_id', $request->id)->get()->last();
        $updateResult = DB::table('quality_reqs')->where([
            ['id', '=', $qualityInfo->id],
            ['status', '=', 0],
        ])->update([
            'status' => 1, //open
        ]);

        $request2 = DB::table('requests')->where('id', $request->id)->update(['quacomment' => $request->reqComm]);

        return response()->json(['status' => $request2, 'newComm' => $request->reqComm]);
    }

    public function updateClass(Request $request)
    {

        if ($request->reqClass != null) {
            $fn = $request->reqClass;
        }
        $qualityInfo = DB::table('quality_reqs')->where('req_id', $request->id)->get()->last();
        $getclassValue = DB::table('classifcations')->where('id', $fn)->first();
        if (!empty($getclassValue)) {
            $this->records($request->id, 'class_quality', $getclassValue->value);
            # check if the class is negative :: 
            if ($getclassValue->type == 0 && $qualityInfo->status != 5){
                    # set request to archive in agent & quality
                    DB::table('quality_reqs')->where('id', $qualityInfo->id)->update([
                        'status' => 5, 'is_followed' => 0
                    ]);
                    DB::table('requests')->where('id',$request->id)->update([
                        'statusReq' => 2, 'is_canceled' => 0, 'is_stared' => 0, 'is_followed' => 0, 'add_to_archive' => Carbon::now('Asia/Riyadh'), 'add_to_stared' => null, 'add_to_followed' => null, 'updated_at' =>  Carbon::now('Asia/Riyadh')
                    ]);
                    # add request history 
                    DB::table('request_histories')->insert([
                        'title'        => RequestHistory::TITLE_MOVE_REQUEST_TO_ARCHIVED_BASKET,
                        'user_id'      => auth()->id(),
                        'recive_id'    => null,
                        'history_date' => (Carbon::now('Asia/Riyadh')),
                        'req_id'       =>  $request->id,
                        'content'      => RequestHistory::NEGATIVE_CLASS_QUALITY,
                    ]);
            }
            else if ($getclassValue->type != 0 && $qualityInfo->status == 5){
                DB::table('quality_reqs')->where('id', $qualityInfo->id)->update([
                    'status' => 1, 'is_followed' => 0
                ]);
            }
        }
            


        $updateResult = DB::table('quality_reqs')->where([
            ['id', '=', $qualityInfo->id],
            ['status', '=', 0],
        ])->update([
            'status' => 1, //open
        ]);

        $request2 = DB::table('requests')->where('id', $request->id)->update(['class_id_quality' => $request->reqClass]);

        return response()->json(['status' => $request2]);
    }

    public function openFile(Request $request, $id)
    {

        $document = DB::table('documents')->where('id', '=', $id)->first();
        $userID = $document->user_id;

        $request = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->where('requests.id', '=', $document->req_id)->select('quality_reqs.user_id')->first();

        $checkQuality = $request->user_id == auth()->id();

        if ($checkQuality) {

            try {
                $filename = $document->location;
                return response()->file(storage_path('app/public/'.$filename));
            }
            catch (\Exception $e) {
                return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');

            }
        } // open without dowunload

        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
    }

    public function downloadFile(Request $request, $id)
    {
        $document = DB::table('documents')->where('id', '=', $id)->first();
        $reqID = $document->req_id;

        $request = DB::table('quality_reqs')->join('requests', 'requests.id', 'quality_reqs.req_id')->where('requests.id', '=', $document->req_id)->select('quality_reqs.user_id')->first();

        $checkQuality = $request->user_id == auth()->id();

        if ($checkQuality) {

            try {
                $filename = $document->location;
                return response()->download(storage_path('app/public/'.$filename));
            }
            catch (\Exception $e) {
                return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');

            }
        }  // dowunload

        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->id(), 'You do not have a premation to do that'));
    }

    public function qualityReqsNotRecived()
    {

        $active_quality = MyHelpers::activeQualities();
        $i = 0;

        if (MyHelpers::checkActiveQualityReqs()) {

            $requestsNotRecived = DB::table('quality_reqs')
                ->join('request_conditions', 'request_conditions.id', 'quality_reqs.con_id')
                ->where('quality_reqs.allow_recive', 0)
                ->where('quality_reqs.con_id', '!=', null)
                ->select('quality_reqs.id', 'quality_reqs.req_id', 'quality_reqs.user_id', 'quality_reqs.allow_recive', 'request_conditions.timeDays', 'quality_reqs.created_at')
                ->get();

            if ($requestsNotRecived->count() > 0) {
                foreach ($requestsNotRecived as $request) {
                    if (count($active_quality) == $i) {
                        $i = 0;
                    }

                    if (MyHelpers::checkQualityReq($request->req_id)) {
                        $check = MyHelpers::checkConditionMatch($request->id);
                        if ($check != false) {
                            #remove need action req if existed(will nt allowed to received same request with admin & quality)
                            $needReq = MyHelpers::checkDublicateOfNeedActionReqWithStatusOnly($request->req_id);
                            if ($needReq != 'false') {
                                MyHelpers::removeNeedActionReq($needReq->id);
                            }
                            #----------------------------
                            $user_id = $active_quality[$i];
                            $ifThereIsPreviousReq = MyHelpers::checkQualityUser($request->id, $user_id);
                            if ($ifThereIsPreviousReq == "true") {
                                DB::table('quality_reqs')->where('id', $request->id)->update(['con_id' => $check, 'allow_recive' => 1, 'user_id' => $user_id, 'created_at' => Carbon::now('Asia/Riyadh')]);

                                DB::table('notifications')->insert([
                                    'value'      => MyHelpers::guest_trans('New Request Added'),
                                    'recived_id' => $user_id,
                                    'created_at' => (Carbon::now('Asia/Riyadh')),
                                    'type'       => 0,
                                    'req_id'     => $request->id,
                                ]);

                                DB::table('request_histories')->insert([
                                    'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                                    'user_id'      => null,
                                    'recive_id'    => $user_id,
                                    'history_date' => (Carbon::now('Asia/Riyadh')),
                                    'req_id'       => $request->req_id,
                                    'content'      => null,
                                ]);
                                $i++;
                            }
                            else {
                                $checkIfQualityUserArchived = User::where('id', $ifThereIsPreviousReq)->where('status', 1)->where('allow_recived', 1)->first();
                                if (!empty($checkIfQualityUserArchived)) {
                                    $user_id = $checkIfQualityUserArchived->id;
                                }
                                else {
                                    $i++;
                                }

                                DB::table('quality_reqs')->where('id', $request->id)->update(['con_id' => $check, 'allow_recive' => 1, 'user_id' => $user_id, 'created_at' => Carbon::now('Asia/Riyadh')]);

                                DB::table('notifications')->insert([
                                    'value'      => MyHelpers::guest_trans('New Request Added'),
                                    'recived_id' => $user_id,
                                    'created_at' => (Carbon::now('Asia/Riyadh')),
                                    'type'       => 0,
                                    'req_id'     => $request->id,
                                ]);

                                DB::table('request_histories')->insert([
                                    'title'        => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                                    'user_id'      => null,
                                    'recive_id'    => $user_id,
                                    'history_date' => (Carbon::now('Asia/Riyadh')),
                                    'req_id'       => $request->req_id,
                                    'content'      => null,
                                ]);
                            }
                        }
                    }
                }
            }
        }
    }

    public function translate($id)
    {
        $quality = quality_req::find($id);
        $request = \App\request::find($quality->req_id);
        $message = 'طلب مضاف من قبل الجودة';
        $check = MyHelpers::checkDublicateOfNeedActionReq($message, $request->user_id, $request->id);
        if ($check) {
            MyHelpers::addNeedActionReqWithoutConditions($message, $request->user_id, $request->id);
        }
        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الطلب ',
        ]);
    }

    public function qualityUsers()
    {
        $myusers = User::withCount("quality_reqs","quality_reqs_followed","quality_reqs_completed"
            ,"quality_reqs_recevied","quality_reqs_arch")->where('role', 5) // active users only
        ->where("subdomain","<>",null)->get();

        return view('QualityManager.QualityUsers.index', compact( 'myusers'));

    }

    public function new_auto_transfer_quality_reqs() # get only match classes
    {
        $counter = 0;
        # is auto transfer worked?
        if (MyHelpers::checkActiveQualityReqs()) {

            #get quality counter
            $quality_counter = MyHelpers::getQualityCounter();
            #get quality activate end date
            $quality_end_date = MyHelpers::getQualityActivateEndDate();

            # check activae quality users
            $active_quality = MyHelpers::activeQualities();
            if (count($active_quality) == 0)
                return;

            # get class conditions
            $conditions = RequestCondition::with(['statusConditions', 'userConditions', 'classificationConditions'])->get(); # get all these tables with relations
            foreach ($conditions as $k => $condition) {
                #$users = $condition->userConditions->pluck('user_id')->toArray();
                #$statuses = $condition->statusConditions->pluck('status')->toArray();
                $classifications = $condition->classificationConditions->pluck('class_id')->toArray();
                $days = $condition->timeDays;

                # start check 
                #if (!empty($users) || !empty($statuses) || !empty($classifications)) {
                if (!empty($classifications)) {
                    $reqs = request_model::query()->whereDate('req_date', '>=', '2022-01-01')
                    ->whereIn('statusReq', [0,1,2,4]) # agent status
                    ->whereDoesntHave('qualityRequests', function($q){
                        $q->where(function($q2){
                            $q2->where('quality_reqs.allow_recive', 1);
                            $q2->whereIn('quality_reqs.status', [0, 1, 2, 4, 5]);// new , open, under process,archived
                        });
                    })
                    ->inRandomOrder()
                    ->limit(2500);

                    # check matches conditins
                    //!empty($users) && $reqs->whereIn('user_id', $users);
                    //!empty($statuses) && $reqs->whereIn('statusReq', $statuses);
                    !empty($classifications) && $reqs->whereIn('class_id_agent', $classifications);
                    $reqs = $reqs->get();
                    echo 'reqs: ' . $reqs ." \n" ;

                    foreach ($reqs as $req) {
                        echo 'req ID: ' . $req->id ." \n" ;
                        $now = Carbon::now();

                        if (
                            !($record = $req->requestRecords()->where([ # get last update of class agent = class condition
                                'colum' => RequestRecord::AGENT_CLASS_RECORD,
                                'value' => $req->class_id_agent,
                                'user_id' => $req->user_id, // because once move request to another 
                            ])->latest('updateValue_at')->first())
                        )
                        {
                            # ther's no record
                            $time = $req->agent_date ?: ($req->updated_at ?: $req->created_at);
                            $time = $time ? Carbon::parse($time) : null;

                        }
                        else {
                            $time = $record->updateValue_at ? Carbon::parse($record->updateValue_at) : null;
                        }

                        if ($time == null)
                            continue;

                        $checkValue = $time->diffInDays($now);
                        $timedaysWithActiveCounter = $days;

                        # quality counter and end date::
                        if ($quality_end_date != null){
                            if ($time < $quality_end_date)
                            $timedaysWithActiveCounter += $quality_counter;
                        }

                        echo 'time: ' . $time ." \n" ;
                        echo 'timedaysWithActiveCounter: ' . $timedaysWithActiveCounter ." \n" ;
                        echo 'checkValue: ' . $checkValue ." \n" ;
                        if ($timedaysWithActiveCounter <= $checkValue){
                            $quality_id = getLastQualityOfDistribution();
                            $model = QualityRequest::create([
                                'allow_recive'              => 1,
                                'user_id'                   => $quality_id,
                                'req_id'                    => $req->id,
                                'req_class_id_agent'        => $req->class_id_agent,
                                'con_id'                    => $condition->id,
                                'status'                    => 0,
                                'is_followed'               => 0,
                            ]);
                            $counter++;
                            setLastQualityOfDistribution($quality_id);

                            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($req->quality)) {
                                MyHelpers::addDailyPerformanceRecord($quality_id);
                            }
                            MyHelpers::incrementDailyPerformanceColumn($quality_id, 'received_basket', $req->id);

                            if ($model->exists) {
                                $req->createHistory([
                                    'user_id'        => null,
                                    'recive_id'      => $quality_id,
                                    'title'          => RequestHistory::TITLE_MOVE_REQUEST_QUALITY,
                                    'content'        => RequestHistory::CONTENT_AUTO_MOVE_QUALITY,
                                    'class_id_agent' => $req->class_id_agent,
                                ]);
                            }
                        }

                    }
                }
            }
            echo 'counter: ' . $counter ." \n" ;
        }
    }

    
    public function check_updates_on_quality_reqs() # get only match classes
    {
        $counter = 0;

        $conditions = RequestCondition::with(['statusConditions', 'userConditions', 'classificationConditions'])->get(); # get all these tables with relations
        if (!empty($conditions)){

            
            foreach ($conditions as $k => $condition) {

                echo 'condition: ' . $condition->id ." \n" ;

                $requests_id = QualityRequest::query()
                ->where('allow_recive', 1)
                ->where('con_id', $condition->id)
                ->whereIn('status', [0, 1, 2, 4, 5])// new , open, under process,archived
                ->pluck('req_id')->toArray();
    
                echo 'requests_id: ' . count($requests_id) ." \n" ;

                # get requests
                $classifications = $condition->classificationConditions->pluck('class_id')->toArray();
                $reqs = request_model::query()->whereIn('id', $requests_id);
                $reqs = $reqs->whereNotIn('class_id_agent',$classifications)->pluck('id')->toArray();

                echo 'reqs: ' . count($reqs) ." \n" ;

                foreach ($reqs as $k => $req_id) {
    
                    $check_quality_req = QualityRequest::query()
                    ->where('con_id', $condition->id)
                    ->whereIn('status', [0, 1, 2, 4, 5])
                    ->where('req_id', $req_id)
                    ->first();

                    if (!empty($check_quality_req)){
                        $quality_user = $check_quality_req->user_id;
                        if (in_array($check_quality_req->status, [0,1])){ # not worked yet
                            $check_quality_req->delete();
                            RequestHistory::create([
                                'req_id'        => $req_id,
                                'user_id'        => $quality_user,
                                'recive_id'      => null,
                                'title'          => RequestHistory::UPDATE_QUALITY_REQUEST,
                                'content'        => RequestHistory::DELETE_QUALITY_REQUEST,
                                'history_date'   => Carbon::now(),
                            ]);
                        }
                        else{
                            QualityRequest::query()->where('id', $check_quality_req->id)->update(['status' => 3]);
                            #$check_quality_req->update(['status',3]); #marked as completed
                            RequestHistory::create([
                                'req_id'         => $req_id,
                                'user_id'        => $quality_user,
                                'recive_id'      => null,
                                'title'          => RequestHistory::UPDATE_QUALITY_REQUEST,
                                'content'        => RequestHistory::MARK_AS_COMPLETED,
                                'history_date'   => Carbon::now(),
                            ]);
                        }
                        $counter++;
                    }

                }
            }
            echo 'counter: ' . $counter ." \n" ;
        }
       

    }
}
