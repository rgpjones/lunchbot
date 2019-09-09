<?php
namespace RgpJones\Rotabot;

use DateTime;
use DateInterval;

class DateValidator
{
    private $cancelledDates;

    private $interval;

    public function __construct(array $cancelledDates = [])
    {
        $this->cancelledDates = $cancelledDates;
        $this->interval = new DateInterval('P1D');
    }

    public function addCancelledDate(DateTime $date)
    {
        if ($this->isDateValid($date)) {
            $this->cancelledDates[] = $date->format('Y-m-d');

            return true;
        }

        return false;
    }

    public function getCancelledDates()
    {
        return $this->cancelledDates;
    }

    public function isDateValid(DateTime $date)
    {
        return !in_array($date->format('l'), array('Saturday', 'Sunday'))
            && !in_array($date->format('Y-m-d'), $this->cancelledDates);
    }

    public function getNextValidDate(DateTime $date)
    {
        while (!$this->isDateValid($date)) {
            $date->add($this->interval);
        }

        return $date;
    }

    public function getPreviousValidDate(DateTime $date)
    {
        while (!$this->isDateValid($date)) {
            $date->sub($this->interval);
        }

        return $date;
    }
}
