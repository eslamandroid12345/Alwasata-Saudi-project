<?php

namespace App\Http\Controllers\Admin;

use App\GuestCustomer;
use App\Http\Controllers\Controller;
use App\request;
use App\Scenario;
use Carbon\Carbon;
use Datatables;
use function foo\func;

class HasbahRequestsService extends Controller
{
    public function guests()
    {
        $guests = GuestCustomer::orderBy('created_at');
        //-------------------------------------------------------------
        // Works
        //-------------------------------------------------------------
        if (request()->works != null) {

            $val = array_search('عسكري', request()->works);
            if (is_numeric($val) != false) {
                $guests = $guests->where(function ($a){
                    $a->whereIn('work', request()->works)
                        ->when(request()->ranks && count(request()->ranks) > 0,function ($q){
                            $q->orWhereIn('military_rank', request()->ranks);
                        });
                });
            }else{
                $guests->whereIn('work', request()->works);
            }
        }
         //-------------------------------------------------------------
        // Date Frame Time
        //-------------------------------------------------------------
        $start = request()->start_date;
        $end = request()->end_date;

        $guests->when($start && !$end, function ($q, $v) use($start){
            $q->where('created_at','>=', $start);
        })->when($end && !$start, function ($q, $v) use($end){
            $q->where('created_at','<=' ,$end);
        })->when($end && $start, function ($q, $v) use($end,$start){
            $q->whereBetween('created_at', [$start, $end]);
        });

        //-------------------------------------------------------------
        // Salary
        //-------------------------------------------------------------
        $from = (int)request()->from_salary;
        $to =(int) request()->to_salary;

        $guests->when(request()->has("from_salary") && !request()->has("to_salary"), function ($q, $v) use($from){
            $q->where('salary','>=', $from);
        })->when(request()->has("to_salary") && !request()->has("from_salary"), function ($q, $v) use($to){
            $q->where('salary','<=' ,$to);
        })->when(request()->has("to_salary") && request()->has("from_salary"), function ($q, $v) use($to,$from){
            $q->whereBetween('salary', [$from, $to]);
        });

        //-------------------------------------------------------------
        // Status
        //-------------------------------------------------------------
       /* if (request()->has("status")){
            if (count(request()->status) != 0) {
                $guests->whereIn('status', request()->status);
            }
        }

       */
        $guests = $guests->when(request()->has("searches") && request("searches") != null, function ($q, $v) {
                $q->where(function ($q){
                $q->where('name',"LIKE", "%%".request("searches")."%%")
                    ->orWhere('mobile',"LIKE", "%%".request("searches")."%%")
                    ->orWhere('email',"LIKE", "%%".request("searches")."%%");
            });
        });
        //-------------------------------------------------------------
        // Has Request
        //-------------------------------------------------------------

        if (request()->has("has_request")) {
            $guests->whereIn('has_request', request()->has_request);
        }


        return DataTables::of($guests)
            ->addColumn('idn', function () {
                static $var = 1;
                return $var++;
            })
            ->addColumn('date', function ($guest) {
                return Carbon::parse($guest->created_at)->format('Y-m-d')."<small class='text-danger d-block' style='margin-top: -5px'>".Carbon::parse($guest->created_at)->format('h:i A')."</small>";
            })
            ->addColumn('name', function ($guest) {
                return $guest->name;
            })
            ->addColumn('count', function ($guest) {
                return $guest->count;
            })
            ->addColumn('email', function ($guest) {
                return $guest->email;
            })
            ->addColumn('mobile', function ($guest) {
                return $guest->mobile;
            })
            ->addColumn('salary', function ($guest) {
                return $guest->salary;
            })
            ->addColumn('status', function ($guest) {
                return !$guest->status ? ' <span class="badge badge-warning">لم يكمل</span>' : ' <span class="badge badge-primary">أكمل الطلب</span>';
            })
            ->addColumn('has_request', function ($guest) {
                return !$guest->has_request ? '<span class="badge badge-info">ليس لديه طلب سابق</span>' : ' <span class="badge badge-success">لديه طلب سابق</span>';
            })
            ->addColumn('work', function ($guest) {
                $ask = $guest->work == 'عسكري' ? '- '.$guest->military_rank : '';
                return $guest->work. $ask;
            })
            ->addColumn('action', function ($guest) {
                return  '<a onclick="deleteData('.$guest->id.')" class="btn btn-xs btn-danger btn-sm text-white"><i class="fa fa-trash"></i></a>';

            })
            ->rawColumns(['idn',"date", 'work','salary', 'has_request','status','name','count','mobile','email', 'action'])->make(true);
    }
}
