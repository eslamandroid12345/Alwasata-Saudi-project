<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class realType extends Model
{
    protected $guarded =[];
    protected $table ='real_types';
    protected $fillable = ['value','parent_id'];

    public function parent()
    {
        return $this->belongsTo(self::class,'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class,'parent_id');
    }

    public function grandchildren()
    {
        return $this->children()->with('grandchildren');
    }
}
