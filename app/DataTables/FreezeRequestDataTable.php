<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\DataTables;

use App\Models\Request as Model;

class FreezeRequestDataTable extends AppDataTable
{

    /**
     * Build DataTable class.
     * @param  mixed  $query  Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract|\Yajra\DataTables\DataTables
     */
    public function dataTable($query)
    {
        return datatables($query)->editColumn("id", fn(Model $model) => $model->id)
            ->editColumn("req_date", fn(Model $model) => $model->req_date_to_date_format)
            ->editColumn("source", fn(Model $model) => $model->source_to_string)
            ->editColumn("user_id", fn(Model $model) => $model->user_id_to_string)
            ->editColumn("customer_id", fn(Model $model) => $model->customer_id_to_string)
            ->editColumn("statusReq", fn(Model $model) => $model->request_status_to_string)
            ->editColumn("class_id_agent", fn(Model $model) => $model->agent_classification_to_string)
            //->editColumn("agent_note", fn(Model $model) => $model->id)
            //->editColumn("quality_note", fn(Model $model) => $model->id)
            //->editColumn("agent_date", fn(Model $model) => $model->id)
            //->editColumn("quality_received", fn(Model $model) => $model->id)
            //->editColumn("updated_at", fn(Model $model) => $model->updated_at_to_datetime_format)
            ->rawColumns(["action"])->addcolumn("action", "{$this->controller->CTRL_VIEW}partials.datatables_actions");
    }

    /**
     * @param  Model  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Model $model)
    {
        $q = $model->newQuery()->with(['user', 'customer','requestSource'])->freezeOnly();
        if(!request()->get('order')){
            $q->latest('id');
        }
        return $q;
    }

    /**
     * Get columns.
     * @return array
     */
    protected function getColumns()
    {
        return [
            [
                "title" => "#",
                "name"  => "id",
                "data"  => "id",
            ],
            [
                "title" => __("attributes.request_date"),
                "name"  => "req_date",
                "data"  => "req_date",
            ],
            [
                "title" => __("attributes.request_type"),
                "name"  => "type",
                "data"  => "type",
            ],
            //[
            //    "title" => __("attributes.agent_id"),
            //    "name"  => "user_id",
            //    "data"  => "user_id",
            //],
            [
                "title" => __("attributes.customer_id"),
                "name"  => "customer_id",
                "data"  => "customer_id",
            ],
            [
                "title" => __("attributes.request_status"),
                "name"  => "statusReq",
                "data"  => "statusReq",
            ],
            //[
            //    "title" => __("attributes.request_source"),
            //    "name"  => "source",
            //    "data"  => "source",
            //],
            [
                "title" => __("attributes.request_classification"),
                "name"  => "class_id_agent",
                "data"  => "class_id_agent",
            ],
            //[
            //    "title" => __("attributes.agent_note"),
            //    "name"  => "agent_note",
            //    "data"  => "agent_note",
            //],
            //[
            //    "title" => __("attributes.quality_note"),
            //    "name"  => "quality_note",
            //    "data"  => "quality_note",
            //],
            //[
            //    "title" => __("attributes.agent_date"),
            //    "name"  => "agent_date",
            //    "data"  => "agent_date",
            //],
            //[
            //    "title" => __("attributes.quality_received"),
            //    "name"  => "quality_received",
            //    "data"  => "quality_received",
            //],
            //[
            //    "title" => __("attributes.updated_at"),
            //    "name"  => "updated_at",
            //    "data"  => "updated_at",
            //],
        ];
    }
}
