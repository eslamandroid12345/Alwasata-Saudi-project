<?php

namespace App\Http\Controllers\HumanResource;

use App\Area;
use App\cities as City;
use App\District;
use App\EmailUser;
use App\Employee;
use App\EmployeeControl;
use App\EmployeeCustody;
use App\EmployeeFile;
use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\reqRecord;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use League\Flysystem\Exception;
use Mpdf\Tag\Em;
use Yajra\DataTables\DataTables;
use MyHelpers;
use PDF;
use Auth;
use function GuzzleHttp\Promise\all;

class UserController extends Controller
{

    public function __construct()
    {
        \View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
    }
    public function allUsers(){
        return view('HumanResource.Users.index');
    }

    public function allUsers_datatable(){
        $users = User::where('status', 1)
            ->where('role', '!=', '6')
            ->orderBy('id', 'DESC');

        return DataTables::of($users)->setRowId(function ($users) {
            return $users->id;
        })->addColumn('email', function ($row) {
            return $row->email ?? 'لا يوجد بريد';
        })->editColumn('work_date', function ($row) {
            return $row->email ?? 'لا يوجد بريد';
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data."<span class='pointer w-100' title='عرض ملف الموظف'>
                               <a href='".route('HumanResource.user.profile', $row->id)."'>
                                <i class='fas fa-eye'></i>
                                عرض ملف الموظف
                                </a>
                            </span> ";
            return $data.'</div>';
        })->editColumn('role', function ($row) {
            $data = $row->role_name;

            if ($row->role == 8) {
                if ($row->accountant_type == 0) {
                    $data = $data.' - تساهيل';
                }
                if ($row->accountant_type == 1) {
                    $data = $data.' - وساطة';
                }
            }

            return $data;
        })->editColumn('status', function ($row) {
            if ($row->allow_recived == 0) {
                $data = MyHelpers::admin_trans(auth()->user()->id, 'not active');
            }else {
                $data = 'نشط';
            }
            return $data;
        })->make(true);
    }

    public function profile($userId,$pdf=null) {
        $user = User::find($userId);
        $controls = collect(EmployeeControl::isActive()->where('type','<>','subsection')->get())->groupBy('type');

        $files = EmployeeFile::where(['user_id' => $userId,'deleted_at' => null])->get();
        $cities = City::all();
        $areas = Area::all();
        $districts = District::all();
        $data=[
            'districts'  => $districts,
            'areas'  => $areas,
            'cities'  => $cities,
            'user'  => $user,
            'files'  => $files,
            'controls'   =>$controls
        ];
        if ($pdf){
            $pdf = PDF::loadView('HumanResource.Users.pdf',$data);
            $pdf->SetProtection(['copy', 'print'], '', 'pass');
            return $pdf->stream('HR-'.$user->name.'-'.$userId.'.pdf');
        }

        return view('HumanResource.Users.profile',$data);
    }

    public function data($request,$key,$object)
    {
        if($request->has($key)){
            return $request->$key;
        }
        return @$object->$key;
    }
    public function personalUpdate(Request $request){
        $employees =Employee::where('mobile',$request->mobile)->pluck('user_id')->toArray();


        if (!((in_array((int)$request->user_id,$employees) && count($employees)  ==1) || count($employees)  ==0 )) {
           if ($request->mobile != null) {
           if ($request->has("is_personal") == 1) {
               $validator = Validator::make($request->all(), [
                   'name'     => 'required|max:55',/*
                   'mobile'     => 'unique:employees'*/
               ]);
               return response()->json([ 'message' => 'يوجد بعض الأخطاء ؟؟ الجوال أو الإسم غير صحيحين ','errors' => $validator->errors()]);
           }
           }

        }

        $validator = Validator::make($request->all(), [
           /* 'name'     => 'required|max:55',
            'mobile'     => ['required',
                             'numeric',
                             'digits:9',
                             'regex:/^(5)[0-9]{8}$/'],
            'email'    => 'email|required',
            'family_count'    => 'numeric|nullable',
            'control_nationality_id'    => 'required',
            'gender'    => 'required',
            'marital_status'    => 'required',
            'qualification'    => 'required',
            'birth_date'    => 'required',*/
        ], [
            'name.required' => ' الاسم مطلوب *',
            'name_en.required' => ' الاسم  باللغة الإنجليزية مطلوب *',
            'birth_date.required' => '  تاريخ الميلاد مطلوب *',
            'marital_status.required' => ' الحالة الإجتماعية مطلوبة *',
            'gender.required' => ' النوع مطلوب *',
            'control_nationality_id.required' => ' الجنسية مطلوبة *',
            'mobile.required' => ' الجوال مطلوب *',
            'mobile.numeric' => ' الجوال لابد ان يكون ارقام *',
            'family_count.numeric' => ' عدد افراد الأسرة لابد ان يكون رقم *',
            'mobile.regex' => ' الجوال لابد ان يبدأ ب 5 ويحتوى على 9 ارقام *',
            'mobile.digits' => ' الجوال لابد ان يكونأختار طبيعه العمل  9 ارقام *',
            'email.required' => ' البريد الإلكترونى مطلوب *',
            'name.max' => '  عدد حروف الإسم كثيرة *',
        ]);

        if ($validator->passes()) {
            $user = Employee::where('user_id',$request->user_id)->first();
            if ($request->area_id){
                if ($request->area_id != $user->area_id){
                    $user->update([
                        'city_id'                   => null,
                        'district_id'               => null,
                    ]);
                }
            }
            $employee = Employee::updateOrCreate([
                "user_id"    => $request->user_id
            ],[
                "name"                      => $this->data($request,'name',$user),
                "name_en"                   => $this->data($request,'name_en',$user),
                "gender"                    => $this->data($request,'gender',$user),
                "mobile"                    => $this->data($request,'mobile',$user),
                "email"                     => $this->data($request,'email',$user),
                "birth_date"                => $this->data($request,'birth_date',$user),
                "family_count"              => $this->data($request,'family_count',$user),
                "marital_status"            => $this->data($request,'marital_status',$user),
                "qualification"             => $this->data($request,'qualification',$user),
                "control_nationality_id"    => $this->data($request,'control_nationality_id',$user),

                "job"                       => $this->data($request,'job',$user),
                "work_date"                 => $this->data($request,'work_date',$user),
                "work_date_2"               => $this->data($request,'work_date_2',$user),
                "work_end_date"             => $this->data($request,'work_end_date',$user),
                "residence_number"          => $this->data($request,'residence_number',$user),
                "residence_end_date"        => $this->data($request,'residence_end_date',$user),
                "direct_date"               => $this->data($request,'direct_date',$user),
                "notes"                     => $this->data($request,'notes',$user),

                'specialization'            => $this->data($request,'specialization',$user),
                'area_id'                   => $this->data($request,'area_id',$user),
                'city_id'                   => $this->data($request,'city_id',$user),
                'district_id'               => $this->data($request,'district_id',$user),

                'street_name'               => $this->data($request,'street_name',$user),
                'building_number'           => $this->data($request,'building_number',$user),
                'unit_number'               => $this->data($request,'unit_number',$user),
                'title'                     => $this->data($request,'title',$user),
                'contact_person_number'     => $this->data($request,'contact_person_number',$user),
                'contact_person_name'       => $this->data($request,'contact_person_name',$user),
                'contact_person_relation'   => $this->data($request,'contact_person_relation',$user),

                "job_number"                => $this->data($request,'job_number',$user),

                "control_section_id"        => $this->data($request,'control_section_id',$user),
                "control_subsection_id"     => $this->data($request,'control_subsection_id',$user),
                "control_guaranty_id"       => $this->data($request,'control_guaranty_id',$user),
                "control_company_id"        => $this->data($request,'control_company_id',$user),
                "control_identity_id"       => $this->data($request,'control_identity_id',$user),
                "control_insurances_id"     => $this->data($request,'control_insurances_id',$user),
                "control_work_id"           => $this->data($request,'control_work_id',$user),
                "control_medical_id"        => $this->data($request,'control_medical_id',$user),
                "guaranty_name"             => $this->data($request,'guaranty_name',$user),
                "control_guaranty_company_id"   => $this->data($request,'control_guaranty_company_id',$user),
            ]);
            if ($request->custody){
                $employee->custodies()->delete();
                foreach ($request->custody as $key=>$item) {
                    if ($item != null){
                        EmployeeCustody::create([
                            'control_id'    => $item,
                            'employee_id'    => $employee->id,
                            'description'   => $request->descriptions[$key]
                        ]);

                    }
                }
            }

            return response()->json([
                'success'                   => true,
                'message'                   => 'تم  الحفظ بنجاح',
                'employee'                  => Employee::find($employee->id),
                'city'                      =>$employee->city->value ?? 'لا يوجد',
                'area'                      =>$employee->area->value ?? 'لا يوجد',
                'district'                  =>$employee->district->value ?? 'لا يوجد',
                'created'                   => $employee->wasRecentlyCreated,
                'gender'                    => $employee->gender,
            ]);
        }
        return response()->json([ 'message' => 'يوجد بعض الأخطاء ؟؟  ','errors' => $validator->errors()]);
    }
    public function subsections(Request $request)
    {

        $output = '<option value="0" disabled="true" selected="true">أختار القسم الفرعى </option>';
        if ($request->value != null){
            $data = EmployeeControl::where('parent_id', $request->value)->get();
            foreach ($data as $row) {
                $output .= '<option value="'.$row->id.'">'.$row->value.'</option>';
            }
        }
        echo $output;
    }

    public function openDownloadFile($method,$id)
    {
        $employee = EmployeeFile::find($id);
        if (!empty($employee)) {
            try {
                return response()->$method(storage_path('app/public/'.$employee->location));
            }catch (\Exception $e){
                return redirect()->back()->with('message2', 'الملف المطلوب غير موجود');
            }
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }
    public function FileUpload(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'file' => 'required|file|max:10240',
        ], [
            'name.required' => ' الإسم مطلوب *',
            'file.required' => ' الملف مطلوب *',
            'file.file' => ' الملف غير صالح *',
            'file.max' => ' الملف كبير جدا  *',
        ]);
        if ($validator->passes()) {

            $upload_date = Carbon::today('Asia/Riyadh')->format('Y-m-d');
            $file = $request->file('file');
            $name = $request->name;
            $user = User::find($request->user_id);
            $folderName = $user->id.'-'.Str::slug($user->username);
            $filename = $name.time().'.'.$file->getClientOriginalExtension();
            $path = $file->storeAs($folderName, $filename);

            EmployeeFile::create([
                'filename'    => $name,
                'location'    => $path,
                'upload_date' => $upload_date,
                'user_id'     => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الملف',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);
    }
    public function FileDelete($id)
    {
        $file = EmployeeFile::find($id);
        if ($file->deleted_at == null){
            $file->deleted_at = now();
            $file->save();
        }else{
            if (auth()->user()->role == 7){
                try {
                    unlink(storage_path('app/public/'.$file->location));
                }catch (Exception $e){}
                $file->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم مسح الملف ',
        ]);
    }
    public function FileRestore($id)
    {
        $file = EmployeeFile::find($id);
        $file->deleted_at = null;
        $file->save();
        return response()->json([
            'success' => true,
            'message' => 'تم إستعادة الملف ',
        ]);
    }

    public function addUserPage()
    {
        return view('HumanResource.Users.addUserPage');
    }

    public function addUser(Request $request)
    {

        //dd($request->input('salesagents', []));
        // get  auth info
        $auth = Auth::user();
        $id = $auth->id;
        $name = $request->name;
        $email = $request->email;
        $mobile = $request->mobile;

        $newuser =\App\Models\User::create([
            'name'               => $name,
            'email'              => $email,
            'mobile'             => $mobile,
            'role'               => $request->role,
            'created_at'         => (Carbon::now('Asia/Riyadh')),
            'subdomain'          => $request->others,
            'bank_id'            => $request->get('bank_id'),
        ]);

        if ($newuser != null) {
            return redirect()->route('HumanResource.users.index')->with('message', MyHelpers::admin_trans(auth()->user()->id, 'Add Succesffuly'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }
}
