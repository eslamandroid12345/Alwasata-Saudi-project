<?php

namespace App\Composers;


use MyHelpers;
use App\funding_source;
use App\salary_source;
use Illuminate\Support\Facades\DB;

class FundingOptionsComposer
{

    public function compose($view)
    {
        $all_status = $this->status();
        $pay_status = $this->statusPay();
        $all_salaries = DB::table('salary_sources')->get();
        $founding_sources = DB::table('funding_sources')->get();
        $realTypes = DB::table('real_types')->select('id', 'value')->get();

        //Add your variables
        $view->with([
            'all_status' => $all_status,
            'pay_status' => $pay_status,
            'all_salaries' => $all_salaries,
            'realTypes' => $realTypes,
            'founding_sources' => $founding_sources,
        ]);
    }

    public function status($getBy = 'empty')
    {
        $s = [
            0 => MyHelpers::admin_trans(auth()->user()->id, 'new req'),
            1 => MyHelpers::admin_trans(auth()->user()->id, 'open req'),
            2 => MyHelpers::admin_trans(auth()->user()->id, 'archive in sales agent req'),
            3 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            4 => MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            //5 => MyHelpers::admin_trans(auth()->user()->id, 'archive in sales manager req'),
            5 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            6 => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            7 => MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            // 8 => MyHelpers::admin_trans(auth()->user()->id, 'archive in funding manager req'),
            8 => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            9 => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            10 => MyHelpers::admin_trans(auth()->user()->id, 'rejected mortgage manager req'),
            // 11 => MyHelpers::admin_trans(auth()->user()->id, 'archive in mortgage manager req'),
            11 => MyHelpers::admin_trans(auth()->user()->id, 'wating mortgage manager req'),
            12 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            13 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            //14 => MyHelpers::admin_trans(auth()->user()->id, 'archive in general manager req'),
            14 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            15 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            16 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            17 => MyHelpers::admin_trans(auth()->user()->id, 'draft in mortgage maanger'),
            18 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales manager req'),
            19 => MyHelpers::admin_trans(auth()->user()->id, 'wating sales agent req'),
            20 => MyHelpers::admin_trans(auth()->user()->id, 'rejected sales manager req'),
            21 => MyHelpers::admin_trans(auth()->user()->id, 'wating funding manager req'),
            22 => MyHelpers::admin_trans(auth()->user()->id, 'rejected funding manager req'),
            23 => MyHelpers::admin_trans(auth()->user()->id, 'wating general manager req'),
            24 => MyHelpers::admin_trans(auth()->user()->id, 'cancel mortgage manager req'),
            25 => MyHelpers::admin_trans(auth()->user()->id, 'rejected general manager req'),
            26 => MyHelpers::admin_trans(auth()->user()->id, 'Completed'),
            27 => MyHelpers::admin_trans(auth()->user()->id, 'Canceled'),
            28 => MyHelpers::admin_trans(auth()->user()->id, 'Undefined'),
            29 => MyHelpers::admin_trans(auth()->user()->id, 'Rejected and archived')
        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[28]);
    }
    public function statusPay($getBy = 'empty')
    {
        $s = [
            0 => MyHelpers::admin_trans(auth()->user()->id, 'draft in funding manager'),
            1 => MyHelpers::admin_trans(auth()->user()->id, 'wating for sales maanger'),
            2 => MyHelpers::admin_trans(auth()->user()->id, 'funding manager canceled'),
            3 => MyHelpers::admin_trans(auth()->user()->id, 'rejected from sales maanger'),
            4 => MyHelpers::admin_trans(auth()->user()->id, 'wating for sales agent'),
            5 => MyHelpers::admin_trans(auth()->user()->id, 'wating for mortgage maanger'),
            6 => MyHelpers::admin_trans(auth()->user()->id, 'rejected from mortgage maanger'),
            7 => MyHelpers::admin_trans(auth()->user()->id, 'approve from mortgage maanger'),
            8 => MyHelpers::admin_trans(auth()->user()->id, 'mortgage manager canceled'),
            9 => MyHelpers::admin_trans(auth()->user()->id, 'The prepayment is completed'),
            10 => MyHelpers::admin_trans(auth()->user()->id, 'rejected from funding manager'),
            11 => MyHelpers::admin_trans(auth()->user()->id, 'Undefined'),

        ];

        return $getBy == 'empty' ? $s : (isset($s[$getBy]) ? $s[$getBy] : $s[28]);
    }
}
