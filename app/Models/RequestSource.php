<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;

class RequestSource extends BaseModel
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'request_source';

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'value' => null,
    ];
}
