<?php

namespace RgpJones\Rotabot\Notifier;

use Symfony\Component\HttpFoundation\Request;

class SlackConfiguration
{
    private $token;

    private $webhook;

    private $user;

    private $channel;

    public function __construct(SlackCredentials $slackCredentials)
    {
        $this->token = $slackCredentials->getSlackToken();
        $this->webhook = $slackCredentials->getSlackWebhookUrl();
    }

    public function setRequest(Request $request)
    {
        if ($request->get('token') != $this->getToken()) {
            throw new SlackTokenMismatchException(
                'The request did not provide the correct Slack authorisation token'
            );
        }

        $this->user = $request->get('user_name');
        $this->channel = $request->get('channel_name');
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getWebhook(): string
    {
        return $this->webhook;
    }

    public function getUser(): string
    {
        return $this->user;
    }

    public function getChannel(): string
    {
        return $this->channel;
    }
}
