<?php

namespace App\Http\Controllers\Reports;

use App\Ask;
use App\classifcation;
use App\DailyPerformances;
use App\Http\Controllers\Controller;
use App\Model\PendingRequest;
use App\Models\RequestHistory;
use App\User;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use PDF;
class SourcesService extends Controller
{
    public function pdf($id,$start=null,$end=null){
        $user = User::with(['classifications' => function ($query) use($start,$end) {
                $query->when($start && !$end, function ($q, $v) use($start){
                    $q->where('requests.created_at','>=', $start);
                })->when($end && !$start, function ($q, $v) use($end){
                    $q->where('requests.created_at','<=' ,$end);
                })->when($end && $start, function ($q, $v) use($end,$start){
                    $q->whereBetween('requests.created_at', [$start, $end]);
                })->select("class_id_agent",\DB::raw("COUNT(*) as counts"))->groupBy("class_id_agent")->get();
            }])->find($id);

        $classifications = classifcation::where('user_role', 0)->get();

        $data=[
          'user'    => $user,
          'start'    => $start,
          'end'    => $end,
          'classifications'    => $classifications,
        ];


      // return view('Charts.requests.pdf.classifications-pdf',$data);
        $pdf = PDF::loadView('Charts.requests.pdf.classifications-pdf',$data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('requests-classifications [التصنيفات]-'.$user->name.'-'.$id.'.pdf');

    }
    protected $created;
    public function sources(Request $request)
    {

        ini_set('memory_limit', '-1');
        if ($request->has("manager_id") && is_array($request->manager_id)){
            if (in_array('allManager', $request->manager_id)) {
                $request->merge([
                    "manager_id"   => 0
                ]);
            }
        }else{
            if ($request->has("sales_id")) {
                $request->merge([
                    "manager_id"   => [$request->sales_id],
                ]);
            }
        }
        if ($request->has("adviser_id")){
            if (in_array("0", $request->adviser_id)) {

                $request->merge([
                    "adviser_id"   => 0
                ]);
            }
        }
        if ($request->has("agent_id")) {
            $request->merge([
                "agent_id"   => $request->agent_id,
                "status_user"   => 2
            ]);
        }
        if ($request->has("sales_id")) {
            $request->merge([
                "sales_id"   => $request->sales_id,
            ]);
        }
        if ($request->has("training_id")){
            $request->merge([
                'adviser_id'    => auth()->user()->trainings->pluck('agent_id')->toArray()
            ]);
        }

        $start = Carbon::parse(date('Y-m-d'));
        $end = Carbon::parse(date('Y-m-d', strtotime('-30 day')));

        if ($request['startdate'] != null && $request['enddate'] != null) {
            $start = Carbon::parse($request['startdate']);
            $end = Carbon::parse($request['enddate']);
        }

        $dates = [];
        for ($d = $start; $d->lte($end); $d->addDay()) {
            $dates[] = $d->format('Y-m-d');
        }
        $users = User::where('role', 0)
            ->when($request->status_user!=2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })->
            when($request->adviser_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('id', $request->adviser_id);
            })->
            when($request->manager_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('manager_id', $request->manager_id);
            })->
            when($request->has("agent_id"), function ($q, $v) use ($request) {
                $q->where('id', $request->agent_id);
            })->
            when($request->has("sales_id"), function ($q, $v) use ($request) {
                $q->where('manager_id', $request->sales_id);
            })
            /*--------------------------------------------------------------------------
           | Classifications
           ---------------------------------------------------------------------------*/
            ->with(['sources','collaborates']);

            $this->created = $dates;
        return DataTables::of($users)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name',function ($item) {
                return $item->name;
            })

            ->addColumn('sources',function ($item) {

                return $item->sources()
                    ->whereIn(\DB::raw("DATE(requests.created_at)"),$this->created)
                    ->select("requests.source",\DB::raw("COUNT(*) as counts"))
                    ->groupBy("requests.source")->get();
            })
            ->addColumn('collaborates',function ($item) {

                return $item->collaborates()
                    ->whereIn(\DB::raw("DATE(requests.created_at)"),$this->created)
                    ->select("collaborator_id",\DB::raw("COUNT(*) as counts"))
                    ->groupBy("collaborator_id")->get();
            })
            ->addColumn('action',function ($item) {
                return "#";
                /*
                <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير الكلي">
                    <a href="'.route('requests.statistics.all.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-list"></i></a></span>

                */
             /*   $data = '<div class="tableAdminOption">
                    <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير المختصر">
                    <a href="'.route('requests.statistics.classification.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-file-download"></i></a></span>
                     </div>';
                return $data;*/
            })

            ->rawColumns([
                'action', 'idn','name','classifications'
            ])->make(true);
    }

    protected $ids=[];
    public function sourcesWsata(Request $request)
    {

        ini_set('memory_limit', '-1');
        if ($request->has("manager_id") && is_array($request->manager_id)){
            if (in_array('allManager', $request->manager_id)) {
                $request->merge([
                    "manager_id"   => 0
                ]);
            }
        }else{
            if ($request->has("sales_id")) {
                $request->merge([
                    "manager_id"   => [$request->sales_id],
                ]);
            }
        }
        if ($request->has("adviser_id")){
            if (in_array("0", $request->adviser_id)) {

                $request->merge([
                    "adviser_id"   => 0
                ]);
            }
        }
        if ($request->has("agent_id")) {
            $request->merge([
                "agent_id"   => $request->agent_id,
                "status_user"   => 2
            ]);
        }
        if ($request->has("sales_id")) {
            $request->merge([
                "sales_id"   => $request->sales_id,
            ]);
        }
        if ($request->has("training_id")){
            $request->merge([
                'adviser_id'    => auth()->user()->trainings->pluck('agent_id')->toArray()
            ]);
        }

        $start = Carbon::parse(date('Y-m-d'));
        $end = Carbon::parse(date('Y-m-d', strtotime('-30 day')));

        if ($request['startdate'] != null && $request['enddate'] != null) {
            $start = Carbon::parse($request['startdate']);
            $end = Carbon::parse($request['enddate']);
        }

        $dates = [];
        for ($d = $start; $d->lte($end); $d->addDay()) {
            $dates[] = $d->format('Y-m-d');
        }

        $users = User::where('role', 0)
            ->when($request->status_user!=2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })->
            when($request->adviser_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('id', $request->adviser_id);
            })->
            when($request->manager_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('manager_id', $request->manager_id);
            })->
            when($request->has("agent_id"), function ($q, $v) use ($request) {
                $q->where('id', $request->agent_id);
            })->
            when($request->has("sales_id"), function ($q, $v) use ($request) {
                $q->where('manager_id', $request->sales_id);
            })->pluck("id")->toArray();
        $this->ids = $users;

        return DataTables::of($dates)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name',function ($item) {
                return $item;
            })

            ->addColumn('sources',function ($item) {

                return \App\Models\Request::whereIn("user_id",$this->ids)
                    ->whereDate("created_at",$item)
                    ->select("source",\DB::raw("COUNT(*) as counts"))
                    ->groupBy("source")->get();


            })
            ->addColumn('collaborates',function ($item) {

               return \App\Models\Request::whereIn("user_id",$this->ids)
                   ->whereDate("created_at",$item)
                   ->select("collaborator_id",\DB::raw("COUNT(*) as counts"))
                   ->groupBy("collaborator_id")->get();
            })
            ->addColumn('action',function ($item) {
                return "#";
                /*
                <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير الكلي">
                    <a href="'.route('requests.statistics.all.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-list"></i></a></span>

                */
              /*  $data = '<div class="tableAdminOption">
                    <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير المختصر">
                    <a href="'.route('requests.statistics.classification.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-file-download"></i></a></span>
                     </div>';
                return $data;*/
            })

            ->rawColumns([
                'action', 'idn','name','classifications'
            ])->make(true);
    }
    public function sourcesPending(Request $request)
    {

        ini_set('memory_limit', '-1');
        if ($request->has("manager_id") && is_array($request->manager_id)){
            if (in_array('allManager', $request->manager_id)) {
                $request->merge([
                    "manager_id"   => 0
                ]);
            }
        }else{
            if ($request->has("sales_id")) {
                $request->merge([
                    "manager_id"   => [$request->sales_id],
                ]);
            }
        }
        if ($request->has("adviser_id")){
            if (in_array("0", $request->adviser_id)) {

                $request->merge([
                    "adviser_id"   => 0
                ]);
            }
        }
        if ($request->has("agent_id")) {
            $request->merge([
                "agent_id"   => $request->agent_id,
                "status_user"   => 2
            ]);
        }
        if ($request->has("sales_id")) {
            $request->merge([
                "sales_id"   => $request->sales_id,
            ]);
        }
        if ($request->has("training_id")){
            $request->merge([
                'adviser_id'    => auth()->user()->trainings->pluck('agent_id')->toArray()
            ]);
        }

        $start = Carbon::parse(date('Y-m-d', strtotime('-365 day')));
        $end = Carbon::parse(date('Y-m-d', strtotime('-345 day')));

        if ($request['startdate'] != null && $request['enddate'] != null) {
            $start = Carbon::parse($request['startdate']);
            $end = Carbon::parse($request['enddate']);
        }

        $dates = [];
        for ($d = $start; $d->lte($end); $d->addDay()) {
            $dates[] = $d->format('Y-m-d');
        }

        $users = User::where('role', 0)
            ->when($request->status_user!=2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })->
            when($request->adviser_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('id', $request->adviser_id);
            })->
            when($request->manager_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('manager_id', $request->manager_id);
            })->
            when($request->has("agent_id"), function ($q, $v) use ($request) {
                $q->where('id', $request->agent_id);
            })->
            when($request->has("sales_id"), function ($q, $v) use ($request) {
                $q->where('manager_id', $request->sales_id);
            })->pluck("id")->toArray();
        $this->ids = $users;

        return DataTables::of($dates)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name',function ($item) {
                return $item;
            })

            ->addColumn('sources',function ($item) {

                return PendingRequest::whereIn("user_id",$this->ids)
                    ->whereDate('created_at', $item)
                    ->select("source",\DB::raw("COUNT(*) as counts"))
                    ->groupBy("source")->get();
            })
            ->addColumn('collaborates',function ($item) {

                return PendingRequest::whereIn("user_id",$this->ids)->whereDate('created_at', $item)
                    ->select("collaborator_id",\DB::raw("COUNT(*) as counts"))
                    ->groupBy("collaborator_id")->get();
            })
            ->addColumn('action',function ($item) {
                return "#";
                /*
                <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير الكلي">
                    <a href="'.route('requests.statistics.all.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-list"></i></a></span>

                */
                /*  $data = '<div class="tableAdminOption">
                      <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير المختصر">
                      <a href="'.route('requests.statistics.classification.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-file-download"></i></a></span>
                       </div>';
                  return $data;*/
            })

            ->rawColumns([
                'action', 'idn','name','classifications'
            ])->make(true);
    }
    public function sourcesSum(Request $request)
    {
        ini_set('memory_limit', '-1');
        if ($request->has("manager_id") && is_array($request->manager_id)){
            if (in_array('allManager', $request->manager_id)) {
                $request->merge([
                    "manager_id"   => 0
                ]);
            }
        }else{
            if ($request->has("sales_id")) {
                $request->merge([
                    "manager_id"   => [$request->sales_id],
                ]);
            }
        }
        if ($request->has("adviser_id")){
            if (in_array("0", $request->adviser_id)) {

                $request->merge([
                    "adviser_id"   => 0
                ]);
            }
        }
        if ($request->has("agent_id")) {
            $request->merge([
                "agent_id"   => $request->agent_id,
                "status_user"   => 2
            ]);
        }
        if ($request->has("sales_id")) {
            $request->merge([
                "sales_id"   => $request->sales_id,
            ]);
        }
        if ($request->has("training_id")){
            $request->merge([
                'adviser_id'    => auth()->user()->trainings->pluck('agent_id')->toArray()
            ]);
        }
        $start = Carbon::parse(date('Y-m-d'));
        $end = Carbon::parse(date('Y-m-d', strtotime('-30 day')));

        if ($request['startdate'] != null && $request['enddate'] != null) {
            $start = Carbon::parse($request['startdate']);
            $end = Carbon::parse($request['enddate']);
        }

        $dates = [];
        for ($d = $start; $d->lte($end); $d->addDay()) {
            $dates[] = $d->format('Y-m-d');
        }
        $users = User::where('role', 0)
            ->when($request->status_user!=2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })->
            when($request->adviser_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('id', $request->adviser_id);
            })->
            when($request->manager_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('manager_id', $request->manager_id);
            })->
            when($request->has("agent_id"), function ($q, $v) use ($request) {
                $q->where('id', $request->agent_id);
            })->
            when($request->has("sales_id"), function ($q, $v) use ($request) {
                $q->where('manager_id', $request->sales_id);
            })->pluck("id")->toArray();
            /*--------------------------------------------------------------------------
           | Classifications
           ---------------------------------------------------------------------------*/


        $data= 0;

        if ($request->type == "sources"){
            $data = \App\Models\Request::whereIn("user_id",$users)
                ->whereIn(\DB::raw("DATE(created_at)"),$dates)
                ->where("source",explode("source",$request->source)[1])
                ->groupBy("source")->count();
        }else{


            $data = \App\Models\Request::whereIn("user_id",$users)
                ->whereIn(\DB::raw("DATE(created_at)"),$dates)
                ->where("collaborator_id",explode("user",$request->collaborator_id)[1])
                ->groupBy("collaborator_id")->count();

        }

        return response()->json(['data' => $data]);
    }
    public function sourcesWsataSum(Request $request)
    {
        ini_set('memory_limit', '-1');
        if ($request->has("manager_id") && is_array($request->manager_id)){
            if (in_array('allManager', $request->manager_id)) {
                $request->merge([
                    "manager_id"   => 0
                ]);
            }
        }else{
            if ($request->has("sales_id")) {
                $request->merge([
                    "manager_id"   => [$request->sales_id],
                ]);
            }
        }
        if ($request->has("adviser_id")){
            if (in_array("0", $request->adviser_id)) {

                $request->merge([
                    "adviser_id"   => 0
                ]);
            }
        }
        if ($request->has("agent_id")) {
            $request->merge([
                "agent_id"   => $request->agent_id,
                "status_user"   => 2
            ]);
        }
        if ($request->has("sales_id")) {
            $request->merge([
                "sales_id"   => $request->sales_id,
            ]);
        }
        if ($request->has("training_id")){
            $request->merge([
                'adviser_id'    => auth()->user()->trainings->pluck('agent_id')->toArray()
            ]);
        }

        $start = Carbon::parse(date('Y-m-d'));
        $end = Carbon::parse(date('Y-m-d', strtotime('-30 day')));

        if ($request['startdate'] != null && $request['enddate'] != null) {
            $start = Carbon::parse($request['startdate']);
            $end = Carbon::parse($request['enddate']);
        }

        $dates = [];
        for ($d = $start; $d->lte($end); $d->addDay()) {
            $dates[] = $d->format('Y-m-d');
        }

        $users = User::where('role', 0)
            ->when($request->status_user!=2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })->
            when($request->adviser_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('id', $request->adviser_id);
            })->
            when($request->manager_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('manager_id', $request->manager_id);
            })->
            when($request->has("agent_id"), function ($q, $v) use ($request) {
                $q->where('id', $request->agent_id);
            })->
            when($request->has("sales_id"), function ($q, $v) use ($request) {
                $q->where('manager_id', $request->sales_id);
            })->pluck("id")->toArray();
        $this->ids = $users;

        if ($request->model == "PendingRequest"){
            $model = PendingRequest::query();
        }else{
            $model = \App\Models\Request::query();
        }
        if ($request->type == "sources"){

            $data = $model->whereIn("user_id",$this->ids)
                ->whereIn(\DB::raw("DATE(created_at)"),$dates)
                ->where("source",explode("source",$request->source)[1])
                ->groupBy("source")
                ->count();
        }else{

            $data =$model->whereIn("user_id",$this->ids)->whereIn(\DB::raw("DATE(created_at)"),$dates)->where("collaborator_id",explode("user",$request->collaborator_id)[1])
                ->groupBy("collaborator_id")->count();

        }

        return response()->json(['data' => $data]);
    }

    // Measurment Tools

    public function measurement_tools(Request $request)
    {

        ini_set('memory_limit', '-1');
        $rows = RequestHistory::where('class_id_agent', 62)->where('title', '<>', 'نقل الطلب')->orderBy('id', 'desc');
        return DataTables::of($rows)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name',function ($item) {
                return  ($item->title == 'move_to_freeze')? ($item->user->name ?? '') : ($item->receiver->name ?? '');
            })
            ->addColumn('customer',function ($item) {
                return $item->request->customer->name ?? '';
            })
            ->addColumn('classification',function ($item) {
                return classifcation::find($item->class_id_agent)->value;
            })
            ->addColumn('reply',function ($item) {
                return ($item->title == 'move_to_freeze')? $item->content : (($item->title == 'نقل الطلب')? $item->title . ' ' . $item->content : $item->title);
            })


            ->rawColumns([
                'action', 'idn','name','classifications'
            ])->make(true);
    }
}
