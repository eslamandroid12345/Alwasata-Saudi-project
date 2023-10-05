<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Http\View\Composers;

use App\Models\Request;
use App\Models\User;
use App\Models\QualityRequestNeedTurned;
use Illuminate\View\View;

class SideBarComposer implements AppComposerInterface
{

    /**
     * Bind the views
     * @return string[]
     */
    public static function views(): array
    {
        return ['layouts.sideBar'];
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view): View
    {
        $freesCount = Request::query()->where('is_freeze', !0)->count();

        if (auth()->user()->role != 7){//admin
            $need_turned_requests =auth()->user()->quality_request_need_turneds()->count();
            $need_turned_done_requests =auth()->user()->quality_request_need_turneds()->whereIn('status',[1,2])->count();
        }
        else{
            $need_turned_requests =QualityRequestNeedTurned::query()->where('status',0)->count();
            $need_turned_done_requests =QualityRequestNeedTurned::query()->whereIn('status',[1,2])->count();
        }

        return $view->with([
            'freesCount' => $freesCount,
            'need_turned_requests' => $need_turned_requests,
            'need_turned_done_requests' => $need_turned_done_requests
        ]);
    }
}
