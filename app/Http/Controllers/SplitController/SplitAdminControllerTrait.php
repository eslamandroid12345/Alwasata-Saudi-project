<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\SplitController;

use App\classifcation;
use App\CustomersPhone;
use App\funding_source;
use App\Model\PendingRequest;
use App\Models\Classification;
use App\Models\Customer;
use App\Models\OtpRequest;
use App\Models\RequestHistory;
use App\Models\User;
use App\RequestNeedAction;
use App\RequestWaitingList;
use App\salary_source;
use App\task;
use App\task_content;
use App\WorkSource;
use Carbon\Carbon;
use Datatables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use MyHelpers;
use Session;

trait SplitAdminControllerTrait
{

    public function moveNeedReqToAnotherArrayAgent(Request $request)
    {
        /**
         * Automatic Distribution
         */
        $autoDistribution = !1;
        $counter = 0;
        $i = 0;
        $salesAgents = [];
        $requestIds = RequestNeedAction::whereIn('id', $request->id)->where('status', 0)->pluck('req_id')->toArray();
        //dd($requestIds);
        //$a = \App\Models\Request::find(71321);
        //dd($a->class_id_agent);
        $requestsData = DB::table('requests')
            ->whereIn('id', $requestIds)
            ->where(fn($b) => $b->whereNotIn('class_id_agent', [57, 58])->orWhereNull('class_id_agent'))
            ->get();
        //dd($requestsData);

        if ($request->agents_ids == '') {
            $autoDistribution = !0;
            //$lastId = getLastAgentOfDistribution();
            // Get After last agent ID
            //$salesAgents = \App\Models\User::forDistributionOnly()->where('id', '>', $lastId)->pluck('id')->toArray();
            //$salesAgents = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->pluck('id')->toArray();
        }
        else {
            $salesAgents = array_merge($salesAgents, $request->agents_ids);
        }
        //dd($requestsData);
        foreach ($requestsData as $model) {
            // Check if there is need action req
            $this->checkIfThereIsNeedActionReq($model->id);

            if (count($salesAgents) == $i) {
                $i = 0;
            }
            $prev_user = $model->user_id;
            $prev_user = str_replace(' ', '', $prev_user);
            if ($autoDistribution) {
                $salesAgents[$i] = getLastAgentOfDistribution();
                if ($model->user_id == $salesAgents[$i]) {
                    setLastAgentOfDistribution($model->user_id);
                    $salesAgents[$i] = getLastAgentOfDistribution();
                }
            }
            else {
                // to remove same user to duplicate with same request
                if (($key = array_search($prev_user, $salesAgents)) !== false) {
                    unset($salesAgents[$key]);
                    $salesAgents = array_values($salesAgents);
                }
            }
            //check if there's no available agent
            if (!$autoDistribution && count($salesAgents) == 0) {
                return response()->json(['updatereq' => 2, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'No Avaliable Agents')]);
            }

            $reqID = $model->id;
            $getAllIdsInQualityReqs = DB::table('quality_reqs')->where('quality_reqs.req_id', '=', $reqID)->pluck('id')->toArray();

            //MOVE NEW AND READ TASK
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {
                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [0, 1])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status'     => 0,
                    'recive_id'  => $salesAgents[$i],
                    'created_at' => carbon::now(),
                ]);

                DB::table('task_contents')->whereIn('task_id', $getAllTasksIds)->update([
                    'task_contents_status' => 0,
                    'date_of_content'      => carbon::now(),
                ]);
            }

            // MOVE replaid task
            $getAllTasksIds = DB::table('tasks')->where(function ($query) use ($reqID, $getAllIdsInQualityReqs, $prev_user) {
                $query->where(function ($query) use ($reqID, $prev_user) {
                    $query->where('tasks.req_id', $reqID);
                    $query->where('tasks.recive_id', $prev_user);
                });

                $query->orWhere(function ($query) use ($getAllIdsInQualityReqs, $prev_user) {
                    $query->whereIn('tasks.req_id', $getAllIdsInQualityReqs);
                    $query->where('tasks.recive_id', $prev_user);
                });
            })->whereIn('status', [2])->pluck('id')->toArray();

            if (count($getAllTasksIds) > 0) {

                //set current task as completed
                DB::table('tasks')->whereIn('id', $getAllTasksIds)->update([
                    'status' => 3,
                ]);

                //GET ALL PERVIOS TASK INFO
                $tasks = DB::table('tasks')->whereIn('id', $getAllTasksIds)->get();

                foreach ($tasks as $task) {

                    $getTaskContent = DB::table('task_contents')->where('task_id', $task->id)->where('task_contents_status', 0) // NO REPLAYS YET
                    ->first();

                    if (!empty($getTaskContent)) {
                        $newTask = task::create([
                            'req_id'    => $task->req_id,
                            'recive_id' => $salesAgents[$i],
                            'user_id'   => $task->user_id,
                        ]);

                        task_content::create([
                            'content'         => $getTaskContent->content,
                            'date_of_content' => Carbon::now('Asia/Riyadh'),
                            'task_id'         => $newTask->id,
                        ]);
                    }
                }
            }

            ///////////////////////////////////////////////////////
            $customerID = $model->customer_id;
            $updatereq = DB::table('requests')->where('id', $reqID)->update([
                'user_id'                 => $salesAgents[$i],
                'statusReq'               => 0,
                'agent_date'              => now(),
                'is_stared'               => 0,
                'is_followed'             => 0,
                'add_to_stared'           => null,
                'add_to_followed'         => null,
                'isUnderProcFund'         => 0,
                'isUnderProcMor'          => 0,
                'recived_date_report'     => null,
                'recived_date_report_mor' => null,
                // 'created_at' => carbon::now(),
                // 'req_date' => Carbon::today('Asia/Riyadh')->format('Y-m-d'),
            ]);
            if ($updatereq) {
                $counter++;
                if ($autoDistribution) {
                    setLastAgentOfDistribution($salesAgents[$i]);
                }
            }

            if ($model->collaborator_id == null) {
                $updatecust = DB::table('customers')->where('id', $customerID)->update([
                    'user_id' => $salesAgents[$i], //active
                ]);
            }

            DB::table('notifications')->insert([
                'value'      => MyHelpers::admin_trans(auth()->user()->id, 'New Request Added'),
                'recived_id' => $salesAgents[$i],
                'created_at' => now('Asia/Riyadh'),
                'type'       => 0,
                'req_id'     => $reqID,
            ]);

            //DB::table('users')->where('id', $salesAgents[$i])->first();
            //  //$pwaPush = MyHelpers::pushPWA($salesAgents[$i], ' يومك سعيد  ' . $agenInfo->name, 'طلب جديد بإنتظارك في سلتك', 'فتح الطلب', 'agent', 'fundingreqpage', $request);

            DB::table('request_histories')->insert([
                'title'          => RequestHistory::TITLE_MOVE_REQUEST,
                'user_id'        => $prev_user,
                'recive_id'      => $salesAgents[$i],
                'history_date'   => now('Asia/Riyadh'),
                'req_id'         => $reqID,
                'class_id_agent' => $model->class_id_agent,
                //'content'        => MyHelpers::admin_trans(auth()->user()->id, 'Admin'),
                'content'        => RequestHistory::CONTENT_ADMIN_TRANS_BASKET,
            ]);

            //remove previous notificationes that related to previous agent's request
            DB::table('notifications')->where([
                'recived_id' => $prev_user,
                'req_id'     => $reqID,
            ])->delete();

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $prev_user;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            // MyHelpers::incrementDailyPerformanceColumn($agent_id, today('Asia/Riyadh')->format('Y-m-d'), 'move_request_from',$reqID);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_from',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $salesAgents[$i];
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$reqID);
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'move_request_to',$reqID);
            //MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$reqID);
            //***********END - UPDATE DAILY PREFROMENCE */

            #move customer's messages to new agent
            MyHelpers::movemessage($customerID, $salesAgents[$i], $prev_user);

            #Remove request from Quality & Need Action Req once moved it
            #1::Remove Req from Quality
            if (MyHelpers::checkQualityReqExistedByReqID($reqID) > 0) {
                $qualityReqDelte = MyHelpers::removeQualityReqByReqID($reqID);
                if ($qualityReqDelte == 0) {
                    MyHelpers::updateQualityReqToCompleteByReqID($reqID);
                }
            }
            #2::Remove from Need Action Req
            MyHelpers::removeNeedActionReqByReqID($reqID);

            $i++;
        }

        if ($counter == 0) {
            //return response()->json([
            //    'updatereq' => 0,
            //    'message'   => "لم يتم تحويل ",
            //]);
        }
        // if 1: update successfully
        return response()->json([
            'counter'   => $counter,
            'updatereq' => 1,
            //'message'   => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully'),
            'message'   => sprintf("تم نقل %d بنجاح",$counter),
        ]);

    }

    public function movePendingReqToAnother(Request $request)
    {

        $content = MyHelpers::guest_trans('PendingRequests');
        $move_pending = MyHelpers::movePendingRequestByAgent($request->id, $request->salesAgent, $content);

        if ($move_pending != -1 && $move_pending != false) {
            $notify = MyHelpers::addNewNotify($move_pending, $request->salesAgent);
            //***********UPDATE DAILY PREFROMENCE */
            $agent_id = $request->salesAgent;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$request->id,'pendings');
           // MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$request->id,'pendings');
            //***********END - UPDATE DAILY PREFROMENCE */

            return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally
        }

        return response()->json(['status' => 0, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again')]);
    }

    public function movePendingReqToAnotherArray(Request $request)
    {

        $counter = 0;
        $i = 0;
        $salesAgents = [];
        $content = MyHelpers::guest_trans('PendingRequests');

        $requests_data = PendingRequest::whereIn('id', $request->id)->get();

        if ($request->agents_ids == '') {
            $salesAgents = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->pluck('id')->toArray();
        }
        else {
            $salesAgents = array_merge($salesAgents, $request->agents_ids);
        }

        foreach ($requests_data as $request_data) {
            if (count($salesAgents) == $i) {
                $i = 0;
            }

            $move_pending = MyHelpers::movePendingRequestByAgent($request_data->id, $salesAgents[$i], $content);

            if ($move_pending != -1 && $move_pending != false) {
                $notify = MyHelpers::addNewNotify($move_pending."- Pending Request Ask Admin", $salesAgents[$i]);
                //***********UPDATE DAILY PREFROMENCE */
                $agent_id = $salesAgents[$i];
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                }
                MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_basket',$request_data->id,'pendings');
                //MyHelpers::incrementDailyPerformanceColumn($agent_id, 'total_recived_request',$request_data->id,'pendings');
                //***********END - UPDATE DAILY PREFROMENCE */

                $counter++;
                $i++;
            }

        }

        if ($counter == 0) {
            return response()->json(['status' => 0, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again')]);
        }
        return response()->json(['status' => 1, 'message' => MyHelpers::admin_trans(auth()->user()->id, 'Move sucessfully')]); // if 1: update succesfally

    }

    public function needActionReqsNew(Request $request)
    {
        $requests = DB::table('request_need_actions')->where('request_need_actions.status', 0)->join('customers', 'customers.id', '=', 'request_need_actions.customer_id')->join('users', 'users.id', '=', 'request_need_actions.agent_id')->select('request_need_actions.*',
            'customers.name as customer_name', 'users.name as user_name', 'customers.salary_id', 'customers.mobile')->count();

        $salesAgents2 = DB::table('request_need_actions')->where('request_need_actions.status', 0)->join('users', 'users.id', '=', 'request_need_actions.agent_id')->distinct('users.id')->select('users.name', 'users.id')->get();

        $salesAgents = User::where('role', 0)->get();

        $salesManagers = User::where('role', 1)->where('status',1)->get();

        $classifcations_sa = Classification::where('user_role', 0)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('Admin.Request.needActionReqsNew', compact('requests','salesManagers', 'salesAgents', 'salesAgents2', 'classifcations_sa', 'worke_sources', 'request_sources'));
    }

    /**
     * Note: طلبات بحاجة الى تحويل - جديدة
     * @param  Request  $request
     * @return mixed
     * @throws \Exception
     */
    public function needActionReqs_datatableNew(Request $request)
    {
        $requests = DB::table('request_need_actions')
            // ->where('request_need_actions.status', 0)
            ->join('customers', 'customers.id', '=', 'request_need_actions.customer_id')
            ->join('requests', 'requests.id', '=', 'request_need_actions.req_id')
            ->join('users', 'users.id', '=', 'request_need_actions.agent_id')
            //->join('users', 'users.id', '=', 'requests.user_id')
            ->leftJoin('tasks', 'tasks.req_id', '=', 'request_need_actions.req_id')
            ->leftJoin('task_contents', 'task_contents.task_id', '=', 'tasks.id')
            ->select('request_need_actions.req_id as request_id', 'request_need_actions.id as need_id', 'request_need_actions.action', 'request_need_actions.status', 'request_need_actions.created_at as need_created_at', 'requests.*', 'customers.name as customer_name', 'users.name as user_name',
                'customers.salary_id', 'customers.mobile', 'task_contents.content as content')
            ->orderBy('request_need_actions.created_at', 'DESC');
        //dd($requests->get()->first());

        // $requests=RequestNeedAction::query()->with('customer','request','user')->orderBy('request_need_actions.created_at', 'DESC');

        if ($request->get('action')) {
            $requests = $requests->where('request_need_actions.action', $request->get('action'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        if ($request->get('status_of_request') == 0) {
            $requests = $requests->where('request_need_actions.status', 0);
        }else{
            $requests = $requests->where('request_need_actions.status', 1);
        }

        $xses = [
            'sa' => 'class_id_agent',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }
        //if ($request->has('search')) {
        //    if (array_key_exists('value',$request->search)){
        //        if (is_numeric($request->search['value']) && strlen($request->search['value']) == 9){
        //            $mobile = DB::table('customers')->where('mobile', $request->search['value']);
        //            if ($mobile->count() == 0) {
        //                $mobiles = CustomersPhone::where('mobile',$request->search['value'])->first();
        //                if ($mobiles != null) {
        //                    $requests = $requests->where('customer_id', $mobiles->customer_id);
        //                }
        //            }
        //            else {
        //                $requests = $requests->where('customers.mobile', $request->search['value']);
        //            }
        //        }
        //        $search = $request->search;
        //        $search['value'] = null;
        //        $request->merge([
        //            'search' => $search
        //        ]);
        //    }
        //
        //}
        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('actions', function ($row) {
            if ($row->status == 0) {
                $data = '<div class="tableAdminOption">';
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-need="'.$row->need_id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-need="'.$row->need_id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                if ($row->type != 'رهن-شراء' && $row->type != 'شراء-دفعة' && $row->statusReq != 16 && $row->statusReq != 15 && $row->statusReq != 14) {
                    $data = $data.'<span class="item pointer" id="move" data-need="'.$row->need_id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Move Req').'">
                                        <i class="fas fa-random"></i>
                                    </span> ';
                }
                $data = $data.'<span class="item pointer" id="moveToDone"  data-id="'.$row->need_id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'notification done').'">
                <i class="fas fa-check"></i> </span> ';

                $data = $data.'</div>';
            }
            else {
                $data = '';
            }
            return $data;
        })->editColumn('need_created_at', function ($row) {
            $data = $row->need_created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }

            if ($row->collaborator_id != null) {

                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();

                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
            }
            return $data;
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('status', function ($row) {
            if ($row->status == 0) {
                return 'جديد';
            }
            else {
                return 'تمت المعالجة';
            }
        })->rawColumns(['actions'])->make(true);
    }

    public function updateNeedActionReq(Request $request)
    {
        $ids = $request->get('needID', []);
        if ($ids && !is_array($ids)) {
            $ids = explode(',', $ids);
        }
        $updateReq = RequestNeedAction::whereIn('id', $ids)->update(['status' => 1]);
        return response($updateReq);
    }


    public function needToBeTurnedReqNew(Request $request)
    {
        $requests =  DB::table('quality_request_need_turneds')->where('quality_request_need_turneds.status', 0)->count();

        $salesAgents2 = DB::table('quality_request_need_turneds')->where('quality_request_need_turneds.status', 0)->join('users', 'users.id', '=', 'quality_request_need_turneds.previous_agent_id')->distinct('users.id')->select('users.name', 'users.id')->get();
        $qualityUser = DB::table('quality_request_need_turneds')->where('quality_request_need_turneds.status', 0)->join('users', 'users.id', '=', 'quality_request_need_turneds.quality_id')->distinct('users.id')->select('users.name', 'users.id')->get();

        $salesAgents = User::where('role', 0)->get();

        $salesManagers = User::where('role', 1)->where('status',1)->get();

        $classifcations_sa = Classification::where('user_role', 0)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('Admin.Request.qualityNeedTurnedReqsNew', compact('requests','salesManagers', 'salesAgents', 'salesAgents2', 'classifcations_sa', 'worke_sources', 'request_sources','qualityUser'));
    }

    public function needToBeTurnedReqNew_datatable(Request $request)
    {
        $requests = DB::table('quality_request_need_turneds')
        ->where('quality_request_need_turneds.status', 0)
        ->join('quality_reqs', 'quality_reqs.id', 'quality_request_need_turneds.quality_req_id')
        ->join('users as others', 'others.id', 'quality_reqs.user_id')
        ->join('requests', 'requests.id', 'quality_reqs.req_id')
        ->join('users', 'users.id', 'requests.user_id')
        ->join('customers', 'customers.id', '=', 'requests.customer_id')
        ->select("others.name_for_admin as  quality","customers.mobile","customers.name as  customer_name", 'requests.id','requests.statusReq','requests.class_id_agent','requests.comment', 'users.name as agentName', 'customers.name',  'requests.quacomment', 'requests.type','quality_request_need_turneds.created_at' , 'reject_reason','quality_request_need_turneds.id as turned_id','quality_request_need_turneds.status')
        ->orderBy('quality_request_need_turneds.created_at', 'DESC');


        if ($request->get('qualityUser')) {
            $requests = $requests->where('quality_request_need_turneds.quality_id', $request->get('qualityUser'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        $xses = [
            'sa' => 'class_id_agent',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('actions', function ($row) {
            if ($row->status == 0) {
                $data = '<div class="tableAdminOption">';
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-need="'.$row->id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-need="'.$row->id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }

                $data = $data.'<span class="item pointer" id="accept"  data-id="'.$row->turned_id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Move Req').'">
                <i class="fas fa-check"></i> </span> ';


                $data = $data.'<span class="item pointer" id="reject"  data-id="'.$row->turned_id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Reject Move').'">
                <i class="fas fa-times"></i> </span> ';

                $data = $data.'</div>';
            }
            else {
                $data = '';
            }
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
        }
        })->rawColumns(['actions'])->make(true);
    }


    public function needToBeTurnedReqDone(Request $request)
    {
        $requests =  DB::table('quality_request_need_turneds')->whereIn('quality_request_need_turneds.status', [1,2])->count();

        $salesAgents2 = DB::table('quality_request_need_turneds')->whereIn('quality_request_need_turneds.status',  [1,2])->join('users', 'users.id', '=', 'quality_request_need_turneds.previous_agent_id')->distinct('users.id')->select('users.name', 'users.id')->get();
        $qualityUser = DB::table('quality_request_need_turneds')->whereIn('quality_request_need_turneds.status',  [1,2])->join('users', 'users.id', '=', 'quality_request_need_turneds.quality_id')->distinct('users.id')->select('users.name', 'users.id')->get();

        $salesAgents = User::where('role', 0)->get();

        $salesManagers = User::where('role', 1)->where('status',1)->get();

        $classifcations_sa = Classification::where('user_role', 0)->get();

        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();

        return view('Admin.Request.qualityNeedTurnedReqsDone', compact('requests','salesManagers', 'salesAgents', 'salesAgents2', 'classifcations_sa', 'worke_sources', 'request_sources','qualityUser'));
    }

    public function needToBeTurnedReqDone_datatable(Request $request)
    {
        $requests = DB::table('quality_request_need_turneds')
        ->whereIn('quality_request_need_turneds.status', [1,2])
        ->join('quality_reqs', 'quality_reqs.id', 'quality_request_need_turneds.quality_req_id')
        ->join('users as others', 'others.id', 'quality_reqs.user_id')
        ->join('requests', 'requests.id', 'quality_reqs.req_id')
        ->join('users', 'users.id', 'requests.user_id')
        ->join('customers', 'customers.id', '=', 'requests.customer_id')
        ->select("others.name_for_admin as  quality","customers.mobile","customers.name as  customer_name", 'requests.id','requests.statusReq','requests.class_id_agent','requests.comment', 'users.name as agentName', 'customers.name',  'requests.quacomment', 'requests.type','quality_request_need_turneds.created_at' , 'reject_reason','quality_request_need_turneds.id as turned_id','quality_request_need_turneds.status')
        ->orderBy('quality_request_need_turneds.created_at', 'DESC');


        if ($request->get('qualityUser')) {
            $requests = $requests->where('quality_request_need_turneds.quality_id', $request->get('qualityUser'));
        }

        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }

        $xses = [
            'sa' => 'class_id_agent',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests = $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('actions', function ($row) {
                $data = '<div class="tableAdminOption">';
                if ($row->type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-need="'.$row->id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-need="'.$row->id.'" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                    <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                }

                $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('class_id_agent', function ($row) {

            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first();

            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
        }
        })->rawColumns(['actions'])->make(true);
    }


    public function moveRequestsToQuality(Request $request)
    {
        $ids = $request->get('ids', []);
        if ($ids && !is_array($ids)) {
            $ids = explode(',', $ids);
        }
        dd($ids);
        $updateReq = RequestNeedAction::whereIn('id', $ids)->update(['status' => 1]);
        return response($updateReq);
    }

    public function addToNeedActionReq(Request $request)
    {
        $check = false;
        $request = \App\request::find($request->id);
        $message = 'طلب مضاف من قبل مدير النظام';
        $check = MyHelpers::checkDublicateOfNeedActionReq($message, $request->user_id, $request->id);
        if ($check) {
            MyHelpers::addNeedActionReqWithoutConditions($message, $request->user_id, $request->id);
        }
        return response()->json([
            'check'   => $check,
            'success' => true,
            'message' => 'تم إضافة الطلب ',
        ]);
    }

    public function addToNeedActionReqArray(Request $request)
    {
        $check = false;
        $count = 0;
        foreach ($request->array as $request_array) {
            $request = \App\request::find($request_array);
            $message = 'طلب مضاف من قبل مدير النظام';
            $check = MyHelpers::checkDublicateOfNeedActionReq($message, $request->user_id, $request->id);
            //dd($check);
            if ($check) {
                ++$count;
                MyHelpers::addNeedActionReqWithoutConditions($message, $request->user_id, $request->id);
            }
        }

        return response()->json([
            'check'   => $check,
            'success' => true,
            'message' => arabic_date(__("replace.requestTrans", ['c' => $count])),
        ]);
    }

    public function updateWaitingReq(Request $request)
    {
        $updateReq = RequestWaitingList::where('req_id', $request->needID)->update(['status' => 1]);
        return response($updateReq);
    }

    public function allCustomer()
    {
        $customers = Customer::query()
            ->whereHas('user')
            ->whereHas('request')
            ->count();
        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = Classification::where('user_role', 0)->get();
        $collaborators = DB::table('user_collaborators')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->select('user_collaborators.collaborato_id as id', 'users.name')
            ->get();
        $collaborators = (new Collection($collaborators))->unique('id');
        $collaborators->values()->all();
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        $salary_sources = DB::table('salary_sources')->select('id', 'value')->get();
        return view('Admin.Customer.allCustomers', compact('customers', 'classifcations_sa', 'collaborators', 'salesAgents', 'worke_sources', 'request_sources', 'salary_sources'));
    }

    public function allCustomer_datatable(Request $request)
    {
        $customers = Customer::query()->with(['user', 'request']);
        if ($request->get('req_date_from')) {
            $customers->whereDate('req_date', '>=', $request->get('req_date_from'));
        }
        if ($request->get('req_date_to')) {
            $customers->whereDate('req_date', '<=', $request->get('req_date_to'));
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            $customers->whereIn('user_id', $request->get('agents_ids'));
        }
        if ($request->get('customer_salary')) {
            $customers->where('salary', '>=', $request->get('customer_salary'));
        }
        if ($request->get('customer_salary_to')) {
            $customers->where('salary', '<=', $request->get('customer_salary_to'));
        }
        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            $customers->whereIn('work', $request->get('work_source'));
        }
        if ($request->get('source') && is_array($request->get('source'))) {
            $customers->whereHas('request', fn(Builder $builder) => $builder->whereIn('source', $request->get('source')));
        }
        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            $customers->whereHas('request', fn(Builder $builder) => $builder->whereIn('collaborator_id', $request->get('collaborator')));
        }
        if ($request->get('customer_phone')) {
            $mobile = $request->get('customer_phone');
            $customers->where(fn(Builder $b) => $b->where('mobile', $mobile)
                ->orWhereHas('customerPhones', fn(Builder $b) => $b->where('mobile', $mobile)));
        }
        if ($request->get('app_downloaded') && is_array($request->get('app_downloaded'))) {
            $customers->whereIn('app_downloaded', $request->get('app_downloaded'));
        }
        $xses = [
            'sa' => 'class_id_agent',
        ];
        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $customers->whereHas('request', fn(Builder $builder) => $builder->whereIn($xs, $req));
            }
        }
        return Datatables::of($customers)->setRowId(function ($customer) {
            return $customer->id;
        })->addColumn('salry', function ($row) {
            if ($row->salary != null) {
                $data = $row->salary.MyHelpers::admin_trans(auth()->user()->id, 'SR');
            }
            else {
                $data = '---';
            }
            return $data;
        })->editColumn('work', function ($row) {
            $data = WorkSource::where('id', $row->work)->first();
            if (empty($data)) {
                $data = $row->work;
            }
            else {
                $data = $data->value;
            }

            return $data ?: '-';
        })->editColumn('source', function (Customer $row) {
            if (!$row->request) {
                return '-';
            }
            $source = $row->request->source;
            $data = DB::table('request_source')->where('id', $source)->first();
            if (!$data) {
                $data = $source;
            }
            else {
                $data = $data->value;
            }
            $collaborator = $row->request->collaborator_id;

            if ($collaborator) {
                $collInfo = DB::table('users')->where('id', $collaborator)->first();
                if ($collInfo->name) {
                    $data .= ($data ? ' - ' : '').$collInfo->name;
                }
            }
            return $data ?: '-';
        })->editColumn('app_downloaded', function (Customer $row) {
            if ($row->app_downloaded == 1) {
                $data = 'نعم';
            }
            else {
                if ($row->app_downloaded == 0) {
                    $data = 'لا';
                }
                else {
                    $data = '';
                }
            }
            return $data;
        })->editColumn('user_name', fn(Customer $row) => $row->user->name)->editColumn('class_id_agent', function ($row) {
            if (($classification = Classification::find($row->request->class_id_agent ?? ''))) {
                return $classification->value;
            }
            return '-';
        })->editColumn('last_sms_date',function (Customer $customer){
            $last = DB::table('sms_logs')->where('mobile',$customer->mobile)->latest()->first();
            if ($last != null)
            {
                return Carbon::createFromFormat('Y-m-d H:i:s', $last->created_at);
            }
            return '-';
        })->addColumn('action', function (Customer $customer) {
            $requestId = $customer->request->id ?? '';
            $type = $customer->request->type ?? '';

            $data = '<div id="tableAdminOption" class="tableAdminOption">';
            if ($customer->app_downloaded == 1) {
                $data .= "<span class='pointer' data-toggle='tooltip' data-id='{$customer->id}' data-placement='top' title='".MyHelpers::admin_trans(auth()->user()->id, 'app_downloaded')."'><i class='fa fa-mobile'></i></span>";
            }

            if ($requestId) {
                if ($type == 'رهن-شراء') {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$requestId.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $requestId).'"><i class="fas fa-eye"></i></a></span>';
                }
                else {
                    $data = $data.'<span class="item pointer" id="open" data-id="'.$requestId.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $requestId).'"><i class="fas fa-eye"></i></a></span>';
                }
            }
            $data = $data.'<span class="item pointer" id="history" data-id="'.$customer->id.'" data-toggle="tooltip" data-placement="top" title="تاريخ الرسائل">
                <a href="'.route('admin.getCustomerHistory', $customer->id).'"><i class="fas fa-history"></i></a></span>';

            $data = $data.'<span class="item pointer" id="edit" type="button" data-toggle="tooltip" data-id="'.$customer->id.'" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <i class="fas fa-edit"></i>
                                </span>';

            $data = $data.'<span class="item pointer" id="reset_password" data-id="'.$customer->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'reset_password').'">
                <a href="'.route('admin.customerResetPassword', $customer->id).'"><i class="fas fa-lock-open"></i></a></span>';
            return $data.'</div>';
        })->make(true);
    }

    public function customerResetPassword($id)
    {
        $customer = \App\customer::find($id);
        return view('Admin.Customer.reset_password_page', compact('customer'));
    }

    public function customerUpdatePassword(Request $request)
    {
        \App\customer::where('id', $request->customer_id)->update([
            'password'   => Hash::make($request->password),
            'isVerified' => 1,
        ]);
        return redirect()->route('admin.allCustomers')->with('msg', "تم تعيين كلمة المرور بنجاح");
    }

    public function ipAddresss_dataTable(Request $request)
    {
        $ips = OtpRequest::select('id', 'mobile', 'ip', DB::raw('COUNT(mobile) as count'))->orderBy('created_at','DESC')
            ->groupBy('ip');

        if (($s = $request->get('search'))) {
            if (is_array($s)) {
                $s = $s['value'] ?? null;
            }
            //dd($s);
            if ($s) {
                $ips = $ips->orWhere('ip', 'LIKE', "%{$s}%")->orWhere('mobile', 'LIKE', "%{$s}%");
            }
            else {
                //$ips = $ips->having("count", ">", 1);
            }
        }
        $ips = $ips->having("count", ">", 1);
        //dd($ips->toSql());
        return Datatables::of($ips)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('idn', function () {
            static $var = 1;
            return $var++;
        })->addColumn('ip', function ($row) {
            return $row->ip;
        })->addColumn('mobile', function ($row) {
            return $row->mobile;
        })->addColumn('counts', function ($row) {
            return $row->count;
        })->addColumn('count', function ($row) {
            $mobiles = OtpRequest::where('ip', $row->ip)->pluck('mobile')->toArray();
            $count = Customer::whereIn('mobile', $mobiles)->whereHas('request')->count();
            return $count;
        })->addColumn('actions', function ($row) {
            return '<a href="'.route('admin.ips.single', $row->ip).'" class="btn btn-primary btn-sm text-white">  <i class="fas fa-list mr-2"></i>الطلبات </a>';

        })->rawColumns(['actions', 'count', 'counts'])->make(true);
    }

    public function myReqs(Request $request)
    {

        //Session::put('customer_salary', 'afn');
        //dd(Session::get('quality_recived'));
        //dd(Session::get('customer_salary'));
        //$requests = DB::table('requests')->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->select('requests.*', 'customers.name as customer_name', 'users.name as user_name', 'customers.salary', 'customers.salary_id','customers.mobile', 'customers.birth_date', 'customers.work')->count();
        $notifys = $this->fetchNotify();
        // $customers = customer::all();

        $regions = customer::select('region_ip')->groupBy('region_ip')->get();

        $salesManagers = User::where('role', 1)->get();
        $salesAgents = User::where('role', 0)->get();
        $qualityUsers = User::where('role',5)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();
        $collaborators = DB::table('user_collaborators')
            ->leftjoin('users', 'users.id', '=', 'user_collaborators.collaborato_id')
            ->select('user_collaborators.collaborato_id as id', 'users.name')->get();

        $collaborators = (new Collection($collaborators))->unique('id');
        //$collaborators->values()->all();
        // dd($collaborators);
        $qulitys = User::where('role', 5)->where('status', 1)->get();
        $worke_sources = WorkSource::all();
        $request_sources = DB::table('request_source')->get();
        return view('Admin.Request.myReqs', compact(
            'notifys', 'regions', 'collaborators','classifcations_sm', 'classifcations_sa', 'classifcations_fm', 'classifcations_gm', 'classifcations_mm', 'classifcations_qu', 'all_status', 'all_salaries', 'founding_sources', 'pay_status', 'salesAgents', 'qulitys',
            'worke_sources', 'request_sources','salesManagers','qualityUsers'));
    }

    public function myReqs_datatable(Request $request)
    {
        
        $requests = DB::table('requests')->join('customers', 'customers.id', '=', 'requests.customer_id')->join('users', 'users.id', '=', 'requests.user_id')->join('joints', 'joints.id', '=', 'requests.joint_id')->join('real_estats', 'real_estats.id', '=', 'requests.real_id')->join('fundings',
            'fundings.id', '=', 'requests.fun_id')->leftjoin('prepayments', 'prepayments.req_id', '=', 'requests.id')
            ->select('requests.*', 'customers.name as cust_name', 'users.name as user_name','users.name as user_status', 'customers.mobile', 'customers.app_downloaded', 'prepayments.payStatus', 'requests.class_id_quality as is_quality_recived');#->orderBy('requests.created_at', 'DESC');
        if ($request->get('req_date_from')) {
            Session::put('req_date_from', $request->get('req_date_from'));
            $requests = $requests->whereDate('req_date', '>=', $request->get('req_date_from'));
        }
        else{
            Session::pull('req_date_from');
        }
        if ($request->get('req_date_to')) {
            Session::put('req_date_to', $request->get('req_date_to'));
            $requests = $requests->whereDate('req_date', '<=', $request->get('req_date_to'));
        }
        else{
            Session::pull('req_date_to');
        }
        if ($request->get('complete_date_from') || $request->get('complete_date_to')) {
            Session::put('complete_date_from',$request->get('complete_date_from'));
            Session::put('complete_date_to',  $request->get('complete_date_to'));
            $requests = $requests->whereBetween('complete_date', [$request->get('complete_date_from'), $request->get('complete_date_to')]);
        }
        else{
            Session::pull('complete_date_from');
            Session::pull('complete_date_to');
        }
        if ($request->get('updated_at_from')) {
            Session::put('updated_at_from',  $request->get('updated_at_from'));
            $requests = $requests->where('requests.updated_at', '>=', $request->get('updated_at_from'));
        }
        else{
            Session::pull('updated_at_from');
        }
        if ($request->get('updated_at_to')) {
            Session::put('updated_at_to',  $request->get('updated_at_to'));
            $requests = $requests->where('requests.updated_at', '<=', $request->get('updated_at_to'));
        }
        else{
            Session::pull('updated_at_to');
        }
        if ($request->get('founding_sources') && is_array($request->get('founding_sources'))) {
            Session::put('founding_sources',  $request->get('founding_sources'));
            $requests = $requests->whereIn('funding_source', $request->get('founding_sources'));
        }
        else{
            Session::pull('founding_sources');
        }
        if ($request->get('app_downloaded') && is_array($request->get('app_downloaded'))) {
            Session::put('app_downloaded',  $request->get('app_downloaded'));
            $requests = $requests->whereIn('app_downloaded', $request->get('app_downloaded'));
        }
        else{
            Session::pull('app_downloaded');
        }
        if ($request->get('notes_status')) {
            Session::put('notes_status',  $request->get('notes_status'));
            if ($request->get('notes_status') == 1) // choose contain only
            {
                $requests = $requests->where('comment', '!=', null);
            }

            if ($request->get('notes_status') == 2) // choose empty only
            {
                $requests = $requests->where('comment', null);
            }
        }
        else{
            Session::pull('notes_status');
        }
        if ($request->get('quality_recived')) {
            if ($request->get('quality_recived') == 1) // choose yes only
            {
                Session::put('quality_recived',  1);
                $requests = $requests->where(function ($query) {
                    $query->where('requests.class_id_quality', '!=', null)
                          ->orWhere('requests.quacomment', '!=', null);
                });
            }

            if ($request->get('quality_recived') == 2) // choose no only
            {
                Session::put('quality_recived',  2);
                $requests = $requests->where('requests.class_id_quality', null)->where('requests.quacomment', null);
            }
        }
        else{
            Session::pull('quality_recived');
        }
        if ($request->get('reqTypes') && is_array($request->get('reqTypes'))) {
            Session::put('reqTypes',  $request->get('reqTypes'));
            $requests = $requests->whereIn('requests.type', $request->get('reqTypes'));
        }
        else{
            Session::pull('reqTypes');
        }
        if ($request->get('req_status') && is_array($request->get('req_status'))) {
            Session::put('req_status',  $request->get('req_status'));
            if ($request->get('checkExisted') != null) {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
                $requests = $requests->where('requests.isSentSalesManager', 1);
            }
            else {
                $requests = $requests->whereIn('statusReq', $request->get('req_status'));
            }
        }
        else{
            Session::pull('req_status');
        }
        if ($request->get('pay_status') && is_array($request->get('pay_status'))) {
            Session::put('pay_status',  $request->get('pay_status'));
            $requests = $requests->whereIn('prepayments.payStatus', $request->get('pay_status'));
        }
        else{
            Session::pull('pay_status');
        }
        if ($request->get('customer_ids') && is_array($request->get('customer_ids'))) {
            Session::put('customer_ids',  $request->get('customer_ids'));
            $requests = $requests->whereIn('customer_id', $request->get('customer_ids'));
        }
        else{
            Session::pull('customer_ids');
        }
        if ($request->get('agents_ids') && is_array($request->get('agents_ids'))) {
            Session::put('agents_ids',  $request->get('agents_ids'));
            $requests = $requests->whereIn('requests.user_id', $request->get('agents_ids'));
        }
        else{
            Session::pull('agents_ids');
        }
        if ($request->get('quality_users') && is_array($request->get('quality_users'))) {
            Session::put('quality_users',  $request->get('quality_users'));
            # shall be recived by this user (is_recived_by_quality)
            $requests = $requests->where(function ($query) {
                $query->where('requests.class_id_quality', '!=', null)
                      ->orWhere('requests.quacomment', '!=', null);
            });
            # if it's recived by the quality
            $get_requests_id = $requests->pluck('requests.id')->toArray();
            $quality_requests_req_ids = DB::table('quality_reqs')->whereIn('user_id',$request->get('quality_users'))->whereIn('req_id',$get_requests_id)->pluck('req_id')->toArray();
            $requests = $requests->whereIn('requests.id', $quality_requests_req_ids);
        }
        else{
            Session::pull('quality_users');
        }
        if ($request->get('user_status') != 2) {
            $requests = $requests->where('users.status', $request->get('user_status'));
        }
        else{
            Session::pull('user_status');
        }
        if ($request->get('customer_salary')) {
            Session::put('customer_salary',  $request->get('customer_salary'));
            $requests = $requests->where('customers.salary', '>=', $request->get('customer_salary'));
        }
        else{
            Session::pull('customer_salary');
        }
        if ($request->get('customer_salary_to')) {
            Session::put('customer_salary_to',  $request->get('customer_salary_to'));
            $requests = $requests->where('customers.salary', '<=', $request->get('customer_salary_to'));
        }
        else{
            Session::pull('customer_salary_to');
        }
        if ($request->get('region_ip') && is_array($request->get('region_ip'))) {
            Session::put('region_ip',  $request->get('region_ip'));
            $requests = $requests->whereIn('customers.region_ip', $request->get('region_ip'));
        }
        else{
            Session::pull('region_ip');
        }
        if ($request->get('work_source') && is_array($request->get('work_source'))) {
            Session::put('work_source',  $request->get('work_source'));
            $requests = $requests->whereIn('customers.work', $request->get('work_source'));
        }
        else{
            Session::pull('work_source');
        }
        if ($request->get('source') && is_array($request->get('source'))) {
            Session::put('source',  $request->get('source'));
            $requests = $requests->whereIn('source', $request->get('source'));
        }
        else{
            Session::pull('source');
        }
        if ($request->get('collaborator') && is_array($request->get('collaborator'))) {
            Session::put('collaborator',  $request->get('collaborator'));
            $requests = $requests->whereIn('collaborator_id', $request->get('collaborator'));
        }
        else{
            Session::pull('collaborator');
        }
        if ($request->get('salary_source') && is_array($request->get('salary_source'))) {
            Session::put('salary_source',  $request->get('salary_source'));
            $requests = $requests->whereIn('customers.salary_id', $request->get('salary_source'));
        }
        else{
            Session::pull('salary_source');
        }
        if ($request->get('customer_phone')) {
            Session::put('customer_phone',  $request->get('customer_phone'));
            $mobile = DB::table('customers')->where('mobile', $request->get('customer_phone'));
            if ($mobile->count() == 0) {
                $mobiles = CustomersPhone::where('mobile', $request->get('customer_phone'))->first();
                if ($mobiles != null) {
                    $requests = $requests->where('customer_id', $mobiles->customer_id);
                }
            }
            else {
                $requests = $requests->where('customers.mobile', $request->get('customer_phone'));
            }
        }
        else{
            Session::pull('customer_phone');
        }
        if ($request->get('customer_birth')) {
            Session::put('customer_birth',  $request->get('customer_birth'));
            $requests = $requests->filter(function ($item) use ($request) {
                return date('yy-mm-dd', strtotime($item->birth_date)) == date('yy-mm-dd', strtotime($request->get('customer_birth')));
            });
        }
        else{
            Session::pull('customer_birth');
        }
        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
            'qu' => 'class_id_quality',
        ];
        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                Session::put($xs, $req);
                $requests = $requests->whereIn($xs, $req);
            }
            else{
                Session::pull($xs);
            }
        }
        return Datatables::of($requests)->setRowId(function ($row) {
            return $row->id;
        })->addColumn('user_status', function ($row) {
            return $row->user_status;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            if ($row->type == 'رهن-شراء') {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.morPurRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            else {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }
            if ($row->type != 'رهن-شراء' && $row->type != 'شراء-دفعة' && $row->statusReq != 16 && $row->statusReq != 15 && $row->statusReq != 14) {
                $data = $data.'<span class="item pointer"  id="move" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Move Req').'">
                                    <i class="fas fa-random"></i>
                                </span> ';
            }
            $data = $data.'<span class="item pointer"  id="addQuality" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add To Quality').'">
            <i class="fas fa-paper-plane"></i></span> ';
            $data = $data.'<span class="item pointer"  id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
            <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comment-alt"></i></a></span>';
            $data = $data.'<span class="item pointer" id="needActionReq" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Add to need action req').'">
            <a onclick="addReqToNeedActionReqFromAdmin('.$row->id.')"><i class="fas fa-directions"></i></a></span>';
            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;
            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('agent_date', function ($row) {
            $data = $row->agent_date;
            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('source', function ($row) {
            $data = DB::table('request_source')->where('id', $row->source)->first();
            if (empty($data)) {
                $data = $row->source;
            }
            else {
                $data = $data->value;
            }
            if ($row->collaborator_id != null) {
                $collInfo = DB::table('users')->where('id', $row->collaborator_id)->first();
                if ($collInfo->name != null && $collInfo->role != 13) {
                    $data = $data.' - '.$collInfo->name;
                }
                else {
                    $data = $data;
                }
            }
            return $data;
        })->editColumn('class_id_agent', function ($row) {
            $classifcations_sa = classifcation::where('id', $row->class_id_agent)->first(); // Khaled
            if ($classifcations_sa != null) {
                return $classifcations_sa->value;
            }
            else {
                return $row->class_id_agent;
            }
        })->editColumn('statusReq', function ($row) {
            if (($row->statusReq == 6 || $row->statusReq == 13) && $row->payStatus != null && ($row->payStatus == 1 || $row->payStatus == 3 || $row->payStatus == 4 || $row->payStatus == 5 || $row->payStatus == 6 || $row->payStatus == 7 || $row->payStatus == 10)) {
                return @$this->statusPay()[$row->payStatus] ?? $this->statusPay()[11];
            }
            else {
                return @$this->status()[$row->statusReq] ?? $this->status()[28];
            }
        })->addColumn('is_quality_recived', function ($row) {
            $data = '<div style="text-align: center;">';
            if ($row->class_id_quality != null || $row->quacomment != null) {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></span>';
            }
            $data = $data.'</div>';
            return $data;
        })->filter(function ($instance) use ($request) {
            $positive_classification_ids=Classification::where('type',1)->pluck('id')->toArray();
            $negative_classification_ids=Classification::where('type',0)->pluck('id')->toArray();

            if ($request->get('type_of_classification')){

                if ($request->get('type_of_classification') == '1') { // positive classification
                    Session::put('type_of_classification',  '1');
                    $instance->whereIn('class_id_agent', $positive_classification_ids);
                }
                
    
                if ($request->get('type_of_classification') == '0') { // negative classification
                    Session::put('type_of_classification',  '0');
                    $instance->whereIn('class_id_agent', $negative_classification_ids);
                }
            }
            else{
                Session::pull('type_of_classification');
            }
            
            

        })->editColumn('updated_at', fn($row) => $row->updated_at ? \Illuminate\Support\Carbon::make($row->updated_at)->format('Y-m-d g:i a') : '-')->rawColumns(['is_quality_recived', 'action'])->make(true);
    }


    public function remove_session_filter(Request $request)
    {
        Session::pull('req_date_from');
        Session::pull('req_date_to');
        Session::pull('complete_date_from');
        Session::pull('complete_date_to');
        Session::pull('updated_at_from');
        Session::pull('updated_at_to');
        Session::pull('founding_sources');
        Session::pull('app_downloaded');
        Session::pull('notes_status');
        Session::pull('quality_recived');
        Session::pull('reqTypes');
        Session::pull('req_status');
        Session::pull('pay_status');
        Session::pull('customer_ids');
        Session::pull('agents_ids');
        Session::pull('quality_users');
        Session::pull('user_status');
        Session::pull('customer_salary');
        Session::pull('customer_salary_to');
        Session::pull('region_ip');
        Session::pull('work_source');
        Session::pull('source');
        Session::pull('collaborator');
        Session::pull('salary_source');
        Session::pull('customer_phone');
        Session::pull('customer_birth');
        Session::pull('class_id_agent');
        Session::pull('class_id_sm');
        Session::pull('class_id_fm');
        Session::pull('class_id_mm');
        Session::pull('class_id_gm');
        Session::pull('class_id_quality');
        Session::pull('type_of_classification');
        return response()->json(['message'=>1 ]);
    }
}
