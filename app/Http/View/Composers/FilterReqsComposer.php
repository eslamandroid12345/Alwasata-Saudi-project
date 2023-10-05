<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use App\classifcation;
use App\funding_source;
use App\Models\Customer;
use App\Models\User;
use App\salary_source;
use App\WorkSource;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class FilterReqsComposer implements AppComposerInterface
{

    /**
     * Bind the views
     * @return string[]
     */
    public static function views(): array
    {
        return ['Admin.Request.filterReqs'];
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view): View
    {
        $salesAgents = User::where('role', 0)->get();
        $classifcations_sa = classifcation::where('user_role', 0)->get();
        $classifcations_sm = classifcation::where('user_role', 1)->get();
        $classifcations_fm = classifcation::where('user_role', 2)->get();
        $classifcations_mm = classifcation::where('user_role', 3)->get();
        $classifcations_gm = classifcation::where('user_role', 4)->get();
        $classifcations_qu = classifcation::where('user_role', 5)->get();
        $all_status = requestsStatuses();
        $pay_status = requestsStatusesPay();
        $request_sources = DB::table('request_source')->get();
        $regions = Customer::select('region_ip')->groupBy('region_ip')->get();
        $worke_sources = WorkSource::all();
        $all_salaries = salary_source::all();
        $founding_sources = funding_source::all();

        return $view->with([
            'salesAgents'       => $salesAgents,
            'classifcations_sa' => $classifcations_sa,
            'classifcations_sm' => $classifcations_sm,
            'classifcations_fm' => $classifcations_fm,
            'classifcations_mm' => $classifcations_mm,
            'classifcations_gm' => $classifcations_gm,
            'classifcations_qu' => $classifcations_qu,
            'all_status'        => $all_status,
            'pay_status'        => $pay_status,
            'request_sources'   => $request_sources,
            'regions'           => $regions,
            'worke_sources'     => $worke_sources,
            'all_salaries'      => $all_salaries,
            'founding_sources'  => $founding_sources,
        ]);
    }
}
