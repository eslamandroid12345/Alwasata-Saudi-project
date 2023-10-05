<?php

namespace App\Http\Controllers\Suggestion;

use App\BankPercentage;
use App\EditCalculationFormulaUser;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class AdminProfitPercentageController extends Controller
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

    public function profitPercentageIndex()
    {
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);

        $profitPercentages = json_decode($response->getBody(), true);
        return view('Suggestions.profitPercentage.index', compact('profitPercentages'));
    }

    public function profitPercentageDataTables($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $profitPercentages = json_decode($response->getBody(), true);
        return Datatables::of($profitPercentages['data'])->setRowId(function ($profitPercentage) {
            return $profitPercentage['id'];
        })->addColumn('action', function ($row) {
            $dataExist = [
                'user_id' => $this->loggedIn,
                'apiId'   => $row['id'],
                'status'  => 0,
            ];
            $count = BankPercentage::where($dataExist)->count();
            if ($count == 0) {
                $data = '<div class="tableAdminOption">';
                $data = $data.'<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                        <a id="editBank" href="'.url('all/suggestion-percentage-index/edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
                                    </span>';
            }
            else {
                $data = '<a class="badge badge-danger text-white p-2"> لديك مقترح لم يتم تقييمه <i class="fas fa-exclamation"></i></a>';
            }
            return $data;
        })->make(true);
    }

    public function getProfitPercentageEditPage($id)
    {

        //To check dublicate
        $dataExist = [
            'user_id' => auth()->user()->id,
            'apiId'   => $id,
            'status'  => 0,
        ];
        $count = BankPercentage::where($dataExist)->count();
        ////

        if ($count == 0) {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$id;
            $bankUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
            $jobPositionsUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';

            $response = $client->get($url, [
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
            // *****************************************
            // Task-28
            // *****************************************
            $jobResponse = $client->get($jobPositionsUrl, [
                'headers' => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
            ]);
            $jobPositions = json_decode($jobResponse->getBody(), true);
            $banks = json_decode($bankResponse->getBody(), true);
            $profitPercentage = json_decode($response->getBody(), true);

            return view('Suggestions.profitPercentage.edit', compact('banks', 'profitPercentage', 'jobPositions'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function updateProfitPercentage(Request $request)
    {

        $rules = [
            'bank_id'     => 'required',
            'from_year'   => 'required|integer',
            'from_salary' => 'required|integer',
            'to_year'     => 'required|integer|gte:from_year',
            'to_salary'   => 'required|integer|gte:to_salary',
            'percentage'  => 'required|numeric',
        ];
        $customMessages = [
            'bank_id.required'     => 'البنك مطلوب',
            'percentage.required'  => 'النسبة مطلوب',
            'percentage.numeric'   => 'النسبة لا تقبل إلا ارقام فقط',
            'from_year.required'   => 'السنة ( من ) مطلوبة',
            'from_salary.required' => 'صافي الراتب ( من ) مطلوبة',
            'from_year.integer'    => 'السنة ( من ) يجب ان تكون عدد صحيح',
            'from_salary.integer'  => 'صافي الراتب ( من ) يجب ان تكون عدد صحيح',
            'to_year.integer'      => 'السنة ( إلي ) يجب ان تكون عدد صحيح',
            'to_salary.integer'    => 'صافي الراتب ( إلي ) يجب ان تكون عدد صحيح',
            'to_year.required'     => 'السنة ( إلي ) مطلوبة',
            'to_salary.required'   => 'صافي الراتب ( إلي ) مطلوبة',
            'to_year.gte'          => 'السنة (إلى) يجب ان تكون اكبر من السنة ( من ) او تساويها ',
        ];

        $this->validate($request, $rules, $customMessages);
        $client = new Client();
        // Get profitPercentage Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);

        $profitPercentage = json_decode($response->getBody(), true)['data'];
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
        $data = [
            "key"   => $profitPercentage['key'],
            "value" => $profitPercentage['value'],

            "text"              => $bank['text'],
            "bank_id"           => $bank['id'],
            "bank_id_to_string" => $bank['text'],
            "bank_code"         => $bank['code'],

            "text_api"              => $profitPercentage['text'],
            "bank_id_api"           => $profitPercentage['bank_id'],
            "bank_id_to_string_api" => $profitPercentage['bank_id_to_string'],
            "bank_code_api"         => $profitPercentage['bank_code'],

            "from_year"                         => $request->has('from_year') ? $request->from_year : 0,
            "to_year"                           => $request->has('to_year') ? $request->to_year : 0,
            "percentage"                        => $request->has('percentage') ? $request->percentage : 0,
            "guarantees"                        => $request->has('guarantees') ? $request->guarantees : 0,
            "personal"                          => $request->has('personal') ? $request->personal : 0,
            "residential_support"               => $request->has('residential_support') ? $request->residential_support : 0,
            "personal_to_string"                => $request->has('personal') ? 'نعم' : 'لا',
            "guarantees_to_string"              => $request->has('guarantees') ? 'نعم' : 'لا',
            "residential_support_to_string"     => $request->has('residential_support') ? 'نعم' : 'لا',
            "to_year_api"                       => $profitPercentage['to_year'],
            "from_year_api"                     => $profitPercentage['from_year'],
            "percentage_api"                    => $profitPercentage['percentage'],
            "personal_api"                      => $profitPercentage['personal'],
            "personal_to_string_api"            => $profitPercentage['personal_to_string'],
            "guarantees_to_string_api"          => $profitPercentage['guarantees_to_string'],
            "residential_support_to_string_api" => $profitPercentage['residential_support_to_string'],
            //*******************************************************************
            // Task-28 Added
            //*******************************************************************
            "to_salary"                         => $request->has('to_salary') ? $request->to_salary : 0,
            "from_salary"                       => $request->has('from_salary') ? $request->from_salary : 0,
            "secured"                           => $request->has('secured') ? $request->secured : 0,
            "secured_to_string"                 => $request->has('secured') ? 'نعم' : 'لا',
            "to_salary_api"                     => $profitPercentage['to_salary'],
            "from_salary_api"                   => $profitPercentage['from_salary'],
            "secured_api"                       => $profitPercentage['secured'],
            "secured_to_string_api"             => $profitPercentage['secured_to_string'],
        ];
        $data['user_id'] = auth()->user()->id;
        $data['apiId'] = $profitPercentage['id'];
        unset($profitPercentage['id']);
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $data['status'] = EditCalculationFormulaUser::where('user_id', auth()->id())->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        $new = BankPercentage::firstOrCreate($data, $data);
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
            if ($request->secured == '') {
                $secured = 0;
            }
            else {
                $secured = 1;
            }
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$new->apiId;
            $response = $client->put($url, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'bank_id'             => $request->bank_id,
                    'job_position_id'     => $request->job_position_id,
                    'from_year'           => $request->from_year,
                    'to_year'             => $request->to_year,
                    'percentage'          => $request->percentage,
                    'residential_support' => $residential_support,
                    'guarantees'          => $guarantees,
                    'personal'            => $personal,
                    'secured'             => $secured,
                    'from_salary'         => $request->from_salary,
                    'to_salary'           => $request->to_salary,
                ],
            ]);
        }
        //**********************************************************************
        // Task-38
        //**********************************************************************
        //$new->update($data);
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
            return redirect()->route('all.suggestions.profitPercentageIndex')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }
}
