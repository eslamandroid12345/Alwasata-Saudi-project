<?php

namespace App\Http\Controllers\Calculator;

use App\Http\Controllers\Controller;
use Datatables;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class CalculatorRuleController extends Controller
{
    public function __construct()
    {
        View::composers([
            'App\Composers\HomeComposer'             => ['layouts.content'],
            'App\Composers\ActivityComposer'         => ['layouts.content'],
            'App\Composers\ActivityCustomerComposer' => ['layouts.content'],
        ]);
        $this->middleware('auth');
    }

    public function getCalculatorRuleIndex()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/CalculatorRule?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $calculatorRules = json_decode($response->getBody(), true);
        return view('Admin.Calculator.CalculatorRules.index', compact('calculatorRules'));
    }

    public function calculatorRuleDataTable()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/CalculatorRule?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $rules = json_decode($response->getBody(), true);
        return Datatables::of($rules['data'])->setRowId(function ($rule) {
            return $rule['id'];
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <a id="editBank" href="'.url('admin/calculator-rules-show/'.$row['id']).'"><i class="fas fa-edit"></i></a>
                                </span>';
            $data = $data.'<span class="item" id="remove" data-id="'.$row['id'].'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';
            //-----------------------------------------------------------------
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function addNewCalculatorRuleItem()
    {
        $client = new Client(['http_errors' => false]);
        $bankUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
        $jobPositionUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
        $ruleTypesUrl = 'https://calculatorapi.alwsata.com.sa/api/Resources/RuleTypes?itemsPerPage=-1';
        $calculatorProgramUrl = 'https://calculatorapi.alwsata.com.sa/api/Resources/Programs?withZero=1';

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
        $ruleTypesResponse = $client->get($ruleTypesUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $calculatorProgramsResponse = $client->get($calculatorProgramUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $banks = json_decode($bankResponse->getBody(), true);
        $jobs = json_decode($jobPositionResponse->getBody(), true);
        $ruleTypes = json_decode($ruleTypesResponse->getBody(), true);
        $calculatorPrograms = json_decode($calculatorProgramsResponse->getBody(), true);
        return view('Admin.Calculator.CalculatorRules.add', compact('banks', 'jobs', 'ruleTypes', 'calculatorPrograms'));
    }

    public function saveNewCalculatorRuleItem(Request $request)
    {
        if ($request->residential_support == '') {
            $residential_support = 0;
        }
        else {
            $residential_support = 1;
        }
        if ($request->guarantees == '') {
            $guarantees = 0;
        }
        else {
            $guarantees = 1;
        }
        if ($request->joint == '') {
            $joint = 0;
        }
        else {
            $joint = 1;
        }
        if ($request->show_result == '') {
            $show_result = 0;
        }
        else {
            $show_result = 1;
        }
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/CalculatorRule';
        $response = $client->post($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'bank_id'                => $request->bank_id,
                'job_position_id'        => $request->job_position_id,
                'rule_type'              => $request->rule_type,
                'calculator_program'     => $request->calculator_program,
                'residential_support'    => $residential_support,
                'guarantees'             => $guarantees,
                'joint'                  => $joint,
                'show_result'            => $show_result,
                'from_salary'            => $request->from_salary,
                'to_salary'              => $request->to_salary,
                'from_basic_salary'      => $request->from_basic_salary,
                'to_basic_salary'        => $request->to_basic_salary,
                'from_retirement_salary' => $request->from_retirement_salary,
                'to_retirement_salary'   => $request->to_retirement_salary,
                'from_age'               => $request->from_age,
                'to_age'                 => $request->to_age,
                'from_retirement_months' => $request->from_retirement_months,
                'to_retirement_months'   => $request->to_retirement_months,
                'from_job_tenure_months' => $request->from_job_tenure_months,
                'to_job_tenure_months'   => $request->to_job_tenure_months,
            ],
        ]);
        $body = [];
        try {
            $body = json_decode($response->getBody(), true);
        }
        catch (Exception $e) {
            $body = [];
        }
        $status = $response->getStatusCode();
        if ($status == 422) {
            $errors = [];
            $errors = $body['errors'] ?? [];
            foreach ($errors as $error) {
                foreach ($error as $key => $value) {
                    return redirect()->back()->with(['errors_api' => $value]);
                }
            }
        }
        elseif ($status != 200) {
            return redirect()->back()->with(['errors_api' => 'حدث خطأ , يرجي المحاولة لاحقاً']);
        }
        else {
            return redirect()->route('admin.calculatorRuleIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }
    }

    public function showCalculatorRuleItem($id)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/CalculatorRule/'.$id;
        $bankUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
        $jobPositionsUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
        $ruleTypesUrl = 'https://calculatorapi.alwsata.com.sa/api/Resources/RuleTypes?itemsPerPage=-1';
        $calculatorProgramUrl = 'https://calculatorapi.alwsata.com.sa/api/Resources/Programs?withZero=1';

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
        $jobResponse = $client->get($jobPositionsUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $ruleTypesResponse = $client->get($ruleTypesUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $calculatorProgramsResponse = $client->get($calculatorProgramUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $jobPositions = json_decode($jobResponse->getBody(), true);
        $banks = json_decode($bankResponse->getBody(), true);
        $calculatorRule = json_decode($response->getBody(), true);
        $ruleTypes = json_decode($ruleTypesResponse->getBody(), true);
        $calculatorPrograms = json_decode($calculatorProgramsResponse->getBody(), true);
        return view('Admin.Calculator.CalculatorRules.edit', compact('banks', 'calculatorRule', 'jobPositions', 'ruleTypes', 'calculatorPrograms'));

    }

    public function updateCalculatorRuleItem(Request $request)
    {
        if ($request->job_position_id == "no") {
            $job_position_id = '';
        }
        else {
            $job_position_id = $request->job_position_id;
        }
        if ($request->residential_support == '') {
            $residential_support = 0;
        }
        else {
            $residential_support = 1;
        }
        if ($request->guarantees == '') {
            $guarantees = 0;
        }
        else {
            $guarantees = 1;
        }
        if ($request->joint == '') {
            $joint = 0;
        }
        else {
            $joint = 1;
        }
        if ($request->show_result == '') {
            $show_result = 0;
        }
        else {
            $show_result = 1;
        }
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/CalculatorRule/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'bank_id'                => $request->bank_id,
                'job_position_id'        => $job_position_id,
                'rule_type'              => $request->rule_type,
                'calculator_program'     => $request->calculator_program,
                'residential_support'    => $residential_support,
                'guarantees'             => $guarantees,
                'joint'                  => $joint,
                'show_result'            => $show_result,
                'from_salary'            => $request->from_salary,
                'to_salary'              => $request->to_salary,
                'from_basic_salary'      => $request->from_basic_salary,
                'to_basic_salary'        => $request->to_basic_salary,
                'from_retirement_salary' => $request->from_retirement_salary,
                'to_retirement_salary'   => $request->to_retirement_salary,
                'from_age'               => $request->from_age,
                'to_age'                 => $request->to_age,
                'from_retirement_months' => $request->from_retirement_months,
                'to_retirement_months'   => $request->to_retirement_months,
                'from_job_tenure_months' => $request->from_job_tenure_months,
                'to_job_tenure_months'   => $request->to_job_tenure_months,
            ],
        ]);
        $body = [];
        try {
            $body = json_decode($response->getBody(), true);
        }
        catch (Exception $e) {
            $body = [];
        }
        $status = $response->getStatusCode();
        if ($status == 422) {
            $errors = [];
            $errors = $body['errors'] ?? [];
            foreach ($errors as $error) {
                foreach ($error as $key => $value) {
                    return redirect()->back()->with(['errors_api' => $value]);
                }
            }
        }
        elseif ($status != 200) {
            return redirect()->back()->with(['errors_api' => 'حدث خطأ , يرجي المحاولة لاحقاً']);
        }
        else {
            return redirect()->route('admin.calculatorRuleIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function deleteCalculatorRuleItem(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/CalculatorRule/'.$request->id;
        $response = $client->delete($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        if ($response) {
            return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Delete Succesffuly'), 'status' => 1]);
        }
        return response(['message' => MyHelpers::admin_trans(auth()->user()->id, 'Try Again'), 'status' => 0]);
    }
}
