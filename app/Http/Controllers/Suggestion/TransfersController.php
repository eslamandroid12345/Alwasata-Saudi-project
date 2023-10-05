<?php

namespace App\Http\Controllers\Suggestion;

use App\CheckTotal;
use App\EditCalculationFormulaUser;
use App\Http\Controllers\Controller;
use App\PropertyStatusRule;
use App\WithoutTransfer;
use Carbon\Carbon;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class TransfersController extends Controller
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
        $rules = json_decode($response->getBody(), true);
        return view('Suggestions.transfers.index', compact('rules'));
    }

    public function dataTable($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer?itemsPerPage=-1';
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
            $count = WithoutTransfer::where($dataExist)->count();
            if ($count == 0) {
                $data = '<div class="tableAdminOption">';
                $data .= '<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                        <a id="editBank" href="'.url('all/suggestion-transfers-index/edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
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
        $count = WithoutTransfer::where($dataExist)->count();
        if ($count == 0) {
            $client = new Client();
            $ruleUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer/'.$id;
            $bankUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
            $jobPositionUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
            $ruleResponse = $client->get($ruleUrl, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $bankResponse = $client->get($bankUrl, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $jobPositionResponse = $client->get($jobPositionUrl, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $rule = json_decode($ruleResponse->getBody(), true);

            $banks = json_decode($bankResponse->getBody(), true);
            $jobs = json_decode($jobPositionResponse->getBody(), true);
            return view('Suggestions.transfers.edit', compact('banks', 'jobs', 'rule'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function update(Request $request)
    {
        $rules = [
            'bank_id'                => 'required',
            'job_position_id'        => 'required',
            'first_batch_percentage' => 'required|integer|max:999',
        ];
        $customMessages = [
            'bank_id.required'                => 'جهة التمويل مطلوب *',
            'job_position_id.required'        => 'جهة العمل / القطاع مطلوب *',
            'first_batch_percentage.required' => 'نسبة الدفعة الأولي مطلوب *',
            'first_batch_percentage.integer'  => 'نسبة الدفعة الأولي يجب ان تكون رقماً صحيحا',
            'first_batch_percentage.max'      => 'يجب ألا تتجاوز نسبة الدفعة الأولي 999',
        ];
        $this->validate($request, $rules, $customMessages);
        $client = new Client();
        $ruleUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer/'.$request->id;

        $ruleResponse = $client->get($ruleUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $rule = json_decode($ruleResponse->getBody(), true)['data'];
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->bank_id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);

        $bank = json_decode($response->getBody(), true)['data'];
        $jobPositionsUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition/'.$request->job_position_id;
        $jobResponse = $client->get($jobPositionsUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);

        $jobPositions = json_decode($jobResponse->getBody(), true)['data'];
        $input = [
            "text"              => $bank['text'],
            "bank_id_to_string" => $bank['text'],
            "bank_code"         => $bank['code'],
            "bank_id"           => $bank['id'],

            "job_position_id"                  => $jobPositions['id'],
            "job_position_code"                => $jobPositions['code'],
            "job_position_id_to_string"        => $jobPositions['text'],
            "first_batch_percentage"           => $request->first_batch_percentage,
            "first_batch_percentage_to_string" => $request->first_batch_percentage,

            "text_api"                             => $rule['bank_id_to_string'],
            "bank_id_to_string_api"                => $rule['bank_id_to_string'],
            "bank_code_api"                        => $rule['bank_code'],
            "bank_id_api"                          => $rule['bank_id'],
            "job_position_id_api"                  => $rule['job_position_id'],
            "job_position_code_api"                => $rule['job_position_code'],
            "job_position_id_to_string_api"        => $rule['job_position_id_to_string'],
            "first_batch_percentage_api"           => $rule['first_batch_percentage'],
            "first_batch_percentage_to_string_api" => $rule['first_batch_percentage_to_string'],
        ];

        $input['user_id'] = auth()->user()->id;
        $input['apiId'] = $rule['id'];

        unset($rule['id']);
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $input['status'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        $new = WithoutTransfer::firstOrCreate($input, $input);
        if ($input['approved'] == 1) {
            //----------------------------------------------------
            // IsverifiedUser Post To API
            //----------------------------------------------------
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer/'.$new->apiId;

            $response = $client->put($url, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'bank_id'                => $request->bank_id,
                    'job_position_id'        => $request->job_position_id,
                    'first_batch_percentage' => $request->first_batch_percentage,
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
            return redirect()->route('all.suggestion.transfers')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }

}
