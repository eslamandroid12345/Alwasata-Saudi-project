<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\JobApplication;
class Nationality extends Model
{
    use SoftDeletes;
    protected $fillable=['title','status','sort'];
    
    public function job_application(){
        return $this->hasMany(JobApplication::class);
    }
}
