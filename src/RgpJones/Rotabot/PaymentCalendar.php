<?php
namespace RgpJones\Rotabot;

use DateTime;

class PaymentCalendar
{
    /**
     * @var array
     */
    private $paymentCalendar;

    public function __construct(array $currentPaymentCalendar = [])
    {

        $this->paymentCalendar = $currentPaymentCalendar;
    }

    public function getPaymentCalendar()
    {
        return $this->paymentCalendar;
    }

    public function memberPaidForDate(DateTime $date, $member, $amount)
    {
        if (isset($this->paymentCalendar[$this->getDateKey($date)][$member])) {
            $amount += $this->paymentCalendar[$this->getDateKey($date)][$member];
        }
        $this->paymentCalendar[$this->getDateKey($date)][$member] = $amount;
        return true;
    }

    public function getAmountMemberPaidForDate(DateTime $date, $member)
    {
        $amount = 0;
        if (isset($this->paymentCalendar[$this->getDateKey($date)][$member])) {
            $amount = $this->paymentCalendar[$this->getDateKey($date)][$member];
        }
        return $amount;
    }

    public function getWhoPaidForDate(DateTime $date)
    {
        return isset($this->paymentCalendar[$this->getDateKey($date)])
            ? array_keys($this->paymentCalendar[$this->getDateKey($date)])
            : [];
    }

    protected function getDateKey(DateTime $date)
    {
        return $date->format('Y-m');
    }
}
