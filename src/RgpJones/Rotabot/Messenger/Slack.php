<?php
namespace RgpJones\Rotabot\Messenger;

class Slack implements Messenger
{
    /**
     * @var array
     */
    private $config;

    public function __construct(object $config)
    {
        $this->config = $config;
    }

    public function send(string $text)
    {
        $content['username'] = 'Rotabot';
        $content['text'] = $text;
        $content['icon_emoji'] = ':calendar:';
        $content['channel'] = (string) $this->config->channel;

        $payload = sprintf("payload=%s", json_encode($content));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_URL, (string) $this->config->webhook);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        curl_exec($ch);

        curl_close($ch);
    }
}
