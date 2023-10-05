<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobApplicationType;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobApplicationTypeRequest;
use Yajra\DataTables\Facades\DataTables;

class JobApplicationTypesController extends Controller
{
   
    public function index()
    {
       $data=JobApplicationType::get();
       return view('Admin.JobApplicationTypes.index',compact('data'));
    }
//----------------------------------------------------------------------------------------
    public function datatable()
    {
        //get all types data
         $data=JobApplicationType::orderBy('id', 'DESC');

        //use datatables (yajra) to handel this data
        return DataTables::of($data)->addColumn('status', function ($data) {
                //return if this type is hide or show
                if($data->status== 0){
                    return 'مخفى';
                }else{
                    return 'ظاهر';
                }
            })->addColumn('action', function ($data) {
                return view('Admin.JobApplicationTypes.datatable.action', compact('data'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

//----------------------------------------------------------------------------------------
    public function create()
    {
        $data=JobApplicationType::get();
        return view('Admin.JobApplicationTypes.add_type',compact('data'));
    }

 //----------------------------------------------------------------------------------------
    public function store(JobApplicationTypeRequest $request)
    {
        try{
            JobApplicationType::create([
                'title'     =>  $request->title,
                'sort'      =>  $request->sort,
                'status'    =>  $request->status,
            ]);
            return redirect()->route('job_applications_types.index')->with(['message'=>'تمت الاضافه بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء الاضافه']);

        }
    }
//----------------------------------------------------------------------------------------
    public function edit($id)
    {
        $data=JobApplicationType::find($id);
        return view('Admin.JobApplicationTypes.edit_type',compact('data'));
    }

   //----------------------------------------------------------------------------------------
    public function update(Request $request,$id)
    {
        //return $request;
        try{
             JobApplicationType::findOrFail($request->id)->update([
                'title'     =>  $request->title,
                'sort'      =>  $request->sort,
                'status'    =>  $request->status,
            ]);
            return redirect()->route('job_applications_types.index')->with(['message'=>'تم التعديل بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء التعديل']);
         }
    }

   //----------------------------------------------------------------------------------------
    public function destroy($id)
    {
        try{
            JobApplicationType::findOrFail($id)->delete();
            return redirect()->route('job_applications_types.index')->with(['message'=>'تم الحذف بنجاح']);
        
        }catch(\Exception $e){
               // return redirect()->back()->with(['message2'=>$e->getMessage()]);
                return redirect()->back()->with(['message2'=>'حدث خطا اثناء الحذف']);
        }
    }
}
