<?php

namespace App\Http\Controllers;

use App\CustomersPhone;
use App\Employee;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use View;

class AddPhonesController extends Controller
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
    //*********************************************
    // Edit Task-17
    //*********************************************
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'mobile' => ['required', 'numeric', 'unique:customers', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'],
        ], [
            'mobile.required' => ' رقم الجوال مطلوب *',
            'mobile.numeric'  => 'رقم الجوال لابد ان يكون ارقام *',
            'mobile.unique'   => 'رقم الجوال موجود بالفعل  *',
            'mobile.regex'    => 'رقم الجوال غير صحيح *',
        ]);

        if ($validator->passes()) {

            $validator2 = Validator::make($request->all(), [
                'mobile' => ['unique:customers_phones'],
            ], [
                'mobile.unique' => 'رقم الجوال موجود بالفعل  *',
            ]);

            if ($validator2->passes()) {
                $input = $request->all();
                $inputs['mobile'] = substr($request->mobile, -9);
                CustomersPhone::create($input);

                return response()->json([
                    'success' => true,
                    'message' => 'تم إضافة رقم الجوال',
                ]);
            }
            else {
                return response()->json(['errors' => $validator2->errors()]);
            }
        }
        return response()->json(['errors' => $validator->errors()]);

    }

    public function edit($id)
    {
        return CustomersPhone::where('request_id', $id)->get();
    }

    //*********************************************
    // Edit Task-17
    //*********************************************
    public function MobilesUpdate(Request $request)
    {

        $error = false;
        $null = false;
        $items = [];
        $errors = [];

        $niceNames = [
            'mobile.numeric' => 'رقم الجوال لابد ان يكون ارقام *',
            'mobile.unique'  => 'رقم الجوال موجود بالفعل  *',
            'mobile.regex'   => 'رقم الجوال غير صحيح *',
        ];

        foreach ($request->mobile as $key => $item) {
            $input['mobile'] = $item;
            $rule['mobile'] = ['required', 'numeric', 'unique:customers', 'regex:/^(009665|9665|\+9665|05|5)(5|0|3|6|4|9|1|8|7)([0-9]{7})$/'];
            $validator = Validator::make($input, $rule, $niceNames);
            if ($validator->fails()) {
                $error = true;
                $errors[CustomersPhone::find($request->ids[$key])->mobile] = $validator->errors()->toArray()['mobile'][0];
            }
        }

        if ($error == true) {
            return response()->json([
                'validations' => $errors,
            ]);
        }
        foreach ($request->mobile as $key => $item) {
            $customer = CustomersPhone::where('mobile', $item)->where('customer_id', '<>', $request->customer_id)->count();
            $customers = DB::table('customers')->where('mobile', $item)->count();

            if ($customer > 0 || $customers > 0) {
                $error = true;
                array_push($items, CustomersPhone::find($request->ids[$key])->mobile);
            }
            if ($item == null) {
                $null = true;
            }
        }
        if ($error == true) {

            if ($null == true) {
                return response()->json([
                    'errors'  => 'خطأ من فضلك أدخل رقم جوال ',
                    'numbers' => $items,
                ]);
            }
            else {
                return response()->json([
                    'errors'  => 'خطأ يوجد رقم مسجل بالفعل من فضلك إستخدم التحقق ',
                    'numbers' => $items,
                ]);
            }
        }
        $customer = CustomersPhone::where('customer_id', $request->customer_id)->delete();
        foreach ($request->mobile as $item) {
            $item = substr($item, -9);
            CustomersPhone::create([
                'customer_id' => $request->customer_id,
                'request_id'  => $request->request_id,
                'mobile'      => $item,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم الحفظ بنجاح',
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|min:10',
        ], [
            'phone.required' => 'محتوى رقم الجوال مطلوب *',
            'phone.min'      => 'محتوى رقم الجوال يجب ان لايقل عن 10 حروف *',
        ]);
        if ($validator->passes()) {
            $input = $request->all();
            $phone = CustomersPhone::findOrFail($request->id);
            $phone->update($input);

            return response()->json([
                'success' => true,
                'message' => 'تم تعديل رقم الجوال',
            ]);
        }
        return response()->json(['errors' => $validator->errors()]);
    }

    public function destroy($id)
    {
        $phone = CustomersPhone::find($id);
        $count = CustomersPhone::where([
            'request_id'  => $phone->request_id,
            'customer_id' => $phone->customer_id,
        ])->count();
        $phone->delete();
        return response()->json([
            'count'   => $count - 1,
            'success' => true,
            'message' => 'تم مسح رقم الجوال ',
        ]);
    }

}
