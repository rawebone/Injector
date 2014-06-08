<?php

namespace spec\Rawebone\Injector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rawebone\Injector\ResolverInterface;

class InjectorSpec extends ObjectBehavior
{
    function let()
    {
        if (!class_exists("serviceA")) {
            require_once __DIR__ . "/DefaultResolverSpecFixtures.php";
        }
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Injector\Injector');
    }

    function it_should_allow_for_a_custom_resolver_to_be_used(ResolverInterface $resolver)
    {
        $this->resolver($resolver);
    }

    function it_should_return_the_arguments_for_an_injectable()
    {
        $args = $this->argsFor(function ($serviceA) {});
        $args["serviceA"]->shouldBeAnInstanceOf('stdClass');
    }

    function it_should_return_a_service()
    {
        $this->service("serviceA")->shouldReturnAnInstanceOf('stdClass');
    }

    function it_should_inject()
    {
        $this->inject(function ($serviceA) { return $serviceA; })
             ->shouldReturnAnInstanceOf('stdClass');
    }
}
