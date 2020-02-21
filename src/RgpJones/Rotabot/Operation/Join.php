<?php
namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Messenger\Messenger;

class Join implements Operation
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    /**
     * @var Messenger
     */
    private $messenger;

    public function __construct(RotaManager $rotaManager, Messenger $messenger)
    {
        $this->rotaManager = $rotaManager;
        $this->messenger = $messenger;
    }

    public function getUsage()
    {
        return '`join`: Join rota';
    }

    public function run(array $args, $username)
    {
        $username = isset($args[0])
            ? $args[0]
            : $username;

        if (!isset($username)) {
            throw new \RunTimeException('No username found to join');
        }
        $this->rotaManager->addMember($username);

        $this->messenger->send("{$username} has joined the rota");
    }
}
