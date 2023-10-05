<?php

namespace App\Http\Controllers\Suggestion;

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

class BatchesController extends Controller
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
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/FirstBatch?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $firstBatch = json_decode($response->getBody(), true);
        return view('Suggestions.batches.index', compact('firstBatch'));
    }

    public function dataTable($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/FirstBatch?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $batches = json_decode($response->getBody(), true);
        return Datatables::of($batches['data'])->setRowId(function ($batches) {
            return $batches['id'];
        })->addColumn('action', function ($row) {
            $dataExist = [
                'user_id' => $this->loggedIn,
                'apiId'   => $row['id'],
                'status'  => 0,
            ];
            $count = FirstBatch::where($dataExist)->count();
            if ($count == 0) {
                $data = '<div class="tableAdminOption">';
                $data .= '<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                        <a id="editBank" href="'.url('all/suggestion-batches-index/edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
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
        $count = FirstBatch::where($dataExist)->count();
        if ($count == 0) {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/FirstBatch/'.$id;
            $response = $client->get($url, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $bankUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
            $bankResponse = $client->get($bankUrl, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $firstBatch = json_decode($response->getBody(), true);
            $banks = json_decode($bankResponse->getBody(), true);
            return view('Suggestions.batches.edit', compact('firstBatch', 'banks'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function update(Request $request)
    {
        $rules = [
            'bank_id'              => 'required',
            'percent'              => 'required|integer|max:999',
            'from_property_amount' => 'required|integer',
            'to_property_amount'   => 'required|integer|gte:from_property_amount',
            'residence_type'       => 'required',
            'secured'              => 'required',
        ];
        $customMessages = [
            'bank_id.required'              => 'جهة التمويل مطلوب',
            'percent.required'              => 'النسبة مطلوب',
            'percent.max'                   => 'النسبة يجب ان لا تتجاوز 999',
            'from_property_amount.required' => 'قيمة العقار - من مطلوب',
            'from_property_amount.integer'  => 'قيمة العقار - من يجب ان تكون رقم صحيح',
            'to_property_amount.required'   => 'قيمة العقار - إلي مطلوب',
            'to_property_amount.integer'    => 'قيمة العقار - إلي يجب ان تكون رقم صحيح',
            'to_property_amount.gte'        => 'قيمة العقار - إلي يجب ان تكون أكبر من او يساوي قيمة العقار - من',
            'residence_type.required'       => 'حقل المسكن مطلوب',
            'secured.required'              => 'حقل مضمون مطلوب',
        ];
        $this->validate($request, $rules, $customMessages);
        $client = new Client();
        // Get Batch Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/FirstBatch/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $firstBatch = json_decode($response->getBody(), true)['data'];
        // Get Bank Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->bank_id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $bank = json_decode($response->getBody(), true)['data'];
        // Get JobPosition Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition/'.$request->job_position_id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);

        $jobPosition = json_decode($response->getBody(), true)['data'];
        $input = [
            "text"              => $bank['text'],
            "key"               => $bank['key'],
            "value"             => $bank['value'],
            "bank_id"           => $bank['id'],
            "bank_id_to_string" => $bank['text'],
            "bank_code"         => $bank['code'],

            "residential_support"            => $request->residence_type,
            "residential_support_to_string"  => $request->residence_type,
            "percent"                        => $request->percent,
            "percent_to_string"              => $request->percent,
            "percentage"                     => $request->percent / 100,
            "from_property_amount"           => $request->from_property_amount,
            "from_property_amount_to_string" => $request->from_property_amount,
            "to_property_amount"             => $request->to_property_amount,
            "to_property_amount_to_string"   => $request->to_property_amount,
            "secured"                        => $request->secured,
            "secured_to_string"              => $request->secured == 0 ? 'لا' : 'نعم',

            "bank_id_api"                        => $firstBatch['bank_id'],
            "bank_id_to_string_api"              => $firstBatch['bank_id_to_string'],
            "bank_code_api"                      => $firstBatch['bank_code'],
            "residential_support_api"            => $firstBatch['residence_type'],
            "residential_support_to_string_api"  => $firstBatch['residence_type_to_string'],
            "percent_api"                        => $firstBatch['percent'],
            "percent_to_string_api"              => $firstBatch['percent_to_string'],
            "percentage_api"                     => $firstBatch['percentage'],
            "from_property_amount_api"           => $firstBatch['from_property_amount'],
            "from_property_amount_to_string_api" => $firstBatch['from_property_amount_to_string'],
            "to_property_amount_api"             => $firstBatch['to_property_amount'],
            "to_property_amount_to_string_api"   => $firstBatch['to_property_amount_to_string'],
            "secured_api"                        => $firstBatch['secured'],
            "secured_to_string_api"              => $firstBatch['secured_to_string'],
        ];

        $input['user_id'] = auth()->user()->id;
        $input['apiId'] = $firstBatch['id'];

        unset($firstBatch['id']);
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $input['status'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        $new = FirstBatch::firstOrCreate($input, $input);
        if ($input['approved'] == 1) {
            //----------------------------------------------------
            // IsverifiedUser Post To API
            //----------------------------------------------------
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/FirstBatch/'.$new->apiId;
            $response = $client->put($url, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'bank_id'              => $request->bank_id,
                    'percent'              => $request->percent,
                    'from_property_amount' => $request->from_property_amount,
                    'to_property_amount'   => $request->to_property_amount,
                    'residence_type'       => $request->residence_type,
                    'secured'              => $request->secured,
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
            return redirect()->route('all.suggestion.batches')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }
}
