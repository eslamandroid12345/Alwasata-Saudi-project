<?php

namespace App\Http\Controllers\Admin\Employee;

use App\EmployeeControl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Support\Facades\Validator;
use View;
use MyHelpers;

class ControlsController extends Controller
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
    public function index($type)
    {
        if (!MyHelpers::getControlTypeName($type)){
            return redirect()->back();
        }

        return view('Admin.employees.controls.index',[
            'type'  => $type,
            'sections' => EmployeeControl::where('type','section')->get(),
            'companies' => EmployeeControl::where('type','company')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
        ], [
            'value.required' => ' الإعداد مطلوب *',
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            EmployeeControl::create($input);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة الإعداد',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);

    }

    public function edit($id)
    {
        return EmployeeControl::findOrFail($id);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
        ], [
            'value.required' => ' الإعداد مطلوب *',
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            $control = EmployeeControl::findOrFail($request->id);
            $control->update($input);

            return response()->json([
                'success' => true,
                'message' => 'تم تعديل الإعداد',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);
    }

    public function destroy($id)
    {
        $control = EmployeeControl::find($id);
        $control->delete();
        return response()->json([
            'success' => true,
            'message' => 'تم مسح الإعداد ',
        ]);
    }

    public function activate($id)
    {

        $control = EmployeeControl::find($id);
        if ($control->active == 0) {
            $message = ' تم تفعيل الإعداد';
        }else {
            $message = ' تم إلغاء تفعيل الإعداد';
        }
        $control->update([
            'active' => $control->active == 1 ? 0 : 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
    protected $type;
    public function datatable($type,$parent_id =null)
    {
        if ($parent_id != null){
            $control = EmployeeControl::where([
                'type' =>  $type,
                'parent_id' =>  $parent_id
            ])->get();
        }else{
            $control = EmployeeControl::where('type', $type)->get();
        }
        $this->type = $type;
        return DataTables::of($control)
            ->editColumn('id', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('value', function ($control) {
                return $control->value;
            })
            ->addColumn('guaranty_name', function ($control) {
                return $control->guaranty_name ?? '-';
            })
            ->addColumn('parent_id', function ($control) {
                return $control->section->value ?? 'لا يوجد';
            })
            ->addColumn('active', function ($asks) {
                $return = '';
                if ($asks->active == 0) {
                    $return = '<span class="badge badge-danger label-inline mr-2">غير مفعل</span>';
                }
                else {
                    $return = '<span class="badge badge-success label-inline mr-2">مفعل </span>';
                }
                $type='';
                if ($this->type =='nationality' && is_numeric($asks->parent_id)){
                    $type='<span class="badge badge-primary label-inline mr-2">جنسية أساسية </span>';
                }
                return $return.$type;
            })
            ->addColumn('action', function ($control) {

                if ($control->active == 0) {
                    $status = '<a onclick="ApproveData('.$control->id.')" class="btn btn-xs btn-primary btn-sm tooltips text-white mr-1 ml-1"><i class="fa fa-thumbs-up"></i> <span class="tooltipstext">تفعيل  </span></a>';
                }
                else {
                    $status = '<a onclick="ApproveData('.$control->id.')" class="btn btn-xs btn-info btn-sm text-white tooltips mr-1 ml-1"><i class="fa fa-thumbs-down"></i><span class="tooltipstext">إلغاء تفعيل </span></a>';
                }
                return '<a onclick="editForm('.$control->id.')" class="btn btn-xs btn-success btn-sm  text-white"><i class="fa fa-edit"></i></a> '.
                    '<a onclick="deleteData('.$control->id.')" class="btn btn-xs btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a>'
                    .$status;

            })
            ->rawColumns(['idn','active', 'parent_id', 'value', 'action'])->make(true);
    }
}
