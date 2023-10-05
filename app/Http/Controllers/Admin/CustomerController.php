<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use MyHelpers;
use Datatables;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::whereHas('request')->whereNotNull("app_rate_starts")->count();
        $rated_customers = Customer::whereHas('request')->whereNotNull("app_rate_starts")->get();
        $sales_agents=[];
        foreach ($rated_customers as $customer) {
            if(isset($customer->request->user)){
                $sales_agents[]=$customer->request->user;
            }
        }
        $salesAgents=array_unique($sales_agents);
        return view("Admin.Rates.index",compact("customers","salesAgents"));
    }

    public function datatable()
    {
        $request=request();
        // $customers = Customer::whereHas('request')->whereNotNull("app_rate_starts")->orderBy('id', 'DESC');

        $customers = Customer::query()->whereHas('request')->whereNotNull("app_rate_starts")->orderBy('date_of_rate', 'DESC')->get();

        if ($request->get('stars')) {
            $customers = $customers->where('app_rate_starts', $request->get('stars'));
        }

        if ($request->get('rate_date')) {
            $customers = $customers->where('date_of_rate',$request->get('rate_date'));
        }

        if ($request->get('agent_id')) {
            $customers = $customers->where('request.user.id', $request->get('agent_id'));
        }

        if ($request->get('is_processed') == '0') {
           $customers =$customers->where('is_processed', 0);
        }

        if ($request->get('is_processed') == '1') {
           $customers =$customers->where('is_processed', 1);
        }


        return Datatables::of($customers)->addColumn('action', function ($row) {
            $data = '<div class="tableAdminOption"><span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="عرض تفاصيل الطلب">
                            <a href="'.route('admin.fundingRequest', $row->request->id).'"> <i class="fa fa-eye"></i></a>
                      </span>
                      <span type="span" class="item pointer" data-toggle="tooltip" data-placement="top" title="سجل الطلب">
                            <a href="'.route('all.reqHistory', $row->request->id).'"> <i class="fa fa-list"></i></a>
                      </span>';

            if($row->is_processed == 0){
                $data .= '<span class="item pointer make-it-processed" id="open" data-id="'.$row->id.'" data-toggle="tooltip" data-placement="top" title="تمت العالجة" >
                            <i class="fa fa-check"></i></a></span>';
            }

            $data.='</div>';
            return $data;
        })->addColumn('salary', function ($row) {
            if ($row->salary != null) {
                $data = $row->salary.' '.MyHelpers::admin_trans(auth()->user()->id, 'SR');
            }
            else {
                $data = '---';
            }
            return $data;
        })->addColumn('user_name', function ($row) {
            return @$row->request->user->name;
        })->addColumn('comment', function ($row) {
            return $row->app_rate_comment ?? 'لا يوجد تعليق';
        })->addColumn('stars', function ($row) {
            $rate='';
            for ($i=0;$i<5;$i++) {
                if ($i<$row->app_rate_starts){
                    $rate.='<span class="fa fa-star text-warning"></span>';
                }else{
                    $rate.='<span class="fa fa-star text-muted"></span>';
                }
            }
            return $rate;
        }) ->addColumn('is_processed', function ($row) {
            $data = '<div style="text-align: center;">';

            if($row->is_processed == 1){
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="نعم">
                <i class="fas fa-check"></i></span>';
            }
            else {
                $data = $data.'<span class="item pointer"  text-align: center;" data-toggle="tooltip" data-placement="top" title="لا">
                <i class="fas fa-times"></i></span>';
            }
            $data = $data.'</div>';
            return $data;
        })
        ->rawColumns(['stars','action','is_processed'])->make(true);
    }

    public function updateIsProcessedRate(Request $request)
    {
        $id=$request->customer_id;
        $customer=Customer::find($id)->update([
            'is_processed' => 1
        ]);
        return response()->json([ 'customer' => $customer ]);
    }
}
