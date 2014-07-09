<?php

namespace spec\Rawebone\Injector;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rawebone\Injector\CallableType;

class CallableTypeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Injector\CallableType');
    }

	function it_should_return_none_if_not_callable()
	{
		$this->type("a")->shouldReturn(CallableType::TYPE_NONE);
	}

	function it_should_return_function_if_function_or_closure()
	{
		$this->type("strtolower")->shouldReturn(CallableType::TYPE_FUNCTION);
		$this->type(function () {})->shouldReturn(CallableType::TYPE_FUNCTION);
	}

	function it_should_return_array_if_array_passed()
	{
		$this->type(array(__CLASS__, __FUNCTION__))->shouldReturn(CallableType::TYPE_ARRAY);
	}

	function it_should_return_invokable_if_an_invokeable_passed()
	{
		$this->type(new CallableTypeFixture_Invokeable())->shouldReturn(CallableType::TYPE_INVOKABLE);
	}

	function it_should_return_constructable_if_an_class_passed()
	{
		$this->type(__NAMESPACE__ . "\\CallableTypeFixture_Constructable")->shouldReturn(CallableType::TYPE_CONSTRUCTABLE);
	}
}

class CallableTypeFixture_Invokeable
{
	public function __invoke()
	{

	}
}

class CallableTypeFixture_Constructable
{
	public function __construct()
	{

	}
}
