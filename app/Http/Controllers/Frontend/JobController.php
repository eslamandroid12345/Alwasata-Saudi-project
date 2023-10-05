<?php

namespace App\Http\Controllers\Frontend;

use App\City;
use App\Models\JobTitle;
use App\Models\University;
use App\Models\Nationality;
use Illuminate\Http\Request;
use App\Models\JobApplication;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

use App\Http\Requests\Customer\JobRequest;
use App\Models\JobApplicationExtraDetail;

class JobController extends Controller
{
    // public function index(){
    //     $data['nationality']=Nationality::where('status','1')->get();
    //     $data['cities']=City::get();
    //     $data['university']=University::where('status','1')->get();
    //     $data['jobs']=JobTitle::where('status','1')->get();
    //     return view('testWebsite.Pages.apply_for_job',$data);
    // }

    public function NewPage(){
        $data['nationality']=Nationality::where('status','1')->get();
        $data['cities']=City::get();
        $data['university']=University::where('status','1')->get();
        $data['jobs']=JobTitle::where('status','1')->get();
        return view('testWebsite.Pages.apply-form-design',$data);
    }
//===========================================================================
  public function apply_for_job(JobRequest $request){
    // public function apply_for_job(Request $request){
        DB::beginTransaction();
        try{
            /*
            $valaditor = \Validator::make($request->all(),$rules, $messages);
            if($valaditor->fails())
            {
                return redirect()->back()->withInput()->withErrors($valaditor)->with(['message2'=>'من فضلك قم بمراجعه جميع المدخلات وتاكد من ادخالها بشكل صحيح']);
            }
            */
            $added=JobApplication::create([
             'first_name'=>$request->first_name,
             'sur_name'=>$request->sur_name,
             'date_of_birth'=>$request->date_of_birth,
             'phone'=>$request->phone,
             'email'=>$request->email,
             'salary'=>$request->salary,
             'gender'=>$request->gender,

             //لو كان مختار جنسيه تانيه هيحط دى ب null
             'nationality_id'=>($request->nationality_id!='other')?$request->nationality_id:Null,
             'other_nationality'=>($request->nationality_id=='other')?$request->other_nationality:Null,

             'city_id'=>($request->city_id!='other')?$request->city_id:Null,
             'other_city'=>($request->city_id=='other')?$request->other_city:Null,

             'university_id'=>($request->university_id!='other')?$request->university_id:Null,
             'other_university'=>($request->university_id=='other')?$request->other_university:Null,

             'job_id'=>$request->job_id,

             'level'=>($request->level!='other')?$request->level:$request->other_level,
             'level_specialization'=>$request->level_specialization,
             'graduation_date'=>$request->graduation_date,
             'grade'=>($request->grade!='other')?$request->grade:$request->other_grade,
             'specialization'=>$request->specialization,
             'duration'=>$request->duration,
             'experance_years'=>($request->experance_years!='other')?$request->experance_years:$request->other_experance_years,
             'possible_start_date'=>$request->possible_start_date,
             'notes'=>$request->notes,


             'need_traning'=>($request->need_traning=='on')?'1':'0',
            ]);

           // dd($added);
            $all_courses=$request->courses;
            $all_experances=$request->experances;
           // return redirect()->back()->withErrors(sizeof($all_courses));
            for($c=0;$c<sizeof($all_courses);$c++){
                if($all_courses[$c]!=null){
                    JobApplicationExtraDetail::create([
                        'job_app_id'    => $added->id,
                        'type'          =>'courses',
                        'title'         => $all_courses[$c],
                        'start_date'    => $request->course_start_date[$c],
                        'end_date'      => $request->course_end_date[$c]
                    ]);
                }
            }

            for($e=0;$e<sizeof($all_experances);$e++){
                if($all_experances[$e]!=null){
                    JobApplicationExtraDetail::create([
                        'job_app_id'    => $added->id,
                        'type'          =>'experances',
                        'title'         => $all_experances[$e],
                        'start_date'    => $request->experance_start_date[$e],
                        'end_date'      => $request->experance_end_date[$e]
                    ]);
                }
            }
            DB::commit();

            // return redirect()->route('apply-form-design')->with(['message'=>'تم تقديم طلبك بنجاح ... سيتم التواصل معك فى اقرب وقت .. شكرا لك']);
            return redirect()->route('job-submitted')->with(['message'=>'تم تقديم طلبك بنجاح ... سيتم التواصل معك فى اقرب وقت .. شكرا لك']);

        }catch(\Exception $e){
            DB::rollBack();
           return $e->getMessage();
        //  return redirect()->route('apply-form-design')->withErrors($e->getMessage());
          return redirect()->route('careers')->withErrors(['message'=>'حدث خطا اثناء تقديم الطلب .. من فضلك حاول مره اخرى']);

        }
    }

    //------------------------------------------------------------------------------------------------------------------
    public function JobThankYou()
    {
        if(session()->has('message'))
        {
            return view('Frontend.job_thank_you');
        }else{
            return redirect()->back();
        }
    }

}
