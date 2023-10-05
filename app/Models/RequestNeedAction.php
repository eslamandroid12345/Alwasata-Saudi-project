<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Models\Classification as Model;
use App\Traits\BelongsTo\BelongsToCustomer;
use App\Traits\BelongsTo\BelongsToRequest;
use App\Traits\BelongsTo\BelongsToUser;
use App\Traits\HasMany\HasManyClassificationAlertSchedule;
use App\Traits\HasMany\HasManyClassificationQuestionnaire;
use App\Traits\HasMany\HasManyRequestJob;
use App\Traits\HasMany\HasManyWaitingRequest;
use App\Traits\HasOne\HasOneQualityRequest;
use App\Traits\Request\FreezeTrait;
use App\Traits\Request\MoveTrait;
use App\Traits\Request\OldModelTrait;
use App\Traits\Request\SourcesTrait;
use App\Traits\Request\UnableToCommunicateClassificationTrait;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Myth\Api\Traits\HasMythApi;

/**
 *
 */
class RequestNeedAction extends BaseModel
{
    use BelongsToCustomer;
    use BelongsToRequest;
    use BelongsToUser;

    /**
     * @return string
     */
    public function belongsToRequestForeignKey(): string
    {
        return 'req_id';
    }

    /**
     * @return string
     */
    public function belongsToUserForeignKey(): string
    {
        return 'agent_id';
    }
}
