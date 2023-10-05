<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\RejectionsReason;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use View;

class RejectionsController extends Controller
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
        return view('Admin.rejections.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2',
        ], [
            'title.required' => 'عنوان الرفض مطلوب *',
            'title.min'      => 'عنوان الرفض يجب ان لايقل عن 2 حروف *',
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            RejectionsReason::create($input);

            return response()->json([
                'success' => true,
                'message' => 'تم إضافة سبب الرفض',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);

    }

    public function edit($id)
    {
        return RejectionsReason::findOrFail($id);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:2',
        ], [
            'title.required' => 'عنوان الرفض مطلوب *',
            'title.min'      => 'عنوان الرفض يجب ان لايقل عن 2 حروف *',
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            $RejectionsReason = RejectionsReason::findOrFail($request->id);
            $RejectionsReason->update($input);

            return response()->json([
                'success' => true,
                'message' => 'تم تعديل سبب الرفض',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);
    }

    public function destroy($id)
    {
        $RejectionsReason = RejectionsReason::find($id);
        $RejectionsReason->delete();
        return response()->json([
            'success' => true,
            'message' => 'تم مسح سبب الرفض ',
        ]);
    }
}
