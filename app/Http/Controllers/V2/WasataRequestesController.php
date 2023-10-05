<?php

namespace App\Http\Controllers\V2;
use App\Http\Controllers\AppController;
use App\Models\ExternalCustomer;
use App\Models\WasataRequestes;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use MyHelpers;



class WasataRequestesController extends AppController
{
    public function index()
    {
        return view('V2.WasataRequestes.index');
    }

    public function indexDatatable()
    {
        // $requestes=WasataRequestes::with('user','externalCustomer')->get();
        $requestes=WasataRequestes::where('type', 'external');
        return Datatables::of($requestes)
            ->setRowId(fn(WasataRequestes $row) => $row->id)
            ->addColumn('action', function (WasataRequestes $row) {
                    $data = '<div id="tableAdminOption" class="tableAdminOption">';

                    $data = $data.'<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                       <a href="'.route('V2.ExternalCustomer.show', $row->external_customer_id).'"><i class="fas fa-eye"></i></a></span>';

                    $data = $data.'<span class="item pointer" id="tasks" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'tasks').'">
                    <a href="'.route('all.taskReq', $row->id).'"><i class="fas fa-comment-alt"></i></a></span>';

                    $data = $data.'</div>';
                    return $data;

            })->rawColumns(['action'])->make(true);
    }

}
