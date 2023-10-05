<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\RejectionsReason;
use Datatables;

class RejectionsService extends Controller
{
    public function rejections()
    {
        $asks = RejectionsReason::orderBy('created_at', 'ASC')->get();
        return DataTables::of($asks)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('title', function ($asks) {
                return $asks->title;
            })
            ->addColumn('action', function ($asks) {
                return '<a onclick="editForm('.$asks->id.')" class="btn btn-xs btn-success btn-sm  text-white"><i class="fa fa-edit"></i></a> '.
                    '<a onclick="deleteData('.$asks->id.')" class="btn btn-xs btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a>';

            })
            ->rawColumns(['idn', 'title', 'action'])->make(true);
    }
}
