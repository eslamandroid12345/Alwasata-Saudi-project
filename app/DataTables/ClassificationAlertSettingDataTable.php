<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\DataTables;

use App\Models\ClassificationAlertSetting as Model;
use Illuminate\Http\Request;
use Yajra\DataTables\Html\Builder;

class ClassificationAlertSettingDataTable extends AppDataTable
{

    /**
     * Build DataTable class.
     * @param  mixed  $query  Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract|\Yajra\DataTables\DataTables
     */
    public function dataTable($query)
    {
        $request=request();
        return datatables($query)->editColumn("id", function (Model $model) {
            return  $model->id;
            //static $increments = 0;
            //return ++$increments;
        })
        ->filter(function ($instance) use ($request) {
            if ($request->get('classification_idd') == '1') { // أجل التواصل id = 62
                $instance->where('classification_id',62);
            }

            if ($request->get('classification_idd') == '2') { // تصنيف أخر id != 62
                $instance->where('classification_id','!=',62);
            }
        })
        ->editColumn("classification_id", function (Model $model) {return $model->classification->value;
        })->rawColumns(["action"])->addcolumn("action", "{$this->controller->CTRL_VIEW}partials.datatables_actions");
    }

    /**
     * @param  Model  $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Model $model)
    {
        return $model->newQuery();
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
                "title" => __("validation.attributes.classification_id"),
                "name"  => 'classification_id',
                "data"  => 'classification_id',
            ],
            [
                "title" => __("validation.attributes.step"),
                "name"  => 'step',
                "data"  => 'step',
            ],
            [
                "title" => __("validation.attributes.hours_to_send"),
                "name"  => 'hours_to_send',
                "data"  => 'hours_to_send',
            ],
            [
                "title" => __("validation.attributes.type"),
                "name"  => 'type',
                "data"  => 'type',
            ],
        ];
    }
    public function html(bool $addAction = true):Builder
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->ajax([
                        'url'  => '',
                        'data' => "function(data){
                            data.classification_idd=$('.active-filter').val()
                        }"
                    ])
                    ->addAction([
                        "data"  => "action",
                        "name"  => "action",
                        "title" => __("datatable.control"),
                        "class" => "data-tables-action",
                        "width" => "150px",
                    ]);
    }

    // public function html()
    // {
    //     return $this->builder()
    //                 ->columns($this->getColumns())
    //                 ->minifiedAjax()
    //                 ->addAction(['width' => '80px'])
    //                 ->parameters($this->getBuilderParameters());
    // data.classification_idd=$('input[name=classification_idd]:checked').val()
    // }
}
