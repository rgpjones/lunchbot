<?php

namespace spec\RgpJones\Rotabot;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RgpJones\Rotabot\MemberList;
use RgpJones\Rotabot\DateValidator;

class RotaSpec extends ObjectBehavior
{
    function it_generates_rota()
    {
        $clubbers = ['Dave', 'Alice', 'Bob', 'Chris'];
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Chris',
            '2010-01-05' => 'Bob',
        ];
        $expectedRota = $currentRota + [
            '2010-01-06' => 'Dave',
            '2010-01-07' => 'Alice',
            '2010-01-08' => 'Chris',
            '2010-01-11' => 'Bob',
            '2010-01-12' => 'Dave',
            '2010-01-13' => 'Alice',
            '2010-01-14' => 'Chris',
        ];

        $this->beConstructedWith(new MemberList($clubbers), new DateValidator(), $currentRota);

        $this->generate(new \DateTime('2010-01-01'), 10)->shouldReturn($expectedRota);
    }

    function it_returns_member_for_date()
    {
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
        ];

        $this->beConstructedWith(
            new MemberList(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            $currentRota
        );

        $this->getMemberForDate(new \DateTime('2010-01-05'))->shouldReturn('Chris');
    }

    function it_skips_current_member_and_realigns_rota()
    {
        $this->beConstructedWith(
            new MemberList(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
            ]
        );

        $this->skipMemberForDate(new \DateTime('2010-01-04'));
        $this->getRota()->shouldReturn([
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Chris',
            '2010-01-05' => 'Dave',
            '2010-01-06' => 'Alice',
        ]);
    }

    function it_cancels_rota_on_date_and_realigns()
    {
        $date = new \DateTime('2010-01-04');

        $this->beConstructedWith(
            new MemberList(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
            ]
        );

        $this->cancelOnDate($date);
        $this->getRota()->shouldReturn(
            [
                '2010-01-01' => 'Alice',
                '2010-01-05' => 'Bob',
                '2010-01-06' => 'Chris',
                '2010-01-07' => 'Dave',
            ]
        );
    }

    function it_swaps_members()
    {
        $this->beConstructedWith(
            new MemberList(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave',
            ]
        );

        $this->swapMember(new \DateTime('2010-01-04'))
            ->shouldReturn([
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Chris',
                '2010-01-05' => 'Bob',
                '2010-01-06' => 'Dave',
            ]);

        $this->swapMember(new \DateTime('2010-01-01'), 'Bob')
            ->shouldReturn([
                '2010-01-01' => 'Bob',
                '2010-01-04' => 'Chris',
                '2010-01-05' => 'Alice',
                '2010-01-06' => 'Dave',
            ]);

        $this->swapMember(new \DateTime('2010-01-01'), 'Chris', 'Dave')
            ->shouldReturn([
                '2010-01-01' => 'Bob',
                '2010-01-04' => 'Dave',
                '2010-01-05' => 'Alice',
                '2010-01-06' => 'Chris',
            ]);
    }

    function it_returns_rota_upto_and_from_specified_date()
    {
        $uptoRota = [
            '2009-12-29' => 'Bob',
            '2009-12-30' => 'Chris',
            '2009-12-31' => 'Dave',
        ];
        $fromRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
            '2010-01-06' => 'Dave',
        ];
        $this->beConstructedWith(
            new MemberList(['Alice', 'Bob', 'Chris', 'Dave']),
            new DateValidator(),
            $uptoRota + $fromRota
        );

        $this->getRotaUptoDate(new \DateTime('2010-01-01'))->shouldReturn($uptoRota);
        $this->getRotaFromDate(new \DateTime('2010-01-01'))->shouldReturn($fromRota);
    }
}
