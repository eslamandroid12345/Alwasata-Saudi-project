<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\SlackMessage;
use Illuminate\Notifications\Notification;

class SlackNotification extends Notification
{
    use Queueable;

    /**
     * @var string
     */
    protected string $message = '';

    /**
     * @var string
     */
    protected string $username = '';

    /**
     * @var string
     */
    protected string $toChannel = '';

    /**
     * @var string|null
     */
    protected ?string $recipient = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($message, ?string $recipient = null, $toChannel = null)
    {
        $this->message = $message;
        $this->recipient = $recipient;
        $this->username = config('config.slack.username');
        $this->toChannel = $toChannel ?? config('config.slack.notifications_channel');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['slack'];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        return (new SlackMessage())
            ->from($this->getUsername(), ':ghost:')
            ->to($this->toChannel)
            ->content($this->getMessage());
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param  string  $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        $message = $this->message;
        if ($this->getRecipient()) {
            $message = $this->getRecipient().PHP_EOL.$message;
        }
        return $message;
    }

    /**
     * @param  string  $message
     */
    public function setMessage(string $message): void
    {
        $this->message = $message;
    }

    /**
     * @return string|null
     */
    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    /**
     * @param  string|null  $recipient
     */
    public function setRecipient(?string $recipient): void
    {
        $this->recipient = $recipient;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
