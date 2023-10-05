<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $guarded =[];

    protected $fillable = [

        'content',
        'attachment',
        'color',
        'status',
        'end_at',

    ];

    protected $table ='announcements';

    public function roles()
    {
        return $this->hasMany('App\AnnounceRole','announce_id','id');
    }
    public function seens()
    {
        return $this->hasMany('App\AnnounceSeen','announce_id','id');
    }
    public function users()
    {
        return $this->hasMany('App\AnnounceUser','announce_id','id');
    }
}
