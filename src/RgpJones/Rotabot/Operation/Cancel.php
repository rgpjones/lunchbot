<?php
namespace RgpJones\Rotabot\Operation;

use RgpJones\Rotabot\RotaManager;
use DateTime;
use RgpJones\Rotabot\Messenger\Messenger;

class Cancel implements Operation
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
        return '`cancel` [date]: Cancel rota for today, or on date specified (Y-m-d)';
    }

    public function run(array $args, $username)
    {
        $date = isset($args[0])
            ? new DateTime($args[0])
            : new DateTime();

        if ($this->rotaManager->cancelOnDate($date)) {
            $message = 'Rota has been cancelled on ';
        } else {
            $message = "Couldn't cancel rota on ";
        }

        $this->messenger->send($message . $date->format('l, jS F Y'));
    }
}
