<?php

namespace App\Http\Controllers\Calculator;

use App\BankPercentage;
use App\Http\Controllers\Controller;
use Datatables;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class AdminProfitPercentageController extends Controller
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

    public function profitPercentageIndex()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);

        $profitPercentages = json_decode($response->getBody(), true);
        return view('Admin.Calculator.profitPercentage.index', compact('profitPercentages'));
    }

    public function profitPercentageDataTables()
    {
        $client = new Client(['http_errors' => false]);
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
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <a id="editBank" href="'.url('admin/profit-percentage-index/edit/'.$row['id']).'"><i class="fas fa-edit"></i></a>
                                </span>';
            $data = $data.'<span class="item" id="remove" data-id="'.$row['id'].'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';
            //-----------------------------------------------------------------
            // #Suggestions Add New Button
            //-----------------------------------------------------------------
            $data = $data.'<span class="item pointer" style="width:104px" data-toggle="tooltip" data-placement="top"  title="المقترحات ">
                                    <a id="editBank" href="'.url('admin/profit-percentage-compare/'.$row['id']).'">المقترحات</a>
                                </span> ';
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function getAddNewProfitPercentagePage()
    {
        $client = new Client(['http_errors' => false]);
        $bankUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/Bank?itemsPerPage=-1';
        $jobPositionsUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
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
        $jobPositions = json_decode($jobResponse->getBody(), true);
        $banks = json_decode($bankResponse->getBody(), true);
        return view('Admin.Calculator.profitPercentage.add', compact('banks', 'jobPositions'));
    }

    public function saveNewProfitPercentage(Request $request)
    {
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
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage';
        $response = $client->post($url, [
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
            return redirect()->route('admin.profitPercentageIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }
    }

    public function getProfitPercentageEditPage($id)
    {
        $client = new Client(['http_errors' => false]);
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
        return view('Admin.Calculator.profitPercentage.edit', compact('banks', 'profitPercentage', 'jobPositions'));
    }

    public function updateProfitPercentage(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $urlProfit = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$request->id;
        $responseProfit = $client->get($urlProfit, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $profitPercentage = json_decode($responseProfit->getBody(), true);
        //        dd($profitPercentage['data']);
        if ($request->bank_id == $profitPercentage['data']['bank_id']) {
            $bank_id = null;
        }
        else {
            $bank_id = $request->bank_id;
        }
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

        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$request->id;
        $response = $client->put($url, [
            'headers'     => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
            'form_params' => [
                'bank_id'             => $bank_id,
                'job_position_id'     => $job_position_id,
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
            return redirect()->route('admin.profitPercentageIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function removeProfitPercentage(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$request->id;
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
    //-----------------------------------------------------------------
    // #Suggestions
    //-----------------------------------------------------------------
    public function percentages($apiId)
    {
        $profitPercentages = BankPercentage::where('apiId', $apiId)
            ->count();

        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ProfitPercentage/'.$apiId;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $name = json_decode($response->getBody(), true);
        return view('Admin.Calculator.profitPercentage.compare', compact('name', 'apiId', 'profitPercentages'));
    }

    public function percentagesDataTables($apiId)
    {
        $profitPercentages = BankPercentage::where('apiId', $apiId)
            ->get();
        return Datatables::of($profitPercentages)->setRowId(function ($profitPercentage) {
            return $profitPercentage->id;
        })->addColumn('user', function ($profitPercentages) {
            return $profitPercentages->user->name;
        })->addColumn('yes', function ($profitPercentages) {
            return $profitPercentages->suggests
                ->where('vote', 'yes')
                ->where('suggestable_type', BankPercentage::class)
                ->where('suggestable_id', $profitPercentages->id)
                ->count();
        })->addColumn('created_at', function ($profitPercentages) {
            return date("d-m-Y", strtotime($profitPercentages->created_at));
        })->addColumn('updated_at', function ($profitPercentages) {
            return date("d-m-Y", strtotime($profitPercentages->updated_at));
        })->addColumn('no', function ($profitPercentages) {
            return $profitPercentages->suggests
                ->where('vote', 'no')
                ->where('suggestable_type', BankPercentage::class)
                ->where('suggestable_id', $profitPercentages->id)
                ->count();
        })->addColumn('action', function ($row) {
            switch ($row->status) {
                case 0:
                    $status = '<a class="btn btn-primary btn-sm text-white" style="width: 120px">لم يتم إتخاذ إجراء</a>';
                    break;
                case 1:
                    $status = '<a class="btn btn-success btn-sm text-white" style="width: 120px"> تم الموافقة</a>';
                    break;
                case 2:
                    $status = '<a class="btn btn-danger btn-sm text-white" style="width: 120px">تم الرفض</a>';
                    break;
                default:
                    $status = '';
            }
            $row->status == 2 ? $title = "نقل للإقتراحات الجديدة" : $title = "رفض المقترح ";
            $row->status == 2 ? $icon = "fa fa-redo" : $icon = "fa fa-thumbs-down";
            $row->status == 2 ? $form = 'DeArchiveForm('.$row->id.')' : $form = 'ArchiveForm('.$row->id.')';
            $approve = '';
            $data = '<div class="tableAdminOption">'.$status;
            $archive = '';

            if ($row->status != 1) {
                $archive = '<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.$title.'">
                        <a onclick="'.$form.'" class="btn btn-danger btn-sm text-white"> <i class="'.$icon.'"></i> </a>
                     </span>';

            }
            if ($row->status == 0) {
                $approve = '<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="الموافقة على هذا المقترح">
                        <a onclick="ApproveForm('.$row->id.')" class="btn btn-success btn-sm text-white"> <i class="fas fa-thumbs-up"></i> </a>
                     </span>';
            }

            $data = $data.$approve.$archive.
                '<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="تفاصيل المقترح">
                         <a href="'.route('admin.suggestions.details', ['percentages', $row->id]).'" class="btn btn-primary btn-sm text-white">  <i class="fas fa-list"></i></a>
                     </span>';
            return $data;
        })->make(true);
    }
}
