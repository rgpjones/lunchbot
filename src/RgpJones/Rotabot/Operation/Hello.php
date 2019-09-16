<?php

namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\Slack\Slack;

class Hello implements Operation
{
    /**
     * @var Slack
     */
    private $slack;

    public function __construct(Slack $slack)
    {
        $this->slack = $slack;
    }

    public function getUsage()
    {
        return '`hello`: Say hello!';
    }

    public function run(array $args, $username)
    {
        $this->slack->send('Hello!');
        return '';
    }
}
