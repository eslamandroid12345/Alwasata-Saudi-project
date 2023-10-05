<?php

namespace App\Http\Controllers\Admin;

use App\Models\Nationality;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\NationalityRequest;

use Yajra\DataTables\Facades\DataTables;

class NationalityController extends Controller
{
   
    public function index()
    {
       $data=Nationality::get();
       return view('Admin.Nationality.index',compact('data'));
    }
//----------------------------------------------------------------------------------------
    public function datatable()
    {
        //get all types data
         $data=Nationality::orderBy('id', 'DESC')->get();

        //use datatables (yajra) to handel this data
        return DataTables::of($data)->addColumn('status', function ($data) {
                //return if this type is hide or show
                if($data->status== 0){
                    return 'مخفى';
                }else{
                    return 'ظاهر';
                }
            })->addColumn('action', function ($data) {
                return view('Admin.Nationality.datatable.action', compact('data'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

//----------------------------------------------------------------------------------------
    public function create()
    {
        $data=Nationality::get();
        return view('Admin.Nationality.add_nationality',compact('data'));
    }

 //----------------------------------------------------------------------------------------
    public function store(NationalityRequest $request)
    {
        try{
            Nationality::create([
                'title'     =>  $request->title,
                'sort'      =>  $request->sort,
                'status'    =>  $request->status,
            ]);
            return redirect()->route('nationality.index')->with(['message'=>'تمت الاضافه بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء الاضافه']);

        }
    }
//----------------------------------------------------------------------------------------
    public function edit($id)
    {
        $data=Nationality::find($id);
        return view('Admin.Nationality.edit_nationality',compact('data'));
    }

   //----------------------------------------------------------------------------------------
    public function update(Request $request,$id)
    {
        //return $request;
        try{
             Nationality::findOrFail($request->id)->update([
                'title'     =>  $request->title,
                'sort'      =>  $request->sort,
                'status'    =>  $request->status,
            ]);
            return redirect()->route('nationality.index')->with(['message'=>'تم التعديل بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء التعديل']);
         }
    }

   //----------------------------------------------------------------------------------------
    public function destroy($id)
    {
        try{
            Nationality::findOrFail($id)->delete();
            return redirect()->route('nationality.index')->with(['message'=>'تم الحذف بنجاح']);
        
        }catch(\Exception $e){
               // return redirect()->back()->with(['message2'=>$e->getMessage()]);
                return redirect()->back()->with(['message2'=>'حدث خطا اثناء الحذف']);
        }
    }
}
