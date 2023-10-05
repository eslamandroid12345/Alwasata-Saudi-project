<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AppDetail;

class AppDetailsController extends Controller
{
    public function index(){
        $app_details=AppDetail::get();
       // return $app_details;
        return view('Admin.AppDetails.index',compact('app_details'));
    }
    //==================================================================
    public function update(Request $request){
       $detail=AppDetail::get();
       for($d=0;$d<$detail->count();$d++){
        AppDetail::findOrfail($detail[$d]->id)->update(
            [
                'icon_title'=>$request->icon_title[$d],
                'icon_desc'=>$request->icon_desc[$d],
            ]
        );
       }
       return redirect()->back()->with(['message'=>'تم التعديل بنجاح']);
    }
}
