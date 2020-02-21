<?php
namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Messenger\Messenger;

class Rota implements Operation
{
    const MIN_DAYS = 5;

    const MAX_DAYS = 20;

    protected $rotaManager;

    private $messenger;

    public function __construct(RotaManager $rotaManager, Messenger $messenger)
    {
        $this->rotaManager = $rotaManager;
        $this->messenger = $messenger;
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

        $this->messenger->send($response);
    }
}
