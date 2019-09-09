<?php
namespace RgpJones\Rotabot\Command;

use RgpJones\Rotabot\Command;
use RgpJones\Rotabot\RotaManager;
use DateTime;
use RgpJones\Rotabot\Slack;

class Paid implements Command
{
    /**
     * @var RotaManager
     */
    protected $rotaManager;

    public function __construct(RotaManager $rotaManager)
    {
        $this->rotaManager = $rotaManager;
    }

    public function getUsage()
    {
        return '`paid` <amount> [date]: Mark yourself as having paid <amount> for the current month. Specify [date] of '
            . 'month if not for current month. e.g. 2014-12-01 for December';
    }

    public function run(array $args, $username)
    {
        $amount = null;
        if (isset($args[0])) {
            $amount = $this->checkAmount($args[0]);
        }

        $date = (isset($args[1]) ? new DateTime($args[1]) : new DateTime());

        if ($amount) {
            if ($this->rotaManager->memberPaidForDate($date, $username, $amount)) {
                $response = sprintf('Your payment of £%.02f has been recorded', $amount);
            } else {
                $response = "Failed to record as paid without an error";
            }
        } else {
            $response = sprintf(
                'You have paid £%.02f this month',
                $this->rotaManager->getAmountMemberPaidForDate(new DateTime(), $username)
            );
        }

        return $response;
    }

    protected function checkAmount($amount)
    {
        $amount = str_replace('£', '', $amount);
        if (!is_numeric($amount) || $amount <= 0) {
            throw new \InvalidArgumentException('You must provide a valid amount for payment');
        }
        return $amount;
    }
}
