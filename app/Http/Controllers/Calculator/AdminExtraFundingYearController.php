<?php

namespace App\Http\Controllers\Calculator;

use App\FundingYear;
use App\Http\Controllers\Controller;
use Datatables;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use MyHelpers;
use View;

class AdminExtraFundingYearController extends Controller
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

    public function extraFundingYearIndex()
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear?itemsPerPage=-1';
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $extraFundingYears = json_decode($response->getBody(), true);
        return view('Admin.Calculator.ExtraFundingYear.index', compact('extraFundingYears'));
    }

    public function extraFundingYearDataTables()
    {
        $client = new Client(['http_errors' => false]);
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
        })->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption">';
            $data = $data.'<span class="item pointer" data-toggle="tooltip" data-placement="top"  title="'.MyHelpers::admin_trans(auth()->user()->id, 'Edit').'">
                                    <a id="editBank" href="'.url('admin/extra-funding-year-edit/'.$row['id']).'"><i class="fas fa-edit"></i></a>
                                </span>';
            $data = $data.'<span class="item" id="remove" data-id="'.$row['id'].'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Archive').'">
                                    <i class="fas fa-trash-alt"></i>
                                </span> ';
            //-----------------------------------------------------------------
            // #Suggestions Add New Button
            //-----------------------------------------------------------------
            $data = $data.'<span class="item pointer" style="width:104px" data-toggle="tooltip" data-placement="top"  title="المقترحات ">
                                <a id="editBank" href="'.url('admin/extra-funding-year-compare/'.$row['id']).'">المقترحات</a>
                            </span> ';
            $data = $data.'</div>';
            return $data;
        })->make(true);
    }

    public function addNewExtraFundingPage()
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
        $jobPositionsUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/JobPosition?itemsPerPage=-1';
        $jobResponse = $client->get($jobPositionsUrl, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $jobPositions = json_decode($jobResponse->getBody(), true);
        return view('Admin.Calculator.ExtraFundingYear.add_new_extra_funding_year', compact('banks', 'jobPositions'));
    }

    public function saveNewExtraFunding(Request $request)
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
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear';
        $response = $client->post($url, [
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
            return redirect()->route('admin.extraFundingYearIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Added successfully'));
        }
    }

    public function editExtraFundingPage($id)
    {
        $client = new Client(['http_errors' => false]);
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
        return view('Admin.Calculator.ExtraFundingYear.edit', compact('extraFundingYear', 'banks', 'jobPositions'));
    }

    public function updateExtraFunding(Request $request)
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
        $client = new Client(['http_errors' => false]);
        $extraFundingYearUrl = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear/'.$request->id;
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
            return redirect()->route('admin.extraFundingYearIndex')
                ->with('msg', MyHelpers::admin_trans(auth()->user()->id, 'Update Succesffuly'));
        }
    }

    public function removeExtraFundingYear(Request $request)
    {
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear/'.$request->id;
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
    public function years($apiId)
    {
        $extraFundingYears = FundingYear::where('apiId', $apiId)
            ->count();
        $client = new Client(['http_errors' => false]);
        $url = 'https://calculatorapi.alwsata.com.sa/api/Panel/ExtraFundingYear/'.$apiId;
        $response = $client->get($url, [
            'headers' => [
                'Accept'   => "application/json",
                'Secret'   => "HvJmAoTxqWlyVEgGBKwETN9afKFra6dmKaknwjSdHSi4bESG1GuYKaOjLoJ4OT7Gfm06Otared:alwsata",
                'S-Client' => "alwsata.com.sa",
            ],
        ]);
        $name = json_decode($response->getBody(), true);
        return view('Admin.Calculator.ExtraFundingYear.compare', compact('name', 'apiId', 'extraFundingYears'));
    }

    public function yearsDataTables($apiId)
    {
        $extraFundingYears = FundingYear::where('apiId', $apiId)
            ->get();

        return Datatables::of($extraFundingYears)->setRowId(function ($extraFundingYear) {
            return $extraFundingYear->id;
        })->addColumn('user', function ($profitPercentages) {
            return $profitPercentages->user->name;
        })
            ->addColumn('yes', function ($extraFundingYear) {
                return $extraFundingYear->suggests
                    ->where('vote', 'yes')
                    ->where('suggestable_type', FundingYear::class)
                    ->where('suggestable_id', $extraFundingYear->id)
                    ->count();
            })->addColumn('created_at', function ($profitPercentages) {
                return date("d-m-Y", strtotime($profitPercentages->created_at));
            })->addColumn('updated_at', function ($profitPercentages) {
                return date("d-m-Y", strtotime($profitPercentages->updated_at));
            })->addColumn('no', function ($extraFundingYear) {
                return $extraFundingYear->suggests
                    ->where('vote', 'no')
                    ->where('suggestable_type', FundingYear::class)
                    ->where('suggestable_id', $extraFundingYear->id)
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
                         <a href="'.route('admin.suggestions.details', ['years', $row->id]).'" class="btn btn-primary btn-sm text-white">  <i class="fas fa-list"></i></a>
                     </span>';
                return $data;
            })->make(true);
    }
}
