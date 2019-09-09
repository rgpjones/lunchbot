<?php
namespace RgpJones\Rotabot;

use DateInterval;
use DateTime;
use InvalidArgumentException;
use RuntimeException;

class Rota
{
    private $memberList;

    private $interval;

    private $rota;

    private $dateValidator;

    public function __construct(MemberList $memberList, DateValidator $dateValidator, array $rota = array())
    {
        ksort($rota);
        $this->memberList = $memberList;
        $this->interval = new DateInterval('P1D');
        $this->dateValidator = $dateValidator;
        $this->rota = $rota;
    }

    public function getMemberList()
    {
        return $this->memberList;
    }

    public function getRota()
    {
        return $this->rota;
    }

    public function generate(DateTime $date, $days)
    {
        $rota = [];
        while (count($rota) < $days) {
            $dateKey = $this->getDateKey($date);

            if (isset($this->rota[$dateKey]) && $this->memberList->hasMember($this->rota[$dateKey])) {
                $rota[$dateKey] = $this->rota[$dateKey];
                $this->memberList->setCurrentMember($this->rota[$dateKey]);
            } else {
                $rota[$dateKey] = $this->memberList->nextMember();
            }
            $date = $date->add($this->interval);
        }
        $this->rota = array_merge($this->rota, $rota);

        return $rota;
    }

    public function getMemberForDate(DateTime $date)
    {
        if (isset($this->rota[$this->getDateKey($date)])) {
            return $this->rota[$this->getDateKey($date)];
        }
    }

    public function setMemberForDate(DateTime $date, $member)
    {
        $this->rota[$this->getDateKey($date)] = $member;
    }

    public function skipMemberForDate(DateTime $date)
    {
        while (isset($this->rota[$this->getDateKey($date)])) {
            $this->rota[$this->getDateKey($date)] = $this->getMemberAfterDate($date);
            $date->add($this->interval);
        }
        return true;
    }

    public function cancelOnDate(DateTime $cancelDate)
    {
        if ($this->dateValidator->isDateValid($cancelDate)) {
            $date = clone $cancelDate;
            if (isset($this->rota[$this->getDateKey($date)])) {
                $this->removeDateAndMoveMembers($date);
            }
            $this->dateValidator->addCancelledDate($cancelDate);
            return true;
        }
        return false;
    }

    public function swapMember(DateTime $date, $toName = null, $fromName = null)
    {
        $rota = $this->getRotaFromDate($date);
        if (count($rota) < 2) {
            throw new RunTimeException('There are not enough days in the rota to swap.');
        }

        $dates = array_keys($rota);
        $fromDate = is_null($fromName)
            ? $dates[0]
            : array_search($fromName, $rota);

        $toDate = is_null($toName)
            ? $dates[1]
            : array_search($toName, $rota);

        $rota = $this->swapMemberByDate(new DateTime($toDate), new DateTime($fromDate));
        $this->memberList->setMembers(array_unique(array_values($rota)));
        return $rota;
    }

    public function getRotaFromDate(DateTime $date)
    {
        $dates = array_keys($this->rota);
        return array_intersect_key(
            $this->rota,
            array_flip(
                array_slice($dates, array_search($date->format('Y-m-d'), $dates))
            )
        );
    }

    public function getRotaUptoDate(DateTime $date)
    {
        $dates = array_keys($this->rota);
        return array_intersect_key(
            $this->rota,
            array_flip(
                array_slice($dates, 0, array_search($date->format('Y-m-d'), $dates))
            )
        );
    }

    protected function removeDateAndMoveMembers($date)
    {
        $member = $this->rota[$this->getDateKey($date)];
        unset($this->rota[$this->getDateKey($date)]);
        while (isset($this->rota[$this->getDateKey($date->add($this->interval))])) {
            $nextMember = $this->rota[$this->getDateKey($date)];
            $this->rota[$this->getDateKey($date)] = $member;
            $member = $nextMember;
        }
        $this->rota[$this->getDateKey($date)] = $member;
    }

    protected function swapMemberByDate(DateTime $toDate, DateTime $fromDate)
    {
        if (!isset($this->rota[$fromDate->format('Y-m-d')])) {
            throw new InvalidArgumentException('Specified From date ' . $fromDate->format('Y-m-d') . ' is invalid');
        }

        if (!isset($this->rota[$toDate->format('Y-m-d')])) {
            throw new InvalidArgumentException('Specified To date ' . $toDate->format('Y-m-d') . ' is invalid');
        }

        $fromMember = $this->rota[$fromDate->format('Y-m-d')];
        $toMember = $this->rota[$toDate->format('Y-m-d')];

        $this->rota[$fromDate->format('Y-m-d')] = $toMember;
        $this->rota[$toDate->format('Y-m-d')] = $fromMember;

        return $this->rota;
    }

    protected function getDateKey(DateTime $date)
    {
        return $this->dateValidator->getNextValidDate($date)->format('Y-m-d');
    }

    protected function getMemberAfterDate(DateTime $date)
    {
        return $this->memberList->getMemberAfter($this->rota[$this->getDateKey($date)]);
    }
}
