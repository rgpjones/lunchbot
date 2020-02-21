<?php

namespace RgpJones\Rotabot\Messenger;

class Memory implements Messenger
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
