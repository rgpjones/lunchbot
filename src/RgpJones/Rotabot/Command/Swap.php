<?php
namespace RgpJones\Rotabot\Command;

use RgpJones\Rotabot\Command;
use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Slack\Slack;
use DateTime;

class Swap implements Command
{
    protected $rotaManager;
    protected $slack;

    public function __construct(RotaManager $rotaManager, Slack $slack)
    {
        $this->rotaManager = $rotaManager;
        $this->slack = $slack;
    }

    public function getUsage()
    {
        return '`swap` [member1] [member2]: Swap shopping duty between member1 and member2. Without member2 specified, '
            . 'member1 is swapped with current member. With no members specified today and next day are swapped';
    }

    public function run(array $args, $username)
    {
        $toUser = isset($args[0])
            ? $args[0]
            : null;

        $fromUser = isset($args[1])
            ? $args[1]
            : null;

        $this->rotaManager->swapMember(new DateTime(), $toUser, $fromUser);

        $this->slack->send('Members swapped');
    }
}
