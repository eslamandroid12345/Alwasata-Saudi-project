<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Traits\BelongsTo\BelongsToRequest;
use Exception;

class RequestJob extends BaseModel{

    use BelongsToRequest;

    /**
     * Automatic move request after is frozen
     *
     * @var string
     */
    const BACK_FROM_FROZEN_BY_REGISTER_AUTO = 'back_from_frozen_by_register_auto';

    /**
     * Customer is back after Unable To Communicate
     */
    const BACK_FROM_UNABLE_TO_COMMUNICATE = 'back_from_unable_to_communicate';

    /**
     * Check from request the customer is back after Unable To Communicate
     */
    const CHECK_FROM_BACK_OF_UNABLE_TO_COMMUNICATE = 'check_from_back_of_unable_to_communicate';

    /**
     * Used in data attribute 'source_back'
     */
    const SOURCE_WEB = 'web';

    /**
     * Used in data attribute 'source_back'
     */
    const SOURCE_HASBAH = 'hasbah';

    /**
     * Used in data attribute 'source_back'
     * new request from mobile app
     */
    const SOURCE_APP_REQUEST = 'app_request';

    /**
     * Used in data attribute 'source_back'
     * new calculation request from mobile app
     */
    const SOURCE_APP_CALCULATOR = 'app_calculator';

    /**
     * Used in data attribute 'source_back'
     * source from chat mobile application
     */
    const SOURCE_APP_MESSAGE = 'app_message';

    /**
     * Used in data attribute 'source_back'
     * source from application the classification was changed
     */
    const SOURCE_CHANGE_CLASS = 'change_class';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'action',
        'data',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'request_id' => null,
        'data'       => '[]',
    ];

    /**
     * Apply all job is database
     */
    public static function runJobs(): void
    {
        $jobs = static::query()->get();

        foreach ($jobs as $job) {
            $job->readJob();
            try {
                $job->delete();
            }
            catch (\Exception $exception) {
            }
        }
    }

    /**
     * Read job from database
     */
    public function readJob(): void
    {
        /** @var array $data */
        $data = $this->data;
        $action = $this->action;

        /** @var Request $request */
        if (!($request = $this->request)) {
            try {
                $this->delete();
            }
            catch (Exception $exception) {
            }
            return;
        }

        switch ($action) {
            case static::BACK_FROM_FROZEN_BY_REGISTER_AUTO:
                if (!$request->isFreeze()) {
                    break;
                }
                // Move request to another agent.
                $history = $request->requestHistories()->latest('history_date')->first();
                $oldId = $request->user_id ?: ($history ? $history->recive_id : null);
                $historyContent = $data['historyContent'] ?? RequestHistory::CONTENT_FROZEN_NEW_BACK;
                $historyContent .= " - ".($data['source_back'] ?? '');
                $historyContent = trim($historyContent, ' -');
                $newId = $request->autoMoveToAnotherAgent($historyContent, $oldId, RequestHistory::MOVE_FROM_FREEZE);
                break;
            case static::BACK_FROM_UNABLE_TO_COMMUNICATE:
                $historyContent = static::BACK_FROM_UNABLE_TO_COMMUNICATE;
                $historyContent .= " - ".($data['source_back'] ?? '');
                $historyContent = trim($historyContent, ' -');
                $request->customerBackAfterUnableToCommunicate($historyContent);
                $title = $request->isFreeze() ? RequestHistory::MOVE_FROM_FREEZE : null;
                $request->autoMoveToAnotherAgent($historyContent, null, $title);
                break;
            case static::CHECK_FROM_BACK_OF_UNABLE_TO_COMMUNICATE:
                $historyContent = static::CHECK_FROM_BACK_OF_UNABLE_TO_COMMUNICATE;
                $historyContent .= " - ".($data['source_back'] ?? '');
                $historyContent = trim($historyContent, ' -');
                $title = $request->isFreeze() ? RequestHistory::MOVE_FROM_FREEZE : null;
                if ($request->class_id_agent == Classification::AGENT_UNABLE_TO_COMMUNICATE) {
                    $request->customerBackAfterUnableToCommunicate($historyContent);
                    $request->autoMoveToAnotherAgent($historyContent, null, $title);
                    if (($model = $request->classificationAlertSchedules()->where('classification_id', Classification::AGENT_UNABLE_TO_COMMUNICATE)->first())) {
                        try {
                            $model->delete();
                        }
                        catch (\Exception $exception) {
                        }
                    }
                }
                break;
        }
    }

    /**
     * @param  null  $userId
     * @param  array  $merge
     * @return array
     */
    public static function newRequestAttributes($userId = null, array $merge = []): array
    {
        return array_merge([
            'user_id'                 => $userId,
            'statusReq'               => 0,
            'agent_date'              => now(),
            'is_stared'               => 0,
            'is_followed'             => 0,
            'is_freeze'               => 0,
            'add_to_stared'           => null,
            'add_to_followed'         => null,
            'isUnderProcFund'         => 0,
            'isUnderProcMor'          => 0,
            'recived_date_report'     => null,
            'recived_date_report_mor' => null,
            //'class_id_agent'          => null,
            'add_to_archive'          => null,
            'remove_from_archive'     => null,
        ], $merge);
    }
}
