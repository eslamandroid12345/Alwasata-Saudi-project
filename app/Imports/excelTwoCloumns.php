<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class excelTwoCloumns implements ToCollection, WithHeadingRow
{
    /**
     * @param  Collection  $collection
     */
    public function collection(Collection $rows)
    {
    }

    public function findNextAgent()
    {
        $last_req_id = DB::table('requests')->max('id'); // latest request_id
        $last_req = DB::table('requests')->where('id', $last_req_id)->get(); // latest request object
        $last_user_id = $last_req[0]->user_id;
        $maxValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->max('id'); // last user id (Sale Agent User)
        $minValue = DB::table('users')->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id'); // first user id (Sale Agent User)

        if ($last_user_id == null) {
            $last_user_id = 61;
        } //Ahmed Qassem

        if ($last_user_id == $maxValue) {
            $user_id = $minValue;
        }
        else {
            // get next user id
            $user_id = User::where('id', '>', $last_user_id)->where('role', 0)->where('allow_recived', 1)->where('status', 1)->min('id');
        }

        return $user_id;

    }

    public function rules(): array
    {
        return [
            'number' => Rule::unique('customers', 'mobile'), // Table name, field in your db
        ];
    }

    public function customValidationMessages()
    {
        return [
            'number.unique' => 'Custom message',
        ];
    }
}
