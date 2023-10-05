<?php

use App\Model\RequestSearching;
use Illuminate\Database\Seeder;
use App\request as AppRequest;

class SearchingRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $rquests = AppRequest::get();//->last()->id;

        foreach($rquests as $rquest){

           if(!$rquest->searching_id){
            RequestSearching::create(
                ['id'=>$rquest->id]
            );
            $rquest->update(['searching_id'=>$rquest->id]);
           }
        }
    }
}
