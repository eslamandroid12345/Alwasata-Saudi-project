<?php

namespace App\Observers;

use App\Employee;
use App\reqRecord;
use Carbon\Carbon;

class EmployeeObserve
{
    /**
     * Handle the employee "created" event.
     *
     * @param  \App\Employee  $employee
     * @return void
     */
    public function created(Employee $employee)
    {
        //
    }

    /**
     * Handle the employee "updated" event.
     *
     * @param  \App\Employee  $employee
     * @return void
     */
    public function saved(Employee $employee)
    {


        foreach ($employee->getChanges() as $key=>$change) {
            reqRecord::create([
                'colum'          => 'profile_'.$key,
                'user_id'        => (auth()->user()->id),
                'value'          => $change,
                'updateValue_at' => Carbon::now('Asia/Riyadh'),
                'comment'        => $employee->user_id,
            ]);
        }
    }

    /**
     * Handle the employee "deleted" event.
     *
     * @param  \App\Employee  $employee
     * @return void
     */
    public function deleted(Employee $employee)
    {
        //
    }

    /**
     * Handle the employee "restored" event.
     *
     * @param  \App\Employee  $employee
     * @return void
     */
    public function restored(Employee $employee)
    {
        //
    }

    /**
     * Handle the employee "force deleted" event.
     *
     * @param  \App\Employee  $employee
     * @return void
     */
    public function forceDeleted(Employee $employee)
    {
        //
    }
}
