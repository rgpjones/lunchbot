<?php
namespace RgpJones\Rotabot\Command;

use RgpJones\Rotabot\Command;
use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Slack\Slack;

class Rota implements Command
{
    const MAX_DAYS = 20;

    /**
     * @var RotaManager
     */
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
        return '`rota`: Show the upcoming rota';
    }

    public function run(array $args, $username)
    {
        $days = count($this->rotaManager->getMembers());

        $rota = $this->rotaManager->generateRota(new \DateTime(), $days);
        $response = '';
        foreach ($rota as $date => $clubber) {
            $date = new \DateTime($date);
            $response .= "{$date->format('l')}: {$clubber}\n";
        }

        $this->slack->send($response);
    }
}
