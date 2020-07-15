<?php

namespace RgpJones\Rotabot\Notifier;

class Memory implements Notifier
{
    private $messages = [];

    public function send(string $text)
    {
        $this->messages[] = $text;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }
}
