<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Traits\Request;

use App\Models\Classification;

trait UnableToCommunicateClassificationTrait
{
    public function customerBackAfterUnableToCommunicate($backMethod)
    {
        if (($model = $this->classificationQuestionnaires()->where([
            'classification_id' => Classification::AGENT_UNABLE_TO_COMMUNICATE,
            'user_id'           => $this->user_id,
            'title'             => null,
            'value'             => null,
        ])->whereNull(['value', 'title'])->first())) {
            // # Change value to true
            $model->update([
                'title' => $backMethod,
                'value' => !0,
            ]);
        }
    }
}
