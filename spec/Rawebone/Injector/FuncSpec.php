<?php

namespace spec\Rawebone\Injector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FuncSpec extends ObjectBehavior
{
    function it_should_throw_an_exception_if_invalid_argument_passed()
    {
        try {
            // This will fail as it is an invalid function
            $this->beConstructedWith("");
        } catch (\ReflectionException $ex) {
        }

        $this->shouldHaveType('Rawebone\Injector\Func');
    }

    function it_should_return_a_reflection_for_a_function()
    {
        $this->beConstructedWith(__NAMESPACE__ . '\test');
        $this->reflection()
             ->shouldReturnAnInstanceOf('ReflectionFunction');
    }

    function it_should_return_a_reflection_for_a_closure()
    {
        $this->beConstructedWith(function () {});
        $this->reflection()
            ->shouldReturnAnInstanceOf('ReflectionFunction');
    }

    function it_should_return_a_reflection_for_an_invokable_class()
    {
        $this->beConstructedWith(new Invokable());
        $this->reflection()
            ->shouldReturnAnInstanceOf('ReflectionMethod');
    }

    function it_should_return_a_reflection_for_an_array_callback()
    {
        $this->beConstructedWith(array(new General(), "method"));
        $this->reflection()
            ->shouldReturnAnInstanceOf('ReflectionMethod');
    }

    function it_should_return_a_reflection_for_a_constructor()
    {
        $this->beConstructedWith(__NAMESPACE__ . '\Constructable');
        $this->reflection()
            ->shouldReturnAnInstanceOf('ReflectionMethod');
    }

    function it_should_invoke_for_a_function()
    {
        $this->beConstructedWith(__NAMESPACE__ . '\test');
        $this->invoke(array("ABC"))->shouldReturn("ABC");
    }

    function it_should_invoke_for_a_closure()
    {
        $this->beConstructedWith(function ($abc) { return $abc; });
        $this->invoke(array("ABC"))->shouldReturn("ABC");
    }

    function it_should_invoke_for_an_invokable_class()
    {
        $this->beConstructedWith(new Invokable());
        $this->invoke(array("ABC"))->shouldReturn("ABC");
    }

    function it_should_invoke_for_an_array_callback()
    {
        $this->beConstructedWith(array(new General(), "method"));
        $this->invoke(array("ABC"))->shouldReturn("ABC");
    }

    function it_should_invoke_and_return_a_new_instace_for_a_constructor()
    {
        $cls = __NAMESPACE__ . '\Constructable';
        $this->beConstructedWith($cls);
        $this->invoke(array("ABC"))->shouldReturnAnInstanceOf($cls);
    }
}

function test($abc)
{
    return $abc;
}

class Invokable
{
    public function __invoke($abc)
    {
        return $abc;
    }
}

class General
{
    public function method($abc)
    {
        return $abc;
    }
}

class Constructable
{
    public function __construct($abc)
    {
    }
}
