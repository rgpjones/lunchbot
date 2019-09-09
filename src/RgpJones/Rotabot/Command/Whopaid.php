<?php
namespace RgpJones\Rotabot\Command;

use DateTime;
use RgpJones\Rotabot\Command;
use RgpJones\Rotabot\RotaManager;
use RgpJones\Rotabot\Slack;

class Whopaid implements Command
{
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
        return '`whopaid`: Report who has paid money this month. This only reports that a person has paid some amount '
            . 'of money.';
    }

    public function run(array $args, $username)
    {
        $date = isset($args[0])
            ? new DateTime($args[0])
            : new DateTime();

        $paidMembers = $this->rotaManager->getWhoPaidForDate($date);
        $this->slack->send(
            "Payments for " . $date->format('F') . ":\n"
                . $this->formatWhoPaid($paidMembers)
        );
    }

    protected function formatWhoPaid($paidMembers)
    {
        $members = $this->rotaManager->getMembers();
        $unpaidMembers = array_diff($members, $paidMembers);

        $message = '';
        if (count($paidMembers) == 0) {
            $message .= "No members have paid so far.\n";
        } else {
            $message .= sprintf("Members that have paid: %s\n", implode(', ', $paidMembers));
        }

        if (count($unpaidMembers) == 0) {
            $message .= "All members have paid.\n";
        } else {
            $message .= sprintf("Members that haven't paid: %s\n", implode(', ', $unpaidMembers));
        }

        return $message;
    }
}
