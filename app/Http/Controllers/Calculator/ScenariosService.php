<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Scenario;
use Datatables;

class ScenariosService extends Controller
{
    public function scenarios()
    {
        $scenarios = Scenario::orderBy('sort_id', 'ASC')->get();
        return DataTables::of($scenarios)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name', function ($scenarios) {
                return $scenarios->name;
            })->addColumn('sort_id', function ($scenarios) {
                return $scenarios->sort_id;
            })
            ->addColumn('action', function ($scenarios) {
                $status = '<a href="'.route('admin.scenarios.users', $scenarios->id).'" class="btn btn-xs btn-warning btn-sm text-white tooltips mr-1 ml-1"><i class="fa fa-users"></i><span class="tooltipstext">المستخدمين</span></a>';

                return '<a onclick="editForm('.$scenarios->id.')" class="btn btn-xs btn-success btn-sm  text-white"><i class="fa fa-edit"></i></a> '.
                    '<a onclick="deleteData('.$scenarios->id.')" class="btn btn-xs btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a>'.
                    $status;

            })
            ->rawColumns(['idn', 'sort_id', 'name', 'action'])->make(true);
    }
}
