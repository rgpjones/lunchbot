<?php
namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Slack\Slack;

class Rota implements Operation
{
    const MIN_DAYS = 5;

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
        $rota = $this->rotaManager->generateRota(
            new \DateTime(),
            max(count($this->rotaManager->getMembers()), self::MIN_DAYS)
        );
        
        $response = '';
        foreach ($rota as $date => $clubber) {
            $date = new \DateTime($date);
            $response .= "{$date->format('l')}: {$clubber}\n";
        }

        $this->slack->send($response);
    }
}
