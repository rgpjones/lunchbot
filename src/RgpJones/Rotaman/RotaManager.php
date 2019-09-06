<?php
namespace RgpJones\Rotaman;

use DateTime;
use RgpJones\Rotaman\Storage\Storage;

class RotaManager
{
    private $storage;

    private $rota;

    private $dateValidator;

    private $memberList;

    private $paymentCalendar;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->load();

        $rota = isset($data['rota']) ? $data['rota'] : [];
        $cancelledDates = isset($data['cancelledDates']) ? $data['cancelledDates'] : [];
        $members = isset($data['members']) ? $data['members'] : [];
        $paymentCalendar = isset($data['paymentCalendar']) ? $data['paymentCalendar'] : [];

        // Maintains members in order as they are in current rota
        $this->memberList = new MemberList($members);
        $this->dateValidator = new DateValidator($cancelledDates);
        $this->rota = new Rota($this->memberList, $this->dateValidator, $rota);
        $this->paymentCalendar =  new PaymentCalendar($paymentCalendar);
    }

    public function __destruct()
    {
        if (!empty($this->storage)) {
            $this->storage->save([
                'members' => $this->memberList->getMembers(),
                'cancelledDates' => $this->dateValidator->getCancelledDates(),
                'rota' => $this->rota->getRota(),
                'paymentCalendar' => $this->paymentCalendar->getPaymentCalendar(),
            ]);
        }
    }

    public function addMember($name)
    {
        $this->memberList->addMember($name);
    }

    public function removeMember($name)
    {
        $this->memberList->removeMember($name);
    }

    public function getMembers()
    {
        return $this->memberList->getMembers();
    }

    public function generateRota(DateTime $date, $days)
    {
        return $this->rota->generate($date, $days);
    }

    public function getMemberForDate(DateTime $date)
    {
        return $this->rota->getMemberForDate($date);
    }

    public function setMemberForDate(DateTime $date, $member)
    {
        return $this->rota->setMemberForDate($date, $member);
    }

    public function skipMemberForDate(DateTime $date)
    {
        return $this->rota->skipMemberForDate($date);
    }

    public function cancelOnDate(DateTime $date)
    {
        return $this->rota->cancelOnDate($date);
    }

    public function swapMember(DateTime $date, $toName = null, $fromName = null)
    {
        return $this->rota->swapMember($date, $toName, $fromName);
    }

    public function getAmountMemberPaidForDate($date, $member)
    {
        return $this->paymentCalendar->getAmountMemberPaidForDate($date, $member);
    }

    public function memberPaidForDate(DateTime $date, $member, $amount)
    {
        return $this->paymentCalendar->memberPaidForDate($date, $member, $amount);
    }

    public function getWhoPaidForDate(DateTime $date)
    {
        return $this->paymentCalendar->getWhoPaidForDate($date);
    }
}
