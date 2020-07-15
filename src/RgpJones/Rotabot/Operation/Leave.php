<?php
namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\Notifier\Notifier;
use RgpJones\Rotabot\RotaManager;

class Leave implements Operation
{
    protected $rotaManager;
    protected $messenger;

    public function __construct(RotaManager $rotaManager, Notifier $messenger)
    {
        $this->rotaManager = $rotaManager;
        $this->messenger = $messenger;
    }

    public function getUsage()
    {
        return '`leave`: Leave rota';
    }

    public function run(array $args, $username)
    {
        if (!isset($username)) {
            throw new \RunTimeException('No username found to leave');
        }
        $this->rotaManager->removeMember($username);

        $this->messenger->send("{$username} has left the rota");
    }
}
