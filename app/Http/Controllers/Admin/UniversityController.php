<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UniversityRequest;
use App\Models\University;
use Yajra\DataTables\Facades\DataTables;

class UniversityController extends Controller
{
   
    public function index()
    {
       $data=University::get();
       return view('Admin.University.index',compact('data'));
    }
//----------------------------------------------------------------------------------------
    public function datatable()
    {
        //get all types data
         $data=University::orderBy('id', 'DESC')->get();

        //use datatables (yajra) to handel this data
        return DataTables::of($data)->addColumn('status', function ($data) {
                //return if this type is hide or show
                if($data->status== 0){
                    return 'مخفى';
                }else{
                    return 'ظاهر';
                }
            })->addColumn('action', function ($data) {
                return view('Admin.University.datatable.action', compact('data'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

//----------------------------------------------------------------------------------------
    public function create()
    {
        $data=University::get();
        return view('Admin.University.add_University',compact('data'));
    }

 //----------------------------------------------------------------------------------------
    public function store(UniversityRequest $request)
    {
        try{
            University::create([
                'title'     =>  $request->title,
                'sort'      =>  $request->sort,
                'status'    =>  $request->status,
            ]);
            return redirect()->route('university.index')->with(['message'=>'تمت الاضافه بنجاح']);
        }catch(\Exception $e){
            return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء الاضافه']);

        }
    }
//----------------------------------------------------------------------------------------
    public function edit($id)
    {
        $data=University::find($id);
        return view('Admin.University.edit_University',compact('data'));
    }

   //----------------------------------------------------------------------------------------
    public function update(Request $request,$id)
    {
        //return $request;
        try{
             University::findOrFail($request->id)->update([
                'title'     =>  $request->title,
                'sort'      =>  $request->sort,
                'status'    =>  $request->status,
            ]);
            return redirect()->route('university.index')->with(['message'=>'تم التعديل بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء التعديل']);
         }
    }

   //----------------------------------------------------------------------------------------
    public function destroy($id)
    {
        try{
            University::findOrFail($id)->delete();
            return redirect()->route('university.index')->with(['message'=>'تم الحذف بنجاح']);
        
        }catch(\Exception $e){
               // return redirect()->back()->with(['message2'=>$e->getMessage()]);
                return redirect()->back()->with(['message2'=>'حدث خطا اثناء الحذف']);
        }
    }
}
