<?php
namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Slack\Slack;

class Join implements Operation
{
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

        $this->slack->send("{$username} has joined the rota");
    }
}
