<?php

namespace App\Http\Controllers\Admin\Employee;

use App\Http\Requests\Admin\VacancyRequest;
use App\Setting;
use App\Vacancy;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DataTables;
use View;
class VacanciesController extends Controller
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
    public function index(){
        return view('Admin.employees.vacancies.index');
    }

    public function store(VacancyRequest $request)
    {
        $input = $request->all();
        Vacancy::create($input);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة الأجازة',
        ]);
    }

    public function edit($id)
    {
        return Vacancy::findOrFail($id);
    }

    public function update(VacancyRequest $request)
    {
        $input = $request->all();
        $vacancy = Vacancy::findOrFail($request->id);
        $vacancy->update($input);

        return response()->json([
            'success' => true,
            'message' => 'تم تعديل الأجازة',
        ]);
    }

    public function destroy($id)
    {
        $vacancy = Vacancy::find($id);
        $vacancy->delete();
        return response()->json([
            'success' => true,
            'message' => 'تم مسح الأجازة ',
        ]);
    }

    public function activate($id)
    {

        $vacancy = Vacancy::find($id);
        if ($vacancy->active == 0) {
            $message = ' تم تفعيل الأجازة';
        }else {
            $message = ' تم إلغاء تفعيل الأجازة';
        }
        $vacancy->update([
            'active' => $vacancy->active == 1 ? 0 : 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function count(){
        if (Setting::where('option_name', 'AdminVacanciesCount')->count() ==0 ) {
            Setting::create([
                'option_name' =>  'AdminVacanciesCount',
                'option_value' => 30,
                'display_name' => 'vacancy number of days',
            ]);
        }
        $AdminVacanciesCount = Setting::where('option_name', 'AdminVacanciesCount')->first();
        return view('Admin.employees.controls.count',[
            'AdminVacanciesCount'    => $AdminVacanciesCount
        ]);
    }
    public function countPost(Request $request){
        Setting::where('option_name', 'AdminVacanciesCount')->update([
            'option_value'   => $request->AdminVacanciesCount
        ]);
        $message = ' تم تحديث رصيد الأجازات';
        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }
    public function datatable()
    {
        $vacancy = Vacancy::all();
        return DataTables::of($vacancy)
            ->editColumn('id', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('name', function ($vacancy) {
                return $vacancy->name;
            })
            ->addColumn('is_salary_deduction', function ($vacancy) {
                $data ='';
                if ($vacancy->is_vacations_deduction == 0) {
                    $data.= '<span class="badge badge-danger label-inline mr-2">لا يقطع من الراتب </span><br>';
                }else{
                    $data.= '<span class="badge badge-success label-inline mr-2">يقطع من الراتب </span><br>';
                }

                if ($vacancy->is_vacations_deduction == 0) {
                    $data.= '<span class="badge badge-danger label-inline mr-2">لا يخصم من رصيد الأجازات </span><br>';
                }else{
                    $data.= '<span class="badge badge-success label-inline mr-2">يخصم من رصيد الأجازات </span><br>';

                }

                if ($vacancy->type == 'official') {
                    $data.= '<span class="badge badge-danger label-inline mr-2">أجازة رسمية (لكل الموظفين) </span>';
                }else{
                    $data.= '<span class="badge badge-success label-inline mr-2">أجازة غير رسمية </span>';
                }

              return $data;
            })
            ->addColumn('gender', function ($vacancy) {
                return $vacancy->genderName;
            })
            ->addColumn('days', function ($vacancy) {
                return $vacancy->days;
            })
            ->addColumn('count', function ($vacancy) {
                return $vacancy->count;
            })
            ->addColumn('active', function ($vacancy) {
                if ($vacancy->active == 0) {
                    return '<span class="badge badge-danger label-inline mr-2">غير مفعل</span>';
                }
                return '<span class="badge badge-success label-inline mr-2">مفعل </span>';

            })
            ->addColumn('action', function ($vacancy) {

                if ($vacancy->active == 0) {
                    $status = '<a onclick="ApproveData('.$vacancy->id.')" class="btn btn-xs btn-primary btn-sm tooltips text-white mr-1 ml-1"><i class="fa fa-thumbs-up"></i> <span class="tooltipstext">تفعيل  </span></a>';
                }
                else {
                    $status = '<a onclick="ApproveData('.$vacancy->id.')" class="btn btn-xs btn-info btn-sm text-white tooltips mr-1 ml-1"><i class="fa fa-thumbs-down"></i><span class="tooltipstext">إلغاء تفعيل </span></a>';
                }
                return '<a onclick="editForm('.$vacancy->id.')" class="btn btn-xs btn-success btn-sm  text-white"><i class="fa fa-edit"></i></a> '.
                    '<a onclick="deleteData('.$vacancy->id.')" class="btn btn-xs btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a>'
                    .$status;

            })
            ->rawColumns(['idn','type','active','is_vacations_deduction','gender','days','days','count', 'is_salary_deduction', 'name', 'action'])->make(true);
    }
}
