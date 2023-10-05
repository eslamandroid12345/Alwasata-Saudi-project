<?php

namespace App\Http\Controllers\Reports;

use App\Ask;
use App\classifcation;
use App\DailyPerformances;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Datatables;
use Illuminate\Http\Request;
use PDF;
class RequestsService extends Controller
{
    public function pdf($id,$start,$end){
        $user = User::find($id);
        $data=[
            'user'    => $user,
            'start'    => $start,
            'end'    => $end,
        ];

        $pdf = PDF::loadView('Charts.daily-performance.pdf.statistics-pdf',$data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('Daily-'.$user->name.'-'.$id.'.pdf');

    }
    public function pdfStatus($id,$start=null,$end=null){
        $user = User::
            /*--------------------------------------------------------------------------
           | Statuses
           ---------------------------------------------------------------------------*/
            withCount(['requests AS newStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 0);
            }])
            ->withCount(['requests AS openStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 1);
            }])
            ->withCount(['requests AS archiveStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 2);
            }])
            ->withCount(['requests AS watingSMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 3);
            }])
            ->withCount(['requests AS rejectedSMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 4);
            }])
            ->withCount(['requests AS archiveSMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 5);
            }])
            ->withCount(['requests AS watingFMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 6);
            }])
            ->withCount(['requests AS rejectedFMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 7);
            }])
            ->withCount(['requests AS archiveFMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 8);
            }])
            ->withCount(['requests AS watingMMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 9);
            }])
            ->withCount(['requests AS rejectedMMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 10);
            }])
            ->withCount(['requests AS archiveMMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 11);
            }])
            ->withCount(['requests AS watingGMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 12);
            }])
            ->withCount(['requests AS rejectedGMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 13);
            }])
            ->withCount(['requests AS archiveGMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 14);
            }])
            ->withCount(['requests AS canceledStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 15);
            }])
            ->withCount(['requests AS completedStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 16);
            }])
            ->withCount(['requests AS fundingReportStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('isUnderProcFund', 1);
            }])
            ->withCount(['requests AS mortgageReportStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('isUnderProcMor', 1);
            }])->find($id);


        $data=[
          'user'    => $user,
          'start'    => $start,
          'end'    => $end,
        ];

        $pdf = PDF::loadView('Charts.requests.pdf.status-pdf',$data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('status-user-'.$user->name.'-'.$id.'.pdf');

    }

    public function pdfAll($id,$start=null,$end=null){

        $user = User::withCount(['requests AS newStatus' => function ($query) use($start,$end){
            $query->dateFilter($start,$end)->where('statusReq', 0);
            }])
            ->withCount(['requests AS openStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 1);
            }])
            ->withCount(['requests AS archiveStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 2);
            }])
            ->withCount(['requests AS watingSMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 3);
            }])
            ->withCount(['requests AS rejectedSMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 4);
            }])
            ->withCount(['requests AS archiveSMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 5);
            }])
            ->withCount(['requests AS watingFMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 6);
            }])
            ->withCount(['requests AS rejectedFMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 7);
            }])
            ->withCount(['requests AS archiveFMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 8);
            }])
            ->withCount(['requests AS watingMMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 9);
            }])
            ->withCount(['requests AS rejectedMMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 10);
            }])
            ->withCount(['requests AS archiveMMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 11);
            }])
            ->withCount(['requests AS watingGMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 12);
            }])
            ->withCount(['requests AS rejectedGMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 13);
            }])
            ->withCount(['requests AS archiveGMStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 14);
            }])
            ->withCount(['requests AS canceledStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 15);
            }])
            ->withCount(['requests AS completedStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('statusReq', 16);
            }])
            ->withCount(['requests AS fundingReportStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('isUnderProcFund', 1);
            }])
            ->withCount(['requests AS mortgageReportStatus' => function ($query) use($start,$end){
                $query->dateFilter($start,$end)->where('isUnderProcMor', 1);
            }])
            ->withCount(['requests AS received' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->where('is_followed', 0)->where('is_stared', 0)->whereIn('statusReq', [0, 1, 4]);
            }])
            ->withCount(['requests AS complete' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->whereNotIn('statusReq', [0, 1, 2, 4]);
            }])
            ->withCount(['requests AS star' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->where('is_stared', 1)->whereIn('statusReq', [0, 1, 4]);
            }])
            ->withCount(['requests AS following' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->where('is_followed', 1)->whereIn('statusReq', [0, 1, 4]);
            }])
            ->withCount(['requests AS archived' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->where('statusReq', 2);
            }])->
            with(['classifications' => function ($query)  use($start,$end){
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
        $pdf = PDF::loadView('Charts.requests.pdf.all-pdf',$data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('requests [تقرير الطلبات المجمع ]-'.$user->name.'-'.$id.'.pdf');
    }
    public function pdfClassification($id,$start=null,$end=null){
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
    public function pdfBasket($id,$start=null,$end=null){
        $user = User::withCount(['requests AS received' => function ($query) {
            $query->dateFilter(request('startdate'),request('enddate'))
                ->where('is_followed', 0)->where('is_stared', 0)->whereIn('statusReq', [0, 1, 4]);
            }])
            ->withCount(['requests AS complete' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->whereNotIn('statusReq', [0, 1, 2, 4]);
            }])
            ->withCount(['requests AS star' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->where('is_stared', 1)->whereIn('statusReq', [0, 1, 4]);
            }])
            ->withCount(['requests AS following' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->where('is_followed', 1)->whereIn('statusReq', [0, 1, 4]);
            }])
            ->withCount(['requests AS archived' => function ($query) {
                $query->dateFilter(request('startdate'),request('enddate'))
                    ->where('statusReq', 2);
            }])->find($id);
        $data=[
          'user'    => $user,
          'start'    => $start,
          'end'    => $end,
        ];

        $pdf = PDF::loadView('Charts.requests.pdf.basket-pdf',$data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('basket-user-'.$user->name.'-'.$id.'.pdf');

    }

    public function classification(Request $request)
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
            ->with(['classifications']);

        return DataTables::of($users)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name',function ($item) {
                return $item->name;
            })

            ->addColumn('classifications',function ($item) {

                return $item->classifications()->when(\request("startdate") && !\request("enddate"), function ($q, $v) {
                        $q->where('requests.created_at','>=', \request("startdate"));
                    })->when(\request("enddate") && !\request("startdate"), function ($q, $v){
                        $q->where('requests.created_at','<=' ,\request("enddate"));
                    })->when(\request("enddate") && \request("startdate"), function ($q, $v) {
                        $q->whereBetween('requests.created_at', [\request("startdate"), \request("enddate")]);
                    })->select("class_id_agent",\DB::raw("COUNT(*) as counts"))->groupBy("class_id_agent")->get();
            })
            ->addColumn('action',function ($item) {
                /*
                <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير الكلي">
                    <a href="'.route('requests.statistics.all.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-list"></i></a></span>

                */
                $data = '<div class="tableAdminOption">
                    <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير المختصر">
                    <a href="'.route('requests.statistics.classification.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-file-download"></i></a></span>
                     </div>';
                return $data;
            })

            ->rawColumns([
                'action', 'idn','name','classifications'
            ])->make(true);
    }
    public function classificationSum(Request $request)
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
            ->with(['classifications'])->get();

        $data= 0;
        foreach ($users  as $item) {
            $data += $item->classifications()->when(\request("startdate") && !\request("enddate"), function ($q, $v) {
                $q->where('requests.created_at','>=', \request("startdate"));
            })->when(\request("enddate") && !\request("startdate"), function ($q, $v){
                $q->where('requests.created_at','<=' ,\request("enddate"));
            })->when(\request("enddate") && \request("startdate"), function ($q, $v) {
                $q->whereBetween('requests.created_at', [\request("startdate"), \request("enddate")]);
            })->where("class_id_agent",$request->classification_id)->groupBy("class_id_agent")->count();
        }

        return response()->json(['data' => $data]);
    }
    public function status(Request $request)
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

        if ($request->has("training_id")){
            $request->merge([
                'adviser_id'    => auth()->user()->trainings->pluck('agent_id')->toArray()
            ]);
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
            })
            /*--------------------------------------------------------------------------
           | Statuses
           ---------------------------------------------------------------------------*/
            ->withCount(['new_status','open_status','archive_status','waiting_sm_status', 'rejected_sm_status',
                         'archive_sm_status','waiting_fm_status','rejected_fm_status','archive_fm_status', 'waiting_mm_status',
                         'rejected_mm_status','archive_mm_status','waiting_gm_status','rejected_gm_status', 'archive_gm_status',
                         'canceled_status','completed_status','funding_report_status','mortgage_report_status']);

        return DataTables::of($users)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name',function ($item) {
                return $item->name;
            })
            ->addColumn('newStatus',function ($item) {
                return $item->new_status_count ??0;
            })
            ->addColumn('openStatus',function ($item) {
                return $item->open_status_count ??0;
            })
            ->addColumn('archiveStatus',function ($item) {
                return $item->archive_status_count ??0;
            })
            ->addColumn('watingSMStatus',function ($item) {
                return $item->waiting_sm_status_count ??0;
            })
            ->addColumn('rejectedSMStatus',function ($item) {
                return $item->rejected_sm_status_count ??0;
            })
            ->addColumn('archiveSMStatus',function ($item) {
                return $item->archive_sm_status_count ??0;
            })
            ->addColumn('watingFMStatus',function ($item) {
                return $item->waiting_fm_status_count ??0;
            })
            ->addColumn('rejectedFMStatus',function ($item) {
                return $item->rejected_fm_status_count ??0;
            })
            ->addColumn('archiveFMStatus',function ($item) {
                return $item->archive_fm_status_count ??0;
            })
            ->addColumn('watingMMStatus',function ($item) {
                return $item->waiting_mm_status_count ??0;
            })
            ->addColumn('rejectedMMStatus',function ($item) {
                return $item->rejected_mm_status_count ??0;
            })
            ->addColumn('archiveMMStatus',function ($item) {
                return $item->archive_mm_status_count ??0;
            })
            ->addColumn('watingGMStatus',function ($item) {
                return $item->wating_gm_status_count ??0;
            })
            ->addColumn('rejectedGMStatus',function ($item) {
                return $item->rejected_gm_status_count ??0;
            })
            ->addColumn('archiveGMStatus',function ($item) {
                return $item->archive_gm_status_count ??0;
            })
            ->addColumn('canceledStatus',function ($item) {
                return $item->canceled_status_count ??0;
            })
            ->addColumn('completedStatus',function ($item) {
                return $item->completed_status_count ??0;
            })
            ->addColumn('fundingReportStatus',function ($item) {
                return $item->funding_report_status_count ??0;
            })
            ->addColumn('mortgageReportStatus',function ($item) {
                return $item->mortgage_report_status_count ??0;
            })
            ->addColumn('action',function ($item) {
                /*<span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير الكلي">
                    <a href="'.route('requests.statistics.all.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-list"></i></a></span>
                    */
                return '<div class="tableAdminOption">
                    <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير المختصر">
                    <a href="'.route('requests.statistics.status.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-file-download"></i></a></span>
                    </div>';
            })
            ->rawColumns([
                'action',
                'idn','name','newStatus','openStatus','archiveStatus','watingSMStatus',
                'rejectedSMStatus','archiveSMStatus','watingFMStatus','rejectedFMStatus','archiveFMStatus',
                'watingMMStatus','rejectedMMStatus','archiveMMStatus','watingGMStatus','rejectedGMStatus',
                'archiveGMStatus','canceledStatus','completedStatus','fundingReportStatus','mortgageReportStatus'
            ])->make(true);
    }
    public function basket(Request $request)
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
        if ($request->has("training_id")){
            $request->merge([
                'adviser_id'    => auth()->user()->trainings->pluck('agent_id')->toArray()
            ]);
        }
        $users = User::where('role', 0)
            ->when($request->status_user!=2, function ($q, $v) use ($request) {
                $q->where('status', $request->status_user);
            })->
            when($request->has("adviser_id") && $request->adviser_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('id', $request->adviser_id);
            })->
            when($request->has("agent_id"), function ($q, $v) use ($request) {
                $q->where('id', $request->agent_id);
            })->
            when($request->has("manager_id") &&$request->manager_id != 0, function ($q, $v) use ($request) {
                $q->whereIn('manager_id', $request->manager_id);
            })
            ->withCount(['completes', 'archived', 'following', 'star', 'received']);
/*

 * */
        return DataTables::of($users)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name',function ($item) {
                return $item->name;
            })
            ->addColumn('complete',function ($item) {
                return $item->completes_count ??0;
            })
            ->addColumn('received',function ($item) {
                return $item->received_count ??0;
            })
            ->addColumn('star',function ($item) {
                return $item->star_count ??0;
            })
            ->addColumn('following',function ($item) {
                return $item->following_count ??0;
            })
            ->addColumn('archived',function ($item) {
                return $item->archived_count ??0;
            })
            ->addColumn('action',function ($item) {
                /*
                 <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير الكلي">
                    <a href="'.route('requests.statistics.all.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-list"></i></a></span>
                 */
                $data = '<div class="tableAdminOption">
                    <span class="item pointer" data-toggle="tooltip" data-placement="top" title="تحميل التقرير المختصر">
                    <a href="'.route('requests.statistics.basket.pdf', [$item->id,request('startdate'),request('enddate')]).'"><i class="fa fa-file-download"></i></a></span>
                     </div>';
                return $data;
            })
            ->rawColumns([
               'action', 'idn','name','complete', 'archived', 'following', 'star', 'received'
            ])->make(true);
    }
    public function statusSum(Request $request)
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

        if ($request->has("training_id")){
            $request->merge([
                'adviser_id'    => auth()->user()->trainings->pluck('agent_id')->toArray()
            ]);
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
            })
            /*--------------------------------------------------------------------------
           | Statuses
           ---------------------------------------------------------------------------*/
            ->withCount([$request->key])->pluck($request->key.'_count')->sum();
        return response()->json(['data' => $users]);
    }

}
