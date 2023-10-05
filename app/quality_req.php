<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class quality_req extends Model
{

    protected $guarded =[];
   // protected $fillable = ['allow_recive', 'user_id', 'req_id','con_id','status','is_followed'];
   // Protected $primaryKey = "id";
    protected $table ='quality_reqs';

    public function request()
    {
        return $this->belongsTo("App\Models\Request","req_id");
    }

    public function user_data()
    {
        return $this->belongsTo("App\User","user_id");
    }
}
