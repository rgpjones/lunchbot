<?php
namespace RgpJones\Rotabot;

use DateTime;
use RgpJones\Rotabot\Storage\Storage;

class RotaManager
{
    private $storage;

    private $rota;

    private $dateValidator;

    private $memberList;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $data = $storage->load();

        $rota = isset($data['rota']) ? $data['rota'] : [];
        $cancelledDates = isset($data['cancelledDates']) ? $data['cancelledDates'] : [];
        $members = isset($data['members']) ? $data['members'] : [];

        // Maintains members in order as they are in current rota
        $this->memberList = new MemberList($members);
        $this->dateValidator = new DateValidator($cancelledDates);
        $this->rota = new Rota($this->memberList, $this->dateValidator, $rota);
    }

    public function __destruct()
    {
        if (!empty($this->storage)) {
            $this->storage->save([
                'members' => $this->memberList->getMembers(),
                'cancelledDates' => $this->dateValidator->getCancelledDates(),
                'rota' => $this->rota->getRota(),
            ]);
        }
    }

    public function addMember($name)
    {
        $this->memberList->addMember($this->stripAt($name));
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

    public function getMemberForToday()
    {
        $today = new DateTime();
        return $this->dateValidator->isDateValid($today)
            ? $this->getMemberForDate($today)
            : null;
    }

    public function getMemberForDate(DateTime $date)
    {
        return $this->rota->getMemberForDate($date);
    }

    public function setMemberForDate(DateTime $date, $member)
    {
        $this->rota->setMemberForDate($date, $member);
        return null;
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

    protected function stripAt($name): string
    {
        return str_replace('@', '', $name);
    }
}
