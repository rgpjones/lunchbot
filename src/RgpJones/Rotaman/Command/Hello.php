<?php

namespace RgpJones\Rotaman\Command;

use RgpJones\Rotaman\Command;
use RgpJones\Rotaman\Slack;

class Hello implements Command
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
        $this->slack->send("Hello!");
    }
}
