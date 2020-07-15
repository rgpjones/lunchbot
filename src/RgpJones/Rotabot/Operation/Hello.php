<?php

namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\Notifier\Notifier;

class Hello implements Operation
{
    private $messenger;

    public function __construct(Notifier $messenger)
    {
        $this->messenger = $messenger;
    }

    public function getUsage()
    {
        return '`hello`: Say hello!';
    }

    public function run(array $args, $username)
    {
        $this->messenger->send('Hello!');
        return '';
    }
}
