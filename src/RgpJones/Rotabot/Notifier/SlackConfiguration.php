<?php

namespace RgpJones\Rotabot\Notifier;

use Symfony\Component\HttpFoundation\RequestStack;

class SlackConfiguration
{
    private $token;

    private $webhook;

    private $user;

    private $channel;

    public function __construct(RequestStack $requestStack, SlackCredentials $slackCredentials)
    {
        $request = $requestStack->getCurrentRequest();

        if ($request->get('token') != $slackCredentials->getSlackToken()) {
            throw new SlackTokenMismatchException(
                'The request did not provide the correct Slack authorisation token'
            );
        }

        $this->token = $slackCredentials->getSlackToken();
        $this->webhook = $slackCredentials->getSlackWebhookUrl();
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
