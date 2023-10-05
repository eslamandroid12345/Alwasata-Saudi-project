<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\DataTables;

use App\Models\User;
use App\Http\Controllers\AppController;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTableAbstract;
use Yajra\DataTables\Html\Builder;
use Yajra\DataTables\Services\DataTable;

class AppDataTable extends DataTable
{

    /**
     * Controller of Datatable model
     *
     * @var AppController
     */
    public $controller;

    /**
     * Auth user
     *
     * @var User
     */
    public $user;

    /**
     * @var \Yajra\DataTables\Utilities\Request|Request
     */
    protected $request;

    /**
     * DataTables print preview view.
     *
     * @var string
     */
    protected $printPreview = "datatables::datatables_print";

    /**
     * list of column will filter from request
     *
     * @var array
     */
    protected $filters = [];

    /**
     * AppDataTable constructor.
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Display ajax response.
     *
     * @return JsonResponse
     */
    public function ajax()
    {
        $source = null;
        if (method_exists($this, 'query')) {
            $source = app()->call([$this, 'query']);
            $source = $this->applyScopes($source);
            if (!empty($this->filters) && is_array($this->filters)) {
                foreach ($this->filters as $filter => $value) {
                    if ($this->request->has($filter)) {
                        $requestValue = $this->request->get($filter);
                        if (is_array($value)) {
                            if (isset($value["cast"])) {
                                if ($value["cast"] === 'array' && !is_array($requestValue)) {
                                    $requestValue = explode(',', trim($requestValue));
                                }
                                elseif ($value["cast"] === 'bool') {
                                    $requestValue = boolval($requestValue);
                                }
                            }
                            if ($requestValue) {
                                if (isset($value["method"])) {
                                    if (is_string($value["method"])) {
                                        $source->{$value["method"]}($requestValue);
                                    }
                                    elseif (is_callable($value["method"])) {
                                        $source = $value["method"]($source, $requestValue);
                                    }
                                    elseif (is_array($value["method"])) {
                                        $params = $value["method"]["params"];
                                        foreach ($params as $k => $param) {
                                            if ($param == ":value") {
                                                $params[$k] = $requestValue;
                                            }
                                        }
                                        $source->{$value["method"]["name"]}(...$params);
                                        // $source = $value["method"]["name"]($source, $requestValue);
                                    }
                                }
                                else {
                                    // dd($value);
                                }
                            }
                        }
                        else {
                            if ($requestValue && is_string($value) /*&& method_exists($source, $value) */) $source->{$value}($requestValue);
                        }
                    }
                }
            }
            // dd(get_class_methods($source),$source->toSql(),$source->getQuery());
        }

        /** @var DataTableAbstract $dataTable */
        $dataTable = app()->call([$this, 'dataTable'], compact('source'));

        if ($callback = $this->beforeCallback) {
            $callback($dataTable);
        }

        if ($callback = $this->responseCallback) {
            $data = new Collection($dataTable->toArray());

            return new JsonResponse($callback($data));
        }

        return $dataTable->toJson();
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @param  bool  $addAction
     *
     * @return Builder
     */
    public function html(bool $addAction = true): Builder
    {
        $b = $this->builder()->columns($this->getColumns())->minifiedAjax("",
                "$.each( $('.DataTable-container-filter :input:not(:checkbox):not(:radio):not(:button),.DataTable-container-filter :radio:checked,.DataTable-container-filter :checkbox:checked'), ( key, elm ) => { data[elm.name] = $(elm).val(); })")->parameters
        ($this->getBuilderParametersPer())->initComplete("function(){
            console.log(this.api().search)
        }");
        if ($addAction) {
            $b->addAction([
                "data"  => "action",
                "name"  => "action",
                "title" => __("datatable.control"),
                "class" => "data-tables-action",
                "width" => "150px",
            ]);
        }
        return $b;
    }

    /**
     * Get columns.
     *
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
                "title" => __("validation.attributes.name"),
                "name"  => "name",
                "data"  => "name",
            ],
        ];
    }

    /**
     * Get DataTable Parameters
     *
     * @return array
     */
    public function getBuilderParametersPer()
    {
        return array_merge(parent::getBuilderParameters(), [
            'pageLength' => 50,
            'lengthMenu' => [[10, 20, 50, 100, 500, -1], [10, 20, 50, 100, 500, __("global.all")]],
        ]);
    }

    /**
     * @param      $url
     * @param      $string
     * @param  bool  $permission
     *
     * @return string
     */
    public function rawLink($url, $string, $permission = false)
    {
        if (!is_null($this->request->action)) {
            if (is_array($string)) {
                if (!isset($string[1])) return "";
                return $string[1];
            }
            return $string;
        }

        if ($permission) {
            if (is_string($string)) {
                return "<a href='{$url}' >{$string}</a>";
            }
            else {
                if (!isset($string[0]) || !isset($string[1])) return "";

                if (method_exists($string[0], 'trashed') && $string[0]->trashed()) {
                    return $string[1];
                }
                else {
                    return "<a href='{$url}' >{$string[1]}</a>";
                }
            }
        }
        return is_string($string) ? $string : (!isset($string[1]) ? "" : $string[1]);
    }
}
