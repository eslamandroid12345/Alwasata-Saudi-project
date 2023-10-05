<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\Request;

use App\Models\RequestHistory;
use App\Models\RequestJob;
use App\Models\RequestRecord;
use App\Traits\HasMany\HasManyRequestRecord;

trait MoveTrait
{
    use HasManyRequestRecord;

    /**
     * return new id of agent
     * @param  null  $historyContent
     * @param  null  $oldId
     * @param  string  $historyTitle
     * @return int
     */
    public function autoMoveToAnotherAgent($historyContent = null, $oldId = null, $historyTitle = null): int
    {
        // Move request to another agent.
        $oldId = $oldId ?? $this->user_id;
        $newId = getLastAgentOfDistribution();

        // History data
        $historyContent = $historyContent ?: RequestHistory::CONTENT_AUTO_MOVE;
        $historyTitle = $historyTitle?:RequestHistory::TITLE_MOVE_REQUEST;

        // Change user ID
        if ($oldId == $newId) {
            setLastAgentOfDistribution($newId);
            $newId = getLastAgentOfDistribution();
        }

        $class_id_agent = $this->class_id_agent;
        $comment = $this->comment;
        if (($c = $this->agentClassification) && $c->isNegative()) {
            $this->createRecordHistory(RequestRecord::AGENT_CLASS_RECORD, $this->agentClassification->value, [
                'user_id' => $oldId,
                'comment' => RequestRecord::AUTO_COMMENT . " - negative",
            ]);
            $this->createRecordHistory(RequestRecord::COMMENT_RECORD, $this->comment, [
                'user_id' => $oldId,
                'comment' => RequestRecord::AUTO_COMMENT . " - negative",
            ]);
            $class_id_agent = null;
            $comment = null;
        }
        $this->update(RequestJob::newRequestAttributes($newId, [
            'agent_date'     => now(),
            'class_id_agent' => $class_id_agent,
            'comment'        => $comment,
        ]));
        setLastAgentOfDistribution($newId);

        // Request history
        $this->createHistory([
            'title'       => $historyTitle,
            'content'     => $historyContent,
            'user_id'     => $oldId,
            'received_id' => $newId,
        ]);
        try {
            $this->waitingRequests()->delete();
        }
        catch (\Exception $exception) {
        }
        return $newId;
    }

    /**
     * @param $type
     * @param $value
     * @param  array  $arguments
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createRecordHistory($type, $value, array $arguments = []): \Illuminate\Database\Eloquent\Model
    {
        $userId = $arguments['user_id'] ?? null;
        $userSwitchId = $arguments['user_switch_id'] ?? null;
        $comment = $arguments['comment'] ?? null;
        $date = $arguments['date'] ?? now('Asia/Riyadh');

        return $this->requestRecords()->create([
            'colum'          => $type,
            'value'          => $value,
            'user_id'        => $userId,
            'user_switch_id' => $userSwitchId,
            'updateValue_at' => $date,
            'comment'        => $comment,
        ]);
    }

    /**
     * Create request history
     * @param  array  $arguments
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createHistory(array $arguments = []): \Illuminate\Database\Eloquent\Model
    {
        $title = $arguments['title'] ?? null;
        $content = $arguments['content'] ?? null;
        $userId = $arguments['user_id'] ?? null;
        $userSwitchId = $arguments['user_switch_id'] ?? null;
        $receivedId = $arguments['received_id'] ?? null;
        $class_id = $arguments['class_id'] ?? null;
        if(isset($arguments['class_id_agent']))
            $class_id = $arguments['class_id_agent'] ?? null;

        $historyDate = $arguments['history_date'] ?? now('Asia/Riyadh');

        return $this->requestHistories()->create([
            'title'          => $title,
            'content'        => $content,
            'user_id'        => $userId,
            'recive_id'      => $receivedId,
            'class_id_agent' => $class_id,
            'history_date'   => $historyDate,
            'user_switch_id' => $userSwitchId,
        ]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requestHistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(RequestHistory::class, 'req_id');
    }
}
