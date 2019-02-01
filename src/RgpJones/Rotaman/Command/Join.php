<?php
namespace RgpJones\Rotaman\Command;

use RgpJones\Rotaman\Command;
use RgpJones\Rotaman\RotaManager;
use RgpJones\Rotaman\Slack;

class Join implements Command
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
        return '`join` [username]: Join rota. Add [username] to add someone else to the rota';
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
