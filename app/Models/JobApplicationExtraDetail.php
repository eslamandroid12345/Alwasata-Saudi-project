<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicationExtraDetail extends Model
{
    public $table='job_application_extra_details';
    protected $fillable=['job_app_id','type','title','start_date','end_date'];
}
