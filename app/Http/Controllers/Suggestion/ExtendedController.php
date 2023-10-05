<?php

namespace App\Http\Controllers\Suggestion;

use App\AvailableExtended;
use App\CheckTotal;
use App\EditCalculationFormulaUser;
use App\Http\Controllers\Controller;
use App\SalaryEquation;
use Carbon\Carbon;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class ExtendedController extends Controller
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
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/AvailableExtend?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $availableExtended = json_decode($response->getBody(), true);
        return view('Suggestions.extended.index', compact('availableExtended'));
    }

    public function dataTable($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/AvailableExtend?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $extends = json_decode($response->getBody(), true);
        return Datatables::of($extends['data'])->setRowId(function ($batches) {
            return $batches['id'];
        })->addColumn('action', function ($row) {
            $dataExist = [
                'user_id' => $this->loggedIn,
                'apiId'   => $row['id'],
                'status'  => 0,
            ];
            $count = AvailableExtended::where($dataExist)->count();
            if ($count == 0) {
                $data = '<div class="tableAdminOption">';
                $data .= '<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                        <a id="editBank" href="'.url('all/suggestion-extended-index/edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
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
        $count = AvailableExtended::where($dataExist)->count();
        if ($count == 0) {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/AvailableExtend/'.$id;
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
            $jobPositionsUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
            $jobResponse = $client->get($jobPositionsUrl, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $jobPositions = json_decode($jobResponse->getBody(), true);
            $banks = json_decode($bankResponse->getBody(), true);
            $availableExtend = json_decode($response->getBody(), true);
            return view('Suggestions.extended.edit', compact('banks', 'availableExtend', 'jobPositions'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function update(Request $request)
    {
        $rules = [
            'bank_id'         => 'required',
            'job_position_id' => 'required',
            'funding_age'     => 'required|integer',
        ];
        $customMessages = [
            'bank_id.required'         => 'حقل جهة التمويل مطلوب *',
            'job_position_id.required' => 'جهة العمل / القطاع مطلوب *',
            'funding_age.required'     => 'حقل عمر التمويل مطلوب *',
            'funding_age.integer'      => 'حقل عمر التمويل يجب ان يكون عدداً صحيحاً',
        ];
        if ($request->basic_salary == '') {
            $basic_salary = 0;
        }
        else {
            $basic_salary = 1;
        }
        if ($request->residential_support == '') {
            $residential_support = 0;
        }
        else {
            $residential_support = 1;
        }
        $this->validate($request, $rules, $customMessages);
        $client = new Client();
        // Get SalaryDeduction Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/AvailableExtend/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $availableExtend = json_decode($response->getBody(), true)['data'];
        // Get Bank Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->bank_id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $banks = json_decode($response->getBody(), true)['data'];

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
            'text'                          => $banks['text'],
            'bank_id'                       => $banks['id'],
            'bank_code'                     => $banks['code'],
            'bank_id_to_string'             => $banks['text'],
            "job_position_id"               => $jobPosition['id'],
            "job_position_code"             => $jobPosition['code'],
            "job_position_id_to_string"     => $jobPosition['text'],
            "basic_salary"                  => $basic_salary,
            "basic_salary_to_string"        => $basic_salary,
            "residential_support"           => $residential_support,
            "residential_support_to_string" => $residential_support == 0 ? 'لا' : 'نعم',
            "funding_age"                   => $request->funding_age,
            "funding_age_to_string"         => $request->funding_age,

            'text_api'                          => $availableExtend['text'],
            'bank_id_api'                       => $availableExtend['bank_id'],
            'bank_code_api'                     => $availableExtend['bank_code'],
            'bank_id_to_string_api'             => $availableExtend['bank_id_to_string'],
            "job_position_id_api"               => $availableExtend['job_position_id'],
            "job_position_code_api"             => $availableExtend['job_position_code'],
            "job_position_id_to_string_api"     => $availableExtend['job_position_id_to_string'],
            "basic_salary_api"                  => $availableExtend['basic_salary'],
            "basic_salary_to_string_api"        => $availableExtend['basic_salary_to_string'],
            "residential_support_api"           => $availableExtend['residential_support'],
            "residential_support_to_string_api" => $availableExtend['residential_support_to_string'],
            "funding_age_api"                   => $availableExtend['funding_age'],
            "funding_age_to_string_api"         => $availableExtend['funding_age_to_string'],
        ];

        $input['user_id'] = auth()->user()->id;
        $input['apiId'] = $availableExtend['id'];

        unset($availableExtend['id']);
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $input['status'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        $new = AvailableExtended::firstOrCreate($input, $input);
        if ($input['approved'] == 1) {
            //----------------------------------------------------
            // IsverifiedUser Post To API
            //----------------------------------------------------
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/AvailableExtend/'.$new->apiId;
            if ($request->basic_salary == '') {
                $basic_salary = 0;
            }
            else {
                $basic_salary = 1;
            }
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
                    'bank_id'             => $request->bank_id,
                    'job_position_id'     => $request->job_position_id,
                    'funding_age'         => $request->funding_age,
                    'basic_salary'        => $basic_salary,
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
            return redirect()->route('all.suggestion.extended')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));

    }
}
