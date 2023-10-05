<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Models\Classification as Model;
use App\Traits\BelongsTo\BelongsToCustomer;
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
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 *
 */
class Request extends BaseModel
{

    use SoftDeletes;
    /** @var int Source Value: hasbah.net COMPLETED */
    const HASBAH_SOURCE = 1001;

    /** @var int Source Value: hasbah.net NOT_COMPLETED */
    const HASBAH_SOURCE_NOT_COMPLETE = 1002;

    use SourcesTrait;
    use BelongsToCustomer;
    use BelongsToUser;
    use HasManyClassificationAlertSchedule;
    use HasManyClassificationQuestionnaire;
    use HasManyWaitingRequest;
    use HasManyRequestJob;
    use HasOneQualityRequest;
    use HasMythApi;
    use OldModelTrait;
    use UnableToCommunicateClassificationTrait;
    use FreezeTrait;
    use MoveTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agent_date',
        'is_freeze',
        'type',
        'statusReq',
        'source',
        'req_date',
        'comment',
        'customer_id',
        'collaborator_id',
        'user_id',
        'class_id_agent',
        'payment_id',
        'fun_id',
        'real_id',
        'joint_id',
        'customer_reason_for_rejected',
        'searching_id',
        'cancel_request_count',
        'collaborator_notes',
        'customer_want_to_contact_date',
        'postponed_communication_status',
        'deleted_at',
        'classification_before_to_freeze',
        'phoneNumbers',
        'bank_notes',
        'private_notes',
        'is_pulled',
        // 'funding_source'
    ];


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'req_date'   => 'datetime',
        'agent_date' => 'datetime',
    ];

    /**
     * The model's attributes.
     *
     * @var array
     */
    protected $attributes = [
        'is_freeze' => !1,
    ];

    /**
     *
     */
    public function scopeDateFilter(Builder $builder,$start,$end):Builder
    {
        return $builder->when($start && !$end, function ($q, $v) use($start){
            $q->where('created_at','>=', $start);
        })->when($end && !$start, function ($q, $v) use($end){
            $q->where('created_at','<=' ,$end);
        })->when($end && $start, function ($q, $v) use($end,$start){
            $q->whereBetween('created_at', [$start, $end]);
        });
    }
    public function scopeDateForAgentFilter(Builder $builder,$start,$end):Builder
    {
        return $builder->when($start && !$end, function ($q, $v) use($start){
            $q->where('updated_at','>=', $start);
        })->when($end && !$start, function ($q, $v) use($end){
            $q->where('updated_at','<=' ,$end);
        })->when($end && $start, function ($q, $v) use($end,$start){
            $q->whereBetween('updated_at', [$start, $end]);
        });
    }
    protected static function boot()
    {
        parent::boot();
        static::deleting(function (self $model) {
            try {
                $model->requestHistories()->delete();
            }
            catch (Exception $exception) {
            }
            try {
                $model->requestRecords()->delete();
            }
            catch (Exception $exception) {
            }
            try {
                $model->requestJobs()->delete();
            }
            catch (Exception $exception) {
            }
            //try {
            //$model->customer()->delete();
            //}
            //catch (\Exception $exception) {
            //}
            try {
                $model->joint()->delete();
            }
            catch (Exception $exception) {
            }

            try {
                $model->real_estat()->delete();
            }
            catch (Exception $exception) {
            }
            try {
                $model->funding()->delete();
            }
            catch (Exception $exception) {
            }
            try {
                $model->prePayment()->delete();
            }
            catch (Exception $exception) {
            }
        });
    }

    /**
     * @return BelongsTo
     */
    public function prePayment(): BelongsTo
    {
        return $this->belongsTo(PrePayment::class, 'payment_id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function agentClassification(): BelongsTo
    {
        return $this->belongsTo(Model::class, 'class_id_agent')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function agentClassificationBeforeFreeze(): BelongsTo
    {
        return $this->belongsTo(Model::class, 'classification_before_to_freeze')->withDefault();
    }

    /**
     * @param  Classification|string  $classification
     * @param  bool  $value
     * @param  string|null  $title
     * @param  string|null  $body
     * @return Classification|\Illuminate\Database\Eloquent\Model
     */
    public function makeClassificationQuestionnaire($classification, bool $value, ?string $title = null, ?string $body = null): Model
    {
        if ($classification instanceof \Illuminate\Database\Eloquent\Model) {
            $classification = $classification->id;
        }

        return $this->classificationQuestionnaires()->create([
            'user_id'           => $this->user_id,
            'classification_id' => $classification,
            'title'             => $title,
            'body'              => $body,
            'value'             => $value,
        ]);
    }

    /**
     * @param  int  $step
     * @param  Carbon|string|null  $time
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function makeNewClassificationAlertSchedule(int $step = 0, $time = null): \Illuminate\Database\Eloquent\Model
    {
        //$time = is_null($time) ? ($this->updated_at ?: ($this->created_at ?: now())) : Carbon::make($time);
        // 2020-01-10
        $time = is_null($time) ? now() : Carbon::make($time);
        $agentClass = $this->class_id_agent;
        $model = $this->classificationAlertSchedules()->create([
            'classification_id' => $agentClass,
            'step'              => $step,
            'send_time'         => $time,
        ]);

        if (!$step) {
            if ($agentClass === Classification::AGENT_UNABLE_TO_COMMUNICATE) {
                // Create Questionnaire
                $this->classificationQuestionnaires()->firstOrCreate([
                    //$this->classificationQuestionnaires()->create([
                    'classification_id' => $agentClass,
                    'user_id'           => $this->user_id,
                    'title'             => null,
                    'body'              => null,
                    'value'             => null,
                ]);
            }
        }

        return $model;
    }

    public function makeNewClassificationAlertScheduleForPostponed(int $step = 0, $time = null) : \Illuminate\Database\Eloquent\Model
    {
        $time = is_null($time) ? now() : Carbon::make($time);
        $agentClass = $this->class_id_agent;
        $model = $this->classificationAlertSchedules()->create([
            'classification_id' => $agentClass,
            'step'              => $step,
            'send_time'         => $time,
        ]);
        if (!$step) {
            if ($agentClass === Classification::TEST_CLASSIFICATION) {
            // if ($agentClass === Classification::POSTPONED_COMMUNICATION) {
                // Create Questionnaire
                $this->classificationQuestionnaires()->firstOrCreate([
                    //$this->classificationQuestionnaires()->create([
                    'classification_id' => $agentClass,
                    'user_id'           => $this->user_id,
                    'title'             => null,
                    'body'              => null,
                    'value'             => null,
                ]);
            }
        }
        return $model;
    }

    /**
     * @return BelongsTo
     */
    public function requestSource(): BelongsTo
    {
        return $this->belongsTo(RequestSource::class, 'source')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function requestStatus(): BelongsTo
    {
        return $this->belongsTo(RequestStatus::class, 'statusReq', 'status_id')->withDefault();
    }

    /**
     * @return string|null
     */
    public function getRequestStatusToStringAttribute(): ?string
    {
        return $this->requestStatus->value ?: '';
    }

    /**
     * @return string|null
     */
    public function getAgentClassificationToStringAttribute(): ?string
    {
        return (string) ($this->agentClassification->value ?: $this->class_id_agent);
    }

    /**
     * @return BelongsTo
     */
    public function collaborator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collaborator_id')->withDefault();
    }


    /**
     * @param $action
     * @param  array  $data
     */
    public function createJob($action, array $data = [])
    {
        $this->requestJobs()->create([
            'action' => $action,
            'data'   => $data,
        ]);
    }

    /**
     * @return string
     */
    public function hasManyRequestRecordForeignKey(): string
    {
        return 'req_id';
    }

    /**
     * @return string
     */
    public function hasOneQualityRequestForeignKey(): string
    {
        return 'req_id';
    }

    /**
     * @return string
     */
    public function hasManyQualityRequestForeignKey(): string
    {
        return 'req_id';
    }

    /**
     * @return Collection
     */
    public function getHistoryWithNotes($role = null): Collection
    {
        $history = $this->requestHistories()->with(['user', 'receiver'])->latest('history_date')->get();
        $historyRows = collect();
        $isAdmin = $role == 7;

        foreach ($history as $item) {
            $title = trans_has(($k = "request_history.{$item->title}")) ? __($k) : $item->title;
            $text = trim("{$title} - {$item->content}", ' -');
            $value = "";
            /** @var User $user */
            $user = $item->user;
            if ($user->name) {
                $name = $user->name;
                $isAdmin && $user->name_for_admin && ($name .= " - {$user->name_for_admin}");
                $value .= __("replace.from", ['from' => $name]);
            }

            /** @var User $receiver */
            $receiver = $item->receiver;
            if ($receiver->name) {
                $name = $receiver->name;
                $isAdmin && $receiver->name_for_admin && ($name .= " - {$receiver->name_for_admin}");
                $value .= ($value ? " - " : "").__("replace.to", ['to' => $name]);
            }

            if ($item->userSwitch->name) {
                $value .= ($value ? " / " : "").$item->userSwitch->name;
            }

            $historyRows->push(collect([
                "text"     => $text,
                "value"    => $value,
                'date'     => $item->history_date ? date_by_locale(Carbon::make($item->history_date)->format(config('config.date_format.full_12'))) : null,
                'raw_date' => $item->history_date ? Carbon::make($item->history_date) : null,
            ]));
        }
        $records = $this->requestRecords()->where('colum', 'comment')->with(['user'])->whereHas('user', fn(Builder $builder) => $builder->where('role', 0))->latest('updateValue_at')->get();
        foreach ($records as $record) {
            $text = __("attributes.agent_note")." {$record->user->name}";
            $value = $record->comment;
            $value .= ($value ? " - " : "").$record->value;

            $historyRows->push(collect([
                "text"     => $text,
                "value"    => $value,
                'date'     => $record->updateValue_at ? date_by_locale(Carbon::make($record->updateValue_at)->format(config('config.date_format.full_12'))) : null,
                'raw_date' => $record->updateValue_at ? Carbon::make($record->updateValue_at) : null,
            ]));
        }
        return $historyRows->sortBy('raw_date')->values();
    }

    public function checkBankAccountExists()
    {
        if(auth()->user()->role == 2 && \DB::table('wasata_requestes')->where('req_id', $this->id)->where('funding_user_id', auth()->id())->count() > 0)
        {
            return true;
        }elseif (auth()->user()->role != 2 && $this->collaborator()->where('role', '13')->exists() || \DB::table('wasata_requestes')->where('req_id', $this->id)->count() > 0) {
            return true;
        }
        return false;
    }
    public function getBankAccountIds()
    {
        if(auth()->user()->role == 2)
        {
            $ids = \DB::table('wasata_requestes')->where('req_id', $this->id)->where('funding_user_id', auth()->id())->pluck('user_id')->toArray();

        }else{
            $ids = \DB::table('wasata_requestes')->where('req_id', $this->id)->pluck('user_id')->toArray();
            if ($this->collaborator()->where('role', '13')->exists() ) {
                $ids[] = $this->collaborator_id;
            }

        }
        return $ids;
    }
}
