<?php

namespace App\Http\Controllers\Admin;

use App\realType;
use App\real_estat;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TypeRequest;
use Yajra\DataTables\Facades\DataTables;

class RealTypeController extends Controller
{
   
    public function index()
    {
       $real_types=realType::get();
       return view('Admin.RealTypes.index',compact('real_types'));
    }
//----------------------------------------------------------------------------------------
    public function datatable()
    {
        //get all types data
         $real_types=realType::orderBy('id', 'DESC')->get();

        //use datatables (yajra) to handel this data
        return DataTables::of($real_types)->addColumn('type', function ($real_types) {
                //return if this type is main or sub
                if($real_types->parent_id== NULL){
                    return 'رئيسى';
                }else{
                    return 'فرعى';
                }
            })->addColumn('action', function ($real_types) {
                return view('Admin.RealTypes.datatable.action', compact('real_types'));
            })
            ->rawColumns(['actions'])
            ->make(true);
    }

//----------------------------------------------------------------------------------------
    public function create()
    {
        $real_types=realType::whereNull('parent_id')->get();
        return view('Admin.RealTypes.add_type',compact('real_types'));
    }

 //----------------------------------------------------------------------------------------
    public function store(TypeRequest $request)
    {
        try{
            realType::create([
                'value'     =>  $request->value,
                'parent_id' =>  ($request->parent_id!='0')?$request->parent_id:Null,
            ]);
            return redirect()->route('real_types.index')->with(['message'=>'تم اضافه نوع العقار بنجاح']);
        }catch(\Exception $e){
          //  return redirect()->back()->with(['message2'=>$e->getMessage()]);
            return redirect()->back()->with(['message2'=>'حدث خطا اثناء اضافه نوع العقار']);

        }
    }
//----------------------------------------------------------------------------------------
    public function edit($id)
    {
        $data['real_types']=realType::whereNull('parent_id')->get();
        $data['real_data']=realType::find($id);
        return view('Admin.RealTypes.edit_type',$data);
    }

   //----------------------------------------------------------------------------------------
    public function update(Request $request,$id)
    {
        //return $request;
        try{
             realType::findOrFail($request->id)->update([
                 'value'     =>  $request->value,
                 'parent_id' =>  ($request->parent_id!='0')?$request->parent_id:Null,
             ]);
             return redirect()->route('real_types.index')->with(['message'=>'تم تعديل نوع العقار بنجاح']);
         }catch(\Exception $e){
            // return redirect()->back()->with(['message2'=>$e->getMessage()]);
             return redirect()->back()->with(['message2'=>'حدث خطا اثناء تعديل نوع العقار']);
         }
    }

   //----------------------------------------------------------------------------------------
    public function destroy($id)
    {
        try{
            //dd($id);
           $real_estat= real_estat::where('type',$id)->count();
           $real_types= realType::where('parent_id',$id)->count();
           
           if($real_estat>0){
                return redirect()->route('real_types.index')->with(['message2'=>'هذا النوع مرتبط بعقارات لا يمكن حذفه']);
           }elseif($real_types>0){
                 return redirect()->route('real_types.index')->with(['message2'=>'هذا النوع مرتبط بانواع فرعيه اخرى  لا يمكن حذفه']);
           }else{
                realType::findOrFail($id)->delete();
                return redirect()->route('real_types.index')->with(['message'=>'تم حذف نوع العقار']);
           }
           
        }catch(\Exception $e){
               // return redirect()->back()->with(['message2'=>$e->getMessage()]);
                return redirect()->back()->with(['message2'=>'حدث خطا اثناء حذف نوع العقار']);
        }
    }
}
