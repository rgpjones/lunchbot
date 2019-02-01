<?php
namespace RgpJones\Rotaman;

use SimpleXMLElement;

class Slack
{
    /**
     * @var array
     */
    private $config;

    private $debug;
    private $messages;

    public function __construct(SimpleXMLElement $config, $debug = false)
    {
        $this->config = $config;
        $this->debug = $debug;
    }

    public function send($message)
    {
        $content['username'] = 'Rotaman';
        $content['text'] = $message;
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

        $this->messages[] = $message;
        curl_close($ch);
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }
}
