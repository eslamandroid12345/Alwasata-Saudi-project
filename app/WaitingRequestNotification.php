<?php

namespace App;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequest;
use App\Traits\BelongsTo\BelongsToUser;

class WaitingRequestNotification extends BaseModel
{
    use BelongsToUser;
    use BelongsToRequest;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'user_id',
    ];
}
