<?php

namespace App\Notifications\Base;

class BaseSmsMessage
{
    /** @var string */
    protected $content;

    /** @var string|string[] */
    protected $mobile;

    /**
     * BaseSlackMessage constructor.
     */
    public function __construct()
    {
    }

    /**
     * Set the content of the message.
     *
     * @param string $content
     *
     * @return $this
     */
    public function content($content): self
    {
        $this->content = $content;
        return $this;
    }

    /**
     * Set the receiver of the message.
     *
     * @param string|string[] $mobile
     *
     * @return $this
     */
    public function to($mobile): self
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return string|string[]
     */
    public function getMobile()
    {
        return $this->mobile;
    }
}
