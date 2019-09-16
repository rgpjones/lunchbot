<?php
namespace RgpJones\Rotabot\Operation;

use DateTime;
use RgpJones\Rotabot\RotaManager;

class Skip implements Operation
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    /**
     * @var Who
     */
    private $whoOperation;

    public function __construct(RotaManager $rotaManager, Who $whoOperation)
    {
        $this->rotaManager = $rotaManager;
        $this->whoOperation = $whoOperation;
    }

    public function getUsage()
    {
        return '`skip`: Skip current member, and pull remaining rota forwards';
    }

    public function run(array $args, $username)
    {
        $this->rotaManager->skipMemberForDate(new DateTime());
        $this->whoOperation->run([], $username);
    }
}
