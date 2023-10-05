<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;

class request extends \App\Models\Request
{
    public function scopeDateFilter(Builder $builder,$start,$end):Builder
    {
        return $builder->when($start && !$end, function ($q, $v) use($start){
            $q->where('created_at','>=', $start);
        })->when($end && !$start, function ($q, $v) use($end){
            $q->where('created_at','<=' ,$end);
        })->when($end && $start, function ($q, $v) use($end,$start){
            $q->whereBetween('created_at', [$start, $end]);
        });
    }

    public function scopeDateForAgentFilter(Builder $builder,$start,$end):Builder
    {
        return $builder->when($start && !$end, function ($q, $v) use($start){
            $q->where('created_at','>=', $start);
        })->when($end && !$start, function ($q, $v) use($end){
            $q->where('created_at','<=' ,$end);
        })->when($end && $start, function ($q, $v) use($end,$start){
            $q->whereBetween('created_at', [$start, $end]);
        });
    }
}
