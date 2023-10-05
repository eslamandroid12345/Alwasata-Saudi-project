<?php

namespace App\Http\Controllers\Suggestion;

use App\CheckTotal;
use App\EditCalculationFormulaUser;
use App\FirstBatch;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class ChecksController extends Controller
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
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductTypeCheckTotal?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $productTypeCheckTotal = json_decode($response->getBody(), true);
        return view('Suggestions.checks.index', compact('productTypeCheckTotal'));
    }

    public function dataTable($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductTypeCheckTotal?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $checks = json_decode($response->getBody(), true);
        return Datatables::of($checks['data'])->setRowId(function ($batches) {
            return $batches['id'];
        })->addColumn('action', function ($row) {
            $dataExist = [
                'user_id' => $this->loggedIn,
                'apiId'   => $row['id'],
                'status'  => 0,
            ];
            $count = CheckTotal::where($dataExist)->count();
            if ($count == 0) {
                $data = '<div class="tableAdminOption">';
                $data .= '<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                        <a id="editBank" href="'.url('all/suggestion-checks-index/edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
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
        $count = CheckTotal::where($dataExist)->count();
        if ($count == 0) {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductTypeCheckTotal/'.$id;
            $response = $client->get($url, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $productTypeUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType?itemsPerPage=-1';
            $productTypeResponse = $client->get($productTypeUrl, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $productTypeCheckTotal = json_decode($response->getBody(), true);
            $productTypes = json_decode($productTypeResponse->getBody(), true);

            return view('Suggestions.checks.edit', compact('productTypes', 'productTypeCheckTotal'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function update(Request $request)
    {

        $rules = [
            'product_type_id' => 'required',
            'percentage'      => 'required|integer|max:999',
        ];
        $customMessages = [
            'product_type_id.required' => 'نوع المنتج مطلوب',
            'percentage.required'      => 'النسبة مطلوب',
            'percentage.max'           => 'النسبة يجب ان لا تتجاوز 999',
        ];
        $this->validate($request, $rules, $customMessages);
        $client = new Client();
        // Get ProductType Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductType/'.$request->product_type_id;
        $responses = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        // Get ProductTypeCheckTotal Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductTypeCheckTotal/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $productTypeCheckTotal = json_decode($response->getBody(), true)['data'];

        $productType = json_decode($responses->getBody(), true)['data'];
        if ($request->residential_support == '') {
            $residential_support = 0;
        }
        else {
            $residential_support = 1;
        }

        $input = [
            "text"                          => $productType['text'],
            "key"                           => $productType['key'],
            "value"                         => $productType['value'],
            "product_type_id"               => $productType['id'],
            "product_type_code"             => $productType['code'],
            "product_type_id_to_string"     => $productType['text'],
            "percentage"                    => $request->percentage,
            "percentage_to_string"          => $request->percentage,
            "residential_support"           => $residential_support,
            "residential_support_to_string" => $residential_support == 0 ? 'لا' : 'نعم',

            "product_type_id_api"               => $productTypeCheckTotal['product_type_id'],
            "product_type_code_api"             => $productTypeCheckTotal['product_type_code'],
            "product_type_id_to_string_api"     => $productTypeCheckTotal['product_type_id_to_string'],
            "percentage_api"                    => $productTypeCheckTotal['product_type_id_to_string'],
            "percentage_to_string_api"          => $productTypeCheckTotal['percentage_to_string'],
            "residential_support_api"           => $productTypeCheckTotal['residential_support'],
            "residential_support_to_string_api" => $productTypeCheckTotal['residential_support_to_string'],
        ];

        $input['user_id'] = auth()->user()->id;
        $input['apiId'] = $productTypeCheckTotal['id'];

        unset($productTypeCheckTotal['id']);
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $input['status'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        $new = CheckTotal::firstOrCreate($input, $input);
        if ($input['approved'] == 1) {
            //----------------------------------------------------
            // IsverifiedUser Post To API
            //----------------------------------------------------
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProductTypeCheckTotal/'.$new->apiId;
            if ($request->residential_support == '') {
                $residential_support = 0;
            }
            else {
                $residential_support = 1;
            }

            $response = $client->put($url, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'product_type_id'     => $request->product_type_id,
                    'percentage'          => $request->percentage,
                    'residential_support' => $residential_support,
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
            return redirect()->route('all.suggestion.checks')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

}
