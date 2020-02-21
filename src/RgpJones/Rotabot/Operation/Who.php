<?php
namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Messenger\Messenger;

class Who implements Operation
{
    protected $rotaManager;

    private $messenger;

    public function __construct(RotaManager $rotaManager, Messenger $messenger)
    {
        $this->rotaManager = $rotaManager;
        $this->messenger = $messenger;
    }

    public function getUsage()
    {
        return '`who`: Whose turn it is today';
    }

    public function run(array $args, $username)
    {
        $this->messenger->send(
            $this->getResponseMessage($this->rotaManager->getMemberForToday())
        );
    }

    protected function getResponseMessage($member)
    {
        return !is_null($member)
            ? sprintf('It is <@%s>\'s turn today', $member)
            : "There is no rota scheduled for today.";
    }
}
