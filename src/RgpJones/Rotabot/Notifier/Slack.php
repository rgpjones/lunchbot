<?php
namespace RgpJones\Rotabot\Notifier;

class Slack implements Notifier
{
    private $slackConfiguration;

    public function __construct(SlackConfiguration $slackConfiguration)
    {
        $this->slackConfiguration = $slackConfiguration;
    }

    public function send(string $text)
    {
        $content['username'] = 'Rotabot';
        $content['text'] = $text;
        $content['icon_emoji'] = ':calendar:';
        $content['channel'] = (string) $this->slackConfiguration->getChannel();

        $payload = sprintf("payload=%s", json_encode($content));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, $this->slackConfiguration->getWebhook());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_exec($ch);

        curl_close($ch);
    }
}
