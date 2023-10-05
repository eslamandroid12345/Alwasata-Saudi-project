<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    public static function getByIndex($index)
    {
        //return self::where('option_name',$index)->first()->option_value ;
    }

    public static function getMessage($index)
    {
        //return self::where('option_name',$index)->first()->display_name ;
    }

    public static function getBankDelegateHost(): string
    {
        return setting('bank_delegate_host') ?? 'localhost:8000';
    }

    public static function setBankDelegateHost($value): void
    {
        setting(['bank_delegate_host' => $value])->save();
    }

    public static function allSetting(){
        return setting()->all();
    }
}
