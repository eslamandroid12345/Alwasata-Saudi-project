<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2\Admin;

use App\DataTables\FreezeRequestDataTable as DataTable;
use App\Helpers\MyHelpers;
use App\Http\Controllers\AppController;
use App\Models\Request as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use yajra\Datatables\Datatables;

class FreezeRequestController extends AppController
{

    public function __construct()
    {
        $this->DataTableClass = DataTable::class;
        $this->MODEL = Model::class;
        parent::__construct();
    }

    public function listView(Request $request)
    {
        return view('V2.Admin.FreezeRequest.index');
    }

    public function listViewDatatable(Request $request)
    {
        /** @var Builder|Model $requests */
        $requests = Model::query()->with(['user', 'customer', 'requestSource','agentClassificationBeforeFreeze'])->freezeOnly();
        $dates = [
            'req_date'      => 'agent_date',
            'complete_date' => 'complete_date',
            'updated_at'    => 'updated_at',
        ];
        $inWHares = [
            'app_downloaded' => 'app_downloaded',
            'req_status'     => 'statusReq',
            'pay_status'     => 'payStatus',
            'reqTypes'       => 'type',
            'agents_ids'     => 'user_id',
            'work_source'    => 'work',
            'source'         => 'source',
            'collaborator'   => 'collaborator_id',
            'salary_source'  => 'salary_id',
        ];
        $wheres = [
            'customer_salary' => 'salary',
        ];
        foreach ($dates as $requestKey => $columnName) {
            $fromKey = "{$requestKey}_from";
            $toKey = "{$requestKey}_to";
            if ($request->get($fromKey)) {
                $requests->whereDate($columnName, '>=', $request->get($fromKey));
            }
            if ($request->get($toKey)) {
                $requests->whereDate($columnName, '<=', $request->get($toKey));
            }
        }

        foreach ($inWHares as $requestKey => $column) {
            if (($c = $request->get($requestKey))) {
                !is_array($c) && ($c = explode(',', $c));
                $requests->whereIn($column, $c);
            }
        }

        foreach ($wheres as $requestKey => $column) {
            if (($c = $request->get($requestKey))) {
                !is_array($c) && ($c = explode(',', $c));
                $requests->where($column, $c);
            }
        }

        if (($c = $request->get('founding_sources'))) {
            !is_array($c) && ($c = explode(',', $c));
            $requests->whereHas('requestSource', fn(Builder $builder) => $builder->whereIn('id', $c));
        }

        if (($c = $request->get('notes_status'))) {
            if ($c == 1) {
                // choose contains only
                $requests->whereNotNull('comment');
            }
            if ($c == 2) {
                // choose empty only
                $requests->whereNull('comment');
            }
        }

        if (($c = $request->get('quality_recived'))) {
            if ($c == 1) {
                $requests->whereNotNull('class_id_quality');
            }
            if ($c == 2) {
                $requests->whereNull('class_id_quality');
            }
        }

        if ($request->get('checkExisted')) {
            $requests->where('isSentSalesManager', 1);
        }

        if (($c = $request->get('region_ip'))) {
            !is_array($c) && ($c = explode(',', $c));
            $requests->whereHas('customer', fn(Builder $builder) => $builder->where('region_ip', $c));
        }

        if (($c = $request->get('customer_phone'))) {
            $requests->where(fn(Builder $builder) => $builder->whereHas('customer', fn(Builder $builder) => $builder->where('mobile', $c)))->orWhereHas('customer', fn(Builder $builder) => $builder->whereHas('customerPhones', fn(Builder $builder) => $builder->where('mobile', $c)));
        }

        $xses = [
            'sa' => 'class_id_agent',
            'sm' => 'class_id_sm',
            'fm' => 'class_id_fm',
            'mm' => 'class_id_mm',
            'gm' => 'class_id_gm',
        ];

        foreach ($xses as $key => $xs) {
            $req = $request->get("class_id_$key");
            if (is_array($req) && count($req) > 0) {
                $requests->whereIn($xs, $req);
            }
        }

        return Datatables::of($requests)
            ->setRowId(fn($row) => $row->id)
            ->addColumn('action', function ($row) {
                $data = '<div class="tableAdminOption">';
                $data .= '<span class="item pointer" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="'.MyHelpers::admin_trans(auth()->user()->id, 'Open').'">
                <a target="_blank" href="'.route('admin.fundingRequest', $row->id).'"><i class="fas fa-eye"></i></a></span>';
                return $data.'</div>';
            })
            ->filter(function ($instance) use ($request) {
                // Filter according to classifiaction before freeezing
                if (isset($request->classification_before_to_freeze)) {
                    $instance->where('classification_before_to_freeze', $request->get('classification_before_to_freeze'));
                }
            })
            ->editColumn("req_date", fn(Model $model) => $model->req_date_to_date_format)
            ->editColumn("classification_before_to_freeze", fn(Model $model) => $model->agentClassificationBeforeFreeze->value)
            ->editColumn("source", fn(Model $model) => $model->source_to_string)
            ->editColumn("customer_id", fn(Model $model) => $model->customer_id_to_string)
            ->rawColumns(['action'])->make(true);
    }

    /**
     * @param  Request  $request
     *
     * @return \Illuminate\Contracts\Validation\Validator|\Illuminate\Validation\Validator
     */
    protected function validator(Request $request)
    {
        return Validator::make($request->all(), []);
    }
}
