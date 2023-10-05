<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use App\Models\FireEvent;
use App\Property;

class FireEventController extends Controller
{
    public function index()
    {
       return view('Admin.FireEvent.index');
    }
    //----------------------------------------------------------------------------------------
    public function datatable()
    {
        //get all types data
         $data=FireEvent::where('related_type', '\\App\\Models\\Property')->orderBy('id', 'DESC');

        //use datatables (yajra) to handel this data
        return DataTables::of($data)
            ->addColumn('customer_name', function ($row) {
                //return if this type is hide or show
                return $row->Customer->name ?? '';
            })->addColumn('user_name', function ($row) {
                //return if this type is hide or show
                return $row->User->name ?? ($row->User->name_for_admin ?? '');
            })->addColumn('real_state', function ($row) {
                //return if this type is hide or show
                return $row->related_id ?? '';
            })
            ->addColumn('action', function ($row) {
                return view('Admin.FireEvent.datatable.action', compact('row'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

    public function show($id)
    {
        $property = Property::findOrfail($id);
       return view('Admin.FireEvent.show', compact('property'));
    }
}
