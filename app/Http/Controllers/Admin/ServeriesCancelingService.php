<?php

namespace App\Http\Controllers\Admin;

use App\AskAnswer;
use App\Http\Controllers\Controller;
use DataTables;

class ServeriesCancelingService extends Controller
{
    public function answers()
    {
        $answers = AskAnswer::distinct('request_id')->groupBy('request_id', 'batch')->get();
        return DataTables::of($answers)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('customer', function ($answers) {
                return $answers->customer->name;
            })
            ->addColumn('user', function ($answers) {
                return $answers->user->name;
            })
            ->addColumn('source', function ($answers) {
                return $answers->request->source;
            })
            ->addColumn('batch', function ($answers) {
                return '#'.($answers->batch + 1);
            })
            ->addColumn('date', function ($answers) {
                return date("d-m-Y [ h:i A ]", strtotime($answers->created_at));
            })
            ->addColumn('count', function ($answers) {
                return $answers->request->answers->where('batch', $answers->batch)->count().' أسئلة ';
            })
            ->addColumn('percentage', function ($answers) {
                return (number_format(($answers->request->answers->where('batch', $answers->batch)->where('answer', 1)->count() / $answers->request->answers->where('batch', $answers->batch)->count()) * 100, 0)).' % ';
            })
            ->addColumn('no', function ($answers) {
                return $answers->request->answers->where('batch', $answers->batch)->where('answer', 0)->count();
            })
            ->addColumn('yes', function ($answers) {
                return $answers->request->answers->where('batch', $answers->batch)->where('answer', 1)->count();
            })
            ->addColumn('not', function ($answers) {
                return $answers->request->answers->where('batch', $answers->batch)->where('answer', 2)->count();
            })
            ->addColumn('action', function ($answers) {
                return '<a onclick="PrviewForm('.$answers->id.')" class="btn btn-xs btn-success btn-sm  text-white">عرض الإجابات</a>';
            })
            ->rawColumns(['idn', 'not', 'yes', 'batch', 'date', 'percentage', 'count', 'no', 'active', 'question', 'yes', 'no', 'action'])->make(true);
    }
}
