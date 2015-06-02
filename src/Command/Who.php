<?php
namespace RgpJones\Lunchbot\Command;

use DateTime;
use RgpJones\Lunchbot\Command;
use RgpJones\Lunchbot\RotaManager;
use RgpJones\Lunchbot\Slack;

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
        return '`who`: Whose turn it is to shop';
    }

    public function run(array $args, $username)
    {
        $member = $this->rotaManager->getMemberForDate(new DateTime());
        $this->slack->send(sprintf('Today it is %s\'s turn to shop', $member));
    }
}
