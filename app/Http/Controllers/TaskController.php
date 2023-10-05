<?php

namespace App\Http\Controllers;

use App\AgentQuality;
use App\task;
use App\task_content;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use View;

//to take date

class TaskController extends Controller
{
    public function __construct()
    {
        // This code to pass $notifys to  content layout rather that repeat this code on many functions
        // You can insert ( use View ) & add below code __construct on all controllers if needed
        View::composers([
            //attaches HomeComposer to pages
            'App\Composers\HomeComposer'     => ['layouts.content'],
            'App\Composers\ActivityComposer' => ['layouts.content'],
        ]);
    }

    public static function getContentOfTask($id)
    {
        return DB::table('task_contents')
            ->where('task_contents.task_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function taskReq($id)
    {

        $role = auth()->user()->role;

        if ($role != 7 && $role != 4) {

            $tasks = DB::table('requests')
                ->where(function ($query) {

                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id);
                    });
                })
                ->join('tasks', 'tasks.req_id', 'requests.id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->where('requests.id', $id)
                ->count();
        }
        else {
            $getQualityReqsIDS = DB::table('quality_reqs')
                ->where('req_id', $id)
                ->pluck('id')->toArray();

            if (count($getQualityReqsIDS) == 0) {
                $tasks = DB::table('requests')
                    ->join('tasks', 'tasks.req_id', 'requests.id')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->where('requests.id', $id)
                    ->where('recive.role', '!=', 5)
                    ->where('user.role', '!=', 5)
                    ->count();
            }

            else {
                $tasks = DB::table('requests')
                    ->join('tasks', 'tasks.req_id', 'requests.id')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->where('requests.id', $id)
                    ->where('recive.role', '!=', 5)
                    ->where('user.role', '!=', 5)
                    ->count();

                $tasks = $tasks + count($getQualityReqsIDS);
            }
        }

        $request = DB::table('requests')
            ->where('requests.id', $id)
            ->join('customers', 'customers.id', 'requests.customer_id')
            ->first();

        $task_status = $this->statusTask();

        return view('Task.taskReq.alltask', compact(
            'id',
            'tasks',
            'request',
            'task_status',
        ));
    }

    public function statusTask($getBy = 'empty')
    {
        $s = [
            0 => MyHelpers::admin_trans(auth()->user()->id, 'new task'),
            1 => MyHelpers::admin_trans(auth()->user()->id, 'open task'),
            2 => MyHelpers::admin_trans(auth()->user()->id, 'Under Processing'),
            3 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            4 => MyHelpers::admin_trans(auth()->user()->id, 'not completed'),
            5 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[4]);
    }

    public function taskReq_datatable(Request $request)
    {
        $role = auth()->user()->role;
        if ($role != 7 && $role != 4) {
            $requests = DB::table('requests')
                ->where(function ($query) {
                    $query->where(function ($query) {
                        $query->where('tasks.user_id', auth()->user()->id);
                    });
                    $query->orWhere(function ($query) {
                        $query->where('tasks.recive_id', auth()->user()->id);
                    });
                })
                ->where('requests.id', $request->get('id'))
                ->join('tasks', 'tasks.req_id', 'requests.id')
                ->join('users as user', 'user.id', 'tasks.user_id')
                ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                ->select('tasks.*', 'user.name as user_name', 'user.role as user_role', 'recive.name as recive_name');
        }
        else {
            $reqid = $request->get('id');

            $getQualityReqsIDS = DB::table('quality_reqs')
                ->where('req_id', $reqid)
                ->pluck('id')->toArray();
            //dd(count($getQualityReqsIDS));
            if (count($getQualityReqsIDS) == 0) {
                $requests = DB::table('requests')
                    ->join('tasks', 'tasks.req_id', 'requests.id')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->where('requests.id', $reqid)
                    ->where('recive.role', '!=', 5)
                    ->where('user.role', '!=', 5)
                    ->select('tasks.*', 'user.name as user_name', 'user.role as user_role', 'recive.name as recive_name');
            }
            else {
                //dd(count($getQualityReqsIDS));
                $taskReqs = DB::table('requests')
                    ->join('tasks', 'tasks.req_id', 'requests.id')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->where('requests.id', $reqid)
                    ->where('recive.role', '!=', 5)
                    ->where('user.role', '!=', 5)
                    ->pluck('tasks.id')->toArray();

                $taskQuality = DB::table('quality_reqs')
                    ->join('tasks', 'tasks.req_id', 'quality_reqs.id')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->whereIn('quality_reqs.id', $getQualityReqsIDS)
                    ->pluck('tasks.id')->toArray();

                $mergedArray = array_merge($taskQuality, $taskReqs);
                //dd($mergedArray);
                $requests = DB::table('tasks')
                    ->join('users as user', 'user.id', 'tasks.user_id')
                    ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
                    ->whereIn('tasks.id', $mergedArray)
                    ->select('tasks.*', 'user.name as user_name', 'user.role as user_role', 'recive.name as recive_name');
            }
        }

        return Datatables::of($requests)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            if (($row->user_id == auth()->user()->id) || ($row->recive_id == auth()->user()->id)) {
                $data = $data.
                    '<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
            <a href="'.route('all.show_users_task', $row->id).'"><i class="fas fa-eye"></i></a></span>';
            }

            if (($row->user_id == auth()->user()->id) && ($row->status == 0 || $row->status == 1)) {
                $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
            <a href="'.route('all.edittask', $row->id).'"><i class="fas fa-edit"></i></a></span>';
            }

            $data = $data.'</div>';
            return $data;
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')
                ->where('task_contents.task_id', $row->id)
                ->get()
                ->last();

            return $data->content;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')
                ->where('task_contents.task_id', $row->id)
                ->get()
                ->last();

            return $data->user_note;
        })->make(true);
    }

    public function addTaskPage($id)
    {
        $reqInfo = DB::table('requests')->where('id', $id)->first();
        $role = auth()->user()->role;

        $agent = DB::table('users')->where('id', $reqInfo->user_id)->get();
        $salesManager = DB::table('users')->where('id', $agent != null ? $agent[0]->manager_id : null)->get();
        $fundingManager = DB::table('users')->where('id', $salesManager != null ? $salesManager[0]->funding_mnager_id : null)->get();
        $mortgageManager = DB::table('users')->where('id', $salesManager != null ? $salesManager[0]->mortgage_mnager_id : null)->get();
        //  $qualityManager = DB::table('users')->where('status',  1)->where('id', '!=',  auth()->user()->id)->where('role',  5)->get();
        $q_m = 0;
        if($quality_req = DB::table('quality_reqs')->where('req_id', $reqInfo->id)->orderBy('id', 'desc')->first())
        {
            $q_m = 1;
            $qualityManager = DB::table('users')->where('id',  $quality_req->user_id)->get();
        }

        $restUsers = DB::table('users')->where('status', 1)->where('id', '!=', auth()->user()->id)->whereIn('role', [7, 4])->get();

        $allUsers = DB::table('users')->where('id', -1)->get(); //Fake collection :)

        if ($role != 0) {
            $allUsers = $allUsers->merge($agent ?: collect());
        }
        if ($role != 1) {
            $allUsers = $allUsers->merge($salesManager ?: collect());
        }
        if ($role != 2) {
            $allUsers = $allUsers->merge($fundingManager ?: collect());
        }
        if ($role != 3) {
            $allUsers = $allUsers->merge($mortgageManager ?: collect());
        }
        if ($role == 7 && $q_m) {
            $allUsers = $allUsers->merge($qualityManager ?: collect());
        }

        /*
            if ($role == 7)
            $allUsers =  $allUsers->merge($qualityManager ?: collect());
            */

        $allUsers = $allUsers->merge($restUsers ?: collect());
        if ($role == 13) {
            $allUsers = auth()->user()->agents;
        }
        if (($reqInfo->statusReq != 16 || $reqInfo->statusReq != 35)) {
            return view('Task.taskReq.addTaskPage', compact('id', 'allUsers'));
        }

        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you have task under process'));
    }

    public function task_post(Request $request)
    {

        $rules = [
            'content' => 'required',
            'recived' => 'required',
        ];

        $customMessages = [
            'required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $lastTaskID = DB::table('tasks')
            ->where('req_id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->where('recive_id', $request->recived)
            ->max('id');

        if ($lastTaskID) {
            $taskInfo = DB::table('tasks')
                ->where('id', $lastTaskID)
                ->first();

            if ($taskInfo->status == 0 || $taskInfo->status == 1 || $taskInfo->status == 2) {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you have task under process'));
            }
        }
        $user = DB::table('users')->where('id', $request->recived)->first();
        if($user->role == 5)
        {
            $quality_req = DB::table('quality_reqs')->where('req_id', $request->id)->first();
            $newTask = task::create([
                'req_id'    => $quality_req->id,
                'recive_id' => $request->recived,
                'user_id'   => auth()->user()->id,
                'status'   => 2
            ]);
            $newContent = task_content::create([
                'content'         => $request->get('content'),
                'date_of_content' => Carbon::now('Asia/Riyadh'),
                'task_id'         => $newTask->id,
                'task_contents_status'            => 1
            ]);

        }else{

            $newTask = task::create([
                'req_id'    => $request->id,
                'recive_id' => $request->recived,
                'user_id'   => auth()->user()->id,
            ]);
            $newContent = task_content::create([
                'content'         => $request->get('content'),
                'date_of_content' => Carbon::now('Asia/Riyadh'),
                'task_id'         => $newTask->id,
            ]);
        }


        #add to need to action requests
        $agentInfo = MyHelpers::getAgentInfo($request->recived);
        if ($agentInfo->status == 0) {
            $addNeedActionReq = MyHelpers::addNeedActionReq('مهمة جديدة', $request->recived, $request->id);
        }

        if (!$newContent) {
            return back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'com'));
        }
        else {
            //***********UPDATE DAILY PREFROMENCE */
            $userInfo = DB::table('users')->where('id', $request->recived)->where('role', 0)->first();
            if (!empty($userInfo)) {
                $agent_id = $request->recived;
                if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                    $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
                }
                MyHelpers::incrementDailyPerformanceColumn($agent_id, 'received_task',$newTask->id);
                //***********END - UPDATE DAILY PREFROMENCE */
            }
            return back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
        }
    }

    public function show_users_task($id)
    {

        $tasks = DB::table('tasks')
            ->where('tasks.id', $id)
            ->join('users as recived', 'recived.id', 'tasks.recive_id')
            ->join('users as sent', 'sent.id', 'tasks.user_id')
            ->select('recived.name as recname', 'recived.role as recrole', 'recived.id as recid', 'sent.id as sentid', 'sent.name as sentname', 'sent.role as sentrole', 'tasks.id', 'tasks.req_id', 'tasks.status')
            ->first();

        if (auth()->user()->role == 7 && $tasks->recrole == 0) {

            $currentTasks = DB::table('tasks')
                ->where('tasks.id', $id)
                ->join('users as recived', 'recived.id', 'tasks.recive_id')
                ->join('users as sent', 'sent.id', 'tasks.user_id')
                ->select('recived.name as recname', 'recived.role as recrole', 'recived.id as recid', 'sent.id as sentid', 'sent.name as sentname', 'sent.role as sentrole', 'tasks.id', 'tasks.req_id', 'tasks.status')
                ->first();

            $tasks = DB::table('tasks')
                ->where('tasks.req_id', $currentTasks->req_id)
                ->join('users as recived', 'recived.id', 'tasks.recive_id')
                ->join('users as sent', 'sent.id', 'tasks.user_id')
                ->where('recived.role', 0)
                //->leftjoin('task_contents', 'task_contents.task_id', 'tasks.id')
                ->select('recived.name as recname', 'recived.role as recrole', 'recived.id as recid', 'sent.id as sentid', 'sent.name as sentname', 'sent.role as sentrole', 'tasks.id', 'tasks.req_id', 'tasks.status')
                ->orderBy('tasks.created_at', 'asc')
                ->get();

            $getReqHistories = DB::table('request_histories')
                ->where('req_id', $currentTasks->req_id)
                ->where('title', 'نقل الطلب')
                ->orderBy('history_date', 'asc')
                ->get();

            //dd($getReqHistories);

            $task_content_last = DB::table('task_contents')
                ->where('task_contents.task_id', $id)
                ->get()
                ->last();

            $reqInfo = DB::table('requests')
                ->join('customers', 'customers.id', 'requests.customer_id')
                ->where('requests.id', $currentTasks->req_id)
                ->select('requests.*', 'customers.name', 'customers.mobile')
                ->first();

            if (empty($reqInfo)) {
                return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
            }

            $sentrole = $currentTasks->sentrole;

            return view('Task.taskReq.showtask-admin', compact('id', 'currentTasks', 'tasks', 'task_content_last', 'reqInfo', 'sentrole', 'getReqHistories'));
        }

        $task_contents = DB::table('task_contents')
            ->where('task_contents.task_id', $id)
            ->orderBy('created_at', 'asc')
            ->get();

        $task_content_last = DB::table('task_contents')
            ->where('task_contents.task_id', $id)
            ->get()
            ->last();

        if ($tasks->sentrole == 5) {

            if (auth()->user()->role == 0) {
                $reqInfo = DB::table('quality_reqs')
                    ->join('requests', 'requests.id', 'quality_reqs.req_id')
                    ->join('customers', 'customers.id', 'requests.customer_id')
                    ->where('quality_reqs.id', $tasks->req_id)
                    ->where('requests.user_id', auth()->user()->id)
                    ->select('quality_reqs.req_id', 'customers.name', 'customers.mobile')
                    ->first();
            }
            else {
                $reqInfo = DB::table('quality_reqs')
                    ->join('requests', 'requests.id', 'quality_reqs.req_id')
                    ->join('customers', 'customers.id', 'requests.customer_id')
                    ->where('quality_reqs.id', $tasks->req_id)
                    ->select('quality_reqs.req_id', 'customers.name', 'customers.mobile')
                    ->first();
            }
        }
        else {

            if (auth()->user()->role == 0) {
                $reqInfo = DB::table('requests')
                    ->join('customers', 'customers.id', 'requests.customer_id')
                    ->where('requests.id', $tasks->req_id)
                    ->where('requests.user_id', auth()->user()->id)
                    ->select('requests.*', 'customers.name', 'customers.mobile')
                    ->first();
            }
            else {
                $reqInfo = DB::table('requests')
                    ->join('customers', 'customers.id', 'requests.customer_id')
                    ->where('requests.id', $tasks->req_id)
                    ->select('requests.*', 'customers.name', 'customers.mobile')
                    ->first();
            }
        }

        if (empty($reqInfo)) {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
        }

        $sentrole = $tasks->sentrole;

        if(env('NEW_THEME') == '1'){
            return view('themes.theme1.Task.taskReq.showtask', compact('id', 'tasks', 'task_contents', 'task_content_last', 'reqInfo', 'sentrole'));
        }else{
            return view('Task.taskReq.showtask', compact('id', 'tasks', 'task_contents', 'task_content_last', 'reqInfo', 'sentrole'));
        }
    }

    public function edittask($id)
    {
        $tasks =
            DB::table('tasks')
                ->join('users as recived', 'recived.id', 'tasks.recive_id')
                ->where('tasks.id', $id)
                ->where('tasks.user_id', auth()->user()->id)
                ->whereIn('tasks.status', [0, 1])
                ->first();

        $task_content_last = DB::table('task_contents')
            ->where('task_contents.task_id', $id)
            ->get()
            ->last();

        if ($tasks) {
            return view('Task.taskReq.edittaskPage', compact('id', 'tasks', 'task_content_last'));
        }
        else {
            return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function edit_task_post(Request $request)
    {
        $rules = [
            'content' => 'required',

        ];

        $customMessages = [
            'content.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateContent = task_content::whereId($request->id)
            ->update([
                'content'         => $request->get('content'),
                'date_of_content' => Carbon::now('Asia/Riyadh'),
            ]);

        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
    }

    public function completeTask($id)
    {

        $tasks = DB::table('tasks')->where('id', $id)->first();
        $reqInfo = DB::table('requests')->where('id', $tasks->req_id)->first();

        if (!$reqInfo) {
            $updateTask = DB::table('tasks')->where('id', $id)
                ->where('status', 2)
                ->update([
                    'status' => 3,
                ]);
        }
        elseif (($reqInfo->statusReq != 16 || $reqInfo->statusReq != 35)) {

            $updateTask = DB::table('tasks')->where('id', $id)
                ->where('status', 2)
                ->update([
                    'status' => 3,
                ]);

            if ($updateTask == 0) {
                return redirect()->route('all.show_users_task', $id)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }
            else {
                return redirect()->route('all.show_users_task', $id)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
            }
        }
        else {
            return redirect()->route('all.show_users_task', $id)->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function notcompleteTask($id)
    {

        $tasks = DB::table('tasks')->where('id', $id)->first();
        $reqInfo = DB::table('requests')->where('id', $tasks->req_id)->first();

        if (!$reqInfo) {
            $updateTask = DB::table('tasks')
                ->where('id', $id)
                ->where('status', 2)
                ->update([
                    'status' => 4,
                ]);
        }

        elseif (($reqInfo->statusReq != 16 || $reqInfo->statusReq != 35)) {
            $updateTask = DB::table('tasks')
                ->where('id', $id)
                ->where('status', 2)
                ->update([
                    'status' => 4,
                ]);

            if ($updateTask == 0) {
                return redirect()->route('all.show_users_task', $id)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }

            else {
                return redirect()->route('all.show_users_task', $id)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
            }
        }
        else {
            return redirect()->route('all.show_users_task', $id)->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function canceleTask($id)
    {

        $tasks = DB::table('tasks')->where('id', $id)->first();
        $reqInfo = DB::table('requests')->where('id', $tasks->req_id)->first();

        if (!$reqInfo) {
            $updateTask = DB::table('tasks')
                ->where('id', $id)
                ->whereIn('status', [0, 1])
                ->update([
                    'status' => 5,
                ]);
        }

        elseif (($reqInfo->statusReq != 16 || $reqInfo->statusReq != 35)) {

            $updateTask = DB::table('tasks')
                ->where('id', $id)
                ->whereIn('status', [0, 1])
                ->update([
                    'status' => 5,
                ]);

            if ($updateTask == 0) {
                return redirect()->route('all.show_users_task', $id)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
            }

            else {
                return redirect()->route('all.show_users_task', $id)->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
            }
        }
        else {
            return redirect()->route('all.show_users_task', $id)->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'you do not have a premation to do that'));
        }
    }

    public function update_users_task_note(Request $request)
    {
        $rules = [
            'user_note' => 'required',
        ];

        $customMessages = [
            'user_note.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $updateTask = task::whereId($request->taskId)
            ->update([
                'status' => 2,
            ]);

        $updateContent = task_content::whereId($request->id)
            ->update([
                'date_of_note'         => Carbon::now('Asia/Riyadh'),
                'user_note'            => $request->user_note,
                'task_contents_status' => 1,
            ]);

        //***********UPDATE DAILY PREFROMENCE */
        $userInfo = DB::table('users')->where('id', auth()->user()->id)->where('role', 0)->first();

        if (!empty($userInfo)) {
            $agent_id = $userInfo->id;
            if (!MyHelpers::checkIfThereDailyPrefromenceRecord($agent_id)) {
                $newRecodDailyPrefromence = MyHelpers::addDailyPerformanceRecord($agent_id);
            }
            MyHelpers::incrementDailyPerformanceColumn($agent_id, 'replayed_task',$request->taskId);
            //***********END - UPDATE DAILY PREFROMENCE */
        }else{
            $task = task::find($request->taskId);

            $userInfo = DB::table('users')->where('id', $task->user_id)->where('role', 0)->first();
            if (!empty($userInfo)) {
                MyHelpers::incrementDailyPerformanceColumn( $userInfo->id, 'received_task',$request->taskId);
            }
        }

        return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Send Succesffuly'));
    }

    public function update_users_task_content(Request $request)
    {

        $rules = [
            'content' => 'required',
        ];
        $updateTask = task::find($request->id);

        $customMessages = [
            'content.required' => MyHelpers::admin_trans(auth()->user()->id, 'The filed is required'),
        ];

        $this->validate($request, $rules, $customMessages);

        $maxConent = task_content::where('task_id', $request->id)
            ->max('id');

        $updateContent = task_content::whereId($maxConent)
            ->update([
                'task_contents_status' => 2,
            ]);
        $updateTask = task::find($request->id);
        if(auth()->user()->role == 0){
            MyHelpers::incrementDailyPerformanceColumn(auth()->user()->id, 'replayed_task', $request->id);
        }
        $newContent = task_content::create([
            'content'         => $request->get('content'),
            'date_of_content' => Carbon::now('Asia/Riyadh'),
            'task_id'         => $request->id,
        ]);

        if (!$newContent) {
            return back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'com'));
        }

        else {
            return redirect()->back()->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function sentTask()
    {

        $userID = auth()->user()->id;

        if (auth()->user()->role == 0) {

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
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
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
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();

            $tasks = $tasks1->merge($tasks2 ?: collect());

        }

        else {
            $tasks = DB::table('tasks')
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
        }

        $tasks = $tasks->unique('id')->count();

        return view('Task.Tasks.senttask');
    }

    public function sentTask_datatable(Request $request)
    {

        $userID = auth()->user()->id;

        if (auth()->user()->role == 0) {
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
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
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
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();

            $tasks = $tasks1->merge($tasks2 ?: collect());

        }

        else {
            $tasks = DB::table('tasks')
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
        }

        $tasks = $tasks->unique('id');

        return Datatables::of($tasks)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer " id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').' '.MyHelpers::admin_trans(auth()->user()->id, 'the task').'">
            <a href="'.route('all.show_users_task', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            if (($row->user_id == auth()->user()->id) && ($row->status == 0 || $row->status == 1)) {
                $data = $data.'<span class="item pointer " id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                <a href="'.route('all.edittask', $row->id).'"><i class="fas fa-edit"></i></a></span>';
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

    public function recivedtask()
    {
        $userID = auth()->user()->id;


        if (auth()->user()->role == 0) {

            // $login_user= auth()->user(); //agent
            // return $login_user->task_to;
            // return $login_user->agent_tasks;


            $tasks = DB::table('tasks')
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

            $tasks2 = DB::table('tasks')
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
        }
        else {
            $tasks = DB::table('tasks')
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

            $tasks2 = DB::table('tasks')
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
        }

        $tasks = $tasks->merge($tasks2 ?: collect());

        $tasks = $tasks->unique('id')->count();

        return view('Task.Tasks.recivedtask', compact(
            'tasks',

        ));
    }

    public function recivedtask_datatable()
    {
        $userID = auth()->user()->id;

        if (auth()->user()->role == 0) {
            $tasks = DB::table('tasks')
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

            $tasks2 = DB::table('tasks')
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
        }
        else {
            $tasks = DB::table('tasks')
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

            $tasks2 = DB::table('tasks')
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
        }

        $tasks = $tasks->merge($tasks2 ?: collect());

        $tasks = $tasks->unique('id');

        // dd($all);
        return Datatables::of($tasks)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').' '.MyHelpers::admin_trans(auth()->user()->id, 'the task').'">
            <a href="'.route('all.show_users_task', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')
                ->where('task_contents.task_id', $row->id)
                ->get()
                ->last();

            return $data->content;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')
                ->where('task_contents.task_id', $row->id)
                ->get()
                ->last();

            return $data->user_note;
        })->make(true);
    }

    public function completedtask()
    {

        $userID = auth()->user()->id;

        if (auth()->user()->role == 0) {
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
        }

        else {
            $tasks = DB::table('tasks')
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
        }

        $tasks = $tasks->unique('id')->count();

        return view('Task.Tasks.completedtask', compact(
            'tasks',

        ));
    }

    public function completedtask_datatable()
    {
        $userID = auth()->user()->id;

        if (auth()->user()->role == 0) {
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
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
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
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();

            $tasks = $tasks1->merge($tasks2 ?: collect());
        }

        else {
            $tasks = DB::table('tasks')
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
                ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
                ->get();
        }

        $tasks = $tasks->unique('id');

        return Datatables::of($tasks)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').' '.MyHelpers::admin_trans(auth()->user()->id, 'the task').'">
            <a href="'.route('all.show_users_task', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')
                ->where('task_contents.task_id', $row->id)
                ->get()
                ->last();

            return $data->content;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')
                ->where('task_contents.task_id', $row->id)
                ->get()
                ->last();

            return $data->user_note;
        })->make(true);
    }

    // notify tasks start

    public function notifyTasks()
    {
        //
        $notifiys = DB::table('tasks')
            ->join('task_contents', 'task_contents.task_id', 'tasks.id')
            ->where(function($q){
                $q->where('user_id', auth()->user()->id)
                ->where('tasks.status', 2)
                ->where('task_contents_status', 1);
            })
            ->Orwhere(function($q){
                $q->where('recive_id', auth()->user()->id)
                ->whereIn('status', [2])
                ->where('task_contents_status', 0);
            })
            ->Orwhere(function($q){
                $q->where('recive_id', auth()->user()->id)
                ->where('status', 0);
            })
            ->select('tasks.*', 'task_contents.date_of_content', 'task_contents.id as task_id', 'task_contents.task_id', 'task_contents.content')
            ->distinct('task_id');
           //If I created the task


        $notifiys = $notifiys->count();
        return view('All.notify-tasks', compact(
            'notifiys',

        ));
    }

    public function notifyTasks_datatable()
    {


        $notifiys = DB::table('tasks')
        ->join('task_contents', 'task_contents.task_id', 'tasks.id')
        ->join('requests', 'requests.id', 'tasks.req_id')
        ->join('users as user', 'user.id', 'tasks.user_id')
        ->leftjoin('users as recive', 'recive.id', 'tasks.recive_id')
        ->join('customers', 'customers.id', 'requests.customer_id')
        ->where(function($q){
            $q->where('tasks.user_id', auth()->user()->id)
            ->where('tasks.status', 2)
            ->where('task_contents_status', 1);
        })
        ->Orwhere(function($q){
            $q->where('tasks.recive_id', auth()->user()->id)
            ->whereIn('tasks.status', [2])
            ->where('task_contents_status', 0);
        })
        ->Orwhere(function($q){
            $q->where('tasks.recive_id', auth()->user()->id)
            ->where('tasks.status', 0);
        })
        // ->select('tasks.*', 'task_contents.date_of_content', 'task_contents.task_id', 'task_contents.content')
        ->select('tasks.*', 'user.name as user_name', 'recive.name as recive_name', 'customers.mobile', 'customers.name', 'content', 'user_note', 'task_contents_status')
        ->distinct('task_id');

        /*return Datatables::of($notifiys)->setRowId(function ($notifiys) {
            return $notifiys->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';

                $data = $data.'<a href="'.route('all.show_users_task' , $row->id).'">
          <span class="item pointer" id="Open" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'"> <i class="fas fa-eye"></i></span></a>';


            $data = $data.'</div>';

            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->addColumn('value', function ($row) {
            if($row->status == 0)
            {
                $data = ' مهمة جديدة تمت إضافتها';
            }else{
                $data = "يوجد رد جديد على التذكرة";
            }
            return $data;
        })->make(true);*/
        return Datatables::of($notifiys)->addColumn('action', function ($row) {

            $data = '<div class="tableAdminOption">';

            $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').' '.MyHelpers::admin_trans(auth()->user()->id, 'the task').'">
            <a href="'.route('all.show_users_task', $row->id).'"><i class="fas fa-eye"></i></a></span>';

            $data = $data.'</div>';
            return $data;
        })->addColumn('notify_type', function ($row) {
            if($row->status == 0)
            {
                $data = ' مهمة جديدة تمت إضافتها';
            }else{
                $data = "يوجد رد جديد على التذكرة";
            }
            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })->editColumn('status', function ($row) {
            return $this->statusTask()[$row->status] ?? $this->statusTask()[5];
        })->editColumn('content', function ($row) {
            $data = DB::table('task_contents')
                ->where('task_contents.task_id', $row->id)
                ->get()
                ->last();

            return $data->content;
        })->editColumn('user_note', function ($row) {
            $data = DB::table('task_contents')
                ->where('task_contents.task_id', $row->id)
                ->get()
                ->last();

            return $data->user_note;
        })->make(true);
    }
    // notify tasks end
    // notify help desk start
    public function notifyhelpDesk()
    {
        //
        $notifiys = DB::table('notifications')->where('recived_id', (auth()->user()->id))
        ->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')
        ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
        ->where('notifications.status', 0) // new
        ->whereIn('notifications.type', [8]) // new help desk
        ->orderBy('notifications.id', 'DESC')
        ->select('notifications.*', 'customers.name');

        $notifiys = $notifiys->count();
        return view('All.notify-helpdesk', compact(
            'notifiys',

        ));
    }

    public function notifyhelpDesk_datatable()
    {


        $notifiys = DB::table('notifications')->where('recived_id', (auth()->user()->id))
        ->leftjoin('requests', 'requests.id', '=', 'notifications.req_id')
        ->leftjoin('customers', 'customers.id', '=', 'requests.customer_id')
        ->where('notifications.status', 0) // new
        ->whereIn('notifications.type', [8]) // new help desk
        ->orderBy('notifications.id', 'DESC')
        ->select('notifications.*', 'customers.name');

        return Datatables::of($notifiys)->setRowId(function ($notifiys) {
            return $notifiys->id;
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
                if(auth()->user()->role == '7')
                {
                    $data = $data.'<a href="'.url('admin/openhelpDeskPage/'.$row->req_id ).'">
              <span class="item pointer" id="Open" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'"> <i class="fas fa-eye"></i></span></a>';

            }else{
                $data = $data.'<a href="'.url('all/openhelpDeskPage/'.$row->req_id ).'">
          <span class="item pointer" id="Open" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'"> <i class="fas fa-eye"></i></span></a>';

                }


            $data = $data.'</div>';

            return $data;
        })->editColumn('created_at', function ($row) {
            $data = $row->created_at;

            return Carbon::parse($data)->format('Y-m-d g:ia');
        })
        ->make(true);

    }
    // notify help desk end
}
