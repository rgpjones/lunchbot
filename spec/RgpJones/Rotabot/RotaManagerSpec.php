<?php

namespace spec\RgpJones\Rotabot;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use RgpJones\Rotabot\Storage\NullStorage as Storage;

class RotaManagerSpec extends ObjectBehavior
{
    function it_returns_members(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];

        $storage->load()->willReturn([]);
        $storage->save(
            ['members' => $members, 'cancelledDates' => [], 'rota' => [], 'paymentCalendar' => []]
        )->willReturn(null);

        $this->beConstructedWith($storage, new \DateTime());

        $this->addMember('Alice');
        $this->addMember('Bob');
        $this->addMember('Chris');
        $this->addMember('Dave');

        $this->getMembers()->shouldReturn($members);
    }

    function it_removes_members(Storage $storage)
    {
        $storage->load()->willReturn(['members' => ['Alice', 'Bob', 'Chris', 'Dave']]);
        $storage->save(
            ['members' => ['Alice', 'Chris', 'Dave'], 'cancelledDates' => [], 'rota' => [], 'paymentCalendar' => []]
        )->willReturn(null);

        $this->beConstructedWith($storage, new \DateTime());

        $this->removeMember('Bob')->shouldReturn(null);

        $this->getMembers()->shouldReturn(['Alice', 'Chris', 'Dave']);
    }

    function it_returns_rota(Storage $storage)
    {
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris'
        ];

        $storage->load()->willReturn(['members' => ['Dave', 'Alice', 'Bob', 'Chris']]);
        $storage->save(
            ['members' => ['Chris', 'Dave', 'Alice', 'Bob'], 'cancelledDates' => [], 'rota' => $expectedRota, 'paymentCalendar' => []]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->generateRota(new \DateTime('2010-01-01'), 3)->shouldReturn($expectedRota);
    }

    function it_maintains_order_of_members_in_rota(Storage $storage)
    {
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Dave',
            '2010-01-05' => 'Chris',
            '2010-01-06' => 'Alice',
            '2010-01-07' => 'Bob',
            '2010-01-08' => 'Chris',
            '2010-01-11' => 'Dave',
            '2010-01-12' => 'Elaine',
            '2010-01-13' => 'Alice',
        ];
        $expectedRota = [
            '2010-01-14' => 'Bob',
            '2010-01-15' => 'Chris',
            '2010-01-18' => 'Dave',
            '2010-01-19' => 'Elaine',
            '2010-01-20' => 'Alice',
        ];

        $storage->load()->willReturn(['members' => ['Alice', 'Bob', 'Chris', 'Dave', 'Elaine'], 'rota' => $currentRota]);
        $storage->save(
            [
                'members' => ['Alice', 'Bob', 'Chris', 'Dave', 'Elaine'],
                'cancelledDates' => [],
                'rota' => ($currentRota + $expectedRota),
                'paymentCalendar' => []
            ]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->generateRota(new \DateTime('2010-01-14'), 5)->shouldReturn($expectedRota);
    }

    function it_excludes_members_removed_from_rota(Storage $storage)
    {
        $currentRota = [
            '2010-01-01' => 'Bob',
            '2010-01-04' => 'Chris',
            '2010-01-05' => 'Dave',
            '2010-01-06' => 'Chris',
            '2010-01-07' => 'Dave',
            '2010-01-08' => 'Bob',
            '2010-01-11' => 'Elaine',
            '2010-01-12' => 'Chris',
            '2010-01-13' => 'Dave',
        ];
        $expectedRota = [
            '2010-01-14' => 'Bob',
            '2010-01-15' => 'Chris',
            '2010-01-18' => 'Dave',
            '2010-01-19' => 'Bob',
            '2010-01-20' => 'Chris',
        ];

        $storage->load()->willReturn(['members' => ['Dave', 'Bob', 'Chris'], 'rota' => $currentRota]);
        $storage->save(
            [
                'members' => ['Chris', 'Dave', 'Bob'],
                'cancelledDates' => [],
                'rota' => ($currentRota + $expectedRota),
                'paymentCalendar' => []
            ]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->generateRota(new \DateTime('2010-01-14'), 5)->shouldReturn($expectedRota);
    }

    function it_returns_member(Storage $storage)
    {
        $members = ['Dave', 'Alice', 'Bob', 'Chris'];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris'
        ];

        $storage->load()->willReturn(
            ['members' => $members, 'cancelledDates' => [], 'rota' => $expectedRota]
        );
        $storage->save(
            [
                'members' => $members,
                'cancelledDates' => [],
                'rota' => $expectedRota,
                'paymentCalendar' => []
            ]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getMemberForDate(new \DateTime('2010-01-04'))->shouldReturn('Bob');
    }

    function it_skips_member_for_date(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
            '2010-01-06' => 'Dave',
        ];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Dave',
            '2010-01-06' => 'Alice',
        ];

        $storage->load()->willReturn(['members' => $members, 'cancelledDates' => [], 'rota' => $currentRota]);
        $storage->save(
            ['members' => $members, 'cancelledDates' => [], 'rota' => $expectedRota, 'paymentCalendar' => []]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->skipMemberForDate(new \DateTime('2010-01-05'));
    }

    function it_cancels_rota_on_date(Storage $storage)
    {
        $members = ['Alice', 'Bob', 'Chris', 'Dave'];
        $currentRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Chris',
            '2010-01-06' => 'Dave',
        ];
        $expectedRota = [
            '2010-01-01' => 'Alice',
            '2010-01-04' => 'Bob',
            '2010-01-06' => 'Chris',
            '2010-01-07' => 'Dave',
        ];

        $storage->load()->willReturn(['members' => $members, 'cancelledDates' => [], 'rota' => $currentRota]);
        $storage->save(
            [
                'members' => $members,
                'cancelledDates' => ['2010-01-05'],
                'rota' => $expectedRota,
                'paymentCalendar' => []
            ]
        )->willReturn(null);

        $this->beConstructedWith($storage);

        $this->cancelOnDate(new \DateTime('2010-01-05'));
    }

    function it_swaps_members_on_dates_specified(Storage $storage)
    {
        $updatedRota = [
            '2010-01-01' => 'Chris',
            '2010-01-04' => 'Bob',
            '2010-01-05' => 'Alice',
            '2010-01-06' => 'Dave',
        ];

        $storage->load()->willReturn([
            'members' => ['Alice', 'Bob', 'Chris', 'Dave'],
            'cancelledDates' => [],
            'rota' => [
                '2010-01-01' => 'Alice',
                '2010-01-04' => 'Bob',
                '2010-01-05' => 'Chris',
                '2010-01-06' => 'Dave'
            ]
        ]);
        $storage->save([
            'members' => ['Chris', 'Bob', 'Alice', 'Dave'],
            'cancelledDates' => [],
            'rota' => $updatedRota,
            'paymentCalendar' => []
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->swapMember(new \DateTime('2010-01-01'), 'Chris')->shouldReturn($updatedRota);
    }

    function it_marks_member_as_paid(Storage $storage)
    {
        $storage->load()->willReturn([
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ]);

        $storage->save([
            'members' => [],
            'cancelledDates' => [],
            'rota' => [],
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                    'Bob' => (float) 20.00,
                ]
            ]
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->memberPaidForDate(
            new \DateTime('2010-03-22'),
            'Bob',
            (float) 20.00
        )->shouldReturn(true);
    }

    function it_returns_amount_member_as_paid(Storage $storage)
    {
        $storage->load()->willReturn([
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ]);

        $storage->save([
            'members' => [],
            'cancelledDates' => [],
            'rota' => [],
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getAmountMemberPaidForDate(
            new \DateTime('2010-03-16'),
            'Alice'
        )->shouldReturn(20.00);
    }

    function it_returns_who_paid_for_date(Storage $storage)
    {
        $storage->load()->willReturn([
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ]);

        $storage->save([
            'members' => [],
            'cancelledDates' => [],
            'rota' => [],
            'paymentCalendar' => [
                '2010-03' => [
                    'Alice' => (float) 20.00,
                ]
            ]
        ])->willReturn(null);

        $this->beConstructedWith($storage);

        $this->getWhoPaidForDate(new \DateTime('2010-03-16'))->shouldReturn(['Alice']);
    }
}
