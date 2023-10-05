<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
// use DB;

class DeleteAgentReceivedRequestsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $requests = \DB::table('quality_reqs')
        ->join('requests', 'requests.id', 'quality_reqs.req_id')
        ->join('users', 'users.id', 'requests.user_id')
        ->join('customers', 'customers.id', '=', 'requests.customer_id')
        ->where('quality_reqs.allow_recive', 1)
        ->whereIn('quality_reqs.status', [0, 1, 2])
        ->where('quality_reqs.is_followed', 0)
        ->select('quality_reqs.id', 'requests.id as reqID', 'users.name as agentName', 'customers.name', 'customers.salary', 'customers.mobile', 'requests.comment', 'requests.statusReq', 'requests.quacomment', 'requests.class_id_agent', 'requests.class_id_quality', 'quality_reqs.status',
            'requests.type', 'requests.collaborator_id', 'requests.source', 'quality_reqs.created_at', 'requests.created_at as req_created_at')
        // ->update(['quality_reqs.updated_at' => null]);
        ->delete();
            // $requests->delete();
    }
}
