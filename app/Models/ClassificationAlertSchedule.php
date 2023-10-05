<?php
/**
 * @Author: Ahmed Fayez
 **/

namespace App\Models;

use App\Alpha\BaseModel;
use App\Helpers\MyHelpers;
use App\Notifications\ClassificationAlertNotification;
use App\Traits\BelongsTo\BelongsToClassification;
use App\Traits\BelongsTo\BelongsToRequest;
use App\Traits\General;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class ClassificationAlertSchedule extends BaseModel
{
    use BelongsToRequest;
    use BelongsToClassification;
    use General;

    /** @var int The request row not found */
    const NO_REQUEST = 200;
    /** @var int The alert was sent */
    const ALERT_SENT = 100;
    /** @var int The alert has next setting but not contain the current time */
    const ALERT_NOT_PAST = 103;
    /** @var int The customer have no channel to send notification */
    const CUSTOMER_NO_CHANNEL = 104;
    /** @var int The alert has no next setting */
    const NO_NEXT_ALERT_SETTING = 101;
    /** @var int The classification of request was changed */
    const CLASSIFICATION_CHANGED = 102;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'request_id',
        'classification_id',
        'send_time',
        'step',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'send_time' => 'datetime',
    ];

    /**
     * Send schedule
     * @return int
     */
    public function sendAlert(): int
    {
        /** @var Request $request */
        if (!($request = $this->request) || !$request->exists) {

            return self::NO_REQUEST;
        }

        if ($this->classification_id != $request->class_id_agent) {
            if ($this->classification_id == Classification::AGENT_UNABLE_TO_COMMUNICATE) {
                $request->customerBackAfterUnableToCommunicate(RequestJob::SOURCE_CHANGE_CLASS);
                try {
                    $this->delete();
                }
                catch (\Exception $exception) {
                }
            }
            return self::CLASSIFICATION_CHANGED;
        }
        if (!($nextAlertSetting = $this->getNextAlertSetting())) {
            $this->makeActionNoNextSetting();
            return self::NO_NEXT_ALERT_SETTING;
        }
        $minutes = $nextAlertSetting->hours_to_send;
        if (app()->environment('production')) {
            $minutes *= 60;
        }
        $sendTime = $this->send_time->copy()->addMinutes($minutes);
        if (!$sendTime->isPast()) {
            return static::ALERT_NOT_PAST;
        }

        $this->fill([
            'step'      => ++$this->step,
            'send_time' => now(),
        ]);
        $this->save();
        $type = $nextAlertSetting->type;

        if ($type == ClassificationAlertSetting::TYPES['move_to_freeze']) {
            sendSlackNotification(__("messages.moveToFreezeScheduleAction", ['req' => $request->id]), null, config('config.slack.classification_channel'));
            $this->moveToFreezeScheduleAction();
            return static::ALERT_SENT;
        }
        $customer = $this->request->customer;
        $channel = null;
        if ($type == ClassificationAlertSetting::TYPES['email']) {
            $channel = filter_var($customer->email, FILTER_VALIDATE_EMAIL) ? $customer->email : null;
        }
        if ($type == ClassificationAlertSetting::TYPES['sms']) {
            $channel = $customer->mobile;
        }
        if ($type == ClassificationAlertSetting::TYPES['push_token']) {
            $channel = $customer->getPushTokens();
        }
        if ($channel) {
            try {
                $notify = new ClassificationAlertNotification($this, $channel, $type);
                $customer->notify($notify);
            }
            catch (Exception $exception) {
                //if (config('app.debug')) {
                //zz($exception);
                //}
            }
            //dd($type);
            //return static::CUSTOMER_NO_CHANNEL;
        }

        try {
            if (!$this->getNextAlertSetting()) {
                $this->makeActionNoNextSetting();
            }
        }
        catch (Exception $exception) {
        }
        return static::ALERT_SENT;
    }

    public function sendAlertForPostponed() : int
    {
        /** @var Request $request */
        if (!($request = $this->request) || !$request->exists) {
            return self::NO_REQUEST;
        }
        if ($this->classification_id != $request->class_id_agent) {
                try {
                    $this->delete();
                }
                catch (\Exception $exception) {
                }
            }
        if (!($nextAlertSetting = $this->getNextAlertSetting())) {
            $this->makeActionNoNextSetting();
            return self::NO_NEXT_ALERT_SETTING;
        }
        $minutes = $nextAlertSetting->hours_to_send;
        // $minutes *= 60;
        $minutes *= 1;
        $sendTime = $this->send_time->copy()->addMinutes($minutes);
        if (!$sendTime->isPast()) {
            return static::ALERT_NOT_PAST;
        }
        $this->fill([
            'step'      => ++$this->step,
            'send_time' => now(),
        ]);
        $this->save();
        $type = $nextAlertSetting->type;
        $customer = $this->request->customer;
        $channel = null;
        $customerData = Customer::where('id',$this->request->customer_id)->first();
        $msg = "عزيزي:   " . $customerData->name;
        $msg .= "    لقد طلبت تأجيل التواصل  لمتابعة طلب التمويل العقاري الخاص بك";
        $msg .= "   يمكنك تحديد الوقت المناسب من خلال الرابط التالي ";
        $msg .= url('/') . "/customer/set-date/" . $this->request->id;
        $msg .= "   كما يمكنك التواصل مباشرة مع مستشارك ومتابعة حالة طلبك أولًا بأول عبر الرابط التالي   ";
        $msg .= " https://alwsata.com.sa/ar/app  ";
        $data = [
            'name'         => $customerData->name,
            'email'        => $customerData->email,
            'request_id'   => $this->request->id
        ];

        /*$notification = \Notification::create([
            'value' => $msg,
            'recived_id' =>  $customerData->id,
            'receiver_type' => 'web',
            'created_at' => (Carbon::now('Asia/Riyadh')),
            'type' => 21,
            'reminder_date' => null,
            'req_id' =>  $this->request->id,
        ]);
        $this->line("Notification Created {}");*/
        if ($type == ClassificationAlertSetting::TYPES['email'] && $customerData->email && filter_var( $customerData->email, FILTER_VALIDATE_EMAIL ) != false) {
            try {
                Mail::send('emails.postponed_communication_email',$data,function ($message) use($customerData){
                    $message->to($customerData->email,$customerData->name)
                        ->subject('تحديد موعد للتواصل');
                });

            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        if ($type == ClassificationAlertSetting::TYPES['sms']) {

           MyHelpers::sendSMS($customerData->mobile,$msg);
        }
        if ($type == ClassificationAlertSetting::TYPES['push_token']) {
            $msg = "عزيزي: " . $customerData->name;
            $msg .= "  لقد طلبت تأجيل التواصل  لمتابعة طلب التمويل العقاري الخاص بك";
            $msg .= "   يمكنك تحديد الوقت المناسب من خلال الضغط على الاشعار!";
            $this->fcm_send($customerData->getPushTokens(), 'أجل التواصل', $msg);
        }
        if ($type == ClassificationAlertSetting::TYPES['move_to_freeze']) {
            sendSlackNotification(__("messages.moveToFreezeScheduleAction", ['req' => $request->id]), null, config('config.slack.classification_channel'));
            $this->moveToFreezeScheduleAction();
            return static::ALERT_SENT;
        }
        if ($channel) {
            try {
                $notify = new ClassificationAlertNotification($this, $channel, $type);
                $customer->notify($notify);
            }
            catch (Exception $exception) {
            }
        }
        try {
            if (!$this->getNextAlertSetting()) {
                $this->makeActionNoNextSetting();
            }
        }
        catch (Exception $exception) {
        }
        return static::ALERT_SENT;
    }

    /**
     * @return ClassificationAlertSchedule|Model|null
     */
    public function getNextAlertSetting(): ?ClassificationAlertSetting
    {
        return ClassificationAlertSetting::query()->where([
            'classification_id' => $this->classification_id,
            'step'              => $this->step + 1,
        ])->first();
    }

    /**
     * Make Action of schedule if there is no next alert setting
     */
    public function makeActionNoNextSetting(): void
    {
        try {
            $this->delete();
        }
        catch (Exception$exception) {

        }
    }

    public function moveToFreezeScheduleAction()
    {
        /** @var Request $request */
        if (($request = $this->request) && $request->exists) {
            if ($request->class_id_agent == Classification::AGENT_UNABLE_TO_COMMUNICATE) {
                if (($model = $request->classificationQuestionnaires()->where([
                    'classification_id' => $request->class_id_agent,
                    'user_id'           => $request->user_id,
                    'title'             => null,
                    'value'             => null,
                ])->first())) {
                    // # Change value to false
                    $model->update(['value' => !1]);
                }
                $request->moveToFreeze(RequestHistory::CONTENT_FROZEN_UNABLE_TO_COMMUNICATE_AUTO);
            }

            if ($request->class_id_agent == Classification::POSTPONED_COMMUNICATION) {
            // if ($request->class_id_agent == Classification::TEST_CLASSIFICATION) {
                if (($model = $request->classificationQuestionnaires()->where([
                    'classification_id' => $request->class_id_agent,
                    'user_id'           => $request->user_id,
                    'title'             => null,
                    'value'             => null,
                ])->first())) {
                    // # Change value to false
                    $model->update(['value' => !1]);
                }
                $request->moveToFreeze(RequestHistory::CONTENT_FROZEN_POSTPONED_COMMUNICATE_AUTO);
            }
        }
        try{
            $this->delete();
        }
        catch (\Exception $exception){}
    }

    /**
     * @return ClassificationAlertSetting|null
     */
    public function getCurrentAlertSetting(): ?ClassificationAlertSetting
    {
        return ClassificationAlertSetting::query()->where([
            'classification_id' => $this->classification_id,
            'step'              => $this->step,
        ])->first();
    }

    /**
     * @return array|null
     */
    public function getNotificationData(): ?array
    {
        if (($data = __("mail.classification.{$this->classification_id}")) && is_array($data)) {
            return $data;
        }
        return null;
    }
}
