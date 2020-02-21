<?php

namespace RgpJones\Rotabot\Operation;

class Ping implements Operation
{
    public function getUsage()
    {
        return '`ping`: Return a pong response';
    }

    public function run(array $args, $username)
    {
        return 'pong' . PHP_EOL;
    }
}
