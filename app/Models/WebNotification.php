<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequest;
use App\Traits\HasMany\HasManyClassificationAlertSetting;
use App\Traits\HasMany\HasManyRequest;

class WebNotification extends BaseModel
{
    use BelongsToRequest;

    protected $table = 'notifications';

    public function belongsToRequestForeignKey(): string
    {
        return  'req_id';
    }
}
