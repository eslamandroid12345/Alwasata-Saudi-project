<?php

namespace App\Http\Controllers\Suggestion;

use App\EditCalculationFormulaUser;
use App\FundingYear;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class AdminExtraFundingYearController extends Controller
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

    public function extraFundingYearIndex()
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $extraFundingYears = json_decode($response->getBody(), true);
        return view('Suggestions.ExtraFundingYear.index', compact('extraFundingYears'));
    }

    public function extraFundingYearDataTables($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $extraFundingYears = json_decode($response->getBody(), true);
        return Datatables::of($extraFundingYears['data'])->setRowId(function ($extraFundingYear) {
            return $extraFundingYear['id'];
        })
            ->addColumn('action', function ($row) {
                $dataExist = [
                    'user_id' => $this->loggedIn,
                    'apiId'   => $row['id'],
                    'status'  => 0,
                ];
                $count = FundingYear::where($dataExist)->count();
                if ($count == 0) {
                    $data = '<div class="tableAdminOption">';
                    $data .= '<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                    <a id="editBank" href="'.url('all/suggestion-funding-year-edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
                                </span>';
                }
                else {
                    $data = '<a class="badge badge-danger text-white p-2"> لديك مقترح لم يتم تقييمه <i class="fas fa-exclamation"></i></a>';
                }

                return $data;
            })->make(true);
    }

    public function editExtraFundingPage($id)
    {
        //To check dublicate
        $dataExist = [
            'user_id' => auth()->user()->id,
            'apiId'   => $id,
            'status'  => 0,
        ];
        $count = FundingYear::where($dataExist)->count();
        ///

        if ($count == 0) {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear/'.$id;
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
            $extraFundingYear = json_decode($response->getBody(), true);
            return view('Suggestions.ExtraFundingYear.edit', compact('extraFundingYear', 'banks', 'jobPositions'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function updateExtraFunding(Request $request)
    {

        $rules = [
            'bank_id'                => 'required',
            'job_position_id'        => 'required',
            'years'                  => 'required',
            //************************************************************
            // Task-28 Added
            //************************************************************
            'from_retirement_months' => 'required|integer',
            'to_retirement_months'   => 'required|integer',
            'from_age'               => 'required|integer',
            'from_salary'            => 'required|integer',
            'to_age'                 => 'required|integer|gte:from_age',
            'to_salary'              => 'required|integer|gte:to_salary',
        ];
        $customMessages = [
            'bank_id.required'                => 'البنك مطلوب',
            'job_position_id.required'        => 'اسم العمل مطلوب',
            'years.required'                  => 'عدد السنوات مطلوب',
            //************************************************************
            // Task-28 Added
            //************************************************************
            'from_age.required'               => 'السنة ( من ) مطلوبة',
            'from_retirement_months.required' => 'الشهر ( من ) مطلوبة',
            'to_retirement_months.required'   => 'الشهر ( من ) مطلوبة',
            'from_salary.required'            => 'صافي الراتب ( من ) مطلوبة',
            'from_age.integer'                => 'السن ( من ) يجب ان تكون عدد صحيح',
            'from_salary.integer'             => 'صافي الراتب ( من ) يجب ان تكون عدد صحيح',
            'to_age.integer'                  => 'السن ( إلي ) يجب ان تكون عدد صحيح',
            'to_salary.integer'               => 'صافي الراتب ( إلي ) يجب ان تكون عدد صحيح',
            'to_age.required'                 => 'السن ( إلي ) مطلوبة',
            'to_salary.required'              => 'صافي الراتب ( إلي ) مطلوبة',
            'to_age.gte'                      => 'السن (إلى) يجب ان تكون اكبر من السنة ( من ) او تساويها ',
        ];

        $this->validate($request, $rules, $customMessages);
        $client = new Client();
        // Get ExtraFundingYear Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);

        $extraFundingYear = json_decode($response->getBody(), true)['data'];
        // Get JobPosition Data
        $jobPositionsUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition/'.$request->job_position_id;
        $jobResponse = $client->get($jobPositionsUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $jobPositions = json_decode($jobResponse->getBody(), true)['data'];
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
        $input = [
            "text"              => $bank['text'],
            "bank_id"           => $bank['id'],
            "bank_id_to_string" => $bank['text'],
            "bank_code"         => $bank['code'],

            "text_api"              => $extraFundingYear['text'],
            "bank_id_api"           => $extraFundingYear['bank_id'],
            "bank_id_to_string_api" => $extraFundingYear['bank_id_to_string'],
            "bank_code_api"         => $extraFundingYear['bank_code'],
            "key"                   => $extraFundingYear['key'],
            "value"                 => $extraFundingYear['value'],

            "job_position_id"                   => $request->job_position_id,
            "job_position_id_api"               => $extraFundingYear['job_position_id'],
            "job_position_code"                 => $jobPositions['code'],
            "job_position_code_api"             => $extraFundingYear['job_position_code'],
            "job_position_id_to_string"         => $jobPositions['text'],
            "job_position_id_to_string_api"     => $extraFundingYear['job_position_id_to_string'],
            "years"                             => $request->years,
            "years_to_string"                   => $request->years.' سنوات ',
            "years_api"                         => $extraFundingYear['years'],
            "years_to_string_api"               => $extraFundingYear['years_to_string'],
            "personal"                          => $request->has('personal') ? $request->personal : 0,
            "personal_api"                      => $extraFundingYear['personal'],
            "personal_to_string"                => $extraFundingYear['personal_to_string'],
            "personal_to_string_api"            => $extraFundingYear['personal_to_string'],
            "guarantees_to_string_api"          => $extraFundingYear['guarantees_to_string'],
            "residential_support_to_string_api" => $extraFundingYear['residential_support_to_string'],
            //*******************************************************
            // Task-28
            //*******************************************************
            "extended"                          => $request->has('extended') ? $request->extended : 0,
            "extended_to_string"                => $request->has('extended') ? 'نعم' : 'لا',
            "after_retirement"                  => $request->has('after_retirement') ? $request->after_retirement : 0,
            "from_salary"                       => $request->from_salary,
            "to_salary"                         => $request->to_salary,
            "from_age"                          => $request->from_age,
            "to_age"                            => $request->to_age,
            "from_retirement_months"            => $request->from_retirement_months,
            "to_retirement_months"              => $request->to_retirement_months,
            "extended_api"                      => $extraFundingYear['extended'],
            "after_retirement_api"              => $extraFundingYear['after_retirement'],
            "after_retirement_to_string_api"    => $extraFundingYear['after_retirement_to_string'],
            "from_salary_api"                   => $extraFundingYear['from_salary'],
            "to_salary_api"                     => $extraFundingYear['to_salary'],
            "from_age_api"                      => $extraFundingYear['from_age'],
            "to_age_api"                        => $extraFundingYear['to_age'],
            "extended_to_string_api"            => $extraFundingYear['extended_to_string'],
            "from_retirement_months_api"        => $extraFundingYear['from_retirement_months'],
            "to_retirement_months_api"          => $extraFundingYear['to_retirement_months'],
        ];

        $input['user_id'] = auth()->user()->id;
        $input['apiId'] = $extraFundingYear['id'];
        unset($extraFundingYear['id']);
        if (($request->residential_support != 1) && ($request->guarantees != 1)) {
            $input['guarantees'] = false;
            $input['residential_support'] = false;
            $input['guarantees_to_string'] = 'لا';
            $input['residential_support_to_string'] = 'لا';
        }
        elseif (($request->residential_support != 1) && ($request->guarantees === "1")) {
            $input['guarantees'] = false;
            $input['residential_support'] = false;
            $input['guarantees_to_string'] = 'لا';
            $input['residential_support_to_string'] = 'لا';
        }
        elseif (($request->residential_support === "1") && ($request->guarantees != 1)) {
            $input['guarantees'] = false;
            $input['residential_support'] = false;
            $input['guarantees_to_string'] = 'لا';
            $input['residential_support_to_string'] = 'لا';
        }
        else {
            $input['guarantees'] = true;
            $input['residential_support'] = true;
            $input['guarantees_to_string'] = 'نعم';
            $input['residential_support_to_string'] = 'نعم';
        }
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $input['status'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        //*************************************************
        // Task-22 $input [because API Added Fileds]
        //*************************************************
        $new = FundingYear::firstOrCreate($input, $input);
        if ($input['approved'] == 1) {
            //----------------------------------------------------
            // IsverifiedUser Post To API
            //----------------------------------------------------
            if ($request->residential_support == '') {
                $residential_support = 0;
            }
            else {
                $residential_support = 1;
            }
            if ($request->personal == '') {
                $personal = 0;
            }
            else {
                $personal = 1;
            }
            if ($request->guarantees == '') {
                $guarantees = 0;
            }
            else {
                $guarantees = 1;
            }
            if ($request->extended == '') {
                $extended = 0;
            }
            else {
                $extended = 1;
            }
            if ($request->after_retirement == '') {
                $after_retirement = 0;
            }
            else {
                $after_retirement = 1;
            }
            $client = new Client();
            $extraFundingYearUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear/'.$new->apiId;
            $response = $client->put($extraFundingYearUrl, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'bank_id'                => $request->bank_id,
                    'job_position_id'        => $request->job_position_id,
                    'years'                  => $request->years,
                    'residential_support'    => $residential_support,
                    'guarantees'             => $guarantees,
                    'personal'               => $personal,
                    'extended'               => $extended,
                    'from_salary'            => $request->from_salary,
                    'to_salary'              => $request->to_salary,
                    'from_age'               => $request->from_age,
                    'to_age'                 => $request->to_age,
                    'after_retirement'       => $after_retirement,
                    'from_retirement_months' => $request->from_retirement_months,
                    'to_retirement_months'   => $request->to_retirement_months,
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
        //*************************************************
        // Task-22 send only if user unauthrized
        //*************************************************
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
            return redirect()->route('all.suggestions.extraFundingYearIndex')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }
}
