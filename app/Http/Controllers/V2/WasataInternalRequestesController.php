<?php

namespace App\Http\Controllers\V2;
use App\Http\Controllers\AppController;
use App\Models\ExternalCustomer;
use App\Models\WasataRequestes;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use MyHelpers;
use App\classifcation;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;



class WasataInternalRequestesController extends AppController
{
    public function index()
    {
        return view('V2.WasataInternalRequestes.index');
    }

    public function indexDatatable()
    {
        // $requestes=WasataRequestes::with('user','externalCustomer')->get();
        // $requestes=WasataRequestes::where('type', 'internal');
        $requests =WasataRequestes::join('requests', 'requests.id', 'wasata_requestes.req_id')
            ->join('users', 'users.id', 'wasata_requestes.funding_user_id')
            ->join('customers', 'customers.id', '=', 'requests.customer_id')
            ->select('requests.id as reqID', 'users.name as fundingName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent', 'requests.class_id_quality',
                'wasata_requestes.req_status',
                'requests.type', 'requests.collaborator_id', 'requests.source', 'wasata_requestes.created_at', 'wasata_requestes.comment AS bank_comment', 'requests.created_at as req_created_at')

                ->where('wasata_requestes.user_id', auth()->id())
                ->whereIn('wasata_requestes.req_type', ['send', 'push'])
                ->where('wasata_requestes.type', 'internal');

                return Datatables::of($requests)->addColumn('action', function ($row) {

                    $data = '<div class="tableAdminOption">';


                    // if ($row->type == 'رهن-شراء') {
                    //     $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
                    //         <a href="'.route('quality.manager.morPurRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
                    // }
                    // else {
                    //     $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->id(), 'Open').'">
                    //         <a href="'.route('quality.manager.fundingRequest', $row->id).'"><i class="fa fa-eye"></i></a></span>';
                    // }

                    /*
                    $data = $data . '<span class="item pointer" id="open" data-id="' . $row->id . '" data-toggle="tooltip" data-placement="top" title="' . MyHelpers::admin_trans(auth()->id(), 'Add to need action req') . '">
                         <a onclick="transalteData(' . $row->id . ')"><i class="fa fa-random"></i></a></span>';
                         */

                    return $data.'</div>';
                })->editColumn('created_at', function ($row) {
                    $data = $row->created_at;

                    return Carbon::parse($data)->format('Y-m-d g:ia');
                })->editColumn('status', function ($row) {
                    return @$row->req_status;
                })->editColumn('statusReq', function ($row) {
                    return $row->statusReq;
                })->editColumn('class_id_agent', function ($row) {

                    $classValue = classifcation::find($row->class_id_agent);
                    if ($classValue) {
                        return $classValue->value;
                    }
                    return $row->class_id_agent;
                })->editColumn('quacomment', function ($row) {



                    $data = '<textarea  title="'.$row->bank_comment.'"  id="reqComment'.$row->reqID.'" class="textarea"  onblur="savecomm('.$row->reqID.')" >'.$row->bank_comment.' </textarea>';

                    return $data;
                })->rawColumns(['action', 'quacomment'])->make(true);
    }

    public function updateComm(Request $request)
    {

        if ($request->reqComm != null) {
            $this->records($request->id, 'comment', $request->reqComm);
        }

        $row = DB::table('wasata_requestes')->where('req_id', $request->id)
                                            ->where('user_id', auth()->id() )
                                            ->first();
        $updateResult = DB::table('wasata_requestes')->where([
            ['id', '=', $row->id],
        ])->update([
            'req_status' => 1, //open
            'comment' => $request->reqComm
        ]);

        return response()->json(['status' => $updateResult, 'newComm' => $request->reqComm]);
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
}
