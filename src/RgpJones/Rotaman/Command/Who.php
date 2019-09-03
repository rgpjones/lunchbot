<?php
namespace RgpJones\Rotaman\Command;

use DateTime;
use RgpJones\Rotaman\Command;
use RgpJones\Rotaman\RotaManager;
use RgpJones\Rotaman\Slack;

class Who implements Command
{
    protected $rotaManager;
    /**
     * @var Slack
     */
    private $slack;

    public function __construct(RotaManager $rotaManager, Slack $slack)
    {
        $this->rotaManager = $rotaManager;
        $this->slack = $slack;
    }

    public function getUsage()
    {
        return '`who`: Whose turn it is today';
    }

    public function run(array $args, $username)
    {
        $member = $this->rotaManager->getMemberForDate(new DateTime());
        $this->slack->send(sprintf('It is <@%s>\'s turn today', $member));
    }
}
