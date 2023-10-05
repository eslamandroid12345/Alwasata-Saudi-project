<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;

class RequestStatus extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'request_status';

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status_id' => 0,
        'value'     => null,
    ];
}
