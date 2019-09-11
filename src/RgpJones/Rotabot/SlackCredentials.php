<?php


namespace RgpJones\Rotabot;

class SlackCredentials
{
    private $slackWebhookUrl;
    private $slackToken;

    public function __construct($slackWebhookUrl, $slackToken)
    {
        $this->slackWebhookUrl = $slackWebhookUrl;
        $this->slackToken = $slackToken;
    }

    /**
     * @return mixed
     */
    public function getSlackWebhookUrl()
    {
        return $this->slackWebhookUrl;
    }

    /**
     * @return mixed
     */
    public function getSlackToken()
    {
        return $this->slackToken;
    }
}
