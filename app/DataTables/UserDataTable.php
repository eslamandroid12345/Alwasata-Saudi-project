<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\DataTables;

use App\Models\ClassificationAlertSetting as Model;
use function __;
use function datatables;

class UserDataTable extends AppDataTable
{

    /**
     * Build DataTable class.
     * @param  mixed  $query  Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract|\Yajra\DataTables\DataTables
     */
    public function dataTable($query)
    {
        return datatables($query)->editColumn("id", function (Model $model) {
            return  $model->id;
            //static $increments = 0;
            //return ++$increments;
        })->editColumn("classification_id", function (Model $model) {return $model->classification->value;
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
}
