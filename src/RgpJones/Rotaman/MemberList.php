<?php
namespace RgpJones\Rotaman;

class MemberList
{
    private $members = [];

    public function __construct(array $members)
    {
        $this->members = $members;
    }

    public function hasMember($name)
    {
        return in_array($name, $this->members);
    }

    public function addMember($name)
    {
        if (in_array($name, $this->members)) {
            throw new \InvalidArgumentException("'{$name}' is already a member of the rota");
        }

        $this->members[] = $name;
    }

    public function removeMember($name)
    {
        if (!in_array($name, $this->members)) {
            throw new \InvalidArgumentException("'{$name}' is not a member of the rota");
        }

        unset($this->members[array_search($name, $this->members)]);

        $this->members = array_values($this->members);
    }

    public function getMembers()
    {
        return $this->members;
    }

    public function setMembers($members)
    {
        $this->members = $members;
    }

    public function nextMember()
    {
        $this->members[] = array_shift($this->members);

        return $this->members[0];
    }

    public function previousMember()
    {
        array_unshift($this->members, array_pop($this->members));

        return $this->members[0];
    }

    public function setCurrentMember($member)
    {
        if (!in_array($member, $this->members)) {
            throw new \InvalidArgumentException("{$member} is not in the members list");
        }
        unset($this->members[array_search($member, $this->members)]);
        $this->nextMember();
        array_unshift($this->members, $member);
    }

    public function getMemberAfter($name)
    {
        if (!in_array($name, $this->members)) {
            throw new \RuntimeException("Member {$name} not found");
        }

        $index = array_search($name, $this->members) + 1;
        if ($index >= count($this->members)) {
            $index = 0;
        }

        return $this->members[$index];
    }
}
