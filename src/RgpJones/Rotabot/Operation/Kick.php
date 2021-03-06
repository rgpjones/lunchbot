<?php
namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\Notifier\Notifier;
use RgpJones\Rotabot\RotaManager;

class Kick implements Operation
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
        return '`kick` <person>: Remove person from rota';
    }

    public function run(array $args, $username)
    {
        if (!isset($args[0])) {
            throw new \RunTimeException('No username found to leave');
        }
        $user = $args[0];

        $this->rotaManager->removeMember($user);

        $this->messenger->send("{$username} removed {$user} from the rota");
    }
}
