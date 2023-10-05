<?php

namespace App\Http\Controllers\Suggestion;

use App\CheckTotal;
use App\EditCalculationFormulaUser;
use App\Http\Controllers\Controller;
use App\ProductType;
use Carbon\Carbon;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class ProductsController extends Controller
{
    protected $loggedIn = null;

    public function __construct()
    {
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
        $this->middleware('auth');
    }

    public function index()
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $ProductType = json_decode($response->getBody(), true);
        return view('Suggestions.products.index', compact('ProductType'));
    }

    public function dataTable($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $ProductTypes = json_decode($response->getBody(), true);
        return Datatables::of($ProductTypes['data'])->setRowId(function ($batches) {
            return $batches['id'];
        })->addColumn('action', function ($row) {
            $dataExist = [
                'user_id' => $this->loggedIn,
                'apiId'   => $row['id'],
                'status'  => 0,
            ];
            $count = ProductType::where($dataExist)->count();
            if ($count == 0) {
                $data = '<div class="tableAdminOption">';
                $data .= '<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                        <a id="editBank" href="'.url('all/suggestion-products-index/edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
                                    </span>';
            }
            else {
                $data = '<a class="badge badge-danger text-white p-2"> لديك مقترح لم يتم تقييمه <i class="fas fa-exclamation"></i></a>';
            }
            return $data;
        })->make(true);
    }

    public function edit($id)
    {
        //To check dublicate
        $dataExist = [
            'user_id' => auth()->user()->id,
            'apiId'   => $id,
            'status'  => 0,
        ];
        $count = ProductType::where($dataExist)->count();
        if ($count == 0) {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType/'.$id;
            $response = $client->get($url, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $productType = json_decode($response->getBody(), true);
            return view('Suggestions.products.edit', compact('productType'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function update(Request $request)
    {
        $rules = [
            'name_ar'                => 'required',
            'name_en'                => 'required',
            'code'                   => 'required',
            'first_batch_percentage' => 'nullable',
        ];
        $customMessages = [
            'name_ar.required' => 'الإسم العربي مطلوب',
            'name_en.required' => 'الإسم الإنجليزي مطلوب',
            'code.required'    => 'الكود مطلوب',
        ];
        if ($request->first_batch_percentage > 999) {
            session()->flash('first_batch_percentage_error', 'نسبة الدفعة الأولي لا يجب ان تتجاوز قيمة 999');
            return redirect()->back();
        }
        if (($request->active != 1) && ($request->property_status != 1)) {
            $property_status = false;
            $active = false;
        }
        elseif (($request->active != 1) && ($request->property_status === "1")) {
            $property_status = true;
            $active = false;
        }
        elseif (($request->active === "1") && ($request->property_status != 1)) {
            $property_status = false;
            $active = true;
        }
        else {
            $property_status = true;
            $active = true;
        }
        $this->validate($request, $rules, $customMessages);

        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $productType = json_decode($response->getBody(), true)['data'];
        $input = [
            "value" => $productType['value'],
            "key"   => $productType['key'],
            "text"  => $productType['text'],

            'name_ar'                          => $request->name_ar,
            'name_en'                          => $request->name_en,
            'code'                             => $request->code,
            'first_batch_percentage'           => $request->first_batch_percentage,
            'first_batch_percentage_to_string' => $request->first_batch_percentage,
            'property_status'                  => $property_status == true ? 1 : 0,
            'property_status_to_string'        => $property_status == true ? 'نعم' : 'لا',
            'active'                           => $active == true ? 1 : 0,
            'active_to_string'                 => $active == true ? 'نعم' : 'لا',

            'name_ar_api'                          => $productType['name_ar'],
            'name_en_api'                          => $productType['name_en'],
            'code_api'                             => $productType['code'],
            'first_batch_percentage_api'           => $productType['first_batch_percentage'],
            'first_batch_percentage_to_string_api' => $productType['first_batch_percentage_to_string'],
            'property_status_api'                  => $productType['property_status'],
            'property_status_to_string_api'        => $productType['property_status_to_string'],
            'active_api'                           => $productType['active'],
            'active_to_string_api'                 => $productType['active_to_string'],
        ];

        $input['user_id'] = auth()->user()->id;
        $input['apiId'] = $productType['id'];

        unset($productType['id']);
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $input['status'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        $new = ProductType::firstOrCreate($input, $input);
        if ($input['approved'] == 1) {
            //----------------------------------------------------
            // IsverifiedUser Post To API
            //----------------------------------------------------
            if (($request->active != 1) && ($request->property_status != 1)) {
                $property_status = false;
                $active = false;
            }
            elseif (($request->active != 1) && ($request->property_status === "1")) {
                $property_status = true;
                $active = false;
            }
            elseif (($request->active === "1") && ($request->property_status != 1)) {
                $property_status = false;
                $active = true;
            }
            else {
                $property_status = true;
                $active = true;
            }
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType/'.$new->apiId;
            $response = $client->put($url, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'name_ar'                => $request->name_ar,
                    'name_en'                => $request->name_en,
                    'code'                   => $request->code,
                    'first_batch_percentage' => $request->first_batch_percentage,
                    'property_status'        => $property_status,
                    'active'                 => $active,
                ],
            ]);
        }
        //**********************************************************************
        // Task-38
        //**********************************************************************
        foreach (EditCalculationFormulaUser::where(['type' => 0])->get() as $item) {
            if ($item->user_id != auth()->id()) {
                DB::table('notifications')->insert([
                    'value'         => 'تم إضافة اقتراح تعديل للحسبة',
                    'recived_id'    => $item->user_id,
                    'receiver_type' => 'web',
                    'created_at'    => (Carbon::now('Asia/Riyadh')),
                    'type'          => 20,
                    'reminder_date' => null,
                    'req_id'        => $new->id,
                ]);
            }
        }
        //Notify Admin
        $admins = MyHelpers::getAllActiveAdmin();
        #send notifiy to admin
        if (EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth == 0) {
            foreach ($admins as $admin) {
                DB::table('notifications')->insert([
                    'value'         => 'تم إضافة اقتراح تعديل للحسبة',
                    'recived_id'    => $admin->id,
                    'receiver_type' => 'web',
                    'created_at'    => (Carbon::now('Asia/Riyadh')),
                    'type'          => 20,
                    'reminder_date' => null,
                    'req_id'        => $new->id,
                ]);

                $emailNotify = MyHelpers::sendEmailNotifiaction('Suggestion_on_calculator', $admin->id, ' اقتراح تعديل على الحاسبة ', 'تم إضافة اقتراح تعديل على الحاسبة');
            }
        }

        //Notify Admin
        if ($response) {
            return redirect()->route('all.suggestion.products')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }
}
