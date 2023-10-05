<?php

use App\Models\Request;
use Illuminate\Database\Seeder;

class UndoFreezeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // move_to_freeze
        $ids = [
                111819,111808,111803,111793,111781,111743,111728,111706,
                111692,111690,111643,111636,111624,111612,111598,111594,111585,
                111584,111582,111571,111558,111555,111547,111544,111538,111521,
                111499,111484,111234,111166,111146,111119,111111,111075,111047,111041
            ];
        $reqs = Request::whereIn('id',$ids)->where('is_freeze', '1')->get();
        foreach ($reqs as $req) {
            $history = $req->requestHistories()->where('title', 'move_to_freeze')->latest()->first();
            if($history)
            {
                echo 'id : '.$history->id.'; req_id: '.$history->req_id.'; user_id: '.$history->user_id;
                $req->update([
                    'class_id_agent'                    => $req->classification_before_to_freeze,
                    'is_freeze'                         => 0,
                    'user_id'                           => $history->user_id,
                    'classification_before_to_freeze'   => null
                 ]);
                //  $history->delete();
            }
        }
    }
}
