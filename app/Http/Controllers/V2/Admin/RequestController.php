<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\Controllers\V2\Admin;

use App\DataTables\FreezeRequestDataTable as DataTable;
use App\Http\Controllers\AppController;
use App\Models\Classification;
use App\Models\Request as Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class RequestController extends AppController
{

    public function __construct()
    {
        $this->DataTableClass = DataTable::class;
        $this->MODEL = Model::class;
        parent::__construct();
    }

    public function getRequestInfo(Request $request)
    {
        $model = Model::findOrFail($request->get('request_id'));
        $rows = [
            [
                "text"  => __("attributes.request_id"),
                "value" => $model->id,
            ],
            [
                "text"  => __("attributes.classification_id"),
                "value" => Classification::findOrNew($model->class_id_agent)->value,
            ],
            [
                "text"  => __("attributes.agent_id"),
                "value" => $model->user->name,
            ],
            [
                "text"  => __("attributes.agent_date"),
                "value" => $model->agent_date ? $model->agent_date->format("Y-m-d") : '-',
            ],
        ];
        //$history = $model->requestHistories()->with(['user', 'receiver'])->latest('history_date')->get();
        //$historyRows = collect();
        //foreach ($history as $item) {
        //    $text = "{$item->title} - {$item->content}";
        //    $value = "";
        //    if ($item->user->name) {
        //        $value .= __("replace.from", ['from' => $item->user->name]);
        //    }
        //    if ($item->receiver->name) {
        //        $value .= ($value ? " - " : "").__("replace.to", ['to' => $item->receiver->name]);
        //    }
        //
        //    //$historyRows->push(collect([
        //    //    "text"  => $text,
        //    //    "value" => $value,
        //    //    'date'  => $item->history_date ? Carbon::make($item->history_date)->format("Y-m-d H:i:s") : null,
        //    //]));
        //}
        //$records = $model->requestRecords()->where('colum', 'comment')->with(['user'])->whereHas('user', fn(Builder $builder) => $builder->where('role', 0))->latest('updateValue_at')->get();
        //foreach ($records as $record) {
        //    $text = __("attributes.agent_note")." {$record->user->name}";
        //    $value = $record->comment;
        //    $value .= ($value ? " - " : "").$record->value;
        //
        //    $historyRows->push(collect([
        //        "text"  => $text,
        //        "value" => $value,
        //        'date'  => $record->updateValue_at ? Carbon::make($record->updateValue_at)->format("Y-m-d H:i:s") : null,
        //    ]));
        //}
        //$historyRows = $historyRows->sortBy('date');
        //dd($historyRows->sortByDesc('date'));
        //dd($history);
        //dd($model->class_id_agent);
        //dd($request->all());
        $role = auth()->check() ? auth()->user()->role : null;
        return response()->json(collect([
            'rows'           => $rows,
            'historyRows'    => $model->getHistoryWithNotes($role),
            'class_id_agent' => Classification::findOrNew($model->class_id_agent)->value,
        ]));
    }

    public function moveToFreeze(Model $model): JsonResponse
    {
        if (!$model->isFreeze()) {
            $model->moveToFreeze(__("global.moveToFreezeByAdmin"));
        }
        //dd($model);
        return response()->json(collect([
            'message' => __("messages.successMoveToFreeze"),
            'success' => !0,
        ]));
    }

    public function moveRequestToFreeze(): JsonResponse
    {
        $request = $this->request;
        $ids = $request->get('ids', []);
        $count = 0;
        foreach ((array) $ids as $id) {
            if (($model = Model::find($id)) && !$model->isFreeze()) {
                $model->moveToFreeze(__("global.moveToFreezeByAdmin"));
                if ($model->isFreeze()) {
                    ++$count;
                }
            }
        }

        return response()->json(collect([
            'message' => __("messages.successMoveToFreezeRequests", ['c' => $count]),
            'success' => !0,
        ]));
    }
}
