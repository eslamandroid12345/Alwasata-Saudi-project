<?php

namespace App\Notifications;

use App\Models\ClassificationAlertSchedule as Model;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class ClassificationAlertNotification extends Notification
{
    use Queueable;

    /**
     * @var array
     */
    protected $via = [];

    /**
     * @var string|string[]|null
     */
    protected $to = null;

    /**
     * @var Model
     */
    protected $classificationAlertSchedule;

    /** @var string Slack classification channel */
    private string $slackChannel;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Model $classificationAlertSchedule, $to, $via = [])
    {
        $this->to = $to;
        $this->slackChannel = config('config.slack.classification_channel');
        $this->classificationAlertSchedule = $classificationAlertSchedule;
        !is_array($via) && ($via = explode(',', $via));
        if (!($data = $classificationAlertSchedule->getNotificationData()) || !is_array($data)) {
            $via = [];
        }
        foreach ($via as $k => $v) {
            if (!array_key_exists($v, $data)) {
                unset($via[$k]);
            }
        }
        $via = array_unique($via);
        $this->via = array_values($via);
        // Todo: Remove this for testing
        //$this->via = ['slack'];
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        $production = app()->environment('production');
        if (!$production) {
            $this->via = ['slack'];
        }

        if (!in_array('slack', $this->via)) {
            $this->via[] = 'slack';
        }

        if (!$production && in_array('mail', $this->via)) {
            $this->via = collect($this->via)->filter(fn($v) => $v != 'mail')->values()->toArray();
        }
        // Todo: Remove this for testing
        //return ['slack'];
        return $this->via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $mail = (new MailMessage)->subject($this->getNotificationSubject('mail'));
        $content = $this->getNotificationContent('mail');
        foreach (explode(PHP_EOL, $content) as $line) {
            $mail->line($line);
        }
        return $mail;
    }

    /**
     * @param  string  $type
     * @return string
     */
    public function getNotificationSubject(string $type): string
    {
        return $this->getNotificationLang($type, 'subject');
    }

    /**
     * @param  string  $type
     * @param  string  $langKey
     * @return string
     */
    public function getNotificationLang(string $type, string $langKey): string
    {
        $k = "mail.classification.{$this->classificationId()}.{$type}";
        $replace = $this->replace([
            'name'   => $this->customerName(),
            'email'  => $this->customerEmail(),
            'mobile' => $this->customerMobile(),
        ]);
        return (string) __("{$k}.{$langKey}", $replace);
    }

    /**
     * @return int
     */
    public function classificationId(): int
    {
        return (int) ($this->classificationAlertSchedule->classification_id ?: 0);
    }

    /**
     * @param  array  $merge
     * @return array
     */
    public function replace(array $merge = []): array
    {
        return array_merge([
            'appStoreUrl'        => 'https://apps.apple.com/sa/app/%D8%A7%D9%84%D9%88%D8%B3%D8%A7%D8%B7%D8%A9-%D8%A7%D9%84%D8%B9%D9%82%D8%A7%D8%B1%D9%8A%D8%A9/id1588240476#?platform=iphone',
            'appStoreUrlShort'   => 'https://apps.apple.com/sa/app/%D8%A7%D9%84%D9%88%D8%B3%D8%A7%D8%B7%D8%A9-%D8%A7%D9%84%D8%B9%D9%82%D8%A7%D8%B1%D9%8A%D8%A9/id1588240476#?platform=iphone',
            'googlePlayUrl'      => 'https://play.google.com/store/apps/details?id=com.wasata.wasata_user&hl=en&gl=US',
            'googlePlayUrlShort' => 'https://play.google.com/store/apps/details?id=com.wasata.wasata_user&hl=en&gl=US',
            'appDownload'        => 'https://alwsata.com.sa/ar/app',
        ], $merge);
    }

    /**
     * @return string
     */
    public function customerName(): string
    {
        $name = $this->classificationAlertSchedule->request->customer->name ?: '';
        try {
            $e = explode(' ', trim($name));
            $name = ($e[0] ?? '').' '.($e[1] ?? '');
            $name = trim($name);
        }
        catch (\Exception $exception) {
            $name = $this->classificationAlertSchedule->request->customer->name ?: '';
        }
        return (string) $name;
    }

    /**
     * @return string
     */
    public function customerEmail(): string
    {
        return (string) ($this->classificationAlertSchedule->request->customer->email ?: '');
    }

    /**
     * @return string
     */
    public function customerMobile(): string
    {
        return (string) ($this->classificationAlertSchedule->request->customer->mobile ?: '');
    }

    /**
     * @param  string  $type
     * @return string
     */
    public function getNotificationContent(string $type): string
    {
        return $this->getNotificationLang($type, 'content');
    }

    /**
     * Get the sms representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toSms($notifiable)
    {
        // Todo: send to sms. A.Fayez
        return $this->getNotificationContent('sms');
    }

    /**
     * Get the app representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toPushToken($notifiable): array
    {
        return [
            'title'  => $this->getNotificationSubject('push_token'),
            'body'   => $this->getNotificationContent('push_token'),
            'tokens' => $this->to,
        ];
    }

    /**
     * @param $notifiable
     * @return SlackMessage
     */
    public function toSlack($notifiable)
    {
        $s = $this->classificationAlertSchedule->getCurrentAlertSetting();
        $type = $s ? $s->type : 'mail';
        if (!$s || !$s->type) {
            $content = "No Type Of Settings [ClassificationAlertSchedule ID: {$this->classificationAlertSchedule->id}]";
        }
        else {
            $content = $this->getNotificationContent($type).PHP_EOL."[Step: {$this->classificationAlertSchedule->step} => {$type}]".PHP_EOL."[Req: {$this->classificationAlertSchedule->request_id}]";
        }
        return (new SlackMessage())->content($content);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [//
        ];
    }

    /**
     * @return string
     */
    public function getSlackChannel(): string
    {
        return $this->slackChannel;
    }

    /**
     * @return string|string[]|null
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * @param  string|string[]|null  $to
     */
    public function setTo($to): void
    {
        $this->to = $to;
    }
}
