<?php

namespace spec\RgpJones\Rotabot;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PaymentCalendarSpec extends ObjectBehavior
{
    function it_returns_current_payment_calendar()
    {
        $currentCalendar = [
            '2012-03' => [
                'Alice' => 15.00,
                'Bob' => 15.00
            ]
        ];

        $this->beConstructedWith($currentCalendar);

        $this->getPaymentCalendar()->shouldReturn($currentCalendar);
    }

    function it_returns_amount_member_paid_for_month()
    {
        $currentCalendar = [
            '2011-08' => [
                'Bob' => 15.00
            ]
        ];

        $this->beConstructedWith($currentCalendar);

        $this->getAmountMemberPaidForDate(new \DateTime('2011-08-31'), 'Bob')->shouldReturn(15.00);
    }

    function it_returns_payment_calendar_with_new_paid_member()
    {
        $currentCalendar = [
            '2012-06' => [
                'Alice' => 15.00,
                'Bob' => 15.00
            ]
        ];

        $newCalendar = [
            '2012-06' => [
                'Alice' => 15.00,
                'Bob' => 15.00,
                'Chris' => 15.00,
            ]
        ];

        $this->beConstructedWith($currentCalendar);

        $this->memberPaidForDate(new \DateTime('2012-06-15'), 'Chris', 15.00);

        $this->getPaymentCalendar()->shouldReturn($newCalendar);
    }

    function it_adds_new_payment_to_existing_payment_for_month()
    {
        $currentCalendar = [
            '2012-07' => [
                'Alice' => 15.00,
                'Bob' => 9.32
            ]
        ];

        $newCalendar = [
            '2012-07' => [
                'Alice' => 15.00,
                'Bob' => 15.00,
            ]
        ];

        $this->beConstructedWith($currentCalendar);

        $this->memberPaidForDate(new \DateTime('2012-07-23'), 'Bob', 5.68);

        $this->getPaymentCalendar()->shouldReturn($newCalendar);
    }

    function it_returns_who_paid_for_date()
    {
        $calendar = [
            '2008-05' => [
                'Alice' => 20.00,
                'Bob' => 20.00
            ],
            '2008-06' => [
                'Alice' => 20.00,
                'Bob' => 15.00
            ],
            '2008-07' => [
                'Alice' => 5.00,
                'Bob' => 10.00
            ]
        ];

        $this->beConstructedWith($calendar);

        $this->getWhoPaidForDate(new \DateTime('2008-06-16'))->shouldReturn(['Alice', 'Bob']);
    }
}
