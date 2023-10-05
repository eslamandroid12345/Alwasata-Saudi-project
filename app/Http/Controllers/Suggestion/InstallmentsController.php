<?php

namespace App\Http\Controllers\Suggestion;

use App\AvailableExtended;
use App\CheckTotal;
use App\EditCalculationFormulaUser;
use App\Http\Controllers\Controller;
use App\SupportInstallment;
use Carbon\Carbon;
use Datatables;
use DB;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class InstallmentsController extends Controller
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
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SupportInstallment?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $supportInstallment = json_decode($response->getBody(), true);
        return view('Suggestions.installments.index', compact('supportInstallment'));
    }

    public function dataTable($userId)
    {
        $this->loggedIn = $userId;
        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SupportInstallment?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $installments = json_decode($response->getBody(), true);
        return Datatables::of($installments['data'])->setRowId(function ($batches) {
            return $batches['id'];
        })->addColumn('action', function ($row) {
            $dataExist = [
                'user_id' => $this->loggedIn,
                'apiId'   => $row['id'],
                'status'  => 0,
            ];
            $count = SupportInstallment::where($dataExist)->count();
            if ($count == 0) {
                $data = '<div class="tableAdminOption">';
                $data .= '<span class="item pointer" style="width:114px" data-toggle="tooltip" data-placement="top"  title="تقديم مقترح" >
                                        <a id="editBank" href="'.url('all/suggestion-installments-index/edit/'.$row['id']).'">تقديم مقترح <i class="fas fa-edit"></i></a>
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
        $count = SupportInstallment::where($dataExist)->count();
        if ($count == 0) {
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SupportInstallment/'.$id;
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
            $supportInstallment = json_decode($response->getBody(), true);

            $banks = json_decode($bankResponse->getBody(), true);
            return view('Suggestions.installments.edit', compact('banks', 'supportInstallment'));
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'You do not have a premation to do that'));
    }

    public function update(Request $request)
    {
        $rules = [
            'bank_id'              => 'required',
            'salary_deduction'     => 'required|numeric',
            'new_salary_deduction' => 'required|numeric',
            'less_percentage'      => 'required|numeric',
        ];
        $customMessages = [
            'bank_id.required'              => 'حقل جهة التمويل مطلوب *',
            'salary_deduction.required'     => 'حقل نسبة استقطاع صافي الراتب مطلوب',
            'salary_deduction.numeric'      => 'حقل نسبة استقطاع صافي الراتب يقبل أرقام فقط',
            'new_salary_deduction.required' => 'حقل نسبة استقطاع صافي الراتب الجديدة مطلوب',
            'new_salary_deduction.numeric'  => 'حقل نسبة استقطاع صافي الراتب الجديدة يقبل أرقام فقط',
            'less_percentage.required'      => 'حقل نسبة سقف القسط من الراتب مطلوب',
            'less_percentage.numeric'       => 'حقل نسبة سقف القسط من الراتب يقبل أرقام فقط',
        ];
        if ($request->support_installment == '') {
            $support_installment = 0;
        }
        else {
            $support_installment = 1;
        }
        $this->validate($request, $rules, $customMessages);

        $client = new Client();
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SupportInstallment/'.$request->id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $supportInstallment = json_decode($response->getBody(), true)['data'];
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank/'.$request->bank_id;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $banks = json_decode($response->getBody(), true)['data'];
        $input = [
            'text'                           => $banks['text'],
            'bank_id'                        => $banks['id'],
            'bank_code'                      => $banks['code'],
            'bank_id_to_string'              => $banks['text'],
            "salary_deduction"               => $request->salary_deduction,
            "salary_deduction_to_string"     => $request->salary_deduction,
            "new_salary_deduction"           => $request->new_salary_deduction,
            "new_salary_deduction_to_string" => $request->new_salary_deduction,
            "less_percentage"                => $request->less_percentage,
            "less_percentage_to_string"      => $request->less_percentage,
            "support_installment"            => $support_installment,
            "support_installment_to_string"  => $support_installment == 0 ? 'لا' : 'نعم',

            'text_api'                           => $supportInstallment['text'],
            'bank_id_api'                        => $supportInstallment['bank_id'],
            'bank_code_api'                      => $supportInstallment['bank_code'],
            'bank_id_to_string_api'              => $supportInstallment['bank_id_to_string'],
            "salary_deduction_api"               => $supportInstallment['salary_deduction'],
            "salary_deduction_to_string_api"     => $supportInstallment['salary_deduction_to_string'],
            "new_salary_deduction_api"           => $supportInstallment['new_salary_deduction'],
            "new_salary_deduction_to_string_api" => $supportInstallment['new_salary_deduction_to_string'],
            "less_percentage_api"                => $supportInstallment['less_percentage'],
            "less_percentage_to_string_api"      => $supportInstallment['less_percentage_to_string'],
            "support_installment_api"            => $supportInstallment['support_installment'],
            "support_installment_to_string_api"  => $supportInstallment['support_installment_to_string'],
        ];

        $input['user_id'] = auth()->user()->id;
        $input['apiId'] = $supportInstallment['id'];

        unset($supportInstallment['id']);
        //**********************************************************************
        // Task-38
        //**********************************************************************
        $input['status'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;
        $input['approved'] = EditCalculationFormulaUser::where(['user_id' => auth()->id(), 'type' => 0])->first()->auth;

        $new = SupportInstallment::firstOrCreate($input, $input);
        if ($input['approved'] == 1) {
            //----------------------------------------------------
            // IsverifiedUser Post To API
            //----------------------------------------------------
            $client = new Client();
            $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/SupportInstallment/'.$new->apiId;
            if ($request->support_installment == '') {
                $support_installment = 0;
            }
            else {
                $support_installment = 1;
            }
            $response = $client->put($url, [
                'headers'     => [
                    'Accept'   => "application/json",
                    'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                    'S-Client' => "alwsata.com.sa",
                ],
                'form_params' => [
                    'bank_id'              => $request->bank_id,
                    'salary_deduction'     => $request->salary_deduction,
                    'new_salary_deduction' => $request->new_salary_deduction,
                    'less_percentage'      => $request->less_percentage,
                    'support_installment'  => $support_installment,
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
            return redirect()->route('all.suggestion.installments')
                ->with('msg', 'تم إرسال المقترح بنجاح');
        }
        return redirect()->back()->with('message2', MyHelpers::admin_trans(auth()->user()->id, 'Try Again'));

    }
}
