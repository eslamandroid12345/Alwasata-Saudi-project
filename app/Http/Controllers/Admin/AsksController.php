<?php

namespace App\Http\Controllers\Admin;

use App\Ask;
use App\AskAnswer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use View;

class AsksController extends Controller
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

    public function index()
    {
        return view('Admin.asks.index');
    }

    public function answers()
    {
        return view('Admin.asks.answers');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|min:10',
        ], [
            'question.required' => 'محتوى السؤل مطلوب *',
            'question.min'      => 'محتوى السؤل يجب ان لايقل عن 10 حروف *',
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            Ask::create($input);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة السؤال',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);

    }

    public function edit($id)
    {
        return Ask::findOrFail($id);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'question' => 'required|min:10',
        ], [
            'question.required' => 'محتوى السؤل مطلوب *',
            'question.min'      => 'محتوى السؤل يجب ان لايقل عن 10 حروف *',
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            $Ask = Ask::findOrFail($request->id);
            $Ask->update($input);

            return response()->json([
                'success' => true,
                'message' => 'تم تعديل السؤال',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);
    }

    public function destroy($id)
    {
        $Ask = Ask::find($id);
        $Ask->delete();
        return response()->json([
            'success' => true,
            'message' => 'تم مسح السؤال ',
        ]);
    }

    public function activate($id)
    {

        $Ask = Ask::find($id);
        if ($Ask->active == 0) {
            $message = ' تم تفعيل السؤال';
        }
        else {
            $message = ' تم إلغاء تفعيل السؤال';
        }
        $Ask->update([
            'active' => $Ask->active == 1 ? 0 : 1,
        ]);

        return response()->json([
            'success' => true,
            'message' => $message,
        ]);
    }

    public function answer($id)
    {
        $answer = AskAnswer::find($id);
        $request = \App\request::find($answer->request_id);

        $data = '<div class="row">';
        /*$data.='<div class="col-lg-12 pt-2 pb-2">
            <table class="table">
            <tr>
                <td>إسم العميل</td>
                <td>'.$request->customer->name.'</td>
            </tr>
             <tr>
                <td>إسم الموظف</td>
                <td>'.$request->user->name.'</td>
            </tr>
            <tr>
                <td> نوع الطلب</td>
                <td>'.$request->source.'</td>
            </tr>
            <tr>
                <td>عدد الأسئلة</td>
                <td>'.$request->answers->count().'</td>
            </tr>
            <tr>
                <td>نعم [عدد]</td>
                <td>'.$request->answers->where('answer',1)->count().'</td>
            </tr>
             <tr>
                <td>لا [عدد]</td>
                <td>'.$request->answers->where('answer',0)->count().'</td>
            </tr>
            </table>
        </div>';*/
        $number = 1;
        $reason = '';
        // Editing on canceling the request from customer
        //*******************************************************
        if ($request->customer_reason_for_cancel != null) {
            $reason .= '<br> <div class="col-lg-12">
                    <div class="card">
                      <div class="card-header">
                      سبب الرفض
                      </div>
                        <div class="card-body">
                        '.$request->customer_reason_for_cancel.'
                        </div>
                    </div>
                 </div>';
        }
        //*******************************************************
        foreach (AskAnswer::with('ask')->where(['request_id' => $request->id, 'user_id' => $answer->user_id, 'batch' => $answer->batch])->get() as $item) {
            $null = $item->answer == 2 ? ' btn btn-warning' : 'btn';

            $false = intval($item->answer) == 0 ? ' btn btn-danger' : ' btn';
            $true = $item->answer == 1 ? ' btn btn-primary' : ' btn';

            $data .= '<div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <b><h4>'.$number++.'-'.$item->ask->question.'</h4></b><br>
                    <div class="row pt-1">
                        <div class="col-lg-3">
                            <button class="'.$true.'">نعم</button>
                        </div>
                        <div class="col-lg-3">
                            <button class="'.$false.'">لا</button>
                        </div>
                        <div class="col-lg-3">
                            <button class="'.$null.'">لم يجيب</button>
                        </div>
                    </div>
                </div>
            </div>
            </div>';
        }
        $data .= $reason.'</div>';
        return $data;
    }

}
