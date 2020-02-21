<?php

namespace RgpJones\Rotabot\Messenger;

class SlackCredentials
{
    private $slackWebhookUrl;
    private $slackToken;

    public function __construct($slackWebhookUrl, $slackToken)
    {
        $this->slackWebhookUrl = $slackWebhookUrl;
        $this->slackToken = $slackToken;
    }

    public function getSlackWebhookUrl()
    {
        return $this->slackWebhookUrl;
    }

    public function getSlackToken()
    {
        return $this->slackToken;
    }
}
