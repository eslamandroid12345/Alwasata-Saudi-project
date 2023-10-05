<?php

namespace App\Http\Controllers\Admin;

use App\Ask;
use App\Http\Controllers\Controller;
use Datatables;

class AsksService extends Controller
{
    public function asks()
    {
        $asks = Ask::orderBy('created_at', 'ASC')->get();
        return DataTables::of($asks)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('question', function ($asks) {
                return $asks->question;
            })
            ->addColumn('active', function ($asks) {
                $return = '';
                if ($asks->active == 0) {
                    $return = '<span class="badge badge-danger label-inline mr-2">غير مفعل</span>';
                }
                else {
                    $return = '<span class="badge badge-success label-inline mr-2">مفعل </span>';
                }
                return $return;
            })
            ->addColumn('yes', function ($asks) {
                return $asks->answers->where('answer', 1)->count();
            })
            ->addColumn('no', function ($asks) {
                return $asks->answers->where('answer', 0)->count();
            })
            ->addColumn('not', function ($asks) {
                return $asks->answers->where('answer', 2)->count();
            })
            ->addColumn('action', function ($asks) {
                $status = '';
                if ($asks->active == 0) {
                    $status = '<a onclick="ApproveData('.$asks->id.')" class="btn btn-xs btn-primary btn-sm tooltips text-white mr-1 ml-1"><i class="fa fa-thumbs-up"></i> <span class="tooltipstext">تفعيل السؤال </span></a>';
                }
                else {
                    $status = '<a onclick="ApproveData('.$asks->id.')" class="btn btn-xs btn-warning btn-sm text-white tooltips mr-1 ml-1"><i class="fa fa-thumbs-down"></i><span class="tooltipstext">إلغاء تفعيل السؤال</span></a>';
                }
                return '<a onclick="editForm('.$asks->id.')" class="btn btn-xs btn-success btn-sm  text-white"><i class="fa fa-edit"></i></a> '.
                    '<a onclick="deleteData('.$asks->id.')" class="btn btn-xs btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a>'.
                    $status;

            })
            ->rawColumns(['idn', 'not', 'active', 'question', 'yes', 'no', 'action'])->make(true);
    }
}
