<?php

namespace spec\RgpJones\Rotabot;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DateValidatorSpec extends ObjectBehavior
{
    function it_returns_specified_date_as_valid_date()
    {
        $date = new \DateTime('2010-01-01');
        $this->getNextValidDate($date)->shouldReturn($date);
    }

    function it_returns_false_if_date_is_invalid()
    {
        $this->isDateValid(new \DateTime('2010-01-02'))->shouldReturn(false);
    }

    function it_returns_true_if_date_is_valid()
    {
        $this->isDateValid(new \DateTime('2010-01-05'))->shouldReturn(true);
    }

    function it_returns_monday_when_passed_weekend_date()
    {
        $this->getNextValidDate(new \DateTime('2010-01-02'))->shouldBeLike(new \DateTime('2010-01-04'));
        $this->getNextValidDate(new \DateTime('2010-01-03'))->shouldBeLike(new \DateTime('2010-01-04'));
    }

    function it_returns_date_after_cancelled_date()
    {
        $this->beConstructedWith(array('2010-01-05'));

        $this->getNextValidDate(new \DateTime('2010-01-05'))->shouldBeLike(new \DateTime('2010-01-06'));
    }

    function it_adds_cancelled_date_to_list()
    {
        $this->addCancelledDate(new \DateTime('2010-01-05'))->shouldReturn(true);
        $this->getCancelledDates()->shouldReturn(['2010-01-05']);
    }

    function it_returns_previous_valid_date()
    {
        $this->getPreviousValidDate(new \DateTime('2010-01-02'))->shouldBeLike(new \DateTime('2010-01-01'));
    }
}
