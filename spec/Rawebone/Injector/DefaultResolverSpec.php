<?php

namespace spec\Rawebone\Injector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultResolverSpec extends ObjectBehavior
{
    function let()
    {
        require_once __DIR__ . "/DefaultResolverSpecFixtures.php";
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Injector\DefaultResolver');
    }

    function it_should_return_a_func_for_a_function_service()
    {
        $this->resolve("serviceA")->shouldReturnAnInstanceOf('Rawebone\Injector\Func');
    }

    function it_should_return_a_func_for_a_class_service()
    {
        $this->resolve("serviceB")->shouldReturnAnInstanceOf('Rawebone\Injector\Func');
    }

}
