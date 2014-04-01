<?php

namespace spec\Rawebone\Injector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TypeCheckerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Injector\TypeChecker');
    }

    function it_should_validate_that_a_name_matches_an_object()
    {
        $this->validate('stdClass', new \stdClass())->shouldReturn(true);
    }

    function it_should_validate_that_an_array_matches_an_array()
    {
        $this->validate('array', array())->shouldReturn(true);
    }
}
