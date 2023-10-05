<?php

namespace App\Observers;

use App\Helpers\MyHelpers;
use App\Models\Request;

class RequestObserver
{
    /**
     * Handle the request "created" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function created(Request $request)
    {
        //
    }

    /**
     * Handle the request "updated" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function updating(Request $request)
    {

        if($request->class_id_agent != $request->getOriginal('class_id_agent'))
        {
            $request->qualityRequests()->update(['status' => 3, 'is_followed' => 0]);
            foreach ($request->qualityRequests as $item) {
                if ($item->user_id != null){
                    MyHelpers::incrementDailyPerformanceColumn($item->user_id, 'completed_request',$request->id);
                }
            }
        }
    }

    /**
     * Handle the request "deleted" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function deleted(Request $request)
    {
        //
    }

    /**
     * Handle the request "restored" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function restored(Request $request)
    {
        //
    }

    /**
     * Handle the request "force deleted" event.
     *
     * @param  \App\Request  $request
     * @return void
     */
    public function forceDeleted(Request $request)
    {
        //
    }
}
