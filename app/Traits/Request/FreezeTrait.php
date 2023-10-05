<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\Request;

use App\Models\Request;
use App\Models\RequestHistory;
use App\Models\RequestJob;
use App\Models\RequestRecord;
use Illuminate\Database\Eloquent\Builder;

trait FreezeTrait
{

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeFreezeOnly(Builder $builder): Builder
    {
        return $builder->where('is_freeze', !0);
    }

    /**
     * @return bool
     */
    public function isFreeze(): bool
    {
        return (bool) $this->is_freeze;
    }

    /**
     * Move request to freeze
     * @param $content
     * @return Request
     */
    public function moveToFreeze($content): self
    {
        $this->createHistory([
            'user_id'        => $this->user_id,
            'title'          => RequestHistory::MOVE_TO_FREEZE,
            'content'        => $content,
            'class_id_agent' => $this->class_id_agent,
        ]);
        $this->createRecordHistory(RequestRecord::AGENT_CLASS_RECORD, $this->agentClassification->value, [
            'user_id' => $this->user_id,
            'comment' => RequestRecord::AUTO_COMMENT." - ".RequestHistory::MOVE_TO_FREEZE,
        ]);
        $this->createRecordHistory(RequestRecord::COMMENT_RECORD, $this->comment, [
            'user_id' => $this->user_id,
            'comment' => RequestRecord::AUTO_COMMENT." - ".RequestHistory::MOVE_TO_FREEZE,
        ]);
        try {
            $this->classificationAlertSchedules()->where(['classification_id' => $this->class_id_agent])->delete();
        }
        catch (\Exception $exception) {
        }
        $this->update(RequestJob::newRequestAttributes(null, [
            'is_freeze'      => !0,
            'class_id_agent' => null,
            'comment'        => null,
            'agent_date'     => null,
            'classification_before_to_freeze' => $this->class_id_agent
        ]));
        try {
            $this->waitingRequests()->delete();
        }
        catch (\Exception $exception) {
        }
        return $this;
    }
}
