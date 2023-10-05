<?php

namespace App\Http\Controllers\Suggestion;

use App\CheckTotal;
use App\EditCalculationFormulaUser;
use App\Http\Controllers\Controller;
use App\SalaryDeduction;
use Carbon\Carbon;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class DeductionsController extends Controller
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
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SalaryEquation?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $salaryDeduction = json_decode($response->getBody(), true);
        return view('Suggestions.deductions.index', compact('salaryDeduction'));
    }

    public function dataTable($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SalaryDeduction?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $deductions = json_decode($response->getBody(), true);
        return Datatables::of($deductions['data'])->setRowId(function ($batches) {
            return $batches['id'];
        })->addColumn('action', function ($row) {
            $dataExist = [
                'user_id' => $this->loggedIn,
                'apiId'   => $row['id'],
                'status'  => 0,
            ];
            $count = SalaryDeduction::where($dataExist)->count();
            if ($count == 0) {
                $data = '<div class="tableAdminOption">';
                $data .= '<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                        <a id="editBank" href="'.url('all/suggestion-deductions-index/edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
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
        $count = SalaryDeduction::where($dataExist)->count();
        if ($count == 0) {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SalaryDeduction/'.$id;
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
            $salaryDeduction = json_decode($response->getBody(), true);
            return view('Suggestions.deductions.edit', compact('banks', 'salaryDeduction', 'jobPositions'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function update(Request $request)
    {
        $rules = [
            'bank_id'          => 'required',
            'job_position_id'  => 'required',
            'salary_deduction' => 'required|integer',
            'from_salary'      => 'integer|nullable',
            'to_salary'        => 'integer|nullable',
        ];
        $customMessages = [
            'bank_id.required'          => 'حقل جهة التمويل مطلوب *',
            'job_position_id.required'  => 'جهة العمل / القطاع مطلوب *',
            'salary_deduction.required' => 'حقل نسبة استقطاع صافي الراتب مطلوب *',
            'salary_deduction.integer'  => 'يجب أن يكون نسبة استقطاع صافي الراتب عددًا صحيحًاً',
            'from_salary.integer'       => 'يجب أن يكون الراتب الأساسي من عددًا صحيحًاً',
            'to_salary.integer'         => 'يجب أن يكون الراتب الأساسي إلي عددًا صحيحًاً',
        ];
        if ($request->guarantees == '') {
            $guarantees = 0;
        }
        else {
            $guarantees = 1;
        }
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
        if ($request->flexible == '') {
            $flexible = 0;
        }
        else {
            $flexible = 1;
        }
        if ($request->secured == '') {
            $secured = 0;
        }
        else {
            $secured = 1;
        }
        $this->validate($request, $rules, $customMessages);
        $client = new Client();
        // Get SalaryDeduction Data
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SalaryDeduction/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $salaryDeduction = json_decode($response->getBody(), true)['data'];
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
        $jobPositions = json_decode($response->getBody(), true)['data'];

        $input = [
            'text'                              => $banks['text'],
            'bank_id'                           => $banks['id'],
            'bank_code'                         => $banks['code'],
            'bank_id_to_string'                 => $banks['text'],
            "job_position_id"                   => $jobPositions['id'],
            "job_position_code"                 => $jobPositions['code'],
            "job_position_id_to_string"         => $jobPositions['text'],
            "salary_deduction"                  => $request->salary_deduction,
            "salary_deduction_to_string"        => $request->salary_deduction,
            "guarantees"                        => $guarantees,
            "guarantees_to_string"              => $guarantees == 0 ? 'لا' : 'نعم',
            "residential_support"               => $residential_support,
            "residential_support_to_string"     => $residential_support == 0 ? 'لا' : 'نعم',
            "personal"                          => $personal,
            "personal_to_string"                => $personal == 0 ? 'لا' : 'نعم',
            "flexible"                          => $flexible,
            "flexible_to_string"                => $flexible == 0 ? 'لا' : 'نعم',
            "secured"                           => $secured,
            "secured_to_string"                 => $secured == 0 ? 'لا' : 'نعم',
            "from_salary"                       => $request->from_salary,
            "to_salary"                         => $request->to_salary,
            "salaries_to_string"                => $request->salaries_to_string,
            'text_api'                          => $salaryDeduction['bank_id_to_string'],
            'bank_id_api'                       => $salaryDeduction['bank_id_to_string'],
            'bank_code_api'                     => $salaryDeduction['bank_code'],
            'bank_id_to_string_api'             => $salaryDeduction['bank_id_to_string'],
            "job_position_id_api"               => $salaryDeduction['job_position_id'],
            "job_position_code_api"             => $salaryDeduction['job_position_code'],
            "job_position_id_to_string_api"     => $salaryDeduction['job_position_id_to_string'],
            "salary_deduction_api"              => $salaryDeduction['salary_deduction'],
            "salary_deduction_to_string_api"    => $salaryDeduction['salary_deduction_to_string'],
            "guarantees_api"                    => $salaryDeduction['guarantees'],
            "guarantees_to_string_api"          => $salaryDeduction['guarantees_to_string'],
            "residential_support_api"           => $salaryDeduction['residential_support'],
            "residential_support_to_string_api" => $salaryDeduction['residential_support_to_string'],
            "personal_api"                      => $salaryDeduction['personal'],
            "personal_to_string_api"            => $salaryDeduction['personal_to_string'],
            "flexible_api"                      => $salaryDeduction['flexible'],
            "flexible_to_string_api"            => $salaryDeduction['flexible_to_string'],
            "secured_api"                       => $salaryDeduction['secured'],
            "secured_to_string_api"             => $salaryDeduction['secured_to_string'],
            "from_salary_api"                   => $salaryDeduction['from_salary'],
            "to_salary_api"                     => $salaryDeduction['to_salary'],
            "salaries_to_string"                => $salaryDeduction['salaries_to_string'],
        ];

        $input['user_id'] = auth()->user()->id;
        $input['apiId'] = $salaryDeduction['id'];

        unset($salaryDeduction['id']);
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $input['status'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        $new = SalaryDeduction::firstOrCreate($input, $input);
        if ($input['approved'] == 1) {
            //----------------------------------------------------
            // IsverifiedUser Post To API
            //----------------------------------------------------
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SalaryDeduction/'.$new->apiId;
            if ($request->guarantees == '') {
                $guarantees = 0;
            }
            else {
                $guarantees = 1;
            }
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
            if ($request->flexible == '') {
                $flexible = 0;
            }
            else {
                $flexible = 1;
            }
            if ($request->secured == '') {
                $secured = 0;
            }
            else {
                $secured = 1;
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
                    'salary_deduction'    => $request->salary_deduction,
                    'from_salary'         => $request->from_salary,
                    'to_salary'           => $request->to_salary,
                    'guarantees'          => $guarantees,
                    'residential_support' => $residential_support,
                    'personal'            => $personal,
                    'flexible'            => $flexible,
                    'secured'             => $secured,
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
            return redirect()->route('all.suggestion.deductions')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));
    }
}
