<?php

namespace App\Http\Controllers\Calculator;

use App\Http\Controllers\Controller;
use Datatables;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class RulesController extends Controller
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

    public function rulesWithoutTransferIndex()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $rules = json_decode($response->getBody(), true);
        return view('Admin.Calculator.Rules.WithoutTransfer.index', compact('rules'));
    }

    public function rulesWithoutTransferDataTable()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer?itemsPerPage=-1';
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
                                    <a id="editBank" href="'.url('admin/without-transfer-show/'.$row['id']).'"><i class="fas fa-edit"></i></a>
                                </span>';
            $data = $data.'<span class="item" id="remove" data-id="'.$row['id'].'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';
            //-----------------------------------------------------------------
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function addNewWithoutTransferRuleItem()
    {
        $client = new Client(['http_errors' => false]);
        $bankUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
        $jobPositionUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
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
        $banks = json_decode($bankResponse->getBody(), true);
        $jobs = json_decode($jobPositionResponse->getBody(), true);
        return view('Admin.Calculator.Rules.WithoutTransfer.add', compact('banks', 'jobs'));
    }

    public function saveNewWithoutTransferRule(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer';
        $response = $client->post($url, [
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
            return redirect()->route('admin.rulesWithoutTransferIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }
    }

    public function showWithoutTransferRuleItem($id)
    {
        $client = new Client(['http_errors' => false]);
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
        return view('Admin.Calculator.Rules.WithoutTransfer.edit', compact('rule', 'banks', 'jobs'));
    }

    public function updateWithoutTransferRuleItem(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $productType = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer/'.$request->id;
        $response = $client->put($productType, [
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
            return redirect()->route('admin.rulesWithoutTransferIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function deleteWithoutTransferRuleItem(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/WithoutTransfer/'.$request->id;
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
