<?php

namespace App\Http\Controllers\HumanResource;

use App\Models\JobTitle;
use App\Models\Nationality;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Yajra\DataTables\DataTables;
use App\Models\JobApplicationType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\JobApplicationExtraDetail;

class JobApplicationController extends Controller
{
    public function index()
    {
        $data['jobs']=JobTitle::get();
        $data['nationalitys']=Nationality::get();
        $data['types']=JobApplicationType::where('status',1)->get();
    //    $data['data']=JobApplication::orderBy('id', 'DESC')->get();
       return view('HumanResource.JobApplication.index',$data);
    }
    //----------------------------------------------------------------------------------------
    public function datatable(Request $request)
    {      
        //get all types data
         $data=JobApplication::orderBy('id', 'DESC');
         
            if(!empty($request->specialization) ){$data=$data->where('specialization','like','%'.$request->specialization.'%');}
            if(!empty($request->job_title) && ($request->job_title)!='0'){$data=$data->where('job_id',$request->job_title);}
            if(!empty($request->nationality_id) && ($request->nationality_id)!='0'){$data=$data->where('nationality_id',$request->nationality_id);}
            if(!empty($request->type_id) && ($request->type_id)!='0'){$data=$data->where('type_id',$request->type_id);}
            if(!empty($request->duration_type) && ($request->duration_type)!='0'){$data=$data->where('duration',$request->duration_type);}
            if(!empty($request->salary_from) && !empty($request->salary_to)){$data=$data->whereBetween('salary',[$request->salary_from,$request->salary_to]);}
            if(isset($request->need_traning)){$data=$data->where('need_traning',$request->need_traning);}

        
        //get all types data
        // $data=JobApplication::orderBy('id', 'DESC');

        //use datatables (yajra) to handel this data
       return DataTables::of($data)->addColumn('name', function ($data) {
                //return name
                return $data->first_name.' '.$data->sur_name;
            })->addColumn('jobtitle', function ($data) {
                //return job title 
                return $data->job_title->title ?? '';
            })->addColumn('university', function ($data) {
                //return university if selected or return other university if choose other
                $info['university']=$data;
                return view('HumanResource.JobApplication.datatable.extra',$info);
            })->addColumn('nationality', function ($data) {
                //return university if selected or return other university if choose other
                $info['nationality']=$data;
                return view('HumanResource.JobApplication.datatable.extra',$info);
            })->addColumn('type', function ($data) {
                $info['type']=$data;
                return view('HumanResource.JobApplication.datatable.extra',$info);
            })->addColumn('action', function ($data) {
                return view('HumanResource.JobApplication.datatable.action', compact('data'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }
    //----------------------------------------------------------------------------------------
    public function show($id)
    {
     // return $id;
      $data['job']=JobApplication::findOrfail($id);
    
      $data['courses']=  JobApplicationExtraDetail::where('job_app_id',$id)->where('type','courses')->get();
      $data['experances']=  JobApplicationExtraDetail::where('job_app_id',$id)->where('type','experances')->get();

      $data['types']=JobApplicationType::where('status',1)->get();

      
      return view('HumanResource.JobApplication.show',$data);
    }
    //----------------------------------------------------------------------------------------
    public function update(Request $request,$id)
    {
       // dd($request);
        try{
             JobApplication::findOrFail($id)->update([
                'hr_id'     =>  Auth::user()->id,
                'type_id'   =>  $request->type_id,
                'hr_notes'  =>  $request->hr_notes,
            ]);
            return redirect()->route('HumanResource.job_applications.index')->with(['message'=>'تم التعديل بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء التعديل']);
         }
    }
    //----------------------------------------------------------------------------------------
    public function destroy($id)
    {
        try{
            JobApplication::findOrFail($id)->delete();
            return redirect()->route('job_applications.index')->with(['message'=>'تم الحذف بنجاح']);

        }catch(\Exception $e){
               // return redirect()->back()->with(['message2'=>$e->getMessage()]);
                return redirect()->back()->with(['message2'=>'حدث خطا اثناء الحذف']);
        }
    }
}
