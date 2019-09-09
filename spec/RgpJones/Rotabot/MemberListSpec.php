<?php

namespace spec\RgpJones\Rotabot;

use PhpSpec\ObjectBehavior;

class MemberListSpec extends ObjectBehavior
{
    function it_should_return_next_member()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->nextMember()->shouldReturn('Bob');
        $this->nextMember()->shouldReturn('Chris');
        $this->nextMember()->shouldReturn('Dave');
        $this->nextMember()->shouldReturn('Alice');
    }

    function it_should_return_prev_member()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->previousMember()->shouldReturn('Dave');
        $this->previousMember()->shouldReturn('Chris');
        $this->previousMember()->shouldReturn('Bob');
        $this->previousMember()->shouldReturn('Alice');
    }

    function it_sets_current_member()
    {
        $this->beConstructedWith(['Dave', 'Alice', 'Bob', 'Chris']);
        $this->setCurrentMember('Alice');
        $this->getMembers()->shouldReturn(['Alice', 'Bob', 'Chris', 'Dave']);
        $this->setCurrentMember('Chris');
        $this->getMembers()->shouldReturn(['Chris', 'Bob', 'Dave', 'Alice']);
        $this->setCurrentMember('Bob');
        $this->getMembers()->shouldReturn(['Bob', 'Dave', 'Alice', 'Chris']);
    }

    function it_adds_new_member_to_list()
    {
        $this->beConstructedWith(['Alice', 'Bob']);
        $this->addMember('Chris');
        $this->getMembers()->shouldReturn(['Alice', 'Bob', 'Chris']);
    }

    function it_removes_member_from_list()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris']);
        $this->removeMember('Bob');
        $this->getMembers()->shouldReturn(['Alice', 'Chris']);
    }

    function it_returns_member_after_name()
    {
        $this->beConstructedWith(['Alice', 'Bob', 'Chris', 'Dave', 'Elaine']);
        $this->getMemberAfter('Chris')->shouldReturn('Dave');
    }
}
