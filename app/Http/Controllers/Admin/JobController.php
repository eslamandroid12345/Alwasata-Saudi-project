<?php

namespace App\Http\Controllers\Admin;

use App\Models\JobTitle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\JobRequest;

use Yajra\DataTables\Facades\DataTables;

class JobController extends Controller
{
   
    public function index()
    {
       $data=JobTitle::get();
       return view('Admin.Jobs.index',compact('data'));
    }
//----------------------------------------------------------------------------------------
    public function datatable()
    {
        //get all types data
         $data=JobTitle::orderBy('id', 'DESC')->get();

        //use datatables (yajra) to handel this data
        return DataTables::of($data)->addColumn('status', function ($data) {
                //return if this type is hide or show
                if($data->status== 0){
                    return 'مخفى';
                }else{
                    return 'ظاهر';
                }
            })->addColumn('action', function ($data) {
                return view('Admin.Jobs.datatable.action', compact('data'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

//----------------------------------------------------------------------------------------
    public function create()
    {
        $data=JobTitle::get();
        return view('Admin.Jobs.add_job_title',compact('data'));
    }

 //----------------------------------------------------------------------------------------
    public function store(JobRequest $request)
    {
        try{
            JobTitle::create([
                'title'     =>  $request->title,
                'sort'      =>  $request->sort,
                'status'    =>  $request->status,
            ]);
            return redirect()->route('job_titles.index')->with(['message'=>'تمت الاضافه بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء الاضافه']);

        }
    }
//----------------------------------------------------------------------------------------
    public function edit($id)
    {
        $data=JobTitle::find($id);
        return view('Admin.Jobs.edit_job_title',compact('data'));
    }

   //----------------------------------------------------------------------------------------
    public function update(Request $request,$id)
    {
        //return $request;
        try{
             JobTitle::findOrFail($request->id)->update([
                'title'     =>  $request->title,
                'sort'      =>  $request->sort,
                'status'    =>  $request->status,
            ]);
            return redirect()->route('job_titles.index')->with(['message'=>'تم التعديل بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء التعديل']);
         }
    }

   //----------------------------------------------------------------------------------------
    public function destroy($id)
    {
        try{
            JobTitle::findOrFail($id)->delete();
            return redirect()->route('job_titles.index')->with(['message'=>'تم الحذف بنجاح']);
        
        }catch(\Exception $e){
               // return redirect()->back()->with(['message2'=>$e->getMessage()]);
                return redirect()->back()->with(['message2'=>'حدث خطا اثناء الحذف']);
        }
    }
}
