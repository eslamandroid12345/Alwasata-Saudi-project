<?php

namespace App\Http\Controllers\Calculator;

use App\Http\Controllers\Controller;
use Datatables;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class PropertyStatusRuleController extends Controller
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

    public function propertyStatusRuleIndex()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/PropertyStatusRule?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $Properties = json_decode($response->getBody(), true);
        return view('Admin.Calculator.PropertyStatus.PropertyStatusRule.index', compact('Properties'));
    }

    public function propertyStatusRuleIndexDataTable()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/PropertyStatusRule?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $properties = json_decode($response->getBody(), true);
        return Datatables::of($properties['data'])->setRowId(function ($property) {
            return $property['id'];
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <a id="editBank" href="'.url('admin/property-status-rule-show/'.$row['id']).'"><i class="fas fa-edit"></i></a>
                                </span>';
            $data = $data.'<span class="item" id="remove" data-id="'.$row['id'].'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';
            //-----------------------------------------------------------------
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function addNewPropertyStatusRuleItem()
    {
        $client = new Client(['http_errors' => false]);
        $bankUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
        $bankResponse = $client->get($bankUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $banks = json_decode($bankResponse->getBody(), true);
        return view('Admin.Calculator.PropertyStatus.PropertyStatusRule.add', compact('banks'));
    }

    public function saveNewPropertyStatusRuleItem(Request $request)
    {
        if ($request->residential_support == '') {
            $residential_support = 0;
        }
        else {
            $residential_support = 1;
        }
        if ($request->property_completed == '') {
            $property_completed = 0;
        }
        else {
            $property_completed = 1;
        }
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/PropertyStatusRule';
        $response = $client->post($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'bank_id'             => $request->bank_id,
                'residential_support' => $residential_support,
                'property_completed'  => $property_completed,
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
            return redirect()->route('admin.propertyStatusRuleIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }
    }

    public function showPropertyStatusRuleItem($id)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/PropertyStatusRule/'.$id;
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
        $property = json_decode($response->getBody(), true);
        $banks = json_decode($bankResponse->getBody(), true);
        return view('Admin.Calculator.PropertyStatus.PropertyStatusRule.edit', compact('property', 'banks'));
    }

    public function updatePropertyStatusRuleItem(Request $request)
    {
        if ($request->residential_support == '') {
            $residential_support = 0;
        }
        else {
            $residential_support = 1;
        }
        if ($request->property_completed == '') {
            $property_completed = 0;
        }
        else {
            $property_completed = 1;
        }
        $client = new Client(['http_errors' => false]);
        $productType = 'https://calculatorapi.alwsata.com.sa/api/Panel/PropertyStatusRule/'.$request->id;
        $response = $client->put($productType, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'bank_id'             => $request->bank_id,
                'residential_support' => $residential_support,
                'property_completed'  => $property_completed,
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
            return redirect()->route('admin.propertyStatusRuleIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function deletePropertyStatusRuleItem(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/PropertyStatusRule/'.$request->id;
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
